<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * @see \Laragear\WebAuthn\Models\WebAuthnCredential
 */
return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // We reset the user option 'useWebauthnOnly' to prevent lockout 
        DB::table('options')->where('key', 'useWebauthnOnly')->delete();

        Schema::create('webauthn_credentials', static function (Blueprint $table): void {
            static::defaultBlueprint($table);
        });

        Schema::dropIfExists('web_authn_credentials');
        Schema::rename('web_authn_recoveries', 'webauthn_recoveries');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('webauthn_credentials');
        Schema::rename('webauthn_recoveries', 'web_authn_recoveries');

        Schema::create('web_authn_credentials', function (Blueprint $table) {
            // This must be the exact same definition as migration 2021_12_03_220140_create_web_authn_tables.php
            $table->string('id', 191);
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('type', 16);
            $table->json('transports');
            $table->string('attestation_type');
            $table->json('trust_path');
            $table->uuid('aaguid');
            $table->binary('public_key');
            $table->unsignedInteger('counter')->default(0);
            $table->uuid('user_handle')->nullable();
            $table->timestamps();
            $table->softDeletes('disabled_at');
            $table->primary(['id', 'user_id']);
        });
    }

    /**
     * Generate the default blueprint for the WebAuthn credentials table.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $table
     * @return void
     */
    protected static function defaultBlueprint(Blueprint $table): void
    {
        $table->string('id', 510)->primary();

        $table->morphs('authenticatable', 'webauthn_user_index');

        // This is the user UUID that is generated automatically when a credential for the
        // given user is created. If a second credential is created, this UUID is queried
        // and then copied on top of the new one, this way the real User ID doesn't change.
        $table->uuid('user_id');

        // The app may allow the user to name or rename a credential to a friendly name,
        // like "John's iPhone" or "Office Computer".
        $table->string('alias')->nullable();

        // Allows to detect cloned credentials when the assertion does not have this same counter.
        $table->unsignedBigInteger('counter')->nullable();
        // Who created the credential. Should be the same reported by the Authenticator.
        $table->string('rp_id');
        // Where the credential was created. Should be the same reported by the Authenticator.
        $table->string('origin');
        $table->json('transports')->nullable();
        $table->uuid('aaguid')->nullable(); // GUID are essentially UUID

        // This is the public key the credential uses to verify the challenges.
        $table->text('public_key');
        // The attestation of the public key.
        $table->string('attestation_format')->default('none');
        // This would hold the certificate chain for other different attestation formats.
        $table->json('certificates')->nullable();

        // A way to disable the credential without deleting it.
        $table->timestamp('disabled_at')->nullable();
        $table->timestamps();
    }
};
