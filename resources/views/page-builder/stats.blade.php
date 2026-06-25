<section class="py-16 md:py-20 px-4" style="background-color: {{ $background_color ?? '#FFFFFF' }};">
    <div class="max-w-page mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($stats ?? [] as $stat)
                <div class="stat-block">
                    <div class="stat-block__number">{{ $stat['number'] ?? '' }}</div>
                    <div class="stat-block__label">{{ $stat['label'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
