<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman otentikasi Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback setelah otentikasi sukses dari Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user yang sudah ada dengan social_id yang sama
            $user = User::where('social_id', $googleUser->getId())
                        ->where('social_type', 'google')
                        ->first();

            if (!$user) {
                // Jika tidak ada dengan social_id, cari berdasarkan email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Jika email terdaftar tapi belum memiliki social_id, pasangkan
                    $user->update([
                        'social_id'   => $googleUser->getId(),
                        'social_type' => 'google',
                    ]);
                } else {
                    // Jika belum terdaftar sama sekali, buat user baru
                    $user = User::create([
                        'name'        => $googleUser->getName(),
                        'email'       => $googleUser->getEmail(),
                        'role'        => 'user', // Akun umum pemesan tiket
                        'social_id'   => $googleUser->getId(),
                        'social_type' => 'google',
                        'password'    => bcrypt(Str::random(24)), // Password acak
                    ]);
                }
            }

            // Login dan buat session user
            Auth::login($user);

            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', 'Gagal masuk menggunakan Google: ' . $e->getMessage());
        }
    }
}
