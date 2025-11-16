@extends('layouts.vertical-guru', ['subtitle' => 'Delegasi Saya'])

@section('css')
    @vite(['resources/css/guru/delegasi.css'])
@endsection

@section('content')
@include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengganti Absensi'])

@include('components.tugas-absensi.tugas-absensi', [
    'mode' => 'guru',
    'myDelegations' => $myDelegations ?? collect(),
    'today' => $today ?? \Carbon\Carbon::now(),
    'showInfoAlert' => false,
    'qrRouteName' => 'guru.absensi.scan',
    'cardTitle' => 'Tugas Pengganti Absensi',
    'emptyMessage' => 'Anda belum memiliki delegasi'
])

@endsection

