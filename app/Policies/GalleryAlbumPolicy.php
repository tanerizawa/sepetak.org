<?php

namespace App\Policies;

class GalleryAlbumPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-content';
}
