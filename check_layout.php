<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check if layout view exists
$viewPath = resource_path('views/layouts/app.blade.php');
echo "Layout file exists: " . (file_exists($viewPath) ? 'yes' : 'no') . "\n";

// Check compiled views
$compiled = storage_path('framework/views');
echo "Compiled views: " . (is_dir($compiled) ? count(glob($compiled . '/*')) : 0) . "\n";

// Try to find the component
$factory = app('view');
echo "Layouts.app exists: " . ($factory->exists('layouts.app') ? 'yes' : 'no') . "\n";
