<x-layouts.app :title="$page->title" :description="$page->meta_description">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main class="page-content">
        {!! $content !!}
    </main>

    <style>
        .page-content > section:not([class*="pt-0"]) { padding-top: 25px !important; }
        .page-content > section:not([class*="pb-0"]):not([class*="pb-4"]):not([class*="pb-6"]) { padding-bottom: 25px !important; }
        .page-content > section:first-child { padding-top: 0 !important; }
        .page-content > section:last-child { padding-bottom: 0 !important; }
    </style>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
