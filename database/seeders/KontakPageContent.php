<?php

namespace Database\Seeders;

/** Teks detail halaman <code>/kontak</code> (blok email/telepon/alamat diisi dari pengaturan situs di view). */
final class KontakPageContent
{
    public static function body(): string
    {
        return <<<'HTML'
<h2>Hubungi kami</h2>
<p>Untuk informasi lebih lanjut, permohonan pendampingan, kolaborasi lintas organisasi, atau kebutuhan data publik, silakan menghubungi sekretariat SEPETAK melalui <strong>email resmi</strong>, <strong>telepon atau WhatsApp</strong>, atau <strong>alamat sekretariat</strong> sebagaimana ditampilkan pada kotak informasi di atas bagian ini.</p>
<p>Situs resmi organisasi: <a href="https://sepetak.org">https://sepetak.org</a></p>

<h3>Dokumen organisasi</h3>
<p>Permohonan salinan atau pertanyaan teknis mengenai <strong>Anggaran Dasar dan Anggaran Rumah Tangga</strong> (periode 2020–2023, hasil <strong>Kongres IV, 31 Oktober–1 November 2020</strong>) dapat disampaikan melalui email resmi dengan subjek yang jelas. Ringkasan publik tersedia di halaman <a href="/halaman/anggaran-dasar-dan-rumah-tangga">Anggaran Dasar dan Anggaran Rumah Tangga</a>.</p>

<h3>Informasi organisasi</h3>
<p>Kronologi kongres, visi, pemetaan konflik, dan struktur dijelaskan pada halaman profil: <a href="/halaman/tentang-kami">Tentang kami</a>, <a href="/halaman/sejarah">Sejarah</a>, <a href="/halaman/struktur-organisasi">Struktur organisasi</a>, dan <a href="/halaman/wilayah-kerja">Wilayah kerja</a>.</p>

<h3>Jalur komunikasi</h3>
<ul>
<li><strong>Pendaftaran anggota baru:</strong> gunakan formulir online di <a href="/daftar-anggota">halaman pendaftaran anggota</a>. Setelah mendaftar, calon anggota akan dihubungi oleh Departemen Internal untuk verifikasi dan penempatan Pokja.</li>
<li><strong>Permohonan pendampingan kasus agraria:</strong> kirim ringkasan kasus (lokasi, pihak terlibat, kronologi, dokumen pendukung) ke email resmi. Permohonan akan ditangani oleh <strong>Departemen Perjuangan Tani</strong> (koordinasi advokasi, kampanye, dan aksi massa).</li>
<li><strong>Liputan media dan kerja sama publikasi:</strong> ajukan permohonan resmi melalui email dengan mencantumkan institusi, nama pewarta atau perwakilan, serta topik peliputan atau kolaborasi. Permohonan akan ditangani oleh jajaran <strong>Departemen Pendidikan, Penelitian, dan/atau Propaganda</strong> sesuai arahan sekretariat.</li>
<li><strong>Solidaritas dan donasi:</strong> untuk dukungan sumber daya atau solidaritas aksi, silakan konfirmasi terlebih dahulu melalui email agar SEPETAK dapat memberikan rekening resmi dan peruntukan yang transparan (koordinasi dengan <strong>Departemen Dana dan Usaha</strong>).</li>
</ul>
<p><em>Bila nomor telepon atau WhatsApp resmi tercantum di pengaturan situs, dapat digunakan untuk keperluan mendesak; selain itu komunikasi resmi utama berjalan melalui email dan kanal yang tertera di halaman ini.</em></p>
HTML;
    }
}
