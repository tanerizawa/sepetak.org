<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Kontak publik kanonik di /kontak (ContactController). Baris Page slug kontak menyebabkan duplikasi isi & admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        Page::query()->where('slug', 'kontak')->each(function (Page $page): void {
            $page->delete();
        });
    }

    public function down(): void
    {
        // Pemulihan konten CMS tidak otomatis.
    }
};
