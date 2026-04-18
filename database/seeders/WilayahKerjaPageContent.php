<?php

namespace Database\Seeders;

/** Halaman /halaman/wilayah-kerja — pemetaan konflik (narasi organisasi, bukan kutipan AD). */
final class WilayahKerjaPageContent
{
    public static function body(): string
    {
        return <<<'HTML'
<h2>Wilayah kerja dan pemetaan konflik agraria</h2>
<p>Karawang adalah salah satu daerah paling padat persoalan agraria di Jawa Barat: wilayah dataran rendah yang sejak 1989 terus diubah menjadi zona industri, perumahan, dan jalan tol, sementara di saat yang sama tetap menjadi sentra padi nasional. Pada skala nasional, laporan Konsorsium Pembaruan Agraria (KPA) tahun 2011 mencatat <strong>163 konflik agraria</strong> di Indonesia — 60% di sektor perkebunan, 22% kehutanan, 13% infrastruktur, 4% tambang, 1% pesisir — dengan korban mencapai <strong>22 jiwa, 69.975 KK, dan 472.048 hektare lahan</strong>; Karawang berada dalam pusaran dinamika serupa.</p>
<p>Setelah <strong>Kongres II (10–11 Desember 2010)</strong> dan konsolidasi program pada <strong>Kongres III (25–26 April 2016)</strong>, SEPETAK menetapkan <strong>lima kategori wilayah rawan konflik agraria</strong> di Karawang sebagai peta prioritas pengorganisasian:</p>

<h3>1. Wilayah masyarakat desa hutan</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: Perum Perhutani, perusahaan tambang, swasta, militer, dan pemerintah.</li>
<li><strong>Jenis konflik</strong>: perampasan tanah, sengketa tapal batas, dan klaim kawasan wisata.</li>
</ul>

<h3>2. Wilayah eks Tegalwaru landen (Karawang Selatan)</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: swasta, operator zona industri, Perhutani, pemerintah.</li>
<li><strong>Jenis konflik</strong>: perampasan tanah dan sengketa tapal batas.</li>
</ul>

<h3>3. Wilayah sekitar zona industri</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: pengembang zona industri, perumahan, pemerintah.</li>
<li><strong>Jenis konflik</strong>: perampasan tanah — konversi sawah produktif menjadi kawasan industri dan perumahan.</li>
</ul>

<h3>4. Wilayah pangan (sabuk sawah Karawang)</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: tuan tanah absentee dan tuan tanah lokal.</li>
<li><strong>Jenis konflik</strong>: kepemilikan tanah absentee yang menjerat petani penggarap ke dalam sistem penyakapan (<em>sharecropping tenancy</em>) yang eksploitatif.</li>
</ul>

<h3>5. Wilayah pesisir</h3>
<ul>
<li><strong>Pihak yang terlibat</strong>: Perhutani, swasta, perusahaan tambang pasir laut, pemerintah.</li>
<li><strong>Jenis konflik</strong>: kepemilikan absentee dan perampasan ruang hidup nelayan serta pedagang pariwisata pantai.</li>
</ul>

<h3>Strategi pengorganisasian sesuai peta</h3>
<p>Pemetaan di atas menjadi pedoman SEPETAK dalam menentukan di desa mana Pokja perlu dibangun lebih dahulu, advokasi apa yang dijalankan sesuai karakter konflik, dan siapa mitra yang relevan. Lima desa basis awal di <strong>Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya</strong> masuk dalam wilayah pangan-pesisir; sementara wilayah selatan (Tegalwaru, Pangkalan) masuk kategori hutan &amp; ekstraktif.</p>
<p><em>Peta ini terus diperbarui</em> — setiap DPTD baru wajib melakukan pemetaan konflik agraria di desanya sebagai dasar kerja organisasi.</p>
HTML;
    }
}
