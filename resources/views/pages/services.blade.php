<x-layouts.app title="Наши услуги">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main class="max-w-page mx-auto px-4 py-8">
        <h1 class="text-3xl font-heading font-bold text-[var(--k-color-text-primary)] mb-8">
            Наши услуги
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
                <div class="card p-6">
                    @if($service->icon)
                        <div class="w-12 h-12 rounded-full bg-[var(--k-color-bg-surface)] flex items-center justify-center mb-4">
                            <span class="text-2xl">{{ $service->icon }}</span>
                        </div>
                    @endif
                    <h3 class="font-heading font-bold text-lg mb-2">{{ $service->title }}</h3>
                    @if($service->description)
                        <p class="text-sm text-[var(--k-color-text-secondary)]">{{ $service->description }}</p>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-[var(--k-color-text-secondary)]">
                    <p>Услуги временно отсутствуют</p>
                </div>
            @endforelse
        </div>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
