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
        Schema::create('twofaccount_group_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('twofaccount_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('group_id');
            $table->timestamps();

            $table->foreign('twofaccount_id')->references('id')->on('twofaccounts')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('group_id')->references('id')->on('groups')->cascadeOnDelete();

            $table->unique(['twofaccount_id', 'user_id'], 'twofaccount_group_assignments_unique_scope');
            $table->index('group_id');
            $table->index('user_id');
        });

        $this->backfillLegacyAssignments();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twofaccount_group_assignments');
    }

    private function backfillLegacyAssignments(): void
    {
        if (! Schema::hasColumn('twofaccounts', 'group_id')) {
            return;
        }

        $now = now();

        DB::table('twofaccounts as t')
            ->join('groups as g', 'g.id', '=', 't.group_id')
            ->whereNotNull('t.group_id')
            ->whereNotNull('t.user_id')
            ->whereColumn('g.user_id', 't.user_id')
            ->select([
                't.id as twofaccount_id',
                't.user_id',
                't.group_id',
            ])
            ->orderBy('t.id')
            ->chunk(500, function ($rows) use ($now) {
                $payload = [];

                foreach ($rows as $row) {
                    $payload[] = [
                        'twofaccount_id' => (int) $row->twofaccount_id,
                        'user_id'        => (int) $row->user_id,
                        'group_id'       => (int) $row->group_id,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                }

                DB::table('twofaccount_group_assignments')->upsert(
                    $payload,
                    ['twofaccount_id', 'user_id'],
                    ['group_id', 'updated_at'],
                );
            });
    }
};
