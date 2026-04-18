# StrReplace fragments for `/home/sepetak.org/database/seeders/DatabaseSeeder.php`

Total edits captured in transcript: **25**

> These fragments are the only surviving traces of edits applied by the previous agent.
> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.
> Use the `new_string` blocks below as guidance when manually rewriting the file.

## Edit #1

### old_string

```
        // 3. Assign all permissions to superadmin
        try {
            $superadmin = Role::findByName('superadmin', 'web');
            $superadmin->syncPermissions(Permission::all());
        } catch (\Exception $e) {
            $this->command->warn("Could not assign permissions: " . $e->getMessage());
        }

        // 4. Admin user
        try {
            $admin = User::updateOrCreate(
                ['email' => 'admin@sepetak.org'],
                [
                    'name'      => 'Administrator SEPETAK',
                    'password'  => Hash::make('password'),
                    'is_active' => true,
                ]
            );
            $admin->syncRoles(['superadmin']);
        } catch (\Exception $e) {
            $this->command->warn("Admin user skipped: " . $e->getMessage());
        }
```

### new_string

```
        // 3. Assign permissions per role
        $rolePermissionMap = [
            'superadmin' => Permission::all()->pluck('name')->toArray(),
            'admin'      => Permission::all()->pluck('name')->toArray(),
            'operator'   => ['manage-members', 'manage-cases', 'manage-advocacy', 'manage-events', 'manage-content'],
            'viewer'     => [],
        ];
        foreach ($rolePermissionMap as $roleName => $permNames) {
            try {
                Role::findByName($roleName, 'web')->syncPermissions($permNames);
            } catch (\Exception $e) {
                $this->command->warn("Could not sync permissions for '{$roleName}': " . $e->getMessage());
            }
        }

        // 4. Default users per role (only superadmin seeded by default; others only if missing)
        $defaultUsers = [
            ['email' => 'admin@sepetak.org',    'name' => 'Administrator SEPETAK', 'role' => 'superadmin'],
            ['email' => 'redaksi@sepetak.org',  'name' => 'Redaksi SEPETAK',       'role' => 'operator'],
            ['email' => 'publik@sepetak.org',   'name' => 'Akun Viewer SEPETAK',   'role' => 'viewer'],
        ];
        foreach ($defaultUsers as $u) {
            try {
                $user = User::updateOrCreate(
                    ['email' => $u['email']],
                    [
                        'name'      => $u['name'],
                        'password'  => Hash::make('password'),
                        'is_active' => true,
                    ]
                );
                $user->syncRoles([$u['role']]);
            } catch (\Exception $e) {
                $this->command->warn("User '{$u['email']}' skipped: " . $e->getMessage());
            }
        }
```

---

## Edit #2

### old_string

```
        // 6. Site Settings
        $settings = [
            ['key' => 'site_name',        'value' => 'SEPETAK - Serikat Petani Karawang',                                                'group' => 'general'],
            ['key' => 'site_tagline',     'value' => 'Berjuang Bersama untuk Hak-hak Petani',                                           'group' => 'general'],
            ['key' => 'site_description', 'value' => 'SEPETAK adalah organisasi petani yang memperjuangkan reforma agraria dan hak-hak petani di Karawang.', 'group' => 'general'],
            ['key' => 'contact_email',    'value' => 'info@sepetak.org',                                                                 'group' => 'contact'],
            ['key' => 'contact_phone',    'value' => '+62 xxx xxxx xxxx',                                                                'group' => 'contact'],
            ['key' => 'contact_address',  'value' => 'Karawang, Jawa Barat',                                                             'group' => 'contact'],
            ['key' => 'social_facebook',  'value' => '',                                                                                  'group' => 'social'],
            ['key' => 'social_instagram', 'value' => '',                                                                                  'group' => 'social'],
            ['key' => 'social_twitter',   'value' => '',                                                                                  'group' => 'social'],
        ];
```

### new_string

```
        // 6. Site Settings
        $settings = [
            ['key' => 'site_name',        'value' => 'SEPETAK - Serikat Pekerja Tani Karawang',                                           'group' => 'general'],
            ['key' => 'site_tagline',     'value' => 'Berjuang Bersama untuk Hak-hak Pekerja Tani dan Nelayan Karawang',                 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang) adalah organisasi massa berbasis pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat, yang memperjuangkan reforma agraria dan hak-hak pekerja tani sejak 10 Desember 2007.', 'group' => 'general'],
            ['key' => 'contact_email',    'value' => 'info@sepetak.org',                                                                  'group' => 'contact'],
            ['key' => 'contact_phone',    'value' => '+62 xxx xxxx xxxx',                                                                 'group' => 'contact'],
            ['key' => 'contact_address',  'value' => 'Kabupaten Karawang, Jawa Barat, Indonesia',                                         'group' => 'contact'],
            ['key' => 'social_facebook',  'value' => '',                                                                                   'group' => 'social'],
            ['key' => 'social_instagram', 'value' => '',                                                                                   'group' => 'social'],
            ['key' => 'social_twitter',   'value' => '',                                                                                   'group' => 'social'],
        ];
```

---

## Edit #3

### old_string

```
        $postsData = [
            [
                'title'        => 'Selamat Datang di Website SEPETAK',
                'slug'         => 'selamat-datang-di-website-sepetak',
                'excerpt'      => 'Kami dengan bangga mempersembahkan website resmi SEPETAK sebagai pusat informasi perjuangan petani Karawang.',
                'body'         => '<p>Selamat datang di website resmi SEPETAK (Serikat Petani Karawang). Melalui platform ini kami berbagi informasi, berita, dan perkembangan terkini seputar perjuangan hak-hak petani di Karawang.</p><p>Website ini merupakan ruang digital bagi seluruh anggota dan simpatisan untuk tetap terhubung dengan gerakan reforma agraria yang kami perjuangkan bersama.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

### new_string

```
        $postsData = [
            [
                'title'        => 'Selamat Datang di Website Resmi SEPETAK',
                'slug'         => 'selamat-datang-di-website-sepetak',
                'excerpt'      => 'Kami dengan bangga mempersembahkan website resmi SEPETAK (Serikat Pekerja Tani Karawang) sebagai pusat informasi perjuangan pekerja tani dan nelayan Karawang.',
                'body'         => '<p>Selamat datang di website resmi <strong>SEPETAK — Serikat Pekerja Tani Karawang</strong>. Melalui platform ini kami berbagi informasi, berita, dan perkembangan terkini seputar perjuangan hak-hak pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat.</p><p>SEPETAK berdiri sejak Kongres I pada 3–4 November 2007 dan dideklarasikan pada 10 Desember 2007 di Karawang. Sejak Kongres III, nama organisasi resmi berubah dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong> untuk menegaskan bahwa kami adalah organisasi pekerja tani yang berdaulat, bukan sekadar kelompok profesi.</p><p>Website ini menjadi ruang digital bagi seluruh anggota dan simpatisan untuk tetap terhubung dengan gerakan reforma agraria yang kami perjuangkan bersama.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

---

## Edit #4

### old_string

```
        $pagesData = [
            [
                'title'        => 'Tentang Kami',
                'slug'         => 'tentang-kami',
                'body'         => '<h2>Tentang SEPETAK</h2><p>SEPETAK (Serikat Petani Karawang) adalah organisasi tani yang berdiri untuk memperjuangkan hak-hak petani dan reforma agraria di wilayah Karawang, Jawa Barat.</p><p>Kami terdiri dari para petani, aktivis, dan relawan yang berdedikasi untuk mewujudkan keadilan agraria dan kesejahteraan petani melalui advokasi, pendampingan hukum, dan pemberdayaan masyarakat tani.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Visi dan Misi',
                'slug'         => 'visi-misi',
                'body'         => '<h2>Visi</h2><p>Terwujudnya reforma agraria sejati dan kesejahteraan petani Karawang yang berkeadilan dan berkelanjutan.</p><h2>Misi</h2><ul><li>Memperjuangkan redistribusi tanah bagi petani tak bertanah</li><li>Mendampingi petani dalam sengketa agraria</li><li>Membangun kesadaran hukum agraria di kalangan petani</li><li>Mendorong kebijakan pertanian yang berpihak pada petani kecil</li><li>Membangun solidaritas dan jaringan antar organisasi tani</li></ul>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Kontak',
                'slug'         => 'kontak',
                'body'         => '<h2>Hubungi Kami</h2><p>Untuk informasi lebih lanjut atau jika Anda membutuhkan bantuan, silakan hubungi kami melalui:</p><ul><li><strong>Email:</strong> info@sepetak.org</li><li><strong>Telepon:</strong> +62 xxx xxxx xxxx</li><li><strong>Alamat:</strong> Karawang, Jawa Barat</li></ul>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
        ];
```

### new_string

```
        $pagesData = [
            [
                'title'        => 'Tentang Kami',
                'slug'         => 'tentang-kami',
                'body'         => '<h2>Tentang SEPETAK</h2><p><strong>SEPETAK (Serikat Pekerja Tani Karawang)</strong> adalah organisasi massa berbasis pekerja tani dan nelayan yang bersifat terbuka di Kabupaten Karawang, Jawa Barat. SEPETAK didirikan melalui <strong>Kongres I</strong> pada 3–4 November 2007 dan dideklarasikan pada <strong>10 Desember 2007</strong> di Karawang.</p><p>Sejak Kongres III, nama resmi organisasi berubah dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong> untuk menegaskan posisi kami sebagai gerakan pekerja tani yang berdaulat atas alat produksi dan hasil kerjanya, sekaligus membuka keanggotaan bagi nelayan pesisir Karawang.</p><h3>Apa yang Kami Lakukan</h3><ul><li>Advokasi dan pendampingan hukum dalam sengketa agraria, termasuk penyelesaian konflik tanah absentee, klaim kawasan hutan Perhutani, dan konflik lahan dengan korporasi.</li><li>Pengorganisasian massa pekerja tani dan nelayan di tingkat desa, kecamatan, dan kabupaten melalui <em>Dewan Pimpinan Tani Kabupaten</em>.</li><li>Pendidikan kritis dan sekolah tani untuk meningkatkan kesadaran hukum agraria anggota.</li><li>Mendorong kebijakan publik yang berpihak pada pekerja tani kecil, termasuk pupuk subsidi, infrastruktur pertanian, dan akses pasar yang adil.</li></ul><h3>Jaringan Perjuangan</h3><p>SEPETAK adalah bagian dari jaringan organisasi tani nasional dan terlibat aktif dalam <a href="https://kpa.or.id" target="_blank" rel="noopener">Konsorsium Pembaruan Agraria (KPA)</a>, bekerja sama dengan serikat-serikat tani se-Jawa Barat, Banten, dan Jawa Tengah untuk memperjuangkan reforma agraria sejati berdasarkan UUPA 1960.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Visi dan Misi',
                'slug'         => 'visi-misi',
                'body'         => '<h2>Visi</h2><p>Terwujudnya reforma agraria sejati, kedaulatan pangan, dan kesejahteraan pekerja tani dan nelayan Karawang yang berkeadilan sosial, berdaulat secara ekonomi, dan berkelanjutan secara ekologis.</p><h2>Misi</h2><ul><li>Memperjuangkan redistribusi tanah bagi pekerja tani tak bertanah dan penyelesaian konflik agraria struktural.</li><li>Mendampingi pekerja tani dan nelayan dalam sengketa agraria, klaim kawasan, dan kriminalisasi.</li><li>Membangun kesadaran hukum agraria dan pendidikan politik anggota melalui sekolah tani.</li><li>Mendorong kebijakan pertanian dan perikanan yang berpihak pada pekerja tani kecil dan nelayan tradisional.</li><li>Membangun solidaritas dan jaringan lintas organisasi tani, buruh, mahasiswa, dan gerakan masyarakat sipil.</li></ul><h2>Lima Pilar Perjuangan (Rekomendasi Kongres II)</h2><p>Kongres II SEPETAK pada 10–11 Desember 2010 menetapkan bahwa setiap pekerja tani harus memiliki akses terhadap lima pilar:</p><ol><li><strong>Tanah</strong> — sebagai alat produksi utama yang didistribusikan secara adil.</li><li><strong>Infrastruktur</strong> — irigasi, jalan usaha tani, dan gudang pasca-panen.</li><li><strong>Modal</strong> — kredit usaha rakyat yang terjangkau dan tidak mencekik.</li><li><strong>Teknologi</strong> — alat mekanisasi tepat guna dan benih berkualitas.</li><li><strong>Akses Pasar</strong> — rantai pasar yang memutus praktik tengkulak eksploitatif.</li></ol>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Sejarah SEPETAK',
                'slug'         => 'sejarah',
                'body'         => '<h2>Sejarah Singkat SEPETAK</h2><p>SEPETAK lahir dari gelombang perlawanan pekerja tani Karawang terhadap alih fungsi lahan pertanian, konflik agraria, dan kriminalisasi petani yang semakin meluas di awal 2000-an.</p><h3>Kongres I — 3–4 November 2007</h3><p>Diadakan di Karawang dan menghasilkan deklarasi pembentukan organisasi pada <strong>10 Desember 2007</strong>. Kongres I menetapkan Anggaran Dasar, struktur Dewan Pimpinan Tani Kabupaten, dan komitmen untuk bergerak bersama nelayan pesisir Karawang.</p><h3>Kongres II — 10–11 Desember 2010</h3><p>Mengokohkan lima pilar perjuangan pekerja tani: tanah, infrastruktur, modal, teknologi, dan akses pasar. Pasca Kongres II, SEPETAK menjadi garda depan penyelesaian sengketa tanah absentee dan pengorganisasian anggota di 13 desa prioritas Karawang.</p><h3>Kongres III — Perubahan Nama</h3><p>Kongres III memutuskan perubahan nama resmi dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong>. Perubahan ini menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat atas alat produksi, sekaligus memperluas keanggotaan bagi pekerja tani penggarap, buruh tani, dan nelayan kecil.</p><h3>Rangkaian Perjuangan Utama</h3><ul><li><strong>Teluk Jambe (2013)</strong> — gugatan perdata 350 hektare lahan pertanian tiga desa melawan PT SAMP (diakuisisi Agung Podomoro Land) hingga ke Mahkamah Agung.</li><li><strong>Aksi Tol Jakarta–Cikampek (11 Juli 2013)</strong> — pekerja tani SEPETAK menutup akses tol sebagai bentuk perlawanan atas putusan yang tidak berpihak.</li><li><strong>Aksi BPN Karawang (27 Juli 2023)</strong> — ribuan pekerja tani bersama LBH mendaftarkan 88 bidang tanah di 13 desa yang diklaim sebagai kawasan hutan Perhutani tanpa dokumen yang utuh.</li><li><strong>Hari Tani Nasional 2025</strong> — SEPETAK bersama 139 organisasi tani dan nelayan di bawah koordinasi KPA menuntut 24 agenda perbaikan struktural agraria di Jakarta.</li></ul><p>Hingga hari ini, perjuangan SEPETAK tidak pernah berhenti. <em>Tanah untuk pekerja tani. Laut untuk nelayan. Keadilan untuk semua.</em></p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Kontak',
                'slug'         => 'kontak',
                'body'         => '<h2>Hubungi Kami</h2><p>Untuk informasi lebih lanjut, permohonan pendampingan, kolaborasi, atau kebutuhan data publik, silakan hubungi sekretariat SEPETAK:</p><ul><li><strong>Email resmi:</strong> info@sepetak.org</li><li><strong>Website:</strong> https://sepetak.org</li><li><strong>Alamat:</strong> Kabupaten Karawang, Jawa Barat, Indonesia</li></ul><p>Untuk <strong>pendaftaran anggota baru</strong>, gunakan formulir online di <a href="/daftar-anggota">halaman pendaftaran anggota</a>.</p><p>Untuk <strong>liputan media dan kerja sama advokasi</strong>, silakan mengajukan permohonan resmi via email dengan mencantumkan institusi, nama pewarta/perwakilan, dan topik peliputan atau kolaborasi.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
        ];
```

---

## Edit #5

### old_string

```
            ['key' => 'site_tagline',     'value' => 'Berjuang Bersama untuk Hak-hak Pekerja Tani dan Nelayan Karawang',                 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang) adalah organisasi massa berbasis pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat, yang memperjuangkan reforma agraria dan hak-hak pekerja tani sejak 10 Desember 2007.', 'group' => 'general'],
```

### new_string

```
            ['key' => 'site_tagline',     'value' => 'Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian',                         'group' => 'general'],
            ['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang) adalah organisasi massa berbasis pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat, yang memperjuangkan TANI MOTEKAR (Tanah, Infrastruktur, Modal, Teknologi, Akses Pasar) sejak 10 Desember 2007.', 'group' => 'general'],
```

---

## Edit #6

### old_string

```
            [
                'title'        => 'Tentang Kami',
                'slug'         => 'tentang-kami',
                'body'         => '<h2>Tentang SEPETAK</h2><p><strong>SEPETAK (Serikat Pekerja Tani Karawang)</strong> adalah organisasi massa berbasis pekerja tani dan nelayan yang bersifat terbuka di Kabupaten Karawang, Jawa Barat. SEPETAK didirikan melalui <strong>Kongres I</strong> pada 3–4 November 2007 dan dideklarasikan pada <strong>10 Desember 2007</strong> di Karawang.</p><p>Sejak Kongres III, nama resmi organisasi berubah dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong> untuk menegaskan posisi kami sebagai gerakan pekerja tani yang berdaulat atas alat produksi dan hasil kerjanya, sekaligus membuka keanggotaan bagi nelayan pesisir Karawang.</p><h3>Apa yang Kami Lakukan</h3><ul><li>Advokasi dan pendampingan hukum dalam sengketa agraria, termasuk penyelesaian konflik tanah absentee, klaim kawasan hutan Perhutani, dan konflik lahan dengan korporasi.</li><li>Pengorganisasian massa pekerja tani dan nelayan di tingkat desa, kecamatan, dan kabupaten melalui <em>Dewan Pimpinan Tani Kabupaten</em>.</li><li>Pendidikan kritis dan sekolah tani untuk meningkatkan kesadaran hukum agraria anggota.</li><li>Mendorong kebijakan publik yang berpihak pada pekerja tani kecil, termasuk pupuk subsidi, infrastruktur pertanian, dan akses pasar yang adil.</li></ul><h3>Jaringan Perjuangan</h3><p>SEPETAK adalah bagian dari jaringan organisasi tani nasional dan terlibat aktif dalam <a href="https://kpa.or.id" target="_blank" rel="noopener">Konsorsium Pembaruan Agraria (KPA)</a>, bekerja sama dengan serikat-serikat tani se-Jawa Barat, Banten, dan Jawa Tengah untuk memperjuangkan reforma agraria sejati berdasarkan UUPA 1960.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Visi dan Misi',
                'slug'         => 'visi-misi',
                'body'         => '<h2>Visi</h2><p>Terwujudnya reforma agraria sejati, kedaulatan pangan, dan kesejahteraan pekerja tani dan nelayan Karawang yang berkeadilan sosial, berdaulat secara ekonomi, dan berkelanjutan secara ekologis.</p><h2>Misi</h2><ul><li>Memperjuangkan redistribusi tanah bagi pekerja tani tak bertanah dan penyelesaian konflik agraria struktural.</li><li>Mendampingi pekerja tani dan nelayan dalam sengketa agraria, klaim kawasan, dan kriminalisasi.</li><li>Membangun kesadaran hukum agraria dan pendidikan politik anggota melalui sekolah tani.</li><li>Mendorong kebijakan pertanian dan perikanan yang berpihak pada pekerja tani kecil dan nelayan tradisional.</li><li>Membangun solidaritas dan jaringan lintas organisasi tani, buruh, mahasiswa, dan gerakan masyarakat sipil.</li></ul><h2>Lima Pilar Perjuangan (Rekomendasi Kongres II)</h2><p>Kongres II SEPETAK pada 10–11 Desember 2010 menetapkan bahwa setiap pekerja tani harus memiliki akses terhadap lima pilar:</p><ol><li><strong>Tanah</strong> — sebagai alat produksi utama yang didistribusikan secara adil.</li><li><strong>Infrastruktur</strong> — irigasi, jalan usaha tani, dan gudang pasca-panen.</li><li><strong>Modal</strong> — kredit usaha rakyat yang terjangkau dan tidak mencekik.</li><li><strong>Teknologi</strong> — alat mekanisasi tepat guna dan benih berkualitas.</li><li><strong>Akses Pasar</strong> — rantai pasar yang memutus praktik tengkulak eksploitatif.</li></ol>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Sejarah SEPETAK',
                'slug'         => 'sejarah',
                'body'         => '<h2>Sejarah Singkat SEPETAK</h2><p>SEPETAK lahir dari gelombang perlawanan pekerja tani Karawang terhadap alih fungsi lahan pertanian, konflik agraria, dan kriminalisasi petani yang semakin meluas di awal 2000-an.</p><h3>Kongres I — 3–4 November 2007</h3><p>Diadakan di Karawang dan menghasilkan deklarasi pembentukan organisasi pada <strong>10 Desember 2007</strong>. Kongres I menetapkan Anggaran Dasar, struktur Dewan Pimpinan Tani Kabupaten, dan komitmen untuk bergerak bersama nelayan pesisir Karawang.</p><h3>Kongres II — 10–11 Desember 2010</h3><p>Mengokohkan lima pilar perjuangan pekerja tani: tanah, infrastruktur, modal, teknologi, dan akses pasar. Pasca Kongres II, SEPETAK menjadi garda depan penyelesaian sengketa tanah absentee dan pengorganisasian anggota di 13 desa prioritas Karawang.</p><h3>Kongres III — Perubahan Nama</h3><p>Kongres III memutuskan perubahan nama resmi dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong>. Perubahan ini menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat atas alat produksi, sekaligus memperluas keanggotaan bagi pekerja tani penggarap, buruh tani, dan nelayan kecil.</p><h3>Rangkaian Perjuangan Utama</h3><ul><li><strong>Teluk Jambe (2013)</strong> — gugatan perdata 350 hektare lahan pertanian tiga desa melawan PT SAMP (diakuisisi Agung Podomoro Land) hingga ke Mahkamah Agung.</li><li><strong>Aksi Tol Jakarta–Cikampek (11 Juli 2013)</strong> — pekerja tani SEPETAK menutup akses tol sebagai bentuk perlawanan atas putusan yang tidak berpihak.</li><li><strong>Aksi BPN Karawang (27 Juli 2023)</strong> — ribuan pekerja tani bersama LBH mendaftarkan 88 bidang tanah di 13 desa yang diklaim sebagai kawasan hutan Perhutani tanpa dokumen yang utuh.</li><li><strong>Hari Tani Nasional 2025</strong> — SEPETAK bersama 139 organisasi tani dan nelayan di bawah koordinasi KPA menuntut 24 agenda perbaikan struktural agraria di Jakarta.</li></ul><p>Hingga hari ini, perjuangan SEPETAK tidak pernah berhenti. <em>Tanah untuk pekerja tani. Laut untuk nelayan. Keadilan untuk semua.</em></p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Kontak',
                'slug'         => 'kontak',
                'body'         => '<h2>Hubungi Kami</h2><p>Untuk informasi lebih lanjut, permohonan pendampingan, kolaborasi, atau kebutuhan data publik, silakan hubungi sekretariat SEPETAK:</p><ul><li><strong>Email resmi:</strong> info@sepetak.org</li><li><strong>Website:</strong> https://sepetak.org</li><li><strong>Alamat:</strong> Kabupaten Karawang, Jawa Barat, Indonesia</li></ul><p>Untuk <strong>pendaftaran anggota baru</strong>, gunakan formulir online di <a href="/daftar-anggota">halaman pendaftaran anggota</a>.</p><p>Untuk <strong>liputan media dan kerja sama advokasi</strong>, silakan mengajukan permohonan resmi via email dengan mencantumkan institusi, nama pewarta/perwakilan, dan topik peliputan atau kolaborasi.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
        ];
```

### new_string

```
            [
                'title'        => 'Tentang Kami',
                'slug'         => 'tentang-kami',
                'body'         => '<h2>Tentang SEPETAK</h2><p><strong>SEPETAK (Serikat Pekerja Tani Karawang)</strong> adalah organisasi massa berbasis pekerja tani dan nelayan yang bersifat terbuka di Kabupaten Karawang, Jawa Barat. SEPETAK didirikan melalui <strong>Kongres I pada 3–4 November 2007</strong> dan dideklarasikan pada <strong>10 Desember 2007</strong> di Karawang, dengan basis awal lima desa di Kecamatan Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya.</p><p>Sejak <strong>Kongres III tahun 2015</strong>, nama resmi organisasi berubah dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong>. Perubahan ini menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat atas alat produksi, sekaligus memperluas keanggotaan bagi pekerja tani penggarap, buruh tani, dan nelayan kecil pesisir Karawang.</p><h3>Sifat dan Bentuk Organisasi</h3><p>SEPETAK adalah <strong>organisasi tani berbasis massa dan bersifat terbuka</strong>. Kami tidak membatasi keanggotaan pada satu lapisan ekonomi — anggota SEPETAK datang dari berbagai lapisan sosial di pedesaan Karawang, termasuk buruh tani (landless-peasant), petani penggarap, petani pemilik lahan kecil, serta nelayan pesisir.</p><h3>Tujuan Organisasi</h3><ol><li>Mewujudkan masyarakat Karawang yang <strong>demokratis, berkeadilan sosial, dan berkedaulatan</strong>.</li><li>Membebaskan pekerja tani dari segala bentuk penindasan dan pembodohan untuk mencapai <strong>kesetaraan dalam ekonomi, sosial, budaya, hukum, dan politik</strong>.</li><li>Memperkuat posisi pekerja tani dalam menentukan <strong>kebijakan politik, hukum, sosial, dan budaya</strong> demi terwujudnya kesejahteraan yang adil, makmur, dan merata.</li></ol><h3>Pokok-pokok Perjuangan</h3><ol><li>Terlibat aktif dan memimpin perjuangan pekerja tani dalam memperjuangkan hak-haknya.</li><li>Aktif membangun, mendorong, dan memajukan kesadaran pekerja tani dan organisasi tani.</li><li>Mendorong dan memajukan kesejahteraan pekerja tani.</li><li>Aktif dalam kerja-kerja solidaritas dan perjuangan Rakyat tertindas lainnya.</li></ol><h3>Jaringan Perjuangan</h3><p>SEPETAK adalah anggota <strong><a href="https://kpa.or.id" target="_blank" rel="noopener">Konsorsium Pembaruan Agraria (KPA)</a></strong> — konsorsium 173 organisasi gerakan tani, masyarakat adat, dan nelayan — serta berjejaring dengan serikat buruh dan ormas lokal Karawang seperti <strong>ALIANSI PERAK</strong> (Aliansi Pergerakan Rakyat Karawang).</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Visi dan Misi',
                'slug'         => 'visi-misi',
                'body'         => '<h2>Visi</h2><p><strong>“Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian.”</strong></p><p>Terwujudnya reforma agraria sejati, kedaulatan pangan, dan kesejahteraan pekerja tani serta nelayan Karawang yang berkeadilan sosial, berdaulat secara ekonomi, dan berkelanjutan secara ekologis.</p><h2>Program Perjuangan — TANI MOTEKAR</h2><p><strong>TANI MOTEKAR</strong> adalah platform perjuangan SEPETAK yang dirumuskan pada <strong>Kongres II tahun 2012</strong>. Akronim ini menyatakan lima pilar yang harus dikuasai pekerja tani untuk mencapai kemandirian dan kesejahteraan:</p><ol><li><strong>T — Tanah</strong>: pokok dasar perjuangan; alat produksi utama yang harus dikuasai dan didistribusikan secara adil kepada pekerja tani.</li><li><strong>I — Infrastruktur</strong>: jalan desa, irigasi teknis, dan fasilitas pasca-panen untuk menunjang produksi pertanian.</li><li><strong>M — Modal</strong>: bukan sekadar uang, tetapi seluruh input pertanian (benih, pupuk, alat mesin pertanian) yang harus berada dalam kontrol pekerja tani.</li><li><strong>T — Teknologi</strong>: teknologi tepat guna untuk meningkatkan produktivitas tanpa membuat pekerja tani tergantung pada korporasi.</li><li><strong>A — Akses Pasar</strong>: rantai pemasaran produk pertanian dan olahan lanjut yang memutus praktik tengkulak eksploitatif melalui model pertukaran antar-organisasi di basis massa.</li></ol><p><em>TANI MOTEKAR sebagai jalan menuju Industrialisasi Pertanian</em> — industri yang diselenggarakan di sektor pertanian pedesaan, berlandaskan partisipasi penuh masyarakat, ketersediaan bahan baku, kelestarian lingkungan, dan pengabdian pada kepentingan publik.</p><h2>Misi</h2><ul><li>Memperjuangkan redistribusi tanah bagi pekerja tani tak bertanah dan penyelesaian konflik agraria struktural.</li><li>Mendampingi pekerja tani dan nelayan dalam sengketa agraria, klaim kawasan hutan Perhutani, dan kriminalisasi.</li><li>Membangun kesadaran hukum agraria dan pendidikan kritis anggota melalui sekolah tani.</li><li>Mendorong kebijakan pertanian dan perikanan yang berpihak pada pekerja tani kecil dan nelayan tradisional.</li><li>Membangun solidaritas dan jaringan lintas organisasi tani, buruh, mahasiswa, dan gerakan masyarakat sipil.</li><li>Membangun model <strong>pertanian kolektif</strong> di desa-desa basis untuk mengakumulasi surplus produksi bagi pengambilalihan bertahap tanah absentee.</li></ul>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Sejarah SEPETAK',
                'slug'         => 'sejarah',
                'body'         => '<h2>Sejarah Singkat SEPETAK</h2><p>SEPETAK lahir dari gelombang pengorganisasian pedesaan pasca reformasi 1998 di Karawang. Sebelum SEPETAK berdiri, gerakan tani Karawang sudah digerakkan oleh Serikat Tani Nasional (STN) — yang mengadvokasi kasus tanah Kuta Tandingan (Telukjambe Barat) dan mengorganisir petani Desa Karang Jaya (Pedes) — serta oleh NGO <em>Duta Tani Karawang</em> yang kemudian melahirkan <em>Dewan Tani Karawang</em>.</p><p>Dari titik masuk berbeda dan melalui pasang-surut, gagasan gerakan tani terus bergulir hingga akhirnya terbentuk <strong>Serikat Petani Karawang (SEPETAK)</strong>.</p><h3>Kongres I — 3–4 November 2007</h3><p>Kongres pertama diadakan di Karawang dan menghasilkan deklarasi pembentukan organisasi pada <strong>10 Desember 2007</strong>. Basis awal adalah lima desa di Kecamatan Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya (sebelumnya basis Dewan Tani Karawang). Kongres I menetapkan Anggaran Dasar dan struktur organisasi: Kongres, Dewan Tani, Dewan Pimpinan Tani Kabupaten (DPTK), Dewan Pimpinan Tani Desa (DPTD), dan Kelompok Kerja (Pokja).</p><h3>Kongres II — 2012</h3><p>Kongres II merumuskan platform perjuangan <strong>TANI MOTEKAR</strong> (Tanah, Infrastruktur, Modal, Teknologi, Akses Pasar) dan program perjuangan <strong>“Bangun Industrialisasi Pertanian”</strong>. Pasca Kongres II, SEPETAK memetakan lima kategori wilayah rawan konflik agraria di Karawang: desa hutan, eks. Tegalwaru landen, sekitar zona industri, wilayah pangan, dan pesisir.</p><h3>Kongres III — 2015</h3><p>Kongres III memutuskan <strong>perubahan nama resmi</strong> dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong> — menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat, bukan sekadar profesi. Kongres III juga memperluas secara formal keanggotaan bagi nelayan kecil dan pekerja tani penggarap.</p><h3>Rangkaian Aksi Perjuangan</h3><ul><li><strong>Penolakan Penambangan Pasir Laut Tanjung Pakis (2008–2009)</strong> — advokasi nelayan, pedagang wisata pantai, dan warga terdampak; berhasil menghentikan kegiatan tambang dan melahirkan basis SEPETAK baru di pesisir utara.</li><li><strong>Penolakan Penambangan Batu Andesit Tegalwaru (2009–2010)</strong> — perlawanan terhadap ekstraktif di wilayah dataran selatan Karawang.</li><li><strong>Aksi Ganti Rugi Gagal Tanam Cilamaya (2011)</strong> — memperjuangkan kompensasi atas gagal panen yang menimpa anggota.</li><li><strong>Sengketa Teluk Jambe (2013)</strong> — gugatan perdata 350 hektare lahan tiga desa melawan PT Sumber Air Mas Pratama (SAMP, diakuisisi Agung Podomoro Land) hingga Mahkamah Agung.</li><li><strong>Aksi Tol Jakarta–Cikampek (11 Juli 2013)</strong> — pekerja tani SEPETAK menutup akses tol sebagai respons putusan yang tidak berpihak.</li><li><strong>Aksi BPN Karawang (27 Juli 2023)</strong> — ribuan pekerja tani bersama LBH Arya Mandalika mendaftarkan <strong>88 bidang tanah di 13 desa</strong> yang diklaim sebagai kawasan hutan Perhutani tanpa dokumen utuh.</li><li><strong>Pernyataan Sikap 1 Agustus 2023</strong> — melawan kriminalisasi anggota oleh FORKOPIMDA Karawang pasca aksi 27 Juli.</li><li><strong>Hari Tani Nasional 2025 (24 September 2025)</strong> — SEPETAK bersama 139 organisasi tani-nelayan di bawah KPA menuntut 24 agenda perbaikan struktural agraria di Jakarta.</li></ul><h3>Pertanian Kolektif Telukjaya</h3><p>Sebagai implementasi TANI MOTEKAR, SEPETAK pernah membangun model <em>pertanian kolektif</em> di Desa Telukjaya (Kec. Pakisjaya) dengan tujuan mengumpulkan surplus produksi untuk mengambil alih tanah absentee. Eksperimen ini menjadi pelajaran berharga tentang pentingnya kontrol kolektif dan kaderisasi yang kuat.</p><p><em>Hingga hari ini, perjuangan SEPETAK tidak pernah berhenti.</em> <strong>Tanah untuk pekerja tani. Laut untuk nelayan. Keadilan untuk semua.</strong></p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Struktur Organisasi',
                'slug'         => 'struktur-organisasi',
                'body'         => '<h2>Struktur Organisasi SEPETAK</h2><p>Struktur SEPETAK disusun berdasarkan Anggaran Dasar hasil Kongres I dan disempurnakan pada kongres berikutnya. Struktur dirancang agar pengambilan keputusan dilakukan secara kolektif dari tingkat dusun hingga kabupaten.</p><h3>1. Kongres</h3><p>Forum tertinggi pembuat dan pengambil keputusan. Dilaksanakan <strong>sekurang-kurangnya tiga tahun sekali</strong>. Peserta terdiri dari seluruh jajaran pimpinan SEPETAK di setiap tingkat struktur dan anggota yang mendapat rekomendasi Dewan Pimpinan.</p><h3>2. Dewan Tani</h3><p>Pembuat keputusan tertinggi setelah Kongres. Rapat dilaksanakan <strong>minimal satu kali dalam enam bulan</strong>. Anggota Dewan Tani:</p><ul><li>Seluruh jajaran Dewan Pimpinan Tani Kabupaten (DPTK).</li><li>Ketua atau perwakilan Pimpinan Tani Desa.</li><li>Anggota SEPETAK yang mendapat rekomendasi Dewan Pimpinan Tani.</li></ul><h3>3. Dewan Pimpinan Tani Kabupaten (DPTK)</h3><p>Badan pimpinan tertinggi di bawah Dewan Tani. Dipilih, diangkat, dan diberhentikan oleh Kongres untuk <strong>masa jabatan tiga tahun</strong>. DPTK adalah pimpinan harian dan pembuat keputusan harian organisasi.</p><p>Komposisi DPTK:</p><ul><li>Ketua Umum</li><li>Sekretaris Umum</li><li>Ketua Departemen dan staf, terdiri dari:<ol><li><strong>Departemen Internal</strong> — pengorganisasian, kaderisasi, keanggotaan.</li><li><strong>Departemen Advokasi dan Perjuangan Tani</strong> — advokasi hukum, kampanye, aksi massa.</li><li><strong>Departemen Dana dan Usaha</strong> — pengelolaan keuangan organisasi dan usaha produktif.</li><li><strong>Departemen Pendidikan, Penelitian, dan Propaganda</strong> — sekolah tani, riset, publikasi.</li></ol></li></ul><p>Seluruh kerja harian departemen dikoordinasi dan dikontrol oleh Sekretaris Umum.</p><h3>4. Dewan Pimpinan Tani Desa (DPTD)</h3><p>Struktur organisasi tertinggi di tingkat desa. Dibentuk melalui Konferensi atau Musyawarah Desa untuk <strong>masa jabatan dua tahun</strong>. Syarat pembentukan DPTD: <strong>minimal tiga Pokja telah terbentuk</strong> di desa tersebut. Komposisi: Ketua, Sekretaris, dan staf departemen.</p><h3>5. Kelompok Kerja (Pokja)</h3><p>Unit organisasi terkecil, terdiri dari <strong>minimal lima anggota SEPETAK</strong> dan berkedudukan di wilayah kerja 1–2 dusun. Pokja dipimpin seorang Koordinator yang dipilih oleh anggota. Tugas utama Pokja: mengkoordinasikan kerja anggota di wilayahnya dan memperluas keanggotaan ke dusun-dusun di sekitarnya.</p><h3>Alur Rekruitmen Anggota</h3><ol><li>Individu bergabung sebagai anggota SEPETAK.</li><li>Jika ada <strong>3–5 anggota</strong> di satu dusun, bentuk <strong>Pokja</strong> dengan koordinator.</li><li>Jika ada <strong>minimal 3 Pokja</strong> di satu desa, selenggarakan <strong>Konferensi/Musyawarah Desa</strong> untuk membentuk <strong>DPTD</strong>.</li><li>DPTD memperluas basis ke desa-desa tetangga.</li></ol><p><em>Sistem berjenjang ini memastikan pengorganisasian berjalan dari bawah ke atas (bottom-up) dan setiap keputusan strategis lahir dari basis massa.</em></p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Kontak',
                'slug'         => 'kontak',
                'body'         => '<h2>Hubungi Kami</h2><p>Untuk informasi lebih lanjut, permohonan pendampingan, kolaborasi, atau kebutuhan data publik, silakan hubungi sekretariat SEPETAK:</p><ul><li><strong>Email resmi:</strong> info@sepetak.org</li><li><strong>Website:</strong> https://sepetak.org</li><li><strong>Alamat:</strong> Kabupaten Karawang, Jawa Barat, Indonesia</li></ul><p>Untuk <strong>pendaftaran anggota baru</strong>, gunakan formulir online di <a href="/daftar-anggota">halaman pendaftaran anggota</a>.</p><p>Untuk <strong>liputan media dan kerja sama advokasi</strong>, silakan mengajukan permohonan resmi via email dengan mencantumkan institusi, nama pewarta/perwakilan, dan topik peliputan atau kolaborasi.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
        ];
```

---

## Edit #7

### old_string

```
            [
                'title'        => 'Tentang Kami',
                'slug'         => 'tentang-kami',
                'body'         => '<h2>Tentang SEPETAK</h2><p><strong>SEPETAK (Serikat Pekerja Tani Karawang)</strong> adalah organisasi massa berbasis pekerja tani dan nelayan yang bersifat terbuka di Kabupaten Karawang, Jawa Barat. SEPETAK didirikan melalui <strong>Kongres I pada 3–4 November 2007</strong> dan dideklarasikan pada <strong>10 Desember 2007</strong> di Karawang, dengan basis awal lima desa di Kecamatan Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya.</p><p>Sejak <strong>Kongres III tahun 2015</strong>, nama resmi organisasi berubah dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong>. Perubahan ini menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat atas alat produksi, sekaligus memperluas keanggotaan bagi pekerja tani penggarap, buruh tani, dan nelayan kecil pesisir Karawang.</p><h3>Sifat dan Bentuk Organisasi</h3><p>SEPETAK adalah <strong>organisasi tani berbasis massa dan bersifat terbuka</strong>. Kami tidak membatasi keanggotaan pada satu lapisan ekonomi — anggota SEPETAK datang dari berbagai lapisan sosial di pedesaan Karawang, termasuk buruh tani (landless-peasant), petani penggarap, petani pemilik lahan kecil, serta nelayan pesisir.</p><h3>Tujuan Organisasi</h3><ol><li>Mewujudkan masyarakat Karawang yang <strong>demokratis, berkeadilan sosial, dan berkedaulatan</strong>.</li><li>Membebaskan pekerja tani dari segala bentuk penindasan dan pembodohan untuk mencapai <strong>kesetaraan dalam ekonomi, sosial, budaya, hukum, dan politik</strong>.</li><li>Memperkuat posisi pekerja tani dalam menentukan <strong>kebijakan politik, hukum, sosial, dan budaya</strong> demi terwujudnya kesejahteraan yang adil, makmur, dan merata.</li></ol><h3>Pokok-pokok Perjuangan</h3><ol><li>Terlibat aktif dan memimpin perjuangan pekerja tani dalam memperjuangkan hak-haknya.</li><li>Aktif membangun, mendorong, dan memajukan kesadaran pekerja tani dan organisasi tani.</li><li>Mendorong dan memajukan kesejahteraan pekerja tani.</li><li>Aktif dalam kerja-kerja solidaritas dan perjuangan Rakyat tertindas lainnya.</li></ol><h3>Jaringan Perjuangan</h3><p>SEPETAK adalah anggota <strong><a href="https://kpa.or.id" target="_blank" rel="noopener">Konsorsium Pembaruan Agraria (KPA)</a></strong> — konsorsium 173 organisasi gerakan tani, masyarakat adat, dan nelayan — serta berjejaring dengan serikat buruh dan ormas lokal Karawang seperti <strong>ALIANSI PERAK</strong> (Aliansi Pergerakan Rakyat Karawang).</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

### new_string

```
            [
                'title'        => 'Tentang Kami',
                'slug'         => 'tentang-kami',
                'body'         => '<h2>Tentang SEPETAK</h2><p><strong>SEPETAK (Serikat Pekerja Tani Karawang)</strong> adalah organisasi massa berbasis pekerja tani dan nelayan yang bersifat terbuka di Kabupaten Karawang, Jawa Barat. SEPETAK didirikan melalui <strong>Kongres I pada 3–4 November 2007</strong> dan dideklarasikan pada <strong>10 Desember 2007</strong> di Karawang.</p><p>Sejak <strong>Kongres III tahun 2015</strong>, nama resmi organisasi berubah dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong>. Perubahan nama ini menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat atas alat produksi, sekaligus memperluas keanggotaan secara formal bagi pekerja tani penggarap, buruh tani, dan nelayan kecil pesisir.</p><h3>Konteks Kabupaten Karawang</h3><p>Karawang adalah daerah tingkat II di utara Jawa Barat, mencakup <strong>30 kecamatan, 297 desa, dan 12 kelurahan</strong> dengan luas daratan ±1.753,27 km² (3,73% luas Jawa Barat) dan wilayah laut ±4 mil × 57 km. Topografinya didominasi dataran rendah (&lt;200 mdpl); hanya tiga kecamatan di selatan yang berada pada ketinggian &gt;200 mdpl hingga lebih dari 1.000 mdpl. Karawang dikenal sebagai <strong>salah satu sentra padi nasional</strong> sekaligus kawasan industri yang berkembang sejak 1989.</p><p>Kombinasi ini membuat Karawang menghadapi tekanan agraria yang khas: di satu sisi ada sawah pangan dan pesisir nelayan, di sisi lain ada konversi lahan untuk zona industri, perumahan, dan infrastruktur. Konflik agraria di sini tidak selalu tampak sebagai konflik terbuka seperti di dataran tinggi, melainkan berlangsung melalui mekanisme pasar tanah dan praktik kepemilikan <em>absentee</em>.</p><h3>Sifat dan Bentuk Organisasi</h3><p>SEPETAK adalah <strong>organisasi tani berbasis massa dan bersifat terbuka</strong>. Kami tidak membatasi keanggotaan pada satu lapisan ekonomi tertentu — anggota SEPETAK datang dari berbagai lapisan sosial pedesaan Karawang, termasuk buruh tani (<em>landless-peasant</em>), petani penyakap (<em>landless-tenant</em>), petani pemilik lahan kecil, serta warga yang menggantungkan hidup dari laut, perikanan, dan pariwisata pesisir.</p><h3>Tujuan Organisasi</h3><ol><li>Mewujudkan masyarakat Karawang yang <strong>demokratis, berkeadilan sosial, dan berkedaulatan</strong>.</li><li>Membebaskan pekerja tani dari segala bentuk penindasan dan pembodohan untuk mencapai <strong>kesetaraan dalam bidang ekonomi, sosial, budaya, hukum, dan politik</strong>.</li><li>Memperkuat posisi pekerja tani dalam menentukan <strong>kebijakan politik, hukum, sosial, dan budaya</strong> demi terwujudnya kesejahteraan yang adil, makmur, dan merata.</li></ol><h3>Pokok-pokok Perjuangan</h3><ol><li>Terlibat aktif dan memimpin perjuangan pekerja tani dalam memperjuangkan hak-haknya.</li><li>Aktif dalam membangun, mendorong, dan memajukan kesadaran pekerja tani dan organisasi tani.</li><li>Mendorong dan memajukan kesejahteraan pekerja tani.</li><li>Aktif dalam kerja-kerja solidaritas dan perjuangan Rakyat tertindas lainnya.</li></ol><h3>Jaringan Perjuangan</h3><ul><li><strong>Nasional</strong>: SEPETAK adalah anggota <a href="https://kpa.or.id" target="_blank" rel="noopener"><strong>Konsorsium Pembaruan Agraria (KPA)</strong></a> — konsorsium yang menghimpun 173 organisasi gerakan tani, masyarakat adat, dan nelayan.</li><li><strong>Daerah</strong>: SEPETAK kerap dipercaya sebagai pelopor aliansi lintas sektor di Karawang. <strong>ALIANSI PERAK</strong> (Aliansi Pergerakan Rakyat Karawang) — gabungan SEPETAK dan beberapa serikat buruh — pernah menjadi warna kuat dalam kampanye isu agraria, sosial budaya, dan tekanan politik kepada pemerintah daerah.</li><li><strong>Lintas ormas lokal</strong>: SEPETAK juga menjalin relasi dengan berbagai LSM dan ormas Karawang seperti <strong>GMBI</strong> (Gerakan Masyarakat Bawah Indonesia) dan <strong>LMP</strong> (Laskar Merah Putih) melalui proses konsolidasi gagasan yang panjang.</li></ul>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

---

## Edit #8

### old_string

```
            [
                'title'        => 'Visi dan Misi',
                'slug'         => 'visi-misi',
                'body'         => '<h2>Visi</h2><p><strong>“Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian.”</strong></p><p>Terwujudnya reforma agraria sejati, kedaulatan pangan, dan kesejahteraan pekerja tani serta nelayan Karawang yang berkeadilan sosial, berdaulat secara ekonomi, dan berkelanjutan secara ekologis.</p><h2>Program Perjuangan — TANI MOTEKAR</h2><p><strong>TANI MOTEKAR</strong> adalah platform perjuangan SEPETAK yang dirumuskan pada <strong>Kongres II tahun 2012</strong>. Akronim ini menyatakan lima pilar yang harus dikuasai pekerja tani untuk mencapai kemandirian dan kesejahteraan:</p><ol><li><strong>T — Tanah</strong>: pokok dasar perjuangan; alat produksi utama yang harus dikuasai dan didistribusikan secara adil kepada pekerja tani.</li><li><strong>I — Infrastruktur</strong>: jalan desa, irigasi teknis, dan fasilitas pasca-panen untuk menunjang produksi pertanian.</li><li><strong>M — Modal</strong>: bukan sekadar uang, tetapi seluruh input pertanian (benih, pupuk, alat mesin pertanian) yang harus berada dalam kontrol pekerja tani.</li><li><strong>T — Teknologi</strong>: teknologi tepat guna untuk meningkatkan produktivitas tanpa membuat pekerja tani tergantung pada korporasi.</li><li><strong>A — Akses Pasar</strong>: rantai pemasaran produk pertanian dan olahan lanjut yang memutus praktik tengkulak eksploitatif melalui model pertukaran antar-organisasi di basis massa.</li></ol><p><em>TANI MOTEKAR sebagai jalan menuju Industrialisasi Pertanian</em> — industri yang diselenggarakan di sektor pertanian pedesaan, berlandaskan partisipasi penuh masyarakat, ketersediaan bahan baku, kelestarian lingkungan, dan pengabdian pada kepentingan publik.</p><h2>Misi</h2><ul><li>Memperjuangkan redistribusi tanah bagi pekerja tani tak bertanah dan penyelesaian konflik agraria struktural.</li><li>Mendampingi pekerja tani dan nelayan dalam sengketa agraria, klaim kawasan hutan Perhutani, dan kriminalisasi.</li><li>Membangun kesadaran hukum agraria dan pendidikan kritis anggota melalui sekolah tani.</li><li>Mendorong kebijakan pertanian dan perikanan yang berpihak pada pekerja tani kecil dan nelayan tradisional.</li><li>Membangun solidaritas dan jaringan lintas organisasi tani, buruh, mahasiswa, dan gerakan masyarakat sipil.</li><li>Membangun model <strong>pertanian kolektif</strong> di desa-desa basis untuk mengakumulasi surplus produksi bagi pengambilalihan bertahap tanah absentee.</li></ul>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

### new_string

```
            [
                'title'        => 'Visi dan Misi',
                'slug'         => 'visi-misi',
                'body'         => '<h2>Visi</h2><p><strong>“Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian.”</strong></p><p>Terwujudnya reforma agraria sejati, kedaulatan pangan, dan kesejahteraan pekerja tani serta nelayan Karawang yang berkeadilan sosial, berdaulat secara ekonomi, dan berkelanjutan secara ekologis.</p><h2>Program Perjuangan — TANI MOTEKAR</h2><p><strong>TANI MOTEKAR</strong> adalah program tuntutan dan platform perjuangan SEPETAK yang dirumuskan pada <strong>Kongres II tahun 2012</strong>. Akronim ini menyatakan lima pilar yang harus dikuasai pekerja tani agar kemandirian dan kesejahteraan benar-benar berpijak pada kontrol produksi sendiri:</p><ol><li><strong>T — Tanah</strong>: pokok dasar perjuangan; alat produksi utama yang harus dikuasai dan didistribusikan secara adil kepada pekerja tani.</li><li><strong>I — Infrastruktur</strong>: jalan desa, irigasi teknis, dan fasilitas pasca-panen sebagai prasyarat produksi pertanian rakyat yang efisien.</li><li><strong>M — Modal</strong>: bukan sekadar uang, tetapi seluruh input pertanian — benih, pupuk, dan alat-mesin pertanian — yang harus berada dalam kontrol pekerja tani, bukan korporasi.</li><li><strong>T — Teknologi</strong>: teknologi tepat guna yang meningkatkan produktivitas tanpa membuat pekerja tani tergantung pada pasokan eksternal.</li><li><strong>A — Akses Pasar</strong>: rantai pemasaran produk pertanian dan olahan lanjutan yang memutus praktik tengkulak eksploitatif melalui model pertukaran antar-organisasi di basis massa.</li></ol><p><em>TANI MOTEKAR sebagai jalan menuju Industrialisasi Pertanian</em> — industri yang diselenggarakan di sektor pertanian pedesaan, berlandaskan partisipasi penuh masyarakat, ketersediaan bahan baku lokal, kelestarian lingkungan, dan pengabdian pada kepentingan publik. Dengan begitu, pengembangan industri di Karawang tidak lagi semata-mata berarti <em>konversi sawah menjadi pabrik</em>, melainkan penguatan produksi pertanian rakyat hingga hilirnya.</p><h2>Misi</h2><ul><li>Memperjuangkan <strong>redistribusi tanah</strong> bagi pekerja tani tak bertanah dan penyelesaian konflik agraria struktural di Karawang.</li><li>Mendampingi pekerja tani dan nelayan dalam sengketa agraria, klaim kawasan hutan Perhutani, tuntutan ganti rugi gagal tanam, hingga kriminalisasi anggota.</li><li>Membangun kesadaran hukum agraria dan pendidikan kritis anggota melalui <strong>sekolah tani, diskusi dusun, dan propaganda internal</strong>.</li><li>Mendorong kebijakan pertanian dan perikanan daerah yang berpihak pada pekerja tani kecil dan nelayan tradisional — termasuk intervensi atas Perda RTRW dan perencanaan infrastruktur.</li><li>Membangun <strong>aliansi lintas sektor</strong> bersama serikat buruh, mahasiswa, dan gerakan masyarakat sipil (ALIANSI PERAK, KPA, dll.) untuk memperkuat gerakan agraria nasional.</li><li>Membangun model <strong>pertanian kolektif</strong> di desa-desa basis sebagai strategi mengakumulasi surplus produksi bagi pengambilalihan bertahap tanah <em>absentee</em>.</li><li>Menyediakan <strong>advokasi pelayanan publik</strong> bagi anggota — mulai dari pendampingan pelayanan PLN, layanan kesehatan (Jamkesda/Jamkesmas ke RSUD Karawang), hingga pendaftaran hak atas tanah — sebagai wujud nyata keberpihakan organisasi pada kesejahteraan anggota.</li></ul>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

---

## Edit #9

### old_string

```
            [
                'title'        => 'Sejarah SEPETAK',
                'slug'         => 'sejarah',
                'body'         => '<h2>Sejarah Singkat SEPETAK</h2><p>SEPETAK lahir dari gelombang pengorganisasian pedesaan pasca reformasi 1998 di Karawang. Sebelum SEPETAK berdiri, gerakan tani Karawang sudah digerakkan oleh Serikat Tani Nasional (STN) — yang mengadvokasi kasus tanah Kuta Tandingan (Telukjambe Barat) dan mengorganisir petani Desa Karang Jaya (Pedes) — serta oleh NGO <em>Duta Tani Karawang</em> yang kemudian melahirkan <em>Dewan Tani Karawang</em>.</p><p>Dari titik masuk berbeda dan melalui pasang-surut, gagasan gerakan tani terus bergulir hingga akhirnya terbentuk <strong>Serikat Petani Karawang (SEPETAK)</strong>.</p><h3>Kongres I — 3–4 November 2007</h3><p>Kongres pertama diadakan di Karawang dan menghasilkan deklarasi pembentukan organisasi pada <strong>10 Desember 2007</strong>. Basis awal adalah lima desa di Kecamatan Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya (sebelumnya basis Dewan Tani Karawang). Kongres I menetapkan Anggaran Dasar dan struktur organisasi: Kongres, Dewan Tani, Dewan Pimpinan Tani Kabupaten (DPTK), Dewan Pimpinan Tani Desa (DPTD), dan Kelompok Kerja (Pokja).</p><h3>Kongres II — 2012</h3><p>Kongres II merumuskan platform perjuangan <strong>TANI MOTEKAR</strong> (Tanah, Infrastruktur, Modal, Teknologi, Akses Pasar) dan program perjuangan <strong>“Bangun Industrialisasi Pertanian”</strong>. Pasca Kongres II, SEPETAK memetakan lima kategori wilayah rawan konflik agraria di Karawang: desa hutan, eks. Tegalwaru landen, sekitar zona industri, wilayah pangan, dan pesisir.</p><h3>Kongres III — 2015</h3><p>Kongres III memutuskan <strong>perubahan nama resmi</strong> dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong> — menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat, bukan sekadar profesi. Kongres III juga memperluas secara formal keanggotaan bagi nelayan kecil dan pekerja tani penggarap.</p><h3>Rangkaian Aksi Perjuangan</h3><ul><li><strong>Penolakan Penambangan Pasir Laut Tanjung Pakis (2008–2009)</strong> — advokasi nelayan, pedagang wisata pantai, dan warga terdampak; berhasil menghentikan kegiatan tambang dan melahirkan basis SEPETAK baru di pesisir utara.</li><li><strong>Penolakan Penambangan Batu Andesit Tegalwaru (2009–2010)</strong> — perlawanan terhadap ekstraktif di wilayah dataran selatan Karawang.</li><li><strong>Aksi Ganti Rugi Gagal Tanam Cilamaya (2011)</strong> — memperjuangkan kompensasi atas gagal panen yang menimpa anggota.</li><li><strong>Sengketa Teluk Jambe (2013)</strong> — gugatan perdata 350 hektare lahan tiga desa melawan PT Sumber Air Mas Pratama (SAMP, diakuisisi Agung Podomoro Land) hingga Mahkamah Agung.</li><li><strong>Aksi Tol Jakarta–Cikampek (11 Juli 2013)</strong> — pekerja tani SEPETAK menutup akses tol sebagai respons putusan yang tidak berpihak.</li><li><strong>Aksi BPN Karawang (27 Juli 2023)</strong> — ribuan pekerja tani bersama LBH Arya Mandalika mendaftarkan <strong>88 bidang tanah di 13 desa</strong> yang diklaim sebagai kawasan hutan Perhutani tanpa dokumen utuh.</li><li><strong>Pernyataan Sikap 1 Agustus 2023</strong> — melawan kriminalisasi anggota oleh FORKOPIMDA Karawang pasca aksi 27 Juli.</li><li><strong>Hari Tani Nasional 2025 (24 September 2025)</strong> — SEPETAK bersama 139 organisasi tani-nelayan di bawah KPA menuntut 24 agenda perbaikan struktural agraria di Jakarta.</li></ul><h3>Pertanian Kolektif Telukjaya</h3><p>Sebagai implementasi TANI MOTEKAR, SEPETAK pernah membangun model <em>pertanian kolektif</em> di Desa Telukjaya (Kec. Pakisjaya) dengan tujuan mengumpulkan surplus produksi untuk mengambil alih tanah absentee. Eksperimen ini menjadi pelajaran berharga tentang pentingnya kontrol kolektif dan kaderisasi yang kuat.</p><p><em>Hingga hari ini, perjuangan SEPETAK tidak pernah berhenti.</em> <strong>Tanah untuk pekerja tani. Laut untuk nelayan. Keadilan untuk semua.</strong></p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

### new_string

```
            [
                'title'        => 'Sejarah SEPETAK',
                'slug'         => 'sejarah',
                'body'         => '<h2>Sejarah Singkat SEPETAK</h2><h3>Latar: Gerakan Tani Karawang Sebelum SEPETAK</h3><p>Gerakan tani di Karawang menggeliat kembali pada era pasca reformasi 1998. Pada momen itu, gagasan-gagasan progresif kaum muda mulai masuk ke pedesaan Karawang melalui dua jalur utama:</p><ul><li><strong>Serikat Tani Nasional (STN)</strong> — mengawali advokasi kasus tanah <em>Kuta Tandingan</em> di Kecamatan Telukjambe Barat dan mengorganisir petani di Desa Karang Jaya, Kecamatan Pedes. Advokasi ini sempat terhenti karena represi aparat dan perubahan situasi lokal, namun berhasil menyisakan pengalaman dan kader awal.</li><li><strong>NGO Duta Tani Karawang</strong> — fokus awalnya bukan pada konflik agraria, melainkan pada advokasi produksi pertanian (termasuk pengendalian hama terpadu). Dari Duta Tani inilah kemudian lahir <strong>Dewan Tani Karawang</strong>, sebuah organisasi tani lokal yang berbasis di Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya. Dewan Tani Karawang akhirnya pecah karena perbedaan pandangan internal, terutama soal penggabungan dengan organ tani nasional.</li></ul><p>Dari dua titik masuk berbeda ini, gagasan gerakan tani terus bergulir hingga akhirnya mengkristal menjadi <strong>Serikat Petani Karawang (SEPETAK)</strong>.</p><h3>Kongres I — 3–4 November 2007</h3><p>Kongres pertama diadakan di Karawang dan menghasilkan <strong>deklarasi pembentukan organisasi pada 10 Desember 2007</strong>. Basis awal adalah lima desa di Kecamatan <em>Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya</em> — wilayah yang sebelumnya menjadi basis Dewan Tani Karawang. Kongres I menetapkan Anggaran Dasar dan struktur organisasi: Kongres, Dewan Tani, Dewan Pimpinan Tani Kabupaten (DPTK), Dewan Pimpinan Tani Desa (DPTD), dan Kelompok Kerja (Pokja).</p><h3>Kongres II — 2012</h3><p>Kongres II merumuskan dua capaian strategis:</p><ol><li><strong>TANI MOTEKAR</strong> (Tanah, Infrastruktur, Modal, Teknologi, Akses Pasar) sebagai program tuntutan dan platform perjuangan.</li><li><strong>“Bangun Industrialisasi Pertanian”</strong> sebagai program perjuangan jangka panjang.</li></ol><p>Pasca Kongres II, SEPETAK melakukan pemetaan wilayah dan menetapkan <strong>lima kategori wilayah rawan konflik agraria</strong> di Karawang: (a) masyarakat desa hutan, (b) eks Tegalwaru landen, (c) sekitar zona industri, (d) wilayah pangan, dan (e) pesisir. Pemetaan ini menjadi basis prioritas organisasi dalam membangun Pokja dan DPTD baru.</p><h3>Kongres III — 2015</h3><p>Kongres III memutuskan <strong>perubahan nama resmi</strong> dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong>. Perubahan ini menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat atas alat produksi, bukan sekadar profesi turunan. Kongres III juga memperluas keanggotaan secara formal bagi pekerja tani penggarap (<em>landless-tenant</em>) dan nelayan kecil pesisir.</p><h3>Rangkaian Aksi Perjuangan</h3><ul><li><strong>Penolakan Penambangan Pasir Laut Tanjung Pakis (2008–2009)</strong> — advokasi nelayan, pedagang wisata pantai, dan warga terdampak; berhasil menghentikan kegiatan tambang dan melahirkan basis SEPETAK baru di pesisir utara.</li><li><strong>Penolakan Penambangan Batu Andesit Tegalwaru (2009–2010)</strong> — perlawanan terhadap industri ekstraktif di dataran selatan Karawang.</li><li><strong>Aksi Ganti Rugi Gagal Tanam Cilamaya (2011)</strong> — memperjuangkan kompensasi bagi anggota yang gagal panen.</li><li><strong>Aksi Lintas Sektor &amp; Solidaritas</strong> — SEPETAK rutin terlibat dalam aksi bersama serikat buruh dan mahasiswa di Karawang untuk tuntutan ekonomis seperti infrastruktur desa, perbaikan irigasi, dan bantuan musim kekeringan.</li><li><strong>Sengketa Teluk Jambe (2013)</strong> — gugatan perdata 350 hektare lahan tiga desa melawan PT Sumber Air Mas Pratama (SAMP, diakuisisi Agung Podomoro Land) hingga Mahkamah Agung.</li><li><strong>Aksi Tol Jakarta–Cikampek (11 Juli 2013)</strong> — pekerja tani SEPETAK menutup akses tol sebagai respons putusan yang tidak berpihak.</li><li><strong>Penolakan Revisi Perda RTRW Karawang</strong> — intervensi atas rencana perubahan tata ruang, khususnya perubahan status kawasan Cilamaya Wetan menjadi kawasan perkotaan yang mengancam lahan anggota.</li><li><strong>Advokasi Pelayanan Publik</strong> — pendampingan anggota dalam urusan PLN, rujukan layanan Jamkesda/Jamkesmas ke RSUD Karawang (aksi 2012 yang kemudian melahirkan hubungan kelembagaan dengan RSUD).</li><li><strong>Aksi BPN Karawang (27 Juli 2023)</strong> — ribuan pekerja tani bersama LBH Arya Mandalika mendaftarkan <strong>88 bidang tanah di 13 desa</strong> yang diklaim sebagai kawasan hutan Perhutani tanpa dokumen utuh.</li><li><strong>Pernyataan Sikap 1 Agustus 2023</strong> — melawan kriminalisasi anggota oleh FORKOPIMDA Karawang pasca aksi 27 Juli.</li><li><strong>Hari Tani Nasional 2025 (24 September 2025)</strong> — SEPETAK bersama 139 organisasi tani-nelayan di bawah KPA menuntut 24 agenda perbaikan struktural agraria di Jakarta.</li></ul><h3>Eksperimen Pertanian Kolektif Telukjaya</h3><p>Sebagai implementasi langsung dari TANI MOTEKAR, SEPETAK pernah membangun model <em>pertanian kolektif</em> di <strong>Desa Telukjaya, Kecamatan Pakisjaya</strong>. Skema kerjanya:</p><ol><li>Organisasi menyewa lahan dari tuan tanah <em>absentee</em>.</li><li>Organisasi menyediakan seluruh input produksi — benih, pupuk, obat-obatan, serta alat dan mesin pertanian — sehingga anggota tidak terjerat tengkulak.</li><li>Surplus produksi dikumpulkan untuk <strong>membeli tanah absentee secara bertahap</strong> dan menjadi aset kolektif organisasi.</li><li>Paralel, SEPETAK juga mengadvokasi pemerintah daerah untuk turut mengambil alih tanah absentee sebagai objek reforma agraria.</li></ol><p>Eksperimen ini sempat terhambat karena persoalan internal — sebagian pengurus DPTD setempat memakai hasil produksi untuk kepentingan pribadi, dan organisasi mengambil sikap tegas memberhentikan pihak yang bersangkutan. Pengalaman ini menjadi pelajaran penting tentang perlunya <strong>kaderisasi yang kuat, transparansi keuangan, dan kontrol kolektif</strong> pada setiap skema produksi.</p><p><em>Hingga hari ini, perjuangan SEPETAK tidak pernah berhenti.</em> <strong>Tanah untuk pekerja tani. Laut untuk nelayan. Keadilan untuk semua.</strong></p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

---

## Edit #10

### old_string

```
            [
                'title'        => 'Struktur Organisasi',
                'slug'         => 'struktur-organisasi',
                'body'         => '<h2>Struktur Organisasi SEPETAK</h2><p>Struktur SEPETAK disusun berdasarkan Anggaran Dasar hasil Kongres I dan disempurnakan pada kongres berikutnya. Struktur dirancang agar pengambilan keputusan dilakukan secara kolektif dari tingkat dusun hingga kabupaten.</p><h3>1. Kongres</h3><p>Forum tertinggi pembuat dan pengambil keputusan. Dilaksanakan <strong>sekurang-kurangnya tiga tahun sekali</strong>. Peserta terdiri dari seluruh jajaran pimpinan SEPETAK di setiap tingkat struktur dan anggota yang mendapat rekomendasi Dewan Pimpinan.</p><h3>2. Dewan Tani</h3><p>Pembuat keputusan tertinggi setelah Kongres. Rapat dilaksanakan <strong>minimal satu kali dalam enam bulan</strong>. Anggota Dewan Tani:</p><ul><li>Seluruh jajaran Dewan Pimpinan Tani Kabupaten (DPTK).</li><li>Ketua atau perwakilan Pimpinan Tani Desa.</li><li>Anggota SEPETAK yang mendapat rekomendasi Dewan Pimpinan Tani.</li></ul><h3>3. Dewan Pimpinan Tani Kabupaten (DPTK)</h3><p>Badan pimpinan tertinggi di bawah Dewan Tani. Dipilih, diangkat, dan diberhentikan oleh Kongres untuk <strong>masa jabatan tiga tahun</strong>. DPTK adalah pimpinan harian dan pembuat keputusan harian organisasi.</p><p>Komposisi DPTK:</p><ul><li>Ketua Umum</li><li>Sekretaris Umum</li><li>Ketua Departemen dan staf, terdiri dari:<ol><li><strong>Departemen Internal</strong> — pengorganisasian, kaderisasi, keanggotaan.</li><li><strong>Departemen Advokasi dan Perjuangan Tani</strong> — advokasi hukum, kampanye, aksi massa.</li><li><strong>Departemen Dana dan Usaha</strong> — pengelolaan keuangan organisasi dan usaha produktif.</li><li><strong>Departemen Pendidikan, Penelitian, dan Propaganda</strong> — sekolah tani, riset, publikasi.</li></ol></li></ul><p>Seluruh kerja harian departemen dikoordinasi dan dikontrol oleh Sekretaris Umum.</p><h3>4. Dewan Pimpinan Tani Desa (DPTD)</h3><p>Struktur organisasi tertinggi di tingkat desa. Dibentuk melalui Konferensi atau Musyawarah Desa untuk <strong>masa jabatan dua tahun</strong>. Syarat pembentukan DPTD: <strong>minimal tiga Pokja telah terbentuk</strong> di desa tersebut. Komposisi: Ketua, Sekretaris, dan staf departemen.</p><h3>5. Kelompok Kerja (Pokja)</h3><p>Unit organisasi terkecil, terdiri dari <strong>minimal lima anggota SEPETAK</strong> dan berkedudukan di wilayah kerja 1–2 dusun. Pokja dipimpin seorang Koordinator yang dipilih oleh anggota. Tugas utama Pokja: mengkoordinasikan kerja anggota di wilayahnya dan memperluas keanggotaan ke dusun-dusun di sekitarnya.</p><h3>Alur Rekruitmen Anggota</h3><ol><li>Individu bergabung sebagai anggota SEPETAK.</li><li>Jika ada <strong>3–5 anggota</strong> di satu dusun, bentuk <strong>Pokja</strong> dengan koordinator.</li><li>Jika ada <strong>minimal 3 Pokja</strong> di satu desa, selenggarakan <strong>Konferensi/Musyawarah Desa</strong> untuk membentuk <strong>DPTD</strong>.</li><li>DPTD memperluas basis ke desa-desa tetangga.</li></ol><p><em>Sistem berjenjang ini memastikan pengorganisasian berjalan dari bawah ke atas (bottom-up) dan setiap keputusan strategis lahir dari basis massa.</em></p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

### new_string

```
            [
                'title'        => 'Struktur Organisasi',
                'slug'         => 'struktur-organisasi',
                'body'         => '<h2>Struktur Organisasi SEPETAK</h2><p>Struktur SEPETAK disusun berdasarkan Anggaran Dasar hasil Kongres I dan disempurnakan pada kongres berikutnya. Struktur dirancang agar pengambilan keputusan dilakukan secara kolektif dari tingkat dusun hingga kabupaten — <em>dari bawah ke atas</em>.</p><h3>1. Kongres</h3><p>Forum tertinggi pembuat dan pengambil keputusan. Dilaksanakan <strong>sekurang-kurangnya tiga tahun sekali</strong>. Peserta terdiri dari seluruh jajaran pimpinan SEPETAK di setiap tingkat struktur dan anggota yang mendapat rekomendasi Dewan Pimpinan.</p><h3>2. Dewan Tani</h3><p>Pembuat keputusan tertinggi setelah Kongres. Rapat dilaksanakan <strong>minimal satu kali dalam enam bulan</strong>. Anggota Dewan Tani:</p><ul><li>Seluruh jajaran Dewan Pimpinan Tani Kabupaten (DPTK).</li><li>Ketua atau perwakilan Pimpinan Tani Desa.</li><li>Anggota SEPETAK yang mendapat rekomendasi Dewan Pimpinan Tani.</li></ul><h3>3. Dewan Pimpinan Tani Kabupaten (DPTK)</h3><p>Badan pimpinan tertinggi di bawah Dewan Tani. Dipilih, diangkat, dan diberhentikan oleh Kongres untuk <strong>masa jabatan tiga tahun</strong>. DPTK adalah pimpinan harian dan pembuat keputusan harian organisasi.</p><p>Komposisi DPTK:</p><ul><li>Ketua Umum</li><li>Sekretaris Umum</li><li>Ketua Departemen dan staf, terdiri dari:<ol><li><strong>Departemen Internal</strong> — pengorganisasian, kaderisasi, keanggotaan.</li><li><strong>Departemen Advokasi dan Perjuangan Tani</strong> — advokasi hukum, kampanye, aksi massa.</li><li><strong>Departemen Dana dan Usaha</strong> — pengelolaan keuangan organisasi dan usaha produktif.</li><li><strong>Departemen Pendidikan, Penelitian, dan Propaganda</strong> — sekolah tani, riset, publikasi.</li></ol></li></ul><p>Seluruh kerja harian departemen dikoordinasi dan dikontrol oleh Sekretaris Umum.</p><h3>4. Dewan Pimpinan Tani Desa (DPTD)</h3><p>Struktur organisasi tertinggi di tingkat desa. Dibentuk melalui Konferensi atau Musyawarah Desa untuk <strong>masa jabatan dua tahun</strong>. Syarat pembentukan DPTD: <strong>minimal tiga Pokja telah terbentuk</strong> di desa tersebut. Komposisi: Ketua, Sekretaris, dan staf departemen.</p><h3>5. Kelompok Kerja (Pokja)</h3><p>Unit organisasi terkecil, terdiri dari <strong>minimal lima anggota SEPETAK</strong> dan berkedudukan di wilayah kerja 1–2 dusun. Pokja dipimpin seorang Koordinator yang dipilih oleh anggota. Tugas utama Pokja: mengkoordinasikan kerja anggota di wilayahnya dan memperluas keanggotaan ke dusun-dusun di sekitarnya.</p><h3>Dua Jalur Pengorganisasian</h3><p>Dalam praktik, SEPETAK menempuh <strong>dua jalur</strong> untuk membangun basis massa di sebuah wilayah baru:</p><ol><li><strong>Rekrutmen individu langsung</strong> — kader melakukan pendekatan satu-persatu hingga terkumpul 3–5 calon anggota di sebuah dusun, lalu membentuk Pokja.</li><li><strong>Pintu masuk konflik</strong> — pendekatan dimulai dari persoalan nyata yang dialami warga (konflik tanah, ganti rugi gagal tanam, tambang, dll.). Advokasi kolektif menjadi pengikat solidaritas yang kemudian dikonsolidasikan menjadi Pokja dan DPTD.</li></ol><p><em>Contoh historis:</em> Pembentukan Pokja di <strong>Dusun Cimahi, Desa Cikarang, Kecamatan Cilamaya Wetan pada 14 Februari 2012</strong> merupakan salah satu capaian penting yang menggabungkan kedua jalur di atas.</p><h3>Alur Rekruitmen Formal</h3><ol><li>Individu bergabung sebagai <strong>Anggota SEPETAK</strong>.</li><li>Jika ada <strong>3–5 anggota</strong> di satu dusun, bentuk <strong>Pokja</strong> dengan koordinator.</li><li>Jika terbentuk <strong>minimal tiga Pokja</strong> di satu desa, selenggarakan <strong>Konferensi / Musyawarah Desa</strong> untuk membentuk <strong>DPTD</strong>.</li><li>DPTD aktif memperluas basis ke dusun dan desa tetangga.</li></ol><h3>Catatan Kaderisasi</h3><p>Salah satu tantangan terberat organisasi adalah menghasilkan <strong>kader dari basis tani itu sendiri</strong>. Menjadi pengorganisir berarti memberikan waktu, tenaga, biaya, dan kadang keselamatan pribadi — sebuah beban yang sulit dipikul dari sektor tani yang ekonominya paling rentan. Karena itu SEPETAK terus menempatkan kaderisasi (sekolah tani, diskusi dusun, pendidikan publik) sebagai investasi jangka panjang organisasi.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
            [
                'title'        => 'Wilayah Kerja & Pemetaan Konflik',
                'slug'         => 'wilayah-kerja',
                'body'         => '<h2>Wilayah Kerja dan Pemetaan Konflik Agraria</h2><p>Karawang adalah salah satu daerah paling padat persoalan agraria di Jawa Barat: wilayah dataran rendah yang sejak 1989 terus diubah menjadi zona industri, perumahan, dan jalan tol, sementara di saat yang sama tetap menjadi sentra padi nasional. Dalam laporan Konsorsium Pembaruan Agraria (KPA) 2011, terjadi <strong>163 konflik agraria</strong> di Indonesia — 60% di sektor perkebunan, 22% kehutanan, 13% infrastruktur, 4% tambang, 1% pesisir — dengan korban mencapai <strong>22 jiwa, 69.975 KK, dan 472.048 hektare lahan</strong>. Karawang berada dalam pusaran konflik ini.</p><p>Pasca Kongres II tahun 2012, SEPETAK menetapkan <strong>lima kategori wilayah rawan konflik agraria</strong> sebagai peta prioritas pengorganisasian:</p><h3>1. Wilayah Masyarakat Desa Hutan</h3><ul><li><strong>Pihak yang terlibat</strong>: Perum Perhutani, perusahaan tambang, swasta, militer, dan pemerintah.</li><li><strong>Jenis konflik</strong>: perampasan tanah, sengketa tapal batas, dan klaim kawasan wisata.</li></ul><h3>2. Wilayah Eks Tegalwaru Landen (Karawang Selatan)</h3><ul><li><strong>Pihak yang terlibat</strong>: swasta, operator zona industri, Perhutani, pemerintah.</li><li><strong>Jenis konflik</strong>: perampasan tanah dan sengketa tapal batas.</li></ul><h3>3. Wilayah Sekitar Zona Industri</h3><ul><li><strong>Pihak yang terlibat</strong>: pengembang zona industri, perumahan, pemerintah.</li><li><strong>Jenis konflik</strong>: perampasan tanah — konversi sawah produktif menjadi kawasan industri dan perumahan.</li></ul><h3>4. Wilayah Pangan (Sabuk Sawah Karawang)</h3><ul><li><strong>Pihak yang terlibat</strong>: tuan tanah absentee dan tuan tanah lokal.</li><li><strong>Jenis konflik</strong>: kepemilikan tanah absentee yang menjerat petani penggarap ke dalam sistem penyakapan (<em>sharecropping tenancy</em>) yang eksploitatif.</li></ul><h3>5. Wilayah Pesisir</h3><ul><li><strong>Pihak yang terlibat</strong>: Perhutani, swasta, perusahaan tambang pasir laut, pemerintah.</li><li><strong>Jenis konflik</strong>: kepemilikan absentee dan perampasan ruang hidup nelayan serta pedagang pariwisata pantai.</li></ul><h3>Strategi Pengorganisasian Sesuai Peta</h3><p>Pemetaan di atas menjadi pedoman SEPETAK dalam menentukan di desa mana Pokja perlu dibangun lebih dahulu, advokasi apa yang dijalankan sesuai karakter konflik, dan siapa mitra yang relevan. Lima desa basis awal di <strong>Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya</strong> masuk dalam wilayah pangan-pesisir; sementara wilayah selatan (Tegalwaru, Pangkalan) masuk kategori hutan &amp; ekstraktif.</p><p><em>Peta ini terus diperbarui</em> — setiap DPTD baru wajib melakukan pemetaan konflik agraria di desanya sebagai dasar kerja organisasi.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

---

## Edit #11

### old_string

```
            [
                'title'        => 'Kontak',
                'slug'         => 'kontak',
                'body'         => '<h2>Hubungi Kami</h2><p>Untuk informasi lebih lanjut, permohonan pendampingan, kolaborasi, atau kebutuhan data publik, silakan hubungi sekretariat SEPETAK:</p><ul><li><strong>Email resmi:</strong> info@sepetak.org</li><li><strong>Website:</strong> https://sepetak.org</li><li><strong>Alamat:</strong> Kabupaten Karawang, Jawa Barat, Indonesia</li></ul><p>Untuk <strong>pendaftaran anggota baru</strong>, gunakan formulir online di <a href="/daftar-anggota">halaman pendaftaran anggota</a>.</p><p>Untuk <strong>liputan media dan kerja sama advokasi</strong>, silakan mengajukan permohonan resmi via email dengan mencantumkan institusi, nama pewarta/perwakilan, dan topik peliputan atau kolaborasi.</p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

### new_string

```
            [
                'title'        => 'Kontak',
                'slug'         => 'kontak',
                'body'         => '<h2>Hubungi Kami</h2><p>Untuk informasi lebih lanjut, permohonan pendampingan, kolaborasi lintas organisasi, atau kebutuhan data publik, silakan hubungi sekretariat SEPETAK:</p><ul><li><strong>Email resmi:</strong> info@sepetak.org</li><li><strong>Website:</strong> https://sepetak.org</li><li><strong>Alamat sekretariat:</strong> Kabupaten Karawang, Jawa Barat, Indonesia</li></ul><h3>Jalur Komunikasi</h3><ul><li><strong>Pendaftaran anggota baru</strong> — gunakan formulir online di <a href="/daftar-anggota">halaman pendaftaran anggota</a>. Setelah mendaftar, calon anggota akan dihubungi oleh Departemen Internal untuk verifikasi dan penempatan Pokja.</li><li><strong>Permohonan pendampingan kasus agraria</strong> — kirim ringkasan kasus (lokasi, pihak terlibat, kronologi, dokumen pendukung) ke email resmi. Akan ditangani oleh Departemen Advokasi dan Perjuangan Tani.</li><li><strong>Liputan media &amp; kerja sama publikasi</strong> — ajukan permohonan resmi via email dengan mencantumkan institusi, nama pewarta/perwakilan, serta topik peliputan atau kolaborasi. Akan ditangani oleh Departemen Pendidikan, Penelitian, dan Propaganda.</li><li><strong>Solidaritas dan donasi</strong> — untuk dukungan sumber daya atau solidaritas aksi, silakan konfirmasi terlebih dahulu via email agar SEPETAK dapat memberi rekening resmi dan peruntukan yang transparan.</li></ul><p><em>SEPETAK tidak memiliki hotline telepon resmi saat ini. Seluruh komunikasi resmi berjalan melalui email dan formulir di website.</em></p>',
                'status'       => 'published',
                'published_at' => now(),
                'author_id'    => $adminId,
            ],
```

---

## Edit #12

### old_string

```
            ['key' => 'contact_phone',    'value' => '+62 xxx xxxx xxxx',                                                                 'group' => 'contact'],
```

### new_string

```
            ['key' => 'contact_phone',    'value' => '',                                                                                   'group' => 'contact'],
```

---

## Edit #13

### old_string

```
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'body' => '<h2>Tentang SEPETAK</h2><p><strong>SEPETAK (Serikat Pekerja Tani Karawang)</strong> adalah organisasi massa berbasis pekerja tani dan nelayan yang bersifat terbuka di Kabupaten Karawang, Jawa Barat. SEPETAK didirikan melalui <strong>Kongres I pada 3–4 November 2007</strong> dan dideklarasikan pada <strong>10 Desember 2007</strong> di Karawang.</p><p>Sejak <strong>Kongres III tahun 2015</strong>, nama resmi organisasi berubah dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong>. Perubahan nama ini menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat atas alat produksi, sekaligus memperluas keanggotaan secara formal bagi pekerja tani penggarap, buruh tani, dan nelayan kecil pesisir.</p><h3>Konteks Kabupaten Karawang</h3><p>Karawang adalah daerah tingkat II di utara Jawa Barat, mencakup <strong>30 kecamatan, 297 desa, dan 12 kelurahan</strong> dengan luas daratan ±1.753,27 km² (3,73% luas Jawa Barat) dan wilayah laut ±4 mil × 57 km. Topografinya didominasi dataran rendah (&lt;200 mdpl); hanya tiga kecamatan di selatan yang berada pada ketinggian &gt;200 mdpl hingga lebih dari 1.000 mdpl. Karawang dikenal sebagai <strong>salah satu sentra padi nasional</strong> sekaligus kawasan industri yang berkembang sejak 1989.</p><p>Kombinasi ini membuat Karawang menghadapi tekanan agraria yang khas: di satu sisi ada sawah pangan dan pesisir nelayan, di sisi lain ada konversi lahan untuk zona industri, perumahan, dan infrastruktur. Konflik agraria di sini tidak selalu tampak sebagai konflik terbuka seperti di dataran tinggi, melainkan berlangsung melalui mekanisme pasar tanah dan praktik kepemilikan <em>absentee</em>.</p><h3>Sifat dan Bentuk Organisasi</h3><p>SEPETAK adalah <strong>organisasi tani berbasis massa dan bersifat terbuka</strong>. Kami tidak membatasi keanggotaan pada satu lapisan ekonomi tertentu — anggota SEPETAK datang dari berbagai lapisan sosial pedesaan Karawang, termasuk buruh tani (<em>landless-peasant</em>), petani penyakap (<em>landless-tenant</em>), petani pemilik lahan kecil, serta warga yang menggantungkan hidup dari laut, perikanan, dan pariwisata pesisir.</p><h3>Tujuan Organisasi</h3><ol><li>Mewujudkan masyarakat Karawang yang <strong>demokratis, berkeadilan sosial, dan berkedaulatan</strong>.</li><li>Membebaskan pekerja tani dari segala bentuk penindasan dan pembodohan untuk mencapai <strong>kesetaraan dalam bidang ekonomi, sosial, budaya, hukum, dan politik</strong>.</li><li>Memperkuat posisi pekerja tani dalam menentukan <strong>kebijakan politik, hukum, sosial, dan budaya</strong> demi terwujudnya kesejahteraan yang adil, makmur, dan merata.</li></ol><h3>Pokok-pokok Perjuangan</h3><ol><li>Terlibat aktif dan memimpin perjuangan pekerja tani dalam memperjuangkan hak-haknya.</li><li>Aktif dalam membangun, mendorong, dan memajukan kesadaran pekerja tani dan organisasi tani.</li><li>Mendorong dan memajukan kesejahteraan pekerja tani.</li><li>Aktif dalam kerja-kerja solidaritas dan perjuangan Rakyat tertindas lainnya.</li></ol><h3>Jaringan Perjuangan</h3><ul><li><strong>Nasional</strong>: SEPETAK adalah anggota <a href="https://kpa.or.id" target="_blank" rel="noopener"><strong>Konsorsium Pembaruan Agraria (KPA)</strong></a> — konsorsium yang menghimpun 173 organisasi gerakan tani, masyarakat adat, dan nelayan.</li><li><strong>Daerah</strong>: SEPETAK kerap dipercaya sebagai pelopor aliansi lintas sektor di Karawang. <strong>ALIANSI PERAK</strong> (Aliansi Pergerakan Rakyat Karawang) — gabungan SEPETAK dan beberapa serikat buruh — pernah menjadi warna kuat dalam kampanye isu agraria, sosial budaya, dan tekanan politik kepada pemerintah daerah.</li><li><strong>Lintas ormas lokal</strong>: SEPETAK juga menjalin relasi dengan berbagai LSM dan ormas Karawang seperti <strong>GMBI</strong> (Gerakan Masyarakat Bawah Indonesia) dan <strong>LMP</strong> (Laskar Merah Putih) melalui proses konsolidasi gagasan yang panjang.</li></ul>',
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
```

### new_string

```
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'body' => TentangKamiPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
```

---

## Edit #14

### old_string

```
        // 8. Sample Pages
        $pagesData = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'body' => TentangKamiPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Visi dan Misi',
                'slug' => 'visi-misi',
                'body' => '<h2>Visi</h2><p><strong>“Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian.”</strong></p><p>Terwujudnya reforma agraria sejati, kedaulatan pangan, dan kesejahteraan pekerja tani serta nelayan Karawang yang berkeadilan sosial, berdaulat secara ekonomi, dan berkelanjutan secara ekologis.</p><h2>Program Perjuangan — TANI MOTEKAR</h2><p><strong>TANI MOTEKAR</strong> adalah program tuntutan dan platform perjuangan SEPETAK yang dirumuskan pada <strong>Kongres II tahun 2012</strong>. Akronim ini menyatakan lima pilar yang harus dikuasai pekerja tani agar kemandirian dan kesejahteraan benar-benar berpijak pada kontrol produksi sendiri:</p><ol><li><strong>T — Tanah</strong>: pokok dasar perjuangan; alat produksi utama yang harus dikuasai dan didistribusikan secara adil kepada pekerja tani.</li><li><strong>I — Infrastruktur</strong>: jalan desa, irigasi teknis, dan fasilitas pasca-panen sebagai prasyarat produksi pertanian rakyat yang efisien.</li><li><strong>M — Modal</strong>: bukan sekadar uang, tetapi seluruh input pertanian — benih, pupuk, dan alat-mesin pertanian — yang harus berada dalam kontrol pekerja tani, bukan korporasi.</li><li><strong>T — Teknologi</strong>: teknologi tepat guna yang meningkatkan produktivitas tanpa membuat pekerja tani tergantung pada pasokan eksternal.</li><li><strong>A — Akses Pasar</strong>: rantai pemasaran produk pertanian dan olahan lanjutan yang memutus praktik tengkulak eksploitatif melalui model pertukaran antar-organisasi di basis massa.</li></ol><p><em>TANI MOTEKAR sebagai jalan menuju Industrialisasi Pertanian</em> — industri yang diselenggarakan di sektor pertanian pedesaan, berlandaskan partisipasi penuh masyarakat, ketersediaan bahan baku lokal, kelestarian lingkungan, dan pengabdian pada kepentingan publik. Dengan begitu, pengembangan industri di Karawang tidak lagi semata-mata berarti <em>konversi sawah menjadi pabrik</em>, melainkan penguatan produksi pertanian rakyat hingga hilirnya.</p><h2>Misi</h2><ul><li>Memperjuangkan <strong>redistribusi tanah</strong> bagi pekerja tani tak bertanah dan penyelesaian konflik agraria struktural di Karawang.</li><li>Mendampingi pekerja tani dan nelayan dalam sengketa agraria, klaim kawasan hutan Perhutani, tuntutan ganti rugi gagal tanam, hingga kriminalisasi anggota.</li><li>Membangun kesadaran hukum agraria dan pendidikan kritis anggota melalui <strong>sekolah tani, diskusi dusun, dan propaganda internal</strong>.</li><li>Mendorong kebijakan pertanian dan perikanan daerah yang berpihak pada pekerja tani kecil dan nelayan tradisional — termasuk intervensi atas Perda RTRW dan perencanaan infrastruktur.</li><li>Membangun <strong>aliansi lintas sektor</strong> bersama serikat buruh, mahasiswa, dan gerakan masyarakat sipil (ALIANSI PERAK, KPA, dll.) untuk memperkuat gerakan agraria nasional.</li><li>Membangun model <strong>pertanian kolektif</strong> di desa-desa basis sebagai strategi mengakumulasi surplus produksi bagi pengambilalihan bertahap tanah <em>absentee</em>.</li><li>Menyediakan <strong>advokasi pelayanan publik</strong> bagi anggota — mulai dari pendampingan pelayanan PLN, layanan kesehatan (Jamkesda/Jamkesmas ke RSUD Karawang), hingga pendaftaran hak atas tanah — sebagai wujud nyata keberpihakan organisasi pada kesejahteraan anggota.</li></ul>',
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Sejarah SEPETAK',
                'slug' => 'sejarah',
                'body' => '<h2>Sejarah Singkat SEPETAK</h2><h3>Latar: Gerakan Tani Karawang Sebelum SEPETAK</h3><p>Gerakan tani di Karawang menggeliat kembali pada era pasca reformasi 1998. Pada momen itu, gagasan-gagasan progresif kaum muda mulai masuk ke pedesaan Karawang melalui dua jalur utama:</p><ul><li><strong>Serikat Tani Nasional (STN)</strong> — mengawali advokasi kasus tanah <em>Kuta Tandingan</em> di Kecamatan Telukjambe Barat dan mengorganisir petani di Desa Karang Jaya, Kecamatan Pedes. Advokasi ini sempat terhenti karena represi aparat dan perubahan situasi lokal, namun berhasil menyisakan pengalaman dan kader awal.</li><li><strong>NGO Duta Tani Karawang</strong> — fokus awalnya bukan pada konflik agraria, melainkan pada advokasi produksi pertanian (termasuk pengendalian hama terpadu). Dari Duta Tani inilah kemudian lahir <strong>Dewan Tani Karawang</strong>, sebuah organisasi tani lokal yang berbasis di Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya. Dewan Tani Karawang akhirnya pecah karena perbedaan pandangan internal, terutama soal penggabungan dengan organ tani nasional.</li></ul><p>Dari dua titik masuk berbeda ini, gagasan gerakan tani terus bergulir hingga akhirnya mengkristal menjadi <strong>Serikat Petani Karawang (SEPETAK)</strong>.</p><h3>Kongres I — 3–4 November 2007</h3><p>Kongres pertama diadakan di Karawang dan menghasilkan <strong>deklarasi pembentukan organisasi pada 10 Desember 2007</strong>. Basis awal adalah lima desa di Kecamatan <em>Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya</em> — wilayah yang sebelumnya menjadi basis Dewan Tani Karawang. Kongres I menetapkan Anggaran Dasar dan struktur organisasi: Kongres, Dewan Tani, Dewan Pimpinan Tani Kabupaten (DPTK), Dewan Pimpinan Tani Desa (DPTD), dan Kelompok Kerja (Pokja).</p><h3>Kongres II — 2012</h3><p>Kongres II merumuskan dua capaian strategis:</p><ol><li><strong>TANI MOTEKAR</strong> (Tanah, Infrastruktur, Modal, Teknologi, Akses Pasar) sebagai program tuntutan dan platform perjuangan.</li><li><strong>“Bangun Industrialisasi Pertanian”</strong> sebagai program perjuangan jangka panjang.</li></ol><p>Pasca Kongres II, SEPETAK melakukan pemetaan wilayah dan menetapkan <strong>lima kategori wilayah rawan konflik agraria</strong> di Karawang: (a) masyarakat desa hutan, (b) eks Tegalwaru landen, (c) sekitar zona industri, (d) wilayah pangan, dan (e) pesisir. Pemetaan ini menjadi basis prioritas organisasi dalam membangun Pokja dan DPTD baru.</p><h3>Kongres III — 2015</h3><p>Kongres III memutuskan <strong>perubahan nama resmi</strong> dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong>. Perubahan ini menegaskan posisi anggota sebagai <em>pekerja</em> yang berdaulat atas alat produksi, bukan sekadar profesi turunan. Kongres III juga memperluas keanggotaan secara formal bagi pekerja tani penggarap (<em>landless-tenant</em>) dan nelayan kecil pesisir.</p><h3>Rangkaian Aksi Perjuangan</h3><ul><li><strong>Penolakan Penambangan Pasir Laut Tanjung Pakis (2008–2009)</strong> — advokasi nelayan, pedagang wisata pantai, dan warga terdampak; berhasil menghentikan kegiatan tambang dan melahirkan basis SEPETAK baru di pesisir utara.</li><li><strong>Penolakan Penambangan Batu Andesit Tegalwaru (2009–2010)</strong> — perlawanan terhadap industri ekstraktif di dataran selatan Karawang.</li><li><strong>Aksi Ganti Rugi Gagal Tanam Cilamaya (2011)</strong> — memperjuangkan kompensasi bagi anggota yang gagal panen.</li><li><strong>Aksi Lintas Sektor &amp; Solidaritas</strong> — SEPETAK rutin terlibat dalam aksi bersama serikat buruh dan mahasiswa di Karawang untuk tuntutan ekonomis seperti infrastruktur desa, perbaikan irigasi, dan bantuan musim kekeringan.</li><li><strong>Sengketa Teluk Jambe (2013)</strong> — gugatan perdata 350 hektare lahan tiga desa melawan PT Sumber Air Mas Pratama (SAMP, diakuisisi Agung Podomoro Land) hingga Mahkamah Agung.</li><li><strong>Aksi Tol Jakarta–Cikampek (11 Juli 2013)</strong> — pekerja tani SEPETAK menutup akses tol sebagai respons putusan yang tidak berpihak.</li><li><strong>Penolakan Revisi Perda RTRW Karawang</strong> — intervensi atas rencana perubahan tata ruang, khususnya perubahan status kawasan Cilamaya Wetan menjadi kawasan perkotaan yang mengancam lahan anggota.</li><li><strong>Advokasi Pelayanan Publik</strong> — pendampingan anggota dalam urusan PLN, rujukan layanan Jamkesda/Jamkesmas ke RSUD Karawang (aksi 2012 yang kemudian melahirkan hubungan kelembagaan dengan RSUD).</li><li><strong>Aksi BPN Karawang (27 Juli 2023)</strong> — ribuan pekerja tani bersama LBH Arya Mandalika mendaftarkan <strong>88 bidang tanah di 13 desa</strong> yang diklaim sebagai kawasan hutan Perhutani tanpa dokumen utuh.</li><li><strong>Pernyataan Sikap 1 Agustus 2023</strong> — melawan kriminalisasi anggota oleh FORKOPIMDA Karawang pasca aksi 27 Juli.</li><li><strong>Hari Tani Nasional 2025 (24 September 2025)</strong> — SEPETAK bersama 139 organisasi tani-nelayan di bawah KPA menuntut 24 agenda perbaikan struktural agraria di Jakarta.</li></ul><h3>Eksperimen Pertanian Kolektif Telukjaya</h3><p>Sebagai implementasi langsung dari TANI MOTEKAR, SEPETAK pernah membangun model <em>pertanian kolektif</em> di <strong>Desa Telukjaya, Kecamatan Pakisjaya</strong>. Skema kerjanya:</p><ol><li>Organisasi menyewa lahan dari tuan tanah <em>absentee</em>.</li><li>Organisasi menyediakan seluruh input produksi — benih, pupuk, obat-obatan, serta alat dan mesin pertanian — sehingga anggota tidak terjerat tengkulak.</li><li>Surplus produksi dikumpulkan untuk <strong>membeli tanah absentee secara bertahap</strong> dan menjadi aset kolektif organisasi.</li><li>Paralel, SEPETAK juga mengadvokasi pemerintah daerah untuk turut mengambil alih tanah absentee sebagai objek reforma agraria.</li></ol><p>Eksperimen ini sempat terhambat karena persoalan internal — sebagian pengurus DPTD setempat memakai hasil produksi untuk kepentingan pribadi, dan organisasi mengambil sikap tegas memberhentikan pihak yang bersangkutan. Pengalaman ini menjadi pelajaran penting tentang perlunya <strong>kaderisasi yang kuat, transparansi keuangan, dan kontrol kolektif</strong> pada setiap skema produksi.</p><p><em>Hingga hari ini, perjuangan SEPETAK tidak pernah berhenti.</em> <strong>Tanah untuk pekerja tani. Laut untuk nelayan. Keadilan untuk semua.</strong></p>',
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Struktur Organisasi',
                'slug' => 'struktur-organisasi',
                'body' => '<h2>Struktur Organisasi SEPETAK</h2><p>Struktur SEPETAK disusun berdasarkan Anggaran Dasar hasil Kongres I dan disempurnakan pada kongres berikutnya. Struktur dirancang agar pengambilan keputusan dilakukan secara kolektif dari tingkat dusun hingga kabupaten — <em>dari bawah ke atas</em>.</p><h3>1. Kongres</h3><p>Forum tertinggi pembuat dan pengambil keputusan. Dilaksanakan <strong>sekurang-kurangnya tiga tahun sekali</strong>. Peserta terdiri dari seluruh jajaran pimpinan SEPETAK di setiap tingkat struktur dan anggota yang mendapat rekomendasi Dewan Pimpinan.</p><h3>2. Dewan Tani</h3><p>Pembuat keputusan tertinggi setelah Kongres. Rapat dilaksanakan <strong>minimal satu kali dalam enam bulan</strong>. Anggota Dewan Tani:</p><ul><li>Seluruh jajaran Dewan Pimpinan Tani Kabupaten (DPTK).</li><li>Ketua atau perwakilan Pimpinan Tani Desa.</li><li>Anggota SEPETAK yang mendapat rekomendasi Dewan Pimpinan Tani.</li></ul><h3>3. Dewan Pimpinan Tani Kabupaten (DPTK)</h3><p>Badan pimpinan tertinggi di bawah Dewan Tani. Dipilih, diangkat, dan diberhentikan oleh Kongres untuk <strong>masa jabatan tiga tahun</strong>. DPTK adalah pimpinan harian dan pembuat keputusan harian organisasi.</p><p>Komposisi DPTK:</p><ul><li>Ketua Umum</li><li>Sekretaris Umum</li><li>Ketua Departemen dan staf, terdiri dari:<ol><li><strong>Departemen Internal</strong> — pengorganisasian, kaderisasi, keanggotaan.</li><li><strong>Departemen Advokasi dan Perjuangan Tani</strong> — advokasi hukum, kampanye, aksi massa.</li><li><strong>Departemen Dana dan Usaha</strong> — pengelolaan keuangan organisasi dan usaha produktif.</li><li><strong>Departemen Pendidikan, Penelitian, dan Propaganda</strong> — sekolah tani, riset, publikasi.</li></ol></li></ul><p>Seluruh kerja harian departemen dikoordinasi dan dikontrol oleh Sekretaris Umum.</p><h3>4. Dewan Pimpinan Tani Desa (DPTD)</h3><p>Struktur organisasi tertinggi di tingkat desa. Dibentuk melalui Konferensi atau Musyawarah Desa untuk <strong>masa jabatan dua tahun</strong>. Syarat pembentukan DPTD: <strong>minimal tiga Pokja telah terbentuk</strong> di desa tersebut. Komposisi: Ketua, Sekretaris, dan staf departemen.</p><h3>5. Kelompok Kerja (Pokja)</h3><p>Unit organisasi terkecil, terdiri dari <strong>minimal lima anggota SEPETAK</strong> dan berkedudukan di wilayah kerja 1–2 dusun. Pokja dipimpin seorang Koordinator yang dipilih oleh anggota. Tugas utama Pokja: mengkoordinasikan kerja anggota di wilayahnya dan memperluas keanggotaan ke dusun-dusun di sekitarnya.</p><h3>Dua Jalur Pengorganisasian</h3><p>Dalam praktik, SEPETAK menempuh <strong>dua jalur</strong> untuk membangun basis massa di sebuah wilayah baru:</p><ol><li><strong>Rekrutmen individu langsung</strong> — kader melakukan pendekatan satu-persatu hingga terkumpul 3–5 calon anggota di sebuah dusun, lalu membentuk Pokja.</li><li><strong>Pintu masuk konflik</strong> — pendekatan dimulai dari persoalan nyata yang dialami warga (konflik tanah, ganti rugi gagal tanam, tambang, dll.). Advokasi kolektif menjadi pengikat solidaritas yang kemudian dikonsolidasikan menjadi Pokja dan DPTD.</li></ol><p><em>Contoh historis:</em> Pembentukan Pokja di <strong>Dusun Cimahi, Desa Cikarang, Kecamatan Cilamaya Wetan pada 14 Februari 2012</strong> merupakan salah satu capaian penting yang menggabungkan kedua jalur di atas.</p><h3>Alur Rekruitmen Formal</h3><ol><li>Individu bergabung sebagai <strong>Anggota SEPETAK</strong>.</li><li>Jika ada <strong>3–5 anggota</strong> di satu dusun, bentuk <strong>Pokja</strong> dengan koordinator.</li><li>Jika terbentuk <strong>minimal tiga Pokja</strong> di satu desa, selenggarakan <strong>Konferensi / Musyawarah Desa</strong> untuk membentuk <strong>DPTD</strong>.</li><li>DPTD aktif memperluas basis ke dusun dan desa tetangga.</li></ol><h3>Catatan Kaderisasi</h3><p>Salah satu tantangan terberat organisasi adalah menghasilkan <strong>kader dari basis tani itu sendiri</strong>. Menjadi pengorganisir berarti memberikan waktu, tenaga, biaya, dan kadang keselamatan pribadi — sebuah beban yang sulit dipikul dari sektor tani yang ekonominya paling rentan. Karena itu SEPETAK terus menempatkan kaderisasi (sekolah tani, diskusi dusun, pendidikan publik) sebagai investasi jangka panjang organisasi.</p>',
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Wilayah Kerja & Pemetaan Konflik',
                'slug' => 'wilayah-kerja',
                'body' => '<h2>Wilayah Kerja dan Pemetaan Konflik Agraria</h2><p>Karawang adalah salah satu daerah paling padat persoalan agraria di Jawa Barat: wilayah dataran rendah yang sejak 1989 terus diubah menjadi zona industri, perumahan, dan jalan tol, sementara di saat yang sama tetap menjadi sentra padi nasional. Dalam laporan Konsorsium Pembaruan Agraria (KPA) 2011, terjadi <strong>163 konflik agraria</strong> di Indonesia — 60% di sektor perkebunan, 22% kehutanan, 13% infrastruktur, 4% tambang, 1% pesisir — dengan korban mencapai <strong>22 jiwa, 69.975 KK, dan 472.048 hektare lahan</strong>. Karawang berada dalam pusaran konflik ini.</p><p>Pasca Kongres II tahun 2012, SEPETAK menetapkan <strong>lima kategori wilayah rawan konflik agraria</strong> sebagai peta prioritas pengorganisasian:</p><h3>1. Wilayah Masyarakat Desa Hutan</h3><ul><li><strong>Pihak yang terlibat</strong>: Perum Perhutani, perusahaan tambang, swasta, militer, dan pemerintah.</li><li><strong>Jenis konflik</strong>: perampasan tanah, sengketa tapal batas, dan klaim kawasan wisata.</li></ul><h3>2. Wilayah Eks Tegalwaru Landen (Karawang Selatan)</h3><ul><li><strong>Pihak yang terlibat</strong>: swasta, operator zona industri, Perhutani, pemerintah.</li><li><strong>Jenis konflik</strong>: perampasan tanah dan sengketa tapal batas.</li></ul><h3>3. Wilayah Sekitar Zona Industri</h3><ul><li><strong>Pihak yang terlibat</strong>: pengembang zona industri, perumahan, pemerintah.</li><li><strong>Jenis konflik</strong>: perampasan tanah — konversi sawah produktif menjadi kawasan industri dan perumahan.</li></ul><h3>4. Wilayah Pangan (Sabuk Sawah Karawang)</h3><ul><li><strong>Pihak yang terlibat</strong>: tuan tanah absentee dan tuan tanah lokal.</li><li><strong>Jenis konflik</strong>: kepemilikan tanah absentee yang menjerat petani penggarap ke dalam sistem penyakapan (<em>sharecropping tenancy</em>) yang eksploitatif.</li></ul><h3>5. Wilayah Pesisir</h3><ul><li><strong>Pihak yang terlibat</strong>: Perhutani, swasta, perusahaan tambang pasir laut, pemerintah.</li><li><strong>Jenis konflik</strong>: kepemilikan absentee dan perampasan ruang hidup nelayan serta pedagang pariwisata pantai.</li></ul><h3>Strategi Pengorganisasian Sesuai Peta</h3><p>Pemetaan di atas menjadi pedoman SEPETAK dalam menentukan di desa mana Pokja perlu dibangun lebih dahulu, advokasi apa yang dijalankan sesuai karakter konflik, dan siapa mitra yang relevan. Lima desa basis awal di <strong>Cilamaya Kulon, Cilamaya Wetan, dan Pakisjaya</strong> masuk dalam wilayah pangan-pesisir; sementara wilayah selatan (Tegalwaru, Pangkalan) masuk kategori hutan &amp; ekstraktif.</p><p><em>Peta ini terus diperbarui</em> — setiap DPTD baru wajib melakukan pemetaan konflik agraria di desanya sebagai dasar kerja organisasi.</p>',
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Kontak',
                'slug' => 'kontak',
                'body' => '<h2>Hubungi Kami</h2><p>Untuk informasi lebih lanjut, permohonan pendampingan, kolaborasi lintas organisasi, atau kebutuhan data publik, silakan hubungi sekretariat SEPETAK:</p><ul><li><strong>Email resmi:</strong> info@sepetak.org</li><li><strong>Website:</strong> https://sepetak.org</li><li><strong>Alamat sekretariat:</strong> Kabupaten Karawang, Jawa Barat, Indonesia</li></ul><h3>Jalur Komunikasi</h3><ul><li><strong>Pendaftaran anggota baru</strong> — gunakan formulir online di <a href="/daftar-anggota">halaman pendaftaran anggota</a>. Setelah mendaftar, calon anggota akan dihubungi oleh Departemen Internal untuk verifikasi dan penempatan Pokja.</li><li><strong>Permohonan pendampingan kasus agraria</strong> — kirim ringkasan kasus (lokasi, pihak terlibat, kronologi, dokumen pendukung) ke email resmi. Akan ditangani oleh Departemen Advokasi dan Perjuangan Tani.</li><li><strong>Liputan media &amp; kerja sama publikasi</strong> — ajukan permohonan resmi via email dengan mencantumkan institusi, nama pewarta/perwakilan, serta topik peliputan atau kolaborasi. Akan ditangani oleh Departemen Pendidikan, Penelitian, dan Propaganda.</li><li><strong>Solidaritas dan donasi</strong> — untuk dukungan sumber daya atau solidaritas aksi, silakan konfirmasi terlebih dahulu via email agar SEPETAK dapat memberi rekening resmi dan peruntukan yang transparan.</li></ul><p><em>SEPETAK tidak memiliki hotline telepon resmi saat ini. Seluruh komunikasi resmi berjalan melalui email dan formulir di website.</em></p>',
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
        ];
```

### new_string

```
        // 8. Sample Pages (isi dari kelas *PageContent — selaras docs/ADART SEPETAK.docx.md)
        $pagesData = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'body' => TentangKamiPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Visi dan Misi',
                'slug' => 'visi-misi',
                'body' => VisiMisiPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Sejarah SEPETAK',
                'slug' => 'sejarah',
                'body' => SejarahPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Struktur Organisasi',
                'slug' => 'struktur-organisasi',
                'body' => StrukturOrganisasiPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Wilayah Kerja & Pemetaan Konflik',
                'slug' => 'wilayah-kerja',
                'body' => WilayahKerjaPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Kontak',
                'slug' => 'kontak',
                'body' => KontakPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Anggaran Dasar dan Anggaran Rumah Tangga',
                'slug' => 'anggaran-dasar-dan-rumah-tangga',
                'body' => AdArtPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
        ];
```

---

## Edit #15

### old_string

```
            ['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang) adalah organisasi massa berbasis pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat, yang memperjuangkan TANI MOTEKAR (Tanah, Infrastruktur, Modal, Teknologi, Akses Pasar) sejak 10 Desember 2007.', 'group' => 'general'],
```

### new_string

```
            ['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang) adalah organisasi tani berbasis massa di Kabupaten Karawang, Jawa Barat, didirikan pada Kongres I (3–4 November 2007; deklarasi 10 Desember 2007), yang memperjuangkan TANI MOTEKAR (Tanah, Infrastruktur, Modal, Teknologi, Akses Pasar) dan reforma agraria.', 'group' => 'general'],
```

---

## Edit #16

### old_string

```
                'body' => '<p>Selamat datang di website resmi <strong>SEPETAK — Serikat Pekerja Tani Karawang</strong>. Melalui platform ini kami berbagi informasi, berita, dan perkembangan terkini seputar perjuangan hak-hak pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat.</p><p>SEPETAK berdiri sejak Kongres I pada 3–4 November 2007 dan dideklarasikan pada 10 Desember 2007 di Karawang. Sejak Kongres III, nama organisasi resmi berubah dari <em>Serikat Petani Karawang</em> menjadi <strong>Serikat Pekerja Tani Karawang</strong> untuk menegaskan bahwa kami adalah organisasi pekerja tani yang berdaulat, bukan sekadar kelompok profesi.</p><p>Website ini menjadi ruang digital bagi seluruh anggota dan simpatisan untuk tetap terhubung dengan gerakan reforma agraria yang kami perjuangkan bersama.</p>',
```

### new_string

```
                'body' => '<p>Selamat datang di website resmi <strong>SEPETAK — Serikat Pekerja Tani Karawang</strong>. Melalui platform ini kami berbagi informasi, berita, dan perkembangan terkini seputar perjuangan hak-hak pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat.</p><p>Organisasi berakar pada Kongres I (3–4 November 2007; deklarasi 10 Desember 2007) sebagai <em>Serikat Petani Karawang</em>. Pada <strong>Kongres IV tahun 2020</strong> nama resmi menjadi <strong>Serikat Pekerja Tani Karawang</strong> bersama pembaruan Anggaran Dasar dan Anggaran Rumah Tangga — menegaskan subjek perjuangan kami sebagai pekerja tani dan nelayan yang berdaulat, bukan sekadar label profesi.</p><p>Website ini menjadi ruang digital bagi seluruh anggota dan simpatisan untuk tetap terhubung dengan gerakan reforma agraria yang kami perjuangkan bersama.</p>',
```

---

## Edit #17

### old_string

```
            ['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang) adalah organisasi tani berbasis massa di Kabupaten Karawang, Jawa Barat, didirikan pada Kongres I (3–4 November 2007; deklarasi 10 Desember 2007), yang memperjuangkan TANI MOTEKAR (Tanah, Infrastruktur, Modal, Teknologi, Akses Pasar) dan reforma agraria.', 'group' => 'general'],
```

### new_string

```
            ['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang sejak Kongres IV/2020; sebelumnya Serikat Petani Karawang) adalah organisasi tani berbasis massa di Kabupaten Karawang, Jawa Barat — Kongres I 3–4 November 2007 (deklarasi 10 Desember 2007); memperjuangkan TANI MOTEKAR (Kongres II, 2010) dan reforma agraria.', 'group' => 'general'],
```

---

## Edit #18

### old_string

```
'body' => '<p>Selamat datang di website resmi <strong>SEPETAK — Serikat Pekerja Tani Karawang</strong>. Melalui platform ini kami berbagi informasi, berita, dan perkembangan terkini seputar perjuangan hak-hak pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat.</p><p>Organisasi berakar pada Kongres I (3–4 November 2007; deklarasi 10 Desember 2007) sebagai <em>Serikat Petani Karawang</em>. Pada <strong>Kongres IV tahun 2020</strong> nama resmi menjadi <strong>Serikat Pekerja Tani Karawang</strong> bersama pembaruan Anggaran Dasar dan Anggaran Rumah Tangga — menegaskan subjek perjuangan kami sebagai pekerja tani dan nelayan yang berdaulat, bukan sekadar label profesi.</p><p>Website ini menjadi ruang digital bagi seluruh anggota dan simpatisan untuk tetap terhubung dengan gerakan reforma agraria yang kami perjuangkan bersama.</p>',
```

### new_string

```
'body' => '<p>Selamat datang di website resmi <strong>SEPETAK — Serikat Pekerja Tani Karawang</strong>. Melalui platform ini kami berbagi informasi, berita, dan perkembangan terkini seputar perjuangan hak-hak pekerja tani dan nelayan di Kabupaten Karawang, Jawa Barat.</p><p>Organisasi berakar pada Kongres I (3–4 November 2007; deklarasi 10 Desember 2007) sebagai <em>Serikat Petani Karawang</em>. Pada <strong>Kongres IV, 31 Oktober–1 November 2020</strong>, nama resmi menjadi <strong>Serikat Pekerja Tani Karawang</strong> bersama pembaruan Anggaran Dasar dan Anggaran Rumah Tangga — menegaskan subjek perjuangan kami sebagai pekerja tani dan nelayan yang berdaulat, bukan sekadar label profesi.</p><p>Website ini menjadi ruang digital bagi seluruh anggota dan simpatisan untuk tetap terhubung dengan gerakan reforma agraria yang kami perjuangkan bersama.</p>',
```

---

## Edit #19

### old_string

```
['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang sejak Kongres IV/2020; sebelumnya Serikat Petani Karawang) adalah organisasi tani berbasis massa di Kabupaten Karawang, Jawa Barat — Kongres I 3–4 November 2007 (deklarasi 10 Desember 2007); memperjuangkan TANI MOTEKAR (Kongres II, 2010) dan reforma agraria.', 'group' => 'general'],
```

### new_string

```
['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang sejak Kongres IV 31 Okt–1 Nov 2020; sebelumnya Serikat Petani Karawang) adalah organisasi tani berbasis massa di Kabupaten Karawang, Jawa Barat — Kongres I 3–4 November 2007 (deklarasi 10 Desember 2007); visi Kongres III 25–26 April 2016; TANI MOTEKAR Kongres II 10–11 Desember 2010; reforma agraria.', 'group' => 'general'],
```

---

## Edit #20

### old_string

```
['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang sejak Kongres IV 31 Okt–1 Nov 2020; sebelumnya Serikat Petani Karawang) adalah organisasi tani berbasis massa di Kabupaten Karawang, Jawa Barat — Kongres I 3–4 November 2007 (deklarasi 10 Desember 2007); visi Kongres III 25–26 April 2016; TANI MOTEKAR Kongres II 10–11 Desember 2010; reforma agraria.', 'group' => 'general'],
```

### new_string

```
['key' => 'site_description', 'value' => 'SEPETAK (Serikat Pekerja Tani Karawang sejak Kongres IV 31 Okt–1 Nov 2020; sebelumnya Serikat Petani Karawang): organisasi tani berbasis massa di Karawang — Kongres I 3–4 Nov 2007; TANI MOTEKAR (Kongres II 10–11 Des 2010); visi (Kongres III 25–26 Apr 2016); reforma agraria.', 'group' => 'general'],
```

---

## Edit #21

### old_string

```
        foreach ($pagesData as $pageData) {
            try {
                Page::updateOrCreate(['slug' => $pageData['slug']], $pageData);
            } catch (\Exception $e) {
                $this->command->warn("Page '{$pageData['slug']}' skipped: ".$e->getMessage());
            }
        }

        $this->command->info('DatabaseSeeder completed successfully.');
```

### new_string

```
        foreach ($pagesData as $pageData) {
            try {
                Page::updateOrCreate(['slug' => $pageData['slug']], $pageData);
            } catch (\Exception $e) {
                $this->command->warn("Page '{$pageData['slug']}' skipped: ".$e->getMessage());
            }
        }

        // 9. Kasus advokasi organisasi (timeline docs/ADVOCACY_TIMELINE_SEPETAK.md)
        try {
            $this->call(AdvocacyOrganizationCasesSeeder::class);
        } catch (\Exception $e) {
            $this->command->warn('AdvocacyOrganizationCasesSeeder skipped: '.$e->getMessage());
        }

        $this->command->info('DatabaseSeeder completed successfully.');
```

---

## Edit #22

### old_string

```
        // 9. Kasus advokasi organisasi (timeline docs/ADVOCACY_TIMELINE_SEPETAK.md)
        try {
            $this->call(AdvocacyOrganizationCasesSeeder::class);
        } catch (\Exception $e) {
            $this->command->warn('AdvocacyOrganizationCasesSeeder skipped: '.$e->getMessage());
        }

        $this->command->info('DatabaseSeeder completed successfully.');
```

### new_string

```
        // 9. Kasus advokasi organisasi (timeline docs/ADVOCACY_TIMELINE_SEPETAK.md)
        try {
            $this->call(AdvocacyOrganizationCasesSeeder::class);
        } catch (\Exception $e) {
            $this->command->warn('AdvocacyOrganizationCasesSeeder skipped: '.$e->getMessage());
        }

        // 10. Program advokasi organisasi (docs/ADVOCACY_PROGRAMS_RESEARCH.md)
        try {
            $this->call(AdvocacyProgramsOrganizationSeeder::class);
        } catch (\Exception $e) {
            $this->command->warn('AdvocacyProgramsOrganizationSeeder skipped: '.$e->getMessage());
        }

        $this->command->info('DatabaseSeeder completed successfully.');
```

---

## Edit #23

### old_string

```
        // 8. Sample Pages (isi dari kelas *PageContent — selaras docs/ADART SEPETAK.docx.md)
        $pagesData = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'body' => TentangKamiPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Visi dan Misi',
                'slug' => 'visi-misi',
                'body' => VisiMisiPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Sejarah SEPETAK',
                'slug' => 'sejarah',
                'body' => SejarahPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Struktur Organisasi',
                'slug' => 'struktur-organisasi',
                'body' => StrukturOrganisasiPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Wilayah Kerja & Pemetaan Konflik',
                'slug' => 'wilayah-kerja',
                'body' => WilayahKerjaPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Kontak',
                'slug' => 'kontak',
                'body' => KontakPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
            [
                'title' => 'Anggaran Dasar dan Anggaran Rumah Tangga',
                'slug' => 'anggaran-dasar-dan-rumah-tangga',
                'body' => AdArtPageContent::body(),
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $adminId,
            ],
        ];
        foreach ($pagesData as $pageData) {
            try {
                Page::updateOrCreate(['slug' => $pageData['slug']], $pageData);
            } catch (\Exception $e) {
                $this->command->warn("Page '{$pageData['slug']}' skipped: ".$e->getMessage());
            }
        }

        // 9. Kasus advokasi organisasi (timeline docs/ADVOCACY_TIMELINE_SEPETAK.md)
```

### new_string

```
        // 8. Halaman profil publik (Tentang, AD/ART, kontak, …) — satu sumber: PublicProfilePagesSeeder
        try {
            $this->call(PublicProfilePagesSeeder::class);
        } catch (\Exception $e) {
            $this->command->warn('PublicProfilePagesSeeder skipped: '.$e->getMessage());
        }

        // 9. Kasus advokasi organisasi (timeline docs/ADVOCACY_TIMELINE_SEPETAK.md)
```

---

## Edit #24

### old_string

```
use App\Models\Page;
use App\Models\Post;
```

### new_string

```
use App\Models\Post;
```

---

## Edit #25

### old_string

```
        // 10. Program advokasi organisasi (docs/ADVOCACY_PROGRAMS_RESEARCH.md)
        try {
            $this->call(AdvocacyProgramsOrganizationSeeder::class);
        } catch (\Exception $e) {
            $this->command->warn('AdvocacyProgramsOrganizationSeeder skipped: '.$e->getMessage());
        }

        $this->command->info('DatabaseSeeder completed successfully.');
```

### new_string

```
        // 10. Program advokasi organisasi (docs/ADVOCACY_PROGRAMS_RESEARCH.md)
        try {
            $this->call(AdvocacyProgramsOrganizationSeeder::class);
        } catch (\Exception $e) {
            $this->command->warn('AdvocacyProgramsOrganizationSeeder skipped: '.$e->getMessage());
        }

        // 11. AI: pool tips harian anggota (5× WIB, nonaktif sampai diaktifkan di Filament)
        try {
            $this->call(DailyMemberTipsArticleSeeder::class);
        } catch (\Exception $e) {
            $this->command->warn('DailyMemberTipsArticleSeeder skipped: '.$e->getMessage());
        }

        $this->command->info('DatabaseSeeder completed successfully.');
```

---

