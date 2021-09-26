<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface SettingServiceInterface
{
    /**
     * Get a setting
     *
     * @param string|array $setting A single setting name or an associative array of name:value settings
     * @return mixed string|int|boolean|null
     */
    public function get(string $setting);


    /**
     * Get all settings
     *
     * @return mixed Collection of settings
     */
    public function all() : Collection;


    /**
     * Set a setting
     *
     * @param string|array $setting A single setting name or an associative array of name:value settings
     * @param string|int|boolean|null $value The value for single setting
     */
    public function set($setting, $value = null) : void;


    /**
     * Delete a setting
     *
     * @param string $name The setting name
     */
    public function delete(string $name) : void;
}