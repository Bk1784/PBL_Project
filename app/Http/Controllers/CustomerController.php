<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function Index()
    {
        return view('customer.master');
    }

    public function ProfileStore(Request $request)
{
    $id = Auth::user()->id;
    $data = User::find($id);

    $data->name = $request->name;
    $data->email = $request->email;
    $data->phone = $request->phone;
    $data->address = $request->address;

    $oldPhotoPath = $data->photo;

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = date('YmdHis') . '_' . $file->getClientOriginalName();
        $uploadPath = public_path('upload/user_images');

        // buat folder jika belum ada
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // pindahkan file ke folder public/upload/user_images
        $file->move($uploadPath, $filename);

        // simpan path relatif ke database
        $data->photo = 'upload/user_images/' . $filename;

        // hapus foto lama jika ada
        if ($oldPhotoPath && file_exists(public_path($oldPhotoPath))) {
            unlink(public_path($oldPhotoPath));
        }
    }

    $data->save();

    $notification = [
        'message' => 'Profile Updated Successfully',
        'alert-type' => 'success'
    ];

    return redirect()->route('customer.profile')->with($notification);
}


    private function deleteOldImage(string $oldPhotoPath): void
    {
        $fullPath = public_path('storage/app/public/customer_photos/' . $oldPhotoPath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    public function Atk()
    {
        return view('customer.atk_dashboard');
    }

    public function CustomerLogout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success', 'Logout Success');
    }

    public function CustomerProfile()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.profile', compact('customer'));
    }


    public function CustomerEditProfile()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.edit_profile', compact('customer'));
    }

    public function CustomerChangePassword()
    {
        return view('customer.change_password');
    }

    public function CustomerUpdatePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $user = Auth::user();

    if (!$user) {
        return back()->with('error', 'User tidak ditemukan.');
    }

    if (!Hash::check($request->old_password, $user->password)) {
        return back()->with('error', 'Password lama salah.');
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('status', 'Password berhasil diubah.');
}

    public function CustomerDetailProduct($id)
    {
        $products = Product::all();

        return view('customer.detail_produk', []);
    }
}