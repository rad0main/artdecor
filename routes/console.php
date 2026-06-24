<?php

use Illuminate\Support\Facades\Schedule;

// Sync new images from Yandex Disk every hour
Schedule::command('artdecor:sync-yandex')->hourly();

// Generate missing thumbnails daily
Schedule::command('artdecor:generate-thumbs')->daily();
