<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\TelegramAuthController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ViewTicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPasswordTelegramController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/forgot-password-telegram', [ForgotPasswordTelegramController::class, 'showEmailForm'])
    ->name('telegram.password.request');

Route::post('/forgot-password-telegram/send-otp', [ForgotPasswordTelegramController::class, 'sendOtp'])
    ->name('telegram.password.sendOtp');

Route::get('/forgot-password-telegram/verify', [ForgotPasswordTelegramController::class, 'showOtpForm'])
    ->name('telegram.password.verifyForm');

Route::post('/forgot-password-telegram/verify', [ForgotPasswordTelegramController::class, 'verifyOtp'])
    ->name('telegram.password.verifyOtp');

Route::get('/forgot-password-telegram/reset', [ForgotPasswordTelegramController::class, 'showResetForm'])
    ->name('telegram.password.resetForm');

Route::post('/forgot-password-telegram/reset', [ForgotPasswordTelegramController::class, 'resetPassword'])
    ->name('telegram.password.reset');

Route::match(['GET', 'POST'], '/telegram/webhook', [TelegramWebhookController::class, 'handle']);
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'Login']);
Route::get('/photo/{filename}', [ProfileController::class, 'servePhoto'])->name('profile.photo');

route::middleware(['guest'])->group(function () {

    Route::get('/register', [RegisterController::class, 'register']);
    Route::post('/register', [RegisterController::class, 'createAccount']);
});

route::get('/home', function () {
    return redirect('/');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'indexadmin'])->name('admin.index')->middleware('userAkses:admin');
    Route::get('/manageuser', [AdminController::class, 'ViewManageUser'])->name('admin.manageuser')->middleware('userAkses:admin');
    Route::get('/user/create', [AdminController::class, 'create'])->name('user.create')->middleware('userAkses:admin');
    Route::get('/edituser/{id}', [AdminController::class, 'editUsers'])->name('admin.EditUser')->middleware('userAkses:admin');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update')->middleware('userAkses:admin');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('user.destroy')->middleware('userAkses:admin');
    Route::post('/admin/store', [AdminController::class, 'storeUser'])->name('user.store')->middleware('userAkses:admin');

    // Routes that allow both user and admin access
    Route::get('/teknisi', [TeknisiController::class, 'index'])->name('teknisi.index')->middleware('userAkses:pengurus,pemilik,admin');
    Route::get('/assign', [TeknisiController::class, 'viewasigne'])->name('teknisi.viewasigne')->middleware('userAkses:pengurus,pemilik,admin');
    Route::get('/close', [TeknisiController::class, 'closeticket'])->name('teknisi.closeticket')->middleware('userAkses:pengurus,pemilik,admin');
    Route::get('/ListTicket', [TeknisiController::class, 'ListTicket'])->name('teknisi.ListTicket')->middleware('userAkses:pengurus,pemilik,admin');
    Route::put('/teknisi/ticket/{id}', [TicketController::class, 'updateteknisi'])->name('ticketsteknisi.update')->middleware('userAkses:pengurus,pemilik,admin');
    Route::put('/close/ticket/{id}', [TicketController::class, 'closeticket'])->name('ticketsteknisi.close')->middleware('userAkses:pengurus,pemilik,admin');
    Route::delete('/teknisi/ticket/{id}', [TicketController::class, 'destroyteknisi'])->name('ticketsteknisi.destroy')->middleware('userAkses:pengurus,pemilik,admin');
    Route::get('/viewtickets/{id}', [ViewTicketController::class, 'viewticketteknisi'])->name('viewticketteknisi.index')->middleware('userAkses:pengurus,pemilik,admin');
    Route::put('/tickets/{id}/assign', [TicketController::class, 'assign'])->name('tickets.assign')->middleware('userAkses:pengurus,pemilik,admin');
    Route::put('/tickets/{id}/cancel_assign', [TicketController::class, 'cancelAssign'])->name('tickets.cancel_assign')->middleware('userAkses:pengurus,pemilik,admin');
    Route::post('/tickets/{id}/requestFollowup', [TicketController::class, 'requestFollowup'])->name('ticketsteknisi.requestFollowup')->middleware('userAkses:pengurus,pemilik,admin');
    Route::put('/tickets/{id}/cancel-request-followup', [TicketController::class, 'cancelRequestFollowUp'])->name('ticketsteknisi.cancelRequestFollowUp')->middleware('userAkses:pengurus,pemilik,admin');
    Route::get('/ProfileTeknisi', [ProfileController::class, 'teknisiprofile'])->name('teknisi.profile')->middleware('userAkses:pengurus,pemilik,admin');
    Route::post('/ProfileTeknisi/update', [ProfileController::class, 'updatecustomer'])->name('teknisi.profileupdate')->middleware('userAkses:pengurus,pemilik,admin,penyewa');
    // Route untuk eskalasi
    Route::get('/tickets/escalation', [TeknisiController::class, 'viewEscalation'])->name('tickets.viewEscalation')->middleware('userAkses:pengurus,pemilik,admin');
    Route::put('/tickets/{id}/escalation', [TicketController::class, 'acceptEscalation'])->name('tickets.accept_escalation')->middleware('userAkses:pengurus,pemilik,admin');
    Route::put('/tickets/{id}/contribute', [TicketController::class, 'contribute'])->name('tickets.contribute')->middleware('userAkses:pengurus,pemilik,admin');
    // Route untuk pengumuman
    Route::get('/managepengumuman', [PengumumanController::class, 'managePengumuman'])->name('pengumuman.index')->middleware('userAkses:pengurus,pemilik,admin');
    Route::get('/managepengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create')->middleware('userAkses:pengurus,pemilik,admin');
    Route::post('/managepengumuman/store', [PengumumanController::class, 'store'])->name('pengumuman.store')->middleware('userAkses:pengurus,pemilik,admin');
    Route::get('/managepengumuman/{id}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit')->middleware('userAkses:pengurus,pemilik,admin');
    Route::put('/managepengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update')->middleware('userAkses:pengurus,pemilik,admin');
    Route::delete('/managepengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy')->middleware('userAkses:pengurus,pemilik,admin');
    // Route untuk laporan
    Route::get('/teknisi/report/monthly', [TeknisiController::class, 'downloadMonthlyReport'])->name('teknisi.report.monthly')->middleware('userAkses:pengurus,pemilik,admin');

    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index')->middleware('userAkses:penyewa,admin');
    Route::get('/Active', [CustomerController::class, 'viewprocess'])->name('customer.viewprocess')->middleware('userAkses:penyewa,admin');
    Route::get('/Profile/edit', [ProfileController::class, 'index'])->name('customer.profile')->middleware('userAkses:penyewa,admin');

    Route::get('customer/viewticket/{id}', [ViewTicketController::class, 'index'])->name('viewtickets.index')->middleware('userAkses:penyewa,admin');
    Route::post('/Profile/update', [ProfileController::class, 'updatecustomer'])->name('customer.profileupdate')->middleware('userAkses:pengurus,pemilik,admin,penyewa');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password')->middleware('userAkses:pengurus,pemilik,admin,penyewa');
    Route::get('/customer/ticket', [TicketController::class, 'index'])->name('customer.tickets')->middleware('userAkses:penyewa,admin');
    Route::post('/customer/ticket', [TicketController::class, 'store'])->name('tickets.store')->middleware('userAkses:penyewa,admin');
    Route::put('/customer/ticket/{id}', [TicketController::class, 'update'])->name('tickets.update')->middleware('userAkses:penyewa,admin');
    Route::put('/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update')->middleware('userAkses:penyewa,admin');

    Route::delete('/customer/ticket/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy')->middleware('userAkses:penyewa,admin');
    Route::get('/logout', [LoginController::class, 'Logout'])->name('logout');

    Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('comments.store')->middleware('userAkses:penyewa,admin');;
    Route::post('/tickets/{ticket}/teknisicomments', [TicketCommentController::class, 'teknisiComment'])->name('comments.teknisiComment')->middleware('userAkses:pengurus,pemilik,admin');
    Route::match(['GET', 'POST'], '/telegram/auth/{ticket_id}', [TelegramAuthController::class, 'telegramAuthorize'])->name('telegram.auth');
    Route::match(['GET', 'POST'], '/profile/telegram/auth', [TelegramAuthController::class, 'telegramAuthorizeFromProfile'])->name('telegram.from.profile');
    Route::post('/telegram/logout', [TelegramAuthController::class, 'telegramLogout'])->name('telegram.logout');

    Route::get('/tickets/{ticket}/download-image', [TicketController::class, 'downloadImage'])->name('tickets.downloadImage');
    Route::get('/tickets/{ticket}/download-image/teknisi', [ViewTicketController::class, 'downloadImage'])->name('ticketsteknisi.downloadImage');

    Route::view('/test-telegram', 'test-telegram');
    // routes/web.php
    Route::get('/telegram/callback', function (\Illuminate\Http\Request $request) {
        dd($request->all());
    });
});
