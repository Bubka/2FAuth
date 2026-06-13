<?php

use App\Models\TwoFAccount;
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
