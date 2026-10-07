<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DirectorateConfig;
use App\Models\DirectorateRow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataDirektoratController extends Controller
{
    /**
     * Get or initialize all 42 menu items grouped by sub-module.
     */
    public static function getMenuStructure()
    {
        return config('data_direktorat.sub_modules', []);
    }

    /**
     * Helper to find item metadata across all sub-modules.
     */
    public static function findItemMeta(string $itemKey)
    {
        $subModules = self::getMenuStructure();
        foreach ($subModules as $subName => $items) {
            if (isset($items[$itemKey])) {
                $meta = $items[$itemKey];
                $meta['sub_module'] = $subName;
                $meta['item_key'] = $itemKey;
                return $meta;
            }
        }
        return null;
    }

    /**
     * Fetch or create DirectorateConfig for a key.
     */
    private function getConfig(string $itemKey): DirectorateConfig
    {
        $meta = self::findItemMeta($itemKey);
        if (!$meta) {
            abort(404, "Modul data direktorat '{$itemKey}' tidak ditemukan.");
        }

        $titleClean = str_replace(['—', '→'], '-', $meta['title']);

        $config = DirectorateConfig::firstOrCreate(
            ['item_key' => $itemKey],
            [
                'sub_module' => $meta['sub_module'],
                'group_prefix' => null,
                'title' => $titleClean,
                'instructions' => config('data_direktorat.default_instructions'),
                'columns' => $meta['columns'],
            ]
        );

        if ($config->title !== $titleClean || $config->sub_module !== $meta['sub_module'] || $config->group_prefix !== null) {
            $config->title = $titleClean;
            $config->sub_module = $meta['sub_module'];
            $config->group_prefix = null;
            $config->save();
        }

        return $config;
    }

    /**
     * Render the generic page for any of the 42 items.
     */
    public function show(string $item_key)
    {
        $config = $this->getConfig($item_key);
        $meta = self::findItemMeta($item_key);
        $rows = DirectorateRow::where('item_key', $item_key)->orderBy('row_order')->orderBy('id')->get();
        $menuStructure = self::getMenuStructure();

        return view('admin.data-direktorat.index', compact('config', 'meta', 'rows', 'menuStructure', 'item_key'));
    }

    /**
     * Add manual data row.
     */
    public function storeRow(Request $request, string $item_key)
    {
        $config = $this->getConfig($item_key);
        $columns = $config->columns;
        $rowVal = [];

        foreach ($columns as $col) {
            $rowVal[$col] = $request->input('col_' . md5($col), '');
        }

        $maxOrder = DirectorateRow::where('item_key', $item_key)->max('row_order') ?? 0;

        DirectorateRow::create([
            'item_key' => $item_key,
            'data' => $rowVal,
            'row_order' => $maxOrder + 1,
        ]);

        return redirect()->route('admin.data-direktorat.show', $item_key)
            ->with('status', 'Baris data baru berhasil ditambahkan.');
    }

    /**
     * Update manual data row.
     */
    public function updateRow(Request $request, string $item_key, $id)
    {
        $config = $this->getConfig($item_key);
        $row = DirectorateRow::where('item_key', $item_key)->findOrFail($id);
        $columns = $config->columns;
        $rowVal = [];

        foreach ($columns as $col) {
            $rowVal[$col] = $request->input('col_' . md5($col), '');
        }

        $row->update(['data' => $rowVal]);

        return redirect()->route('admin.data-direktorat.show', $item_key)
            ->with('status', 'Baris data berhasil diperbarui.');
    }

    /**
     * Delete a single data row.
     */
    public function destroyRow(string $item_key, $id)
    {
        $row = DirectorateRow::where('item_key', $item_key)->findOrFail($id);
        $row->delete();

        return redirect()->route('admin.data-direktorat.show', $item_key)
            ->with('status', 'Baris data berhasil dihapus.');
    }

    /**
     * Clear all data rows for an item.
     */
    public function clearRows(string $item_key)
    {
        DirectorateRow::where('item_key', $item_key)->delete();

        return redirect()->route('admin.data-direktorat.show', $item_key)
            ->with('status', 'Semua data tabel berhasil dikosongkan.');
    }

    /**
     * Download active Excel template (Row 1: Title, Row 3: Instructions, Row 6: Column headers).
     */
    public function downloadTemplate(string $item_key)
    {
        $config = $this->getConfig($item_key);
        $spreadsheet = $this->buildSpreadsheet($config, []);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_' . $item_key . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Change Excel template (Upload new template file -> Column structure changes dynamically).
     */
    public function changeTemplate(Request $request, string $item_key)
    {
        $request->validate([
            'template_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $config = $this->getConfig($item_key);
        $file = $request->file('template_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        // Check Row 1 for title if uploaded
        $row1Val = trim((string) $sheet->getCell('A1')->getValue());
        if (str_starts_with(strtoupper($row1Val), 'TEMPLATE UPLOAD:')) {
            $newTitle = trim(substr($row1Val, strlen('TEMPLATE UPLOAD:')));
            if (!empty($newTitle)) {
                $config->title = str_replace(['—', '→'], '-', $newTitle);
            }
        }

        // Check Row 3 for instructions
        $row3Val = trim((string) $sheet->getCell('A3')->getValue());
        if (str_starts_with(strtoupper($row3Val), 'PETUNJUK:')) {
            $newInstruct = trim(substr($row3Val, strlen('PETUNJUK:')));
            if (!empty($newInstruct)) {
                $config->instructions = $newInstruct;
            }
        }

        // Detect Header Row (Check Row 6 first, or fallback to first non-empty row)
        $headerRowIndex = 6;
        $extractedHeaders = [];

        $highestColumn = $sheet->getHighestDataColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        // Try reading Row 6
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $val = trim((string) $sheet->getCellByColumnAndRow($col, 6)->getValue());
            if ($val !== '') {
                $extractedHeaders[] = $val;
            }
        }

        // Fallback search if Row 6 has no values
        if (empty($extractedHeaders)) {
            for ($r = 1; $r <= 10; $r++) {
                $temp = [];
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $val = trim((string) $sheet->getCellByColumnAndRow($col, $r)->getValue());
                    if ($val !== '') {
                        $temp[] = $val;
                    }
                }
                if (count($temp) >= 2) {
                    $extractedHeaders = $temp;
                    $headerRowIndex = $r;
                    break;
                }
            }
        }

        if (empty($extractedHeaders)) {
            return redirect()->route('admin.data-direktorat.show', $item_key)
                ->with('error', 'Gagal membaca header kolom dari file template Excel yang diunggah.');
        }

        // Save new columns
        $config->columns = array_values($extractedHeaders);

        // Store custom template file
        $path = $file->storeAs('public/templates', $item_key . '_custom.xlsx');
        $config->custom_template_path = $path;
        $config->save();

        return redirect()->route('admin.data-direktorat.show', $item_key)
            ->with('status', 'Template Excel berhasil diperbarui! Struktur kolom tabel telah disesuaikan.');
    }

    /**
     * Backup current item data to Excel file matching active template structure.
     */
    public function backupData(string $item_key)
    {
        $config = $this->getConfig($item_key);
        $rows = DirectorateRow::where('item_key', $item_key)->orderBy('row_order')->orderBy('id')->get();
        $spreadsheet = $this->buildSpreadsheet($config, $rows);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Backup_' . $item_key . '_' . date('Y-m-d_H-i') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import Excel file -> UPSERT Logic:
     * Key = first column of table (e.g., "Tahun").
     * Existing key = old row updated with new data.
     * New key = appended to table.
     */
    public function importExcel(Request $request, string $item_key)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $config = $this->getConfig($item_key);
        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        // Determine header row (Default row 6 or autodetect)
        $headerRow = 6;
        $headers = [];

        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $val = trim((string) $sheet->getCellByColumnAndRow($col, 6)->getValue());
            if ($val !== '') {
                $headers[$col] = $val;
            }
        }

        // Fallback scan if row 6 was empty
        if (empty($headers)) {
            for ($r = 1; $r <= 10; $r++) {
                $temp = [];
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $val = trim((string) $sheet->getCellByColumnAndRow($col, $r)->getValue());
                    if ($val !== '') {
                        $temp[$col] = $val;
                    }
                }
                if (count($temp) >= 2) {
                    $headers = $temp;
                    $headerRow = $r;
                    break;
                }
            }
        }

        if (empty($headers)) {
            return redirect()->route('admin.data-direktorat.show', $item_key)
                ->with('error', 'Gagal menemukan header tabel pada file Excel. Pastikan header berada di baris 6.');
        }

        // Update config columns if header modified
        $headerList = array_values($headers);
        $config->columns = $headerList;
        $config->save();

        // Primary key column is first column (index 0)
        $keyColumnName = $headerList[0];

        // Map existing rows by primary key value
        $existingRows = DirectorateRow::where('item_key', $item_key)->get();
        $existingMap = [];
        foreach ($existingRows as $row) {
            $keyVal = trim((string)($row->data[$keyColumnName] ?? ''));
            if ($keyVal !== '') {
                $existingMap[$keyVal] = $row;
            }
        }

        $insertedCount = 0;
        $updatedCount = 0;
        $startRow = $headerRow + 1;
        $maxOrder = DirectorateRow::where('item_key', $item_key)->max('row_order') ?? 0;

        for ($r = $startRow; $r <= $highestRow; $r++) {
            $rowVal = [];
            $hasValue = false;

            foreach ($headers as $colIdx => $colName) {
                $cellValue = $sheet->getCellByColumnAndRow($colIdx, $r)->getFormattedValue();
                $cellValueStr = trim((string) $cellValue);
                if ($cellValueStr !== '') {
                    $hasValue = true;
                }
                $rowVal[$colName] = $cellValueStr;
            }

            if ($hasValue) {
                $firstCell = reset($rowVal);
                if (is_string($firstCell) && (str_contains(strtolower($firstCell), 'petunjuk') || str_contains(strtolower($firstCell), 'isi mulai baris'))) {
                    continue;
                }

                $primaryKeyVal = trim((string)($rowVal[$keyColumnName] ?? ''));

                if ($primaryKeyVal !== '' && isset($existingMap[$primaryKeyVal])) {
                    // UPSERT: Update existing row data
                    $existingRow = $existingMap[$primaryKeyVal];
                    $existingRow->update(['data' => $rowVal]);
                    $updatedCount++;
                } else {
                    // UPSERT: Append new row
                    $maxOrder++;
                    $newRow = DirectorateRow::create([
                        'item_key' => $item_key,
                        'data' => $rowVal,
                        'row_order' => $maxOrder,
                    ]);
                    if ($primaryKeyVal !== '') {
                        $existingMap[$primaryKeyVal] = $newRow;
                    }
                    $insertedCount++;
                }
            }
        }

        $msgParts = [];
        if ($insertedCount > 0) {
            $msgParts[] = "{$insertedCount} baris baru ditambahkan";
        }
        if ($updatedCount > 0) {
            $msgParts[] = "{$updatedCount} baris diperbarui (upsert)";
        }
        $summary = count($msgParts) > 0 ? implode(', ', $msgParts) : 'tidak ada perubahan data';

        return redirect()->route('admin.data-direktorat.show', $item_key)
            ->with('status', "Import Excel berhasil! ({$summary})");
    }

    /**
     * Helper to build Spreadsheet object formatted according to spec.
     */
    private function buildSpreadsheet(DirectorateConfig $config, $rows = [])
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($config->item_key, 0, 31));

        $columns = $config->columns ?: ['Tahun', 'Nilai', 'Keterangan'];
        $colCount = count($columns);
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(max(1, $colCount));

        $cleanTitle = str_replace(['—', '→'], '-', $config->title);

        // Row 1: Title
        $sheet->setCellValue('A1', 'TEMPLATE UPLOAD: ' . strtoupper($cleanTitle));
        $sheet->mergeCells("A1:{$lastColLetter}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF047857');
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setIndent(1);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Row 3: Instructions
        $instructions = $config->instructions ?: config('data_direktorat.default_instructions');
        $sheet->setCellValue('A3', 'PETUNJUK: ' . $instructions);
        $sheet->mergeCells("A3:{$lastColLetter}3");
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF334155'));
        $sheet->getRowDimension(3)->setRowHeight(24);

        // Row 6: Column Headers
        $sheet->getRowDimension(6)->setRowHeight(28);
        foreach ($columns as $idx => $colName) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx + 1);
            $sheet->setCellValue("{$colLetter}6", $colName);
            $sheet->getStyle("{$colLetter}6")->getFont()->setBold(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
            $sheet->getStyle("{$colLetter}6")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF059669');
            $sheet->getStyle("{$colLetter}6")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("{$colLetter}6")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF047857'));
        }

        // Row 7+: Data Rows or Blank Template Rows
        $currentRow = 7;
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $sheet->getRowDimension($currentRow)->setRowHeight(22);
                foreach ($columns as $idx => $colName) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx + 1);
                    $val = $row->data[$colName] ?? '';
                    $sheet->setCellValue("{$colLetter}{$currentRow}", $val);

                    $sheet->getStyle("{$colLetter}{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE2E8F0'));
                    $sheet->getStyle("{$colLetter}{$currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }
                $currentRow++;
            }
        } else {
            // Add 3 sample empty formatted rows for template download
            for ($r = 7; $r <= 9; $r++) {
                $sheet->getRowDimension($r)->setRowHeight(22);
                foreach ($columns as $idx => $colName) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx + 1);
                    $sheet->getStyle("{$colLetter}{$r}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE2E8F0'));
                }
            }
        }

        // Auto-width columns
        foreach (range(1, max(1, $colCount)) as $colIndex) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        return $spreadsheet;
    }
}
