<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up(): void
    {
        // Peringatan: Proses ini membaca kolom 'nik' sebagai teks biasa
        // dan menyimpannya kembali sebagai string terenkripsi
        DB::table('members')->whereNotNull('nik')->orderBy('id')->chunk(500, function ($members) {
            foreach ($members as $member) {
                // Periksa apakah nilainya belum dienkripsi (string enkripsi Laravel dimulai dengan 'eyJ')
                if (!str_starts_with($member->nik, 'eyJ')) {
                    DB::table('members')->where('id', $member->id)->update([
                        'nik' => Crypt::encryptString($member->nik),
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        DB::table('members')->whereNotNull('nik')->orderBy('id')->chunk(500, function ($members) {
            foreach ($members as $member) {
                // Dekripsi kembali ke plaintext jika dimulai dengan string enkripsi
                if (str_starts_with($member->nik, 'eyJ')) {
                    try {
                        DB::table('members')->where('id', $member->id)->update([
                            'nik' => Crypt::decryptString($member->nik),
                        ]);
                    } catch (\Exception $e) {
                        // Abaikan jika gagal dekripsi
                    }
                }
            }
        });
    }
};