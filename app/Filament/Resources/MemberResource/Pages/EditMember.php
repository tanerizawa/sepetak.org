<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\Address;
use App\Notifications\MemberApproved;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('printCard')
                ->label('Kartu Anggota')
                ->icon('heroicon-o-identification')
                ->color('gray')
                ->url(fn () => route('admin.members.card', $this->record), shouldOpenInNewTab: true),
            Actions\Action::make('approve')
                ->label('Setujui')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => $this->record->status === 'pending')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update([
                        'status' => 'active',
                        'approved_at' => now(),
                        'approved_by' => auth()->id(),
                    ]);

                    try {
                        if (! empty($this->record->email)) {
                            \Illuminate\Support\Facades\Notification::route('mail', $this->record->email)
                                ->notify(new MemberApproved($this->record));
                        }
                    } catch (\Throwable $e) {
                        Log::warning('Gagal kirim email approve anggota: '.$e->getMessage(), ['member_id' => $this->record->id]);
                    }

                    Notification::make()->title('Anggota disetujui')->success()->send();
                    $this->refreshFormData(['status', 'approved_at']);
                }),
            Actions\Action::make('reject')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->record->status === 'pending')
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'status' => 'rejected',
                        'rejected_at' => now(),
                        'rejection_reason' => $data['rejection_reason'],
                    ]);
                    Notification::make()->title('Pendaftaran ditolak')->warning()->send();
                    $this->refreshFormData(['status']);
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->address) {
            $data['address_line_1'] = $this->record->address->line_1;
            $data['address_village'] = $this->record->address->village;
            $data['address_district'] = $this->record->address->district;
            $data['address_regency'] = $this->record->address->regency;
            $data['address_province'] = $this->record->address->province;
            $data['address_postal_code'] = $this->record->address->postal_code;
        }

        return $data;
    }

    /**
     * Filament FileUpload menyimpan state sebagai `string` atau map `uuid => path`
     * setelah hidrasi; `addMediaFromDisk` butuh path string yang ada di disk.
     *
     * @return list<string>
     */
    protected function normalizeUploadState(mixed $state): array
    {
        if ($state === null || $state === '' || $state === []) {
            return [];
        }

        if (is_string($state)) {
            return [$state];
        }

        if (is_array($state)) {
            return array_values(array_filter($state, static fn ($v): bool => is_string($v) && $v !== ''));
        }

        return [];
    }

    /**
     * @return list<string>
     */
    protected function uploadStatePaths(string $key): array
    {
        $raw = $this->form->getRawState();
        $state = $raw[$key] ?? ($this->data[$key] ?? null);

        return $this->normalizeUploadState($state);
    }

    protected function afterSave(): void
    {
        foreach ($this->uploadStatePaths('photo_upload') as $path) {
            if (Storage::disk('public')->exists($path)) {
                $this->record->clearMediaCollection('photo');
                $this->record->addMediaFromDisk($path, 'public')
                    ->toMediaCollection('photo');

                break;
            }
        }

        foreach ($this->uploadStatePaths('documents_upload') as $path) {
            if (Storage::disk('public')->exists($path)) {
                $this->record->addMediaFromDisk($path, 'public')
                    ->toMediaCollection('documents');
            }
        }

        $addressFields = [
            'line_1' => $this->data['address_line_1'] ?? null,
            'village' => $this->data['address_village'] ?? null,
            'district' => $this->data['address_district'] ?? null,
            'regency' => $this->data['address_regency'] ?? null,
            'province' => $this->data['address_province'] ?? null,
            'postal_code' => $this->data['address_postal_code'] ?? null,
        ];

        if ($this->record->address_id) {
            $this->record->address->update($addressFields);
        } elseif (array_filter($addressFields)) {
            $address = Address::create($addressFields);
            $this->record->update(['address_id' => $address->id]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
