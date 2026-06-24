<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('phone', 20);
            $table->text('message')->nullable();
            $table->string('source', 50);
            $table->json('article_ids')->nullable();
            $table->string('facade_top_color', 50)->nullable();
            $table->string('facade_bottom_color', 50)->nullable();
            $table->string('status', 20)->default('new');
            $table->text('manager_comment')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('source');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
