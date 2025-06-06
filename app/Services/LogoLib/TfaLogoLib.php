<?php

namespace App\Services\LogoLib;

use App\Services\LogoLib\AbstractLogoLib;
use App\Services\LogoLib\LogoLibInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TfaLogoLib extends AbstractLogoLib implements LogoLibInterface
{
    /**
     * @var \Illuminate\Support\Collection<string, string>
     */
    protected $tfas;

    /**
     * @var string
     */
    const TFA_JSON = 'tfa.json';

    /**
     * @var string
     */
    const TFA_URL = 'https://2fa.directory/api/v3/tfa.json';

    /**
     * @var string
     */
    protected string $libUrl = 'https://raw.githubusercontent.com/2factorauth/twofactorauth/master/img/';

    /**
     * 
     */
    public function __construct()
    {
        $this->setTfaCollection();
    }    

    /**
     * Fetch a logo for the given service and save it as an icon
     *
     * @param  string|null  $serviceName  Name of the service to fetch a logo for
     * @return string|null The icon filename or null if no logo has been found
     */
    public function getIcon(?string $serviceName) : string|null
    {
        $logoFilename = $this->getLogo(strval($serviceName));

        if ($logoFilename) {
            $iconFilename = \Illuminate\Support\Str::random(40) . '.' . $this->format;

            return $this->copyToIconStore($logoFilename, $iconFilename) ? $iconFilename : null;
        } else {
            return null;
        }
    }

    /**
     * Return the logo's filename for a given service
     *
     * @param  string  $serviceName  Name of the service to fetch a logo for
     * @return string|null The logo filename or null if no logo has been found
     */
    protected function getLogo(string $serviceName)
    {
        $referenceName = $this->tfas->get($this->sanitizeServiceName(strval($serviceName)));
        $logoFilename  = $referenceName . '.' . $this->format;
        $cachedFilename = $this->cachePrefix . $logoFilename;

        if ($referenceName && ! Storage::disk('logos')->exists($cachedFilename)) {
            $this->fetchLogo($cachedFilename);
        }

        return Storage::disk('logos')->exists($cachedFilename) ? $cachedFilename : null;
    }

    /**
     * Build and set the TFA directoy collection
     */
    protected function setTfaCollection() : void
    {
        // We fetch a fresh tfaDirectory if necessary to prevent too many API calls
        if (Storage::disk('logos')->exists(self::TFA_JSON)) {
            if (time() - Storage::disk('logos')->lastModified(self::TFA_JSON) > 86400) {
                $this->cacheTfaDirectorySource();
            }
        } else {
            $this->cacheTfaDirectorySource();
        }

        $this->tfas = Storage::disk('logos')->exists(self::TFA_JSON)
            ? new Collection(json_decode(Storage::disk('logos')->get(self::TFA_JSON)))
            : collect([]);
    }

    /**
     * Fetch and cache fresh TFA.Directory data using the https://2fa.directory API
     */
    protected function cacheTfaDirectorySource() : void
    {
        try {
            $response = Http::withOptions([
                'proxy' => config('2fauth.config.outgoingProxy'),
            ])->retry(3, 100)->get(self::TFA_URL);

            $coll = collect(json_decode(htmlspecialchars_decode($response->body()), true)) /* @phpstan-ignore-line */
                ->mapWithKeys(function ($item, $key) {
                    return [
                        strtolower(head($item)) => $item[1]['domain'],
                    ];
                });

            Storage::disk('logos')->put(self::TFA_JSON, $coll->toJson())
                ? Log::info('Fresh tfa.json saved to logos dir')
                : Log::notice('Cannot save tfa.json to logos dir');
        } catch (\Exception $e) {
            Log::error('Caching of tfa.json failed:' . $e->getMessage());
        }
    }

    /**
     * Url to use in http request to get a specific logo from the logo lib
     */
    protected function logoUrl(string $logoFilename) : string
    {
        return $this->libUrl . $logoFilename[0] . '/' . $logoFilename;
    }

    /**
     * Prepare service name to match logo libs naming convention
     */
    protected function sanitizeServiceName(string $service) : string
    {
        return strtolower(str_replace(['+'], ['plus'], $service));
    }
}
