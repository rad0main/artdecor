@php
    use function Filament\Support\generate_icon_html;
@endphp

<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament::button type="submit">
            Сохранить настройки
        </x-filament::button>
    </x-filament-panels::form>
</x-filament-panels::page>
