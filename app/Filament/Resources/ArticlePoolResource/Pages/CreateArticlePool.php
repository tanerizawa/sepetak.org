<?php

namespace App\Filament\Resources\ArticlePoolResource\Pages;

use App\Filament\Resources\ArticlePoolResource;
use App\Filament\Resources\ArticlePoolResource\Concerns\ValidatesArticlePoolSchedule;
use Filament\Resources\Pages\CreateRecord;

class CreateArticlePool extends CreateRecord
{
    use ValidatesArticlePoolSchedule;

    protected static string $resource = ArticlePoolResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->assertArticlePoolScheduleValid($data);

        return $data;
    }
}