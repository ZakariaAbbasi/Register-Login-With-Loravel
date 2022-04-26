<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MagicController;
use App\Http\Controllers\Auth\SocialsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\TwoFactorsController;
use Illuminate\Support\Facades\Log;

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


Route::prefix('auth')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegisterationForm'])->name('auth.register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('auth.login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('auth.login');
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');
    Route::get('/email/send-verification', [VerificationController::class, 'send'])->name('auth.email.send.verification');
    Route::get('/email/verify', [VerificationController::class, 'verify'])->name('auth.email.verify');
    Route::get('/password-forget', [ForgetPasswordController::class, 'showForgetForm'])->name('auth.forget.password.form');
    Route::post('/password-forget', [ForgetPasswordController::class, 'sendResetLink'])->name('auth.forget.password');
    Route::get('/password-reset', [ResetPasswordController::class, 'showResetForm'])->name('auth.password.reset.form');
    Route::post('/password-reset', [ResetPasswordController::class, 'reset'])->name('auth.password.reset');
    Route::get('/redirect/{provider}', [SocialsController::class, 'redirectToProvider'])->name('auth.login.provider.redirect');
    Route::get('{provider}/callback', [SocialsController::class, 'handleProviderCallback'])->name('auth.login.provider.callback');
    Route::get('/magic/login', [MagicController::class, 'showMagicForm'])->name('auth.magic.login.form');
    Route::post('/magic/login', [MagicController::class, 'sendToken'])->name('auth.magic.send.token');
    Route::get('/magic/{token}', [MagicController::class, 'login'])->name('auth.magic.login');

    Route::get('/two-factor/toggle', [TwoFactorsController::class, 'toggle'])->name('auth.two.factor.toggle.form');
    Route::get('/two-factor/activate', [TwoFactorsController::class, 'activate'])->name('auth.two.factor.activate');
    Route::get('/two-factor/code', [TwoFactorsController::class, 'showEnterCodeForm'])->name('auth.two.factor.code.form');
    Route::post('/two-factor/code', [TwoFactorsController::class, 'confirmCode'])->name('auth.two.factor.code');
    Route::get('/two-factor/deactivate', [TwoFactorsController::class, 'deactivate'])->name('auth.two.factor.deactivate');
    Route::get('login/code', [LoginController::class, 'showCodeForm'])->name('auth.login.code.form');
    Route::post('login/code', [LoginController::class, 'confirmCode'])->name('auth.login.code');
    Route::get('/two-factor/resent', [TwoFactorsController::class, 'resent'])->name('auth.two.factor.resent');
});
