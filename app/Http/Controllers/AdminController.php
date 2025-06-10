<?php

namespace App\Http\Controllers;

use App\Mail\Websitemail;
use App\Models\Admin;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

    public function allReports()
    {
        $orders = Order::all(); // ambil semua orders dari tabel orders
        return view('admin.manage_report', compact('orders'));
    }

    public function searchByDate(Request $request)
    {
        $date = $request->date;
        $orders = Order::whereDate('created_at', $date)->get();
        return view('admin.manage_report', compact('orders'));
    }

    public function searchByMonth(Request $request)
    {
        $month = $request->month;
        $year = $request->year_name;

        $orders = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', date('m', strtotime($month)))
            ->get();

        return view('admin.manage_report', compact('orders'));
    }

    public function searchByYear(Request $request)
    {
        $year = $request->year;
        $orders = Order::whereYear('created_at', $year)->get();
        return view('admin.manage_report', compact('orders'));
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

        Mail::to($request->email)->send(new Websitemail($subject, $message));
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Update data
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->address = $request->address;
    
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

    public function AdminChangePassword()
    {
        return view('admin.change_password');
    }

    public function AdminUpdatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $admin = Admin::find(Auth::guard('admin')->id());

        if (!Hash::check($request->old_password, $admin->password)) {
            return back()->withErrors(['old_password' => 'Password lama tidak sesuai']);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return redirect()->route('admin.dashboard')->with('success', 'Password berhasil diperbarui!');
    }
   
}