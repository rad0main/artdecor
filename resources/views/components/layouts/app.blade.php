<!DOCTYPE html>
<html lang="ru" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description ?? \App\Models\Setting::get('seo.default_description') }}">

    <title>{{ $title ?? \App\Models\Setting::get('seo.default_title') }} | ArtDecor</title>

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $title ?? \App\Models\Setting::get('seo.default_title') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? '' }}">
    <meta property="og:description" content="{{ $description ?? \App\Models\Setting::get('seo.default_description') }}">

    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Fonts: Montserrat + PT Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-white text-[#3C3D41] antialiased">
    {{ $header ?? '' }}

    {{ $slot }}

    {{ $footer ?? '' }}

    {{-- Callback Modal --}}
    <x-modal name="callback" title="Заказать звонок">
        <form @submit.prevent="submitCallback" class="space-y-4"
              x-data="{ phone: '', name: '' }"
              x-init="$data.submitCallback = async function() {
                  await axios.post('/api/order', { name, phone, source: 'callback' });
                  name = ''; phone = '';
                  $dispatch('close-modal');
                  alert('Спасибо! Мы перезвоним вам.');
              }">
            <input type="text" x-model="name" placeholder="Ваше имя" required
                   class="w-full px-4 py-3 border rounded-lg text-sm outline-none focus:border-[var(--k-color-primary)] focus:ring-1 focus:ring-[var(--k-color-primary)]">
            <input type="tel" x-model="phone" placeholder="+7 (999) 123-45-67" required
                   class="w-full px-4 py-3 border rounded-lg text-sm outline-none focus:border-[var(--k-color-primary)] focus:ring-1 focus:ring-[var(--k-color-primary)]">
            <button type="submit" class="btn-primary w-full text-center">Заказать звонок</button>
        </form>
    </x-modal>

    {{-- Jivosite --}}
    @php $jivositeId = \App\Models\Setting::get('integrations.jivosite_id'); @endphp
    @if($jivositeId)
        <script src="//code.jivosite.com/widget/{{ $jivositeId }}" async></script>
    @endif

    {{-- Яндекс.Метрика --}}
    @php $metrikaId = \App\Models\Setting::get('integrations.yandex_metrika_id'); @endphp
    @if($metrikaId)
        <script>
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],
            k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
            ym({{ $metrikaId }}, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true });
        </script>
    @endif
</body>
</html>
