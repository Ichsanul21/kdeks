<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SehatiRegistrationRequest;
use App\Models\LphPartner;
use App\Models\SehatiRegistration;

class SehatiRegistrationController extends BaseCrudController
{
    protected string $modelClass = SehatiRegistration::class;
    protected string $pageTitle = 'Pendaftaran SEHATI';
    protected string $routePrefix = 'admin.sehati-registrations';
    protected string $requestClass = SehatiRegistrationRequest::class;
    protected array $searchColumns = ['owner_name', 'business_name', 'product_name', 'phone', 'status'];
    protected array $tableColumns = [
        ['key' => 'owner_name', 'label' => 'Pemilik'],
        ['key' => 'business_name', 'label' => 'UMKM'],
        ['key' => 'product_name', 'label' => 'Produk'],
        ['key' => 'status', 'label' => 'Status', 'options' => [
            'baru' => 'Baru',
            'ditinjau' => 'Ditinjau',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai'
        ]],
        ['key' => 'izin_status', 'label' => 'Izin Status', 'options' => [
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak'
        ]],
    ];

    public function approve(string $id, \Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        $registration = SehatiRegistration::findOrFail($id);
        $decision = $request->input('decision');

        if ($decision === 'yes') {
            $registration->update([
                'izin_status' => 'disetujui',
                'status' => 'selesai',
            ]);
        } elseif ($decision === 'no') {
            $registration->update([
                'izin_status' => 'ditolak',
                'status' => 'selesai',
            ]);
        }

        return redirect()->back()->with('status', 'Status pengajuan berhasil diperbarui.');
    }

    protected function resolvedFields(): array
    {
        return [
            ['name' => 'lph_partner_id', 'label' => 'LPH / LP3H', 'type' => 'select', 'options' => \App\Models\LphPartner::query()->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'owner_name', 'label' => 'Nama Pemilik UMKM', 'type' => 'text', 'required' => true],
            ['name' => 'business_name', 'label' => 'Nama UMKM', 'type' => 'text', 'required' => true],
            ['name' => 'product_name', 'label' => 'Nama Produk', 'type' => 'text', 'required' => true],
            ['name' => 'phone', 'label' => 'Nomor HP / WA', 'type' => 'text', 'required' => true],
            ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => [
                'baru' => 'Baru',
                'ditinjau' => 'Ditinjau',
                'diproses' => 'Diproses',
                'selesai' => 'Selesai'
            ]],
        ];
    }
}
