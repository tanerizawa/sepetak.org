<?php

namespace App\Filament\Resources\ArticleTopicResource\Pages;

use App\Filament\Resources\ArticleTopicResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArticleTopics extends ListRecords
{
    protected static string $resource = ArticleTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
