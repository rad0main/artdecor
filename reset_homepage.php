<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
Illuminate\Support\Facades\DB::table('pages')->where('is_homepage', true)->update(['is_homepage' => false]);
echo "Done: " . Illuminate\Support\Facades\DB::table('pages')->where('is_homepage', true)->count() . " homepage pages remaining\n";
