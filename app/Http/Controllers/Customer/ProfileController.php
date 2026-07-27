<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            $customer = Customer::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'no_wa' => '6281234567890',
                'alamat' => 'Alamat belum diatur',
                'status' => 'active',
            ]);
        }

        return view('customer.profile', compact('customer'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string|max:1000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $customer->update([
            'nama' => $request->nama,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $user->update([
            'name' => $request->nama,
        ]);

        return redirect()->route('customer.profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
