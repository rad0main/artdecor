<div class="work-card relative overflow-hidden rounded-lg group cursor-pointer">
    <div class="aspect-[4/3] bg-[var(--k-color-bg-surface)]">
        <img src="{{ $work['thumb'] ?? '' }}"
             alt="{{ $work['title'] ?? '' }}"
             loading="lazy"
             class="w-full h-full object-cover">
    </div>

    {{-- Hover overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent
                opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="text-white font-bold text-sm">{{ $work['title'] ?? '' }}</h3>
        </div>
    </div>
</div>
