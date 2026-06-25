<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$routes = collect(Route::getRoutes()->getRoutesByName())->keys()->filter(fn ($name) => str_contains($name, 'filament') && (str_contains($name, 'script') || str_contains($name, 'style') || str_contains($name, 'asset')));
echo "Filament asset routes: " . $routes->count() . "\n";
foreach ($routes->take(5) as $r) {
    echo "  - $r\n";
}

echo "\nTesting direct file...\n";
$directPath = public_path('js/filament/notifications/notifications.js');
echo "Direct path exists: " . (file_exists($directPath) ? 'yes' : 'no') . "\n";

echo "\nChecking vendor path...\n";
$vendorPath = base_path('vendor/filament/notifications/dist/index.js');
echo "Vendor exists: " . (file_exists($vendorPath) ? 'yes' : 'no') . "\n";
echo "Filament dist dir: " . (is_dir(base_path('vendor/filament/notifications/dist')) ? 'yes' : 'no') . "\n";
