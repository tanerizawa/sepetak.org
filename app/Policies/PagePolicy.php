<?php

namespace App\Policies;

/**
 * Halaman statis (Tentang Kami, Kontak, dll.) dikelola bersama oleh tim konten.
 * Siapa pun yang memiliki izin manage-content dapat mengubah halaman mana pun,
 * tanpa pembatasan author_id — berbeda dari PostPolicy yang membatasi operator
 * pada artikel yang ditulis sendiri.
 */
class PagePolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-content';

    public function update(\App\Models\User $user, \Illuminate\Database\Eloquent\Model $record): bool
    {
        return parent::update($user, $record);
    }

    public function delete(\App\Models\User $user, \Illuminate\Database\Eloquent\Model $record): bool
    {
        return parent::delete($user, $record);
    }
}
