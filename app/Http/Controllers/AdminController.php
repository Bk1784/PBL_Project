<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminLogin(){
        return view('admin.login');
    }
    public function AdminDashboard(){
        return view('admin.admin_dashboard');
    }

    public function AdminLoginSubmit(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];
        if(Auth::guard('admin')->attempt($data) ){
            return redirect()->route('admin.dashboard')->with('success', 'Login Successfully');
        }else{
            return redirect()->route('admin.login')->with('error', 'Invalid Credentials');

        }
    }
}
