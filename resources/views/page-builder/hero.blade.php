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
                     class="absolute inset-0 transition-opacity duration-700"
                     x-transition:enter="transition-opacity ease-out duration-700"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    <div class="absolute inset-0 bg-cover bg-center"
                         :style="'background-image: url(' + slide.image + ')'">
                    </div>
                </div>
            </template>

            @if($overlay)
                <div class="absolute inset-0 bg-black/40"></div>
            @endif

            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white px-4 max-w-3xl">
                    @if($title)
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-heading font-bold mb-4">{{ $title }}</h1>
                    @endif
                    @if($subtitle)
                        <p class="text-lg sm:text-xl text-white/90 mb-8 max-w-2xl mx-auto">{{ $subtitle }}</p>
                    @endif
                    @if($btn_text && $btn_url)
                        <a href="{{ $btn_url }}" class="btn-primary text-lg px-10 py-4 inline-block">{{ $btn_text }}</a>
                    @endif
                </div>
            </div>

            @if(count($slides) > 1)
                <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-2">
                    <template x-for="(slide, i) in slides" :key="'dot-' + i">
                        <button @click="current = i"
                                :class="current === i ? 'bg-white' : 'bg-white/40'"
                                class="w-3 h-3 rounded-full transition-colors"></button>
                    </template>
                </div>
            @endif
        </div>
    @else
        @if($overlay)
            <div class="absolute inset-0 bg-black/40"></div>
        @endif
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white px-4 max-w-3xl">
                @if($title)
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-heading font-bold mb-4">{{ $title }}</h1>
                @endif
                @if($subtitle)
                    <p class="text-lg sm:text-xl text-white/90 mb-8">{{ $subtitle }}</p>
                @endif
                @if($btn_text && $btn_url)
                    <a href="{{ $btn_url }}" class="btn-primary text-lg px-10 py-4 inline-block">{{ $btn_text }}</a>
                @endif
            </div>
        </div>
    @endif
</section>
