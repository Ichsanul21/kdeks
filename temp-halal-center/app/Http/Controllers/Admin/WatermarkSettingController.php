<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WatermarkSettingRequest;
use App\Models\SiteSetting;
use App\Services\MediaStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WatermarkSettingController extends Controller
{
    public function __construct(protected MediaStorageService $mediaStorageService)
    {
    }

    public function edit(): View
    {
        return view('admin.watermark-settings.edit', [
            'pageTitle' => 'Watermark Global',
            'setting' => SiteSetting::query()->firstOrCreate(['id' => 1]),
        ]);
    }

    public function update(WatermarkSettingRequest $request): RedirectResponse
    {
        $setting = SiteSetting::query()->firstOrCreate(['id' => 1]);
        $validated = $request->validated();

        $setting->watermark_enabled = $request->boolean('watermark_enabled');
        $setting->watermark_text = $validated['watermark_text'] ?? null;
        $setting->watermark_opacity = $validated['watermark_opacity'] ?? 0.18;

        if ($request->hasFile('watermark_image_path')) {
            $setting->watermark_image_path = $this->mediaStorageService->replace(
                $setting->watermark_image_path,
                $request->file('watermark_image_path'),
                'public',
                'admin.watermark-settings'
            );
        }

        $setting->save();

        return redirect()
            ->route('admin.watermark-settings.edit')
            ->with('status', 'Pengaturan watermark berhasil diperbarui.');
    }
}
