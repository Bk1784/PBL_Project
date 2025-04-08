<?php

use App\Http\Controllers\Admin\ManageController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;


 //// CUSTOMER GUEST: Hanya bisa diakses jika belum login
 Route::prefix('customer')->middleware('customer.guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('customer.register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('customer.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('customer.password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('customer.password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('customer.password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('customer.password.store');
});

// CUSTOMER AUTH: Hanya bisa diakses jika sudah login
Route::middleware('customer')->group(function(){
    Route::get('/', [CustomerController::class, 'Index'])->name('index');
    Route::get('/atk_dashboard', [CustomerController::class, 'Atk'])->name('atk_dashboard');
    Route::get('/customer/logout', [CustomerController::class, 'CustomerLogout'])->name('customer.logout');
    Route::get('/customer/profile', [CustomerController::class, 'CustomerProfile'])->name('customer.profile');
    Route::get('/customer/edit/profile', [CustomerController::class, 'CustomerEditProfile'])->name('customer.edit_profile');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

});

<<<<<<< HEAD

Route::middleware('auth')->group(function () {
    Route::post('/profile/store', [CustomerController::class, 'ProfileStore'])->name('profile.store');
    
=======
// CUSTOMER AUTH: Hanya bisa diakses jika sudah login
Route::middleware('customer')->group(function () {
    // Route::get('/', [CustomerController::class, 'Index'])->name('index');
    Route::get('/atk_dashboard', [CustomerController::class, 'Atk'])->name('atk_dashboard');
    Route::get('/customer/logout', [CustomerController::class, 'CustomerLogout'])->name('customer.logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    
});

Route::get('/produk/detail', [CustomerController::class, 'CustomerDetailProduct'])->name('produk.detail');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// CUSTOMER AUTH
Route::controller(CustomerController::class)->group(function () {
    Route::get('/customer/atk-dashboard',  'atkDashboard')->name('customer.atk_dashboard');
    Route::get('/produk-atk', 'atkDashboard')->name('atk_dashboard');
    Route::get('/customer/dashboard', 'CustomerDashboard')->name('customer.dashboard');
    Route::get('/customer/logout', 'CustomerLogout')->name('customer.logout');
    Route::get('/customer/profile', 'CustomerProfile')->name('customer.profile');
    Route::get('/customer/profile/edit', 'CustomerEditProfile')->name('customer.profile.edit');
    Route::put('/customer/profile/update', 'CustomerUpdateProfile')->name('customer.profile.update');
    Route::get('/customer/change-password', 'CustomerChangePassword')->name('customer.change.password');
    Route::post('/customer/update-password', 'CustomerUpdatePassword')->name('customer.update.password');
    Route::post('/customer/forgot-password', 'CustomerForgotPasswordSubmit')->name('customer.forgot_password.submit');
>>>>>>> f178caba8e6022187ed39930a4be590817bb6540
});

require __DIR__ . '/auth.php';

//ADMIN
Route::middleware('admin')->group(function () {
    Route::get('/admin/manage_client', [AdminController::class, 'AdminManageClient'])->name('admin.manage_client');
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::get('/admin/edit_profile', [AdminController::class, 'AdminEditProfile'])->name('admin.edit.profile');
    Route::put('/admin/profile/update', [AdminController::class, 'AdminUpdateProfile'])->name('admin.update.profile');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');

    Route::controller(ManageController::class)->group(function () {
        Route::get('/admin/all/product', 'AdminAllProduct')->name('admin.all.product');
        Route::get('/admin/add/product', 'AdminAddProduct')->name('admin.add.product');
        Route::post('/admin/store/product', 'AdminStoreProduct')->name('admin.product.store');
        Route::get('/admin/edit/product/{id}', 'AdminEditProduct')->name('admin.edit.product');
        Route::put('/admin/update/product', 'AdminUpdateProduct')->name('admin.update.product');
        Route::get('/admin/delete/product/{id}', 'AdminDeleteProduct')->name('admin.delete.product');
    });
    Route::controller(ManageController::class)->group(function(){
        Route::get('/pending/toko', 'PendingToko')->name('pending.toko');
        Route::get('/clientchangeStatus', 'ClientChangeStatus');
        Route::get('/approve/toko', 'ApproveToko')->name('approve.toko');
    });
});
//ADMIN GUEST
Route::middleware('admin.guest')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');
    Route::post('/admin/login_submit', [AdminController::class, 'AdminLoginSubmit'])->name('admin.login_submit');
    Route::get('/admin/forget_password', [AdminController::class, 'AdminForgetPassword'])->name('admin.forget_password');
    Route::post('/admin/password_submit', [AdminController::class, 'AdminPasswordSubmit'])->name('admin.password_submit');
    Route::get('/admin/reset-password/{token}/{email}', [AdminController::class, 'AdminResetPassword']);
    Route::post('admin.reset_password_submit', [AdminController::class, 'AdminResetPasswordSubmit'])->name('admin.reset_password_submit');
});

//CLIENT
    Route::get('/client/dashboard', [ClientController::class, 'ClientDashboard'])->name('client.dashboard');
    
Route::middleware(['status','client'])->group(function () {
    Route::get('/client/logout', [ClientController::class, 'ClientLogout'])->name('client.logout');
    Route::get('/client/profile', [ClientController::class, 'profile'])->name('client.profile');
    Route::get('/client/profile/edit', [ClientController::class, 'editProfile'])->name('client.edit.profile');
    Route::put('/client/profile/update', [ClientController::class, 'updateProfile'])->name('client.update.profile');
    Route::get('/client/change-password', [ClientController::class, 'ClientChangePassword'])->name('client.change.password');
    Route::post('/client/update-password', [ClientController::class, 'ClientUpdatePassword'])->name('client.update.password');
});
//CLIENT GUEST
Route::middleware('client.guest')->group(function () {
    Route::get('/client/login', [ClientController::class, 'ClientLogin'])->name('client.login');
    Route::get('/client/register', [ClientController::class, 'ClientRegister'])->name('client.register');
    Route::post('/client/register/submit', [ClientController::class, 'ClientRegisterSubmit'])->name('client.register.submit');
    Route::post('/client/login_submit', [ClientController::class, 'ClientLoginSubmit'])->name('client.login_submit');
    Route::get('/client/forget_password', [ClientController::class, 'ClientForgetPassword'])->name('client.forget_password');
    Route::post('/client/password_submit', [ClientController::class, 'ClientPasswordSubmit'])->name('client.password_submit');
    Route::get('client/reset-password/{token}/{email}', [ClientController::class, 'ClientResetPassword']);
    Route::post('/client/reset_password_submit', [ClientController::class, 'ClientResetPasswordSubmit'])->name('client.reset_password_submit');
});




// UNTUK SEMUA PENGGUNA
Route::get('/changeStatus', [ManageController::class, 'ChangeStatus']);

});