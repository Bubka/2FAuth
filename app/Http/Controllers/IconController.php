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

        $this->validate($request, [
            'icon' => 'required|image',
        ]);
        
        $path = $request->file('icon')->store('public/icons');

        return response()->json(pathinfo($path)['basename'], 201);
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