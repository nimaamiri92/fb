<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Site\AddressController;
use App\Http\Controllers\Site\BranchControllers;
use App\Http\Controllers\Site\ContactUsController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\OrderController;
use App\Http\Controllers\Site\PaymentController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\UserController;
use App\Http\Controllers\Site\WishListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('site.login');
Route::post('login', 'Auth\LoginController@login')->name('site.login.store');
Route::get('logout', 'Auth\LoginController@logout')->name('site.logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('site.register');
Route::post('register', 'Auth\RegisterController@register')->name('site.register.store');
Route::get('verify/{mobile}', 'Auth\RegisterController@showVerificationForm')->name('site.verify');
Route::post('verify', 'Auth\RegisterController@verify')->name('site.verify.store');
Route::get('/resend/{mobile}', 'Auth\RegisterController@resendSms')->name('site.verify.resend.sms');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLink')->name('password.email');
Route::get('password/reset/sent-successfully',
    'Auth\ForgotPasswordController@sentSuccessfully')->name('password.reset.sentSuccessfully');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset.store');


Route::group(['as' => 'site.'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::post('/product/{product}/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
    Route::any('payment/callback', [PaymentController::class, 'callbackUrl'])->name('payment.callbackUrl');
});


Route::group(['middleware' => ['auth:site'], 'as' => 'site.'], function () {
    Route::post('/product/{product}/add-to-wish-list',
        [WishListController::class, 'store'])->name('product.add_to_wish_list');
    Route::get('wishlist', [WishListController::class, 'index'])
        ->name('dashboard.wishlist');
    Route::group(['prefix' => 'cart'], function () {
        Route::get('show', [CartController::class, 'show'])->name('cart.show');
        Route::group(['prefix' => '{cart}'], function () {
            Route::group(['prefix' => 'cartItem'], function () {
                Route::delete('{cartItem}/delete', [CartController::class, 'removeCartItem'])->name('cart.delete');
            });
        });
    });


    Route::get('address/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('address/store', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('address', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('address/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::post('address/{address}/update', [AddressController::class, 'update'])->name('addresses.update');
    Route::get('address/{address}/delete', [AddressController::class, 'delete'])->name('addresses.delete');


    Route::post('order/store', [OrderController::class, 'createOrderAndGoToGateway'])->name('order.store');


    Route::get('dashboard', [UserController::class, 'index'])->name('dashboard.home');
    Route::get('edit-profile', [UserController::class, 'editProfile'])->name('dashboard.edit-profile');
    Route::post('save-edit-profile', [UserController::class, 'saveEditProfile'])->name('dashboard.save-edit-profile');
    Route::get('order-history', [UserController::class, 'orderHistory'])->name('dashboard.order-history');
    Route::get('order-history-details/{order}', [UserController::class, 'orderHistoryDetails'])
        ->name('dashboard.order-history-details');
});

Route::get('about-us',[HomeController::class,'aboutUs'])->name('site.about-us');
Route::get('contact-us',[ContactUsController::class,'create'])->name('site.contact-us.create');
Route::get('branches',[BranchControllers::class,'index'])->name('site.branch.index');
Route::post('contact-us/store',[ContactUsController::class,'store'])->name('site.contact-us.store');


Route::get('certificates', function () {
    return view('site.layouts.certificates');
})->name('site.certificates');

Route::get('help', function () {
    return view('site.layouts.help');
})->name('site.help');

Route::get('size-guide', function () {
    return view('site.layouts.size-guide');
})->name('site.size-guide');

Route::get('transfer', function () {
    return view('site.layouts.transfer');
})->name('site.transfer');


Route::get('tt', [\App\Http\Controllers\ZahraController::class, 'index']);//dont remove it,its for Zahra
