<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Anggota';

    protected static ?string $modelLabel = 'Anggota';

    protected static ?string $pluralModelLabel = 'Anggota';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas')
                ->schema([
                    Forms\Components\TextInput::make('member_code')
                        ->label('Kode Anggota')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Dibuat otomatis saat disimpan'),
                    Forms\Components\TextInput::make('full_name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('nik')
                        ->label('NIK')
                        ->maxLength(32),
                    Forms\Components\Select::make('gender')
                        ->label('Jenis Kelamin')
                        ->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),
                    Forms\Components\TextInput::make('birth_place')->label('Tempat Lahir')->maxLength(120),
                    Forms\Components\DatePicker::make('birth_date')->label('Tanggal Lahir'),
                ])->columns(2),

            Forms\Components\Section::make('Kontak')
                ->schema([
                    Forms\Components\TextInput::make('phone')->label('Nomor HP')->tel()->maxLength(32),
                    Forms\Components\TextInput::make('email')->label('Email')->email()->maxLength(255),
                    Forms\Components\Textarea::make('notes')->label('Catatan')->rows(3)->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Keanggotaan')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Menunggu Persetujuan',
                            'active' => 'Aktif',
                            'inactive' => 'Nonaktif',
                            'rejected' => 'Ditolak',
                        ])
                        ->default('pending')
                        ->required(),
                    Forms\Components\DatePicker::make('joined_at')->label('Tanggal Bergabung'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member_code')->label('Kode')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('full_name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('HP')->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'secondary' => 'inactive',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('joined_at')->label('Bergabung')->date()->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Menunggu',
                    'active' => 'Aktif',
                    'inactive' => 'Nonaktif',
                    'rejected' => 'Ditolak',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
