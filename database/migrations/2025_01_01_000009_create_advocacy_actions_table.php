<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advocacy_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advocacy_program_id')->constrained('advocacy_programs')->cascadeOnDelete();
            $table->date('action_date');
            $table->enum('action_type', ['meeting', 'training', 'campaign', 'field_visit', 'legal', 'other']);
            $table->text('notes')->nullable();
            $table->text('outcome')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advocacy_actions');
    }
};
