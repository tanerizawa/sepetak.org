<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Memperbarui isi halaman /halaman/tentang-kami tanpa menjalankan seluruh DatabaseSeeder.
 */
class TentangKamiContentUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::query()->where('slug', 'tentang-kami')->first();

        if (! $page) {
            $this->command?->warn('Halaman tentang-kami tidak ditemukan; lewati.');

            return;
        }

        $authorId = $page->author_id ?? User::query()->orderBy('id')->value('id');

        $page->update([
            'body' => TentangKamiPageContent::body(),
            'author_id' => $authorId,
        ]);

        $this->command?->info('Halaman tentang-kami diperbarui.');
    }
}
