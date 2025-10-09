@extends('layouts.vertical-murid')

@section('title', 'Pengumuman')

@section('content')
    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Pengumuman</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengumuman</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Pengumuman --}}
    <div class="row">
        {{-- Contoh Pengumuman 1 --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ujian Tengah Semester Ganjil</h5>
                    <p class="text-muted"><small>Dipublikasikan: 08 Oktober 2025</small></p>
                    <p class="card-text">
                        Diberitahukan kepada seluruh siswa SMKN 4 Kendari bahwa Ujian Tengah Semester (UTS) Ganjil akan dilaksanakan mulai tanggal 13 Oktober hingga 17 Oktober 2025. Harap mempersiapkan diri dan melunasi administrasi.
                    </p>
                    <a href="#" class="btn btn-primary btn-sm">Baca Selengkapnya</a>
                </div>
            </div>
        </div>

        {{-- Contoh Pengumuman 2 --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kegiatan Class Meeting</h5>
                    <p class="text-muted"><small>Dipublikasikan: 07 Oktober 2025</small></p>
                    <p class="card-text">
                        Dalam rangka menyambut akhir semester, akan diadakan kegiatan class meeting yang akan diisi dengan berbagai lomba antar kelas. Kegiatan akan dilaksanakan setelah UTS selesai. Informasi lomba akan diumumkan kemudian.
                    </p>
                    <a href="#" class="btn btn-primary btn-sm">Baca Selengkapnya</a>
                </div>
            </div>
        </div>

        {{-- Pesan Jika Tidak Ada Pengumuman --}}
        {{-- @if ($pengumuman->isEmpty())
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Tidak ada pengumuman untuk saat ini.
                </div>
            </div>
        @endif --}}
    </div>
@endsection