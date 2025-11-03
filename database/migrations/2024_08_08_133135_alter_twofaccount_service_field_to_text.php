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
        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->text('service')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If for any reason, the migration is rolled back while the field data are still
        // encrypted, restoring from Text to String type would trunkate the data, making them
        // definitly undecipherable. So we do not restore the original type.
    }
};
