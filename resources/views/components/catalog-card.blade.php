<div class="card group">
    <div class="relative aspect-[4/3] overflow-hidden bg-[var(--k-color-bg-surface)]">
        <img src="{{ $image['thumb'] ?? '' }}"
             alt="{{ $image['title'] ?? '' }}"
             loading="lazy"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    </div>

    <div class="p-3 flex items-center justify-between">
        <span class="text-sm text-[var(--k-color-text-secondary)]">
            {{ $image['title'] ?? 'Без артикула' }}
        </span>

        <button @click="toggleFavorite({{ $image['id'] }})"
                class="text-xl transition-all duration-200 hover:scale-110"
                :class="isFavorite({{ $image['id'] }}) ? 'text-[var(--k-color-primary)]' : 'text-[var(--k-color-text-secondary)]'"
                aria-label="В избранное">
            <span x-text="isFavorite({{ $image['id'] }}) ? '♥' : '♡'">♡</span>
        </button>
    </div>
</div>
