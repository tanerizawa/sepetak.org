<?php

use Database\Seeders\PublicProfilePagesSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Menjamin halaman profil publik (termasuk AD/ART) ada di tabel pages setelah deploy,
 * agar muncul di Filament /admin/pages dan URL /halaman/… tidak 404.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new PublicProfilePagesSeeder)->run();
    }

    public function down(): void
    {
        // Konten halaman tidak dihapus saat rollback.
    }
};
