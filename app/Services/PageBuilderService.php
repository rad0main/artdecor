<?php

declare(strict_types=1);

namespace App\Services;

use App\PageBuilder\BaseWidget;
use App\PageBuilder\Widgets\ProdRowWidget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class PageBuilderService
{
    /** @var array<string, class-string<BaseWidget>> */
    protected array $widgets = [];

    /**
     * Register a widget class.
     * @param class-string<BaseWidget> $widgetClass
     */
    public function register(string $widgetClass): void
    {
        if (! is_subclass_of($widgetClass, BaseWidget::class)) {
            throw new \InvalidArgumentException("{$widgetClass} must extend " . BaseWidget::class);
        }
        $this->widgets[$widgetClass::name()] = $widgetClass;
    }

    /** Get all registered widgets grouped by category */
    public function getWidgetsGrouped(): Collection
    {
        $groups = [];
        foreach ($this->widgets as $name => $class) {
            $category = $class::category();
            $groups[$category][] = [
                'name' => $name,
                'title' => $class::title(),
                'icon' => $class::icon(),
                'schema' => $class::schema(),
                'defaults' => $class::defaults(),
                'isContainer' => $class::isContainer(),
                'config' => $class::config(),
            ];
        }

        return collect($groups);
    }

    /** Get widget display title by type */
    public function getWidgetTitle(string $type): ?string
    {
        $class = $this->getWidgetClass($type);
        return $class ? $class::title() : null;
    }

    /** Get widget metadata for Filament Builder blocks (lazy-loaded) */
    public function getWidgetBlocks(): array
    {
        $blocks = [];
        foreach ($this->widgets as $name => $class) {
            $blocks[] = \Filament\Forms\Components\Builder\Block::make($name)
                ->label($class::title())
                ->icon($class::icon())
                ->schema($class::schema());
        }
        return $blocks;
    }

    /** Get widget class by name */
    public function getWidgetClass(string $name): ?string
    {
        return $this->widgets[$name] ?? null;
    }

    /**
     * Render page content (array of sections) to HTML string.
     */
    public function render(array $content): string
    {
        $html = '';

        foreach ($content as $section) {
            $section = $this->normalizeBlock($section);
            $sectionType = $section['type'] ?? '';
            $settings = $section['settings'] ?? [];
            $children = $section['children'] ?? [];

            $widgetClass = $this->getWidgetClass($sectionType);

            if ($widgetClass) {
                /** @var BaseWidget $widget */
                $widget = app($widgetClass);

                if ($widgetClass::isContainer() && ! empty($children)) {
                    $settings['_children'] = $this->renderChildren($children);
                }

                $rendered = $widget->render($settings);

                if ($rendered instanceof \Illuminate\Contracts\View\View) {
                    $rendered = $rendered->render();
                }

                $html .= $rendered;
            }
        }

        return $html;
    }

    /**
     * Normalize block data. Filament Builder stores settings under "data" key,
     * our widgets expect "settings". Support both for backward compatibility.
     */
    public function normalizeBlock(array $block): array
    {
        if (! isset($block['settings']) && isset($block['data'])) {
            $block['settings'] = $block['data'];
            unset($block['data']);
        }
        return $block;
    }

    /**
     * Render a single block (for visual editor preview).
     */
    public function renderBlock(array $block): string
    {
        $block = $this->normalizeBlock($block);
        $type = $block['type'] ?? '';
        $settings = $block['settings'] ?? [];
        $class = $this->getWidgetClass($type);

        if (! $class) {
            return '<div class="p-4 text-red-500">Неизвестный блок: ' . $type . '</div>';
        }

        $widget = app($class);
        $rendered = $widget->render($settings);

        if ($rendered instanceof \Illuminate\Contracts\View\View) {
            return $rendered->render();
        }

        return $rendered;
    }

    /** Render nested widgets inside a container */
    protected function renderChildren(array $children): string
    {
        $html = '';
        foreach ($children as $child) {
            $type = $child['type'] ?? '';
            $settings = $child['settings'] ?? [];

            $widgetClass = $this->getWidgetClass($type);
            if ($widgetClass) {
                $widget = app($widgetClass);
                $rendered = $widget->render($settings);
                if ($rendered instanceof \Illuminate\Contracts\View\View) {
                    $rendered = $rendered->render();
                }
                $html .= $rendered;
            }
        }
        return $html;
    }

    /** Initialize with all built-in widgets */
    public static function boot(): self
    {
        $service = new static();

        $service->register(\App\PageBuilder\Widgets\HeroWidget::class);
        $service->register(\App\PageBuilder\Widgets\PromoSliderWidget::class);
        $service->register(\App\PageBuilder\Widgets\TextWidget::class);
        $service->register(\App\PageBuilder\Widgets\ImageWidget::class);
        $service->register(\App\PageBuilder\Widgets\GalleryWidget::class);
        $service->register(\App\PageBuilder\Widgets\ColumnsWidget::class);
        $service->register(\App\PageBuilder\Widgets\CtaWidget::class);
        $service->register(\App\PageBuilder\Widgets\HtmlWidget::class);
        $service->register(\App\PageBuilder\Widgets\AccordionWidget::class);
        $service->register(\App\PageBuilder\Widgets\TabsWidget::class);
        $service->register(\App\PageBuilder\Widgets\PricesWidget::class);
        $service->register(\App\PageBuilder\Widgets\TestimonialsWidget::class);
        $service->register(\App\PageBuilder\Widgets\VideoWidget::class);
        $service->register(\App\PageBuilder\Widgets\StatsWidget::class);
        $service->register(\App\PageBuilder\Widgets\ProdRowWidget::class);

        return $service;
    }
}
