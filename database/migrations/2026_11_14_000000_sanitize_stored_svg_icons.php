<?php

use App\Models\Icon;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SVG icons stored before sanitization may embed scripts
        $sanitizer = new Sanitizer;
        $sanitizer->removeRemoteReferences(true);
        $sanitizer->minify(true);

        if (Schema::hasTable('icons')) {
            $this->sanitizeDatabaseIcons($sanitizer);
        }

        $this->sanitizeDiskIcons($sanitizer);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }

    /**
     * Sanitize SVG icons stored in the database
     */
    protected function sanitizeDatabaseIcons(Sanitizer $sanitizer) : void
    {
        foreach (Icon::where('name', 'like', '%.svg')->get() as $icon) {
            $content = $icon->content;

            if (! is_string($content) || $content === '' || $content === __('error.indecipherable')) {
                Log::warning(sprintf('Migration: SVG icon "%s" cannot be read from the database, skipped', $icon->name));

                continue;
            }

            $sanitized = $sanitizer->sanitize($content);

            if (! $sanitized) {
                $this->removeIcon($icon->name);

                Log::warning(sprintf('Migration: SVG icon "%s" cannot be sanitized, removing it', $icon->name));
            } elseif ($sanitized !== $content) {
                $icon->content = $sanitized;
                $icon->save();

                Log::notice(sprintf('Migration: SVG icon "%s" has been sanitized in the database', $icon->name));
            }
        }
    }

    /**
     * Sanitize SVG icons stored on disk
     */
    protected function sanitizeDiskIcons(Sanitizer $sanitizer) : void
    {
        $disk = Storage::disk('icons');

        foreach ($disk->files() as $filename) {
            if (Str::lower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'svg') {
                continue;
            }

            $content = $disk->get($filename);

            if (! is_string($content) || $content === '') {
                continue;
            }

            $sanitized = $sanitizer->sanitize($content);

            if (! $sanitized) {
                $this->removeIcon($filename);

                Log::warning(sprintf('Migration: SVG icon "%s" cannot be sanitized, removing it', $filename));
            } elseif ($sanitized !== $content) {
                $disk->put($filename, $sanitized);

                Log::notice(sprintf('Migration: SVG icon "%s" has been sanitized on disk', $filename));
            }
        }
    }

    /**
     * Delete an icon and detach it from the accounts using it
     */
    protected function removeIcon(string $name) : void
    {
        Storage::disk('icons')->delete($name);

        if (Schema::hasTable('icons')) {
            DB::table('icons')->where('name', $name)->delete();
        }

        DB::table('twofaccounts')->where('icon', $name)->update(['icon' => null]);
    }
};
