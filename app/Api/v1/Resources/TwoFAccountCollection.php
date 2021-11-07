<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TwoFAccountCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = 'App\Api\v1\Resources\TwoFAccountReadResource';


    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // By default we want this collection to not return the secret.
        // The underlying TwoFAccountReadResource hides the secret only when withSecret == false.
        // When withSecret is provided the underlying resource will return secret according to the parameter value
        // If no withSecret is set we force it to false to ensure the secret will not being returned.
        if (!$request->has('withSecret')) {
            $request->merge(['withSecret' => false]);
        }

        return $this->collection;
    }
}