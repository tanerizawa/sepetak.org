<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgrarianCaseParty extends Model
{
    protected $fillable = [
        'agrarian_case_id',
        'party_type',
        'name',
        'role',
        'contact',
    ];

    public function agrarianCase(): BelongsTo
    {
        return $this->belongsTo(AgrarianCase::class);
    }
}
