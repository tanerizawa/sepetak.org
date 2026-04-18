<?php

namespace App\Policies;

class ArticlePoolPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-content';
}
