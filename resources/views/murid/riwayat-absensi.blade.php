@extends('layouts.vertical-murid')

@section('title', 'Riwayat Absensi')

{{-- Menambahkan CSS untuk Date Picker --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Riwayat Absensi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-murid') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Absensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance History Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="date-range" class="form-label">Filter berdasarkan tanggal:</label>
                            <input type="text" id="date-range" class="form-control" placeholder="Pilih rentang tanggal...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Contoh Data Dinamis --}}
                                <tr>
                                    <td>08 Oktober 2025</td>
                                    <td>Produktif RPL</td>
                                    <td><span class="badge bg-success">Hadir</span></td>
                                    <td>07:02</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>07 Oktober 2025</td>
                                    <td>Matematika</td>
                                    <td><span class="badge bg-success">Hadir</span></td>
                                    <td>07:05</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>06 Oktober 2025</td>
                                    <td>Bahasa Indonesia</td>
                                    <td><span class="badge bg-warning text-dark">Izin</span></td>
                                    <td>-</td>
                                    <td>Acara keluarga</td>
                                </tr>
                                <tr>
                                    <td>05 Oktober 2025</td>
                                    <td>Pendidikan Agama</td>
                                    <td><span class="badge bg-info">Sakit</span></td>
                                    <td>-</td>
                                    <td>Surat dokter terlampir</td>
                                </tr>
                                <tr>
                                    <td>04 Oktober 2025</td>
                                    <td>Produktif RPL</td>
                                    <td><span class="badge bg-danger">Alpa</span></td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                {{-- Data absensi lainnya akan ditampilkan di sini --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Menambahkan JS untuk Date Picker --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inisialisasi Flatpickr untuk filter rentang tanggal
        flatpickr("#date-range", {
            mode: "range",
            dateFormat: "d-m-Y",
        });
    </script>
@endsection