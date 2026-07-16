<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BookItemController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CirculationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\OpacController;
use App\Http\Controllers\ProfileController;

// ── OPAC (public) ─────────────────────────────────────────────────────────────
Route::get('/', [OpacController::class, 'index'])->name('opac.index');
Route::get('/opac/buku/{book}', [OpacController::class, 'show'])->name('opac.show');

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated Admin Routes ────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ── Katalogisasi ────────────────────────────────────────────────────────
    Route::resource('books', BookController::class);
    Route::get('/books/{book}/print-barcode', [BookController::class, 'printBarcode'])->name('books.barcode');
    Route::resource('authors', AuthorController::class);
    Route::resource('publishers', PublisherController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('locations', LocationController::class);

    // Book Items (eksemplar) - nested
    Route::resource('books.items', BookItemController::class)->shallow();
    Route::get('/book-items/{item}/barcode', [BookItemController::class, 'barcode'])->name('book-items.barcode');

    // ── Anggota ─────────────────────────────────────────────────────────────
    Route::resource('members', MemberController::class);
    Route::get('/members/{member}/print-card', [MemberController::class, 'printCard'])->name('members.print-card');
    Route::get('/members/{member}/history', [MemberController::class, 'history'])->name('members.history');

    // ── Sirkulasi ────────────────────────────────────────────────────────────
    Route::get('/sirkulasi', [CirculationController::class, 'index'])->name('circulations.index');
    Route::get('/sirkulasi/pinjam', [CirculationController::class, 'loanForm'])->name('circulations.loan');
    Route::post('/sirkulasi/pinjam', [CirculationController::class, 'storeLoan'])->name('circulations.store-loan');
    Route::get('/sirkulasi/kembali', [CirculationController::class, 'returnForm'])->name('circulations.return');
    Route::post('/sirkulasi/kembali', [CirculationController::class, 'processReturn'])->name('circulations.process-return');
    Route::get('/sirkulasi/{circulation}', [CirculationController::class, 'show'])->name('circulations.show');
    Route::post('/sirkulasi/{circulation}/perpanjang', [CirculationController::class, 'renew'])->name('circulations.renew');
    Route::post('/sirkulasi/{circulation}/bayar-denda', [CirculationController::class, 'payFine'])->name('circulations.pay-fine');

    // AJAX helpers
    Route::get('/api/members/search', [MemberController::class, 'ajaxSearch'])->name('members.ajax-search');
    Route::get('/api/book-items/lookup', [BookItemController::class, 'ajaxLookup'])->name('book-items.ajax-lookup');

    // ── Laporan ──────────────────────────────────────────────────────────────
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/sirkulasi', [ReportController::class, 'circulation'])->name('reports.circulation');
    Route::get('/laporan/anggota', [ReportController::class, 'members'])->name('reports.members');
    Route::get('/laporan/koleksi', [ReportController::class, 'collection'])->name('reports.collection');
    Route::get('/laporan/keterlambatan', [ReportController::class, 'overdue'])->name('reports.overdue');
    Route::get('/laporan/export/{type}', [ReportController::class, 'export'])->name('reports.export');

    // ── Admin only ───────────────────────────────────────────────────────────
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware('can:manage-settings')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

});
