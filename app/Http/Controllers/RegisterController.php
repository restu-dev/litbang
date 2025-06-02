<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index');
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:6|max:255'
        ]);

        $validateData['password'] = Hash::make($validateData['password']); 


        User::create($validateData);

        return redirect('/register')->with('success', 'Registratison successfull!');

    }
}
