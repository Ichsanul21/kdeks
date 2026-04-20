<?php

namespace App\Http\Controllers;

use App\Models\GuestMessage;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('public.contact', [
            'setting' => SiteSetting::first(),
            'locale' => session('site_locale', 'id'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        GuestMessage::create($validated);

        return back()->with('status', 'Pesan Anda telah berhasil dikirim. Terima kasih!');
    }
}
