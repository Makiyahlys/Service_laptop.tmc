<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir login dengan switcher demo multi-role.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login pengguna.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi / password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            return $this->redirectBasedOnRole($user)
                ->with('success', 'Selamat datang kembali, ' . $user->name . ' (' . $user->getRoleLabel() . ')!');
        }

        return back()->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah. Silakan coba lagi.',
            ]);
    }

    /**
     * Fitur One-Click Quick Login untuk kemudahan demonstrasi penguji / dosen TA.
     */
    public function quickLogin(string $role): RedirectResponse
    {
        $validRoles = ['kasir', 'teknisi', 'owner', 'admin'];
        if (! in_array($role, $validRoles)) {
            return redirect()->route('login')->with('error', 'Role tidak valid.');
        }

        $email = match ($role) {
            'kasir' => 'kasir@tmc.com',
            'teknisi' => 'teknisi@tmc.com',
            'owner' => 'owner@tmc.com',
            'admin' => 'admin@tmc.com',
        };

        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Akun demo untuk peran ini belum tersedia.');
        }

        Auth::login($user);
        request()->session()->regenerate();

        return $this->redirectBasedOnRole($user)
            ->with('success', 'Berhasil masuk langsung sebagai ' . $user->name . ' (' . $user->getRoleLabel() . ') untuk mode demonstrasi!');
    }

    /**
     * Logout pengguna dari sesi saat ini.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem TMC Laptop.');
    }

    /**
     * Mengarahkan pengguna ke halaman awal yang sesuai dengan hak akses perannya.
     */
    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->role === 'teknisi') {
            return redirect()->intended(route('teknisi.index'));
        }

        if ($user->role === 'owner') {
            return redirect()->intended(route('admin.laporan'));
        }

        // Kasir dan Super Admin diarahkan ke modul penerimaan kasir
        return redirect()->intended(route('admin.index'));
    }
}
