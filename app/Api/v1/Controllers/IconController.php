<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Services\LogoService;
use Illuminate\Support\Facades\App;


class IconController extends Controller
{
    /**
     * Handle uploaded icon image
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'icon' => 'required|image',
        ]);
        
        $path = $request->file('icon')->store('', 'icons');
        $response['filename'] = pathinfo($path)['basename'];

        return response()->json($response, 201);
    }


    /**
     * Fetch a logo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(Request $request)
    {
        $this->validate($request, [
            'service' => 'string|regex:/^[^:]+$/i',
        ]);
        
        $logoService = App::make(LogoService::class);
        $icon = $logoService->getIcon($request->service);

        return $icon
            ? response()->json(['filename' => $icon], 201)
            : response()->json(null, 204);
    }
    

    /**
     * delete an icon
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($icon)
    {
        Storage::disk('icons')->delete($icon); 

        return response()->json(null, 204);
    }
}