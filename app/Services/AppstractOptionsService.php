<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AppstractOptionsService implements SettingServiceInterface
{
    /**
     * @inheritDoc
     */
    public function get(string $setting)
    {
        $value = option($setting, config('app.options' . $setting));
        $value = $this->restoreType($value);

        return $value;
    }


    /**
     * @inheritDoc
     */
    public function all() : Collection
    {
        // Get a collection of user saved options
        $userOptions = DB::table('options')->pluck('value', 'key');
        $userOptions->transform(function ($item, $key) {
            return $this->restoreType($item);
        });
        $userOptions = collect(config('app.options'))->merge($userOptions);

        return $userOptions;
    }


    /**
     * @inheritDoc
     */
    public function set($setting, $value = null) : void
    {
        $settings = is_array($setting) ? $setting : [$setting => $value];

        foreach ($settings as $setting => $value) {
            $settings[$setting] = $this->replaceBoolean($value);
        }

        option($settings);
    }


    /**
     * @inheritDoc
     */
    public function delete(string $name) : void
    {
        option()->remove($name);
    }
    

    /**
     * Replaces boolean by a patterned string as appstrack/laravel-options package does not support var type
     * 
     * @param \Illuminate\Support\Collection $settings
     * @return \Illuminate\Support\Collection
     */
    private function replaceBoolean($value)
    {
        return is_bool($value) ? '{{' . $value . '}}' : $value;
    }


    /**
     * Replaces patterned string that represent booleans with real booleans
     * 
     * @param \Illuminate\Support\Collection $settings
     * @return \Illuminate\Support\Collection
     */
    private function restoreType($value)
    {
        $value = is_numeric($value) ? (float) $value : $value;

        if( $value === '{{}}' ) {
            return false;
        }
        else if( $value === '{{1}}' ) {
            return true;
        }
        else {
            return $value;
        }
    }
}