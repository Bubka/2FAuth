<?php

namespace App\Services\LogoLib;

use App\Facades\IconStore;
use App\Services\LogoLib\LogoLibInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

abstract class AbstractLogoLib implements LogoLibInterface
{
    /**
     * The prefix to be aplied to cached filename.
     */
    protected string $cachePrefix = '';

    /**
     * Base url of the icon collection
     */
    protected string $libUrl = '';

    /**
     * Base url of the icon collection
     */
    protected string $format = 'svg';

    /**
     * Suffix to append to the queried resource to get a specific variant
     */
    protected string $variant = '';

    /**
     * Fetch a logo for the given service and save it as an icon
     *
     * @param  string|null  $serviceName  Name of the service to fetch a logo for
     * @param  string|null  $variant  The theme variant to fetch (light, dark, etc...)
     * @return string|null The icon filename or null if no logo has been found
     */
    public function getIcon(?string $serviceName, string $variant = null) : string|null
    {
        $this->setVariant($variant);
        $logoFilename = $this->getLogo(strval($serviceName));

        if (! $logoFilename && $this->format != 'png') {
            // maybe the svg is not available, we try to get the png format
            $this->format = 'png';
            $logoFilename = $this->getLogo(strval($serviceName));
        }

        if (! $logoFilename && $this->variant != 'regular' && ! $this->strictFetch()) {
            // maybe the variant is not available, we try to get the regular svg variant
            $this->format = 'svg';
            if ($icon = $this->getIcon($serviceName)) {
                return $icon;
            }
        }

        if ($logoFilename) {
            $iconFilename = \Illuminate\Support\Str::random(40) . '.' . $this->format;

            return $this->copyToIconStore($logoFilename, $iconFilename) ? $iconFilename : null;
        } else {
            return null;
        }
    }

    /**
     * Sets the variant using passed parameter or default
     */
    protected function setVariant(?string $variant) : void
    {
        if (! $variant || ! in_array($variant, ['regular', 'dark', 'light'])) {
            $this->variant = Auth::user()
                ? Auth::user()->preferences['iconVariant']
                : 'regular';
        } else {
            $this->variant = $variant;
        }
    }

    /**
     * Should we fallback to the regular variant
     */
    protected function strictFetch() : bool
    {
        return Auth::user()
            ? (bool)Auth::user()->preferences['iconVariantStrictFetch']
            : false;
    }

    /**
     * Return the logo's filename for a given service
     *
     * @param  string  $serviceName  Name of the service to fetch a logo for
     * @return string|null The logo filename or null if no logo has been found
     */
    protected function getLogo(string $serviceName)
    {
        $referenceName  = $this->sanitizeServiceName(strval($serviceName));
        $logoFilename   = $referenceName . $this->suffix() . '.' . $this->format;
        $cachedFilename = $this->cachePrefix . $logoFilename;

        if ($referenceName && ! Storage::disk('logos')->exists($cachedFilename)) {
            $this->fetchLogo($logoFilename);
        }

        return Storage::disk('logos')->exists($cachedFilename) ? $cachedFilename : null;
    }

    /**
     * Suffix to append to the reference name to get a specific variant
     */
    protected function suffix() : string
    {
        switch ($this->variant) {
            case 'light':
                $suffix = '-light';
                break;

            case 'dark':
                $suffix = '-dark';
                break;
            
            default:
                $suffix = '';
                break;
        }

        return $suffix;
    }

    /**
     * Url to use in http request to get a specific logo from the logo lib
     */
    protected function logoUrl(string $logoFilename) : string
    {
        return $this->libUrl . $this->format . '/' . $logoFilename;
    }

    /**
     * Prepare service name to match logo libs naming convention
     */
    protected function sanitizeServiceName(string $service) : string
    {
        return preg_replace('/[^0-9a-z]+/', '-', strtolower($service));
    }

    /**
     * Fetch and cache a logo from the logo library
     *
     * @param  string  $logoFilename  Logo filename to fetch
     */
    protected function fetchLogo(string $logoFilename) : void
    {
        $url = $this->logoUrl($logoFilename);

        try {
            $response = Http::withOptions([
                'proxy' => config('2fauth.config.outgoingProxy'),
            ])->retry(3, 100)->get($url);

            if ($response->successful()) {
                $filename = $this->cachePrefix . $logoFilename;

                Storage::disk('logos')->put($filename, $response->body())
                    ? Log::info(sprintf('Logo file "%s" saved to logos dir.', $filename))
                    : Log::notice(sprintf('Cannot save logo file "%s" to logos dir', $filename));
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
