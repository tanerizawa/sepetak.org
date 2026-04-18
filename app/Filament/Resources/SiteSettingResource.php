<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 99;

    protected static ?string $modelLabel = 'Pengaturan (Raw)';

    protected static ?string $pluralModelLabel = 'Pengaturan (Raw)';

    public static function shouldRegisterNavigation(): bool
    {
        // Halaman utama "Pengaturan" sudah disediakan oleh SettingsPage dengan tab rapi.
        // Resource ini hanya tersedia via URL langsung untuk superadmin yang butuh edit raw key/value.
        return auth()->user()?->hasRole('superadmin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('setting_key')
                ->label('Kunci')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('group_name')
                ->label('Grup')
                ->maxLength(255),

            Forms\Components\Textarea::make('setting_value')
                ->label('Nilai')
                ->rows(4)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('setting_key')
                    ->label('Kunci')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('group_name')
                    ->label('Grup')
                    ->sortable(),

                Tables\Columns\TextColumn::make('setting_value')
                    ->label('Nilai')
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\Filter::make('group_name')
                    ->form([
                        Forms\Components\TextInput::make('group_name')->label('Grup'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['group_name'] ?? null,
                            fn (Builder $q, string $value): Builder => $q->where('group_name', $value)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
