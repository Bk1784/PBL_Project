<?php

namespace App\Http\Controllers;

use App\Mail\ClientMail;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ClientController extends Controller
{
    public function ClientLogin()
    {
        return view('client.client_login');
    }

    public function ClientRegister()
    {
        return view('client.client_register');
    }

    public function ClientRegisterSubmit(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'string', 'unique:clients'],

        ]);

        Client::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'Client',
            'status' => '0',
        ]);
        $notification = array(
            'message' => 'Client Update Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('client.login')->with($notification);
    }

    public function ClientLoginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];
        if (Auth::guard('client')->attempt($data)) {
            return redirect()->route('client.dashboard')->with('success', 'Login Successfully');
        } else {
            return redirect()->route('client.login')->with('error', 'Invalid Credentials');
        }
    }

    public function ClientLogout()
    {
        Auth::guard('client')->logout();
        return redirect()->route('client.login')->with('success', 'Logout Success');
    }

    public function ClientForgetPassword()
    {
        return view('client.forget_password');
    }

    public function ClientPasswordSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $client_data = Client::where('email', $request->email)->first();
        if (!$client_data) {
            return redirect()->back()->with('error', 'Email Not Found');
        }
        $token = hash('sha256', time());
        $client_data->token = $token;
        $client_data->update();

        $reset_link = url('client/reset-password/' . $token . '/' . $request->email);
        $subject = 'Reset Password';
        $message = 'Please Click on below link to reset password<br>';
        $message .= "<a href='" . $reset_link . " '> Click Here </a>";

        Mail::to($request->email)->send(new ClientMail($subject, $message));
        return redirect()->back()->with('success', 'Reset Password Link Send On Your Email');
    }

    public function ClientResetPassword($token, $email)
    {
        $client_data = Client::where('email', $email)->where('token', $token)->first();
        if (!$client_data) {
            return redirect()->route('client.login')->with('error', 'Invalid Token Or Email');
        }
        return view('client.reset_password', compact('token', 'email'));
    }

    public function ClientResetPasswordSubmit(Request $request)
    {
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

    public function ClientDashboard()
    {
        return view('client.client_dashboard');
    }

    public function profile()
    {
        $client = Auth::guard('client')->user();
        return view('client.client_profile', compact('client'));
    }

    public function editProfile()
    {
        $client = Auth::guard('client')->user();
        return view('client.client_edit_profile', compact('client'));
    }

    public function updateProfile(Request $request)
    {
        $client = Client::find(Auth::guard('client')->id());

        if (!$client) {
            return redirect()->back()->with('error', 'Client tidak ditemukan.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('profile_photos', 'public');
            $validatedData['photo'] = $filePath;
        }

        $client->fill($validatedData);

        if ($client->isDirty()) {
            $client->update($validatedData);
            $client->save();
            return redirect()->route('client.profile')->with('success', 'Profil berhasil diperbarui!');
        }

        return redirect()->route('client.profile')->with('info', 'Tidak ada perubahan pada profil.');
    }


    public function ClientPesanan()
    {
        return redirect()->route('client.pending.orders');
    }

    public function pendingOrders()
    {
        $client = Auth::guard('client')->user();
        $orders = Order::with('product')
            ->where('user_id', $client->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.Order.pending_orders', compact('orders'));
    }

    public function confirmOrders()
    {
        $client = Auth::guard('client')->user();
        $orders = Order::with('product')
            ->where('user_id', $client->id)
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.Order.confirm_orders', compact('orders'));
    }

    public function processingOrders()
    {
        $client = Auth::guard('client')->user();
        $orders = Order::with('product')
            ->where('user_id', $client->id)
            ->where('status', 'processing')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.Order.processing_orders', compact('orders'));
    }

    public function deliveredOrders()
    {
        $client = Auth::guard('client')->user();
        $orders = Order::with('product')
            ->where('user_id', $client->id)
            ->where('status', 'delivered')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.Order.delivered_orders', compact('orders'));
    }

    public function confirmReceived($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 'delivered') {
            $order->status = 'completed';
            $order->save();
            return redirect()->back()->with('success', 'Pesanan berhasil diterima');
        }
        return redirect()->back()->with('error', 'Pesanan tidak dapat diterima');
    }

    public function cancelOrder($id)
    {
        $order = Order::findOrFail($id);
        if (in_array($order->status, ['pending', 'confirmed'])) {
            $order->status = 'cancelled';
            $order->save();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan');
        }
        return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan');
    }

    public function orderDetails($id)
    {
        $order = Order::with('product')->findOrFail($id);
        return view('client.Order.order_details', compact('order'));
    }

    public function executedOrders()
    {
        $client = Auth::guard('client')->user();
        $orders = Order::with('product')
            ->where('user_id', $client->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.executed_pesanan', compact('orders'));
    }

    public function ClientLaporan(Request $request)
    {
        $sort = $request->get('sort', 'most_sold');
        
        $products = Product::query()
            ->when($sort === 'most_sold', function($query) {
                return $query->frequentlySold();
            })
            ->when($sort === 'least_sold', function($query) {
                return $query->frequentlySold()->orderBy('sold_count');
            })
            ->get();

        return view('client.laporan', compact('products', 'sort'));
    }

    public function getProductDetails($id)
    {
        $product = Product::with('orderItems')->findOrFail($id);
        return response()->json([
            'description' => $product->description,
            'total_sold' => $product->totalSold(),
            'total_revenue' => $product->totalRevenue()
        ]);
    }

    public function ClientChangePassword()
    {
        return view('client.change_password');
    }

    public function ClientUpdatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $client = Client::find(Auth::guard('client')->id());

        if (!Hash::check($request->old_password, $client->password)) {
            return back()->withErrors(['old_password' => 'Password lama tidak sesuai']);
        }

        $client->password = Hash::make($request->new_password);
        $client->save();

        return redirect()->route('client.dashboard')->with('success', 'Password berhasil diperbarui!');
    }
}