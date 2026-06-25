<x-layouts.app :title="$page->title" :description="$page->meta_description">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main class="page-content">
        {!! $content !!}
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
