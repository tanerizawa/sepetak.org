<?php

namespace App\Policies;

class EventPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-events';
}
