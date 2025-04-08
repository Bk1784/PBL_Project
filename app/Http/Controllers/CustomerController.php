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
    public function atkDashboard()
    {
        return view('customer.atk_dashboard');
    }

    public function CustomerLogout()
    {
        // Auth::guard('web')->logout();
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login')->with('success', 'Logout Success');
    }

    public function CustomerLogin()
    {
        return view('customer.customer_login');
    }

    public function CustomerForgotPassword()
    {
        return view('customer.customer_forgot_password');
    }

    public function CustomerForgotPasswordSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
        ]);

        return back()->with('success', 'Link reset password telah dikirimkan ke email Anda.');
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

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
        }

        Customer::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'kontak' => $request->kontak,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'bio' => $request->bio,
            'photo' => $photoPath,
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

        $customer = \App\Models\Customer::where('email', $request->email)->first();

        if (!$customer) {
            $anyCustomer = \App\Models\Customer::get();
            $passwordMatch = false;
            foreach ($anyCustomer as $cust) {
                if (\Illuminate\Support\Facades\Hash::check($request->password, $cust->password)) {
                    $passwordMatch = true;
                    break;
                }
            }

            if ($passwordMatch) {
                return back()->withErrors([
                    'email' => 'Email yang anda masukkan salah.',
                ])->withInput();
            } else {
                return back()->withErrors([
                    'error' => 'Data tidak ditemukan! Silakan masukkan data anda dengan benar atau DAFTAR.',
                ])->withInput();
            }
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->password, $customer->password)) {
            return back()->withErrors([
                'password' => 'Password yang anda masukkan salah.',
            ])->withInput();
        }

        // Autentikasi sukses
        Auth::guard('customer')->login($customer);

        return redirect()->route('customer.dashboard')->with('success', 'Login berhasil!');
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

    public function CustomerProfile()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.customer_profile', compact('customer'));
    }

    public function CustomerEditProfile()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.customer_edit_profile', compact('customer'));
    }

    public function CustomerUpdateProfile(Request $request)
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

        $data = $request->only(['nama', 'username', 'email', 'kontak', 'alamat', 'jenis_kelamin', 'bio']);

        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('profile_photos', 'public');
            $data['photo'] = $filePath;
        }

        $customer->update($data);

        return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function CustomerChangePassword()
    {
        return view('customer.customer_change_password');
    }

    public function CustomerUpdatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ], [
            'old_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $customer = Customer::find(Auth::guard('customer')->id());

        if (!$customer) {
            return back()->with('error', 'Akun tidak ditemukan.');
        }

        if (!Hash::check($request->old_password, $customer->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    public function CustomerDetailProduct(){
        $product = Product::orderBy('id', 'desc')->get();
        return view('customer.detail_produk', compact('product'));    
    }
}