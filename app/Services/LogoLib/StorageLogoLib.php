<?php

namespace App\Services\LogoLib;

use App\Facades\IconStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StorageLogoLib extends AbstractLogoLib implements LogoLibInterface
{
    /**
     * The file extensions to consider when searching for logos
     */
    protected array $extensions = ['svg', 'png', 'webp', 'jpg', 'jpeg', 'bmp'];

    /**
     * Fetch a logo for the given service and save it as an icon
     *
     * @param  string|null  $serviceName  Name of the service to fetch a logo for
     * @param  string|null  $variant  The local icon pack to fetch from
     * @return string|null The icon filename or null if no logo has been found
     */
    public function getIcon(?string $serviceName, ?string $variant = null) : ?string
    {
        $this->setVariant($variant);
        $logoFilename = $this->getLogo(strval($serviceName));

        if ($logoFilename) {
            $iconFilename = \Illuminate\Support\Str::random(40) . '.' . pathinfo(basename($logoFilename), PATHINFO_EXTENSION);

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
        $this->variant = ! $variant ? Auth::user()->preferences['iconPack'] ?? '' : $variant;
    }

    /**
     * Return the logo's filename for a given service
     *
     * @param  string  $serviceName  Name of the service to fetch a logo for
     * @return string|null The logo filename or null if no logo has been found
     */
    protected function getLogo(string $serviceName)
    {
        $referenceName = $this->sanitizeServiceName($serviceName);
        $candidates    = Storage::disk('iconPacks')->files($this->variant);

        foreach ($candidates as $candidate) {
            if (preg_match('/^' . preg_quote($referenceName, '/') . '\.(' . implode('|', $this->extensions) . ')$/i', basename($candidate))) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Copy a logo file to the icons store with a new name
     */
    protected function copyToIconStore(string $logoFilename, string $iconFilename) : bool
    {
        if ($content = Storage::disk('iconPacks')->get($logoFilename)) {
            return IconStore::store($iconFilename, $content);
        }

        return false;
    }
}
