<div x-data="tabs(0)" class="mb-8">
    <div class="flex flex-wrap justify-center gap-2 border-b border-[var(--k-color-border)] pb-0 mb-8">
        @foreach($tabs ?? [] as $i => $tab)
            <button class="tab-btn" :class="active === {{ $i }} && 'active'" @click="active = {{ $i }}">{{ $tab['title'] ?? '' }}</button>
        @endforeach
    </div>
    @foreach($tabs ?? [] as $i => $tab)
        <div x-show="active === {{ $i }}" x-transition class="prose prose-lg max-w-none">
            {!! $tab['content'] ?? '' !!}
        </div>
    @endforeach
</div>
