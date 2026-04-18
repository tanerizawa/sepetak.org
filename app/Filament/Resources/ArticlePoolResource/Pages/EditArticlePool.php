<?php

namespace App\Filament\Resources\ArticlePoolResource\Pages;

use App\Filament\Resources\ArticlePoolResource;
use App\Filament\Resources\ArticlePoolResource\Concerns\ValidatesArticlePoolSchedule;
use App\Models\ArticlePool;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticlePool extends EditRecord
{
    use ValidatesArticlePoolSchedule;

    protected static string $resource = ArticlePoolResource::class;

    /**
     * Pastikan TagsInput selalu menerima array string HH:MM (data lama kadang string atau null).
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $times = $data['schedule_times'] ?? null;

        if (is_string($times) && trim($times) !== '') {
            $data['schedule_times'] = array_values(array_filter(array_map(trim(...), explode(',', $times))));
        } elseif (! is_array($times)) {
            $data['schedule_times'] = [];
        } else {
            $data['schedule_times'] = array_values(array_filter(array_map(
                static fn ($t) => is_string($t) ? trim($t) : (is_scalar($t) ? trim((string) $t) : ''),
                $times
            )));
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ArticlePoolResource::makeGenerateArticleHeaderAction(fn (): ArticlePool => $this->getRecord()),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->assertArticlePoolScheduleValid($data);

        return $data;
    }
}
