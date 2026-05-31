<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * @codeCoverageIgnore
 */
class TransposeTwoFAccountGroupAssignments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fauth:transpose-group-assignments {--dry-run : Report only, do not write data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transpose legacy twofaccounts.group_id values into twofaccount_group_assignments';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! Schema::hasTable('twofaccount_group_assignments')) {
            $this->error('The twofaccount_group_assignments table does not exist. Run migrations first.');

            return self::FAILURE;
        }

        if (! Schema::hasColumn('twofaccounts', 'group_id')) {
            $this->comment('Legacy twofaccounts.group_id column does not exist anymore. Nothing to transpose.');

            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');

        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;

        $rows = DB::table('twofaccounts as t')
            ->join('groups as g', 'g.id', '=', 't.group_id')
            ->whereNotNull('t.group_id')
            ->whereNotNull('t.user_id')
            ->whereColumn('g.user_id', 't.user_id')
            ->select([
                't.id as twofaccount_id',
                't.user_id',
                't.group_id',
            ])
            ->orderBy('t.id');

        $rows->chunk(500, function ($chunk) use ($dryRun, &$inserted, &$updated, &$skipped) {
            foreach ($chunk as $row) {
                $baseQuery = DB::table('twofaccount_group_assignments')
                    ->where('twofaccount_id', (int) $row->twofaccount_id)
                    ->where('user_id', (int) $row->user_id);

                $exists = $baseQuery->exists();

                if ($dryRun) {
                    if ($exists) {
                        $updated++;
                    } else {
                        $inserted++;
                    }

                    continue;
                }

                $affected = DB::table('twofaccount_group_assignments')->updateOrInsert(
                    [
                        'twofaccount_id' => (int) $row->twofaccount_id,
                        'user_id'        => (int) $row->user_id,
                    ],
                    [
                        'group_id'   => (int) $row->group_id,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ],
                );

                if (! $affected) {
                    $skipped++;
                } elseif ($exists) {
                    $updated++;
                } else {
                    $inserted++;
                }
            }
        });

        $this->info($dryRun ? 'Dry run completed.' : 'Transpose completed.');
        $this->line(sprintf('Inserted: %d', $inserted));
        $this->line(sprintf('Updated:  %d', $updated));
        $this->line(sprintf('Skipped:  %d', $skipped));

        return self::SUCCESS;
    }
}
