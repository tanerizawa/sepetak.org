<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvocacyProgramResource\Pages;
use App\Models\AdvocacyProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdvocacyProgramResource extends Resource
{
    protected static ?string $model = AdvocacyProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Advokasi';

    protected static ?string $modelLabel = 'Program Advokasi';

    protected static ?string $pluralModelLabel = 'Program Advokasi';

    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Program')->schema([
                Forms\Components\TextInput::make('program_code')->label('Kode')->maxLength(32),
                Forms\Components\TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\Textarea::make('description')->label('Deskripsi')->rows(5)->columnSpanFull(),
                Forms\Components\TextInput::make('location_text')->label('Lokasi')->maxLength(255),
                Forms\Components\DatePicker::make('start_date')->label('Mulai'),
                Forms\Components\DatePicker::make('end_date')->label('Berakhir'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'planning' => 'Perencanaan',
                        'active' => 'Berjalan',
                        'paused' => 'Dijeda',
                        'completed' => 'Selesai',
                    ])
                    ->default('planning')
                    ->required(),
                Forms\Components\Select::make('lead_user_id')
                    ->label('Penanggung Jawab')
                    ->relationship('leadUser', 'name')
                    ->searchable()
                    ->preload(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('program_code')->label('Kode')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->wrap(),
                Tables\Columns\BadgeColumn::make('status')->label('Status')->colors([
                    'warning' => 'planning',
                    'success' => 'active',
                    'gray' => 'paused',
                    'gray' => 'completed',
                ]),
                Tables\Columns\TextColumn::make('start_date')->label('Mulai')->date()->sortable(),
                Tables\Columns\TextColumn::make('end_date')->label('Berakhir')->date()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'planning' => 'Perencanaan',
                    'active' => 'Berjalan',
                    'paused' => 'Dijeda',
                    'completed' => 'Selesai',
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
            'index' => Pages\ListAdvocacyPrograms::route('/'),
            'create' => Pages\CreateAdvocacyProgram::route('/create'),
            'edit' => Pages\EditAdvocacyProgram::route('/{record}/edit'),
        ];
    }
}
