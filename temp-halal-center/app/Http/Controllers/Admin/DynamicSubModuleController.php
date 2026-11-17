<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicSubModule;
use App\Models\DynamicSubModuleData;
use App\Models\SectorItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DynamicSubModuleController extends Controller
{
    /**
     * Authorize that the current user has access to this dynamic sub-module or directorate.
     */
    protected function authorizeAccess(?DynamicSubModule $subModule = null, ?int $sectorItemId = null): void
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if ($user->hasAnyRole(['developer', 'superadmin'])) {
            return;
        }

        if (!$user->isAdminDirektorat()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin Admin Direktorat.');
        }

        $userDirectorate = $user->assigned_directorate;
        if (!$userDirectorate) {
            abort(403, 'Akses ditolak. Akun Anda belum terhubung dengan direktorat manapun.');
        }

        if ($subModule) {
            if ((int) $subModule->sector_item_id !== (int) $userDirectorate->id) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses sub-modul direktorat lain.');
            }
        }

        if ($sectorItemId !== null) {
            if ((int) $sectorItemId !== (int) $userDirectorate->id) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses direktorat lain.');
            }
        }
    }

    /**
     * Get active directorate for current user.
     */
    protected function getCurrentDirectorate(): ?SectorItem
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        if ($user->isAdminDirektorat()) {
            return $user->assigned_directorate;
        }

        // For Superadmin/Developer, check if sector_id is requested, else first sector
        $sectorId = request()->query('sector_id');
        if ($sectorId) {
            return SectorItem::find($sectorId);
        }

        return SectorItem::orderBy('sort_order')->orderBy('id')->first();
    }

    /**
     * List all dynamic sub-modules for the current directorate.
     */
    public function index()
    {
        $user = auth()->user();
        $requestedSectorId = request()->query('sector_id');
        $this->authorizeAccess(null, $requestedSectorId ? (int)$requestedSectorId : null);

        $currentDirectorate = $this->getCurrentDirectorate();
        $allDirectorates = $user->hasAnyRole(['developer', 'superadmin'])
            ? SectorItem::orderBy('sort_order')->get()
            : collect([$currentDirectorate])->filter();

        $query = DynamicSubModule::withCount('rows')->with('sectorItem');

        if (!$user->hasAnyRole(['developer', 'superadmin'])) {
            $directorateId = $currentDirectorate ? $currentDirectorate->id : 0;
            $query->where('sector_item_id', $directorateId);
        } else {
            if ($currentDirectorate) {
                $query->where('sector_item_id', $currentDirectorate->id);
            }
        }

        $subModules = $query->latest()->get();

        return view('admin.dynamic-sub-modules.index', compact('subModules', 'currentDirectorate', 'allDirectorates'));
    }

    /**
     * Show form builder to create a new dynamic sub-module.
     */
    public function create()
    {
        $requestedSectorId = request()->query('sector_id');
        $this->authorizeAccess(null, $requestedSectorId ? (int)$requestedSectorId : null);
        $currentDirectorate = $this->getCurrentDirectorate();

        if (!$currentDirectorate) {
            abort(403, 'Direktorat tidak ditemukan.');
        }

        return view('admin.dynamic-sub-modules.builder', [
            'subModule' => new DynamicSubModule(),
            'currentDirectorate' => $currentDirectorate,
            'isEdit' => false,
        ]);
    }

    /**
     * Store new dynamic sub-module created via form builder.
     */
    public function store(Request $request)
    {
        $requestedSectorId = $request->input('sector_item_id') ?? $request->query('sector_id');
        $this->authorizeAccess(null, $requestedSectorId ? (int)$requestedSectorId : null);
        $currentDirectorate = $this->getCurrentDirectorate();

        if (!$currentDirectorate) {
            abort(403, 'Direktorat tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'instructions' => 'nullable|string|max:1000',
            'fields_json' => 'required|string',
        ], [
            'name.required' => 'Nama sub-modul wajib diisi.',
            'fields_json.required' => 'Definisi field kolom wajib diatur.',
        ]);

        $fields = json_decode($request->input('fields_json'), true);
        if (!is_array($fields) || empty($fields)) {
            return back()->withInput()->withErrors(['fields_json' => 'Minimal 1 field kolom harus didefinisikan.']);
        }

        $cleanFields = $this->sanitizeFields($fields);
        if (empty($cleanFields)) {
            return back()->withInput()->withErrors(['fields_json' => 'Field kolom tidak valid. Pastikan setiap field memiliki nama.']);
        }

        $subModule = new DynamicSubModule();
        $subModule->sector_item_id = $currentDirectorate->id;
        $subModule->name = trim($request->input('name'));
        $subModule->description = $request->input('description');
        $subModule->instructions = $request->input('instructions') ?: config('data_direktorat.default_instructions');
        $subModule->fields = $cleanFields;
        $subModule->created_by = auth()->id();
        $subModule->save();

        return redirect()->route('admin.dynamic-sub-modules.show', $subModule->id)
            ->with('status', "Sub-modul '{$subModule->name}' berhasil dibuat! Anda sekarang dapat mengelola data, unduh template, atau import Excel.");
    }

    /**
     * Show form builder to edit an existing dynamic sub-module.
     */
    public function edit(DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        return view('admin.dynamic-sub-modules.builder', [
            'subModule' => $dynamicSubModule,
            'currentDirectorate' => $dynamicSubModule->sectorItem,
            'isEdit' => true,
        ]);
    }

    /**
     * Update existing dynamic sub-module definition.
     */
    public function update(Request $request, DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'instructions' => 'nullable|string|max:1000',
            'fields_json' => 'required|string',
        ]);

        $fields = json_decode($request->input('fields_json'), true);
        if (!is_array($fields) || empty($fields)) {
            return back()->withInput()->withErrors(['fields_json' => 'Minimal 1 field kolom harus didefinisikan.']);
        }

        $cleanFields = $this->sanitizeFields($fields);
        if (empty($cleanFields)) {
            return back()->withInput()->withErrors(['fields_json' => 'Field kolom tidak valid.']);
        }

        $dynamicSubModule->name = trim($request->input('name'));
        $dynamicSubModule->description = $request->input('description');
        $dynamicSubModule->instructions = $request->input('instructions') ?: config('data_direktorat.default_instructions');
        $dynamicSubModule->fields = $cleanFields;
        $dynamicSubModule->save();

        return redirect()->route('admin.dynamic-sub-modules.show', $dynamicSubModule->id)
            ->with('status', 'Definisi sub-modul berhasil diperbarui.');
    }

    /**
     * Delete dynamic sub-module and all its data.
     */
    public function destroy(DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        $name = $dynamicSubModule->name;
        $dynamicSubModule->delete();

        return redirect()->route('admin.dynamic-sub-modules.index')
            ->with('status', "Sub-modul '{$name}' dan seluruh datanya berhasil dihapus.");
    }

    /**
     * Show data page for this dynamic sub-module (CRUD, Import, Export, Clear).
     */
    public function show(DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        $rows = $dynamicSubModule->rows()->get();
        $fields = $dynamicSubModule->fields ?: [];
        $columns = $dynamicSubModule->columns;
        $sector = $dynamicSubModule->sectorItem;

        return view('admin.dynamic-sub-modules.show', compact('dynamicSubModule', 'rows', 'fields', 'columns', 'sector'));
    }

    /**
     * Store manual data row.
     */
    public function storeRow(Request $request, DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        $validatedData = $this->validateRowInput($request, $dynamicSubModule);
        $maxOrder = $dynamicSubModule->rows()->max('row_order') ?? 0;

        $dynamicSubModule->rows()->create([
            'sector_item_id' => $dynamicSubModule->sector_item_id,
            'data' => $validatedData,
            'row_order' => $maxOrder + 1,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.dynamic-sub-modules.show', $dynamicSubModule->id)
            ->with('status', 'Baris data baru berhasil ditambahkan.');
    }

    /**
     * Update manual data row.
     */
    public function updateRow(Request $request, DynamicSubModule $dynamicSubModule, $rowId)
    {
        $this->authorizeAccess($dynamicSubModule);

        $row = $dynamicSubModule->rows()->findOrFail($rowId);
        $validatedData = $this->validateRowInput($request, $dynamicSubModule);

        $row->update([
            'data' => $validatedData,
        ]);

        return redirect()->route('admin.dynamic-sub-modules.show', $dynamicSubModule->id)
            ->with('status', 'Baris data berhasil diperbarui.');
    }

    /**
     * Destroy a single data row.
     */
    public function destroyRow(DynamicSubModule $dynamicSubModule, $rowId)
    {
        $this->authorizeAccess($dynamicSubModule);

        $row = $dynamicSubModule->rows()->findOrFail($rowId);
        $row->delete();

        return redirect()->route('admin.dynamic-sub-modules.show', $dynamicSubModule->id)
            ->with('status', 'Baris data berhasil dihapus.');
    }

    /**
     * Clear all data rows for this sub-module.
     */
    public function clearRows(DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        $dynamicSubModule->rows()->delete();

        return redirect()->route('admin.dynamic-sub-modules.show', $dynamicSubModule->id)
            ->with('status', 'Semua data pada sub-modul ini berhasil dikosongkan.');
    }

    /**
     * Download Excel template generated from field definitions.
     */
    public function downloadTemplate(DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        $spreadsheet = $this->buildSpreadsheet($dynamicSubModule, []);
        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_' . Str::slug($dynamicSubModule->name) . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Change Excel template.
     */
    public function changeTemplate(Request $request, DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        $request->validate([
            'template_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('template_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        // Check Row 1 for title
        $row1Val = trim((string) $sheet->getCell('A1')->getValue());
        if (str_starts_with(strtoupper($row1Val), 'TEMPLATE UPLOAD:')) {
            $newTitle = trim(substr($row1Val, strlen('TEMPLATE UPLOAD:')));
            if (!empty($newTitle)) {
                $dynamicSubModule->name = $newTitle;
            }
        }

        // Check Row 3 for instructions
        $row3Val = trim((string) $sheet->getCell('A3')->getValue());
        if (str_starts_with(strtoupper($row3Val), 'PETUNJUK:')) {
            $newInstruct = trim(substr($row3Val, strlen('PETUNJUK:')));
            if (!empty($newInstruct)) {
                $dynamicSubModule->instructions = $newInstruct;
            }
        }

        // Detect Header Row
        $highestColumn = $sheet->getHighestDataColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        $extractedHeaders = [];

        // Check Row 6 first
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $val = trim((string) $sheet->getCellByColumnAndRow($col, 6)->getValue());
            if ($val !== '') {
                $extractedHeaders[] = $val;
            }
        }

        // Fallback scan rows 1-10
        if (empty($extractedHeaders)) {
            for ($r = 1; $r <= 10; $r++) {
                $temp = [];
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $val = trim((string) $sheet->getCellByColumnAndRow($col, $r)->getValue());
                    if ($val !== '') {
                        $temp[] = $val;
                    }
                }
                if (count($temp) >= 1) {
                    $extractedHeaders = $temp;
                    break;
                }
            }
        }

        if (empty($extractedHeaders)) {
            return redirect()->route('admin.dynamic-sub-modules.show', $dynamicSubModule->id)
                ->with('error', 'Gagal membaca header kolom dari file template Excel yang diunggah.');
        }

        // Update fields based on extracted headers while keeping existing definitions if matching
        $existingMap = $dynamicSubModule->field_map;
        $updatedFields = [];
        foreach ($extractedHeaders as $header) {
            if (isset($existingMap[$header])) {
                $updatedFields[] = $existingMap[$header];
            } else {
                $updatedFields[] = [
                    'name' => $header,
                    'type' => 'text',
                    'required' => false,
                    'options' => [],
                ];
            }
        }

        $dynamicSubModule->fields = $updatedFields;
        $path = $file->storeAs('public/templates', 'dyn_' . $dynamicSubModule->id . '_' . Str::slug($dynamicSubModule->name) . '.xlsx');
        $dynamicSubModule->custom_template_path = $path;
        $dynamicSubModule->save();

        return redirect()->route('admin.dynamic-sub-modules.show', $dynamicSubModule->id)
            ->with('status', 'Template Excel berhasil diperbarui! Struktur kolom tabel telah disesuaikan.');
    }

    /**
     * Backup data to Excel file.
     */
    public function backupData(DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        $rows = $dynamicSubModule->rows()->get();
        $spreadsheet = $this->buildSpreadsheet($dynamicSubModule, $rows);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Backup_' . Str::slug($dynamicSubModule->name) . '_' . date('Y-m-d_H-i') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import Excel file -> UPSERT logic:
     * Key = first column of table.
     * Existing key = updated.
     * New key = inserted.
     */
    public function importExcel(Request $request, DynamicSubModule $dynamicSubModule)
    {
        $this->authorizeAccess($dynamicSubModule);

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        // Header detection (Default row 6 or fallback)
        $headerRow = 6;
        $headers = [];

        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $val = trim((string) $sheet->getCellByColumnAndRow($col, 6)->getValue());
            if ($val !== '') {
                $headers[$col] = $val;
            }
        }

        if (empty($headers)) {
            for ($r = 1; $r <= 10; $r++) {
                $temp = [];
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $val = trim((string) $sheet->getCellByColumnAndRow($col, $r)->getValue());
                    if ($val !== '') {
                        $temp[$col] = $val;
                    }
                }
                if (count($temp) >= 1) {
                    $headers = $temp;
                    $headerRow = $r;
                    break;
                }
            }
        }

        if (empty($headers)) {
            return redirect()->route('admin.dynamic-sub-modules.show', $dynamicSubModule->id)
                ->with('error', 'Gagal menemukan header kolom tabel pada file Excel. Pastikan header berada di baris 6.');
        }

        $headerList = array_values($headers);
        $keyColumnName = $headerList[0];
        $fieldMap = $dynamicSubModule->field_map;

        // Map existing rows by primary key
        $existingRows = $dynamicSubModule->rows()->get();
        $existingMap = [];
        foreach ($existingRows as $row) {
            $keyVal = trim((string) ($row->data[$keyColumnName] ?? ''));
            if ($keyVal !== '') {
                $existingMap[$keyVal] = $row;
            }
        }

        $insertedCount = 0;
        $updatedCount = 0;
        $startRow = $headerRow + 1;
        $maxOrder = $dynamicSubModule->rows()->max('row_order') ?? 0;

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

                // Check required fields for this row
                $primaryKeyVal = trim((string) ($rowVal[$keyColumnName] ?? ''));

                if ($primaryKeyVal !== '' && isset($existingMap[$primaryKeyVal])) {
                    $existingRow = $existingMap[$primaryKeyVal];
                    $existingRow->update(['data' => $rowVal]);
                    $updatedCount++;
                } else {
                    $maxOrder++;
                    $newRow = $dynamicSubModule->rows()->create([
                        'sector_item_id' => $dynamicSubModule->sector_item_id,
                        'data' => $rowVal,
                        'row_order' => $maxOrder,
                        'created_by' => auth()->id(),
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
        $summary = count($msgParts) > 0 ? implode(', ', $msgParts) : 'tidak ada data yang diproses';

        return redirect()->route('admin.dynamic-sub-modules.show', $dynamicSubModule->id)
            ->with('status', "Import Excel berhasil! ({$summary})");
    }

    /**
     * Validate and extract row input values according to field definitions.
     */
    protected function validateRowInput(Request $request, DynamicSubModule $dynamicSubModule): array
    {
        $fields = $dynamicSubModule->fields ?: [];
        $rules = [];
        $messages = [];
        $validatedData = [];

        foreach ($fields as $field) {
            $name = $field['name'];
            $inputKey = 'col_' . md5($name);
            $type = $field['type'] ?? 'text';
            $isRequired = !empty($field['required']);
            $options = $field['options'] ?? [];

            $fieldRules = [];
            if ($isRequired) {
                $fieldRules[] = 'required';
                $messages["{$inputKey}.required"] = "Field '{$name}' wajib diisi.";
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($type === 'number') {
                $fieldRules[] = 'numeric';
                $messages["{$inputKey}.numeric"] = "Field '{$name}' harus berupa angka numerik.";
            } elseif ($type === 'date') {
                $fieldRules[] = 'date';
                $messages["{$inputKey}.date"] = "Field '{$name}' harus berupa tanggal yang valid.";
            }

            $rules[$inputKey] = $fieldRules;
        }

        $request->validate($rules, $messages);

        foreach ($fields as $field) {
            $name = $field['name'];
            $inputKey = 'col_' . md5($name);
            $validatedData[$name] = $request->input($inputKey, '');
        }

        return $validatedData;
    }

    /**
     * Sanitize fields array from form builder.
     */
    protected function sanitizeFields(array $fields): array
    {
        $sanitized = [];
        $allowedTypes = ['text', 'number', 'date', 'select', 'textarea'];

        foreach ($fields as $item) {
            if (!is_array($item)) {
                continue;
            }

            $name = trim($item['name'] ?? '');
            if ($name === '') {
                continue;
            }

            $type = strtolower(trim($item['type'] ?? 'text'));
            if (!in_array($type, $allowedTypes)) {
                $type = 'text';
            }

            $required = !empty($item['required']);
            $options = [];

            if ($type === 'select') {
                $rawOptions = $item['options'] ?? [];
                if (is_string($rawOptions)) {
                    $rawOptions = preg_split('/[\r\n,]+/', $rawOptions);
                }
                if (is_array($rawOptions)) {
                    $options = array_values(array_filter(array_map('trim', $rawOptions)));
                }
            }

            $sanitized[] = [
                'name' => $name,
                'type' => $type,
                'required' => $required,
                'options' => $options,
            ];
        }

        return $sanitized;
    }

    /**
     * Build PhpSpreadsheet matching exact KDEKS template style.
     */
    protected function buildSpreadsheet(DynamicSubModule $dynamicSubModule, $rows = [])
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($dynamicSubModule->name, 0, 31));

        $columns = $dynamicSubModule->columns ?: ['Data'];
        $colCount = count($columns);
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(max(1, $colCount));

        $cleanTitle = str_replace(['—', '→'], '-', $dynamicSubModule->name);

        // Row 1: Title
        $sheet->setCellValue('A1', 'TEMPLATE UPLOAD: ' . strtoupper($cleanTitle));
        $sheet->mergeCells("A1:{$lastColLetter}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF047857');
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setIndent(1);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Row 3: Instructions
        $instructions = $dynamicSubModule->instructions ?: config('data_direktorat.default_instructions');
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

        // Row 7+: Data Rows or Sample Empty Rows
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
            // Add 3 sample empty formatted rows for template
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
