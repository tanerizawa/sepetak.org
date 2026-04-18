# Rencana Fitur: Sistem Artikel Otomatis & Terjadwal

> **Status**: DRAFT — Menunggu Review
> **Tanggal**: 17 April 2026
> **Versi**: 1.0
> **Penulis**: Engineering (Copilot-Assisted)

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Analisis Sistem Saat Ini](#2-analisis-sistem-saat-ini)
3. [Deskripsi Fitur](#3-deskripsi-fitur)
4. [Arsitektur Teknis](#4-arsitektur-teknis)
5. [Skema Database (Tambahan)](#5-skema-database-tambahan)
6. [Pool Kategori & Topik Artikel](#6-pool-kategori--topik-artikel)
7. [Mekanisme Generasi Artikel](#7-mekanisme-generasi-artikel)
8. [Sistem Penjadwalan](#8-sistem-penjadwalan)
9. [Manajemen via Admin Panel (Filament)](#9-manajemen-via-admin-panel-filament)
10. [Standar Kualitas Konten](#10-standar-kualitas-konten)
11. [Alur Kerja (Workflow)](#11-alur-kerja-workflow)
12. [Keamanan & Etika](#12-keamanan--etika)
13. [Rencana Implementasi Bertahap](#13-rencana-implementasi-bertahap)
14. [Migrasi & Kompatibilitas](#14-migrasi--kompatibilitas)
15. [Testing Strategy](#15-testing-strategy)
16. [Risiko & Mitigasi](#16-risiko--mitigasi)
17. [Estimasi Struktur File](#17-estimasi-struktur-file)
18. [Keputusan yang Perlu Diambil](#18-keputusan-yang-perlu-diambil)

---

## 1. Ringkasan Eksekutif

Fitur ini menambahkan kemampuan **generasi artikel otomatis** yang bersifat **timeless** (bukan berita aktual) ke sistem SEPETAK. Artikel dihasilkan secara programatis berdasarkan **pool kategori/topik** yang dikurasi, dengan penjadwalan publikasi otomatis. Konten berfokus pada essay, opini, kajian ilmiah, dan analisis kritis yang berakar pada tradisi pemikiran **Marxian**, **Neo-Marxian**, dan pemikiran **modern/post-modern** yang relevan dengan isu agraria, lingkungan, HAM, dan perjuangan petani.

### Mengapa Fitur Ini Diperlukan

- **Konsistensi publikasi**: Organisasi dengan tim kecil kesulitan memproduksi konten akademik secara rutin
- **SEO & Authority**: Konten timeless berkualitas meningkatkan domain authority dan organic traffic jangka panjang
- **Edukasi publik**: Menyediakan basis pengetahuan yang kontekstual bagi anggota dan simpatisan
- **Arsip intelektual**: Membangun repositori pemikiran kritis yang relevan dengan misi SEPETAK

---

## 2. Analisis Sistem Saat Ini

### 2.1 Infrastruktur yang Sudah Tersedia

| Komponen                  | Status   | Detail                                                                                                                             |
| ------------------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| Model `Post`              | ✅ Aktif | Fields: title, slug, excerpt, body, status (draft/published/archived), published_at, author_id. Soft delete. Spatie Media (cover). |
| Model `Category`          | ✅ Aktif | Many-to-many via `post_category` pivot. Fields: name, slug.                                                                        |
| Model `Tag`               | ✅ Aktif | Many-to-many via `post_tag` pivot. Fields: name, slug.                                                                             |
| `PostResource` (Filament) | ✅ Aktif | CRUD lengkap, RichEditor body, status filter, bulk publish/archive.                                                                |
| `PostController` (Publik) | ✅ Aktif | `index()` dengan pagination, `show()` dengan scope `published`.                                                                    |
| Route publik              | ✅ Aktif | `/berita` (index), `/berita/{slug}` (detail).                                                                                      |
| Queue system              | ✅ Aktif | Database driver default, Redis siap produksi. Laravel Horizon terpasang.                                                           |
| Cron scheduler            | ✅ Aktif | `ops/cron/sepetak` terkonfigurasi. `routes/console.php` tersedia.                                                                  |
| Supervisor                | ✅ Aktif | `ops/supervisor/sepetak-queue.conf` untuk queue worker.                                                                            |
| JSON-LD                   | ✅ Aktif | NewsArticle + BreadcrumbList structured data di halaman post.                                                                      |
| RSS Feed                  | ✅ Aktif | `/feed.xml` otomatis memasukkan post published.                                                                                    |
| Sitemap                   | ✅ Aktif | `/sitemap.xml` dinamis.                                                                                                            |

### 2.2 Gap yang Perlu Diisi

| Gap                                            | Solusi                                                      |
| ---------------------------------------------- | ----------------------------------------------------------- |
| Tidak ada mekanisme auto-generate konten       | Tambah `ArticleGenerator` service + AI provider integration |
| Post model belum membedakan manual vs otomatis | Tambah kolom `source_type` dan metadata terkait             |
| Tidak ada pool topik/template yang dikurasi    | Tambah tabel `article_topics` + `article_topic_pools`       |
| Tidak ada penjadwalan generasi berkala         | Tambah scheduled command + konfigurasi interval             |
| Kategori belum dipetakan ke domain pemikiran   | Seed kategori baru yang relevan + mapping pool              |
| Tidak ada review queue untuk artikel otomatis  | Tambah status `pending_review` atau workflow approval       |
| Tidak ada audit trail khusus konten otomatis   | Tambah `article_generation_logs`                            |

### 2.3 Dependensi Eksisting yang Dimanfaatkan

- **Model `Post`**: Artikel otomatis tetap disimpan sebagai `Post` — tidak perlu model baru. Ini menjaga kompatibilitas penuh dengan frontend publik, RSS, sitemap, dan JSON-LD yang sudah berjalan.
- **Queue + Horizon**: Generasi artikel (yang bisa memakan waktu) dijalankan via queue job.
- **Spatie Media Library**: Cover image otomatis (jika diimplementasikan) menggunakan media collection `cover` yang sudah ada.
- **Role & Permission**: Hanya `superadmin` dan `admin` yang dapat mengelola konfigurasi auto-article.

---

## 3. Deskripsi Fitur

### 3.1 Tipe Artikel yang Dihasilkan

Seluruh artikel bersifat **timeless** (evergreen) — bukan berita kejadian aktual. Tipe yang didukung:

| Tipe                   | Deskripsi                                        | Contoh Judul                                                         |
| ---------------------- | ------------------------------------------------ | -------------------------------------------------------------------- |
| **Essay**              | Tulisan argumentatif mendalam dengan tesis jelas | "Alienasi Petani di Era Revolusi Hijau: Pembacaan Marxian"           |
| **Opini**              | Pandangan kritis terhadap isu tertentu           | "Mengapa Reforma Agraria Gagal: Kritik Struktural"                   |
| **Kajian Ilmiah**      | Tinjauan literatur atau analisis berbasis data   | "Konflik Agraria dan Akumulasi Primitif: Studi Kasus Jawa Barat"     |
| **Analisis Kebijakan** | Dekonstruksi kebijakan publik                    | "UU Cipta Kerja dan Dispossesi Petani Kecil"                         |
| **Profil Pemikiran**   | Eksplorasi gagasan tokoh/aliran                  | "Antonio Gramsci dan Hegemoni Budaya dalam Konteks Petani Indonesia" |
| **Tinjauan Historis**  | Narasi sejarah analitis                          | "Gerakan Petani Indonesia dari Sarekat Islam hingga Era Reformasi"   |

### 3.2 Kerangka Intelektual

Artikel dibangun dengan landasan pemikiran berlapis:

**Fondasi Klasik (Marxisme-Engelisme)**

- Materialisme historis dan dialektis
- Teori nilai-lebih dan eksploitasi
- Akumulasi primitif (primitive accumulation)
- Pertanyaan agraria (The Agrarian Question — Kautsky)
- Kondisi kelas pekerja (Engels)

**Neo-Marxian & Mazhab Kritis**

- Antonio Gramsci: hegemoni kultural, intelektual organik
- Mazhab Frankfurt: industri budaya, rasionalitas instrumental (Adorno, Horkheimer, Marcuse)
- Louis Althusser: aparatus ideologis negara
- Nicos Poulantzas: teori negara dan kelas
- David Harvey: akumulasi melalui perampasan (accumulation by dispossession)
- Immanuel Wallerstein: sistem-dunia (world-systems theory)

**Pemikiran Modern & Post-Modern yang Relevan**

- Michel Foucault: relasi kuasa-pengetahuan, governmentality
- Pierre Bourdieu: kapital simbolik, kekerasan simbolik, habitus
- Amartya Sen: kapabilitas dan pembangunan sebagai kebebasan
- Arturo Escobar: post-development, kritik terhadap pembangunanisme
- James C. Scott: senjata kaum lemah (weapons of the weak), seeing like a state
- Gayatri Spivak: subaltern studies
- Partha Chatterjee: politik masyarakat sipil vs masyarakat politik

**Pemikir Agraria & Lingkungan**

- Henry Bernstein: dinamika agraria kontemporer
- Jan Douwe van der Ploeg: peasant mode of production, re-peasantization
- Eric Wolf: peasant wars, antropologi politik
- Vandana Shiva: ekofeminisme, biopiracy
- Joan Martinez-Alier: ekologi politik, environmentalism of the poor

**Konteks Indonesia**

- Soekarno: Marhaenisme dan Pancasila sebagai dialektika
- Tan Malaka: materialisme-dialektika-logika
- Semaoen & gerakan Sarekat Islam/PKI awal
- Gunawan Wiradi: struktur agraria Jawa
- Noer Fauzi Rachman: reforma agraria kontemporer
- Konsorsium Pembaruan Agraria (KPA): data dan analisis konflik

### 3.3 Standar Konten

Setiap artikel harus memenuhi:

1. **Bahasa**: Formal/akademik, Bahasa Indonesia baku (KBBI/EYD V)
2. **Panjang**: Minimal 1.500 kata, optimal 2.000–3.500 kata
3. **Struktur**:
   - Judul akademik (informatif, tidak clickbait)
   - Abstrak/ringkasan (150–250 kata)
   - Pendahuluan (konteks, permasalahan, tesis)
   - Pembahasan (minimal 3 sub-bagian berargumen)
   - Penutup/Kesimpulan
   - Daftar Pustaka / Referensi
4. **Sitasi**: Minimal 5 referensi per artikel, format APA 7th Edition
   - Referensi harus **nyata dan dapat diverifikasi** (buku, jurnal, laporan resmi)
   - Dilarang mengarang referensi fiktif
5. **Nada**: Kritis tetapi konstruktif, berpihak pada keadilan agraria
6. **Disclosure**: Setiap artikel otomatis wajib mencantumkan label bahwa konten dibantu AI

---

## 4. Arsitektur Teknis

### 4.1 Diagram Komponen

```
┌──────────────────────────────────────────────────────────┐
│                    FILAMENT ADMIN PANEL                    │
│  ┌──────────────┐  ┌──────────────┐  ┌────────────────┐  │
│  │ ArticleTopic │  │ ArticlePool  │  │ AutoArticle    │  │
│  │ Resource     │  │ Resource     │  │ Config Resource │  │
│  └──────┬───────┘  └──────┬───────┘  └───────┬────────┘  │
│         │                 │                   │           │
└─────────┼─────────────────┼───────────────────┼───────────┘
          │                 │                   │
          ▼                 ▼                   ▼
┌──────────────────────────────────────────────────────────┐
│                    SERVICE LAYER                          │
│  ┌──────────────────────────────────────────────────┐    │
│  │            ArticleGeneratorService               │    │
│  │  ┌─────────────┐  ┌───────────┐  ┌───────────┐  │    │
│  │  │ TopicPicker  │  │ Composer  │  │ Publisher │  │    │
│  │  │ (weighted    │  │ (AI +     │  │ (schedule │  │    │
│  │  │  random)     │  │  template)│  │  + queue) │  │    │
│  │  └─────────────┘  └───────────┘  └───────────┘  │    │
│  └──────────────────────────────────────────────────┘    │
│                          │                               │
│  ┌──────────────────────────────────────────────────┐    │
│  │         AI Provider Interface (Contract)          │    │
│  │  ┌──────────┐  ┌──────────┐  ┌────────────────┐  │    │
│  │  │ OpenAI   │  │ Anthropic│  │ Local/Ollama   │  │    │
│  │  │ Provider │  │ Provider │  │ Provider       │  │    │
│  │  └──────────┘  └──────────┘  └────────────────┘  │    │
│  └──────────────────────────────────────────────────┘    │
└──────────────────────────────────────────────────────────┘
          │                                    │
          ▼                                    ▼
┌─────────────────────┐          ┌──────────────────────┐
│  DATABASE            │          │  QUEUE (Redis/DB)    │
│  - article_topics    │          │  - GenerateArticle   │
│  - article_pools     │          │  - PublishScheduled   │
│  - article_gen_logs  │          │    Article            │
│  - posts (existing)  │          └──────────────────────┘
│  - categories (ext.) │                    │
│  - tags (existing)   │                    ▼
└─────────────────────┘          ┌──────────────────────┐
                                 │  SCHEDULER (cron)    │
                                 │  - Daily/Weekly      │
                                 │    trigger           │
                                 └──────────────────────┘
```

### 4.2 Prinsip Desain

1. **Separation of Concerns**: AI provider di-abstract via interface — mudah ganti vendor
2. **Queue-First**: Generasi artikel selalu via queue job (bisa 30–120 detik per artikel)
3. **Human-in-the-Loop**: Artikel auto-generated default masuk `draft` atau `pending_review`, bukan langsung `published`
4. **Graceful Degradation**: Jika AI provider down, sistem log error dan retry — tidak crash
5. **Idempotent**: Satu scheduled run tidak menghasilkan duplikat jika dijalankan ulang
6. **Backward Compatible**: Semua artikel tetap masuk tabel `posts` — frontend publik tidak perlu diubah

---

## 5. Skema Database (Tambahan)

### 5.1 Tabel Baru: `article_topics`

Pool topik yang dikurasi untuk generasi artikel.

| Column             | Type         | Notes                                                                                                                                        |
| ------------------ | ------------ | -------------------------------------------------------------------------------------------------------------------------------------------- |
| id                 | bigint       | Primary key                                                                                                                                  |
| title              | varchar(200) | Judul topik (mis. "Alienasi Buruh Tani")                                                                                                     |
| slug               | varchar(200) | Unique                                                                                                                                       |
| description        | text         | Deskripsi konteks dan sudut pandang                                                                                                          |
| thinking_framework | varchar(100) | Kerangka pikir utama: `marxist`, `neo_marxian`, `postmodern`, `critical_theory`, `agrarian_political_economy`, `ecopolitics`, `human_rights` |
| article_type       | varchar(50)  | Tipe: `essay`, `opinion`, `scientific_review`, `policy_analysis`, `thinker_profile`, `historical_review`                                     |
| key_references     | json         | Array referensi wajib yang harus disitasi (ISBN, DOI, atau judul buku)                                                                       |
| prompt_template    | longtext     | Template prompt AI yang sudah dikurasi untuk topik ini                                                                                       |
| weight             | integer      | Bobot seleksi (1–100, default 50). Semakin tinggi, semakin sering terpilih                                                                   |
| max_uses           | integer      | Nullable. Maks berapa kali topik ini boleh dipakai. NULL = unlimited                                                                         |
| times_used         | integer      | Default 0. Counter berapa kali sudah dipakai                                                                                                 |
| is_active          | boolean      | Default true                                                                                                                                 |
| category_id        | bigint       | FK categories.id — kategori default saat publish                                                                                             |
| created_by         | bigint       | FK users.id                                                                                                                                  |
| created_at         | datetime     |                                                                                                                                              |
| updated_at         | datetime     |                                                                                                                                              |
| deleted_at         | datetime     | Nullable                                                                                                                                     |

### 5.2 Tabel Baru: `article_topic_tags`

Mapping tags default ke topik.

| Column           | Type   | Notes                |
| ---------------- | ------ | -------------------- |
| article_topic_id | bigint | FK article_topics.id |
| tag_id           | bigint | FK tags.id           |

### 5.3 Tabel Baru: `article_pools`

Pengelompokan topik ke dalam pool penjadwalan.

| Column             | Type         | Notes                                                                     |
| ------------------ | ------------ | ------------------------------------------------------------------------- |
| id                 | bigint       | Primary key                                                               |
| name               | varchar(100) | Nama pool (mis. "Pool Mingguan Marxian")                                  |
| slug               | varchar(120) | Unique                                                                    |
| description        | text         | Nullable                                                                  |
| schedule_frequency | varchar(30)  | `daily`, `weekly`, `biweekly`, `monthly`                                  |
| schedule_day       | varchar(10)  | Nullable. Hari (untuk weekly): `monday`–`sunday`                          |
| schedule_time      | time         | Waktu publish (WIB)                                                       |
| articles_per_run   | integer      | Default 1. Berapa artikel per jadwal                                      |
| is_active          | boolean      | Default true                                                              |
| auto_publish       | boolean      | Default false. Jika true, langsung `published`; jika false, masuk `draft` |
| created_at         | datetime     |                                                                           |
| updated_at         | datetime     |                                                                           |

### 5.4 Tabel Baru: `article_pool_topic` (Pivot)

| Column           | Type   | Notes                |
| ---------------- | ------ | -------------------- |
| article_pool_id  | bigint | FK article_pools.id  |
| article_topic_id | bigint | FK article_topics.id |

### 5.5 Tabel Baru: `article_generation_logs`

Audit trail setiap proses generasi.

| Column             | Type        | Notes                                                     |
| ------------------ | ----------- | --------------------------------------------------------- |
| id                 | bigint      | Primary key                                               |
| article_topic_id   | bigint      | FK article_topics.id, nullable                            |
| article_pool_id    | bigint      | FK article_pools.id, nullable                             |
| post_id            | bigint      | FK posts.id, nullable (terisi setelah sukses)             |
| status             | varchar(30) | `queued`, `generating`, `completed`, `failed`, `rejected` |
| ai_provider        | varchar(50) | Provider yang digunakan (mis. `openai`, `anthropic`)      |
| ai_model           | varchar(80) | Model spesifik (mis. `gpt-4o`, `claude-sonnet-4`)         |
| prompt_used        | longtext    | Prompt lengkap yang dikirim                               |
| raw_response       | longtext    | Nullable. Response mentah dari AI                         |
| tokens_used        | integer     | Nullable. Total token consumed                            |
| generation_time_ms | integer     | Nullable. Waktu proses (ms)                               |
| error_message      | text        | Nullable. Pesan error jika gagal                          |
| triggered_by       | varchar(30) | `scheduler`, `manual`                                     |
| created_at         | datetime    |                                                           |
| updated_at         | datetime    |                                                           |

### 5.6 Perubahan pada Tabel Existing: `posts`

Tambah kolom untuk membedakan artikel manual vs otomatis:

| Column Baru       | Type        | Notes                                                   |
| ----------------- | ----------- | ------------------------------------------------------- |
| source_type       | varchar(20) | Default `manual`. Values: `manual`, `auto_generated`    |
| article_topic_id  | bigint      | Nullable. FK article_topics.id — topik sumber jika auto |
| generation_log_id | bigint      | Nullable. FK article_generation_logs.id                 |
| ai_disclosure     | boolean     | Default false. True jika konten dibantu AI              |

---

## 6. Pool Kategori & Topik Artikel

### 6.1 Kategori Baru (Seed)

Kategori berikut ditambahkan ke tabel `categories` yang sudah ada:

| Nama Kategori           | Slug                    | Deskripsi                                                               |
| ----------------------- | ----------------------- | ----------------------------------------------------------------------- |
| Kajian Marxian          | kajian-marxian          | Essay dan analisis berbasis pemikiran Marx, Engels, dan tradisi Marxian |
| Ekonomi Politik Agraria | ekonomi-politik-agraria | Analisis struktural isu agraria dan pertanahan                          |
| Ekologi & Lingkungan    | ekologi-lingkungan      | Perspektif kritis terhadap isu lingkungan dan keberlanjutan             |
| Hak Asasi Manusia       | hak-asasi-manusia       | Kajian HAM dalam konteks petani dan masyarakat rural                    |
| Pemikiran Kritis        | pemikiran-kritis        | Profil tokoh, aliran, dan gagasan teori kritis                          |
| Analisis Kebijakan      | analisis-kebijakan      | Dekonstruksi kebijakan publik yang berdampak pada petani                |
| Sejarah Gerakan         | sejarah-gerakan         | Tinjauan historis gerakan tani dan perlawanan rakyat                    |
| Opini & Refleksi        | opini-refleksi          | Tulisan opini dan refleksi mendalam tentang isu kontemporer             |

### 6.2 Tags Baru (Seed)

| Tag                   | Slug                  |
| --------------------- | --------------------- |
| Karl Marx             | karl-marx             |
| Friedrich Engels      | friedrich-engels      |
| Antonio Gramsci       | antonio-gramsci       |
| David Harvey          | david-harvey          |
| Reforma Agraria       | reforma-agraria       |
| Konflik Agraria       | konflik-agraria       |
| Materialisme Historis | materialisme-historis |
| Kapitalisme           | kapitalisme           |
| Neoliberalisme        | neoliberalisme        |
| Hegemoni              | hegemoni              |
| Petani                | petani                |
| Buruh Tani            | buruh-tani            |
| Ekologi Politik       | ekologi-politik       |
| Hak Atas Tanah        | hak-atas-tanah        |
| Post-Development      | post-development      |
| Subaltern             | subaltern             |
| Akumulasi Primitif    | akumulasi-primitif    |
| Kedaulatan Pangan     | kedaulatan-pangan     |

### 6.3 Contoh Pool & Topik

**Pool: "Kajian Mingguan Marxian"**

- Frekuensi: Weekly (Senin)
- Waktu: 07:00 WIB
- Auto-publish: false (review dulu)
- Topik-topik:
  1. "Teori Nilai-Lebih dan Eksploitasi Buruh Tani di Jawa Barat"
  2. "Alienasi Petani dalam Modernisasi Pertanian Indonesia"
  3. "Fetisisme Komoditas: Mengapa Petani Miskin di Tanah Subur"
  4. "Kontradiksi Kapital di Sektor Agraria Asia Tenggara"
  5. "Engels dan Kondisi Kelas Pekerja: Relevansi bagi Buruh Tani"

**Pool: "Analisis Kebijakan Bulanan"**

- Frekuensi: Monthly (Minggu ke-1)
- Waktu: 09:00 WIB
- Auto-publish: false
- Topik-topik:
  1. "UU Cipta Kerja dan Dampaknya terhadap Hak Petani Kecil"
  2. "Program Sertifikasi Tanah: Formalisasi vs Keadilan Distribusi"
  3. "Subsidi Pertanian di Indonesia: Siapa yang Diuntungkan?"
  4. "Bank Tanah: Solusi atau Instrumen Akumulasi Baru?"
  5. "Proyek Strategis Nasional dan Penggusuran: Perspektif Harvey"

**Pool: "Essay Ekologi & HAM Dua Mingguan"**

- Frekuensi: Biweekly (Rabu)
- Waktu: 08:00 WIB
- Auto-publish: false
- Topik-topik:
  1. "Environmentalism of the Poor: Gerakan Lingkungan Rakyat Kecil"
  2. "Ekofeminisme dan Perjuangan Perempuan Petani"
  3. "Climate Justice: Keadilan Iklim dalam Perspektif Selatan Global"
  4. "Hak Atas Pangan sebagai Hak Asasi: Tinjauan Amartya Sen"
  5. "Biopiracy dan Kedaulatan Benih Petani Nusantara"

---

## 7. Mekanisme Generasi Artikel

### 7.1 AI Provider Contract

```php
namespace App\Contracts;

interface ArticleAiProvider
{
    /**
     * Generate artikel berdasarkan prompt.
     *
     * @param string $systemPrompt  Instruksi sistem (persona, gaya, standar)
     * @param string $userPrompt    Prompt spesifik topik
     * @param array  $options       Model, temperature, max_tokens, dll.
     * @return ArticleAiResponse
     */
    public function generate(
        string $systemPrompt,
        string $userPrompt,
        array $options = []
    ): ArticleAiResponse;

    public function name(): string;
}
```

### 7.2 System Prompt (Persona)

```
Kamu adalah penulis akademik dan intelektual organik yang menulis untuk
Serikat Pekerja Tani Karawang (SEPETAK). Tugasmu menulis artikel ilmiah
populer dalam Bahasa Indonesia baku yang memenuhi standar berikut:

1. BAHASA: Formal, akademik, mengikuti EYD V dan KBBI. Hindari bahasa
   kasual, slang, atau clickbait.
2. STRUKTUR: Judul → Abstrak (150-250 kata) → Pendahuluan → Pembahasan
   (min. 3 subbagian) → Kesimpulan → Daftar Pustaka.
3. SITASI: Gunakan format APA 7th Edition. Minimal 5 referensi yang NYATA
   dan DAPAT DIVERIFIKASI. Jangan pernah mengarang referensi fiktif.
   Prioritaskan: buku klasik (Marx, Engels, Gramsci, Harvey, dll.),
   jurnal terindeks, laporan KPA/BPS/Walhi, dan dokumen resmi.
4. NADA: Kritis-konstruktif. Berpihak pada keadilan agraria dan hak petani.
   Hindari dogmatisme — sajikan argumen dialektis.
5. PANJANG: 2.000–3.500 kata (tidak termasuk daftar pustaka).
6. FORMAT: Gunakan Markdown. Heading dengan ##, sitasi inline dengan
   (Penulis, Tahun), daftar pustaka di akhir.
7. KONTEKS: Artikel ini dipublikasikan di website SEPETAK (sepetak.org),
   organisasi serikat pekerja tani di Karawang, Jawa Barat. Pembaca adalah
   anggota organisasi, akademisi, aktivis, dan publik yang tertarik isu
   agraria.
```

### 7.3 Proses Generasi (Step-by-step)

```
1. TopicPicker memilih topik dari pool aktif
   └─ Weighted random, memperhitungkan:
      - weight topik
      - times_used (hindari repetisi)
      - max_uses limit
      - cooldown (topik terakhir dipakai X hari lalu)

2. PromptBuilder menyusun prompt final
   └─ System prompt (persona tetap)
   └─ Topic-specific prompt dari article_topics.prompt_template
   └─ Key references injection
   └─ Variasi instruksi (agar tidak monoton)

3. AiProvider.generate() dipanggil
   └─ Timeout: 120 detik
   └─ Retry: 2x dengan exponential backoff

4. ResponseParser memproses hasil
   └─ Validasi struktur (harus ada abstrak, daftar pustaka, dll.)
   └─ Ekstrak judul, excerpt, body
   └─ Validasi panjang minimum
   └─ Deteksi referensi fiktif (heuristic check)

5. PostCreator menyimpan sebagai Post
   └─ title, slug (auto-generate), excerpt, body
   └─ status: draft (default) atau published (jika auto_publish)
   └─ source_type: auto_generated
   └─ author_id: user sistem khusus atau user yang dikonfigurasi
   └─ published_at: scheduled time
   └─ Attach categories & tags dari topic
   └─ ai_disclosure: true

6. Logger mencatat ke article_generation_logs
   └─ Semua metadata: prompt, response, tokens, waktu, status
```

### 7.4 Validasi Kualitas Otomatis

Sebelum disimpan, output AI melewati validasi:

| Validasi           | Kriteria                                                     | Aksi Gagal                                 |
| ------------------ | ------------------------------------------------------------ | ------------------------------------------ |
| Panjang minimum    | ≥ 1.500 kata                                                 | Reject, retry dengan prompt yang diperkuat |
| Struktur           | Harus mengandung `## ` heading, "Daftar Pustaka"/"Referensi" | Reject                                     |
| Referensi minimum  | ≥ 5 sitasi inline `(Author, Year)`                           | Reject                                     |
| Bahasa             | Tidak mengandung phrase disallowed (clickbait, informal)     | Log warning                                |
| Duplikasi judul    | Cek slug unik terhadap posts existing                        | Append suffix                              |
| Deteksi halusinasi | Cross-check referensi terhadap daftar known references       | Log warning, flag review                   |

---

## 8. Sistem Penjadwalan

### 8.1 Artisan Command

```php
// app/Console/Commands/GenerateScheduledArticles.php

class GenerateScheduledArticles extends Command
{
    protected $signature = 'articles:generate
        {--pool= : ID pool spesifik}
        {--force : Abaikan jadwal, generate sekarang}
        {--dry-run : Simulasi tanpa menyimpan}';

    protected $description = 'Generate artikel otomatis berdasarkan pool terjadwal';
}
```

### 8.2 Registrasi di Scheduler

```php
// routes/console.php atau app/Console/Kernel.php

Schedule::command('articles:generate')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground()
    ->onOneServer();
```

Scheduler berjalan tiap jam dan memeriksa pool mana yang jadwalnya sudah tiba. Ini memungkinkan fleksibilitas jadwal per-pool tanpa banyak entry cron.

### 8.3 Queue Job

```php
// app/Jobs/GenerateArticleJob.php

class GenerateArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 180;      // 3 menit
    public int $backoff = 60;       // 1 menit antar retry
    public string $queue = 'articles';  // Queue terpisah

    public function __construct(
        public ArticleTopic $topic,
        public ArticlePool $pool,
        public string $triggeredBy = 'scheduler',
    ) {}
}
```

### 8.4 Queue Terpisah

Disarankan queue `articles` terpisah dari queue `default` agar proses generasi yang berat tidak memblokir notifikasi email dan job ringan lainnya.

```ini
# ops/supervisor/sepetak-articles-queue.conf
[program:sepetak-articles]
command=php /home/sepetak.org/artisan queue:work redis --queue=articles --tries=2 --timeout=300
numprocs=1
```

---

## 9. Manajemen via Admin Panel (Filament)

### 9.1 Resource Baru

#### ArticleTopicResource

**Navigation**: Konten → Topik Artikel

| Tab Form            | Fields                                                                                                                                        |
| ------------------- | --------------------------------------------------------------------------------------------------------------------------------------------- |
| **Informasi Topik** | title, slug (auto), description (Textarea), thinking_framework (Select), article_type (Select)                                                |
| **Konfigurasi AI**  | prompt_template (Textarea/CodeEditor), key_references (JSON Repeater: title + author + year + type)                                           |
| **Publikasi**       | category_id (Select dari categories), tags (Select multiple), weight (NumberInput 1-100), max_uses (NumberInput nullable), is_active (Toggle) |

**Table Columns**: title, article_type (badge), thinking_framework (badge), times_used/max_uses, is_active, created_at

**Actions**: Edit, Delete, **"Generate Sekarang"** (trigger manual → dispatch job)

#### ArticlePoolResource

**Navigation**: Konten → Pool Jadwal Artikel

| Tab Form        | Fields                                                                                                                                     |
| --------------- | ------------------------------------------------------------------------------------------------------------------------------------------ |
| **Pool**        | name, slug (auto), description                                                                                                             |
| **Jadwal**      | schedule_frequency (Select), schedule_day (Select, visible if weekly/biweekly), schedule_time (TimePicker), articles_per_run (NumberInput) |
| **Konfigurasi** | is_active (Toggle), auto_publish (Toggle dengan warning merah jika true)                                                                   |
| **Topik**       | Repeater / CheckboxList dari article_topics aktif                                                                                          |

**Table Columns**: name, schedule_frequency (badge), next_run (computed), articles_per_run, topik terhubung (count), is_active

#### ArticleGenerationLogResource (Read-Only)

**Navigation**: Konten → Log Generasi Artikel

**Table Columns**: created_at, topic.title, pool.name, status (badge warna), ai_provider, ai_model, tokens_used, generation_time_ms, post link (jika ada)

**Filter**: status, ai_provider, date range

**Actions**: View (detail lengkap termasuk prompt & response), Delete log lama

### 9.2 Perubahan PostResource

- Tambah kolom tabel: `source_type` (badge: "Manual" gray, "AI" purple)
- Tambah filter: `source_type`
- Tambah info banner di form edit jika `source_type === 'auto_generated'`:
  > ℹ️ Artikel ini dihasilkan otomatis. Topik: {topic.title}. Silakan review dan edit sebelum publikasi.

### 9.3 Widget Dashboard Baru

**ArticleGenerationStatsWidget**:

- Total artikel auto-generated bulan ini
- Sukses vs gagal (bar chart)
- Token usage bulan ini
- Artikel pending review

---

## 10. Standar Kualitas Konten

### 10.1 Template Referensi Wajib

Setiap topik harus menyertakan `key_references` — daftar referensi nyata yang **wajib** disitasi dalam artikel. Contoh format JSON:

```json
[
  {
    "author": "Marx, K.",
    "year": 1867,
    "title": "Das Kapital: Kritik der Politischen Ökonomie, Band I",
    "type": "book"
  },
  {
    "author": "Harvey, D.",
    "year": 2003,
    "title": "The New Imperialism",
    "publisher": "Oxford University Press",
    "type": "book"
  },
  {
    "author": "Konsorsium Pembaruan Agraria",
    "year": 2023,
    "title": "Catatan Akhir Tahun 2023: Konflik Agraria di Tengah Satisfaksi Palsu Reforma Agraria",
    "type": "report"
  }
]
```

### 10.2 Daftar Referensi Terverifikasi (Seeded)

Sistem akan menyertakan database referensi terverifikasi yang dapat dipilih saat membuat topik:

**Buku Klasik**

- Marx, K. (1867). _Das Kapital, Vol. I_
- Marx, K. (1852). _The Eighteenth Brumaire of Louis Bonaparte_
- Engels, F. (1845). _The Condition of the Working Class in England_
- Gramsci, A. (1971). _Selections from the Prison Notebooks_
- Kautsky, K. (1899). _Die Agrarfrage (The Agrarian Question)_

**Buku Neo-Marxian & Kontemporer**

- Harvey, D. (2003). _The New Imperialism_
- Harvey, D. (2005). _A Brief History of Neoliberalism_
- Wallerstein, I. (2004). _World-Systems Analysis: An Introduction_
- Scott, J. C. (1985). _Weapons of the Weak: Everyday Forms of Peasant Resistance_
- Scott, J. C. (1998). _Seeing Like a State_
- Bourdieu, P. (1977). _Outline of a Theory of Practice_
- Foucault, M. (1975). _Discipline and Punish_
- Sen, A. (1999). _Development as Freedom_
- Escobar, A. (1995). _Encountering Development_
- Van der Ploeg, J. D. (2008). _The New Peasantries_
- Bernstein, H. (2010). _Class Dynamics of Agrarian Change_
- Wolf, E. (1969). _Peasant Wars of the Twentieth Century_
- Shiva, V. (1997). _Biopiracy: The Plunder of Nature and Knowledge_
- Martinez-Alier, J. (2002). _The Environmentalism of the Poor_
- Spivak, G. C. (1988). _Can the Subaltern Speak?_

**Konteks Indonesia**

- Wiradi, G. (2000). _Reforma Agraria: Perjalanan yang Belum Berakhir_
- Rachman, N. F. (2012). _Land Reform dari Masa ke Masa_
- White, B., & Wiradi, G. (2012). _Agrarian and Other Transformations of the Indonesian Countryside_
- Konsorsium Pembaruan Agraria. (2023). _Catatan Akhir Tahun KPA_
- Soekarno. (1926). _Nasionalisme, Islamisme, dan Marxisme_
- Malaka, T. (1943). _Madilog: Materialisme, Dialektika, dan Logika_

### 10.3 Disclaimer / AI Disclosure

Setiap artikel auto-generated yang dipublikasikan wajib menampilkan notice di akhir body:

```html
<aside class="ai-disclosure">
  <p>
    <em
      >Artikel ini disusun dengan bantuan kecerdasan buatan dan telah ditinjau
      oleh redaksi SEPETAK. Referensi yang dicantumkan adalah sumber nyata yang
      dapat diverifikasi. Pandangan dalam artikel ini tidak selalu mencerminkan
      posisi resmi organisasi.</em
    >
  </p>
</aside>
```

---

## 11. Alur Kerja (Workflow)

### 11.1 Alur Otomatis (Default)

```
Scheduler Hourly
    │
    ├─ Cek pool aktif yang jadwalnya jatuh pada jam ini
    │
    ├─ Untuk setiap pool yang match:
    │   ├─ TopicPicker pilih topik (weighted random)
    │   ├─ Dispatch GenerateArticleJob ke queue 'articles'
    │   └─ Log: status=queued
    │
    ├─ Queue Worker memproses job:
    │   ├─ Build prompt
    │   ├─ Call AI provider
    │   ├─ Validate response
    │   ├─ Create Post (status=draft, source_type=auto_generated)
    │   ├─ Attach categories + tags
    │   ├─ Log: status=completed
    │   └─ (Opsional) Notifikasi ke admin: "Artikel baru menunggu review"
    │
    └─ Admin di Filament:
        ├─ Lihat artikel baru di PostResource (filter: source_type=auto_generated, status=draft)
        ├─ Review, edit jika perlu
        └─ Publish (ubah status + set published_at)
```

### 11.2 Alur Manual (Trigger Admin)

```
Admin di ArticleTopicResource
    │
    ├─ Klik "Generate Sekarang" pada topik tertentu
    ├─ Modal konfirmasi muncul
    ├─ Dispatch GenerateArticleJob (triggered_by=manual)
    ├─ Toast notification: "Artikel sedang diproses..."
    │
    └─ Setelah selesai:
        ├─ Notifikasi Filament: "Artikel '{title}' berhasil dibuat"
        └─ Link ke halaman edit Post
```

### 11.3 Alur Review Gagal

```
GenerateArticleJob gagal (AI error / validasi gagal)
    │
    ├─ Log: status=failed, error_message
    ├─ Retry (maks 2x)
    │
    └─ Jika tetap gagal:
        ├─ Notifikasi ke admin (opsional)
        └─ Log final: status=failed
```

---

## 12. Keamanan & Etika

### 12.1 Keamanan

| Aspek               | Implementasi                                                                          |
| ------------------- | ------------------------------------------------------------------------------------- |
| API Key AI          | Disimpan di `.env`, TIDAK di database. Diakses via `config('services.openai.key')`    |
| Rate limiting       | Maks N request/jam ke AI provider (configurable)                                      |
| Input sanitization  | Prompt template di-sanitize sebelum dikirim ke AI                                     |
| Output sanitization | HTML response dari AI di-purify sebelum disimpan ke `body` (HTMLPurifier atau serupa) |
| Access control      | Hanya role `superadmin` dan `admin` yang dapat mengelola topik, pool, dan config      |
| Audit trail         | Semua generasi dicatat di `article_generation_logs`                                   |
| Cost control        | Dashboard widget menampilkan total token/biaya bulanan                                |

### 12.2 Etika Konten

| Prinsip                | Implementasi                                                 |
| ---------------------- | ------------------------------------------------------------ |
| Transparansi           | Setiap artikel AI wajib disclosure                           |
| Verifikasi referensi   | Hanya referensi nyata; key_references sebagai anchor         |
| Non-plagiarisme        | AI diminta menghasilkan tulisan original, bukan menyalin     |
| Editorial oversight    | Default draft, bukan auto-publish                            |
| Nada bertanggung jawab | System prompt melarang ujaran kebencian, SARA, dan provokasi |
| Hak cipta              | Tidak boleh menyalin panjang dari satu sumber                |

---

## 13. Rencana Implementasi Bertahap

### Phase 1: Database & Model (1 sprint)

- [ ] Migration: `article_topics`, `article_topic_tags`, `article_pools`, `article_pool_topic`, `article_generation_logs`
- [ ] Migration: alter `posts` → tambah `source_type`, `article_topic_id`, `generation_log_id`, `ai_disclosure`
- [ ] Model: `ArticleTopic`, `ArticlePool`, `ArticleGenerationLog`
- [ ] Update Model `Post`: relasi baru, scope `autoGenerated()`, accessor
- [ ] Seeder: kategori baru, tags baru, sample topics (5–10)
- [ ] Test: unit test model relations

### Phase 2: AI Provider & Generator Service (1 sprint)

- [ ] Contract: `App\Contracts\ArticleAiProvider` interface
- [ ] DTO: `ArticleAiResponse` value object
- [ ] Provider: `OpenAiArticleProvider` (implementasi pertama)
- [ ] Provider: `AnthropicArticleProvider` (opsional, implementasi kedua)
- [ ] Service: `ArticleGeneratorService` (TopicPicker, PromptBuilder, ResponseParser, PostCreator)
- [ ] Validator: `ArticleQualityValidator`
- [ ] Config: `config/article-generator.php`
- [ ] Test: unit test service dengan mock AI provider

### Phase 3: Scheduling & Queue (1 sprint)

- [ ] Command: `GenerateScheduledArticles`
- [ ] Job: `GenerateArticleJob`
- [ ] Registrasi scheduler di `routes/console.php`
- [ ] Supervisor config: `ops/supervisor/sepetak-articles-queue.conf`
- [ ] Test: feature test command + job dispatch

### Phase 4: Filament Admin UI (1 sprint)

- [ ] Resource: `ArticleTopicResource` (CRUD + action "Generate Sekarang")
- [ ] Resource: `ArticlePoolResource` (CRUD + preview jadwal)
- [ ] Resource: `ArticleGenerationLogResource` (read-only log viewer)
- [ ] Update: `PostResource` (source_type badge, filter, info banner)
- [ ] Widget: `ArticleGenerationStatsWidget`
- [ ] Policy: `ArticleTopicPolicy`, `ArticlePoolPolicy`, `ArticleGenerationLogPolicy`
- [ ] Test: Livewire test untuk setiap resource

### Phase 5: Seed Data & Polish (1 sprint)

- [ ] Seeder: 20–30 topik awal yang dikurasi dengan prompt template dan referensi
- [ ] Seeder: 3–5 pool dengan jadwal berbeda
- [ ] Frontend: AI disclosure styling di `posts/show.blade.php`
- [ ] Notification: `ArticleGeneratedNotification` ke admin
- [ ] Documentation: update `DEVELOPMENT_PARTS.md`
- [ ] Integration test end-to-end: scheduler → job → AI (mock) → post → publish
- [ ] Pint formatting pass
- [ ] CI update: tambah test baru di workflow

---

## 14. Migrasi & Kompatibilitas

### 14.1 Backward Compatibility

| Komponen                | Dampak                                              | Mitigasi                                          |
| ----------------------- | --------------------------------------------------- | ------------------------------------------------- |
| Tabel `posts`           | Tambah 4 kolom nullable — tidak breaking            | Default `source_type='manual'`, kolom lain NULL   |
| PostController (publik) | Tidak berubah — scope `published()` tetap berfungsi | Post auto-generated baru muncul setelah published |
| PostResource (Filament) | Tambah kolom & filter — tidak breaking              | Perubahan aditif saja                             |
| RSS Feed                | Otomatis memasukkan post baru                       | Tidak ada perubahan                               |
| Sitemap                 | Otomatis memasukkan post baru                       | Tidak ada perubahan                               |
| JSON-LD                 | Otomatis bekerja untuk post baru                    | Tidak ada perubahan                               |
| SEO                     | Post baru mendapat canonical, OG, dll.              | Tidak ada perubahan                               |

### 14.2 Rollback Strategy

- Semua migrasi memiliki `down()` yang jelas
- Kolom baru di `posts` bisa di-drop tanpa kehilangan post existing
- Tabel baru bisa di-drop secara independen
- Config AI key di `.env` — hapus untuk nonaktifkan total

---

## 15. Testing Strategy

| Layer       | Apa yang Ditest                                                                     | Tool                    |
| ----------- | ----------------------------------------------------------------------------------- | ----------------------- |
| Unit        | Model relations, TopicPicker logic, PromptBuilder, ResponseParser, QualityValidator | PHPUnit                 |
| Unit        | AI Provider (mock HTTP)                                                             | PHPUnit + Http::fake()  |
| Feature     | `articles:generate` command + job dispatch                                          | PHPUnit                 |
| Feature     | GenerateArticleJob end-to-end (mock AI)                                             | PHPUnit + Queue::fake() |
| Feature     | Filament resource CRUD (ArticleTopic, ArticlePool)                                  | Livewire::test()        |
| Feature     | PostResource filter source_type                                                     | Livewire::test()        |
| Feature     | API key missing → graceful error                                                    | PHPUnit                 |
| Integration | Scheduler → Job → Mock AI → Post created → Log recorded                             | PHPUnit                 |

Estimasi: 15–25 test baru, 60–100 assertion tambahan.

---

## 16. Risiko & Mitigasi

| #   | Risiko                             | Probabilitas | Dampak   | Mitigasi                                                                              |
| --- | ---------------------------------- | ------------ | -------- | ------------------------------------------------------------------------------------- |
| 1   | Biaya API AI tinggi                | Medium       | Medium   | Rate limit, budget cap per bulan, dashboard monitoring token                          |
| 2   | Kualitas artikel tidak konsisten   | Medium       | High     | Validasi otomatis + human review wajib (default draft)                                |
| 3   | Referensi fiktif (halusinasi AI)   | High         | High     | Key references injection, heuristic check, daftar referensi terverifikasi, disclaimer |
| 4   | AI provider downtime               | Low          | Medium   | Retry mechanism, fallback provider, queue akan re-attempt                             |
| 5   | Artikel terlalu repetitif          | Medium       | Medium   | Weighted random + cooldown + max_uses per topik                                       |
| 6   | Isu etika (plagiarisme, bias)      | Medium       | High     | System prompt ketat, disclaimer, editorial review                                     |
| 7   | API key bocor                      | Low          | High     | `.env` only, never in DB/log, `.gitignore` enforced                                   |
| 8   | Konten mengandung ujaran kebencian | Low          | Critical | System prompt boundary, output filter, human review                                   |
| 9   | Overhead maintenance pool & topik  | Low          | Low      | UI Filament yang mudah, bulk actions                                                  |
| 10  | Perubahan API vendor AI            | Medium       | Medium   | Interface abstraction, mudah ganti provider                                           |

---

## 17. Estimasi Struktur File

```
app/
├── Console/Commands/
│   └── GenerateScheduledArticles.php
├── Contracts/
│   └── ArticleAiProvider.php
├── DTOs/
│   └── ArticleAiResponse.php
├── Filament/
│   ├── Resources/
│   │   ├── ArticleTopicResource.php
│   │   ├── ArticleTopicResource/Pages/
│   │   ├── ArticlePoolResource.php
│   │   ├── ArticlePoolResource/Pages/
│   │   ├── ArticleGenerationLogResource.php
│   │   └── ArticleGenerationLogResource/Pages/
│   └── Widgets/
│       └── ArticleGenerationStatsWidget.php
├── Jobs/
│   └── GenerateArticleJob.php
├── Models/
│   ├── ArticleTopic.php
│   ├── ArticlePool.php
│   └── ArticleGenerationLog.php
├── Notifications/
│   └── ArticleGeneratedNotification.php
├── Policies/
│   ├── ArticleTopicPolicy.php
│   ├── ArticlePoolPolicy.php
│   └── ArticleGenerationLogPolicy.php
├── Services/
│   ├── ArticleGeneratorService.php
│   ├── TopicPicker.php
│   ├── PromptBuilder.php
│   ├── ResponseParser.php
│   └── ArticleQualityValidator.php
└── Services/AiProviders/
    ├── OpenAiArticleProvider.php
    └── AnthropicArticleProvider.php

config/
└── article-generator.php

database/
├── migrations/
│   ├── xxxx_create_article_topics_table.php
│   ├── xxxx_create_article_topic_tags_table.php
│   ├── xxxx_create_article_pools_table.php
│   ├── xxxx_create_article_pool_topic_table.php
│   ├── xxxx_create_article_generation_logs_table.php
│   └── xxxx_add_auto_article_columns_to_posts_table.php
└── seeders/
    ├── ArticleCategorySeeder.php
    ├── ArticleTagSeeder.php
    └── ArticleTopicSeeder.php

ops/supervisor/
└── sepetak-articles-queue.conf

tests/
├── Unit/
│   ├── TopicPickerTest.php
│   ├── PromptBuilderTest.php
│   ├── ResponseParserTest.php
│   └── ArticleQualityValidatorTest.php
└── Feature/
    ├── GenerateScheduledArticlesCommandTest.php
    ├── GenerateArticleJobTest.php
    └── Filament/
        ├── ArticleTopicResourceTest.php
        └── ArticlePoolResourceTest.php
```

---

## 18. Keputusan yang Perlu Diambil

Sebelum implementasi dimulai, berikut keputusan yang perlu di-resolve:

| #   | Keputusan               | Opsi                                                               | Rekomendasi                                                                             |
| --- | ----------------------- | ------------------------------------------------------------------ | --------------------------------------------------------------------------------------- |
| 1   | **AI Provider utama**   | OpenAI (GPT-4o) / Anthropic (Claude) / Keduanya                    | Mulai dengan **satu provider** untuk kesederhanaan; tambah fallback di phase berikutnya |
| 2   | **Default behavior**    | Auto-publish vs Draft (review dulu)                                | **Draft** — human review wajib untuk fase awal hingga kualitas terbukti konsisten       |
| 3   | **Budget bulanan AI**   | Berapa token/bulan yang dialokasikan                               | Tentukan ceiling (mis. $20/bulan = ~500K tokens GPT-4o)                                 |
| 4   | **Frekuensi awal**      | Berapa artikel/minggu                                              | Mulai **2–3 artikel/minggu** dan evaluasi                                               |
| 5   | **Author identity**     | User sistem khusus ("Redaksi SEPETAK") vs admin tertentu           | Buat user **"Redaksi SEPETAK"** sebagai author default artikel auto                     |
| 6   | **Cover image**         | Generate otomatis (AI image) / Placeholder / Manual setelah review | **Placeholder** generik per kategori untuk fase awal                                    |
| 7   | **Notifikasi review**   | Email ke admin / Filament notification / Keduanya                  | **Filament database notification** (ringan, in-panel)                                   |
| 8   | **Bahasa artikel**      | Hanya Indonesia / Bilingual (ID + EN)                              | **Indonesia saja** untuk fokus                                                          |
| 9   | **Route publik**        | Tetap `/berita/{slug}` atau pisah `/artikel/{slug}`                | **Tetap `/berita/{slug}`** — semua post di satu tempat, tidak fragmentasi SEO           |
| 10  | **Disclosure position** | Atas artikel / Bawah artikel / Keduanya                            | **Bawah artikel** (tidak mengganggu pembacaan)                                          |

---

## Lampiran A: Contoh Prompt Template untuk Topik

```
### Topik: Teori Nilai-Lebih dan Eksploitasi Buruh Tani di Jawa Barat

Tulis sebuah essay akademik yang menganalisis relevansi teori nilai-lebih
(Mehrwert/surplus value) Karl Marx terhadap kondisi buruh tani di Jawa Barat
kontemporer. Essay harus:

1. Menjelaskan konsep nilai-lebih dari Das Kapital Vol. I secara ringkas
   namun akurat
2. Mengontekstualisasikan konsep tersebut ke sektor pertanian Indonesia,
   khususnya relasi kerja petani penggarap, buruh tani harian, dan
   pemilik lahan di Karawang dan sekitarnya
3. Mengutip data konflik agraria terkini dari Konsorsium Pembaruan Agraria
   (KPA) dan/atau BPS
4. Menganalisis bagaimana mekanisme ekstraksi nilai-lebih beroperasi
   melalui: (a) upah di bawah biaya reproduksi, (b) rent-seeking
   intermediary, (c) ketergantungan input pertanian korporat
5. Menyertakan perspektif Henry Bernstein tentang classes of labour
   dalam agrarian political economy
6. Ditutup dengan refleksi tentang implikasi bagi pengorganisasian
   serikat pekerja tani

Referensi WAJIB disitasi:
- Marx, K. (1867). Das Kapital, Volume I.
- Bernstein, H. (2010). Class Dynamics of Agrarian Change.
- Konsorsium Pembaruan Agraria. (2023). Catatan Akhir Tahun 2023.
- Wiradi, G. (2000). Reforma Agraria: Perjalanan yang Belum Berakhir.
```

## Lampiran B: Contoh Config File

```php
// config/article-generator.php

return [
    'enabled' => env('ARTICLE_GENERATOR_ENABLED', false),

    'default_provider' => env('ARTICLE_AI_PROVIDER', 'openai'),

    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('ARTICLE_OPENAI_MODEL', 'gpt-4o'),
            'max_tokens' => (int) env('ARTICLE_MAX_TOKENS', 4096),
            'temperature' => (float) env('ARTICLE_TEMPERATURE', 0.7),
        ],
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ARTICLE_ANTHROPIC_MODEL', 'claude-sonnet-4-20250514'),
            'max_tokens' => (int) env('ARTICLE_MAX_TOKENS', 4096),
            'temperature' => (float) env('ARTICLE_TEMPERATURE', 0.7),
        ],
    ],

    'defaults' => [
        'status' => 'draft',
        'auto_publish' => false,
        'author_name' => 'Redaksi SEPETAK',
        'min_word_count' => 1500,
        'min_references' => 5,
    ],

    'limits' => [
        'max_per_day' => (int) env('ARTICLE_MAX_PER_DAY', 5),
        'max_per_month' => (int) env('ARTICLE_MAX_PER_MONTH', 50),
        'cooldown_hours' => (int) env('ARTICLE_TOPIC_COOLDOWN_HOURS', 72),
    ],

    'queue' => [
        'connection' => env('ARTICLE_QUEUE_CONNECTION', 'redis'),
        'name' => env('ARTICLE_QUEUE_NAME', 'articles'),
    ],
];
```

---

> **Catatan Review**: Dokumen ini adalah rancangan awal. Semua keputusan di Bagian 18 harus di-resolve sebelum implementasi dimulai. Estimasi total: **4–5 sprint** untuk implementasi penuh, atau **2 sprint** jika hanya Phase 1–3 (tanpa UI Filament yang lengkap).
