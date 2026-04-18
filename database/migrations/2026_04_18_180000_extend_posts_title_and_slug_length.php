<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE posts ALTER COLUMN title TYPE VARCHAR(500)');
            DB::statement('ALTER TABLE posts ALTER COLUMN slug TYPE VARCHAR(255)');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE posts MODIFY title VARCHAR(500) NOT NULL');
            DB::statement('ALTER TABLE posts MODIFY slug VARCHAR(255) NOT NULL');
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE posts ALTER COLUMN title TYPE VARCHAR(200)');
            DB::statement('ALTER TABLE posts ALTER COLUMN slug TYPE VARCHAR(200)');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE posts MODIFY title VARCHAR(200) NOT NULL');
            DB::statement('ALTER TABLE posts MODIFY slug VARCHAR(200) NOT NULL');
        }
    }
};
