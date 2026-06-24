<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CatalogCategory;
use App\Models\CatalogColor;
use App\Models\CatalogImage;
use App\Models\Work;
use App\Models\WorkCategory;
use Illuminate\Console\Command;

class ImportWordPress extends Command
{
    protected $signature = 'artdecor:import-wp {file : Path to JSON export file}';
    protected $description = 'Import data from WordPress export JSON';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($file), true);

        if (!$data) {
            $this->error('Invalid JSON file');
            return self::FAILURE;
        }

        $this->info('Starting WordPress import...');

        // Import catalog categories
        if (isset($data['catalog']['categories'])) {
            $this->importCatalogCategories($data['catalog']['categories']);
        }

        // Import catalog colors
        if (isset($data['catalog']['colors'])) {
            $this->importCatalogColors($data['catalog']['colors']);
        }

        // Import catalog images
        if (isset($data['catalog']['images'])) {
            $this->importCatalogImages($data['catalog']['images']);
        }

        // Import works
        if (isset($data['works']['items'])) {
            $this->importWorks($data['works']['items']);
        }

        $this->info('Import completed successfully!');
        return self::SUCCESS;
    }

    private function importCatalogCategories(array $categories): void
    {
        $bar = $this->output->createProgressBar(count($categories));
        $bar->start();

        foreach ($categories as $item) {
            CatalogCategory::updateOrCreate(
                ['slug' => $item['slug']],
                ['name' => $item['name'], 'sort_order' => $item['sort_order'] ?? 0]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }

    private function importCatalogColors(array $colors): void
    {
        $bar = $this->output->createProgressBar(count($colors));
        $bar->start();

        foreach ($colors as $item) {
            CatalogColor::updateOrCreate(
                ['slug' => $item['slug']],
                ['name' => $item['name'], 'hex' => $item['hex'], 'sort_order' => $item['sort_order'] ?? 0]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }

    private function importCatalogImages(array $images): void
    {
        $bar = $this->output->createProgressBar(count($images));
        $bar->start();

        $categoryMap = CatalogCategory::pluck('id', 'slug')->toArray();
        $colorMap = CatalogColor::pluck('id', 'slug')->toArray();

        foreach ($images as $item) {
            try {
                $image = CatalogImage::updateOrCreate(
                    ['title' => $item['title']],
                    [
                        'category_id' => $categoryMap[$item['category_slug']] ?? null,
                        'is_active' => true,
                    ]
                );

                if (!empty($item['colors'])) {
                    $colorIds = [];
                    foreach ($item['colors'] as $colorSlug) {
                        if (isset($colorMap[$colorSlug])) {
                            $colorIds[] = $colorMap[$colorSlug];
                        }
                    }
                    if (!empty($colorIds)) {
                        $image->colors()->sync($colorIds);
                    }
                }

                if (!empty($item['image_url'])) {
                    $tempPath = tempnam(sys_get_temp_dir(), 'wp_');
                    $content = @file_get_contents($item['image_url']);
                    if ($content) {
                        file_put_contents($tempPath, $content);
                        $image->addMedia($tempPath)->toMediaCollection('catalog');
                        unlink($tempPath);
                    }
                }
            } catch (\Exception $e) {
                $this->warn("  Failed to import {$item['title']}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }

    private function importWorks(array $works): void
    {
        $bar = $this->output->createProgressBar(count($works));
        $bar->start();

        foreach ($works as $item) {
            try {
                $work = Work::updateOrCreate(
                    ['title' => $item['title']],
                    [
                        'category_id' => $item['category_id'] ?? null,
                        'description' => $item['description'] ?? null,
                        'is_featured' => $item['is_featured'] ?? false,
                    ]
                );

                if (!empty($item['image_url'])) {
                    $tempPath = tempnam(sys_get_temp_dir(), 'wp_work_');
                    $content = @file_get_contents($item['image_url']);
                    if ($content) {
                        file_put_contents($tempPath, $content);
                        $work->addMedia($tempPath)->toMediaCollection('works');
                        unlink($tempPath);
                    }
                }
            } catch (\Exception $e) {
                $this->warn("  Failed to import work {$item['title']}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }
}
