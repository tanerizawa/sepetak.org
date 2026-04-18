<?php

namespace Database\Seeders;

use App\Models\AgrarianCase;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/**
 * Mengisi kasus/advokasi organisasi dari kurasi docs/ADVOCACY_TIMELINE_SEPETAK.md.
 * Idempotent: updateOrCreate berdasarkan case_code ORG-ADV-001 … ORG-ADV-028.
 */
class AdvocacyOrganizationCasesSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->where('email', 'admin@sepetak.org')->value('id');

        foreach ($this->rows() as $row) {
            AgrarianCase::query()->updateOrCreate(
                ['case_code' => $row['case_code']],
                [
                    'title' => $row['title'],
                    'summary' => $row['summary'],
                    'description' => $row['description'],
                    'location_text' => $row['location_text'],
                    'start_date' => $row['start_date'],
                    'status' => $row['status'],
                    'priority' => $row['priority'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]
            );
        }

        Cache::forget('homepage.stats');
        Cache::forget('homepage.stats.v2');

        $this->command?->info('Kasus advokasi organisasi: '.count($this->rows()).' entri (case_code ORG-ADV-*).');
    }

    /**
     * @return list<array{case_code:string,title:string,summary:string,description:string,location_text:?string,start_date:string,status:string,priority:string}>
     */
    private function rows(): array
    {
        $foot = "\n\n— Data diimpor dari kurasi repositori (docs/ADVOCACY_TIMELINE_SEPETAK.md, April 2026). Sesuaikan di admin setelah verifikasi sekretariat.";

        return [
            [
                'case_code' => 'ORG-ADV-001',
                'title' => 'Advokasi kasus tanah Kuta Tandingan (STN, pra-SEPETAK)',
                'summary' => 'Akar gerakan: STN mengadvokasi tanah di Kuta Tandingan dan mengorganisir Karang Jaya; advokasi melemah setelah represi.',
                'description' => 'Tipe: advokasi_pra_organisasi. Rentang perkiraan 1998–2006. Sumber: docs/[PROFIL] Sejarah Serikat Petani Karawang.docx.md.'.$foot,
                'location_text' => 'Kuta Tandingan, Kec. Telukjambe Barat; Karang Jaya, Kec. Pedes, Kab. Karawang',
                'start_date' => '1998-01-01',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-002',
                'title' => 'Kongres I & deklarasi Serikat Petani Karawang (2007)',
                'summary' => 'Pendirian legal organisasi; AD pertama; basis lima desa Cilamaya & Pakisjaya.',
                'description' => 'Tipe: tonggak_organisasi. Bukan sengketa lahan; catatan kelembagaan untuk konteks advokasi berikutnya. Sumber: AD/ART Pasal 2; database/seeders/SejarahPageContent.php.'.$foot,
                'location_text' => 'Kabupaten Karawang',
                'start_date' => '2007-11-03',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-003',
                'title' => 'Penolakan penambangan pasir laut Tanjung Pakis',
                'summary' => 'Advokasi bersama nelayan, pedagang hasil laut, dan warga wisata pantai; narasi internal menegaskan penghentian operasi tambang.',
                'description' => 'Tipe: aksi_konflik_ekstraktif. Rentang: 2008–2009. Sumber: narasi profil organisasi & halaman sejarah situs.'.$foot,
                'location_text' => 'Desa Tanjung Pakis, Kab. Karawang (pesisir utara)',
                'start_date' => '2008-01-01',
                'status' => 'resolved',
                'priority' => 'medium',
            ],
            [
                'case_code' => 'ORG-ADV-004',
                'title' => 'Penolakan penambangan batu andesit Tegalwaru',
                'summary' => 'Perlawanan terhadap industri ekstraktif di dataran selatan Karawang.',
                'description' => 'Tipe: aksi_konflik_ekstraktif. Rentang: 2009–2010. Sumber: SejarahPageContent.'.$foot,
                'location_text' => 'Tegalwaru, Karawang Selatan',
                'start_date' => '2009-01-01',
                'status' => 'reported',
                'priority' => 'medium',
            ],
            [
                'case_code' => 'ORG-ADV-005',
                'title' => 'Aksi massa gabungan (infrastruktur, irigasi, kekeringan, solidaritas)',
                'summary' => 'Aksi lintas wilayah terkait tuntutan ekonomis dan solidaritas (periode konsolidasi awal).',
                'description' => 'Tipe: aksi_lintas_sektor. Rentang: 2007–2008 (perkiraan). Sumber: dokumen profil 2016.'.$foot,
                'location_text' => 'Berbagai wilayah Kab. Karawang',
                'start_date' => '2007-01-01',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-006',
                'title' => 'Kongres II — program TANI MOTEKAR (2010)',
                'summary' => 'Lima pilar: tanah, infrastruktur, modal, teknologi, akses pasar.',
                'description' => 'Tipe: tonggak_program. Tanggal: 10–11 Desember 2010. Sumber: Wikipedia id — Serikat Petani Karawang.'.$foot,
                'location_text' => 'Karawang',
                'start_date' => '2010-12-10',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-007',
                'title' => 'Aksi ganti rugi gagal tanam Cilamaya (2011)',
                'summary' => 'Tuntutan kompensasi bagi anggota yang gagal panen.',
                'description' => 'Tipe: advokasi_ekonomi. Sumber: SejarahPageContent; dokumen profil.'.$foot,
                'location_text' => 'Cilamaya, Kab. Karawang',
                'start_date' => '2011-01-01',
                'status' => 'reported',
                'priority' => 'medium',
            ],
            [
                'case_code' => 'ORG-ADV-008',
                'title' => 'Pembentukan Pokja Dusun Cimahi, Desa Cikarang',
                'summary' => 'Fasilitasi DPTK; jalur rekrutmen menuju konsolidasi desa.',
                'description' => 'Tipe: pengorganisasian. Tanggal: 14 Februari 2012. Sumber: dokumen profil; StrukturOrganisasiPageContent.'.$foot,
                'location_text' => 'Dusun Cimahi, Desa Cikarang, Kec. Cilamaya Wetan',
                'start_date' => '2012-02-14',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-009',
                'title' => 'Pendampingan & aksi layanan kesehatan (Jamkesda/Jamkesmas) ke RSUD Karawang',
                'summary' => 'Hambatan administrasi pasien; memicu perbaikan relasi dengan RSUD menurut narasi internal.',
                'description' => 'Tipe: advokasi_pelayanan_publik. Rentang: 2012. Sumber: dokumen profil organisasi.'.$foot,
                'location_text' => 'RSUD Karawang',
                'start_date' => '2012-01-01',
                'status' => 'resolved',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-010',
                'title' => 'Pendampingan sengketa/tagihan listrik (PLN)',
                'summary' => 'Advokasi anggota terkait layanan listrik; memperkuat legitimasi organisasi di basis.',
                'description' => 'Tipe: advokasi_pelayanan_publik. Rentang: 2012–2013. Sumber: dokumen profil.'.$foot,
                'location_text' => 'Basis anggota, Kab. Karawang',
                'start_date' => '2012-01-01',
                'status' => 'reported',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-011',
                'title' => 'Sengketa lahan Teluk Jambe vs PT SAMP / Agung Podomoro Land',
                'summary' => 'Konflik skala besar (±350 ha diklaim); gugatan perdata hingga MA; SEPETAK sebagai wadah pengorganisasian sejak 2007.',
                'description' => 'Tipe: sengketa_lahan_korporasi. Lokasi: Desa Wanakerta, Wanasari, Margamulya, Kec. Telukjambe Barat. Akar konflik jauh sebelum 2007; keterlibatan SEPETAK terdokumentasi pasca deklarasi. Rujukan: Wikipedia id — Serikat Petani Karawang; TanahKita; Gresnews; Sindo 22 Feb 2016 (DPRD & fasilitas PT).'."\n\nPeristiwa terkait dicatat juga sebagai kasus terpisah ORG-ADV-012 s.d. ORG-ADV-015 untuk jejak administrasi; boleh digabung menjadi pembaruan satu kasus di Filament.".$foot,
                'location_text' => 'Wanakerta, Wanasari, Margamulya, Kec. Telukjambe Barat',
                'start_date' => '1990-01-01',
                'status' => 'legal_process',
                'priority' => 'urgent',
            ],
            [
                'case_code' => 'ORG-ADV-012',
                'title' => 'Aksi Mahkamah Agung — kekecewaan putusan sengketa Teluk Jambe (20 Februari 2013)',
                'summary' => 'Massa memaksa masuk kompleks MA dan mengancam menduduki gedung.',
                'description' => 'Tipe: aksi_protes_hukum. Terkait kasus Teluk Jambe (ORG-ADV-011). Sumber: Wikipedia id — Serikat Petani Karawang.'.$foot,
                'location_text' => 'Jakarta — kompleks Mahkamah Agung',
                'start_date' => '2013-02-20',
                'status' => 'closed',
                'priority' => 'medium',
            ],
            [
                'case_code' => 'ORG-ADV-013',
                'title' => 'Penutupan akses Tol Jakarta–Cikampek (11 Juli 2013)',
                'summary' => 'Aksi massa terkait putusan MA dan klaim tanah Teluk Jambe / PT SAMP.',
                'description' => 'Tipe: aksi_jalan_tol. Terkait ORG-ADV-011. Sumber: Wikipedia id — Serikat Petani Karawang; liputan pers tambahan (km 42–44, bentrok/water cannon; koordinator lapangan Moris Moy Purba di SINDOnews).'."\n".'https://www.antaranews.com/berita/384759/ratusan-warga-blokir-jalan-tol-jakarta-cikampek'."\n".'https://money.kompas.com/read/2013/07/11/1322142/Pengguna.Jalan.Dirugikan.Akibat.Demo.di.Tol.Cikampek'."\n".'https://news.okezone.com/read/2013/07/11/527/835379/ini-alasan-warga-petani-blokir-jalan-tol-cikampek'."\n".'https://daerah.sindonews.com/berita/760166/31/hatta-rajasa-pelaku-pemblokiran-jalan-tol-harus-ditindak'.$foot,
                'location_text' => 'Jalan tol Jakarta–Cikampek',
                'start_date' => '2013-07-11',
                'status' => 'closed',
                'priority' => 'medium',
            ],
            [
                'case_code' => 'ORG-ADV-014',
                'title' => 'Klarifikasi publik — isu dukungan penambangan pasir laut (21 Maret 2013)',
                'summary' => 'Organisasi menyangkal dukungan ke tambang pasir setelah pertemuan formal dengan instansi terkait.',
                'description' => 'Tipe: klarifikasi_publik. Sumber: Wikipedia id — Serikat Petani Karawang.'.$foot,
                'location_text' => 'Pesisir utara Karawang / Cibuaya',
                'start_date' => '2013-03-21',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-015',
                'title' => 'Tekanan politik DPRD terkait fasilitas PT SAMP di Telukjambe (liputan 2016)',
                'summary' => 'DPRD Komisi A; isu IMB/HGB/AMDAL; rekomendasi pembongkaran kantor pemasaran/reklame.',
                'description' => 'Tipe: advokasi_kebijakan. Terkait ORG-ADV-011. Sumber: Sindo Karawang, 22 Februari 2016.'.$foot,
                'location_text' => 'Telukjambe Barat, Kab. Karawang',
                'start_date' => '2012-03-15',
                'status' => 'mediation',
                'priority' => 'high',
            ],
            [
                'case_code' => 'ORG-ADV-016',
                'title' => 'Penolakan revisi Perda RTRW Karawang (kawasan Cilamaya Wetan)',
                'summary' => 'Intervensi atas perubahan status kawasan yang mengancam lahan pertanian anggota.',
                'description' => 'Tipe: advokasi_kebijakan. Rentang: 2010-an. Sumber: SejarahPageContent; dokumen profil.'.$foot,
                'location_text' => 'Kec. Cilamaya Wetan & wilayah terdampak, Kab. Karawang',
                'start_date' => '2010-01-01',
                'status' => 'legal_process',
                'priority' => 'high',
            ],
            [
                'case_code' => 'ORG-ADV-017',
                'title' => 'Kongres III — visi agraria & pemetaan wilayah rawan (25–26 April 2016)',
                'summary' => 'Rumusan “Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian”; lima kategori wilayah rawan konflik.',
                'description' => 'Tipe: tonggak_program. Sumber: narasi resmi organisasi; SejarahPageContent.'.$foot,
                'location_text' => 'Kabupaten Karawang',
                'start_date' => '2016-04-25',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-018',
                'title' => 'Percobaan pertanian kolektif basis Pakisjaya (termasuk Telukjaya)',
                'summary' => 'Model sewa lahan absentee dan produksi kolektif; dihentikan setelah masalah kontrol internal menurut dokumen profil.',
                'description' => 'Tipe: program_basis (bukan sengketa lahan terbuka). Sumber: docs/[PROFIL] Sejarah Serikat Petani Karawang.docx.md.'.$foot,
                'location_text' => 'Kec. Pakisjaya (Desa Telukjaya dan desa lain), Kab. Karawang',
                'start_date' => '2010-01-01',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-019',
                'title' => 'Kongres IV — Serikat Pekerja Tani Karawang & AD/ART 2020–2023',
                'summary' => 'Perubahan nama resmi, pembaruan tata tertib tertulis, pengesahan 1 November 2020.',
                'description' => 'Tipe: tonggak_organisasi. Tanggal kongres: 31 Oktober–1 November 2020. Sumber: AD/ART; SejarahPageContent.'.$foot,
                'location_text' => 'Kabupaten Karawang',
                'start_date' => '2020-10-31',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-020',
                'title' => 'Unjuk rasa Pemda Karawang — enam tuntutan strategis (6 Oktober 2022)',
                'summary' => 'Koalisi LBH Cakra Indonesia, KPBI, mahasiswa; tuntutan LPRA, upah, pendidikan, BBM, BPN, kehutanan; dialog DPRD.',
                'description' => 'Tipe: aksi_massa_kabupaten. Koalisi menurut pers: LBH Cakra Indonesia, KPBI, GMPI Karawang, mahasiswa; dialog DPRD. Sumber: iNews Karawang.'."\n".'https://karawang.inews.id/read/183914/serikat-pekerja-tani-karawang-berunjuk-rasa-hari-ini-berikut-6-tuntutannya'."\n".'https://karawang.inews.id/read/184068/ratusan-petani-unjuk-rasa-di-pemda-karawang-tuntut-sertifikasi-tanah-hingga-turunkan-harga-bbm'."\n".'https://karawang.inews.id/read/184232/dprd-karawang-dukung-unjuk-rasa-sepetak-janji-perjuangkan-hak-petani'.$foot,
                'location_text' => 'Kantor Pemda Kabupaten Karawang',
                'start_date' => '2022-10-06',
                'status' => 'reported',
                'priority' => 'medium',
            ],
            [
                'case_code' => 'ORG-ADV-021',
                'title' => 'Pendaftaran hak atas tanah — 88 bidang di 13 desa (klaim vs kawasan hutan)',
                'summary' => 'Langkah administratif ke BPN; narasi klaim hutan tanpa dokumen utuh; didampingi LBH Arya Mandalika.',
                'description' => 'Tipe: administrasi_tanah. Tanggal pengajuan: 21 Juli 2023. Sumber: Elangmasnews; docs/ORGANIZATION_RESEARCH.md.'."\n".'https://elangmasnews.com/peristiwa/kedatangan-serikat-pekerja-tani-karawang-sepetak-ke-kantor-badan-pertanahan-negara-bpn-karawang-dengan-ribuan-petani/'.$foot,
                'location_text' => '13 desa Kab. Karawang (rincian desa — lengkapi di admin)',
                'start_date' => '2023-07-21',
                'status' => 'under_review',
                'priority' => 'high',
            ],
            [
                'case_code' => 'ORG-ADV-022',
                'title' => 'Aksi massa ke Kantor BPN Karawang (27 Juli 2023)',
                'summary' => 'Ribuan petani; orasi LBH; tuntutan penyelesaian konflik agraria terkait klaim Perhutani/kawasan hutan.',
                'description' => 'Tipe: aksi_massa_kabupaten. Terkait ORG-ADV-021. Sumber: Elangmasnews (tautan sama seperti ORG-ADV-021).'.$foot,
                'location_text' => 'Kantor BPN Karawang',
                'start_date' => '2023-07-27',
                'status' => 'reported',
                'priority' => 'high',
            ],
            [
                'case_code' => 'ORG-ADV-023',
                'title' => 'Pernyataan sikap — tolak kriminalisasi pasca aksi BPN (1 Agustus 2023)',
                'summary' => 'Respons organisasi dan koalisi terhadap tekanan hukum/aparat pasca 27 Juli.',
                'description' => 'Tipe: pernyataan_sikap. Sumber: Change.org (kampanye pernyataan sikap).'."\n".'https://www.change.org/p/pernyataan-sikap-atas-kriminalisasi-petani-yang-dilakuka-pemerintah-kabupaten-karawang'.$foot,
                'location_text' => 'Kabupaten Karawang',
                'start_date' => '2023-08-01',
                'status' => 'reported',
                'priority' => 'high',
            ],
            [
                'case_code' => 'ORG-ADV-024',
                'title' => 'Hari Tani — mobilisasi ke Jakarta bersama KPA (139 organisasi, September 2025)',
                'summary' => 'Tuntutan 24 agenda perbaikan struktural agraria; SEPETAK bagian delegasi Jawa Barat.',
                'description' => 'Tipe: aksi_nasional. Delegasi audiensi DPR menyebut SEPETAK di antara ormas (kumparan). Sumber: Pejabatpublik.com; Kompas.com; kumparan.'."\n".'https://pejabatpublik.com/2025/09/22/profil-dewi-kartika-sekjen-konsorsium-pembaruan-agraria/'."\n".'https://www.kompas.com/jawa-timur/read/2025/09/24/061500488/petani-kpa-gelar-demo-hari-tani-nasional-tuntut-reforma-agraria-yang'."\n".'https://kumparan.com/kumparannews/perwakilan-buruh-tani-diterima-dasco-hingga-nusron-bawa-9-tuntutan-25uq4DGgVjC'.$foot,
                'location_text' => 'Jakarta (DPR); basis Jabar/Banten',
                'start_date' => '2025-09-24',
                'status' => 'reported',
                'priority' => 'medium',
            ],
            [
                'case_code' => 'ORG-ADV-025',
                'title' => 'Aksi “Karawang Poek” — evaluasi Perbup 19/2025 & reforma agraria (12 November 2025)',
                'summary' => 'Koalisi KBPP+ (ribuan massa, termasuk SEPETAK); tujuh tuntutan; audiensi Pemda.',
                'description' => 'Tipe: aksi_koalisi_kabupaten. Sumber: Koran Perdjoeangan.'."\n".'https://www.koranperdjoeangan.com/ribuan-massa-karawang-poek-gruduk-pemda-buruh-petani-dan-mahasiswa-satu-suara-desak-cabut-perbup-19-2025/'.$foot,
                'location_text' => 'Kantor Pemda Kabupaten Karawang',
                'start_date' => '2025-11-12',
                'status' => 'mediation',
                'priority' => 'high',
            ],
            [
                'case_code' => 'ORG-ADV-026',
                'title' => 'HUT ke-18 SEPETAK — deklarasi tanah kolektif & penanaman mangrove Sedari (10 Desember 2025)',
                'summary' => 'Acara peringatan HAM; undangan DPTK; undangan Kepala ATR/BPN Karawang.',
                'description' => 'Tipe: peringatan_organisasi. Sumber: Suarana.com.'."\n".'https://www.suarana.com/2025/12/hut-ke-18-sepetak-kukuhkan-tanah.html'.$foot,
                'location_text' => 'Desa Sedari, Kec. Cibuaya, Kab. Karawang',
                'start_date' => '2025-12-10',
                'status' => 'closed',
                'priority' => 'low',
            ],
            [
                'case_code' => 'ORG-ADV-027',
                'title' => 'Demonstrasi SEPETAK ke Mabes Polri — desakan usut perampasan tanah (30 Juni 2014)',
                'summary' => 'Liputan galeri pers: ratusan petani SEPETAK berunjuk rasa di Mabes Polri menuntut penyidikan perampasan tanah di Karawang.',
                'description' => 'Tipe: aksi_nasional. Sumber primer arsip: Media Indonesia (galeri foto).'."\n".'https://mediaindonesia.com/galleries/detail_galleries/305-demo-petani-karawang'.$foot,
                'location_text' => 'Mabes Polri, Jakarta Selatan',
                'start_date' => '2014-06-30',
                'status' => 'closed',
                'priority' => 'medium',
            ],
            [
                'case_code' => 'ORG-ADV-028',
                'title' => 'Solidaritas — sambutan long march petani Blitar–Jakarta di Karawang (28 Januari 2013)',
                'summary' => 'Narasi arsip: ratusan petani Blitar menuju Istana disambut SEPETAK dan aktivis Karawang di bundaran Mall Karawang.',
                'description' => 'Tipe: solidaritas_antarwilayah. Sumber: blog arsip (mengutip karawangnews) — **verifikasi sekretariat** sebelum dipakai sebagai kutipan resmi.'."\n".'https://maklumatrakyat.blogspot.com/2013/01/longmarch-petani-blitar-jakarta-tiba-di.html'.$foot,
                'location_text' => 'Bundaran Mall Karawang, Kab. Karawang',
                'start_date' => '2013-01-28',
                'status' => 'closed',
                'priority' => 'low',
            ],
        ];
    }
}
