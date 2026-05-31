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
        Schema::create('twofaccount_user_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('twofaccount_id');
            $table->unsignedInteger('position');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('twofaccount_id')->references('id')->on('twofaccounts')->cascadeOnDelete();

            $table->unique(['user_id', 'twofaccount_id'], 'twofaccount_user_orders_unique_scope');
            $table->index(['user_id', 'position']);
            $table->index('twofaccount_id');
        });

        $this->backfillLegacyOrderColumnValues();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // cannot drop foreign keys in SQLite
        Schema::whenTableHasColumn('twofaccount_user_orders', 'user_id', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['user_id']);
            }
        });
        Schema::whenTableHasColumn('twofaccount_user_orders', 'twofaccount_id', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['twofaccount_id']);
            }
        });

        Schema::dropIfExists('twofaccount_user_orders');
    }

    private function backfillLegacyOrderColumnValues(): void
    {
        if (! Schema::hasColumn('twofaccounts', 'order_column')) {
            return;
        }

        $now = now();

        DB::table('twofaccounts')
            ->whereNotNull('user_id')
            ->select([
                'id as twofaccount_id',
                'user_id',
                DB::raw('COALESCE(order_column, id) as position'),
            ])
            ->orderBy('user_id')
            ->orderByRaw('COALESCE(order_column, id)')
            ->orderBy('id')
            ->chunk(500, function ($rows) use ($now) {
                $payload = [];

                foreach ($rows as $row) {
                    $payload[] = [
                        'user_id'        => (int) $row->user_id,
                        'twofaccount_id' => (int) $row->twofaccount_id,
                        'position'       => (int) $row->position,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                }

                DB::table('twofaccount_user_orders')->upsert(
                    $payload,
                    ['user_id', 'twofaccount_id'],
                    ['position', 'updated_at'],
                );
            });
    }
};
