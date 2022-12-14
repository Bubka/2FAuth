<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TwoFAccountExportCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = TwoFAccountExportResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'app'      => '2fauth_v' . config('2fauth.version'),
            'schema'   => 1,
            'datetime' => now(),
            'data'     => $this->collection,
        ];
    }
}
