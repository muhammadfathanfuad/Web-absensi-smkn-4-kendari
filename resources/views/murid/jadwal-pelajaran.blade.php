@extends('layouts.vertical-murid')

@section('title', 'Jadwal Pelajaran')

{{-- 1. Mengirim CSS Flatpickr dari CDN --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Kolom Kiri: Pengumuman dan Jadwal --}}
        <div class="col-lg-8">
            {{-- Kotak Pengumuman --}}
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Pemberitahuan / Pengumuman</h4>
                    <div class="alert alert-warning" role="alert">
                        <i class="mdi mdi-alert-outline me-2"></i>
                        <strong>Pemberitahuan!</strong> Ujian Tengah Semester akan dilaksanakan mulai minggu depan.
                    </div>
                </div>
            </div>

            {{-- Jadwal Pembelajaran --}}
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Jadwal Pelajaran Hari Ini</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Produktif RPL</td>
                                    <td>XI RPL</td>
                                    <td>07:00 - 09:15</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Dasar Desain Grafis</td>
                                    <td>X DKV 1</td>
                                    <td>10:00 - 12:15</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Kalender --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Kalender</h4>
                    {{-- Elemen HTML untuk kalender --}}
                    <div class="flatpickr-calendar-inline"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- 2. Mengirim JS Flatpickr dari CDN dan skrip inisialisasi --}}
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inisialisasi Flatpickr
        flatpickr('.flatpickr-calendar-inline', {
            inline: true,
        });
    </script>
@endsection