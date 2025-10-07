@extends('layouts.vertical-guru', ['subtitle' => 'Pengumuman'])

@section('content')

@include('layouts.partials.page-title', ['title' => 'Pengumuman', 'subtitle' => 'Informasi'])

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        @forelse ($pengumuman as $item)
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fs-13">{{ $item['waktu'] }}</span>
                        <span class="text-muted fs-13">{{ $item['tanggal'] }}</span>
                    </div>
                    
                    <p class="card-text">
                        {{ $item['isi'] }}
                    </p>

                    <div class="text-end">
                        <small class="text-muted fst-italic">- {{ $item['penulis'] }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center">
                    <p class="text-muted">Belum ada pengumuman untuk saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

@endsection
