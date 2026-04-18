<?php

namespace App\Policies;

class AdvocacyProgramPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-advocacy';
}
