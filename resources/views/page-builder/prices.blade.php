<section class="py-16 md:py-20 px-4" style="background-color: {{ $background_color ?? '#F5F5F5' }};">
    <div class="max-w-page mx-auto">
        @if($heading)
            <div class="section-heading"><h2>{{ $heading }}</h2></div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            @foreach($prices ?? [] as $price)
                <div class="price-card @if($price['featured'] ?? false) price-card--featured @endif">
                    <div class="price-card__name">{{ $price['name'] ?? '' }}</div>
                    <div class="price-card__price">от {{ $price['price'] ?? '' }} <span>{{ $price['unit'] ?? '₽/м²' }}</span></div>
                    @if(!empty($price['features']))
                        <ul class="price-card__features">
                            @foreach(is_string($price['features']) ? array_filter(explode("\n", $price['features'])) : ($price['features'] ?? []) as $feature)
                                <li>{{ trim($feature) }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ $price['btn_url'] ?? '/contacts' }}" class="btn-primary w-full">{{ $price['btn_text'] ?? 'Заказать' }}</a>
                </div>
            @endforeach
        </div>
    </div>
</section>
