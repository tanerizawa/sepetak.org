<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agrarian_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_code', 30)->unique();
            $table->string('title', 200);
            $table->text('summary');
            $table->longText('description');
            $table->string('location_text', 200)->nullable();
            $table->date('start_date');
            $table->enum('status', ['reported', 'under_review', 'mediation', 'legal_process', 'resolved', 'closed'])->default('reported');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->foreignId('lead_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agrarian_cases');
    }
};
