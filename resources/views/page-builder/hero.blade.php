@php
    $heightClass = match($height ?? 'medium') {
        'small' => 'h-[300px] sm:h-[300px]',
        'medium' => 'h-[400px] sm:h-[500px]',
        'large' => 'h-[500px] sm:h-[700px]',
        'fullscreen' => 'h-screen',
        default => 'h-[400px] sm:h-[500px]',
    };
@endphp

<section class="relative {{ $heightClass }} overflow-hidden bg-[var(--k-color-secondary)]">
    @if(!empty($slides))
        <div x-data="slider({{ json_encode($slides) }})" x-init="init()" class="h-full">
            <template x-for="(slide, i) in slides" :key="i">
                <div x-show="current === i"
                     class="absolute inset-0"
                     x-transition:enter="transition-opacity duration-700"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    <div class="absolute inset-0 bg-cover bg-center"
                         :style="'background-image: url(' + slide.image + ')'">
                    </div>
                </div>
            </template>

            @if($overlay ?? true)
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            @endif

            <div class="absolute inset-0 flex items-end">
                <div class="max-w-page mx-auto px-4 pb-16 md:pb-24 w-full">
                    <div class="max-w-2xl">
                        @if($title)
                            <h1 class="text-3xl md:text-5xl lg:text-6xl font-heading font-bold text-white mb-4 drop-shadow-lg leading-tight">{{ $title }}</h1>
                        @endif
                        @if($subtitle)
                            <p class="text-lg md:text-xl text-white/90 mb-8 drop-shadow">{{ $subtitle }}</p>
                        @endif
                        @if($btn_text && $btn_url)
                            <a href="{{ $btn_url }}" class="btn-primary text-base md:text-lg px-10 py-4 inline-block">{{ $btn_text }}</a>
                        @endif
                    </div>
                </div>
            </div>

            @if(!empty($slides) && count($slides) > 1)
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <template x-for="(slide, i) in slides" :key="'dot-' + i">
                        <button @click="current = i"
                                :class="current === i ? 'bg-white w-8' : 'bg-white/50 w-3'"
                                class="h-3 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            @endif
        </div>
    @else
        @if($overlay ?? true)
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
        @endif
        <div class="absolute inset-0 flex items-end">
            <div class="max-w-page mx-auto px-4 pb-16 md:pb-24 w-full">
                <div class="max-w-2xl">
                    @if($title)
                        <h1 class="text-3xl md:text-5xl lg:text-6xl font-heading font-bold text-white mb-4 drop-shadow-lg leading-tight">{{ $title }}</h1>
                    @endif
                    @if($subtitle)
                        <p class="text-lg md:text-xl text-white/90 mb-8 drop-shadow">{{ $subtitle }}</p>
                    @endif
                    @if($btn_text && $btn_url)
                        <a href="{{ $btn_url }}" class="btn-primary text-base md:text-lg px-10 py-4 inline-block">{{ $btn_text }}</a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</section>
