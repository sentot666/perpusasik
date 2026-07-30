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
use App\Http\Controllers\GuestBookController;
use App\Http\Controllers\AgendaController;
// ── Language Switcher ──────────────────────────────────────────────────────────
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('lang.switch');

// ── OPAC (public) ─────────────────────────────────────────────────────────────
Route::get('/', [OpacController::class, 'index'])->name('opac.index');
Route::get('/katalog', [OpacController::class, 'katalog'])->name('opac.katalog');
Route::get('/opac/agenda', [OpacController::class, 'agenda'])->name('opac.agenda');
Route::get('/opac/buku/{book}', [OpacController::class, 'show'])->name('opac.show');

// ── Buku Tamu Mandiri (public) ────────────────────────────────────────────────
Route::get('/isi-buku-tamu', [GuestBookController::class, 'visitorForm'])->name('guest-books.visitor');
Route::post('/isi-buku-tamu', [GuestBookController::class, 'visitorStore'])->name('guest-books.visitor.store');

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/login-anggota', [LoginController::class, 'showMemberLoginForm'])->name('login.member');
    Route::post('/login', [LoginController::class, 'login']);

    // Password Reset
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');
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

    // ── Buku Tamu / Aktivitas Harian ───────────────────────────────────────────
    Route::get('/guest-books/export', [GuestBookController::class, 'export'])->name('guest-books.export');
    Route::resource('guest-books', GuestBookController::class)->only(['index', 'store', 'destroy']);
    Route::resource('agendas', AgendaController::class);

    // ── Katalogisasi ────────────────────────────────────────────────────────
    Route::middleware(['can:view-books'])->group(function () {
        Route::resource('books', BookController::class);
        Route::get('/books/{book}/print-barcode', [BookController::class, 'printBarcode'])->name('books.barcode');
        Route::resource('authors', AuthorController::class);
        Route::resource('publishers', PublisherController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('locations', LocationController::class);

        // Book Items (eksemplar) - nested
        Route::resource('books.items', BookItemController::class)->shallow();
        Route::get('/book-items/{item}/barcode', [BookItemController::class, 'barcode'])->name('book-items.barcode');
    });

    // ── Anggota ─────────────────────────────────────────────────────────────
    Route::middleware(['can:view-members'])->group(function () {
        Route::post('members/print-bulk', [MemberController::class, 'printBulk'])->name('members.print-bulk');
        Route::get('members/{member}/print-card', [MemberController::class, 'printCard'])->name('members.print-card');
        Route::resource('members', MemberController::class);
        Route::get('/members/{member}/print-card', [MemberController::class, 'printCard'])->name('members.print-card');
        Route::get('/members/{member}/history', [MemberController::class, 'history'])->name('members.history');
    });

    // ── Sirkulasi ────────────────────────────────────────────────────────────
    Route::middleware(['can:process-loans'])->group(function () {
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
    });

    // ── Laporan ──────────────────────────────────────────────────────────────
    Route::middleware(['can:view-reports'])->group(function () {
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/sirkulasi', [ReportController::class, 'circulation'])->name('reports.circulation');
        Route::get('/laporan/anggota', [ReportController::class, 'members'])->name('reports.members');
        Route::get('/laporan/koleksi', [ReportController::class, 'collection'])->name('reports.collection');
        Route::get('/laporan/keterlambatan', [ReportController::class, 'overdue'])->name('reports.overdue');
        Route::get('/laporan/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    });

    // ── Admin only ───────────────────────────────────────────────────────────
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware('can:manage-settings')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

});

// 🔴 Member Portal Routes
Route::middleware(['auth'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\Member\DashboardController::class)->name('dashboard')->middleware('can:view-history');
    
    // Catalog & Detail
    Route::get('/catalog', \App\Http\Controllers\Member\CatalogController::class)->name('catalog')->middleware('can:view-history');
    Route::get('/catalog/{book}', [\App\Http\Controllers\Member\CatalogController::class, 'show'])->name('catalog.show')->middleware('can:view-history');
    
    // My Books (Active Loans)
    Route::get('/my-books', \App\Http\Controllers\Member\MyBookController::class)->name('my-books')->middleware('can:view-history');
    
    // Loan History
    Route::get('/loans', \App\Http\Controllers\Member\LoanHistoryController::class)->name('loans')->middleware('can:view-history');
    
    // Fines
    Route::get('/fines', \App\Http\Controllers\Member\FineController::class)->name('fines')->middleware('can:view-history');
    
    // Wishlists
    Route::get('/wishlist', [\App\Http\Controllers\Member\WishlistController::class, 'index'])->name('wishlist')->middleware('can:view-history');
    Route::post('/wishlist/{book}', [\App\Http\Controllers\Member\WishlistController::class, 'store'])->name('wishlist.store')->middleware('can:view-history');
    Route::delete('/wishlist/{book}', [\App\Http\Controllers\Member\WishlistController::class, 'destroy'])->name('wishlist.destroy')->middleware('can:view-history');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\Member\ProfileController::class, 'index'])->name('profile')->middleware('can:view-history');
    Route::get('/profile/edit', [\App\Http\Controllers\Member\ProfileController::class, 'edit'])->name('profile.edit')->middleware('can:view-history');
    Route::put('/profile', [\App\Http\Controllers\Member\ProfileController::class, 'update'])->name('profile.update')->middleware('can:view-history');
    Route::put('/profile/password', [\App\Http\Controllers\Member\ProfileController::class, 'updatePassword'])->name('profile.password')->middleware('can:view-history');
});
