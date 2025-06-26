<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LogInController extends Controller
{
    public function auth(){
        return view('auth.login');
    }
    public function check(Request $request){
        $login = Login::where('email', $request->email)->first();

        if(!$login){
            return back()->withErrors('No such email found')->withInput();
        }

        if(!Hash::check($request->password, $login->password)){
            return back()->withErrors("Password doesn't match")->withInput();
        }

        Auth::login($login);
        return redirect()->intended('/admin/products');
    }

    public function logout(){
        if(Auth::check()){
            Auth::logout();
        }
        return redirect('/');
    }
}
