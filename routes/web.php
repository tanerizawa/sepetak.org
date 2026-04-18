<?php

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\MemberCardController;
use App\Http\Controllers\AdvocacyProgramPublicController;
use App\Http\Controllers\AgrarianCasePublicController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberRegistrationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('beranda');

Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/halaman/kontak', [PageController::class, 'show'])->defaults('slug', 'kontak')->name('contact.show');

Route::get('/daftar-anggota', [MemberRegistrationController::class, 'create'])->name('member-registration.create');
Route::post('/daftar-anggota', [MemberRegistrationController::class, 'store'])->name('member-registration.store');

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

Route::middleware(['auth'])->group(function (): void {
    Route::get('/admin/anggota/{member}/kartu-kta', [MemberCardController::class, 'show'])
        ->name('admin.members.card');
});

Route::middleware(['web', 'auth'])
    ->prefix('admin/exports')
    ->name('admin.exports.')
    ->group(function (): void {
        Route::get('members.pdf', [ExportController::class, 'membersPdf'])->name('members.pdf');
        Route::get('agrarian-cases.pdf', [ExportController::class, 'agrarianCasesPdf'])->name('agrarian-cases.pdf');
    });
