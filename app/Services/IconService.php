<?php

namespace App\Services;

use App\Facades\IconStore;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class IconService
{
    /**
     * Build an icon by fetching the official logo on the internet
     */
    public function buildFromOfficialLogo(?string $service) : ?string
    {
        return App::make(LogoService::class)->getIcon($service);
    }

    /**
     * Build an icon from an image resource
     * 
     * @param  \Psr\Http\Message\StreamInterface|\Illuminate\Http\File|\Illuminate\Http\UploadedFile|string|resource  $resource
     * @param  string  $extension  The file extension, without the dot
     */
    public function buildFromResource($resource, $extension) : ?string
    {
        if (! $resource || ! $extension) {
            return null;
        }

        $filename = Helpers::getRandomFilename($extension);

        if (IconStore::store($filename, $resource)) {
            if (self::isValidImageResource($filename, $resource)) {
                Log::info(sprintf('Image "%s" successfully stored for import', $filename));

                return $filename;
            } else {
                IconStore::delete($filename);
            }
        }

        return null;
    }

    /**
     * Build an icon by fetching an image file on the internet 
     */
    public function buildFromRemoteImage(string $url) : ?string
    {
        $isRemoteData = Validator::make(
            [$url],
            ['url']
        )->passes() && Str::startsWith($url, ['http://', 'https://']);

        return $isRemoteData ? $this->storeRemoteImage($url) : null;
    }

    /**
     * Fetch and store an external image file
     */
    protected function storeRemoteImage(string $url) : ?string
    {
        try {
            $path_parts  = pathinfo($url);
            $filename = Helpers::getRandomFilename($path_parts['extension']);

            try {
                $response = Http::withOptions([
                    'proxy' => config('2fauth.config.outgoingProxy'),
                ])->retry(3, 100)->get($url);

                if ($response->successful()) {
                    Storage::disk('imagesLink')->put($filename, $response->body());
                }
            } catch (\Exception $exception) {
                Log::error(sprintf('Cannot fetch imageLink at "%s"', $url));
                
                return null;
            }

            $imagesLinkResource = Storage::disk('imagesLink')->get($filename);
            if ($imagesLinkResource && self::isValidImageResource($filename, $imagesLinkResource)) {
                // Should be a valid image, we move it to the icons disk
                if (IconStore::store($filename, $imagesLinkResource)) {
                    Storage::disk('imagesLink')->delete($filename);
                }

                Log::info(sprintf('Icon file "%s" stored', $filename));
            } else {
                Storage::disk('imagesLink')->delete($filename);
                throw new \Exception('Unsupported mimeType or missing image on storage');
            }

            if (IconStore::exists($filename)) {
                return $filename;
            }
        }
        // @codeCoverageIgnoreStart
        catch (\Exception|\Throwable $ex) {
            Log::error(sprintf('Icon storage failed: %s', $ex->getMessage()));
        }
        // @codeCoverageIgnoreEnd

        return null;
    }

    /**
     * Validate a file is a valid image
     *
     * @param  string  $filename
     * @param  string  $content
     */
    public static function isValidImageResource($filename, $content) : bool
    {
        Storage::disk('temp')->put($filename, $content);
        $extension         = Str::replace('jpg', 'jpeg', pathinfo($filename, PATHINFO_EXTENSION), false);
        $mimeType          = Storage::disk('temp')->mimeType($filename);
        $acceptedMimeTypes = [
            'image/png',
            'image/jpeg',
            'image/webp',
            'image/bmp',
            'image/x-ms-bmp',
            'image/svg+xml',
        ];
        
        $isValid = in_array($mimeType, $acceptedMimeTypes)
            && ($mimeType !== 'image/svg+xml' ? getimagesize(Storage::disk('temp')->path($filename)) : true)
            && Str::contains($mimeType, $extension, true);

        Storage::disk('temp')->delete($filename);

        return $isValid;
    }
}
