<?php

namespace App\Filament\Resources\ArticleTopicResource\Pages;

use App\Filament\Resources\ArticleTopicResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticleTopic extends CreateRecord
{
    protected static string $resource = ArticleTopicResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
