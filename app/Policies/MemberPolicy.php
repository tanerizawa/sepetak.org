<?php

namespace App\Policies;

class MemberPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-members';
}
