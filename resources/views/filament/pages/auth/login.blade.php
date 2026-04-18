{{-- View login SEPETAK — mengganti <x-filament-panels::page.simple> dengan
     header sederhana gaya poster, tetapi tetap memakai Livewire form dari
     parent Filament\Pages\Auth\Login agar otentikasi berjalan normal. --}}

<div class="fi-simple-page">
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIMPLE_PAGE_START, scopes: $this->getRenderHookScopes()) }}

    <div class="mb-8">
        <div class="font-mono text-[0.7rem] uppercase tracking-widest text-flag-600 mb-3 flex items-center gap-3">
            <span class="inline-block h-[3px] w-8 bg-flag-500"></span>
            Masuk Sistem
        </div>
        <h2 class="font-display text-4xl sm:text-5xl leading-[0.9] uppercase tracking-tight text-ink-900 dark:text-paper-50">
            Otentikasi<br>
            <span class="text-flag-600">Pengurus.</span>
        </h2>
        <p class="mt-4 text-sm text-ink-700 dark:text-paper-200 leading-relaxed">
            Gunakan email dan kata sandi yang telah diterbitkan sekretariat. Bila mengalami kendala akses, hubungi administrator panel.
        </p>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    {{-- @csrf: form Filament default tidak menyertakan _token; submit HTML biasa ke URL
         saat ini (POST /admin/login) tanpa ini → 419. Livewire tetap memakai /livewire/update. --}}
    <x-filament-panels::form id="form" wire:submit="authenticate" class="space-y-6">
        @csrf
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    <x-filament-actions::modals />

    <div class="mt-10 pt-6 border-t-2 border-ink-900/10 dark:border-paper-50/10 flex items-center justify-between text-[0.7rem] font-mono uppercase tracking-widest text-ink-700 dark:text-paper-200">
        <span>Serikat Pekerja Tani Karawang</span>
        <a href="{{ url('/') }}" class="hover:text-flag-600 dark:hover:text-flag-400 hover:underline">← Kembali ke Situs</a>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIMPLE_PAGE_END, scopes: $this->getRenderHookScopes()) }}
</div>
