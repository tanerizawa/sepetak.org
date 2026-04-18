<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Longgarkan enum status: tambahkan 'rejected'. Di PostgreSQL enum
        // dibuat pakai CHECK constraint oleh Laravel, bukan tipe ENUM native,
        // sehingga cukup drop-and-recreate check-constraint-nya.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE members DROP CONSTRAINT IF EXISTS members_status_check');
            DB::statement("ALTER TABLE members ADD CONSTRAINT members_status_check CHECK (status::text = ANY (ARRAY['pending'::character varying, 'active'::character varying, 'inactive'::character varying, 'resigned'::character varying, 'deceased'::character varying, 'rejected'::character varying]::text[]))");
        }

        Schema::table('members', function (Blueprint $table) {
            if (! Schema::hasColumn('members', 'rejected_at')) {
                $table->dateTime('rejected_at')->nullable()->after('approved_at');
            }
            if (! Schema::hasColumn('members', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
            if (! Schema::hasColumn('members', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('rejection_reason')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'approved_by')) {
                $table->dropConstrainedForeignId('approved_by');
            }
            if (Schema::hasColumn('members', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('members', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE members DROP CONSTRAINT IF EXISTS members_status_check');
            DB::statement("ALTER TABLE members ADD CONSTRAINT members_status_check CHECK (status::text = ANY (ARRAY['pending'::character varying, 'active'::character varying, 'inactive'::character varying, 'resigned'::character varying, 'deceased'::character varying]::text[]))");
        }
    }
};
