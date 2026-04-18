<?php

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\MemberCardController;
use App\Http\Controllers\AdvocacyProgramPublicController;
use App\Http\Controllers\AgrarianCasePublicController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberRegistrationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('beranda');

Route::permanentRedirect('/halaman/kontak', '/kontak');
Route::get('/kontak', [ContactController::class, 'show'])->name('contact.show');
Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/daftar-anggota', [MemberRegistrationController::class, 'create'])->name('member-registration.create');
Route::post('/daftar-anggota', [MemberRegistrationController::class, 'store'])
    ->middleware(\Spatie\Honeypot\ProtectAgainstSpam::class)
    ->name('member-registration.store');

Route::permanentRedirect('/berita', '/artikel');
Route::permanentRedirect('/berita/{slug}', '/artikel/{slug}');

Route::get('/artikel', [PostController::class, 'index'])->name('posts.index');
Route::get('/artikel/penulis/{id}', [PostController::class, 'author'])->whereNumber('id')->name('posts.author');
Route::get('/artikel/kategori/{slug}', [PostController::class, 'category'])->name('posts.category');
Route::get('/artikel/tag/{slug}', [PostController::class, 'tag'])->name('posts.tag');
Route::get('/artikel/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/agenda', [EventController::class, 'index'])->name('events.index');

Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri/{slug}', [GalleryController::class, 'show'])->name('gallery.show');

Route::get('/kasus-agraria', [AgrarianCasePublicController::class, 'index'])->name('agrarian-cases.index');
Route::get('/kasus-agraria/{case_code}', [AgrarianCasePublicController::class, 'show'])->name('agrarian-cases.show');

Route::get('/program-advokasi', [AdvocacyProgramPublicController::class, 'index'])->name('advocacy-programs.index');
Route::get('/program-advokasi/{program_code}', [AdvocacyProgramPublicController::class, 'show'])->name('advocacy-programs.show');

Route::get('/sitemap.xml', [FeedController::class, 'sitemap'])->name('sitemap');
Route::get('/feed.xml', [FeedController::class, 'rss'])->name('feed');
Route::get('/robots.txt', [FeedController::class, 'robots'])->name('robots');

Route::get('/health', HealthController::class)->name('health');

Route::redirect('/login', '/admin/login')->name('login');
Route::redirect('/masuk', '/admin/login');
Route::redirect('/admin/logout', '/admin');

/*
 * POST /admin/login — fallback otentikasi HTML klasik (tanpa Livewire / JS bermasalah).
 * Filament hanya mendaftarkan GET /admin/login; tanpa rute ini, POST dari browser → 405.
 * Livewire login normal tetap memakai POST /livewire/update.
 */
Route::post('/admin/login', function (Request $request) {
    $validated = $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
        'remember' => ['nullable'],
    ]);

    if (! auth()->attempt(
        ['email' => $validated['email'], 'password' => $validated['password']],
        $request->boolean('remember'),
    )) {
        return redirect()
            ->to(Filament::getPanel('admin')->getLoginUrl() ?? '/admin/login')
            ->withErrors(['data.email' => __('filament-panels::pages/auth/login.messages.failed')])
            ->withInput(['data' => ['email' => $validated['email']]]);
    }

    $request->session()->regenerate();

    /** @var User|null $user */
    $user = auth()->user();
    $panel = Filament::getPanel('admin');

    if (! $user || ! $user->canAccessPanel($panel)) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->to(Filament::getPanel('admin')->getLoginUrl() ?? '/admin/login')
            ->withErrors(['data.email' => __('filament-panels::pages/auth/login.messages.failed')])
            ->withInput(['data' => ['email' => $validated['email']]]);
    }

    return redirect()->intended(Filament::getPanel('admin')->getUrl());
})->name('admin.login.post');

Route::middleware(['auth'])->group(function (): void {
    Route::get('/admin/anggota/{member}/kartu-kta', [MemberCardController::class, 'show'])
        ->name('admin.members.card');
});

Route::middleware(['web', 'auth'])
    ->prefix('admin/exports')
    ->name('admin.exports.')
    ->group(function (): void {
        Route::get('members.pdf', [ExportController::class, 'membersPdf'])
            ->middleware('can:manage-members')
            ->name('members.pdf');
        Route::get('agrarian-cases.pdf', [ExportController::class, 'agrarianCasesPdf'])
            ->middleware('can:manage-cases')
            ->name('agrarian-cases.pdf');
    });
