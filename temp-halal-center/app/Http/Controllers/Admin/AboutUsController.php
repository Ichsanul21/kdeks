<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AboutUsController extends Controller
{
    public function edit(): View
    {
        $aboutUs = AboutUs::firstOrCreate(['id' => 1]);
        
        return view('admin.crud.form', [
            'pageTitle' => 'Tentang Kami',
            'routePrefix' => 'admin.about-us',
            'item' => $aboutUs,
            'mode' => 'edit',
            'formFields' => [
                ['name' => 'description', 'label' => 'Deskripsi Utama', 'type' => 'richtext', 'required' => true],
                ['name' => 'focus', 'label' => 'Fokus Utama (Pisahkan per baris)', 'type' => 'textarea', 'required' => true],
            ],
            'publicFileFields' => [],
            'privateFileFields' => [],
            'publicShowRoute' => 'about',
            'publicShowRouteKey' => null,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'description' => ['required', 'string'],
            'focus' => ['required', 'string'],
        ]);

        $aboutUs = AboutUs::firstOrCreate(['id' => 1]);
        $aboutUs->update($validated);

        return redirect()->back()->with('status', 'Data Tentang Kami berhasil diperbarui.');
    }
}
