<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvocacyProgram extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'program_code',
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'lead_user_id',
        'location_text',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function leadUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_user_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(AdvocacyAction::class, 'advocacy_program_id');
    }
}
