<?php

namespace App\Policies;

class PostPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-content';
}
