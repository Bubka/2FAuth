<?php

namespace App\Services;

use App\Exceptions\FailedIconStoreDatabaseTogglingException;
use App\Facades\Settings;
use App\Models\Icon;
use App\Models\TwoFAccount;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery\MockInterface;

class IconStoreService
{
    /**
     * The storage disk to use
     */
    protected ?string $disk;

    /**
     * Icon replication to database to ease backup
     */
    protected bool $usesDatabase;

    /**
     * The SVG sanitizer
     */
    protected Sanitizer $svgSanitizer;

    /**
     * 
     */
    public function __construct(Sanitizer $svgSanitizer)
    {
        $this->usesDatabase = Settings::get('storeIconsInDatabase');
        $this->setDisk();

        $this->svgSanitizer = $svgSanitizer;
        $this->svgSanitizer->removeRemoteReferences(true);
        $this->svgSanitizer->minify(true);
    }

    /**
     * The storage disk instance
     */
    protected function disk() : Filesystem|MockInterface
    {
        return Storage::disk($this->disk);
    }

    /**
     * Set the storage disk to use
     *
     * @return $this
     */
    public function setDisk(string $diskName = 'icons')
    {
        $this->disk = $diskName;

        return $this;
    }

    /**
     * Whether or not database replication is enabled on the store.
     * This should always equals the 'storeIconsInDatabase' setting
     */
    public function usesDatabase() : bool
    {
        return $this->usesDatabase;
    }

    /**
     * Toggle database replication
     */
    public function setDatabaseReplication(bool $usesDatabase) : void
    {
        if ($this->usesDatabase != $usesDatabase) {
            if ($usesDatabase) {
                $this->clearDatabase();
                $this->mirrorDiskToDatabase();
            } else {
                $this->mirrorDatabaseToDisk();
                $this->clearDatabase();
            }

            $this->usesDatabase = $usesDatabase;
        }
    }

    /**
     * Insert all registered icons into the database
     */
    protected function mirrorDiskToDatabase() : void
    {
        DB::beginTransaction();
        try {
            foreach ($this->registeredIcons() as $filename) {
                if ($content = $this->get($filename)) {
                    $this->storeToDatabase($filename, $content);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw new FailedIconStoreDatabaseTogglingException;
        }
    }

    /**
     * Save all database records as file in the disk
     */
    protected function mirrorDatabaseToDisk() : void
    {
        foreach (Icon::all() as $icon) {
            if (! $this->storeToDisk($icon->name, $icon->content)) {
                throw new FailedIconStoreDatabaseTogglingException;
            }
        }
    }

    /**
     * Get the list of all icon names registered in the TwoFAccount table
     *
     * @return Collection<array-key, mixed>
     */
    protected function registeredIcons()
    {
        return TwoFAccount::whereNotNull('icon')->pluck('icon');
    }

    /**
     * Get the content of a given icon resource, prior to the database record
     */
    public function get(string $name) : ?string
    {
        if ($this->usesDatabase) {
            return Icon::find($name)?->content;
        } else {
            return $this->disk()->get($name) ?: null;
        }
    }

    /**
     * Get the mime-type of a given icon resource
     */
    public function mimeType(string $name) : string|false
    {
        if ($this->usesDatabase && $this->missingInDisk($name)) {
            $this->storeToDisk($name, $this->get($name));
        }

        return $this->disk()->mimeType($name);
    }

    /**
     * Delete all icons from the storage
     */
    public function clear() : bool
    {
        $diskCleared = $this->clearDisk();

        if ($diskCleared && $this->usesDatabase) {
            $this->clearDatabase();
        }

        return $diskCleared;
    }

    /**
     * Delete all icons on the disk
     */
    protected function clearDisk() : bool
    {
        $filesForDelete = Arr::where($this->disk()->files(), function (string $filename) {
            return Str::endsWith($filename, ['png', 'jpg', 'jpeg', 'bmp', 'webp', 'svg']);
        });

        return $this->disk()->delete($filesForDelete);
    }

    /**
     * Delete all icons from the database
     */
    protected function clearDatabase() : void
    {
        Icon::truncate();
    }

    /**
     * Delete the given icons from the storage
     */
    public function delete(array|string $names) : bool
    {
        $names = is_array($names) ? $names : func_get_args();

        $deletedFromDisk = $this->disk()->delete($names);

        if ($deletedFromDisk && $this->usesDatabase) {
            Icon::destroy($names);

            return Icon::whereIn('name', $names)->count() == 0;
        }

        return $deletedFromDisk;
    }

    /**
     * Create the given icon in the storage
     */
    public function store(string $name, string $content) : bool
    {
        $storedToDisk = $this->storeToDisk($name, $content);

        if ($this->mimeType($name) == 'image/svg+xml') {
            $sanitized = $this->sanitize($content);

            if (! $sanitized) {
                $this->delete($name);

                return false;
            }

            if ($content != $sanitized) {
                $content      = $sanitized;
                $storedToDisk = $this->storeToDisk($name, $content);
            }
        }

        if ($this->usesDatabase) {
            return $this->storeToDatabase($name, $content);
        }

        return $storedToDisk;
    }

    /**
     * Sanitize the given content (when icon is an svg image)
     */
    protected function sanitize(string $content) : string
    {
        return $this->svgSanitizer->sanitize($content);
    }

    /**
     * Create the given icon in the disk
     */
    protected function storeToDisk(string $name, string $content) : bool
    {
        return $this->disk()->put($name, $content);
    }

    /**
     * Create the given icon in the database
     */
    protected function storeToDatabase(string $name, string $content) : bool
    {
        $icon          = Icon::firstOrNew(['name' => $name]);
        $icon->content = $content;

        return $icon->save();
    }

    /**
     * Determines if an icon exists in the store, prior to the database.
     * If a database record does not have the corresponding file in disk, it will create it.
     */
    public function exists(string $name) : bool
    {
        if ($this->usesDatabase) {
            $exists = $this->existsInDatabase($name);
            if ($exists && $this->missingInDisk($name)) {
                $this->storeToDisk($name, $this->get($name));
            }

            return $exists;
        } else {
            return $this->existsInDisk($name);
        }
    }

    /**
     * Determine if an icon exists in the database
     */
    protected function existsInDatabase(string $name) : bool
    {
        return Icon::find($name) != null;
    }

    /**
     * Determine if an icon exists in the database
     */
    protected function existsInDisk(string $name) : bool
    {
        return $this->disk()->exists($name);
    }

    /**
     * Determine if an icon is missing in the database
     */
    protected function missingInDisk(string $name) : bool
    {
        return ! $this->existsInDisk($name);
    }
}
