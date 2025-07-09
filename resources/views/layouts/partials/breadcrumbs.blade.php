<nav aria-label="breadcrumb" class="my-3">
    <ol class="breadcrumb bg-light rounded shadow-sm px-3 py-2 align-items-center">
        @if(empty($breadcrumbs))
            <li class="breadcrumb-item active" aria-current="page">
                <i class="bi bi-house-door-fill text-primary"></i> Home
            </li>
        @else
            @foreach ($breadcrumbs as $breadcrumb)
                @if (!$loop->last)
                    <li class="breadcrumb-item">
                        @if($loop->first && strtolower($breadcrumb['label']) === 'dashboard')
                            <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none fw-semibold">
                                <i class="bi bi-speedometer2 text-primary"></i> {{ $breadcrumb['label'] }}
                            </a>
                        @elseif($loop->first && strtolower($breadcrumb['label']) === 'home')
                            <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none fw-semibold">
                                <i class="bi bi-house-door-fill text-primary"></i> {{ $breadcrumb['label'] }}
                            </a>
                        @else
                            <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none fw-semibold">
                                {{ $breadcrumb['label'] }}
                            </a>
                        @endif
                    </li>
                @else
                    <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">
                        {{ $breadcrumb['label'] }}
                    </li>
                @endif
            @endforeach
        @endif
    </ol>
</nav> 