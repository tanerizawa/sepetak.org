<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class BaseResourcePolicy
{
    /**
     * Spatie permission name (e.g. "manage-members").
     */
    protected string $permission = '';

    public function viewAny(User $user): bool
    {
        return $this->canRead($user);
    }

    public function view(User $user, Model $record): bool
    {
        return $this->canRead($user);
    }

    public function create(User $user): bool
    {
        return $this->canWrite($user);
    }

    public function update(User $user, Model $record): bool
    {
        return $this->canWrite($user);
    }

    public function delete(User $user, Model $record): bool
    {
        return $this->canDelete($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canDelete($user);
    }

    public function restore(User $user, Model $record): bool
    {
        return $this->canDelete($user);
    }

    public function forceDelete(User $user, Model $record): bool
    {
        return $this->canDelete($user);
    }

    protected function canRead(User $user): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->hasAnyRole(['superadmin', 'admin', 'operator', 'viewer'])) {
            return true;
        }

        return $this->permission !== '' && $user->can($this->permission);
    }

    protected function canWrite(User $user): bool
    {
        if (! $user->is_active || $user->hasRole('viewer')) {
            return false;
        }

        return $this->permission === '' || $user->can($this->permission);
    }

    protected function canDelete(User $user): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->hasRole('viewer') || $user->hasRole('operator')) {
            return false;
        }

        return $this->permission === '' || $user->can($this->permission);
    }
}
