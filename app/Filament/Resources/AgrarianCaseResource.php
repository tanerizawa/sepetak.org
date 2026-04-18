<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgrarianCaseResource\Pages;
use App\Filament\Resources\AgrarianCaseResource\RelationManagers;
use App\Models\AgrarianCase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AgrarianCaseResource extends Resource
{
    protected static ?string $model = AgrarianCase::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationGroup = 'Advokasi';

    protected static ?string $modelLabel = 'Kasus Agraria';

    protected static ?string $pluralModelLabel = 'Kasus Agraria';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Kasus')->schema([
                Forms\Components\TextInput::make('case_code')->label('Kode Kasus')->maxLength(32),
                Forms\Components\TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\Textarea::make('summary')->label('Ringkasan')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('description')->label('Deskripsi Lengkap')->rows(6)->columnSpanFull(),
                Forms\Components\TextInput::make('location_text')->label('Lokasi')->maxLength(255),
                Forms\Components\DatePicker::make('start_date')->label('Tanggal Mulai'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Terbuka',
                        'in_progress' => 'Sedang Ditangani',
                        'mediation' => 'Mediasi',
                        'closed' => 'Selesai',
                        'archived' => 'Arsip',
                    ])
                    ->default('open')
                    ->required(),
                Forms\Components\Select::make('priority')
                    ->label('Prioritas')
                    ->options(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi', 'urgent' => 'Mendesak'])
                    ->default('medium'),
                Forms\Components\Select::make('lead_user_id')
                    ->label('Penanggung Jawab')
                    ->relationship('leadUser', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\DateTimePicker::make('closed_at')->label('Ditutup Pada'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('case_code')->label('Kode')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->limit(60)->wrap(),
                Tables\Columns\TextColumn::make('location_text')->label('Lokasi')->toggleable(),
                Tables\Columns\BadgeColumn::make('status')->label('Status')->colors([
                    'warning' => 'open',
                    'primary' => 'in_progress',
                    'info' => 'mediation',
                    'success' => 'closed',
                    'gray' => 'archived',
                ]),
                Tables\Columns\BadgeColumn::make('priority')->label('Prioritas')->colors([
                    'gray' => 'low',
                    'warning' => 'medium',
                    'danger' => 'high',
                    'gray' => 'urgent',
                ]),
                Tables\Columns\TextColumn::make('start_date')->label('Mulai')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'open' => 'Terbuka',
                    'in_progress' => 'Ditangani',
                    'mediation' => 'Mediasi',
                    'closed' => 'Selesai',
                    'archived' => 'Arsip',
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\FilesRelationManager::class,
        ];
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
            'index' => Pages\ListAgrarianCases::route('/'),
            'create' => Pages\CreateAgrarianCase::route('/create'),
            'edit' => Pages\EditAgrarianCase::route('/{record}/edit'),
        ];
    }
}
