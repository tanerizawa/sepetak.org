<?php

use Database\Seeders\KajianIlmiahCategorySeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        (new KajianIlmiahCategorySeeder())->run();
    }
};

