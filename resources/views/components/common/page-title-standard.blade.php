@props([
    'title' => '',
    'subtitle' => '',
    'breadcrumbItems' => [], // Array of ['label' => '...', 'route' => '...' (optional), 'active' => false]
    'showBreadcrumb' => true,
    'breadcrumbParent' => null, // For simple breadcrumb: parent label
    'breadcrumbParentRoute' => null, // For simple breadcrumb: parent route
    'breadcrumbActive' => null // For simple breadcrumb: active label (defaults to subtitle)
])

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ $subtitle ?: $title }}</h4>
            @if($showBreadcrumb)
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    @if(!empty($breadcrumbItems))
                        {{-- Custom breadcrumb items --}}
                        @foreach($breadcrumbItems as $item)
                            <li class="breadcrumb-item {{ isset($item['active']) && $item['active'] ? 'active' : '' }}">
                                @if(isset($item['route']) && !isset($item['active']))
                                    <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                                @else
                                    {{ $item['label'] }}
                                @endif
                            </li>
                        @endforeach
                    @elseif($breadcrumbParent)
                        {{-- Simple breadcrumb with parent and active --}}
                        @if($breadcrumbParentRoute)
                            <li class="breadcrumb-item"><a href="{{ route($breadcrumbParentRoute) }}">{{ $breadcrumbParent }}</a></li>
                        @else
                            <li class="breadcrumb-item">{{ $breadcrumbParent }}</li>
                        @endif
                        <li class="breadcrumb-item active">{{ $breadcrumbActive ?: ($subtitle ?: $title) }}</li>
                    @else
                        {{-- Default breadcrumb --}}
                        <li class="breadcrumb-item">{{ $title }}</li>
                        <li class="breadcrumb-item active">{{ $subtitle ?: $title }}</li>
                    @endif
                </ol>
            </div>
            @endif
        </div>
    </div>
</div>

