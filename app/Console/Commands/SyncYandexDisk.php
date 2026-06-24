<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CatalogCategory;
use App\Models\CatalogImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncYandexDisk extends Command
{
    protected $signature = 'artdecor:sync-yandex {--dry-run : Preview only without making changes}';
    protected $description = 'Sync new images from Yandex Disk to catalog';

    public function handle(): int
    {
        $disk = Storage::disk('yandex');

        if (!$disk->exists('catalog')) {
            $this->error('Directory "catalog" not found on Yandex Disk');
            return self::FAILURE;
        }

        $directories = $disk->directories('catalog');
        $this->info('Found ' . count($directories) . ' category directories');

        $totalNew = 0;

        foreach ($directories as $dir) {
            $categorySlug = basename($dir);
            $category = CatalogCategory::where('slug', $categorySlug)->first();

            if (!$category) {
                $this->warn("  Category not found for slug: {$categorySlug}, skipping");
                continue;
            }

            $files = $disk->files($dir);
            $this->line("  [{$category->name}] Found " . count($files) . ' files');

            foreach ($files as $path) {
                $filename = pathinfo($path, PATHINFO_FILENAME);

                $exists = CatalogImage::where('title', $filename)
                    ->where('category_id', $category->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $totalNew++;

                if ($this->option('dry-run')) {
                    $this->line("    Would add: {$filename} → {$category->name}");
                    continue;
                }

                $tempPath = tempnam(sys_get_temp_dir(), 'yandex_');
                file_put_contents($tempPath, $disk->get($path));

                $image = CatalogImage::create([
                    'title' => $filename,
                    'category_id' => $category->id,
                    'is_active' => true,
                ]);

                $image->addMedia($tempPath)->toMediaCollection('catalog');
                unlink($tempPath);

                $this->line("    Added: {$filename}");
            }
        }

        if ($this->option('dry-run')) {
            $this->info("Dry-run: {$totalNew} new images would be added");
        } else {
            $this->info("Sync completed: {$totalNew} new images added");
        }

        return self::SUCCESS;
    }
}
