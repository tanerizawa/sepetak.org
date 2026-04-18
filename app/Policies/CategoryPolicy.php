<?php

namespace App\Policies;

class CategoryPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-content';
}
