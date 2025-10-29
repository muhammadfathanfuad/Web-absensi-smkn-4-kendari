# Dokumentasi: Cara Kerja Menu Status Absensi untuk Guru

## Gambaran Umum

Menu Status Absensi memungkinkan guru melihat rekap kehadiran siswa berdasarkan mata pelajaran dan tanggal tertentu. Sistem ini memanfaatkan relasi database antara Attendance, ClassSession, Timetable, dan ClassSubject.

## Route dan Controller

**Route:**

```php
Route::get('/status-absensi', [AbsensiController::class, 'showStatus'])
    ->name('guru.status-absensi');
```

**Controller:** `app/Http/Controllers/Guru/AbsensiController.php`
**Method:** `showStatus(Request $request)`

---

## Cara Kerja Sistem

### 1. **Controller Logic (`showStatus` Method)**

```php
public function showStatus(Request $request)
{
    // 1. Ambil semua mata pelajaran untuk dropdown filter
    $subjects = Subject::orderBy('name')->get();

    // 2. Ambil parameter filter dari request
    $selectedSubjectId = $request->input('subject_id');  // Mata pelajaran yang dipilih
    $selectedDate = $request->input('date', TimeOverrideService::today());  // Tanggal (default: hari ini)

    // 3. Query dasar: Ambil semua attendance dengan relasi
    $query = Attendance::with([
        'student.user',
        'classSession.timetable.classSubject.subject'
    ])
    ->whereHas('classSession', function ($q) use ($selectedDate) {
        $q->where('date', $selectedDate);  // Filter berdasarkan tanggal
    });

    // 4. Filter tambahan: Filter berdasarkan mata pelajaran jika dipilih
    if ($selectedSubjectId) {
        $query->whereHas('classSession.timetable.classSubject', function ($q) use ($selectedSubjectId) {
            $q->where('subject_id', $selectedSubjectId);
        });
    }

    // 5. Ambil data dan urutkan berdasarkan ID terbaru
    $attendances = $query->latest('id')->get();

    // 6. Kirim ke view
    return view('guru.status-absensi', compact(
        'subjects',      // List mata pelajaran untuk dropdown
        'attendances',   // Data attendance yang sudah di-filter
        'selectedSubjectId',
        'selectedDate'
    ));
}
```

---

## Struktur Data dan Relasi

### Database Flow:

```
attendance_sessions (Session absensi)
    ↓
class_sessions (Sesi kelas untuk tanggal tertentu)
    ↓
timetables (Jadwal pelajaran)
    ↓
class_subjects (Subjek per kelas)
    ↓
students (Siswa)
```

### Relasi Model:

1. **Attendance** → **Student** (belongsTo)
    - `student_id` → `students.user_id`
2. **Attendance** → **ClassSession** (belongsTo)

    - `class_session_id` → `class_sessions.id`

3. **ClassSession** → **Timetable** (belongsTo)

    - `timetable_id` → `timetables.id`

4. **Timetable** → **ClassSubject** (belongsTo)

    - `class_subject_id` → `class_subjects.id`

5. **ClassSubject** → **Subject** (belongsTo)
    - `subject_id` → `subjects.id`

---

## Query Breakdown

### Query Dasar:

```php
Attendance::with([
    'student.user',                                    // Eager load student + user
    'classSession.timetable.classSubject.subject'     // Eager load class session → timetable → class subject → subject
])
```

### Filter Berdasarkan Tanggal:

```php
->whereHas('classSession', function ($q) use ($selectedDate) {
    $q->where('date', $selectedDate);
})
```

-   Hanya mengambil attendance yang ada pada tanggal tertentu
-   Filter di `class_sessions` table

### Filter Berdasarkan Mata Pelajaran:

```php
if ($selectedSubjectId) {
    $query->whereHas('classSession.timetable.classSubject', function ($q) use ($selectedSubjectId) {
        $q->where('subject_id', $selectedSubjectId);
    });
}
```

-   Hanya mengambil attendance untuk mata pelajaran tertentu
-   Filter melalui relasi: ClassSession → Timetable → ClassSubject → Subject

---

## View: Form Filter dan Tabel

### Form Filter:

```html
<form action="{{ route('guru.status-absensi') }}" method="GET">
    <div class="row g-3 align-items-end">
        <!-- Dropdown Mata Pelajaran -->
        <div class="col-md-5">
            <select name="subject_id" id="subject_id" class="form-select">
                <option value="">Semua Mapel</option>
                @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Input Tanggal -->
        <div class="col-md-5">
            <input
                type="date"
                name="date"
                value="{{ $selectedDate }}"
                class="form-control"
            />
        </div>

        <!-- Tombol Filter -->
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>
```

### Tabel Attendance:

```html
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Mata Pelajaran</th>
            <th>Jam Masuk</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($attendances as $absen)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $absen->student->nis }}</td>
            <td>{{ $absen->student->user->full_name }}</td>
            <td>
                {{ $absen->classSession->timetable->classSubject->subject->name
                }}
            </td>
            <td>{{ $absen->check_in_time }}</td>
            <td>
                <!-- Status badge dengan logika khusus -->
                @if($absen->status == 'S')
                <span class="badge bg-soft-warning">Sakit</span>
                @elseif($absen->status == 'I')
                <span class="badge bg-soft-info">Izin</span>
                @elseif($absen->status == 'T')
                <span class="badge bg-soft-danger">Terlambat</span>
                @elseif($absen->status == 'H')
                <span class="badge bg-soft-success">Hadir</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>
```

---

## Logika Status Badge

### Kode Status:

-   **'H'** → Hadir (badge hijau)
-   **'T'** → Terlambat (badge merah)
-   **'I'** → Izin (badge biru)
-   **'S'** → Sakit (badge kuning)

### Prioritas Status:

```php
1. Sakit ('S')       → Badge kuning
2. Izin ('I')        → Badge biru
3. Terlambat ('T')   → Badge merah
4. Hadir ('H')       → Badge hijau
5. Other             → Badge abu-abu (default)
```

### Fallback Logic:

```php
@elseif($absen->status == 'T' || ($absen->notes === 'Terlambat' && $absen->status !== 'H'))
```

-   Deteksi terlambat berdasarkan status atau notes
-   Fallback jika status tidak ter-set dengan benar

---

## Contoh Penggunaan

### Filter: Semua Mata Pelajaran, Hari Ini

```
Query: GET /status-absensi?date=2024-01-15
Result: Semua attendance hari ini untuk semua mata pelajaran
```

### Filter: Matematika, Kemarin

```
Query: GET /status-absensi?subject_id=5&date=2024-01-14
Result: Semua attendance kemarin untuk mata pelajaran Matematika
```

### Filter: Semua Mata Pelajaran, Tanggal Tertentu

```
Query: GET /status-absensi?date=2024-01-10
Result: Semua attendance pada tanggal 10 Januari 2024
```

---

## Kelebihan Sistem

1. **Relasi Database Efisien**

    - Menggunakan eager loading untuk menghindari N+1 query
    - Relasi dalam satu query

2. **Filtering Flexible**

    - Bisa filter per mata pelajaran
    - Bisa filter per tanggal
    - Keduanya bisa dikombinasikan

3. **Real-time Data**

    - Data langsung dari database
    - Tidak ada cache yang kadaluarsa

4. **User-friendly UI**
    - Form filter sederhana
    - Badge warna untuk status
    - Empty state handling

---

## Tips Penggunaan

1. **Filter Default**: Hari ini jika tidak dipilih
2. **Semua Mapel**: Pilih "Semua Mapel" untuk melihat semua data
3. **Tanggal Custom**: Pilih tanggal untuk lihat data historis
4. **Empty State**: Muncul jika tidak ada data untuk filter

---

## Troubleshooting

### Data Tidak Muncul:

-   Pastikan ada ClassSession untuk tanggal tersebut
-   Pastikan ada Attendance yang ter-record
-   Periksa filter mata pelajaran

### Badge Status Salah:

-   Periksa kolom `status` di tabel attendance
-   Periksa kolom `notes` untuk fallback
-   Pastikan data ter-save dengan benar

---

_Documentation created: 2024_
