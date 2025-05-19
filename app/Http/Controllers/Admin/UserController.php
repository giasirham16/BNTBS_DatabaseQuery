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

    public function store(Request $request)
    {
        $data = User::where('username', $request->username)
            ->where('statusApproval', 2)
            ->first();

        if ($data) {
            return redirect()->route('viewUser')->with('error', 'Data user sudah ada!');
        } else {
            try {
                $data = $request->only(['username', 'password', 'role', 'statusApproval']);
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
    }

    public function destroy(Request $request)
    {
        try {
            $data = User::find($request->id);
            // dd($request->id);
            $admin = Auth::user()->username;
            if ($admin == 'superadmin1') {
                $data->statusApproval = 3;
            } else if ($admin == 'superadmin2') {
                $data->statusApproval = 4;
            } else {
                return redirect()->route('viewUser')->with('error', 'Invalid role!');
            }
            $data->reasonApproval = $request->reason;

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
            if ($request->approval == 1) {
                if ($data->statusApproval != 3 && $data->statusApproval != 4) {
                    $data->statusApproval = 2;
                } else {
                    $data->statusApproval = 99;
                }
            } else {
                if (strtolower(Auth::user()->username) == 'superadmin1') {
                    if ($data->statusApproval != 3 && $data->statusApproval != 4) {
                        $data->statusApproval = 6;
                    } else {
                        $data->statusApproval = 2;
                    }
                } else {
                    if ($data->statusApproval != 3 && $data->statusApproval != 4) {
                        $data->statusApproval = 5;
                    } else {
                        $data->statusApproval = 2;
                    }
                }
            }
            $data->reasonApproval = $request->reasonApproval;

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

    
    // public function ubahPassword()
    // {
    //     $pengguna = Auth::user();
    //     return view('admin.pages.pengguna.ubahPasswordPengguna', compact('pengguna'));
    // }

    // public function updatePassword(Request $request, $id)
    // {
    //     $request->validate([
    //         'currentPassword' => 'required',
    //         'password' => 'required|min:8',
    //         'passwordConfirmation' => 'required|same:password'
    //     ], [
    //         'password.min' => "Password minimal 8",
    //         'passwordConfirmation.same' => "Password tidak sama"
    //     ]);

    //     $user = User::findOrFail($id);

    //     if (!Hash::check($request->currentPassword, $user->password)) {
    //         return back()->with('error', 'Password lama tidak cocok');
    //     }

    //     $user->update([
    //         'password' => Hash::make($request->password)
    //     ]);

    //     return redirect()->route('pengguna.ubahPassword', ['id' => $id])->with('success', 'Password berhasil diubah');
    // }
}
