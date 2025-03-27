<?php

namespace App\Http\Controllers;

use App\Mail\ClientMail;
use App\Models\Client;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function ClientLogin(){
        return view('client.client_login');
    }
    public function ClientRegister(){
        return view('client.client_register');
    }
    public function ClientRegisterSubmit(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'string', 'unique:clients'],

        ]);

        Client::insert([
            'name' => $request->name, 
            'email' => $request->email, 
            'phone' => $request->phone, 
            'address' => $request->address, 
            'password' =>Hash::make($request->password), 
            'role' => 'client', 
            'status' => '0', 
        ]);
        $notification = array (
            'message' => 'Client Update Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('client.login')->with($notification);
    }

    public function ClientLoginSubmit(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];
        if(Auth::guard('client')->attempt($data) ){
            return redirect()->route('client.dashboard')->with('success', 'Login Successfully');
        }else{
            return redirect()->route('client.login')->with('error', 'Invalid Credentials');
        }
    }

    public function ClientLogout(){
        Auth::guard('client')->logout();
        return redirect()->route('client.login')->with('success', 'Logout Success');
    }



    public function ClientForgetPassword(){
        return view('client.forget_password');
    }

    public function ClientPasswordSubmit(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        $client_data = Client::where('email', $request->email)->first();
        if(!$client_data){
            return redirect()->back()->with('error', 'Email Not Found');
        }
        $token = hash('sha256', time());
        $client_data->token = $token;
        $client_data->update();

        $reset_link = url('client/reset-password/'.$token.'/'.$request->email);
        $subject = 'Reset Password';
        $message = 'Please Click on below link to reset password<br>';
        $message .= "<a href='".$reset_link." '> Click Here </a>";

        \Mail::to($request->email)->send(new ClientMail($subject, $message));
        return redirect()->back()->with('success', 'Reset Password Link Send On Your Email');
    }

    public function ClientResetPassword($token, $email){
        $client_data = Client::where('email', $email)->where('token', $token)->first();
        if(!$client_data){
            return redirect()->route('client.login')->with('error', 'Invalid Token Or Email');
        }
        return view('client.reset_password', compact('token', 'email'));
    }

    public function ClientResetPasswordSubmit(Request $request){
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);
        $client_data = Client::where('email', $request->email)->where('token', $request->token)->first();
        $client_data->password = Hash::make($request->password);
        $client_data->token = "";
        $client_data->update();

        return redirect()->route('client.login')->with('success', 'Password Reset Success');

    }


    public function ClientDashboard(){
        return view('client.client_dashboard');
    }
}
