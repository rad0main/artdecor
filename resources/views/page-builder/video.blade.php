<section class="py-16 md:py-20 px-4" style="background-color: {{ $background_color ?? '#F5F5F5' }};">
    <div class="max-w-page mx-auto">
        @if($title)
            <div class="section-heading"><h2>{{ $title }}</h2></div>
        @endif
        @if($description)
            <p class="text-center text-[var(--k-color-text-secondary)] max-w-2xl mx-auto mb-8">{!! $description !!}</p>
        @endif
        <div class="max-w-3xl mx-auto">
            <div class="video-preview aspect-video rounded-lg overflow-hidden bg-[var(--k-color-secondary)]">
                @if($thumbnail)
                    <img src="{{ $thumbnail }}" alt="{{ $title ?? 'Видео' }}" class="w-full h-full object-cover opacity-80">
                @endif
                <div class="video-preview__play">
                    <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
            </div>
            <div class="flex items-center justify-center gap-2 mt-4">
                <div class="stars">
                    @for($i = 0; $i < 5; $i++)
                        <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
                <span class="text-sm font-bold text-[var(--k-color-text-primary)]">4.9</span>
            </div>
        </div>
    </div>
</section>
