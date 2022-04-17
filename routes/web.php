<?php

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\ResetPasswordController;

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

Route::get('/', function () {
    return view('welcome');
 
})->name('home');


Route::prefix('auth')->group(function(){
    Route::get('/register',[RegisterController::class, 'showRegisterationForm'])->name('auth.register.form');
    Route::post('/register',[RegisterController::class, 'register'])->name('auth.register');
    Route::get('/login',[LoginController::class, 'showLoginForm'])->name('auth.login.form');
    Route::post('/login',[LoginController::class, 'login'])->name('auth.login');
    Route::get('/logout',[LoginController::class, 'logout'])->name('auth.logout');
    Route::get('/email/send-verification',[VerificationController::class, 'send'])->name('auth.email.send.verification');
    Route::get('/email/verify',[VerificationController::class, 'verify'])->name('auth.email.verify');
    Route::get('/password-forget', [ForgetPasswordController::class, 'showForgetForm'])->name('auth.forget.password.form');
    Route::post('/password-forget', [ForgetPasswordController::class, 'sendResetLink'])->name('auth.forget.password');
    Route::get('/password-reset', [ResetPasswordController::class, 'showResetForm'])->name('auth.password.reset.form');


});

