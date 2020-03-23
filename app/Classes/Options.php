<?php

namespace App\Classes;

class Options
{

    /**
     * Build a collection of options to apply
     *
     * @return Options collection
     */
    public static function get()
    {
        // Get a collection of user saved options
        $userOptions = \Illuminate\Support\Facades\DB::table('options')->pluck('value', 'key');

        // We replace patterned string that represent booleans with real booleans
        $userOptions->transform(function ($item, $key) {
                if( $item === '{{}}' ) {
                    return false;
                }
                else if( $item === '{{1}}' ) {
                    return true;
                }
                else {
                    return $item;
                }
        });
    
        // Merge options from App configuration. It ensures we have a complete options collection with
        // fallback values for every options
        $options = collect(config('app.options'))->merge($userOptions);

        return $options;
    }


    /**
     * Set user options
     *
     * @param array All options to store
     * @return void
     */
    public static function store($userOptions)
    {
        foreach($userOptions as $opt => $val) {

            // We replace boolean values by a patterned string in order to retrieve
            // them later (as the Laravel Options package do not support var type)
            // Not a beatufilly solution but, hey, it works ^_^
            option([$opt => is_bool($val) ? '{{' . $val . '}}' : $val]);
        }
    }


}
