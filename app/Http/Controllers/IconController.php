<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;


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
        $messages = [
            'icon.image' => 'Supported format are jpeg, png, bmp, gif, svg, or webp'
        ];

        $validator = Validator::make($request->all(), [
            'icon' => 'required|image',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }


        // if($request->hasFile('icon')){

            $path = $request->file('icon')->storePublicly('public/icons');

            return response()->json(pathinfo($path)['basename'], 201);
        // }
        // else
        // {
        //     return response()->json('no file in $request', 204);
        // }
    }


   /**
     * delete an icon
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete($icon)
    {

        if( Storage::exists('public/icons/' . $icon) ) {

            Storage::delete('public/icons/' . $icon); 
        }

        return response()->json(null, 204);
    }
}