@extends('layouts.vertical-murid')

@section('title', 'QR Code Absensi')

@section('content')
    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">QR Code Absensi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-murid') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">QR Code</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- QR Code Card --}}
     <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    {{-- Menampilkan data murid secara dinamis --}}
                    <div class="mb-4">
                        <img src="{{ asset($murid->foto) }}" alt="Foto Murid" class="rounded-circle avatar-lg">
                    </div>
                    <h5 class="font-size-16 mb-1">{{ $murid->nama }}</h5>
                    <p class="text-muted mb-2">NISN: {{ $murid->nisn }}</p>
                    <p class="text-muted mb-4">Kelas: {{ $murid->kelas }}</p>

                    {{-- 1. Siapkan wadah kosong untuk QR Code --}}
                    <div id="qrcode-container" class="mb-4 d-flex justify-content-center"></div>

                    <div class="alert alert-info" role="alert">
                        <i class="mdi mdi-information-outline me-2"></i>
                        Tunjukkan kode ini kepada guru untuk melakukan absensi.
                    </div>

                    <a href="{{ route('dashboard-murid') }}" class="btn btn-light w-100">
                        <i class="bx bx-arrow-back me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {{-- 2. Muat library qrcode.js dari CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <script type="text/javascript">
        // 3. Jalankan skrip untuk membuat QR Code
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil wadah yang sudah kita siapkan
            var qrcodeContainer = document.getElementById('qrcode-container');
            
            // Ambil data unik (NISN) dari PHP yang dikirim controller
            var dataToEncode = "{{ $muridJson }}";

            // Buat QR Code baru
            new QRCode(qrcodeContainer, {
                text: dataToEncode,
                width: 250,
                height: 250,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });
    </script>
@endsection