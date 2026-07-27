<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Find user by google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // 2. Find user by email
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    $user = $existingUser;
                    $user->google_id = $googleUser->id;
                    $user->save();
                } else {
                    // 3. Register a new Customer User
                    $user = DB::transaction(function () use ($googleUser) {
                        // Create User
                        $u = User::create([
                            'name' => $googleUser->name,
                            'username' => 'google_' . $googleUser->id,
                            'email' => $googleUser->email,
                            'password' => null, // Social sign in has no password
                            'role_id' => 5, // Customer role
                            'status' => 'active',
                            'google_id' => $googleUser->id,
                            'foto' => $googleUser->avatar,
                        ]);

                        // Create Customer Profile
                        Customer::create([
                            'user_id' => $u->id,
                            'nama' => $googleUser->name,
                            'alamat' => '',
                            'no_wa' => '',
                            'email' => $googleUser->email,
                            'sumber_info' => 'Google Login',
                            'status' => 'active',
                        ]);

                        return $u;
                    });
                }
            }

            if ($user->status !== 'active') {
                return redirect()->route('login')->withErrors(['email' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.']);
            }

            Auth::login($user);

            // Redirect based on role
            if ($user->role_id == 5) {
                return redirect()->intended('/customer/dashboard');
            }
            return redirect()->intended('/admin');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Login dengan Google gagal. ' . $e->getMessage()]);
        }
    }

    public function linkGoogleAccount()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleLinkCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $existingUser = User::where('google_id', $googleUser->id)
                ->where('id', '!=', auth()->user()->id)
                ->first();

            if ($existingUser) {
                return redirect()->back()->withErrors(['google' => 'Akun Google ini sudah terhubung dengan pengguna lain.']);
            }

            $user = auth()->user();
            $user->google_id = $googleUser->id;
            $user->save();

            return redirect()->back()->with('success', 'Akun Google berhasil dihubungkan.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['google' => 'Penghubungan akun Google gagal.']);
        }
    }

    public function unlinkGoogleAccount(Request $request)
    {
        $user = auth()->user();

        if (empty($user->google_id)) {
            return redirect()->back();
        }

        if (!$user->password) {
            return redirect()->back()->withErrors(['google' => 'Anda harus memiliki password untuk memutuskan koneksi Google.']);
        }

        $user->google_id = null;
        $user->save();

        return redirect()->back()->with('success', 'Akun Google berhasil diputuskan.');
    }
}
