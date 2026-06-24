<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CatalogImage;
use App\Models\Work;
use Illuminate\Console\Command;

class GenerateThumbs extends Command
{
    protected $signature = 'artdecor:generate-thumbs {--model=catalog : catalog|work}';
    protected $description = 'Regenerate all thumbnails for catalog images or works';

    public function handle(): int
    {
        $modelType = $this->option('model');

        if ($modelType === 'work') {
            $model = Work::class;
            $collection = 'works';
            $label = 'работ';
        } else {
            $model = CatalogImage::class;
            $collection = 'catalog';
            $label = 'изображений каталога';
        }

        $count = $model::count();
        $this->info("Generating thumbnails for {$count} {$label}...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $model::chunk(50, function ($items) use ($collection, $bar) {
            foreach ($items as $item) {
                $media = $item->getFirstMedia($collection);
                if ($media) {
                    $media->markAsConversionGenerated('thumb', true);
                    $media->markAsConversionGenerated('preview', true);
                }
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info('Thumbnail generation completed!');

        return self::SUCCESS;
    }
}
