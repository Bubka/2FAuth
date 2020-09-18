<?php

use App\TwoFAccount;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderColumnToTwofaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->integer('order_column')->nullable();
        });

        // The primary index is used to set a default value for the newly
        // created order_column
        foreach (TwoFAccount::get() as $twofaccount) {
            $twofaccount->order_column = $twofaccount->id;
            $twofaccount->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->dropColumn('order_column');
        });
    }
}
