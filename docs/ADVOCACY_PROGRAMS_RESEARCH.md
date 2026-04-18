# Kurasi Program Advokasi SEPETAK — untuk pengisian modul **Program Advokasi**

Dokumen ini mengumpulkan **calon entri `advocacy_programs`** (dan petunjuk **`advocacy_actions`**) dari **dokumentasi repositori** (`docs/`, `database/seeders/`, halaman situs) serta **sumber publik terbatas** di internet. Gunakan sebagai **bahan kerja** pengisian Filament; **verifikasi sekretariat** sebelum mempublikasikan sebagai klaim resmi organisasi.

**Impor ke database:** `php artisan db:seed --class=AdvocacyProgramsOrganizationSeeder --force` (atau `php artisan db:seed` penuh — dipanggil dari `DatabaseSeeder` setelah kasus organisasi). Entri memakai `program_code` stabil **`ORG-PRG-001`** … **`ORG-PRG-012`** (`updateOrCreate`). Beberapa **aksi program** contoh diisi otomatis (Kongres II/III, BPN 2023, Hari Tani 2025, mangrove Sedari).

**Halaman publik:** daftar `https://sepetak.org/program-advokasi` (`advocacy-programs.index`), detail `https://sepetak.org/program-advokasi/{program_code}` (`advocacy-programs.show`) — pola sama seperti kasus agraria; tautan di navigasi, footer, beranda (statistik), dan `sitemap.xml`.

**Konteks situs:** teks beranda menyebut *“Program advokasi, pelatihan, dan pengorganisasian yang aktif dijalankan departemen terkait.”* — modul database mendukung pelacakan program lintas waktu tanpa kolom “departemen” resmi; label departemen di bawah bersifat **usulan pemetaan** ke struktur AD/ART.

---

## 1. Pemetaan ke skema database (Laravel)

### Tabel `advocacy_programs`

| Kolom | Batas / enum | Catatan pengisian |
| ----- | ------------ | ----------------- |
| `program_code` | `string(30)`, unik | Biarkan kosong saat buat baru → model mengisi `ADV-YYYYMMDD-XXXXX`. Atau set manual (mis. `ADV-TANIMOTEKAR`) jika ingin stabil untuk impor skrip. |
| `title` | `string(200)` | Judul singkat untuk daftar publik (jika nanti diekspos) & admin. |
| `description` | `longText` | Rich text: tujuan, sasaran anggota, luaran, rujukan dokumen. |
| `status` | `planned` \| `active` \| `paused` \| `completed` | **Aktif** = program berjalan hari ini; **Selesai** = arsip jelas; **Direncanakan** = MOU/rencana belum lapangan. |
| `start_date` / `end_date` | `date`, nullable | Rentang legal/operasional; boleh null jika berkelanjutan. |
| `lead_user_id` | FK → `users` | Penanggung jawab di panel admin (pengurus terdaftar). |
| `location_text` | `string(200)` | Contoh: `Kab. Karawang`, `Desa Sedari, Kec. Cibuaya`, `Basis Telukjambe Barat`. |
| Foto | Media koleksi `photos` | Unggah dokumentasi kegiatan (opsional). |

### Tabel `advocacy_actions` (anak per program)

| Kolom | Enum `action_type` | Label UI |
| ----- | ------------------- | -------- |
| `action_date` | tanggal | — |
| `action_type` | `meeting` | Rapat |
| | `training` | Pelatihan |
| | `campaign` | Kampanye |
| | `field_visit` | Kunjungan Lapangan |
| | `legal` | Proses Hukum |
| | `other` | Lainnya |
| `notes` / `outcome` | teks | Catatan lapangan / hasil. |

**Relasi:** satu program → banyak aksi (RelationManager **Aksi Program** di Filament).

---

## 2. Struktur departemen (ringkas dari AD/ART)

`docs/ADART SEPETAK.docx.md` mendefinisikan **Dewan Pimpinan Tani Kabupaten (DPTK)** dibantu staf, termasuk **departemen** antara lain:

- **Departemen internal** — koordinasi & kontrol kerja harian.
- **Departemen perjuangan tani** — aksi, logistik aksi, koordinasi lapangan.
- **Departemen pendidikan** — pendidikan anggota & masyarakat (pleno bulanan).
- **Departemen penelitian** — kajian, data, evaluasi.
- **Departemen propaganda** — publikasi, kampanye kesadaran.
- **Departemen perempuan** — kerja khusus gender (redaksi AD perlu dibaca utuh untuk tugas rinci).
- **Departemen dana dan usaha** — ekonomi organisasi.

Kolom **“Departemen (usulan)”** pada tabel §3–§4 hanya untuk **merapikan** konten saat Anda menulis deskripsi program di admin — **bukan** field database.

---

## 3. Calon program dari dokumentasi **internal repositori**

Sumber utama: `database/seeders/VisiMisiPageContent.php`, `SejarahPageContent.php`, `docs/ORGANIZATION_RESEARCH.md`, `docs/ADVOCACY_TIMELINE_SEPETAK.md`, `docs/ADART SEPETAK.docx.md`.

| No | Judul program (calon `title`) | Ringkasan operasional | Departemen (usulan) | Rentang (usulan) | Status DB (usulan) | Rujukan internal |
| -- | ----------------------------- | ----------------------- | --------------------- | ---------------- | ------------------ | ---------------- |
| P1 | **TANI MOTEKAR** — lima pilar perjuangan | Payung program kongres: **Tanah, Infrastruktur, Modal, Teknologi, Akses pasar**; narasi industrialisasi pertanian rakyat. | Pendidikan + propaganda + perjuangan | 2010 — berkelanjutan | `active` atau `completed` (jika hanya arsip kongres) + program turunan `active` | VisiMisiPageContent; Wikipedia dikutip di seeder |
| P2 | **Visi Kongres III** — kedaulatan agraria & industrialisasi pertanian | Payung strategis pasca 2016; pemetaan **lima wilayah rawan konflik** (hutan, Tegalwaru, industri, pangan, pesisir). | Penelitian + perjuangan | 2016 — berkelanjutan | `active` | SejarahPageContent; Wilayah kerja |
| P3 | **Pengorganisasian basis** — DPTD, Pokja, konsolidasi desa | Struktur AD: Kongres → Dewan Tani → DPTK → DPTD → Pokja; contoh historis **Pokja Dusun Cimahi** (2012). | Perjuangan + internal | 2007 — berkelanjutan | `active` | SejarahPageContent; ADVOCACY_TIMELINE #8; AD/ART |
| P4 | **Pendidikan kritis & sekolah tani** | Misi organisasi: sekolah tani, diskusi dusun, propaganda internal, kesadaran hukum agraria. | Pendidikan + propaganda | — | `active` | VisiMisiPageContent (Misi) |
| P5 | **Advokasi sengketa agraria** (pendampingan berkelanjutan) | Sengketa lahan, absentee, Perhutani, korporasi; gugatan, mediasi, aksi massa terikat kasus. | Perjuangan + (aksi `legal`) | 1990-an — berkelanjutan | `active` | ORGANIZATION_RESEARCH; timeline ORG-ADV-011 dst. |
| P6 | **Administrasi & pendaftaran hak atas tanah** (lintas desa / BPN) | Jalur **88 bidang / 13 desa**, pendampingan LBH; terkait klaim kawasan hutan. | Perjuangan + legal | 2023 — berkelanjutan | `active` | ORG-ADV-021, ORG-ADV-022 |
| P7 | **Intervensi kebijakan tata ruang** (Perda RTRW, kawasan strategis) | Perlawanan konversi lahan pertanian & rencana perkotaan. | Penelitian + perjuangan | 2010-an — berkelanjutan | `active` / `paused` | ORG-ADV-016; Sejarah |
| P8 | **Advokasi pelayanan publik anggota** | Contoh arsip: PLN, Jamkesda/Jamkesmas ke RSUD — memperkuat legitimasi organisasi. | Internal (+ pendidikan) | 2012–2013 (contoh) | `completed` | ADVOCACY_TIMELINE #9–10; VisiMisi “advokasi pelayanan publik” |
| P9 | **Pertanian kolektif basis** (model ekonomi desa) | Program ekonomi kolektif basis Pakisjaya/Telukjaya; narasi internal menutup karena kontrol. | Dana & usaha | ±2010–2015 | `completed` | ADVOCACY_TIMELINE #18 |
| P10 | **Koalisi & solidaritas agraria** (KPA, serikat wilayah, buruh) | Kerja sama KPA, SPP, Majalengka, Banten, Cianjur, dll.; aksi bersama (Hari Tani, dll.). | Perjuangan + propaganda | — | `active` | ORGANIZATION_RESEARCH; VisiMisi (aliansi) |
| P11 | **Pesisir & ekologi** — advokasi tambang pasir, mangrove | Tanjung Pakis (pasir laut); penanaman mangrove Sedari (HUT 18). | Perjuangan + penelitian | 2008–2009; 2025 | `resolved` / `active` | Sejarah; Suarana HUT 18 |
| P12 | **Penolakan ekstraktif** — andesit Tegalwaru | Tekanan industri batu andesit selatan. | Perjuangan | 2009–2010 | `reported` atau `completed` | SejarahPageContent |

---

## 4. Temuan **internet** (ringkas; bukan kurikulum resmi)

| Temuan | Relevansi untuk “Program” | URL |
| ------ | --------------------------- | --- |
| **Wikipedia — Serikat Petani Karawang** | Kongres, sengketa, aksi; **tidak** memuat daftar pelatihan terjadwal. | [id.wikipedia.org/wiki/Serikat_Petani_Karawang](https://id.wikipedia.org/wiki/Serikat_Petani_Karawang) |
| **KPA — daftar organisasi Jabar** | SEPETAK disebut di antara ormas yang diajak aksi/mobilisasi **Hari Tani 2025**; ini **kerja koalisi**, bisa dijadikan program *“Partisipasi koalisi KPA”* atau dicatat sebagai **Aksi** di bawah P10. | [pejabatpublik.com — profil Sekjen KPA](https://pejabatpublik.com/2025/09/22/profil-dewi-kartika-sekjen-konsorsium-pembaruan-agraria/) |
| **Suarana — HUT ke-18 SEPETAK** | Rangkaian **deklarasi tanah kolektif** + **penanaman mangrove** Sedari (10 Desember 2025); cocok sebagai **aksi/kampanye** di bawah P11 atau program acara khusus. | [suarana.com/2025/12/hut-ke-18-sepetak-kukuhkan-tanah.html](https://www.suarana.com/2025/12/hut-ke-18-sepetak-kukuhkan-tanah.html) |
| **Elangmasnews — aksi BPN** | Mobilisasi massa & pendampingan LBH; lebih dekat ke **kasus** (`agrarian_cases`) daripada program berkelanjutan — bisa **dilink** dari deskripsi P6. | [elangmasnews.com — SEPETAK ke BPN](https://elangmasnews.com/peristiwa/kedatangan-serikat-pekerja-tani-karawang-sepetak-ke-kantor-badan-pertanahan-negara-bpn-karawang-dengan-ribuan-petani/) |

### Pembeda penting (jangan salah atribusi)

- **Diklat organisasi FSP KEP SPSI Karawang** (serikat buruh pabrik) adalah kegiatan **FSP KEP SPSI**, bukan SEPETAK — hanya relevan sebagai contoh **ekosistem pelatihan di Karawang**, bukan data program SEPETAK. Contoh liputan: [spkep-spsi.org — Diklat organisasi PC FSP KEP SPSI Karawang](https://spkep-spsi.org/pc-fsp-kep-spsi-karawang-gelar-diklat-organisasi-untuk-perkuat-kaderisasi-anggota/).
- **Serikat Tani Karawang (Setakar)** ≠ SEPETAK — jangan menggabungkan agenda demo/RDP mereka ke program SEPETAK tanpa verifikasi.

---

## 5. CSV usulan (salin ke spreadsheet → isi di Filament)

Kolom diselaraskan dengan kebutuhan admin; `program_code` boleh dikosongkan saat input manual.

```csv
suggested_key,title,status_suggestion,start_date,end_date,location_text,department_tag,primary_source
TANIMOTEKAR,TANI MOTEKAR — lima pilar perjuangan (Tanah Infrastruktur Modal Teknologi Akses Pasar),active,2010-12-10,,Kabupaten Karawang,Pendidikan+Propaganda,VisiMisiPageContent+Wikipedia
VISI2016,Visi Kongres III — Rebut Kedaulatan Agraria Bangun Industrialisasi Pertanian,active,2016-04-25,,Kabupaten Karawang,Penelitian+Perjuangan,SejarahPageContent
BASIS,Pengorganisasian basis — DPTD Pokja konsolidasi desa,active,2007-12-10,,Kabupaten Karawang,Perjuangan+Internal,ADART+SejarahPageContent
SIKOL,Pendidikan kritis sekolah tani diskusi dusun,active,,,Kabupaten Karawang,Pendidikan+Propaganda,VisiMisiPageContent
AGRAKON,Advokasi sengketa agraria berkelanjutan,active,1990-01-01,,Kabupaten Karawang,Perjuangan+Legal,ORGANIZATION_RESEARCH
BPN88,Administrasi hak atas tanah & pendampingan BPN (13 desa),active,2023-07-21,,Kabupaten Karawang,Perjuangan+Legal,ADVOCACY_TIMELINE+Elangmasnews
RTRW,Intervensi kebijakan tata ruang & Perda RTRW,active,2010-01-01,,Kabupaten Karawang,Penelitian+Perjuangan,Sejarah+ORG-ADV-016
LAYANPUB,Advokasi pelayanan publik anggota (arsip PLN Jamkesda dll),completed,2012-01-01,2013-12-31,Kabupaten Karawang,Internal,ADVOCACY_TIMELINE
KOLEKTIF,Pertanian kolektif basis (arsip Pakisjaya),completed,2010-01-01,2015-12-31,Kec. Pakisjaya,Dana+Usaha,ADVOCACY_TIMELINE
KPA_KOAL,Koalisi agraria & partisipasi KPA serikat wilayah,active,,,Jabar+Nasional,Perjuangan+Propaganda,ORGANIZATION_RESEARCH
PESISIR,Pesisir ekologi tambang pasir & mangrove,active,2008-01-01,,Pesisir utara Karawang; Sedari,Perjuangan+Penelitian,Sejarah+Suarana
ANDESIT,Penolakan tambang andesit Tegalwaru,reported,2009-01-01,2010-12-31,Tegalwaru,Perjuangan,SejarahPageContent
```

---

## 6. Contoh isian **Aksi Program** (`advocacy_actions`)

Gunakan setelah baris `advocacy_programs` dibuat.

| Program (usulan) | `action_date` | `action_type` | Catatan `notes` |
| ------------------ | ------------- | --------------- | ----------------- |
| P6 BPN88 | 2023-07-21 | `legal` | Pengajuan 88 bidang — taut ke ORG-ADV-021 |
| P6 BPN88 | 2023-07-27 | `campaign` | Aksi massa BPN — ORG-ADV-022 |
| P11 Pesisir | 2025-12-10 | `field_visit` | Penanaman mangrove Sedari — HUT 18 |
| P10 KPA_KOAL | 2025-09-24 | `campaign` | Hari Tani ke DPR — ORG-ADV-024 |
| P1 TANIMOTEKAR | 2010-12-10 | `meeting` | Kongres II penetapan lima pilar |

---

## 7. Checklist validasi sekretariat

- [ ] Nama resmi program & ejaan lima pilar TANI MOTEKAR.
- [ ] Penanggung jawab (`lead_user_id`) per program vs struktur DPTK terkini.
- [ ] Mana yang **program organisasi** vs **entri kasus** (`agrarian_cases`) — hindari duplikasi konten; gunakan taut narif di `description`.
- [ ] Tanggal pasti kegiatan pelatihan (jika ada arsip rapat pleno departemen pendidikan).
- [ ] Foto & izin publikasi untuk koleksi `photos`.

---

**Pemutakhiran:** April 2026 — isi default **Program Advokasi** di-database lewat `AdvocacyProgramsOrganizationSeeder`; penyuntingan lanjutan dilakukan di Filament.
