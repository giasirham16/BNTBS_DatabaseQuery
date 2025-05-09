<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function ubahPassword()
    {
        $pengguna = Auth::user();
        return view('admin.pages.pengguna.ubahPasswordPengguna', compact('pengguna'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'currentPassword' => 'required',
            'password' => 'required|min:8',
            'passwordConfirmation' => 'required|same:password'
        ],[
            'password.min' => "Password minimal 8",
            'passwordConfirmation.same' => "Password tidak sama"
        ]);

        $user = User::findOrFail($id);

        if (!Hash::check($request->currentPassword, $user->password)) {
            return back()->with('error', 'Password lama tidak cocok');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('pengguna.ubahPassword', ['id' => $id])->with('success', 'Password berhasil diubah');
    }
}
