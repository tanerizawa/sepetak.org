<?php

namespace Database\Seeders;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Pool "tips harian anggota": 5 slot WIB (kisaran waktu shalat), profil ringkas, auto-publish.
 * Aktifkan pool di Filament dan set ARTICLE_GENERATOR_ENABLED=true + kunci OpenRouter.
 */
class DailyMemberTipsArticleSeeder extends Seeder
{
    public function run(): void
    {
        $cat = Category::firstOrCreate(
            ['slug' => 'panduan-tips-anggota'],
            ['name' => 'Panduan & Tips Anggota']
        );

        $tagTips = Tag::firstOrCreate(
            ['slug' => 'tips-anggota'],
            ['name' => 'Tips Anggota']
        );
        $tagOrg = Tag::firstOrCreate(
            ['slug' => 'organisasi-desa'],
            ['name' => 'Organisasi Desa']
        );

        $topics = [
            [
                'title' => 'Cara menyusun notulen rapat Pokja agar mudah dilacak',
                'description' => 'Langkah ringkas dokumentasi rapat tingkat kelompok kerja: agenda, keputusan, tindak lanjut.',
                'thinking_framework' => 'agrarian_political_economy',
                'article_type' => 'member_guide',
                'weight' => 55,
                'key_references' => [
                    ['author' => 'SEPETAK', 'year' => 2020, 'title' => 'Anggaran Rumah Tangga (cuplikan tata rapat)'],
                    ['author' => 'Kementerian Hukum RI', 'year' => 2011, 'title' => 'UU No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik'],
                ],
            ],
            [
                'title' => 'Memahami surat undangan resmi ke instansi desa/kecamatan',
                'description' => 'Format singkat, lampiran yang biasanya diperlukan, dan salinan arsip untuk organisasi.',
                'thinking_framework' => 'human_rights',
                'article_type' => 'member_guide',
                'weight' => 52,
                'key_references' => [
                    ['author' => 'Permendagri', 'year' => 2018, 'title' => 'Pedoman umum administrasi desa (referensi format surat)'],
                    ['author' => 'KPA', 'year' => 2019, 'title' => 'Panduan advokasi agraria tingkat komunitas'],
                ],
            ],
            [
                'title' => 'Tips mengumpulkan bukti foto/video di lokasi sengketa tanah',
                'description' => 'Etika dokumentasi, metadata waktu, dan privasi warga — versi ringkas untuk anggota lapangan.',
                'thinking_framework' => 'human_rights',
                'article_type' => 'member_guide',
                'weight' => 58,
                'key_references' => [
                    ['author' => 'Komnas HAM', 'year' => 2021, 'title' => 'Panduan dokumentasi pelanggaran HAM (adaptasi praktis)'],
                    ['author' => 'Walhi', 'year' => 2020, 'title' => 'Panduan kampanye lingkungan berbasis bukti'],
                ],
            ],
            [
                'title' => 'Menyusun daftar hadir dan mandat musyawarah desa',
                'description' => 'Contoh kolom daftar hadir, mandat delegasi, dan arsip minimum untuk DPTD.',
                'thinking_framework' => 'agrarian_political_economy',
                'article_type' => 'member_guide',
                'weight' => 50,
                'key_references' => [
                    ['author' => 'Biro Hukum Kemendagri', 'year' => 2015, 'title' => 'UU Desa No. 6 Tahun 2014 (bagian partisipasi)'],
                    ['author' => 'SEPETAK', 'year' => 2020, 'title' => 'Anggaran Dasar & ART (cuplikan organ desa)'],
                ],
            ],
            [
                'title' => 'Ringkasan istilah hukum agraria yang sering muncul di surat desa',
                'description' => 'HGU, girik, SPPT, AJB — penjelasan satu paragraf per istilah untuk non-yuridis.',
                'thinking_framework' => 'human_rights',
                'article_type' => 'member_guide',
                'weight' => 60,
                'key_references' => [
                    ['author' => 'BPN', 'year' => 2016, 'title' => 'Materi sosialisasi sertifikat tanah (ringkasan publik)'],
                    ['author' => 'Konsorsium Pembaruan Agraria', 'year' => 2022, 'title' => 'Kamus mini reforma agraria'],
                ],
            ],
            [
                'title' => 'Checklist keselamatan saat aksi damai atau audiensi',
                'description' => 'Koordinasi lini depan, komunikasi dengan aparat, dan titik kumpul darurat.',
                'thinking_framework' => 'human_rights',
                'article_type' => 'member_guide',
                'weight' => 54,
                'key_references' => [
                    ['author' => 'Kontras', 'year' => 2018, 'title' => 'Panduan aksi damai hak asasi'],
                    ['author' => 'ILO', 'year' => 2013, 'title' => 'Pedoman keselamatan pekerja pertanian (adaptasi)'],
                ],
            ],
            [
                'title' => 'Menyimpan dan memindahkan arsip organisasi ke cloud secara aman',
                'description' => 'Folder, izin akses, sandi, dan cadangan bulanan untuk sekretariat basis.',
                'thinking_framework' => 'critical_theory',
                'article_type' => 'member_guide',
                'weight' => 48,
                'key_references' => [
                    ['author' => 'Kominfo', 'year' => 2022, 'title' => 'Panduan keamanan siber UMKM/organisasi'],
                    ['author' => 'SANS Institute', 'year' => 2020, 'title' => 'Security awareness essentials'],
                ],
            ],
            [
                'title' => 'Menyusun laporan singkat ke DPTK: format satu halaman',
                'description' => 'Konteks, capaian, hambatan, kebutuhan — agar rapat kabupaten efisien.',
                'thinking_framework' => 'agrarian_political_economy',
                'article_type' => 'member_guide',
                'weight' => 56,
                'key_references' => [
                    ['author' => 'SEPETAK', 'year' => 2020, 'title' => 'Struktur ART (tugas DPTD–DPTK)'],
                ],
            ],
            [
                'title' => 'Tips komunikasi dengan wartawan: persiapan 5 menit sebelum wawancara',
                'description' => 'Pesan inti, batasan data sensitif, dan persetujuan warga.',
                'thinking_framework' => 'human_rights',
                'article_type' => 'member_guide',
                'weight' => 49,
                'key_references' => [
                    ['author' => 'AJI Indonesia', 'year' => 2019, 'title' => 'Etika jurnalistik dan perlindungan sumber'],
                ],
            ],
            [
                'title' => 'Membaca jadwal irigasi desa dan dampaknya ke jadwal tanam',
                'description' => 'Koordinasi Pokja dengan kelompok tani/P3A untuk mitigasi kekeringan.',
                'thinking_framework' => 'agrarian_political_economy',
                'article_type' => 'member_guide',
                'weight' => 51,
                'key_references' => [
                    ['author' => 'BPS', 'year' => 2023, 'title' => 'Statistik pertanian (referensi musim tanam)'],
                    ['author' => 'FAO', 'year' => 2021, 'title' => 'Climate-smart agriculture briefs'],
                ],
            ],
        ];

        $topicModels = [];
        foreach ($topics as $row) {
            $topicModels[] = ArticleTopic::updateOrCreate(
                ['slug' => Str::slug($row['title'])],
                array_merge($row, [
                    'prompt_template' => '',
                    'category_id' => $cat->id,
                    'is_active' => true,
                    'max_uses' => null,
                ])
            );
        }

        foreach ($topicModels as $t) {
            $t->tags()->syncWithoutDetaching([$tagTips->id, $tagOrg->id]);
        }

        $slots = config('article-generator.default_member_practical_schedule_times', [
            '04:45', '12:10', '15:20', '18:05', '19:25',
        ]);

        $pool = ArticlePool::updateOrCreate(
            ['slug' => 'tips-harian-anggota-wib'],
            [
                'name' => 'Tips harian anggota (5× WIB)',
                'description' => 'Artikel ringan praktis; jadwal kisaran waktu shalat (perkiraan, bukan hisab); langsung terbit bila auto-publish aktif.',
                'schedule_frequency' => 'daily',
                'schedule_day' => null,
                'schedule_time' => '04:45',
                'schedule_times' => $slots,
                'articles_per_run' => 1,
                'is_active' => false,
                'auto_publish' => true,
                'content_profile' => 'member_practical',
            ]
        );

        $pool->topics()->sync(collect($topicModels)->pluck('id')->all());

        $this->command?->info('DailyMemberTipsArticleSeeder: pool tips-harian-anggota-wib + '.count($topicModels).' topik.');
    }
}
