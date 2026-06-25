<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class TwoFAccountCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = TwoFAccountReadResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return Collection<int|string, TwoFAccountReadResource>
     */
    public function toArray($request)
    {
        // By default we want this collection to not return the secret.
        // The underlying TwoFAccountReadResource hides the secret only when withSecret == false.
        // When withSecret is provided the underlying resource will return secret according to the parameter value
        // If no withSecret is set we force it to false to ensure the secret will not being returned.
        if (! $request->has('withSecret')) {
            $request->merge(['withSecret' => false]);
        }

        // Here we add a timestamp to the request if OTPs have to be in the response.
        // The 'at' parameter is used by the TwoFAccountReadResource class to obtain
        // all OTPs at the same timestamps
        if ($request->has('withOtp')) {
            $request->merge(['at' => now()->timestamp]);
        }

        return $this->collection;
    }
}
