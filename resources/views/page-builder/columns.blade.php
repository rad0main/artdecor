@php
    $cols = (int)($columns_count ?? 2);
    $gridClass = match($cols) {
        2 => 'grid-cols-1 lg:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 xl:grid-cols-4',
        default => 'grid-cols-1 lg:grid-cols-2',
    };
@endphp

<section class="py-12 px-4">
    <div class="max-w-page mx-auto">
        <div class="grid {{ $gridClass }} gap-8">
            @if(!empty($columns))
                @foreach($columns as $col)
                    <div class="col-content">
                        @if(!empty($col['content']))
                            {!! $col['content'] !!}
                        @endif
                    </div>
                @endforeach
            @elseif(!empty($_children))
                @php
                    $childrenParts = explode('<!--column-->', $_children);
                @endphp
                @foreach($childrenParts as $part)
                    @if(trim($part))
                        <div class="col-content">
                            {!! $part !!}
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</section>
