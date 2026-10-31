<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberApprovalController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Guru\AcademicController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\MemberActivationController;
use App\Http\Controllers\MemberReportController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\SecureChatController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'beranda'])->name('home');
Route::view('/profil', 'profil')->name('profil');
Route::get('/prestasi', [PageController::class, 'prestasi'])->name('public.prestasi');
Route::get('/samudra-biru', [PageController::class, 'samudraBiru'])->name('public.samudra-biru');
Route::get('/teknik-besi', [PageController::class, 'teknikBesi'])->name('public.teknik-besi');
Route::get('/tenaga-ahli', [PageController::class, 'tenagaAhli'])->name('public.tenaga-ahli');
Route::get('/workshop', [PageController::class, 'workshop'])->name('public.workshop');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:6,1');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('throttle:6,1');
});

Route::get('/aktivasi/{user}/{token}', [MemberActivationController::class, 'create'])
    ->middleware('throttle:6,1')->name('activation.create');
Route::post('/aktivasi/{user}/{token}', [MemberActivationController::class, 'store'])
    ->middleware('throttle:6,1')->name('activation.store');

Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function (): void {
    Route::middleware('role:anggota')->prefix('anggota')->name('member.')->group(function (): void {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::get('/portal', [MemberReportController::class, 'index'])->name('portal');
        Route::post('/laporan', [MemberReportController::class, 'store'])->name('laporan.store');
        Route::get('/laporan/{laporan}/download', [MemberReportController::class, 'download'])->name('laporan.download');
    });

    Route::middleware('role:guru,admin')->prefix('guru')->name('guru.')->group(function (): void {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::get('/akademik', [AcademicController::class, 'index'])->name('academic');
        Route::post('/nilai', [AcademicController::class, 'storeScore'])->name('nilai.store');
        Route::post('/jadwal', [AcademicController::class, 'storeSchedule'])->name('jadwal.store');
        Route::post('/pengumuman', [AcademicController::class, 'storeAnnouncement'])->name('pengumuman.store');
        Route::patch('/laporan/{laporan}/status', [AcademicController::class, 'updateReport'])->name('laporan.status');
        Route::get('/laporan/{laporan}/download', [MemberReportController::class, 'download'])->name('laporan.download');
    });

    Route::middleware('role:anggota,guru,admin')->group(function (): void {
        Route::get('/chat', [SecureChatController::class, 'index'])->name('chat.index');
        Route::post('/chat', [SecureChatController::class, 'store'])->name('chat.store');
        Route::get('/chat/{chat}/media', [SecureChatController::class, 'media'])->name('chat.media');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/members', [MemberApprovalController::class, 'index'])->name('members.index');
        Route::patch('/members/{user}/approve', [MemberApprovalController::class, 'approve'])->name('members.approve');
        Route::patch('/members/{user}/reject', [MemberApprovalController::class, 'reject'])->name('members.reject');
        Route::post('/members/{user}/activation', [MemberApprovalController::class, 'resendActivation'])->name('members.activation.resend');
    });
});
