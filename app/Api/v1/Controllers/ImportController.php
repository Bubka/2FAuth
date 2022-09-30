<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\TwoFAccountImportRequest;
use App\Api\v1\Resources\TwoFAccountCollection;
use App\Contracts\MigrationService;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    /**
     * @var $migrator The Migration service
     */
    protected $migrator;


    /**
     * Constructor
     */
    public function __construct(MigrationService $migrationService)
    {
        $this->migrator = $migrationService;
    }


    /**
     * Convert Google Auth data to a TwoFAccounts collection
     *
     * @param  \App\Api\v1\Requests\TwoFAccountImportRequest  $request
     * @return \App\Api\v1\Resources\TwoFAccountCollection
     */
    public function googleAuth(TwoFAccountImportRequest $request)
    { 
        $request->merge(['withSecret' => true]);
        $twofaccounts = $this->migrator->migrate($request->uri);

        return new TwoFAccountCollection($twofaccounts);
    }


    /**
     * Convert Aegis data to a TwoFAccounts collection
     *
     * @param  \App\Api\v1\Requests\TwoFAccountImportRequest  $request
     * @return \App\Api\v1\Resources\TwoFAccountCollection
     */
    public function aegis(TwoFAccountImportRequest $request)
    { 
        $request->merge(['withSecret' => true]);
        $twofaccounts = $this->migrator->migrate($request->uri);

        return new TwoFAccountCollection($twofaccounts);
    }
}
