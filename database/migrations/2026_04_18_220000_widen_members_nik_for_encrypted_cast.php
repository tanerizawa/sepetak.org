<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cast `encrypted` pada `nik` menyimpan JSON panjang; varchar(32) memicu
     * SQLSTATE[22001] di PostgreSQL saat menyimpan anggota dengan NIK terisi.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->text('nik')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('nik', 32)->nullable()->change();
        });
    }
};
