<?php

namespace App\Services\LogoLib;

use App\Facades\IconStore;
use App\Services\LogoLib\LogoLibInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

abstract class AbstractLogoLib implements LogoLibInterface
{
    /**
     * Url to use in http request to get a specific logo from the logo lib
     */
    abstract protected function logoUrl(string $logoFilename) : string;

    /**
     * Prepare service name to match logo libs naming convention
     */
    abstract protected function sanitizeServiceName(string $service) : string;

    /**
     * Fetch and cache a logo from the logo library
     *
     * @param  string  $logoFilename  Logo filename to fetch
     */
    protected function fetchLogo(string $logoFilename) : void
    {
        try {
            $response = Http::withOptions([
                'proxy' => config('2fauth.config.outgoingProxy'),
            ])->retry(3, 100)->get($this->logoUrl($logoFilename));

            if ($response->successful()) {
                Storage::disk('logos')->put($logoFilename, $response->body())
                    ? Log::info(sprintf('Logo "%s" saved to logos dir.', $logoFilename))
                    : Log::notice(sprintf('Cannot save logo "%s" to logos dir', $logoFilename));
            }
        } catch (\Exception $exception) {
            Log::error(sprintf('Fetching of logo "%s" failed.', $logoFilename));
        }
    }

    /**
     * Copy a logo file to the icons store with a new name
     */
    protected function copyToIconStore(string $logoFilename, string $iconFilename) : bool
    {
        if ($content = Storage::disk('logos')->get($logoFilename)) {
            return IconStore::store($iconFilename, $content);
        }

        return false;
    }
}
