<?php

use App\Http\Controllers\Admin\ManageController;
use App\Http\Controllers\Admin\ManageOrderController;
use App\Http\Controllers\Admin\ManageRatingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\PurchasesController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;


//// CUSTOMER GUEST: Hanya bisa diakses jika belum login
//CUSTOMER
Route::get('/', [CustomerController::class, 'Index'])->name('index');
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
Route::middleware('customer')->group(function () {
    Route::get('/atk_dashboard', [CustomerController::class, 'Atk'])->name('atk_dashboard');
    Route::get('/customer/logout', [CustomerController::class, 'CustomerLogout'])->name('customer.logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    // ---------------------------------- RUTE UNTUK PROFILE, EDIT, CHANGE PASSWORD -------------------------------------//
    Route::get('/customer/profile', [CustomerController::class, 'CustomerProfile'])->name('customer.profile');
    Route::get('/customer/edit/profile', [CustomerController::class, 'CustomerEditProfile'])->name('customer.edit_profile');
    Route::post('/profile/store', [CustomerController::class, 'ProfileStore'])->name('profile.store');
    Route::get('/change-password', [CustomerController::class, 'CustomerChangePassword'])->name('customer.change_password');
    Route::post('/update-password', [CustomerController::class, 'CustomerUpdatePassword'])->name('customer.update_password');

    // ----------------------------------------------- RUTE UNTUK CART ---------------------------------------------------//
    Route::get('/customer/product/{id}', [CustomerController::class, 'CustomerDetailProduct']);
    Route::controller(CartController::class)->group(function () {
        Route::get('/add_to_cart/{id}', 'AddToCart')->name('add_to_cart');
        Route::post('/cart/update-quantity', 'UpdateCartQuantity')->name('cart.updateQuantity');
        Route::post('/cart/remove', 'CartRemove')->name('cart.remove');
        Route::get('/checkout', 'CheckoutProduk')->name('customer.checkout.view_checkout');
        Route::post('/cart/sync', [CartController::class, 'sync'])->name('cart.sync');
    });

    Route::controller(HomeController::class)->group(function () {
        Route::get('/produk/details/{id}', 'DetailProduk')->name('detail_products');
    });

    //MANAGE ORDER CUSTOMER
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('customer.orders.all_orders');
        Route::post('/cash/order', 'CashOrder')->name('customer.orders.cash_order');
        Route::get('/orders/{id}/details', 'OrderDetails')->name('customer.orders.details');
        Route::post('/orders/{id}/cancel', 'CancelOrder')->name('customer.orders.cancel');
        Route::post('/orders/{id}/received', 'ConfirmReceived')->name('customer.orders.received');
        Route::get('/orders/{id}/invoice', 'downloadInvoice')->name('customer.orders.invoice');
        Route::post('/get-midtrans-token', 'getMidtransToken')->name('customer.orders.get_midtrans_token');
        Route::post('/midtrans/callback', 'callback');
        Route::get('/checkout/thanks', 'thanks')->name('checkout.thanks');
        Route::get('/orders/{id}/rate', 'TampilanRating')->name('customer.rating.rate');
        Route::post('/orders/{id}/rate', 'KirimRating')->name('rating.rate');

    });

    // Route::controller(ReviewController::class)->group(function () {
    //     Route::get('/rate', 'index')->name('rate.index');        // tampilkan rate.blade
    //     Route::post('/store/review', 'StoreReview')->name('store.review'); // simpan review
    // });

        
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

    Route::controller(ReportController::class)->group(function () {
        Route::get('/admin/all/reports', 'AdminAllReports')->name('admin.all.reports');
        Route::post('/admin/search/bydate', 'AdminSearchByDate')->name('admin.search.bydate');
        Route::get('/admin/search-by-date/result', 'AdminSearchByDateResult')->name('admin.search.bydate.result');
        Route::post('/admin/search/bymonth', 'AdminSearchByMonth')->name('admin.search.bymonth');
        Route::get('/admin/search-by-month/result', 'AdminSearchByMonthResult')->name('admin.search.bymonth.result');
        Route::post('/admin/search/byyear', 'AdminSearchByYear')->name('admin.search.byyear');
        Route::get('admin/search/byyear/result/{year}', 'AdminSearchByYearResult')->name('admin.search.byyear.result');
    });
    
    Route::controller(ManageController::class)->group(function () {
        Route::get('/pending/toko', 'PendingToko')->name('pending.toko');
        Route::get('/clientchangeStatus', 'ClientChangeStatus');
        Route::get('/approve/toko', 'ApproveToko')->name('approve.toko');
    });
    Route::controller(ManageOrderController::class)->group(function () {
        Route::get('/order/order/list', [OrderController::class, 'orderList'])->name('user.orders');
        Route::get('/order/{id}', [OrderController::class, 'show'])->name('user.order.details');

        Route::get('/orders/pending', 'PendingOrders')->name('admin.pending.orders');
        Route::get('/orders/confirm', 'ConfirmOrders')->name('admin.confirm.orders');
        Route::get('/orders/processing', 'ProcessingOrders')->name('admin.processing.orders');
        Route::get('/orders/delivered', 'DeliveredOrders')->name('admin.delivered.orders');
        Route::get('/orders/details/{id}', 'OrderDetails')->name('admin.order.details');
    });

    Route::controller(PurchasesController::class)->group(function(){
        Route::get('/all/purchases', 'index')->name('admin.backend.purchases.all');
        Route::get('/add/purchases', 'create')->name('admin.backend.purchases.add');
        Route::post('/add/purchases/store',  'store')->name('admin.purchases.store');
    });

    Route::controller(ManageRatingController::class)->group(function(){
        Route::get('/admin/rating', 'AdminRating')->name('admin.backend.rating.admin_rating');
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

Route::middleware(['status', 'client'])->group(function () {
    Route::get('/sales-report', [ClientController::class, 'ClientLaporan'])->name('sales.report');
    Route::get('/product-details/{id}', [ClientController::class, 'getProductDetails'])->name('product.details');

    Route::get('/client/pesanan', [ClientController::class, 'ClientPesanan'])->name('client.pesanan');
    Route::get('/client/pesanan/pending', [ClientController::class, 'pendingOrders'])->name('client.pending.orders');
    Route::get('/client/pesanan/confirm', [ClientController::class, 'confirmOrders'])->name('client.confirm.orders');
    Route::get('/client/pesanan/processing', [ClientController::class, 'processingOrders'])->name('client.processing.orders');
    Route::get('/client/pesanan/delivered', [ClientController::class, 'deliveredOrders'])->name('client.delivered.orders');

    Route::post('/client/pesanan/confirm-received/{id}', [ClientController::class, 'confirmReceived'])->name('client.pesanan.confirm_received');
    Route::post('/client/pesanan/cancel/{id}', [ClientController::class, 'cancelOrder'])->name('client.pesanan.cancel');
    Route::post('/client/pesanan/execute/{id}', [ClientController::class, 'executeOrder'])->name('client.pesanan.execute');
    Route::post('/client/pesanan/confirm/{id}', [ClientController::class, 'confirmOrder'])->name('client.pesanan.confirm');
    Route::post('/client/pesanan/process/{id}', [ClientController::class, 'processOrder'])->name('client.pesanan.process');
    Route::post('/client/pesanan/complete/{id}', [ClientController::class, 'completeOrder'])->name('client.pesanan.complete');
    Route::get('/client/pesanan/{id}', [ClientController::class, 'orderDetails'])->name('client.pesanan.details');

    Route::get('/client/pesanan/executed', [ClientController::class, 'executedOrders'])->name('client.pesanan.executed');

    Route::get('/client/laporan', [ClientController::class, 'ClientLaporan'])->name('client.laporan');
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