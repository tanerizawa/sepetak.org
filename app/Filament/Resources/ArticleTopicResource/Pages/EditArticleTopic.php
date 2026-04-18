<?php

namespace App\Filament\Resources\ArticleTopicResource\Pages;

use App\Filament\Resources\ArticleTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticleTopic extends EditRecord
{
    protected static string $resource = ArticleTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
