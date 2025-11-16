@props([
    'leaveRequests' => collect(),
    'mode' => 'guru', // 'guru' or 'murid'
    'showPagination' => false, // Show pagination (for murid)
    'detailModalFunction' => 'showDetailModal'
])

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 d-flex align-items-center">
                    <i class="bx bx-list-ul me-2"></i>
                    Riwayat Permohonan Izin
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                @if($mode === 'guru')
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Tanggal Izin</th>
                                    <th>Jenis Izin</th>
                                    <th>Status</th>
                                    <th>Pengganti</th>
                                    <th>Tanggal Ajukan</th>
                                    <th>Aksi</th>
                                @else
                                    <th>No</th>
                                    <th>Jenis Izin</th>
                                    <th>Tanggal</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Tanggal Ajukan</th>
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($mode === 'guru')
                                @forelse($leaveRequests as $i => $request)
                                    @php
                                        // Get all timetables for this request
                                        $allTimetables = collect();
                                        if ($request->timetables && $request->timetables->count() > 0) {
                                            foreach ($request->timetables as $timetablePivot) {
                                                if ($timetablePivot->timetable) {
                                                    $allTimetables->push($timetablePivot->timetable);
                                                }
                                            }
                                        } else {
                                            if ($request->timetable) {
                                                $allTimetables->push($request->timetable);
                                            }
                                        }
                                        
                                        // Get unique classes
                                        $uniqueClasses = $allTimetables->map(function($timetable) {
                                            return $timetable->classSubject->class->name;
                                        })->unique()->values();
                                        $totalClasses = $uniqueClasses->count();
                                    @endphp
                                    @if($totalClasses > 0)
                                        @foreach($uniqueClasses as $idx => $className)
                                            <tr>
                                                @if($idx === 0)
                                                    <td rowspan="{{ $totalClasses }}">{{ $i + 1 }}</td>
                                                @endif
                                                <td>
                                                    <strong>{{ $className }}</strong>
                                                </td>
                                                @if($idx === 0)
                                                    <td rowspan="{{ $totalClasses }}">
                                                        @if($request->end_date && $request->end_date != $request->leave_date)
                                                            {{ $request->leave_date->format('d M Y') }} - {{ $request->end_date->format('d M Y') }}
                                                            <br><small class="text-muted">({{ $request->leave_date->diffInDays($request->end_date) + 1 }} hari)</small>
                                                        @else
                                                            {{ $request->leave_date->format('d M Y') }}
                                                        @endif
                                                    </td>
                                                    <td rowspan="{{ $totalClasses }}">
                                                        <span class="badge bg-{{ $request->leave_type === 'sakit' ? 'danger' : ($request->leave_type === 'izin' ? 'secondary' : ($request->leave_type === 'keperluan-keluarga' ? 'info' : 'primary')) }}">
                                                            {{ $request->leave_type_display }}
                                                        </span>
                                                    </td>
                                                    <td rowspan="{{ $totalClasses }}">
                                                        <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                                            @if($request->status === 'pending')
                                                                Menunggu
                                                            @elseif($request->status === 'approved')
                                                                Disetujui
                                                            @elseif($request->status === 'rejected')
                                                                Ditolak
                                                            @else
                                                                {{ ucfirst($request->status) }}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td rowspan="{{ $totalClasses }}">
                                                        @if($request->substitute)
                                                            {{ $request->substitute->full_name }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td rowspan="{{ $totalClasses }}">{{ $request->created_at->format('d M Y') }}</td>
                                                    <td rowspan="{{ $totalClasses }}">
                                                        <button class="btn btn-sm btn-outline-primary" onclick="{{ $detailModalFunction }}({{ $request->id }})">
                                                            <i class="bx bx-show"></i> Detail
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td colspan="7" class="text-center text-muted">Data jadwal tidak ditemukan</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-inbox fs-48 d-block mx-auto mb-2"></i>
                                                Belum ada riwayat permohonan izin
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                @forelse($leaveRequests as $i => $request)
                                    @php
                                        $startDate = \Carbon\Carbon::parse($request->start_date);
                                        $endDate = \Carbon\Carbon::parse($request->end_date);
                                        $duration = $startDate->diffInDays($endDate) + 1;
                                        $leaveTypeDisplay = $request->leave_type_display ?? ucfirst($request->leave_type);
                                        // Calculate correct sequential number across pages
                                        $sequentialNumber = ($leaveRequests->currentPage() - 1) * $leaveRequests->perPage() + $i + 1;
                                    @endphp
                                    <tr>
                                        <td>{{ $sequentialNumber }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->leave_type === 'sakit' ? 'danger' : ($request->leave_type === 'izin' ? 'secondary' : ($request->leave_type === 'keperluan-keluarga' ? 'info' : 'primary')) }}">
                                                {{ $leaveTypeDisplay }}
                                            </span>
                                        </td>
                                        <td>{{ $startDate->format('d M Y') }} @if($duration > 1) - {{ $endDate->format('d M Y') }} @endif</td>
                                        <td>{{ $duration }} hari</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                                @if($request->status === 'pending')
                                                    Menunggu
                                                @elseif($request->status === 'approved')
                                                    Disetujui
                                                @elseif($request->status === 'rejected')
                                                    Ditolak
                                                @else
                                                    {{ ucfirst($request->status) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('d M Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="{{ $detailModalFunction }}({{ $request->id }})">
                                                <i class="bx bx-show"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-inbox fs-48 d-block mx-auto mb-2"></i>
                                                Belum ada riwayat permohonan izin
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
                
                @if($showPagination && $mode === 'murid' && $leaveRequests->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top" id="pagination-wrapper">
                        <div class="text-muted">
                            Menampilkan {{ $leaveRequests->firstItem() }} sampai {{ $leaveRequests->lastItem() }} dari {{ $leaveRequests->total() }} data
                        </div>
                        <div class="d-flex">
                            {{ $leaveRequests->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

