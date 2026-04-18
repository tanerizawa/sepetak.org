<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgrarianCase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'case_code',
        'title',
        'summary',
        'description',
        'location_text',
        'start_date',
        'status',
        'priority',
        'lead_user_id',
        'created_by',
        'updated_by',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'closed_at' => 'datetime',
        ];
    }

    public function leadUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function parties(): HasMany
    {
        return $this->hasMany(AgrarianCaseParty::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(AgrarianCaseUpdate::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(AgrarianCaseFile::class);
    }
}
