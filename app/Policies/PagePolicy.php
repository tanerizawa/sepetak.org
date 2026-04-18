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
}
