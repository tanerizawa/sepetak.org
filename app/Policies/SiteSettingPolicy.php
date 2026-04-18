<?php

namespace App\Policies;

class SiteSettingPolicy extends BaseResourcePolicy
{
    protected string $permission = 'manage-settings';
}
