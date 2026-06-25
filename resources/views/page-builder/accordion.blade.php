<div class="max-w-3xl mx-auto" x-data="accordion()">
    @foreach($items ?? [] as $i => $item)
        <div class="accordion-item">
            <button class="accordion-item__header" @click="toggle({{ $i }})">
                <span class="flex items-center gap-2">
                    <span class="text-[var(--k-color-accent-blue)] font-bold">{{ $i + 1 }}.</span>
                    {{ $item['title'] ?? '' }}
                </span>
                <svg class="accordion-item__arrow" :class="openIndex === {{ $i }} && 'open'" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openIndex === {{ $i }}" x-collapse class="accordion-item__body">
                {!! $item['content'] ?? '' !!}
            </div>
        </div>
    @endforeach
</div>
