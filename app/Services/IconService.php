<?php

namespace App\Services;

use App\Services\LogoService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * App\Services\IconService
 */
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
        // TODO : controller la valeur de $extension
        $filename = self::getRandomName($extension);

        if (Storage::disk('icons')->put($filename, $resource)) {
            if (self::isValidImageFile($filename, 'icons')) {
                Log::info(sprintf('Image "%s" successfully stored for import', $filename));

                return $filename;
            } else {
                Storage::disk('icons')->delete($filename);
            }
        }

        return null;
    }

    /**
     * Build an icon by fetching an image file on the internet 
     */
    public function buildFromRemoteImage(string $url) : ?string
    {
        $isRemoteData = Str::startsWith($url, ['http://', 'https://']) && Validator::make(
            [$url],
            ['url']
        )->passes();

        return $isRemoteData ? $this->storeRemoteImage($url) : null;
    }

    /**
     * Fetch and store an external image file
     */
    protected function storeRemoteImage(string $url) : ?string
    {
        try {
            $path_parts  = pathinfo($url);
            $filename = $this->getRandomName($path_parts['extension']);

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

            if (self::isValidImageFile($filename, 'imagesLink')) {
                // Should be a valid image, we move it to the icons disk
                if (Storage::disk('icons')->put($filename, Storage::disk('imagesLink')->get($filename))) {
                    Storage::disk('imagesLink')->delete($filename);
                }

                Log::info(sprintf('Icon file "%s" stored', $filename));
            } else {
                Storage::disk('imagesLink')->delete($filename);
                throw new \Exception('Unsupported mimeType or missing image on storage');
            }

            if (Storage::disk('icons')->exists($filename)) {
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
     * Generate a unique filename
     *
     */
    private static function getRandomName(string $extension) : string
    {
        return Str::random(40) . '.' . $extension;
    }

    /**
     * Validate a file is a valid image
     *
     * @param  string  $filename
     * @param  string  $disk
     */
    public static function isValidImageFile($filename, $disk) : bool
    {
        return in_array(Storage::disk($disk)->mimeType($filename), [
            'image/png',
            'image/jpeg',
            'image/webp',
            'image/bmp',
            'image/x-ms-bmp',
            'image/svg+xml',
        ]) && (Storage::disk($disk)->mimeType($filename) !== 'image/svg+xml' ? getimagesize(Storage::disk($disk)->path($filename)) : true);
    }
}
