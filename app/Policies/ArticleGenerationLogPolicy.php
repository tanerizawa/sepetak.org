<?php

namespace App\Policies;

class ArticleGenerationLogPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-content';
}
