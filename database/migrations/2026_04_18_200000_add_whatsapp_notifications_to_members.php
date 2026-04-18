<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (! Schema::hasColumn('members', 'whatsapp_notifications')) {
                $table->boolean('whatsapp_notifications')->default(true)->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'whatsapp_notifications')) {
                $table->dropColumn('whatsapp_notifications');
            }
        });
    }
};
