<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect based on role
            if ($user->role === 'pemilik') {
                return redirect()->intended('/dashboard/pemilik');
            }

            return redirect()->intended('/dashboard/penyewa');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|string|email|max:50|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:15',
            'role' => 'required|in:pemilik,penyewa',
            'nik' => 'nullable|string|max:16|unique:users,nik',
            'alamat_asal' => 'nullable|string',
        ]);

        $user = User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_hp' => $validated['no_hp'],
            'role' => $validated['role'],
            'nik' => $validated['nik'] ?? null,
            'alamat_asal' => $validated['alamat_asal'] ?? null,
        ]);

        Auth::login($user);

        // Redirect based on role
        if ($user->role === 'pemilik') {
            return redirect('/dashboard/pemilik');
        }

        return redirect('/dashboard/penyewa');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
