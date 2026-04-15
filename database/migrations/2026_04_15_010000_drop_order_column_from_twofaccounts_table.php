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
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (! Schema::hasColumn('twofaccounts', 'order_column')) {
            return;
        }

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->dropColumn('order_column');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasColumn('twofaccounts', 'order_column')) {
            return;
        }

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->integer('order_column')->nullable();
        });

        $this->restoreLegacyOrderColumnValues();
    }

    private function restoreLegacyOrderColumnValues(): void
    {
        if (! Schema::hasTable('twofaccount_user_orders')) {
            return;
        }

        DB::table('twofaccounts as t')
            ->join('twofaccount_user_orders as o', function ($join) {
                $join->on('o.twofaccount_id', '=', 't.id')
                    ->on('o.user_id', '=', 't.user_id');
            })
            ->select([
                't.id as twofaccount_id',
                'o.position',
            ])
            ->orderBy('t.id')
            ->chunk(500, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('twofaccounts')
                        ->where('id', $row->twofaccount_id)
                        ->update(['order_column' => (int) $row->position]);
                }
            });

        DB::table('twofaccounts')
            ->whereNull('order_column')
            ->update(['order_column' => DB::raw('id')]);
    }
};
