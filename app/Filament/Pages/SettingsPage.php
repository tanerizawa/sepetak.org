<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    /** Harus cocok dengan salah satu label di AdminPanelProvider::navigationGroups. */
    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 90;

    protected static ?string $title = 'Pengaturan Situs';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $slug = 'pengaturan';

    public ?array $data = [];

    /**
     * Field schema: key => [label, group, type, helper]
     * type: text, textarea, email, tel, url
     */
    public const SCHEMA = [
        // Umum
        'site_name' => ['Nama Situs', 'umum', 'text', 'Digunakan untuk title, og:site_name, dan footer.'],
        'site_tagline' => ['Tagline / Moto', 'umum', 'text', 'Kalimat singkat identitas organisasi.'],
        'site_description' => ['Deskripsi Situs (meta)', 'umum', 'textarea', 'Ringkas (±160–240 karakter disarankan) untuk meta description, Open Graph, dan JSON-LD.'],
        'hero_intro' => ['Teks ringkas di bawah judul hero', 'umum', 'textarea', 'Satu atau dua kalimat di bawah headline beranda; tidak memakai field deskripsi meta.'],
        'founded_year' => ['Tahun Berdiri', 'umum', 'text', 'Contoh: 2007'],

        // Kontak
        'contact_email' => ['Email Kontak', 'kontak', 'email', 'Alamat email publik.'],
        'contact_phone' => ['Nomor Telepon / WA', 'kontak', 'tel', 'Format internasional, contoh: +628123456789'],
        'contact_address' => ['Alamat Sekretariat', 'kontak', 'textarea', 'Alamat sekretariat organisasi.'],

        // Sosial Media
        'social_instagram' => ['Instagram URL', 'sosial', 'url', 'Contoh: https://instagram.com/sepetak'],
        'social_facebook' => ['Facebook URL', 'sosial', 'url', ''],
        'social_twitter' => ['X / Twitter URL', 'sosial', 'url', ''],
        'social_youtube' => ['YouTube URL', 'sosial', 'url', ''],
        'social_tiktok' => ['TikTok URL', 'sosial', 'url', ''],

        // SEO
        'seo_default_og_image' => ['OG Image Default (URL)', 'seo', 'url', 'Gambar fallback untuk share sosial.'],
        'seo_robots' => ['Robots Directive', 'seo', 'text', 'Default: index,follow. Gunakan "noindex,nofollow" untuk staging.'],
        'seo_google_site_verification' => ['Google Site Verification', 'seo', 'text', 'Kode meta Google Search Console.'],
    ];

    public const GROUPS = [
        'umum' => 'Umum',
        'kontak' => 'Kontak',
        'sosial' => 'Sosial Media',
        'seo' => 'SEO',
    ];

    public function mount(): void
    {
        $this->authorizeAccess();

        $data = [];
        foreach (array_keys(self::SCHEMA) as $key) {
            $data[$key] = SiteSetting::getValue($key, '');
        }
        $this->form->fill($data);
    }

    protected function authorizeAccess(): void
    {
        abort_unless(
            auth()->user()?->hasAnyRole(['superadmin', 'admin']),
            403,
            'Anda tidak berhak mengakses pengaturan situs.'
        );
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['superadmin', 'admin']) ?? false;
    }

    public function form(Form $form): Form
    {
        $tabs = [];
        foreach (self::GROUPS as $groupKey => $groupLabel) {
            $tabs[] = Tabs\Tab::make($groupLabel)
                ->icon(match ($groupKey) {
                    'umum' => 'heroicon-o-building-library',
                    'kontak' => 'heroicon-o-phone',
                    'sosial' => 'heroicon-o-share',
                    'seo' => 'heroicon-o-magnifying-glass',
                })
                ->schema($this->fieldsForGroup($groupKey));
        }

        return $form
            ->schema([Tabs::make('Pengaturan')->tabs($tabs)->columnSpanFull()])
            ->statePath('data');
    }

    protected function fieldsForGroup(string $group): array
    {
        $fields = [];
        foreach (self::SCHEMA as $key => [$label, $g, $type, $helper]) {
            if ($g !== $group) {
                continue;
            }
            $field = match ($type) {
                'textarea' => Forms\Components\Textarea::make($key)->rows(3),
                'email' => Forms\Components\TextInput::make($key)->email(),
                'tel' => Forms\Components\TextInput::make($key)->tel(),
                'url' => Forms\Components\TextInput::make($key)->url(),
                default => Forms\Components\TextInput::make($key),
            };
            $field->label($label);
            if ($helper) {
                $field->helperText($helper);
            }
            if (in_array($type, ['textarea'])) {
                $field->columnSpanFull();
            }
            $fields[] = $field;
        }

        return $fields;
    }

    public function save(): void
    {
        $this->authorizeAccess();

        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if (! array_key_exists($key, self::SCHEMA)) {
                continue;
            }
            $group = self::SCHEMA[$key][1] ?? 'umum';
            SiteSetting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => is_string($value) ? trim($value) : $value, 'group_name' => $group],
            );
        }

        Notification::make()
            ->title('Pengaturan disimpan')
            ->body('Cache setting dibersihkan otomatis.')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->submit('save'),
        ];
    }
}
