<?php

namespace App\Filament\Resources\AgrarianCaseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    protected static ?string $title = 'Berkas';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('file_category')
                ->label('Kategori')
                ->options([
                    'evidence' => 'Bukti',
                    'legal' => 'Dokumen Hukum',
                    'correspondence' => 'Surat Menyurat',
                    'photo' => 'Foto',
                    'other' => 'Lainnya',
                ])
                ->required(),

            Forms\Components\TextInput::make('label')
                ->label('Label')
                ->required()
                ->maxLength(255),

            Forms\Components\FileUpload::make('attachment')
                ->label('File')
                ->disk('public')
                ->directory('case-files')
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('file_category')
                    ->label('Kategori'),

                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
