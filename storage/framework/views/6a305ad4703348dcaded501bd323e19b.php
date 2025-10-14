<?php $__env->startSection('title', 'Riwayat Absensi'); ?>


<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Riwayat Absensi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard-murid')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Absensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
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
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inisialisasi Flatpickr untuk filter rentang tanggal
        flatpickr("#date-range", {
            mode: "range",
            dateFormat: "d-m-Y",
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views\murid\riwayat-absensi.blade.php ENDPATH**/ ?>