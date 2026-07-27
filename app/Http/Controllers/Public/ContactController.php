<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pesan' => 'required|string',
        ]);

        // Just redirect to WhatsApp or back with success
        $whatsapp = \App\Models\Setting::get('whatsapp', '6281234567890');
        $text = urlencode("Halo PHC, saya {$request->nama} ({$request->email}). Ingin bertanya: {$request->pesan}");
        $waUrl = "https://wa.me/{$whatsapp}?text={$text}";

        return redirect()->away($waUrl);
    }
}
