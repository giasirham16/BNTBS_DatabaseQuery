<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // 🔹 MENAMPILKAN FORM LOGIN
    public function showLoginForm()
    {
        return view('admin.pages.login.login');
    }

    public function login(Request $request)
    {

        // dd($request->all());
        
        // 🔹 Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
    
        $credentials = $request->only('username', 'password');
    
        // 🔹 Logout session sebelumnya jika ada
        Auth::logout();
    
        // 🔹 Coba login menggunakan Auth::attempt()
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // 🔹 Regenerasi session untuk keamanan
            if(strtolower(Auth::user()->role) === 'operator'){
                return redirect()->route('viewQuery');
            }
        }
    
        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }
    

    // 🔹 LOGOUT & HAPUS SESSION
    public function logout(Request $request)
    {
        Auth::logout(); // 🔹 Logout user dari sistem
    
        $request->session()->invalidate(); // 🔹 Hapus semua sesi
        $request->session()->regenerateToken(); // 🔹 Regenerasi CSRF token untuk keamanan
    
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
