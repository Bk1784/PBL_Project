<?php

namespace App\Http\Controllers;

use App\Mail\Websitemail;
use App\Models\Admin;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Mail;

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

    public function AdminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'Logout Success');
    }

    public function AdminForgetPassword(){
        return view('admin.forget_password');
    }

    public function AdminPasswordSubmit(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        $admin_data = Admin::where('email', $request->email)->first();
        if(!$admin_data){
            return redirect()->back()->with('error', 'Email Not Found');
        }
        $token = hash('sha256', time());
        $admin_data->token = $token;
        $admin_data->update();

        $reset_link = url('admin/reset-password/'.$token.'/'.$request->email);
        $subject = 'Reset Password';
        $message = 'Please Click on below link to reset password<br>';
        $message .= "<a href='".$reset_link." '> Click Here </a>";

        \Mail::to($request->email)->send(new Websitemail($subject, $message));
        return redirect()->back()->with('success', 'Reset Password Link Send On Your Email');
    }
    public function AdminResetPassword($token, $email){
        $admin_data = Admin::where('email', $email)->where('token', $token)->first();
        if(!$admin_data){
            return redirect()->route('admin.login')->with('error', 'Invalid Token Or Email');
        }
        return view('admin.reset_password', compact('token', 'email'));
    }

    public function AdminResetPasswordSubmit(Request $request){
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);
        $admin_data = Admin::where('email', $request->email)->where('token', $request->token)->first();
        $admin_data->password = Hash::make($request->password);
        $admin_data->token = "";
        $admin_data->update();

        return redirect()->route('admin.login')->with('success', 'Password Reset Success');

    }
    public function AdminProfile(){
        $admin = Auth::guard('admin')->user();
        return view('admin.admin_profile', compact('admin'));
    }

    public function AdminEditProfile() {
        $admin = Auth::guard('admin')->user();
        return view('admin.admin_edit_profile', compact('admin'));
    }

    public function AdminUpdateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Update data
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->address = $request->address;
        $admin->bio = $request->bio;
        $admin->photo = $request->photo;
    
        // Handle foto profil
        if ($request->hasFile('photo')) {
            if ($admin->photo) {
                Storage::delete('public/' . $admin->photo);
            }
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
            $admin->photo = $photoPath;
        }
        $admin->save();
    
        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function AdminManageClient(){
        return view('admin.manage_client');
    }

   
}
