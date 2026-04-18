<?php

namespace App\Policies;

class AgrarianCasePolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-cases';
}
