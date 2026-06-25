<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use App\Services\PageBuilderService;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page as BasePage;
use Illuminate\Contracts\Support\Htmlable;

class VisualEditor extends BasePage
{
    protected static string $resource = PageResource::class;

    protected static string $view = 'filament.pages.visual-editor';

    public ?Page $record = null;

    public string $pageContent = '';

    public function mount(Page $record): void
    {
        $this->record = $record;
        $this->pageContent = $this->record->renderContent();
    }

    public function getTitle(): string|Htmlable
    {
        return "Редактор: {$this->record->title}";
    }

    public function deleteBlock(int $index): void
    {
        $content = $this->record->content ?? [];
        unset($content[$index]);
        $this->record->content = array_values($content);
        $this->record->save();

        $this->pageContent = $this->record->renderContent();
    }

    public function moveBlock(int $index, string $direction): void
    {
        $content = $this->record->content ?? [];

        if ($direction === 'up' && $index > 0) {
            $tmp = $content[$index - 1];
            $content[$index - 1] = $content[$index];
            $content[$index] = $tmp;
        } elseif ($direction === 'down' && $index < count($content) - 1) {
            $tmp = $content[$index + 1];
            $content[$index + 1] = $content[$index];
            $content[$index] = $tmp;
        }

        $this->record->content = array_values($content);
        $this->record->save();

        $this->pageContent = $this->record->renderContent();
    }

    public function duplicateBlock(int $index): void
    {
        $content = $this->record->content ?? [];
        $block = $content[$index] ?? null;

        if ($block) {
            $newBlock = $block;
            $newBlock['id'] = uniqid();
            array_splice($content, $index + 1, 0, [$newBlock]);

            $this->record->content = $content;
            $this->record->save();
        }

        $this->pageContent = $this->record->renderContent();
    }

    public function getBlockSettings(int $index): array
    {
        $content = $this->record->content ?? [];
        return $content[$index]['settings'] ?? [];
    }

    public function updateBlockSettings(int $index, array $settings): void
    {
        $content = $this->record->content ?? [];
        if (isset($content[$index])) {
            $content[$index]['settings'] = $settings;
            $this->record->content = $content;
            $this->record->save();
        }
        $this->pageContent = $this->record->renderContent();
    }

    public function addBlock(string $type): void
    {
        $builder = app(PageBuilderService::class);
        $class = $builder->getWidgetClass($type);

        if ($class) {
            $content = $this->record->content ?? [];
            $content[] = [
                'id' => uniqid(),
                'type' => $type,
                'settings' => $class::defaults(),
            ];

            $this->record->content = $content;
            $this->record->save();
        }

        $this->pageContent = $this->record->renderContent();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Сохранить')
                ->action(fn () => $this->record->save())
                ->success(),
        ];
    }
}
