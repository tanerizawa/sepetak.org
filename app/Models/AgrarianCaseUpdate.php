<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgrarianCaseUpdate extends Model
{
    protected $fillable = [
        'agrarian_case_id',
        'update_date',
        'summary',
        'next_step',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'update_date' => 'date',
        ];
    }

    public function agrarianCase(): BelongsTo
    {
        return $this->belongsTo(AgrarianCase::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
