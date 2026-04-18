<?php

namespace App\Filament\Resources\ArticlePoolResource\Pages;

use App\Filament\Resources\ArticlePoolResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArticlePools extends ListRecords
{
    protected static string $resource = ArticlePoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
