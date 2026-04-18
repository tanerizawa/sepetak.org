<?php

namespace Database\Seeders;

/**
 * Halaman /halaman/wilayah-kerja — pemetaan konflik (narasi organisasi & kurasi lapangan;
 * bukan pengganti dokumen resmi AD/ART).
 */
final class WilayahKerjaPageContent
{
    public static function body(): string
    {
        return <<<'HTML'
<h2>Wilayah kerja dan pemetaan konflik agraria</h2>
<p>Kabupaten Karawang secara administratif mencakup puluhan kecamatan dan ratusan desa/kelurahan di dataran rendah yang bersamaan berfungsi sebagai <strong>lumbung pangan</strong> (khususnya padi) dan sebagai <strong>kawasan industrialisasi</strong> yang berkembang sejak akhir abad ke-20. Tekanan agraria muncul dalam bentuk konversi lahan, klaim status tanah dan kawasan hutan, infrastruktur (termasuk koridor tol), relasi sewa-menyewa dan bagi hasil, serta dominasi pemilik tanah yang tidak tinggal di desa (<em>absentee</em>).</p>
<p>Pada skala nasional, laporan Konsorsium Pembaruan Agraria (<strong>KPA</strong>) tahun 2011 pernah mencatat <strong>163 konflik agraria</strong> di Indonesia, dengan perincian sektor (antara lain perkebunan, kehutanan, infrastruktur, tambang, pesisir) dan dampak luasan serta rumah tangga terdampak. Karawang berada dalam pusaran dinamika serupa dan menjadi salah satu basis pengorganisasian SEPETAK sejak <strong>Kongres I (2007)</strong>.</p>
<p>Setelah <strong>Kongres II (10–11 Desember 2010)</strong> dan konsolidasi strategi pada <strong>Kongres III (25–26 April 2016)</strong>, SEPETAK menetapkan <strong>lima kategori wilayah rawan konflik agraria</strong> di Karawang sebagai peta prioritas pengorganisasian. Kategori ini dipakai bersama dokumentasi kasus di <a href="/kasus-agraria">daftar kasus agraria</a> dan data pendampingan di sekretariat.</p>

<h3>1. Wilayah masyarakat desa hutan</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: Perum Perhutani, investor swasta, korporasi berbasis lahan, aparat pemerintah, serta — pada episode tertentu — lembaga pendamping pihak ketiga yang berposisi berbeda dengan garis perjuangan petani.</li>
<li><strong>Jenis konflik</strong>: klaim status kawasan hutan versus garapan dan penguasaan tanah oleh rumah tangga tani; sengketa tapal batas; administrasi <strong>IP4T</strong> (inventarisasi penguasaan, pemilikan, penggunaan, dan pemanfaatan tanah); kriminalisasi; serta persoalan <strong>Pengelolaan Hutan Bersama Masyarakat (PHBM)</strong> dan dokumen desa.</li>
<li><strong>Contoh koridor dokumentasi</strong>: dinamika hutan di <strong>Medalsari</strong> (narasi intimidasi dan kriminalisasi pengurus); pendaftaran administratif sampel <strong>88 bidang di 13 desa</strong> ke BPN yang berbenturan dengan klaim kawasan hutan; perkara kayu hutan di <strong>Parung Mulya, Ciampel</strong> sebagai preseden kriminalisasi.</li>
</ul>

<h3>2. Wilayah eks Tegalwaru landen (Karawang Selatan)</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: swasta, operator zona industri, Perhutani, dan pemerintah daerah dalam tata ruang.</li>
<li><strong>Jenis konflik</strong>: perampasan tanah, sengketa tapal batas, serta tekanan industri ekstraktif (misalnya narasi tambang batu andesit) di dataran selatan.</li>
<li><strong>Catatan pengorganisasian</strong>: koridor ini sering menjadi pintu masuk konflik multipihak (hutan, tambang, rencana tata ruang) yang membutuhkan pendampingan hukum dan kampanye terkoordinasi.</li>
</ul>

<h3>3. Wilayah sekitar zona industri, perumahan, dan infrastruktur</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: pengembang kawasan industri dan perumahan, korporasi properti, BUMN/BUMD terkait infrastruktur, serta instansi pertanahan dalam putusan dan eksekusi.</li>
<li><strong>Jenis konflik</strong>: konversi sawah produktif dan ruang hidup pedesaan menjadi kawasan industri, perumahan, atau jalan tol; sengketa gugatan perdata dan penegakan putusan pengadilan skala luas.</li>
<li><strong>Contoh koridor dokumentasi</strong>: sengketa skala besar di <strong>Telukjambe Barat</strong> melibatkan <strong>Margamulya, Wanasari, dan Wanakerta</strong>, dengan riwayat rantai klaim korporasi dan dampak aksi massa serta eksekusi lahan di medan publik.</li>
</ul>

<h3>4. Wilayah pangan (sabuk sawah Karawang)</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: tuan tanah <em>absentee</em> dan tuan tanah lokal, operator irigasi, serta kebijakan tampungan air waduk yang memengaruhi musim tanam.</li>
<li><strong>Jenis konflik</strong>: relasi penyakapan (<em>sharecropping tenancy</em>) yang eksploitatif; ketidakpastian administrasi tanah garapan; serta konflik <strong>hak air irigasi</strong> ketika kebijakan operator waduk berbenturan dengan siklus tanam padi.</li>
<li><strong>Contoh koridor dokumentasi</strong>: <strong>Pakisjaya</strong> dan sekitarnya (narasi protes terhadap pembatasan tanam akibat kondisi tampungan <strong>Jatiluhur</strong> serta upaya menghidupkan kembali peran komisi irigasi di tingkat daerah).</li>
</ul>

<h3>5. Wilayah pesisir</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: Perhutani, investor tambak dan pariwisata, perusahaan tambang pasir laut, serta pemerintah dalam penetapan kawasan pesisir dan revitalisasi.</li>
<li><strong>Jenis konflik</strong>: klaim kawasan hutan di pesisir versus tambak rakyat dan ruang hidup nelayan; penolakan revitalisasi tambak yang dinilai merugikan; serta pencemaran dan tekanan lingkungan hidup.</li>
<li><strong>Contoh koridor dokumentasi</strong>: solidaritas pesisir di <strong>Sedari, Cibuaya</strong> bersama koalisi perikanan (narasi aksi <strong>Hari Nelayan</strong> dan kampanye &quot;ruang hidup bukan kawasan hutan&quot;); narasi historis penolakan tambang pasir laut di utara Karawang.</li>
</ul>

<h3>Strategi pengorganisasian sesuai peta</h3>
<p>Pemetaan di atas memandu prioritas pembentukan <strong>Pokja</strong> dan <strong>DPTD</strong>, jenis advokasi (mediasi, administrasi tanah, litigasi, aksi massa), serta pilihan mitra sipil. Basis awal kongres meliputi lima desa di <strong>Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya</strong>, yang secara spasial menjembatani sabuk pangan dan tekanan pesisir–industri.</p>
<p>Konflik di Karawang bersifat <strong>multipihak</strong> (hutan, korporasi properti, pertanahan, air, pesisir); oleh karena itu peta ini dibaca bersama <a href="/halaman/sejarah">Sejarah SEPETAK</a>, <a href="/halaman/visi-misi">Visi dan Misi</a>, dan arsip perkara, bukan sebagai daftar statis.</p>
<p><em>Peta ini terus diperbarui.</em> Setiap DPTD baru wajib melakukan pemetaan konflik agraria di desanya sebagai dasar kerja organisasi dan pelaporan ke DPTK.</p>
HTML;
    }
}
