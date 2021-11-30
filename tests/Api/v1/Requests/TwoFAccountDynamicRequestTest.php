<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountDynamicRequest;
use App\Api\v1\Requests\TwoFAccountUriRequest;
use App\Api\v1\Requests\TwoFAccountStoreRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TwoFAccountDynamicRequestTest extends TestCase
{

    use WithoutMiddleware;

    /**
     * @test
     */
    public function test_user_is_authorized()
    {   
        Auth::shouldReceive('check')
        ->once()
        ->andReturn(true);

        $request = new TwoFAccountDynamicRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @test
     */
    public function test_returns_TwoFAccountUriRequest_rules_when_has_uri_input()
    {
        $twofaccountUriRequest = new TwoFAccountUriRequest();
        $request = new TwoFAccountDynamicRequest();
        $request->merge(['uri' => 'uristring']);

        $this->assertEquals($twofaccountUriRequest->rules(), $request->rules());
    }

    /**
     * @test
     */
    public function test_returns_TwoFAccountStoreRequest_rules_otherwise()
    {
        $twofaccountStoreRequest = new TwoFAccountStoreRequest();
        $request = new TwoFAccountDynamicRequest();

        $this->assertEquals($twofaccountStoreRequest->rules(), $request->rules());
    }

}