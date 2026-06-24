<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $defaults = [
            ['key' => 'contacts.phone', 'value' => '8 (499) 34-34-713'],
            ['key' => 'contacts.email', 'value' => 'info@skinali.moscow'],
            ['key' => 'contacts.address', 'value' => ''],
            ['key' => 'contacts.work_hours', 'value' => 'Пн-Пт: 9:00 - 18:00'],
            ['key' => 'integrations.yandex_metrika_id', 'value' => '95172838'],
            ['key' => 'integrations.jivosite_id', 'value' => 'YVm3vjP8Wd'],
            ['key' => 'seo.default_title', 'value' => 'ArtDecor — Скинали и стеклянные изделия на заказ'],
            ['key' => 'seo.default_description', 'value' => 'Производство и продажа скинали, стеклянных фартуков для кухни, панно, перегородок. Собственное производство. Фиксированная стоимость.'],
        ];

        foreach ($defaults as $setting) {
            DB::table('settings')->insert($setting);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
