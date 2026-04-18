<?php

namespace App\Filament\Resources\ArticlePoolResource\Pages;

use App\Filament\Resources\ArticlePoolResource;
use App\Filament\Resources\ArticlePoolResource\Concerns\ValidatesArticlePoolSchedule;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticlePool extends EditRecord
{
    use ValidatesArticlePoolSchedule;

    protected static string $resource = ArticlePoolResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->assertArticlePoolScheduleValid($data);

        return $data;
    }

    protected function getHeaderActions(): array