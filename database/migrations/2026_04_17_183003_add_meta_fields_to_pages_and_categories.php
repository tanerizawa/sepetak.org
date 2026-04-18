<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('meta_description', 300)->nullable()->after('body');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('color', 20)->nullable()->after('slug');
            $table->string('icon', 60)->nullable()->after('color');
            $table->text('description')->nullable()->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('meta_description');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['color', 'icon', 'description']);
        });
    }
};
