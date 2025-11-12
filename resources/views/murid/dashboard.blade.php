@extends('layouts.vertical-murid', ['subtitle' => 'Dashboard'])

@section('css')
<style>
    /* Mobile Layout - Fix card size for consistent display */
    @media (max-width: 991.98px) {
        /* Statistik Cards - Mobile Layout */
        .d-xl-none .row {
            margin-left: -8px;
            margin-right: -8px;
        }
        
        .d-xl-none .col-6 {
            padding-left: 8px;
            padding-right: 8px;
            flex: 0 0 50%;
            max-width: 50%;
            width: 50%;
        }
        
        .d-xl-none .card {
            margin-bottom: 12px;
            height: 100%;
            min-height: 90px;
            max-height: 110px;
            display: flex;
            flex-direction: column;
            width: 100%;
            box-sizing: border-box;
        }
        
        .d-xl-none .card-body {
            padding: 8px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .d-xl-none .card-body .d-flex {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        
        .d-xl-none .card-body .flex-shrink-0 {
            flex-shrink: 0;
            margin-bottom: 4px;
        }
        
        .d-xl-none .card-body .avatar-md {
            width: 36px;
            height: 36px;
            min-width: 36px;
            min-height: 36px;
            max-width: 36px;
            max-height: 36px;
        }
        
        .d-xl-none .card-body .avatar-md i {
            font-size: 20px !important;
        }
        
        .d-xl-none .card-body .flex-grow-1 {
            flex-grow: 1;
            width: 100%;
            text-align: center;
        }
        
        .d-xl-none .card-body h5 {
            font-size: 11px;
            margin-bottom: 2px;
            text-align: center;
        }
        
        .d-xl-none .card-body h3 {
            font-size: 18px;
            margin-bottom: 2px;
            line-height: 1.2;
            text-align: center;
        }
        
        .d-xl-none .card-body p {
            font-size: 9px;
            margin-bottom: 0;
            text-align: center;
        }
        
        .d-xl-none .ms-3 {
            margin-left: 0 !important;
        }
    }
    
    /* Extra small devices (phones, less than 576px) */
    @media (max-width: 575.98px) {
        .d-xl-none .card {
            min-height: 85px;
            max-height: 105px;
        }
        
        .d-xl-none .card-body {
            padding: 6px;
        }
        
        .d-xl-none .card-body .avatar-md {
            width: 32px;
            height: 32px;
            min-width: 32px;
            min-height: 32px;
            max-width: 32px;
            max-height: 32px;
        }
        
        .d-xl-none .card-body .avatar-md i {
            font-size: 18px !important;
        }
        
        .d-xl-none .card-body h5 {
            font-size: 10px;
        }
        
        .d-xl-none .card-body h3 {
            font-size: 16px;
        }
        
        .d-xl-none .card-body p {
            font-size: 8px;
        }
    }
    
    /* Small devices (landscape phones, 576px and up) */
    @media (min-width: 576px) and (max-width: 767.98px) {
        .d-xl-none .card {
            min-height: 95px;
            max-height: 115px;
        }
        
        .d-xl-none .card-body {
            padding: 10px;
        }
        
        .d-xl-none .card-body .avatar-md {
            width: 40px;
            height: 40px;
            min-width: 40px;
            min-height: 40px;
            max-width: 40px;
            max-height: 40px;
        }
        
        .d-xl-none .card-body .avatar-md i {
            font-size: 22px !important;
        }
        
        .d-xl-none .card-body h5 {
            font-size: 12px;
        }
        
        .d-xl-none .card-body h3 {
            font-size: 20px;
        }
        
        .d-xl-none .card-body p {
            font-size: 10px;
        }
    }
    
    /* Medium devices (tablets, 768px and up) */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .d-xl-none .card {
            min-height: 100px;
            max-height: 120px;
        }
        
        .d-xl-none .card-body {
            padding: 12px;
        }
        
        .d-xl-none .card-body .avatar-md {
            width: 44px;
            height: 44px;
            min-width: 44px;
            min-height: 44px;
            max-width: 44px;
            max-height: 44px;
        }
        
        .d-xl-none .card-body .avatar-md i {
            font-size: 24px !important;
        }
        
        .d-xl-none .card-body h5 {
            font-size: 13px;
        }
        
        .d-xl-none .card-body h3 {
            font-size: 22px;
        }
        
        .d-xl-none .card-body p {
            font-size: 11px;
        }
    }
</style>
@endsection

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Siswa', 'subtitle' => 'Dashboard'])

    <div class="row">
        <div class="col-xl-8">
            {{-- Welcome Card --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:user-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Selamat Datang Kembali, {{ $namaSiswa }}! 👋</h5>
                            <p class="text-muted mb-1">"Pendidikan adalah senjata paling ampuh untuk mengubah dunia" - Nelson Mandela</p>
                            <p class="text-muted mb-0">Dashboard Siswa - {{ \App\Services\TimeOverrideService::now()->translatedFormat('l, j F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik Cards - Desktop Layout --}}
            <div class="row d-none d-xl-flex">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class='bx bx-check-circle fs-20 text-success'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Kehadiran</h6>
                                    <h4 class="mb-0 text-success">{{ $hadirCount ?? 0 }}</h4>
                                    <p class="text-muted mb-0 small">Hari hadir</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class='bx bx-time-five fs-20 text-warning'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Terlambat</h6>
                                    <h4 class="mb-0 text-warning">{{ $izinCount ?? 0 }}</h4>
                                    <p class="text-muted mb-0 small">Kali terlambat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-none d-xl-flex">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class='bx bx-user-check fs-20 text-info'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Izin</h6>
                                    <h4 class="mb-0 text-info">{{ $sakitCount ?? 0 }}</h4>
                                    <p class="text-muted mb-0 small">Kali izin</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class='bx bx-x-circle fs-20 text-danger'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Alpa</h6>
                                    <h4 class="mb-0 text-danger">{{ $alpaCount ?? 0 }}</h4>
                                    <p class="text-muted mb-0 small">Kali alpa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Winrate Harian - Pojok Kanan Atas --}}
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Winrate Harian 📈</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div id="winrateChart" class="apex-charts" style="height: 200px;"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 text-center">
                    <h6 class="text-muted mb-0">Target: 90% Kehadiran</h6>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards - Mobile Layout --}}
    <div class="row d-xl-none">
        <div class="col-6 col-md-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class='bx bx-check-circle fs-32 text-success'></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Kehadiran</h5>
                            <h3 class="mb-0 text-success">{{ $hadirCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Hari hadir</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class='bx bx-time-five fs-32 text-warning'></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Terlambat</h5>
                            <h3 class="mb-0 text-warning">{{ $izinCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Kali terlambat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class='bx bx-user-check fs-32 text-info'></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Izin</h5>
                            <h3 class="mb-0 text-info">{{ $sakitCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Kali izin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class='bx bx-x-circle fs-32 text-danger'></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Alpa</h5>
                            <h3 class="mb-0 text-danger">{{ $alpaCount ?? 0 }}</h3>
                            <p class="text-muted mb-0">Kali alpa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Jadwal Pelajaran Hari Ini --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-info bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:calendar-outline" class="fs-32 text-info avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="card-title mb-1">
                                Jadwal Pelajaran Hari Ini 📚
                            </h4>
                            <p class="text-muted mb-0">Jadwal lengkap untuk hari ini</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Guru</th>
                                    <th scope="col">Jam</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($timetables ?? collect() as $i => $tt)
                                    @php
                                        $currentTime = \App\Services\TimeOverrideService::now();
                                        $startTime = \Carbon\Carbon::parse($tt['start_time']);
                                        $endTime = \Carbon\Carbon::parse($tt['end_time']);
                                        $isUpcoming = $startTime->isFuture() && $startTime->diffInMinutes($currentTime) <= 30;
                                        $isCurrent = $currentTime->between($startTime, $endTime);
                                        $isPast = $endTime->isPast();
                                    @endphp
                                    <tr class="@if($isUpcoming) table-warning @elseif($isCurrent) table-success @endif">
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                        <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                                    </span>
                                                </div>
                                                {{ $tt['subject'] ?? '—' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info py-1 px-2">{{ $tt['teacher_name'] ?? '—' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($tt['start_time'])->format('H:i') }} - {{ \Carbon\Carbon::parse($tt['end_time'])->format('H:i') }}</span>
                                        </td>
                                        <td>
                                            @if($isUpcoming)
                                                <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                                    <i class="bx bxs-circle text-warning me-1"></i>Segera Dimulai
                                                </span>
                                            @elseif($isCurrent)
                                                <span class="badge bg-success-subtle text-success py-1 px-2">
                                                    <i class="bx bxs-circle text-success me-1"></i>Sedang Berlangsung
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                                    <i class="bx bxs-circle text-secondary me-1"></i>Selesai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted text-center">
                                                <iconify-icon icon="solar:calendar-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                                Tidak ada jadwal untuk hari ini.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Data untuk chart winrate dari database
        @php
            $defaultWinrate = [0, 0, 0, 0, 0, 0, 0];
            $finalWinrate = $winrateData ?? $defaultWinrate;
        @endphp
        var winrateData = @json($finalWinrate);
    </script>
    @vite(['resources/js/pages/dashboard-murid.js'])
@endpush