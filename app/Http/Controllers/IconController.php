<?php

namespace App\Http\Controllers;

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

        if($request->hasFile('icon')){

            $path = $request->file('icon')->storePublicly('public');

            return response()->json('storage/' . pathinfo($path)['basename'], 201);
        }
        else
        {
            return response()->json('no file in $request', 204);
        }
    }


   /**
     * delete an icon
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete($icon)
    {

        if( Storage::exists('public/' . $icon) ) {

            Storage::delete('public/' . $icon); 
        }

        return response()->json(null, 204);
    }
}