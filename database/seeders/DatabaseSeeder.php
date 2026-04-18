<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRolesAndPermissions();
        $this->seedUsers();
        $this->seedCategories();
        $this->seedSiteSettings();
        $this->seedWelcomePost();

        // Deferred — each seeder guards against duplicate page slugs.
        $this->runOptional([
            PublicProfilePagesSeeder::class,
            TentangKamiContentUpdateSeeder::class,
            AdvocacyProgramsOrganizationSeeder::class,
            AdvocacyOrganizationCasesSeeder::class,
            DailyMemberTipsArticleSeeder::class,
            ArticleTopicSeeder::class,
        ]);
    }

    protected function runOptional(array $seeders): void
    {
        foreach ($seeders as $seeder) {
            if (class_exists($seeder)) {
                $this->call($seeder);
            }
        }
    }

    protected function seedRolesAndPermissions(): void
    {
        $permissions = [
            'manage-members',
            'manage-cases',
            'manage-advocacy',
            'manage-events',
            'manage-content',
            'manage-users',
            'manage-settings',
        ];

        foreach ($permissions as $p) {
            Permission::findOrCreate($p, 'web');
        }

        $superadmin = Role::findOrCreate('superadmin', 'web');
        $admin = Role::findOrCreate('admin', 'web');
        $operator = Role::findOrCreate('operator', 'web');
        $viewer = Role::findOrCreate('viewer', 'web');

        $superadmin->syncPermissions($permissions);
        $admin->syncPermissions($permissions);
        $operator->syncPermissions([
            'manage-members',
            'manage-cases',
            'manage-advocacy',
            'manage-events',
            'manage-content',
        ]);
        $viewer->syncPermissions([]);
    }

    protected function seedUsers(): void
    {
        /** @var User $admin */
        $admin = User::updateOrCreate(
            ['email' => 'admin@sepetak.org'],
            [
                'name' => 'Administrator SEPETAK',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $admin->syncRoles(['superadmin']);

        $redaksi = User::updateOrCreate(
            ['email' => 'redaksi@sepetak.org'],
            [
                'name' => 'Redaksi SEPETAK',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $redaksi->syncRoles(['operator']);

        $viewer = User::updateOrCreate(
            ['email' => 'publik@sepetak.org'],
            [
                'name' => 'Pengunjung Publik',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $viewer->syncRoles(['viewer']);
    }

    protected function seedCategories(): void
    {
        $defaults = [
            ['slug' => 'umum', 'name' => 'Umum'],
            ['slug' => 'agraria', 'name' => 'Agraria'],
            ['slug' => 'advokasi', 'name' => 'Advokasi'],
            ['slug' => 'organisasi', 'name' => 'Organisasi'],
            ['slug' => 'panduan-tips-anggota', 'name' => 'Panduan & Tips Anggota'],
        ];

        foreach ($defaults as $c) {
            Category::updateOrCreate(['slug' => $c['slug']], ['name' => $c['name']]);
        }
    }

    protected function seedSiteSettings(): void
    {
        $pairs = [
            ['setting_key' => 'site_name', 'setting_value' => 'SEPETAK (Serikat Pekerja Tani Karawang)', 'group_name' => 'umum'],
            ['setting_key' => 'site_tagline', 'setting_value' => 'Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian', 'group_name' => 'umum'],
            ['setting_key' => 'site_description', 'setting_value' => 'Serikat Pekerja Tani Karawang (SEPETAK): organisasi massa pekerja tani dan nelayan di Kabupaten Karawang. Tonggak Kongres I (2007), program TANI MOTEKAR (Kongres II, 2010), nama resmi Kongres IV (2020). Reforma agraria dan solidaritas kolektif.', 'group_name' => 'umum'],
            ['setting_key' => 'hero_intro', 'setting_value' => 'SEPETAK memperjuangkan reforma agraria sejati, meliputi akses tanah, air, dan benih bagi pekerja tani serta nelayan di wilayah pedesaan dan pesisir Kabupaten Karawang.', 'group_name' => 'umum'],
            ['setting_key' => 'contact_email', 'setting_value' => 'kontak@sepetak.org', 'group_name' => 'kontak'],
        ];

        foreach ($pairs as $p) {
            SiteSetting::updateOrCreate(
                ['setting_key' => $p['setting_key']],
                ['setting_value' => $p['setting_value'], 'group_name' => $p['group_name']]
            );
        }
    }

    protected function seedWelcomePost(): void
    {
        $admin = User::where('email', 'admin@sepetak.org')->first();
        if (! $admin) {
            return;
        }

        Post::firstOrCreate(
            ['slug' => 'selamat-datang-di-sepetak'],
            [
                'title' => 'Selamat Datang di SEPETAK',
                'excerpt' => 'Website resmi Serikat Pekerja Tani Karawang (SEPETAK): ruang solidaritas, advokasi, dan informasi agraria.',
                'body' => '<p>Selamat datang di sepetak.org. Kami adalah serikat pekerja tani yang berdiri sejak 2007 dan meresmikan nama <strong>Serikat Pekerja Tani Karawang</strong> pada Kongres IV (31 Oktober–1 November 2020).</p>',
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $admin->getKey(),
            ]
        );
    }
}
