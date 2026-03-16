<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('twofaccount_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('twofaccount_id')->constrained('twofaccounts')->cascadeOnDelete();
            $table->foreignId('shared_with_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('scope', 24);
            $table->foreignId('created_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['twofaccount_id', 'scope']);
            $table->index('shared_with_user_id');
            $table->unique(['twofaccount_id', 'scope', 'shared_with_user_id'], 'twofaccount_shares_unique_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twofaccount_shares');
    }
};
