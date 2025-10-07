@extends('layouts.vertical-guru', ['subtitle' => 'Absensi'])

@section('content')

@php
    // Definisikan status dan warna badge di sini agar mudah dikelola
    $statusMap = [
        'H' => ['text' => 'Hadir', 'color' => 'success'],
        'S' => ['text' => 'Sakit', 'color' => 'warning'],
        'I' => ['text' => 'Izin', 'color' => 'info'],
        'A' => ['text' => 'Alpha', 'color' => 'danger'],
        null => ['text' => 'Belum Absen', 'color' => 'secondary'],
    ];
@endphp

@include('layouts.partials.page-title', ['title' => 'Status Absensi', 'subtitle' => 'Guru'])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Form Filter --}}
                <form action="{{ route('status-absensi') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="kelas_id" class="form-label">Pilih Kelas</label>
                            <select class="form-select" id="kelas_id" name="kelas_id">
                                <option selected disabled>-- Semua Kelas --</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k['id'] }}">{{ $k['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="mapel_id" class="form-label">Pilih Mata Pelajaran</label>
                            <select class="form-select" id="mapel_id" name="mapel_id">
                                <option selected disabled>-- Semua Mapel --</option>
                                 @foreach ($mapel as $m)
                                    <option value="{{ $m['id'] }}">{{ $m['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </div>
                </form>
                {{-- Akhir Form Filter --}}

                <hr class="my-4">

                {{-- Tabel Daftar Hadir --}}
                <h4 class="card-title mb-4">Daftar Hadir Siswa</h4>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">No.</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Kelamin</th>
                                <th class="text-center">Status</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student['nis'] }}</td>
                                    <td>{{ $student['nama'] }}</td>
                                    <td>{{ $student['jk'] }}</td>
                                    <td class="text-center">
                                        @php
                                            $status = $student['status'] ?? null;
                                            $statusInfo = $statusMap[$status];
                                        @endphp
                                        <span class="badge bg-soft-{{ $statusInfo['color'] }} text-{{ $statusInfo['color'] }} fs-12">
                                            {{ $statusInfo['text'] }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Keterangan bisa ditampilkan di sini jika ada --}}
                                    </td>
                                    <td class="text-center">
                                        {{-- Tampilkan aksi hanya jika siswa belum hadir --}}
                                        @if ($student['status'] !== 'H')
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-warning">Sakit</button>
                                                <button type="button" class="btn btn-outline-info">Izin</button>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Silakan pilih filter untuk menampilkan data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    {{-- Tombol simpan bisa diubah menjadi tombol "Selesaikan Sesi" atau sejenisnya --}}
                    <button type="submit" class="btn btn-success">Selesaikan Sesi Absensi</button>
                </div>
                {{-- Akhir Tabel Daftar Hadir --}}

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row-->

@endsection

