<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Hash;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'name' => 'Nepareizs lietotajvards vai parole.',
        ])->onlyInput('name');
    }
    public function register(Request $data)
    {
        $validate = $data->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $ExistanceCheck = User::where('name', $data['name'])->first();
        if (!is_null($ExistanceCheck)) {
            return redirect()->to('/loginform')->with('message', 'Login already exists!');
        }

        User::insert([
        'name' => $data['name'],
        'password' => Hash::make($data['password']),
        'email' => "mail".uniqid()."@mail.com"
        ]);
        
        return redirect()->to('/loginform')->with('message', 'Account created!');
    }
}
