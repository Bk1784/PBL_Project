<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\Websitemail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Customer;
use App\Models\Product;


class CustomerController extends Controller
{
    // public function Index(){
    //     return view('customer.master');
    // }
    public function Atk(){
        return view('customer.atk_dashboard');
    }

    public function CustomerLogout(){
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success', 'Logout Success');
    }

    public function CustomerLogin()
    {
        return view('customer.customer_login');
    }

    public function CustomerRegister()
    {
        return view('customer.customer_register');
    }

    public function CustomerRegisterSubmit(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:200'],
            'email' => ['required', 'string', 'unique:customers'],
            'password' => ['required', 'min:8'],
        ]);

        Customer::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'kontak' => $request->kontak,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'bio' => $request->bio,
            'photo' => $request->photo,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.login')->with('success', 'Registrasi berhasil!');
    }

    public function CustomerLoginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($request->only('email', 'password'))) {
            return redirect()->route('customer.dashboard')->with('success', 'Login berhasil!');
        } else {
            return redirect()->route('customer.login')->with('error', 'Email atau password salah');
        }
    }

    // public function CustomerLogout()
    // {
    //     Auth::guard('customer')->logout();
    //     return redirect()->route('customer.login')->with('success', 'Logout berhasil!');
    // }

    public function CustomerDashboard()
    {
        return view('customer.customer_dashboard');
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.customer_profile', compact('customer'));
    }

    public function editProfile()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.customer_edit_profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50',
            'email' => 'required|email',
            'kontak' => 'required|string|max:15',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'nullable|string',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('profile_photos', 'public');
            $customer->photo = $filePath;
        }

        $customer->update($request->except('photo'));

        return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function CustomerChangePassword()
    {
        return view('customer.change_password');
    }

    public function CustomerUpdatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $customer = Customer::find(Auth::guard('customer')->id());
        
        if (!Hash::check($request->old_password, $customer->password)) {
            return back()->withErrors(['old_password' => 'Password lama tidak sesuai']);
        }

        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return redirect()->route('customer.dashboard')->with('success', 'Password berhasil diperbarui!');
    }

    public function CustomerDetailProduct(){
        $product = Product::orderBy('id', 'desc')->get();
        return view('customer.detail_produk', compact('product'));    
    }
}