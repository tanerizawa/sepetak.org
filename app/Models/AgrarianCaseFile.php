<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AgrarianCaseFile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'agrarian_case_id',
        'file_category',
        'label',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function agrarianCase(): BelongsTo
    {
        return $this->belongsTo(AgrarianCase::class);
    }
}
