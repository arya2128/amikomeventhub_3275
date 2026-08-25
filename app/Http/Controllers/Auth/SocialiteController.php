<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman otentikasi Google.
     */
    public function redirectToGoogle(Request $request)
    {
        if ($request->has('redirect')) {
            session(['socialite_redirect' => $request->get('redirect')]);
        }
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
                        'name'        => $googleUser->getName() ?? $googleUser->getNickname() ?? 'User Google',
                        'email'       => $googleUser->getEmail(),
                        'role'        => 'user', // Akun umum pemesan tiket
                        'social_id'   => $googleUser->getId(),
                        'social_type' => 'google',
                        'password'    => password_hash(Str::random(32), PASSWORD_BCRYPT),
                    ]);
                }
            }

            // Login dan buat session user
            Auth::login($user);

            if (session()->has('socialite_redirect')) {
                $redirectUrl = session()->pull('socialite_redirect');
                return redirect($redirectUrl);
            }

            if (in_array($user->role, ['admin', 'organizer', 'superadmin'])) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', 'Gagal masuk menggunakan Google: ' . $e->getMessage());
        }
    }
}
