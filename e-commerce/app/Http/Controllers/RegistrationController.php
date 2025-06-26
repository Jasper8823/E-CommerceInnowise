<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class RegistrationController extends Controller
{
    public function create(){
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:logins,email',
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        Login::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return redirect('/admin/products')->with('success', 'Registration successful.');
    }
}
