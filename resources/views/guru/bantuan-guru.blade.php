@extends('layouts.vertical-guru', ['subtitle' => 'Bantuan'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Bantuan'])

    @include('components.bantuan.bantuan', [
        'mode' => 'guru',
        'showVideoGuide' => true,
        'showDocumentation' => true,
        'showSystemStatus' => true,
        'showSearchFAQ' => false,
        'showCategoryHelp' => false,
        'showTipsTricks' => false
    ])
@endsection
