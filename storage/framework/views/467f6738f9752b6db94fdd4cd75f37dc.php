<?php $__env->startSection('title', 'Pemindai Kode QR'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Pemindai Kode QR</h4>
        <p class="card-subtitle mb-4">Arahkan kamera ke kode QR untuk memindai.</p>

        <div id="scanner-container" class="mx-auto text-center" style="max-width: 500px;">
            <video id="video" playsinline hidden style="width: 100%; border-radius: 8px;"></video>
            <canvas id="canvas" style="display: none;"></canvas>

            <div id="result" class="mt-3 p-2 rounded" style="background-color: #f0f2f5;">Hasil pindaian akan muncul di sini.</div>

            <button id="scanBtn" class="btn btn-primary mt-3">Mulai Pindai</button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
    // DEBUG #1: Cek apakah blok script ini dimuat
    console.log("DEBUG #1: Blok script pemindai QR sudah dimuat.");

    document.addEventListener('DOMContentLoaded', () => {
        // DEBUG #2: Cek apakah DOM sudah siap
        console.log("DEBUG #2: DOM Content Loaded, script siap dijalankan.");

        const video = document.getElementById('video');
        const canvasElement = document.getElementById('canvas');
        const canvas = canvasElement.getContext('2d');
        const scanBtn = document.getElementById('scanBtn');
        const resultContainer = document.getElementById('result');
        let stream = null;

        if (scanBtn) {
            // DEBUG #3: Cek apakah tombol ditemukan dan listener dipasang
            console.log("DEBUG #3: Tombol 'scanBtn' ditemukan. Memasang event listener.");

            scanBtn.addEventListener('click', async () => {
                // DEBUG #4: Cek apakah tombol berhasil diklik
                console.log("DEBUG #4: Tombol 'Mulai Pindai' diklik. Mencoba mengakses kamera...");
                
                try {
                    scanBtn.style.display = 'none';
                    video.hidden = false;
                    resultContainer.textContent = "Arahkan kamera ke kode QR...";

                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'environment'
                        }
                    });

                    video.srcObject = stream;
                    video.play();
                    requestAnimationFrame(tick);

                    // Membalikkan video (flip horizontal)
                    video.style.transform = 'scaleX(-1)';  // Membalikkan video

                } catch (err) {
                    console.error("ERROR saat mengakses kamera: ", err);
                    resultContainer.textContent = "Gagal mengakses kamera. Pastikan Anda memberikan izin dan menggunakan koneksi HTTPS.";
                    scanBtn.style.display = 'block';
                }
            });
        } else {
            // DEBUG ERROR: Tombol tidak ditemukan
            console.error("DEBUG ERROR: Tombol dengan id 'scanBtn' tidak ditemukan!");
        }

        function tick() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);

                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code) {
                    drawResult(code.data);
                    stopCamera();
                    return;
                }
            }
            if (stream && stream.active) {
                requestAnimationFrame(tick);
            }
        }

        function drawResult(data) {
            if (data.startsWith('http')) {
                resultContainer.innerHTML = `Kode terdeteksi: <a href="${data}" target="_blank">${data}</a>`;
            } else {
                resultContainer.textContent = `Kode terdeteksi: ${data}`;
            }
            console.log("Kode QR terdeteksi:", data);
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.hidden = true;
                scanBtn.style.display = 'block';
                scanBtn.textContent = 'Pindai Lagi';
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.vertical-guru', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/scan-qr.blade.php ENDPATH**/ ?>