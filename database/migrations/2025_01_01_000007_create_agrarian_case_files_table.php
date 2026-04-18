<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agrarian_case_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agrarian_case_id')->constrained('agrarian_cases')->cascadeOnDelete();
            $table->string('file_category', 80)->nullable();
            $table->string('label', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agrarian_case_files');
    }
};
