<?php

namespace App\Policies;

class PostPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-content';

    public function update(\App\Models\User $user, \Illuminate\Database\Eloquent\Model $record): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']) || $record->author_id === $user->id;
    }

    public function delete(\App\Models\User $user, \Illuminate\Database\Eloquent\Model $record): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']) || $record->author_id === $user->id;
    }
}
