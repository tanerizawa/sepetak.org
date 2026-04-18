<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agrarian_case_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agrarian_case_id')->constrained('agrarian_cases')->cascadeOnDelete();
            $table->enum('party_type', ['member', 'community', 'institution', 'company', 'government', 'other']);
            $table->string('name', 200);
            $table->string('role', 150)->nullable();
            $table->string('contact', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agrarian_case_parties');
    }
};
