<?php

namespace App\Policies;

class ArticleTopicPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-content';
}
