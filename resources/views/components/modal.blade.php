<div x-data="{ open: false, name: '{{ $name ?? '' }}' }"
     x-show="open"
     x-on:open-modal.window="if ($event.detail === name) open = true"
     x-on:close-modal.window="open = false"
     x-on:keydown.escape.window="open = false"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display: none;">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

    {{-- Контент --}}
    <div class="relative bg-white rounded-xl shadow-lg w-full max-w-md p-8"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="scale-100 opacity-100"
         x-transition:leave-end="scale-95 opacity-0"
         @click.outside="open = false">

        {{-- Кнопка закрытия --}}
        <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Заголовок --}}
        @if(isset($title))
            <h2 class="text-xl font-heading font-bold mb-6">{{ $title }}</h2>
        @endif

        {{-- Контент --}}
        {{ $slot }}
    </div>
</div>
