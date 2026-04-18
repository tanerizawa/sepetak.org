<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agrarian_case_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agrarian_case_id')->constrained('agrarian_cases')->cascadeOnDelete();
            $table->date('update_date');
            $table->text('summary');
            $table->text('next_step')->nullable();
            $table->enum('status', ['reported', 'under_review', 'mediation', 'legal_process', 'resolved', 'closed']);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agrarian_case_updates');
    }
};
