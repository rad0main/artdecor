<div x-data="{
    filters: { category: '', color: '', search: '' },
    searchTimeout: null,

    applyFilters() {
        window.dispatchEvent(new CustomEvent('filter-change', { detail: { ...this.filters } }));
    },

    updateSearch() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => this.applyFilters(), 300);
    },

    resetFilters() {
        this.filters = { category: '', color: '', search: '' };
        this.applyFilters();
    }
}" class="space-y-4 mb-8">
    {{-- Основные фильтры --}}
    <div class="flex flex-wrap gap-4">
        {{-- Категория --}}
        <div class="w-full sm:w-auto">
            <select x-model="filters.category" @change="applyFilters()"
                    class="w-full sm:w-48 px-4 py-2.5 border border-[var(--k-color-border)] rounded-lg text-sm
                           focus:border-[var(--k-color-primary)] focus:ring-1 focus:ring-[var(--k-color-primary)] outline-none">
                <option value="">Все категории</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Цвет --}}
        <div class="w-full sm:w-auto">
            <select x-model="filters.color" @change="applyFilters()"
                    class="w-full sm:w-48 px-4 py-2.5 border border-[var(--k-color-border)] rounded-lg text-sm
                           focus:border-[var(--k-color-primary)] focus:ring-1 focus:ring-[var(--k-color-primary)] outline-none">
                <option value="">Все цвета</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Поиск --}}
        <div class="w-full sm:w-auto flex-1 sm:max-w-xs">
            <input type="text" x-model="filters.search" @input="updateSearch()"
                   placeholder="Поиск по артикулу..."
                   class="w-full px-4 py-2.5 border border-[var(--k-color-border)] rounded-lg text-sm
                          focus:border-[var(--k-color-primary)] focus:ring-1 focus:ring-[var(--k-color-primary)] outline-none">
        </div>
    </div>

    {{-- Активные фильтры --}}
    <div class="flex flex-wrap items-center gap-2 text-xs" x-show="filters.category || filters.color || filters.search">
        <template x-if="filters.category">
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-[var(--k-color-bg-surface)] border border-[var(--k-color-border)] rounded">
                <span x-text="'Категория: ' + $el.nextElementSibling?.textContent"></span>
                <button @click="filters.category = ''; applyFilters()" class="ml-1 hover:text-[var(--k-color-primary)]">&times;</button>
            </span>
        </template>
        <template x-if="filters.color">
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-[var(--k-color-bg-surface)] border border-[var(--k-color-border)] rounded">
                <span x-text="'Цвет: '"></span>
                <button @click="filters.color = ''; applyFilters()" class="ml-1 hover:text-[var(--k-color-primary)]">&times;</button>
            </span>
        </template>
        <template x-if="filters.search">
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-[var(--k-color-bg-surface)] border border-[var(--k-color-border)] rounded">
                <span x-text="'Поиск: ' + filters.search"></span>
                <button @click="filters.search = ''; applyFilters()" class="ml-1 hover:text-[var(--k-color-primary)]">&times;</button>
            </span>
        </template>
        <button @click="resetFilters()" class="text-[var(--k-color-primary)] hover:underline ml-2">Сбросить</button>
    </div>
</div>
