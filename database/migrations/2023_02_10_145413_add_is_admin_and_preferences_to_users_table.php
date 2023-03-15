<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')
                ->after('password')
                ->default(0);
            $table->json('preferences')->nullable();
        });

        DB::table('users')->update(['is_admin' => 1]);

        // The 'useWebauthnAsDefault' option is replaced by a local storage record
        // so we delete it form the Options table to prevent its conversion to
        // a user preference
        DB::table('options')->where('key', 'useWebauthnAsDefault')->delete();

        // User options are converted as user preferences
        $options     = DB::table('options')->get();
        $preferences = config('2fauth.preferences');

        foreach ($options as $option) {
            if (Arr::has($preferences, $option->key)) {
                DB::table('users')->update(['preferences->'.$option->key => static::restoreType($option->value)]);
                DB::table('options')->where('id', $option->id)->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferences');
        });
    }

    /**
     * Replaces patterned string that represent booleans with real booleans
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected static function restoreType(mixed $value)
    {
        if (is_numeric($value)) {
            $value = is_float($value + 0) ? (float) $value : (int) $value;
        }

        if ($value === '{{}}') {
            return false;
        } elseif ($value === '{{1}}') {
            return true;
        }
            
        return $value;
    }
};
