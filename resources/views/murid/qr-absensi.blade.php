@extends('layouts.vertical-murid', ['subtitle' => 'Scan QR Absensi'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Scan QR Absensi', 'subtitle' => 'Murid'])

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Arahkan kamera ke QR Code guru</h5>
                    <div class="mb-2">
                        <label for="cameraSelect" class="form-label">Pilih Kamera</label>
                        <select id="cameraSelect" class="form-select"></select>
                    </div>
                    <div id="reader" style="width:100%; min-height:300px;"></div>
                    <div id="scanStatus" class="mt-2 text-muted">Menunggu pemindaian...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/html5-qrcode.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scanStatus = document.getElementById('scanStatus');
            let last = { value: null, time: 0 };

            function onScanSuccess(decodedText, decodedResult) {
                try {
                    const payload = JSON.parse(decodedText);
                    const timetableId = payload.timetable_id || payload.timetableId || payload.timetable;
                    if (!timetableId) {
                        scanStatus.innerText = 'QR tidak berisi timetable_id.';
                        return;
                    }

                    const now = Date.now();
                    if (last.value === timetableId && (now - last.time) < 3000) return;
                    last = { value: timetableId, time: now };

                    scanStatus.innerText = 'Mengirim data absensi...';

                    fetch("{{ route('murid.qr.submit') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ timetable_id: timetableId })
                    })
                    .then(r => r.json().then(data => ({ ok: r.ok, data })))
                    .then(({ ok, data }) => {
                        if (!ok) throw new Error(data.error || data.message || 'Gagal');
                        scanStatus.innerText = data.message || 'Absensi berhasil: ' + (data.status || 'H');
                    })
                    .catch(err => {
                        scanStatus.innerText = err.message || 'Gagal mengirim data.';
                    });

                } catch (e) {
                    scanStatus.innerText = 'QR tidak valid.';
                }
            }

            const cameraSelect = document.getElementById('cameraSelect');
            let currentScanner = null;
            let currentCameraId = null;

            function loadScriptOnce(src) {
                return new Promise((resolve, reject) => {
                    if (window.Html5Qrcode) return resolve();
                    const existing = document.querySelector(`script[src="${src}"]`);
                    if (existing) {
                        existing.addEventListener('load', () => resolve());
                        existing.addEventListener('error', () => reject(new Error('Failed to load ' + src)));
                        return;
                    }
                    const s = document.createElement('script');
                    s.src = src;
                    s.onload = () => resolve();
                    s.onerror = () => reject(new Error('Failed to load ' + src));
                    document.head.appendChild(s);
                });
            }

            async function initCameras() {
                try {
                    if (typeof Html5Qrcode === 'undefined') {
                        scanStatus.innerText = 'Memuat library pemindai...';
                        await loadScriptOnce('{{ asset('vendor/html5-qrcode.min.js') }}');
                    }

                    if (typeof Html5Qrcode === 'undefined') {
                        throw new Error('Html5Qrcode tidak terdefinisi');
                    }

                    const cameras = await Html5Qrcode.getCameras();
                    if (!cameras || cameras.length === 0) {
                        scanStatus.innerText = 'Tidak menemukan kamera.';
                        return;
                    }

                    // populate select
                    cameraSelect.innerHTML = '';
                    cameras.forEach(cam => {
                        const opt = document.createElement('option');
                        opt.value = cam.id;
                        opt.text = cam.label || cam.id;
                        cameraSelect.appendChild(opt);
                    });

                    function startCamera(cameraId) {
                        if (currentScanner) {
                            currentScanner.stop().catch(()=>{}).finally(() => {
                                currentScanner.clear();
                                currentScanner = new Html5Qrcode('reader');
                                currentScanner.start(cameraId, { fps: 10, qrbox: 250 }, onScanSuccess)
                                    .catch(err => { scanStatus.innerText = 'Gagal mengakses kamera: ' + (err.message || err); console.error(err); });
                            });
                        } else {
                            currentScanner = new Html5Qrcode('reader');
                            currentScanner.start(cameraId, { fps: 10, qrbox: 250 }, onScanSuccess)
                                .catch(err => { scanStatus.innerText = 'Gagal mengakses kamera: ' + (err.message || err); console.error(err); });
                        }
                        currentCameraId = cameraId;
                    }

                    // start with first
                    startCamera(cameras[0].id);

                    cameraSelect.addEventListener('change', function () {
                        const newId = this.value;
                        if (newId && newId !== currentCameraId) startCamera(newId);
                    });

                } catch (err) {
                    scanStatus.innerText = 'Gagal menginisialisasi kamera: ' + (err.message || err);
                    console.error(err);
                }
            }

            initCameras();
        });
    </script>
@endpush