<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data = User::all();
        return view('superadmin.ManageUser')->with('data', $data);
    }

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
        ], [
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

    public function store(Request $request)
    {
        try {
            $data = $request->only(['username', 'password', 'role', 'statusApproval']);
            $data['statusApproval'] = Hash::make($data['statusApproval']);
            $saved = User::create($data);

            if ($saved) {
                return redirect()->route('viewUser')->with('success', 'Data menunggu approval untuk dibuat!');
            } else {
                return redirect()->route('viewUser')->with('error', 'Gagal membuat data!');
            }
        } catch (\Exception $e) {
            return redirect()->route('viewUser')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $data = User::find($id);
            $admin = Auth::user()->username;
            if ($admin == 'superadmin1') {
                $data->statusApproval = 3;
            } else if ($admin == 'superadmin2') {
                $data->statusApproval = 4;
            } else {
                return redirect()->route('viewUser')->with('error', 'Invalid role!');
            }

            $status = $data->save();

            if ($status) {
                return redirect()->route('viewUser')->with('success', 'Data menunggu approval untuk dihapus!');
            } else {
                return redirect()->route('viewUser')->with('error', 'Data gagal dihapus!');
            }
        } catch (\Exception $e) {
            return redirect()->route('viewUser')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function approve(Request $request)
    {
        try {
            $data = User::find($request->id);
            $data->statusApproval = $request->statusApproval;

            $status = $data->save();

            if ($status) {
                return redirect()->route('viewUser')->with('success', 'Data menunggu approval untuk dihapus!');
            } else {
                return redirect()->route('viewUser')->with('error', 'Data gagal dihapus!');
            }
        } catch (\Exception $e) {
            return redirect()->route('viewUser')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
