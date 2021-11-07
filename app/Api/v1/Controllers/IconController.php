<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


class IconController extends Controller
{
   /**
     * Handle uploaded icon image
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'icon' => 'required|image',
        ]);
        
        $path = $request->file('icon')->store('public/icons');
        $response['filename'] = pathinfo($path)['basename'];

        return response()->json($response, 201);
    }


   /**
     * delete an icon
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete($icon)
    {
        Storage::delete('public/icons/' . $icon); 

        return response()->json(null, 204);
    }
}