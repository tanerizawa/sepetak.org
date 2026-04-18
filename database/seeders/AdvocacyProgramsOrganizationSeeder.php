<?php

namespace Database\Seeders;

use App\Models\AdvocacyAction;
use App\Models\AdvocacyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/**
 * Mengisi program advokasi organisasi dari kurasi docs/ADVOCACY_PROGRAMS_RESEARCH.md.
 * Idempotent: updateOrCreate berdasarkan program_code ORG-PRG-001 … ORG-PRG-018.
 */
class AdvocacyProgramsOrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->where('email', 'admin@sepetak.org')->value('id');

        foreach ($this->programRows() as $row) {
            AdvocacyProgram::query()->updateOrCreate(
                ['program_code' => $row['program_code']],
                [
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'status' => $row['status'],
                    'start_date' => $row['start_date'],
                    'end_date' => $row['end_date'] ?? null,
                    'location_text' => $row['location_text'],
                    'lead_user_id' => $row['assign_lead'] ? $userId : null,
                ]
            );
        }

        $this->seedActions($userId);

        Cache::forget('homepage.stats');
        Cache::forget('homepage.stats.v2');

        $this->command?->info('Program advokasi organisasi: '.count($this->programRows()).' entri (program_code ORG-PRG-*).');
    }

    private function seedActions(?int $userId): void
    {
        $link = fn (string $code): ?AdvocacyProgram => AdvocacyProgram::query()->where('program_code', $code)->first();

        $mk = static function (AdvocacyProgram $p, string $date, string $type, string $notes) use ($userId): void {
            AdvocacyAction::query()->firstOrCreate(
                [
                    'advocacy_program_id' => $p->id,
                    'action_date' => $date,
                    'action_type' => $type,
                ],
                [
                    'notes' => $notes,
                    'created_by' => $userId,
                ]
            );
        };

        if ($p = $link('ORG-PRG-001')) {
            $mk($p, '2010-12-10', 'meeting', 'Kongres II: penetapan lima pilar TANI MOTEKAR (tanah, infrastruktur, modal, teknologi, akses pasar).');
        }
        if ($p = $link('ORG-PRG-006')) {
            $mk($p, '2023-07-21', 'legal', 'Pengajuan administratif 88 bidang tanah di 13 desa ke BPN (kait modul kasus ORG-ADV-021).');
            $mk($p, '2023-07-27', 'campaign', 'Aksi massa ribuan petani ke Kantor BPN Karawang bersama LBH Arya Mandalika (ORG-ADV-022).');
        }
        if ($p = $link('ORG-PRG-010')) {
            $mk($p, '2025-09-24', 'campaign', 'Partisipasi Hari Tani / mobilisasi ke DPR bersama KPA dan ormas Jabar–Banten (ORG-ADV-024).');
        }
        if ($p = $link('ORG-PRG-011')) {
            $mk($p, '2025-12-10', 'field_visit', 'HUT ke-18: deklarasi tanah kolektif & penanaman mangrove di Sedari, Cibuaya (ORG-ADV-026).');
        }
        if ($p = $link('ORG-PRG-002')) {
            $mk($p, '2016-04-25', 'meeting', 'Kongres III: rumusan visi “Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian” & pemetaan wilayah rawan.');
        }
        if ($p = $link('ORG-PRG-013')) {
            $mk($p, '2021-06-01', 'meeting', 'Respons kebijakan PJT II terhadap tanam padi di Pakisjaya; koordinasi dengan pemangku kebijakan daerah (ORG-ADV-033).');
        }
        if ($p = $link('ORG-PRG-015')) {
            $mk($p, '2016-04-01', 'legal', 'Pengaduan ke KPK, Mabes Polri, dan Komnas HAM bersama massa tiga desa (ORG-ADV-031).');
            $mk($p, '2022-10-15', 'legal', 'Pendampingan administrasi tanah bersama LBH Cakra di BPN Karawang (ORG-ADV-034).');
        }
        if ($p = $link('ORG-PRG-016')) {
            $mk($p, '2025-10-01', 'meeting', 'Rakor Kementerian ATR/BPN — narasi reforma agraria Karawang (ORG-ADV-037).');
        }
    }

    /**
     * @return list<array{
     *     program_code: string,
     *     title: string,
     *     description: string,
     *     status: string,
     *     start_date: string,
     *     end_date?: string,
     *     location_text: string,
     *     assign_lead: bool
     * }>
     */
    private function programRows(): array
    {
        $foot = "\n\n<p><em>Data diimpor dari kurasi repositori internal (April 2026). Sesuaikan dan verifikasi melalui panel admin setelah persetujuan sekretariat.</em></p>";

        return [
            [
                'program_code' => 'ORG-PRG-001',
                'title' => 'TANI MOTEKAR — lima pilar perjuangan (Tanah, Infrastruktur, Modal, Teknologi, Akses Pasar)',
                'description' => '<p>Payung program dari <strong>Kongres II (10–11 Desember 2010)</strong>: petani menguasai tanah, infrastruktur desa, modal produksi, teknologi tepat guna, dan akses pasar yang adil. Narasi organisasi menghubungkan lima pilar dengan <em>industrialisasi pertanian rakyat</em> di pedesaan.</p><p><strong>Departemen (usulan pemetaan):</strong> pendidikan, propaganda, perjuangan tani.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2010-12-10',
                'location_text' => 'Kabupaten Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-002',
                'title' => 'Visi Kongres III — Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian',
                'description' => '<p>Rumusan strategis <strong>25–26 April 2016</strong>; pemetaan <strong>lima kategori wilayah rawan konflik agraria</strong> di Karawang (hutan, Tegalwaru/landen, industri, pangan, pesisir).</p><p><strong>Departemen (usulan):</strong> penelitian, perjuangan tani.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2016-04-25',
                'location_text' => 'Kabupaten Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-003',
                'title' => 'Pengorganisasian basis — DPTD, Pokja, dan konsolidasi desa',
                'description' => '<p>Struktur resmi: Kongres → Dewan Tani → DPTK → DPTD → Pokja. Contoh arsip: <strong>Pokja Dusun Cimahi, Desa Cikarang</strong> (14 Februari 2012).</p><p><strong>Departemen (usulan):</strong> perjuangan tani, internal.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2007-12-10',
                'location_text' => 'Kabupaten Karawang (basis desa)',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-004',
                'title' => 'Pendidikan kritis — sekolah tani, diskusi dusun, propaganda internal',
                'description' => '<p>Penguatan kesadaran hukum agraria dan kaderisasi melalui sekolah tani, diskusi tingkat dusun/desa, serta materi propaganda internal sesuai misi organisasi.</p><p><strong>Departemen (usulan):</strong> pendidikan, propaganda.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2007-12-10',
                'location_text' => 'Kabupaten Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-005',
                'title' => 'Advokasi sengketa agraria berkelanjutan',
                'description' => '<p>Pendampingan konflik lahan, tanah absentee, klaim kawasan hutan, dan korporasi; mediasi, gugatan, serta aksi massa yang terikat perkara (modul terkait: <strong>Kasus agraria</strong>).</p><p><strong>Departemen (usulan):</strong> perjuangan tani, pendampingan hukum.</p>'.$foot,
                'status' => 'active',
                'start_date' => '1990-01-01',
                'location_text' => 'Kabupaten Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-006',
                'title' => 'Administrasi hak atas tanah & pendampingan BPN (13 desa, 88 bidang)',
                'description' => '<p>Jalur pendaftaran/pemutakhiran data tanah anggota terkait narasi klaim kawasan hutan; pendampingan <strong>LBH Arya Mandalika</strong>. Peristiwa kunci: 21 &amp; 27 Juli 2023.</p><p><strong>Departemen (usulan):</strong> perjuangan tani.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2023-07-21',
                'location_text' => '13 desa Kab. Karawang (rincian di admin)',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-007',
                'title' => 'Intervensi kebijakan tata ruang & Perda RTRW',
                'description' => '<p>Perlawanan terhadap alih fungsi lahan pertanian dan rencana perkotaan yang merugikan basis; termasuk isu <strong>Cilamaya Wetan</strong> dan revisi Perda.</p><p><strong>Departemen (usulan):</strong> penelitian, perjuangan tani.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2010-01-01',
                'location_text' => 'Kabupaten Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-008',
                'title' => 'Advokasi pelayanan publik anggota (arsip PLN, Jamkesda/Jamkesmas, RSUD)',
                'description' => '<p>Pendampingan anggota pada layanan listrik, jaminan kesehatan, dan relasi dengan RSUD Karawang — memperkuat legitimasi organisasi di basis (arsip perkiraan 2012–2013).</p><p><strong>Departemen (usulan):</strong> internal, pendidikan.</p>'.$foot,
                'status' => 'completed',
                'start_date' => '2012-01-01',
                'end_date' => '2013-12-31',
                'location_text' => 'Kabupaten Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-009',
                'title' => 'Pertanian kolektif basis (arsip Pakisjaya & sekitarnya)',
                'description' => '<p>Model sewa lahan absentee, input kolektif, dan surplus untuk akumulasi tanah menurut narasi internal; program dihentikan setelah masalah kontrol.</p><p><strong>Departemen (usulan):</strong> dana &amp; usaha.</p>'.$foot,
                'status' => 'completed',
                'start_date' => '2010-01-01',
                'end_date' => '2015-12-31',
                'location_text' => 'Kec. Pakisjaya, Kab. Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-010',
                'title' => 'Koalisi agraria & partisipasi KPA / serikat wilayah',
                'description' => '<p>Kerja sama dengan <strong>Konsorsium Pembaruan Agraria (KPA)</strong>, Serikat Petani Pasundan, Majalengka, Banten, Cianjur, dan jaringan tani lain untuk kampanye reforma agraria skala daerah &amp; nasional.</p><p><strong>Departemen (usulan):</strong> perjuangan tani, propaganda.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2007-12-10',
                'location_text' => 'Jawa Barat & nasional',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-011',
                'title' => 'Pesisir & ekologi — tambang pasir, mangrove, ketahanan lingkungan',
                'description' => '<p>Advokasi penolakan tambang pasir laut di <strong>Tanjung Pakis</strong> (narasi 2008–2009); serta kegiatan pesisir seperti penanaman mangrove di rangkaian HUT organisasi di <strong>Sedari, Cibuaya</strong> (2025).</p><p><strong>Departemen (usulan):</strong> perjuangan tani, penelitian.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2008-01-01',
                'location_text' => 'Pesisir utara & Sedari, Kab. Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-012',
                'title' => 'Penolakan tambang batu andesit Tegalwaru',
                'description' => '<p>Tekanan industri ekstraktif di dataran selatan Karawang; status perkembangan lapangan perlu verifikasi sekretariat.</p><p><strong>Departemen (usulan):</strong> perjuangan tani.</p>'.$foot,
                'status' => 'paused',
                'start_date' => '2009-01-01',
                'end_date' => '2010-12-31',
                'location_text' => 'Tegalwaru, Karawang Selatan',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-013',
                'title' => 'Advokasi hak air irigasi dan tata kelola waduk (Pakisjaya & sekitarnya)',
                'description' => '<p>Pendampingan rumah tangga tani terkait alokasi air irigasi dari sistem Jatiluhur; menanggapi kebijakan operator waduk (<strong>PJT II</strong>) yang berdampak pada musim tanam, serta mendorong peran <strong>Komisi Irigasi (Komir)</strong> dan koordinasi perencanaan di tingkat daerah.</p><p><strong>Departemen (usulan):</strong> perjuangan tani, penelitian.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2021-01-01',
                'location_text' => 'Kec. Pakisjaya & basis irigasi Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-014',
                'title' => 'Advokasi lingkungan hidup dan pencemaran industri',
                'description' => '<p>Kampanye dan pendampingan terkait kualitas perairan (termasuk sungai utama di wilayah kerja), limbah berbahaya dan beracun, serta pelestarian lingkungan pedesaan sebagai bagian dari ketahanan basis.</p><p><strong>Departemen (usulan):</strong> propaganda, penelitian, perjuangan tani.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2010-01-01',
                'location_text' => 'Kabupaten Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-015',
                'title' => 'Litigasi dan bantuan hukum bersama lembaga bantuan hukum',
                'description' => '<p>Pendampingan perkara dan administrasi hukum bersama mitra seperti <strong>LBH Arya Mandalika</strong>, <strong>LBH Cakra</strong>, dan <strong>LBH Street Lawyer</strong>, termasuk gugatan perdata massa tani, pengaduan ke lembaga antikorupsi dan HAM, serta administrasi perkara kriminalisasi.</p><p><strong>Departemen (usulan):</strong> perjuangan tani (koordinasi eksternal).</p>'.$foot,
                'status' => 'active',
                'start_date' => '2013-01-01',
                'location_text' => 'Karawang & forum hukum nasional',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-016',
                'title' => 'Reforma agraria: IP4T, LPRA, One Map Policy, dan badan pelaksana',
                'description' => '<p>Mendorong <strong>inventarisasi penguasaan, pemilikan, penggunaan, dan pemanfaatan tanah (IP4T)</strong> atas tanah petani di kawasan berklaim hutan; mengadvokasi penetapan <strong>lokasi prioritas reforma agraria (LPRA)</strong>; serta mendukung kebijakan <strong>One Map Policy</strong> dan pembentukan <strong>badan pelaksana reforma agraria</strong> sejalan dengan notulen rapat koordinasi tingkat kabupaten dan nasional.</p><p><strong>Departemen (usulan):</strong> penelitian, perjuangan tani.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2015-01-01',
                'location_text' => 'Kabupaten Karawang',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-017',
                'title' => 'Jaringan sipil, HAM, dan solidaritas lintas sektor',
                'description' => '<p>Memperluas kerja sama advokasi dengan mitra seperti <strong>KontraS</strong>, <strong>YLBHI</strong>, <strong>Bina Desa</strong>, <strong>WALHI</strong>, <strong>ELSAM</strong>, <strong>IHCS</strong>, <strong>SERBUK</strong>, <strong>SETAKAR</strong>, <strong>AGRA</strong>, <strong>FPBI</strong>, <strong>GMNI</strong>, serta berbagai organisasi mahasiswa — tanpa menyamakan seluruh posisi mitra dengan kebijakan resmi harian DPTK.</p><p><strong>Departemen (usulan):</strong> perjuangan tani, propaganda.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2007-12-10',
                'location_text' => 'Nasional & Jawa Barat',
                'assign_lead' => false,
            ],
            [
                'program_code' => 'ORG-PRG-018',
                'title' => 'Komunikasi publik, propaganda digital, dan dokumentasi',
                'description' => '<p>Penguatan kanal komunikasi resmi (situs <strong>sepetak.or.id</strong>, media sosial, poster, dan materi grafis kritis) untuk mendiseminasikan analisis agraria, memobilisasi solidaritas, dan mendokumentasikan aksi lapangan.</p><p><strong>Departemen (usulan):</strong> propaganda, pendidikan.</p>'.$foot,
                'status' => 'active',
                'start_date' => '2012-01-01',
                'location_text' => 'Kabupaten Karawang (jaringan daring)',
                'assign_lead' => false,
            ],
        ];
    }
}
