<?php

declare(strict_types=1);

namespace App\PageBuilder;

use Illuminate\Contracts\View\View;

abstract class BaseWidget
{
    /** Unique widget identifier (kebab-case) */
    abstract public static function name(): string;

    /** Display name in the admin panel */
    abstract public static function title(): string;

    /** Icon class for admin (e.g. 'heroicon-o-hero') */
    abstract public static function icon(): string;

    /** Form schema for widget settings (array of Filament form components) */
    abstract public static function schema(): array;

    /** Default settings for the widget */
    abstract public static function defaults(): array;

    /** Render the widget with given settings */
    abstract public function render(array $settings): View|string;

    /** Categories for grouping in the admin panel */
    public static function category(): string
    {
        return 'basic';
    }

    /** Whether this widget accepts nested widgets (like Columns) */
    public static function isContainer(): bool
    {
        return false;
    }

    /** Merge settings with defaults */
    protected function mergeSettings(array $settings): array
    {
        return array_merge(static::defaults(), $settings);
    }

    /** Helper: responsive classes for breakpoints */
    protected function responsiveClass(string $prefix, array $settings, string $key): string
    {
        $classes = [];
        $value = $settings[$key] ?? $settings[$key . '_default'] ?? null;

        if ($value) {
            $classes[] = $prefix . '-' . $value;
        }

        if (! empty($settings[$key . '_tablet'])) {
            $classes[] = $prefix . '-' . $settings[$key . '_tablet'] . '-tablet';
        }

        if (! empty($settings[$key . '_mobile'])) {
            $classes[] = $prefix . '-' . $settings[$key . '_mobile'] . '-mobile';
        }

        return implode(' ', $classes);
    }
}
