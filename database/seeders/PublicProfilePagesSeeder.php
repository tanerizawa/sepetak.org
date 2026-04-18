<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Menyinkronkan isi halaman profil publik dari kelas *PageContent (AD/ART, tentang, visi, dll.).
 * Jalankan: php artisan db:seed --class=PublicProfilePagesSeeder
 */
class PublicProfilePagesSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::query()->where('is_active', true)->orderBy('id')->value('id');

        if (! $authorId) {
            $this->command?->error('Tidak ada pengguna aktif; lewati penyegaran halaman.');

            return;
        }

        $this->normalizeAdArtSlug();

        $map = [
            'tentang-kami' => [
                'title' => 'Tentang Kami',
                'body' => TentangKamiPageContent::body(),
            ],
            'visi-misi' => [
                'title' => 'Visi dan Misi',
                'body' => VisiMisiPageContent::body(),
            ],
            'sejarah' => [
                'title' => 'Sejarah SEPETAK',
                'body' => SejarahPageContent::body(),
            ],
            'struktur-organisasi' => [
                'title' => 'Struktur Organisasi',
                'body' => StrukturOrganisasiPageContent::body(),
            ],
            'wilayah-kerja' => [
                'title' => 'Wilayah Kerja & Pemetaan Konflik',
                'body' => WilayahKerjaPageContent::body(),
            ],
            'anggaran-dasar-dan-rumah-tangga' => [
                'title' => 'Anggaran Dasar dan Anggaran Rumah Tangga',
                'body' => AdArtPageContent::body(),
            ],
        ];

        foreach ($map as $slug => $attrs) {
            $existing = Page::query()->where('slug', $slug)->first();
            Page::updateOrCreate(
                ['slug' => $slug],
                array_merge($attrs, [
                    'status' => 'published',
                    'published_at' => $existing?->published_at ?? now(),
                    'author_id' => $existing?->author_id ?? $authorId,
                ])
            );
        }

        $this->command?->info('Halaman profil publik disegarkan ('.count($map).' slug).');
    }

    /**
     * Slug kanonik situs vs. hasil Str::slug judul penuh di Filament (dua kata "anggaran").
     */
    private function normalizeAdArtSlug(): void
    {
        $canonical = 'anggaran-dasar-dan-rumah-tangga';
        $legacy = 'anggaran-dasar-dan-anggaran-rumah-tangga';

        $legacyPage = Page::query()->where('slug', $legacy)->first();
        if (! $legacyPage) {
            return;
        }

        $canonicalPage = Page::query()->where('slug', $canonical)->first();
        if (! $canonicalPage) {
            $legacyPage->update(['slug' => $canonical]);

            return;
        }

        if ($legacyPage->id !== $canonicalPage->id) {
            $legacyPage->forceDelete();
        }
    }
}
