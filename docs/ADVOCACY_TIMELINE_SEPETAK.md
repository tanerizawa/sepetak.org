# Timeline advokasi & peristiwa SEPETAK — data untuk modul kasus agraria

**Impor ke database:** jalankan `php artisan db:seed --class=AdvocacyOrganizationCasesSeeder --force` (atau `php artisan db:seed` penuh — seeder dipanggil dari `DatabaseSeeder`). Entri memakai `case_code` tetap **`ORG-ADV-001`** … **`ORG-ADV-028`** (`updateOrCreate`, aman dijalankan ulang).

Dokumen ini mengumpulkan jejak advokasi, sengketa, aksi massa, dan pendampingan yang dikaitkan dengan **SEPETAK / Serikat Petani Karawang** dalam sumber internal repositori dan sumber publik (pers, Wikipedia, basis data konflik). **Belum semua baris siap impor otomatis:** tanggal awal sering perkiraan; verifikasi sekretariat sebelum memasukkan ke produksi.

**Pemetaan ke model `AgrarianCase` (Laravel):**

| Kolom DB | Isi dari dokumen ini |
| -------- | -------------------- |
| `title` | Judul singkat unik per baris |
| `summary` | 1–3 kalimat (boleh sama ringkasnya dengan baris “Ringkasan”) |
| `description` | Konteks lengkap + pihak + rujukan (HTML rich text di admin) |
| `location_text` | Lokasi bebas (kecamatan/desa/kabupaten) |
| `start_date` | Tanggal mulai perkara/aksi utama (YYYY-MM-DD); gunakan tanggal tengah rentang jika hanya tahun |
| `status` | Saran: `reported`, `legal_process`, `mediation`, `resolved`, `closed` — sesuaikan fakta |
| `priority` | Opsional; isi setelah prioritas internal disepakati |

Catatan terpisah untuk **`AgrarianCaseParty`** (pihak lawan, pendamping, pemerintah) dan **`AgrarianCaseUpdate`** (peristiwa lanjutan dalam satu kasus) — beberapa baris di bawah sebaiknya digabung menjadi **satu kasus** dengan banyak update (mis. Teluk Jambe: gugatan → MA → tol → media).

---

## Ringkasan sumber

| Kode sumber | Arti |
| ----------- | ---- |
| `repo:sejarah` | `database/seeders/SejarahPageContent.php` (narasi situs) |
| `repo:org` | `docs/ORGANIZATION_RESEARCH.md` |
| `repo:profil` | `docs/[PROFIL] Sejarah Serikat Petani Karawang.docx.md` (dokumen 2016; sebagian tanggal kongres **bertentangan** dengan konsensus situs terbaru — dipakai hanya untuk detail lapangan bila tidak kontradiktif) |
| `web:wikipedia` | [Wikipedia — Serikat Petani Karawang](https://id.wikipedia.org/wiki/Serikat_Petani_Karawang) |
| `web:sinews` | [Sindo — Sengketa Teluk Jambe / DPRD & PT SAMP](https://daerah.sindonews.com/berita/1087462/21/sengketa-teluk-jambe-dprd-karawang-dukung-pembongkaran-pt-samp) |
| `web:tanahkita` | [TanahKita — detil konflik PT SAMP vs tiga desa](https://tanahkita.id/data/konflik/detil/ZXB1bXowNkE4OFk) |
| `web:gres` | [Gresnews — konflik petani vs PT SAMP](https://www.gresnews.com/artikel/86846/Konflik-Petani-Karawang-Lawan-PT-SAMP-Meruncing-Telah-24-Tahun-Tak-Terselesaikan/) |
| `web:inews-aksi` | [iNews — aksi SEPETAK 6 Oktober 2022](https://karawang.inews.id/read/183914/serikat-pekerja-tani-karawang-berunjuk-rasa-hari-ini-berikut-6-tuntutannya) |
| `web:inews-dprd` | [iNews — dukungan DPRD terhadap aksi SEPETAK](https://karawang.inews.id/read/184232/dprd-karawang-dukung-unjuk-rasa-sepetak-janji-perjuangkan-hak-petani) |
| `web:elangmas` | [Elangmasnews — SEPETAK ke BPN Karawang](https://elangmasnews.com/peristiwa/kedatangan-serikat-pekerja-tani-karawang-sepetak-ke-kantor-badan-pertanahan-negara-bpn-karawang-dengan-ribuan-petani/) |
| `web:change` | [Change.org — pernyataan sikap kriminalisasi](https://www.change.org/p/pernyataan-sikap-atas-kriminalisasi-petani-yang-dilakuka-pemerintah-kabupaten-karawang) |
| `web:kpa` | [Pejabatpublik — KPA & Hari Tani 2025 (menyebut SEPETAK)](https://pejabatpublik.com/2025/09/22/profil-dewi-kartika-sekjen-konsorsium-pembaruan-agraria/) |
| `web:koranperdj` | [Koran Perdjoeangan — Karawang Poek 12 November 2025](https://www.koranperdjoeangan.com/ribuan-massa-karawang-poek-gruduk-pemda-buruh-petani-dan-mahasiswa-satu-suara-desak-cabut-perbup-19-2025/) |
| `web:suarana` | [Suarana — HUT ke-18 SEPETAK, Sedari](https://www.suarana.com/2025/12/hut-ke-18-sepetak-kukuhkan-tanah.html) |
| `web:antara-tol` | [ANTARA — blokade Tol Japek 11 Juli 2013 (Teluk Jambe)](https://www.antaranews.com/berita/384759/ratusan-warga-blokir-jalan-tol-jakarta-cikampek) |
| `web:kompas-tol` | [Kompas — dampak demo tol Cikampek 11 Juli 2013](https://money.kompas.com/read/2013/07/11/1322142/Pengguna.Jalan.Dirugikan.Akibat.Demo.di.Tol.Cikampek) |
| `web:okezone-tol` | [Okezone — alasan blokir tol 11 Juli 2013](https://news.okezone.com/read/2013/07/11/527/835379/ini-alasan-warga-petani-blokir-jalan-tol-cikampek) |
| `web:sindo-hatta` | [SINDOnews — Hatta Rajasa & koordinator SEPETAK Moris Moy Purba (2013)](https://daerah.sindonews.com/berita/760166/31/hatta-rajasa-pelaku-pemblokiran-jalan-tol-harus-ditindak) |
| `web:mediaindonesia-polri` | [Media Indonesia — galeri demo SEPETAK ke Mabes Polri, 30 Juni 2014](https://mediaindonesia.com/galleries/detail_galleries/305-demo-petani-karawang) |
| `web:inews-lapangan` | [iNews — liputan lapangan unjuk rasa Pemda 6 Oktober 2022](https://karawang.inews.id/read/184068/ratusan-petani-unjuk-rasa-di-pemda-karawang-tuntut-sertifikasi-tanah-hingga-turunkan-harga-bbm) |
| `web:galuh-lbh` | [Galuhpakuannusantara — LBH Cakra soal kriminalisasi pasca aksi SEPETAK 2023](https://www.galuhpakuannusantara.com/2023/08/aksi-sepetak-berujung-kriminalisasi-ini.html) |
| `web:kumparan-delegasi` | [kumparan — audiensi KPA di DPR; delegasi menyebut SEPETAK (24 September 2025)](https://kumparan.com/kumparannews/perwakilan-buruh-tani-diterima-dasco-hingga-nusron-bawa-9-tuntutan-25uq4DGgVjC) |
| `web:kompas-htn2025` | [Kompas — demo Hari Tani KPA / long march ke DPR (24 September 2025)](https://www.kompas.com/jawa-timur/read/2025/09/24/061500488/petani-kpa-gelar-demo-hari-tani-nasional-tuntut-reforma-agraria-yang) |
| `blog:longmarch2013` | [Blog arsip — long march Blitar disambut SEPETAK di Karawang (28 Januari 2013)](https://maklumatrakyat.blogspot.com/2013/01/longmarch-petani-blitar-jakarta-tiba-di.html) — **sekunder; verifikasi primer** |

---

## Timeline (urut kronologi)

Kolom **`gabung_ke_kasus`**: isi ID/nama kasus induk jika baris ini sebaiknya menjadi **Update** saja, bukan kasus baru.

| # | start_date | end_date | tipe | judul (calon `title`) | lokasi (`location_text`) | ringkasan | pihak / konteks | sumber | status_usul | gabung_ke_kasus |
|---|------------|----------|------|----------------------|----------------------------|------------|-----------------|--------|--------------|-----------------|
| 1 | 1998-01-01 | 2006-12-31 | advokasi_pra_organisasi | Advokasi kasus tanah Kuta Tandingan (STN) | Kuta Tandingan, Kec. Telukjambe Barat; Desa Karang Jaya, Kec. Pedes, Kab. Karawang | Advokasi STN atas sengketa/pengorganisasian yang menurut narasi internal menjadi salah satu akar gerakan sebelum SEPETAK; mengalami represi dan melemahnya basis. | STN, aparat, pemkab (implicit); basis awal kader. | `repo:profil` | `closed` / non-DT | — |
| 2 | 2007-11-03 | 2007-12-10 | tonggak_organisasi | Kongres I & deklarasi Serikat Petani Karawang | Karawang (kab.); basis Cilamaya Kulon, Cilamaya Wetan, Pakisjaya | Pendirian organisasi; AD & struktur organ. Bukan sengketa lahan, tetapi tonggak legal organisasi pendamping kasus. | Anggota basis lima desa. | `repo:sejarah`, AD/ART | `closed` | — |
| 3 | 2008-01-01 | 2009-12-31 | aksi_konflik_ekstraktif | Penolakan penambangan pasir laut Tanjung Pakis | Desa Tanjung Pakis (pesisir utara Karawang) | Mobilisasi nelayan, pedagang hasil laut, pelaku wisata pantai; live-in pengurus; menurut narasi berhasil menghentikan tambang pasir laut dan memperluas basis SEPETAK. | Warga vs operator tambang pasir laut; DPRD disebut dalam narasi profil. | `repo:profil`, `repo:sejarah` | `resolved` (sisi aksi) | — |
| 4 | 2009-01-01 | 2010-12-31 | aksi_konflik_ekstraktif | Penolakan penambangan batu andesit Tegalwaru | Tegalwaru, Karawang Selatan | Perlawanan terhadap industri ekstraktif batu andesit di dataran selatan. | Warga/SEPETAK vs penambangan. | `repo:sejarah`, `repo:profil` | `reported` / verifikasi | — |
| 5 | 2007-01-01 | 2008-12-31 | aksi_lintas_sektor | Aksi massa gabungan (infrastruktur, irigasi, kekeringan, solidaritas) | Berbagai wilayah Kab. Karawang | Menurut dokumen profil 2016, setelah tahun pertama pengorganisasian terdapat aksi massal lintas wilayah terkait tuntutan ekonomis. | SEPETAK, buruh, mahasiswa (implicit di narasi situs). | `repo:profil`, `repo:sejarah` | `closed` | — |
| 6 | 2010-12-10 | 2010-12-11 | tonggak_program | Kongres II — program TANI MOTEKAR | Karawang | Lima pilar: tanah, infrastruktur, modal, teknologi, akses pasar. | Organisasi. | `web:wikipedia`, `repo:sejarah` | `closed` | — |
| 7 | 2011-01-01 | 2011-12-31 | advokasi_ekonomi | Aksi ganti rugi gagal tanam Cilamaya | Cilamaya, Kab. Karawang | Tuntutan kompensasi bagi anggota gagal panen. | Petani vs pelaku ekonomi/pembiayaan (perlu rinci sekretariat). | `repo:sejarah`, `repo:profil` | `reported` | — |
| 8 | 2012-02-14 | — | pengorganisasian | Pembentukan Pokja Dusun Cimahi, Desa Cikarang | Dusun Cimahi, Desa Cikarang, Kec. Cilamaya Wetan | Fasilitasi DPTK; contoh jalur rekrutmen + konsolidasi menuju DPTD. | SEPETAK, warga dusun. | `repo:profil`, `database/seeders/StrukturOrganisasiPageContent.php` | `closed` | — |
| 9 | 2012-01-01 | 2012-12-31 | advokasi_pelayanan_publik | Pendampingan & aksi layanan kesehatan (Jamkesda/Jamkesmas) ke RSUD Karawang | RSUD Karawang | Hambatan administrasi pasien; aksi memicu perbaikan relasi dengan RSUD menurut dokumen profil. | Anggota vs rumah sakit/administrasi. | `repo:profil`, `repo:sejarah` | `mediation` / `resolved` | — |
| 10 | 2012-01-01 | 2013-12-31 | advokasi_pelayanan_publik | Pendampingan sengketa/tagihan listrik (PLN) | Basis anggota di pedesaan Kab. Karawang | Contoh advokasi non-agraria yang memperkuat legitimasi organisasi di basis. | Anggota vs PLN. | `repo:profil` | `reported` | — |
| 11 | 1990-01-01 | — | sengketa_lahan_korporasi | Sengketa lahan Teluk Jambe vs PT SAMP / APL (Industrial Karawang Estate) | Desa Wanakerta, Wanasari, Margamulya, Kec. Telukjambe Barat (empat desa disebut sebagian pers); ±350 ha diklaim | Konflik panjang penguasaan vs garapan warga; gugatan perdata, eksekusi kontroversial, keterlibatan SEPETAK sejak 2007 sebagai wadah pengorganisasian. | Warga vs PT Sumber Air Mas Pratama (SAMP) / Agung Podomoro Land; YLBHI disebut di TanahKita sebagai pendamping awal. | `web:wikipedia`, `web:tanahkita`, `web:gres`, `web:sinews` | `legal_process` | **Kasus induk** |
| 12 | 2013-02-20 | 2013-02-20 | aksi_protes_hukum | Aksi Mahkamah Agung (kekecewaan putusan Teluk Jambe) | Jakarta — kompleks MA | Massa memaksa masuk dan mengancam menduduki gedung MA. | Petani Karawang / SEPETAK vs lembaga peradilan. | `web:wikipedia` | `closed` (peristiwa) | Teluk Jambe |
| 13 | 2013-07-11 | 2013-07-11 | aksi_jalan_tol | Penutupan Tol Jakarta–Cikampek | Akses tol Japek terdampak | Protes atas putusan MA terkait 350 ha dan PT SAMP/APL. | SEPETAK & massa vs dampak putusan hukum. | `web:wikipedia` | `closed` | Teluk Jambe |
| 14 | 2013-03-21 | 2013-03-21 | klarifikasi_publik | Klarifikasi isu dukungan penambangan pasir laut | Lepas pantai utara Karawang / Cibuaya | Pernyataan resmi menyangkal dukungan ke tambang pasir setelah undangan ke BPMPT & pertemuan pengusaha. | SEPETAK vs isu publik / reputasi. | `web:wikipedia` | `closed` | — |
| 15 | 2012-03-15 | 2016-02-22 | advokasi_kebijakan | Sengketa Teluk Jambe — tekanan politik & DPRD (rekomendasi pembongkaran fasilitas PT) | Telukjambe Barat, Karawang | Liputan 2016: DPRD Komisi A rencana surat rekomendasi pembongkaran kantor pemasaran/reklame; isu IMB/HGB/AMDAL. | DPRD, Bupati, PT SAMP/BMI, warga tiga desa. | `web:sinews` | `mediation` | Teluk Jambe |
| 16 | 2010-01-01 | 2015-12-31 | advokasi_kebijakan | Penolakan revisi Perda RTRW Karawang (Cilamaya Wetan perkotaan) | Kec. Cilamaya Wetan & wilayah terdampak | Ancaman alih fungsi lahan pertanian ke kawasan perkotaan. | SEPETAK vs kebijakan tata ruang kabupaten. | `repo:sejarah`, `repo:profil` | `legal_process` / ongoing | — |
| 17 | 2016-04-25 | 2016-04-26 | tonggak_program | Kongres III — visi agraria & industri | Karawang | Rumusan “Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian”; pemetaan 5 wilayah rawan konflik. | Organisasi. | `repo:sejarah` | `closed` | — |
| 18 | 2010-01-01 | 2015-12-31 | program_basis | Percobaan pertanian kolektif (basis Pakisjaya, termasuk Telukjaya) | Desa Telukjaya & desa lain, Kec. Pakisjaya | Model sewa lahan absentee, input kolektif, surplus untuk akumulasi tanah; gagal kontrol internal menurut dokumen profil. | Anggota, pengurus DPTD (oknum), tuan tanah absentee. | `repo:profil` | `closed` | — |
| 19 | 2020-10-31 | 2020-11-01 | tonggak_organisasi | Kongres IV — nama Serikat Pekerja Tani Karawang & AD/ART baru | Karawang | Perubahan subjek perjuangan & formalisasi anggota penggarap/nelayan. | Organisasi. | `repo:sejarah`, AD/ART | `closed` | — |
| 20 | 2022-10-06 | 2022-10-06 | aksi_massa_kabupaten | Unjuk rasa Pemda Karawang — enam tuntutan strategis | Kantor Pemda Kab. Karawang | Tuntutan LPRA/sertifikasi, upah buruh, pendidikan gratis, BBM, BPN, kehutanan; koalisi LBH Cakra, KPBI, mahasiswa; dialog DPRD. | SEPETAK + koalisi vs Pemda/DPRD (aspirasi). | `web:inews-aksi`, `web:inews-dprd` | `reported` | — |
| 21 | 2023-07-21 | 2023-07-21 | administrasi_tanah | Pengajuan pendaftaran 88 bidang tanah (13 desa) ke BPN | 13 desa di Kab. Karawang (detail desa perlu entri sekretariat) | Langkah administratif hak atas tanah terkait klaim kawasan hutan tanpa dokumen utuh menurut narasi aksi. | SEPETAK, anggota, LBH Arya Mandalika, BPN. | `web:elangmas`, `repo:org` | `under_review` | Perhutani–klaim hutan (satukan?) |
| 22 | 2023-07-27 | 2023-07-27 | aksi_massa_kabupaten | Aksi massa ke Kantor BPN Karawang | Karawang | Ribuan petani; orasi LBH; tuntutan penyelesaian konflik agraria Perhutani vs petani. | SEPETAK, LBH Arya Mandalika, BPN, Perhutani (implicit). | `web:elangmas`, `repo:org` | `reported` | Gabung baris 21 jika satu program |
| 23 | 2023-08-01 | 2023-08-01 | pernyataan_sikap | Pernyataan sikap tolak kriminalisasi pasca aksi BPN | FORKOPIMDA Karawang (implicit target) | Respons terhadap tekanan hukum/aparat pasca 27 Juli. | SEPETAK & koalisi vs pemkab/aparat. | `web:change`, `repo:org` | `reported` | BPN 2023 |
| 24 | 2025-09-21 | 2025-09-24 | aksi_nasional | Hari Tani — mobilisasi ke Jakarta (KPA, 139 organisasi) | Jakarta (DPR); basis Jabar/Banten | Tuntutan 24 agenda struktural agraria; SEPETAK disebut di antara organisasi Jabar. | KPA, SEPETAK, ormas tani-nelayan. | `web:kpa` | `reported` | — |
| 25 | 2025-11-12 | 2025-11-12 | aksi_koalisi_kabupaten | Aksi “Karawang Poek” — tuntut evaluasi Perbup 19/2025 & reforma agraria | Pemda Kab. Karawang | ~6700 massa KBPP+ termasuk SEPETAK; tujuh tuntutan; audiensi dengan Bupati & DPRD. | Buruh, petani, mahasiswa vs kebijakan Pemkab. | `web:koranperdj` | `mediation` | — |
| 26 | 2025-12-10 | 2025-12-10 | peringatan_organisasi | HUT ke-18 — deklarasi tanah kolektif & penanaman mangrove | Desa Sedari, Kec. Cibuaya, Karawang | Acara HAM; undangan resmi DPTK; undangan Kepala ATR/BPN Karawang. | SEPETAK, pemerintah (tamu), kaum tani basis. | `web:suarana` | `closed` (event) | — |
| 27 | 2014-06-30 | 2014-06-30 | aksi_nasional | Demo SEPETAK ke Mabes Polri — desakan usut perampasan tanah | Mabes Polri, Jakarta Selatan | Liputan galeri pers: ratusan petani SEPETAK; fokus sengketa tanah di Kab. Karawang. | SEPETAK vs negara (penegakan/percepatan penyidikan). | `web:mediaindonesia-polri` | `reported` | — |
| 28 | 2013-01-28 | 2013-01-28 | solidaritas | Sambutan long march petani Blitar–Jakarta di Karawang | Bundaran Mall Karawang | Narasi: ~120 petani jalan kaki ke Istana; istirahat di GOR; **SEPETAK** dan aktivis Karawang menyambut. | Solidaritas antarwilayah petani. | `blog:longmarch2013` | `closed` (peristiwa) | — |

**Catatan sumber tambahan untuk baris yang sudah ada:** pemblokiran tol **11 Juli 2013** (#13) dapat dilengkapi dengan `web:antara-tol`, `web:kompas-tol`, `web:okezone-tol`, `web:sindo-hatta` (nama jalan desa di pers: Wanaraja/Wanakerta vs Wanasari — **samakan dengan arsip perkara**). Aksi **6 Oktober 2022** (#20): koalisi di lapangan menurut [iNews lapangan](https://karawang.inews.id/read/184068/ratusan-petani-unjuk-rasa-di-pemda-karawang-tuntut-sertifikasi-tanah-hingga-turunkan-harga-bbm) mencakup **GMPI Karawang** selain LBH Cakra & KPBI. **Hari Tani 2025** (#24): [kumparan](https://kumparan.com/kumparannews/perwakilan-buruh-tani-diterima-dasco-hingga-nusron-bawa-9-tuntutan-25uq4DGgVjC) memuat daftar delegasi audiensi yang secara eksplisit menyebut **SEPETAK**.

---

## Rekomendasi penggabungan untuk database

1. **Satu kasus induk “Teluk Jambe – PT SAMP/APL”** (`#11`) dengan **`AgrarianCaseUpdate`** untuk `#12`, `#13`, `#15` (dan putusan pengadilan jika ada berkas).
2. **Satu program “Pendaftaran tanah vs klaim hutan (88 bidang, 13 desa)”** (`#21`–`#23`) agar tidak memecah administrasi dan aksi menjadi duplikat artifisial.
3. **Kongres** (`#2`, `#6`, `#17`, `#19`) biasanya **bukan** baris `AgrarianCase`; lebih cocok sebagai metadata organisasi atau `AdvocacyProgram` jika modul itu dipakai.
4. **#18 pertanian kolektif** — masukkan hanya jika modul kasus Anda mengizinkan tipe “program ekonomi basis”; jika strictly “konflik lahan”, boleh diabaikan.

---

## Cuplikan deskripsi panjang (untuk kolom `description`)

### Teluk Jambe (#11)

Warga menggarap lahan eks landreform/partikelir selama puluhan tahun dengan bukti pajak; PT SAMP memperoleh klaim HGB skala besar (sumber TanahKita & pers). SEPETAK mengorganisir gugatan perdata dan aksi lanjutan. Konflik melibatkan tiga–empat desa di Kec. Telukjambe Barat; luasan yang diperdebatkan di pers sering **350 ha** vs objek gugatan **65 ha** (Sindo 2016). Status hukum: verifikasi berkas terbaru di pengadilan/BPN.

### BPN / Perhutani 2023 (#21–#23)

Pendaftaran **88 bidang** di **13 desa** (21 Juli) lalu aksi massa **27 Juli 2023** ke BPN Karawang bersama LBH Arya Mandalika; orasi menyangkal klaim hutan tanpa dokumen utuh sementara HGB/HGU pihak swasta tidak disentuh. Pernyataan sikap **1 Agustus 2023** menolak kriminalisasi.

---

## Versi CSV (salin ke spreadsheet)

Kolom: `start_date,end_date,event_type,title,location_text,summary,status_suggestion,sources`

```csv
start_date,end_date,event_type,title,location_text,summary,status_suggestion,sources
1998-01-01,2006-12-31,advokasi_pra_organisasi,Advokasi Kuta Tandingan (STN),"Telukjambe Barat; Pedes, Karawang",Akar gerakan pra-SEPETAK; represi memelemahkan basis.,closed,repo:profil
2007-11-03,2007-12-10,tonggak_organisasi,Kongres I & deklarasi SEPETAK,"Karawang",Pendirian Serikat Petani Karawang.,closed,"repo:sejarah;AD/ART"
2008-01-01,2009-12-31,aksi_konflik_ekstraktif,Penolakan tambang pasir laut Tanjung Pakis,"Tanjung Pakis, Karawang",Advokasi nelayan & warga; narasi penghentian operasi.,resolved,repo:profil;repo:sejarah
2009-01-01,2010-12-31,aksi_konflik_ekstraktif,Penolakan tambang andesit Tegalwaru,"Tegalwaru, Karawang",Perlawanan industri ekstraktif selatan.,reported,repo:sejarah
2010-12-10,2010-12-11,tonggak_program,Kongres II TANI MOTEKAR,"Karawang",Lima pilar perjuangan.,closed,web:wikipedia
2011-01-01,2011-12-31,advokasi_ekonomi,Ganti rugi gagal tanam Cilamaya,"Cilamaya, Karawang",Tuntutan kompensasi anggota.,reported,repo:sejarah
2012-02-14,,pengorganisasian,Pokja Dusun Cimahi Desa Cikarang,"Cimahi, Cikarang, Cilamaya Wetan",Pembentukan Pokja.,closed,repo:profil
2012-01-01,2012-12-31,advokasi_pelayanan_publik,Jamkesda/Jamkesmas RSUD Karawang,"RSUD Karawang",Aksi & perbaikan relasi layanan.,mediation,repo:profil
2012-01-01,2013-12-31,advokasi_pelayanan_publik,Pendampingan sengketa listrik PLN,"Karawang",Advokasi anggota vs PLN.,reported,repo:profil
1990-01-01,,sengketa_lahan_korporasi,Sengketa Teluk Jambe vs PT SAMP/APL,"Wanakerta; Wanasari; Margamulya; Telukjambe Barat",Konflik 350 ha; gugatan & eksekusi kontroversial; keterlibatan SEPETAK sejak 2007.,legal_process,"web:wikipedia;web:tanahkita;web:gres;web:sinews"
2013-02-20,,aksi_protes_hukum,Aksi Mahkamah Agung (Teluk Jambe),Jakarta,Massa desak MA terkait putusan.,closed,web:wikipedia
2013-07-11,,aksi_jalan_tol,Penutupan Tol Japek (Teluk Jambe),Tol Jakarta-Cikampek,Protes putusan MA & SAMP.,closed,web:wikipedia
2013-03-21,,klarifikasi_publik,Klarifikasi isu tambang pasir laut,"Pantai utara Karawang",Bantah dukungan tambang.,closed,web:wikipedia
2012-03-15,2016-02-22,advokasi_kebijakan,DPRD & fasilitas PT SAMP Teluk Jambe,"Telukjambe Barat",Rekomendasi pembongkaran bangunan tanpa izin (liputan 2016).,mediation,web:sinews
2010-01-01,2015-12-31,advokasi_kebijakan,Penolakan revisi Perda RTRW,"Cilamaya Wetan, Karawang",Ancaman konversi lahan pertanian.,legal_process,repo:profil
2016-04-25,2016-04-26,tonggak_program,Kongres III visi agraria,"Karawang",Visi & pemetaan wilayah rawan.,closed,repo:sejarah
2010-01-01,2015-12-31,program_basis,Pertanian kolektif basis Pakisjaya,"Telukjaya dll., Pakisjaya",Program ekonomi kolektif; kontrol internal gagal.,closed,repo:profil
2020-10-31,2020-11-01,tonggak_organisasi,Kongres IV nama Pekerja Tani,"Karawang",AD/ART 2020-2023.,closed,repo:sejarah
2022-10-06,,aksi_massa_kabupaten,Unjuk rasa Pemda + 6 tuntutan,"Pemda Karawang",Koalisi LBH KPBI mahasiswa; dialog DPRD.,reported,web:inews-aksi;web:inews-dprd
2023-07-21,,administrasi_tanah,Pendaftaran 88 bidang 13 desa,"13 desa Karawang",Langkah ke BPN terkait klaim hutan.,under_review,web:elangmas
2023-07-27,,aksi_massa_kabupaten,Aksi ribuan petani ke BPN Karawang,"BPN Karawang",Tuntutan administrasi tanah adil.,reported,web:elangmas
2023-08-01,,pernyataan_sikap,Tolak kriminalisasi pasca BPN,"Karawang",Respons FORKOPIMDA/pemkab.,reported,web:change
2025-09-24,,aksi_nasional,Hari Tani KPA Jakarta,"Jakarta",139 ormas; SEPETAK bagian Jabar.,reported,web:kpa
2025-11-12,,aksi_koalisi_kabupaten,Karawang Poek,"Pemda Karawang",6700 massa; Perbup 19/2025; reforma agraria.,mediation,web:koranperdj
2025-12-10,,peringatan_organisasi,HUT 18 tanah kolektif Sedari,"Desa Sedari, Cibuaya",Deklarasi tanah kolektif & mangrove.,closed,web:suarana
2014-06-30,,aksi_nasional,Demo SEPETAK ke Mabes Polri,"Jakarta",Desakan usut perampasan tanah Karawang.,reported,web:mediaindonesia-polri
2013-01-28,,solidaritas,Sambutan long march Blitar di Karawang,"Karawang",SEPETAK sambut petani Blitar menuju Jakarta.,closed,blog:longmarch2013
```

---

**Pemutakhiran dokumen:** April 2026 — riset web diperluas (demo, blokade tol, koalisi KBPP/KPA, kriminalisasi, Mabes Polri 2014, solidaritas long march 2013). Baris #28 memakai sumber blog: wajib **verifikasi primer** sebelum impor produksi. Setelah verifikasi sekretariat, perbarui nama desa konsisten untuk Teluk Jambe, daftar 13 desa BPN 2023, dan nomor perkara pengadilan.
