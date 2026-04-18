# Perencanaan: autopost harian ringkas untuk anggota (5× WIB)

## Tujuan

- **Lima artikel ringan per hari** untuk anggota (panduan berorganisasi, tips lapangan, informasi hukum praktis, tata tertib ringkas).
- **Jadwal mengikuti ritme harian** perkiraan waktu shalat di Indonesia (bukan hisab astronomis): slot operasional default `04:45`, `12:10`, `15:20`, `18:05`, `19:25` WIB — dapat diubah per pool di Filament (`Slot jam harian`).
- **Variasi konten**: topik yang sudah menghasilkan artikel sukses **hari yang sama** pada pool yang sama tidak dipilih lagi; prompt AI memuat **daftar judul terbitan otomatis 14 hari terakhir** agar angle tidak berulang.
- **Tanpa review manual**: aktifkan **Auto Publish** pada pool `tips-harian-anggota-wib` (atau pool kustom) setelah redaksi siap menerima risiko kontrol pasca-publikasi.

## Komponen teknis

| Bagian | Perilaku |
|--------|------------|
| `config/article-generator.php` | `schedule_timezone` (default `Asia/Jakarta`), `content_profiles.member_practical`, `default_member_practical_schedule_times`. |
| `ArticlePool` | Kolom `schedule_times` (JSON array HH:MM) + `content_profile` (`pillar` \| `member_practical`). `isDueAt` cocokkan menit lokal. |
| `routes/console.php` | `articles:generate` tiap **menit** (ringan jika tidak ada pool jatuh tempo). |
| `TopicPicker` | Untuk `member_practical`, topik yang sudah sukses hari itu pada pool yang sama dikeluarkan dari undian. |
| `ContentProfile` + `PromptComposer` | Profil memilih strategi prompt/validator. **Pool `member_practical` menang mutlak.** Jika pool yang di-dispatch **pillar** tetapi topik adalah **`member_guide`** atau kategori slug `panduan-tips-anggota`, jalur tetap **materi praktis** (regresi: topik tips terpasang ke pool pillar / Filament memilih pool aktif pertama). Setelah deploy, jalankan `php artisan queue:restart` agar worker antrian memuat kode baru. |
| `ArticleQualityValidator` | Mendelegasikan ke `AcademicQualityRuleSet` atau `MemberPracticalQualityRuleSet` sesuai profil; ambang kata & sitasi terpisah. |
| `ArticleGeneratorService` | `auto_publish` dari pool; disclosure teks disesuaikan untuk materi praktis. |
| Filament **Artikel** (`PostResource`) | Filter **Profil pool (dari topik)**, **Pool jadwal (generasi)** (nama pool yang menulis log), **Kategori** (mis. *Panduan & Tips Anggota* / slug `panduan-tips-anggota` untuk artikel ringan), plus kolom toggle **Profil pool (topik)** & **Pool jadwal (generasi)**. |
| Seeder | `DailyMemberTipsArticleSeeder` — kategori `panduan-tips-anggota`, 10 topik awal, pool **nonaktif** sampai diaktifkan. |

## Checklist redaksi (urut, ringkas)

1. **Lingkungan:** `.env` — `ARTICLE_GENERATOR_ENABLED=true`, `OPENROUTER_API_KEY`, `ARTICLE_AUTHOR_USER_ID`, antrian (`QUEUE_CONNECTION` bukan `sync` di produksi).
2. **Basis data:** `php artisan migrate` → seed (`DailyMemberTipsArticleSeeder` bila perlu).
3. **Pool tips:** Filament → **Artikel Otomatis → Pool Jadwal** — profil **Ringkas praktis**, frekuensi **Harian** jika pakai **beberapa slot** `HH:MM` (validasi form; lihat deskripsi bagian Jadwal). Pasang **Topik terhubung** yang bertipe panduan anggota.
4. **Topik:** **Artikel Otomatis → Topik Artikel** — tipe **Panduan ringkas anggota**, kategori panduan bila dipakai, tab **Publikasi → Pool jadwal** hubungkan ke pool ringkas (sama dengan pivot dari halaman Pool).
5. **Deploy kode prompt:** `php artisan queue:restart` agar worker memuat versi terbaru.
6. **Scheduler:** `* * * * * php artisan schedule:run` setiap menit di server.
7. **Review artikel:** **Konten → Artikel** — filter **Kategori**, **Pool jadwal (generasi)**, atau **Profil pool (dari topik)**; aktifkan kolom pool bila perlu.

### Pool lama — jangan hapus sembarangan

Nonaktifkan atau perbaiki profil/lepas topik. Menghapus pool dapat memutus `article_generation_logs`. Hindari banyak pool **aktif** overlap jam (pillar vs praktis).

## Risiko & mitigasi

- **Kualitas / kesalahan faktual**: turunkan risiko dengan menambah topik berkualitas, `prompt_template` per topik, atau menonaktifkan auto-publish sementara.
- **Biaya API**: batas `ARTICLE_MAX_PER_DAY` tetap berlaku global; lima slot = lima generasi maksimum per hari untuk pool ini bila tidak ada pool lain.
- **Topik habis**: jika semua topik sudah terpakai hari itu, perintah akan berhenti dengan peringatan “No available topics”; tambah topik atau longgarkan cooldown global (`ARTICLE_TOPIC_COOLDOWN_HOURS`).

## Matriks pengujian otomatis (PHPUnit)

| Proses | Berkas tes | Cakupan |
|--------|------------|---------|
| Slot jam multi (WIB) | `tests/Unit/ArticlePoolScheduleTest.php` | `isDueAt`, `getNextRunAt` |
| Prompt pillar vs praktis | `tests/Unit/ArticleGenerator/PromptBuilderTest.php`, `PromptComposerTest.php` | System/user prompt terpisah per jalur |
| Filter admin daftar artikel | `tests/Feature/Filament/PostResourceLivewireTest.php` | Filter profil pool / daftar artikel |
| Resolusi profil (pool + topik) | `tests/Unit/ArticleGenerator/ContentProfileResolutionTest.php` | Pool pillar + `member_guide`, pool null, kategori tips |
| Validator profil | `tests/Unit/ArticleGenerator/ArticleQualityValidatorTest.php` | Ambang kata/sitasi `member_practical` vs `pillar` |
| Parser abstrak | `tests/Unit/ArticleGenerator/ResponseParserHeadingsTest.php` | Heading `Ringkasan praktis` |
| Pemilihan topik harian | `tests/Unit/ArticleGenerator/TopicPickerMemberPoolTest.php` | Topik sudah `completed` hari ini di pool yang sama dilewati |
| Perintah artisan + pipeline | `tests/Feature/ArticleGenerator/ArticlesGenerateCommandTest.php` | `--sync`, post `published`, batas `max_per_day` |
| Pendaftaran anggota (RateLimiter) | `tests/Feature/MemberRegistrationTest.php` (implisit) | `RateLimiter::attempt` dengan callback |
| Ekspor PDF admin | `tests/Feature/AdminExportTest.php` | Izin `manage-*` selaras route `can:` |

Jalankan: `./vendor/bin/phpunit tests/Unit/ArticlePoolScheduleTest.php tests/Unit/ArticleGenerator tests/Feature/ArticleGenerator tests/Feature/Filament/PostResourceLivewireTest.php`
