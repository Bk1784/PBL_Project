<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function Index(){
        return view('customer.master');
    }
    public function Atk(){
        return view('customer.atk_dashboard');
    }

    public function CustomerLogout(){
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success', 'Logout Success');
    }
}
