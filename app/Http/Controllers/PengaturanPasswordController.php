<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PengaturanPasswordController extends Controller
{
    public function index()
    {
        $title = 'Pengaturan Password';
        $active = 'pengaturan password';
        
        return view('pengaturan-password.index',compact('title','active'));
    }

    public function editPassword(Request $request)
    {
        $id_user = Auth::user()->id;
        $user = User::findOrFail($id_user);

        $request->validate([
            'password' => 'required',
            'new_password' => 'required|min:7|different:password',
        ]);

        if (Hash::check($request->password, $user->password)) {

            $user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            return redirect('/pengaturan-password')->with('success', 'Update Password successfull!');
        } else {
            return redirect('/pengaturan-password')->with('failed', 'Update Password Failed!');
        }
    }

}
