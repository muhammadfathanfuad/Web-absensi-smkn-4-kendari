

<?php $__env->startSection('content'); ?>

<?php
    // Definisikan status dan warna badge di sini agar mudah dikelola
    $statusMap = [
        'H' => ['text' => 'Hadir', 'color' => 'success'],
        'S' => ['text' => 'Sakit', 'color' => 'warning'],
        'I' => ['text' => 'Izin', 'color' => 'info'],
        'A' => ['text' => 'Alpha', 'color' => 'danger'],
        null => ['text' => 'Belum Absen', 'color' => 'secondary'],
    ];
?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Status Absensi', 'subtitle' => 'Guru'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <form action="<?php echo e(route('status-absensi')); ?>" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="kelas_id" class="form-label">Pilih Kelas</label>
                            <select class="form-select" id="kelas_id" name="kelas_id">
                                <option selected disabled>-- Semua Kelas --</option>
                                <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k['id']); ?>"><?php echo e($k['nama']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="mapel_id" class="form-label">Pilih Mata Pelajaran</label>
                            <select class="form-select" id="mapel_id" name="mapel_id">
                                <option selected disabled>-- Semua Mapel --</option>
                                 <?php $__currentLoopData = $mapel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m['id']); ?>"><?php echo e($m['nama']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </div>
                </form>
                

                <hr class="my-4">

                
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
                            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($student['nis']); ?></td>
                                    <td><?php echo e($student['nama']); ?></td>
                                    <td><?php echo e($student['jk']); ?></td>
                                    <td class="text-center">
                                        <?php
                                            $status = $student['status'] ?? null;
                                            $statusInfo = $statusMap[$status];
                                        ?>
                                        <span class="badge bg-soft-<?php echo e($statusInfo['color']); ?> text-<?php echo e($statusInfo['color']); ?> fs-12">
                                            <?php echo e($statusInfo['text']); ?>

                                        </span>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td class="text-center">
                                        
                                        <?php if($student['status'] !== 'H'): ?>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-warning">Sakit</button>
                                                <button type="button" class="btn btn-outline-info">Izin</button>
                                            </div>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Silakan pilih filter untuk menampilkan data siswa.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    
                    <button type="submit" class="btn btn-success">Selesaikan Sesi Absensi</button>
                </div>
                

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row-->

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\PresenZ\Web-absensi-smkn-4-kendari\resources\views/guru/status-absensi.blade.php ENDPATH**/ ?>