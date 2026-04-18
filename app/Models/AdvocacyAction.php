<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvocacyAction extends Model
{
    protected $fillable = [
        'advocacy_program_id',
        'action_date',
        'action_type',
        'notes',
        'outcome',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'action_date' => 'date',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(AdvocacyProgram::class, 'advocacy_program_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
