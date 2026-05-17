<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otp_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requester_id')->nullable();
            $table->string('requester_name');
            $table->string('requester_email');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('owner_name');
            $table->string('owner_email');
            $table->unsignedInteger('twofaccount_id')->nullable();
            $table->string('twofaccount_account');
            $table->string('twofaccount_service')->nullable();
            $table->string('ip_address', 45);
            $table->string('otp_type', 10);
            $table->unsignedMediumInteger('counter')->nullable();
            $table->timestamp('generated_at');

            $table->foreign('requester_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('twofaccount_id')->references('id')->on('twofaccounts')->nullOnDelete();

            $table->index('requester_id');
            $table->index('twofaccount_id');
            $table->index('ip_address');
            $table->index('otp_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // cannot drop foreign keys in SQLite
        Schema::whenTableHasColumn('otp_logs', 'requester_id', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['requester_id']);
            }
        });
        Schema::whenTableHasColumn('otp_logs', 'owner_id', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['owner_id']);
            }
        });
        Schema::whenTableHasColumn('otp_logs', 'twofaccount_id', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['twofaccount_id']);
            }
        });

        Schema::dropIfExists('otp_logs');
    }
};
