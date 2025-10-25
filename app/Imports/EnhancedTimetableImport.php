<?php

namespace App\Imports;

use App\Models\Timetable;
use App\Models\Term;
use App\Models\ClassSubject;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class EnhancedTimetableImport implements ToCollection
{
    private $headers = [];
    private $processedCount = 0;
    private $grade;
    private $weekType;
    private $termId;
    private $currentHari;
    private $detectedFormat = null;

    public function __construct($grade = null, $weekType = null, $termId = null)
    {
        $this->grade = $grade;
        $this->weekType = $weekType;
        $this->termId = $termId;
        $this->currentHari = null;
    }

    public function collection(Collection $rows)
    {
        $term = $this->termId ? Term::find($this->termId) : Term::where('is_active', true)->latest()->first();
        if (!$term) {
            throw new \Exception('Semester tidak ditemukan atau tidak ada term aktif.');
        }

        $daysMap = [
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            'sabtu' => 6,
            'minggu' => 7,
        ];

        $this->processedCount = 0;
        $errors = [];

        // Detect format first
        $this->detectFormat($rows);

        foreach ($rows as $index => $row) {
            Log::info("Processing row {$index}: " . json_encode($row->toArray()));

            // Skip empty rows
            if (empty($row[0]) && empty($row[1])) {
                continue;
            }

            // Process based on detected format
            if ($this->detectedFormat === 'format1') {
                $this->processFormat1($row, $index, $term->id, $daysMap, $errors);
            } elseif ($this->detectedFormat === 'format2') {
                $this->processFormat2($row, $index, $term->id, $daysMap, $errors);
            } elseif ($this->detectedFormat === 'format3') {
                $this->processFormat3($row, $index, $term->id, $daysMap, $errors);
            } else {
                // Try both formats
                $this->processFormat1($row, $index, $term->id, $daysMap, $errors);
            }
        }

        Log::info("Enhanced timetable import completed. Processed {$this->processedCount} entries.");
        
        if (!empty($errors)) {
            Log::warning("Import completed with errors: " . implode('; ', $errors));
        }
    }

    private function detectFormat(Collection $rows)
    {
        if (count($rows) < 2) {
            $this->detectedFormat = 'format1';
            return;
        }

        // Check for Format 3: Class names in row 1, HARI/JAM/WAKTU in row 2
        $row0 = $rows[0];
        $row1 = $rows[1];
        $row2 = $rows[2];
        
        // Check if row 1 contains class names (TKJA, TKJB, etc.)
        $hasClassNames = false;
        $classCount = 0;
        for ($i = 3; $i < count($row1); $i++) { // Start from column 3 (after HARI, JAM, WAKTU)
            if (isset($row1[$i]) && trim($row1[$i]) !== '' && 
                preg_match('/^[A-Z]{2,4}[A-Z]?$/', trim($row1[$i]))) {
                $classCount++;
            }
        }
        
        if ($classCount >= 3) {
            $hasClassNames = true;
        }
        
        // Check if row 2 has HARI, JAM, WAKTU headers or data
        $hasHeaders = false;
        if (isset($row2[0]) && isset($row2[1]) && isset($row2[2])) {
            $col0 = strtolower(trim($row2[0]));
            $col1 = strtolower(trim($row2[1]));
            $col2 = strtolower(trim($row2[2]));
            
            if (($col0 === 'hari' || $col0 === 'senin') && 
                ($col1 === 'jam' || $col1 === '0') && 
                ($col2 === 'waktu' || $col2 === '07.00 - 08.00')) {
                $hasHeaders = true;
            }
        }
        
        if ($hasClassNames && $hasHeaders) {
            $this->detectedFormat = 'format3';
            $this->headers = $row1->toArray(); // Class names are in row 1
            Log::info("Detected Format 3: Class names in row 1, HARI/JAM/WAKTU in row 2");
            return;
        }

        // Check for Format 1: HARI, WAKTU, KELAS1, KELAS2, ...
        $firstRow = $rows[0];
        $hasHariWaktu = false;
        $hasClassColumns = false;

        if (isset($firstRow[0]) && isset($firstRow[1])) {
            $col0 = strtolower(trim($firstRow[0]));
            $col1 = strtolower(trim($firstRow[1]));
            
            if (($col0 === 'hari' || $col0 === 'hari') && 
                ($col1 === 'waktu' || $col1 === 'jam')) {
                $hasHariWaktu = true;
            }
        }

        // Check for class columns (starting from index 2)
        $classCount = 0;
        for ($i = 2; $i < count($firstRow); $i++) {
            if (isset($firstRow[$i]) && trim($firstRow[$i]) !== '') {
                $classCount++;
            }
        }

        if ($classCount >= 3) {
            $hasClassColumns = true;
        }

        if ($hasHariWaktu && $hasClassColumns) {
            $this->detectedFormat = 'format1';
            Log::info("Detected Format 1: HARI, WAKTU, KELAS columns");
            return;
        }

        // Check for Format 2: Title rows + HARI, JAM, WAKTU, KELAS
        $titleRowFound = false;
        $headerRowFound = false;
        
        for ($i = 0; $i < min(5, count($rows)); $i++) {
            $row = $rows[$i];
            $rowArray = $row->toArray();
            $rowStr = strtolower(implode('|', array_filter($rowArray, function($cell) {
                return $cell !== null && trim($cell) !== '';
            })));
            
            if (strpos($rowStr, 'jadwal') !== false || strpos($rowStr, 'kelas') !== false) {
                $titleRowFound = true;
            }
            
            if (strpos($rowStr, 'hari') !== false && strpos($rowStr, 'waktu') !== false) {
                $headerRowFound = true;
                $this->headers = $row->toArray();
                break;
            }
        }

        if ($titleRowFound && $headerRowFound) {
            $this->detectedFormat = 'format2';
            Log::info("Detected Format 2: Title + HARI, JAM, WAKTU, KELAS format");
            return;
        }

        // Default to format1
        $this->detectedFormat = 'format1';
        Log::info("Defaulting to Format 1");
    }

    private function processFormat1($row, $index, $termId, $daysMap, &$errors)
    {
        // Original format processing
        if ($index === 0) {
            $this->headers = $row->toArray();
            return;
        }

        $hari = strtolower(trim($row[0] ?? ''));
        $waktu = trim($row[1] ?? '');

        if (empty($hari) && !empty($waktu)) {
            $hari = $this->currentHari;
        }

        if (!empty($hari)) {
            $this->currentHari = $hari;
        }

        if (empty($hari) || empty($waktu)) {
            return;
        }

        $this->processTimeSlot($row, $index, $termId, $daysMap, $hari, $waktu, $errors);
    }

    private function processFormat2($row, $index, $termId, $daysMap, &$errors)
    {
        // Skip title rows
        if ($index < 4) {
            return;
        }

        $hari = strtolower(trim($row[0] ?? ''));
        $waktu = trim($row[2] ?? ''); // Format 2: HARI, JAM, WAKTU, KELAS

        if (empty($hari) && !empty($waktu)) {
            $hari = $this->currentHari;
        }

        if (!empty($hari)) {
            $this->currentHari = $hari;
        }

        if (empty($hari) || empty($waktu)) {
            return;
        }

        $this->processTimeSlot($row, $index, $termId, $daysMap, $hari, $waktu, $errors);
    }

    private function processFormat3($row, $index, $termId, $daysMap, &$errors)
    {
        // Skip header rows (row 0 and row 1)
        if ($index <= 1) {
            return;
        }

        // Skip empty rows
        if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
            return;
        }

        $hari = strtolower(trim($row[0] ?? ''));
        $waktu = trim($row[2] ?? ''); // Format 3: HARI, JAM, WAKTU, KELAS

        // If hari is empty but waktu is not, use the previous day
        if (empty($hari) && !empty($waktu)) {
            $hari = $this->currentHari;
        }

        if (!empty($hari)) {
            $this->currentHari = $hari;
        }

        if (empty($hari) || empty($waktu)) {
            return;
        }

        $this->processTimeSlot($row, $index, $termId, $daysMap, $hari, $waktu, $errors);
    }

    private function processTimeSlot($row, $index, $termId, $daysMap, $hari, $waktu, &$errors)
    {
        $timeParts = preg_split('/\s*-\s*/', $waktu);
        if (count($timeParts) !== 2) {
            return;
        }

        $startTime = str_replace('.', ':', $timeParts[0]) . ':00';
        $endTime = str_replace('.', ':', $timeParts[1]) . ':00';

        $dayOfWeek = $daysMap[$hari] ?? null;
        if (!$dayOfWeek) {
            return;
        }

        // Process class columns
        $startCol = $this->detectedFormat === 'format2' ? 3 : ($this->detectedFormat === 'format3' ? 3 : 2);
        
        for ($colIndex = $startCol; $colIndex < count($row); $colIndex++) {
            $classInfo = trim($row[$colIndex] ?? '');
            if (empty($classInfo)) {
                continue;
            }

            $className = $this->getClassName($colIndex);
            if (empty($className)) {
                continue;
            }

            if ($this->isSpecialEntry($classInfo)) {
                continue;
            }

            try {
                $this->processClassInfo($className, $classInfo, $termId, $dayOfWeek, $startTime, $endTime);
                $this->processedCount++;
            } catch (\Exception $e) {
                $errorMsg = "Error processing class {$className} in row {$index}: " . $e->getMessage();
                Log::error($errorMsg);
                $errors[] = $errorMsg;
            }
        }
    }

    private function getClassName($colIndex)
    {
        if ($this->detectedFormat === 'format2') {
            // For format 2, class names are in a separate row
            // We need to find the class name row
            return $this->headers[$colIndex] ?? '';
        } elseif ($this->detectedFormat === 'format3') {
            // For format 3, class names are in row 0 (headers)
            return $this->headers[$colIndex] ?? '';
        } else {
            // For format 1, class names are in headers
            return $this->headers[$colIndex] ?? '';
        }
    }

    private function isSpecialEntry($classInfo)
    {
        $specialEntries = [
            'UPACARA BENDERA',
            'ISTRAHAT, SHOLAT DAN MAKAN',
            'ISTIRAHAT',
            'SHOLAT',
            'MAKAN',
            'UPACARA',
            'BENDERA',
            'BREAK',
            'REST',
            'LUNCH',
            'PRAYER',
            'PELAJARAN KOSONG',
            'KOSONG',
            'LIBUR',
            'HOLIDAY',
            'OFF',
            'FREE',
        ];
        
        $classInfoUpper = strtoupper(trim($classInfo));
        
        if (in_array($classInfoUpper, $specialEntries)) {
            return true;
        }
        
        foreach ($specialEntries as $special) {
            if (strpos($classInfoUpper, $special) !== false) {
                return true;
            }
        }
        
        if (strpos($classInfo, '/') === false) {
            return true;
        }
        
        return false;
    }

    private function processClassInfo($className, $classInfo, $termId, $dayOfWeek, $startTime, $endTime)
    {
        $classInfo = str_replace([' ', '\\/'], ['', '/'], $classInfo);
        $parts = explode('/', $classInfo);
        if (count($parts) !== 2) {
            throw new \Exception("Invalid class info format: {$classInfo}. Expected format: 'SubjectCode/TeacherCode'");
        }

        $subjectCode = trim($parts[0]);
        $teacherCode = trim($parts[1]);

        // Find teacher with flexible matching
        $teacher = Teacher::where('kode_guru', $teacherCode)->first();
        
        if (!$teacher && is_numeric($teacherCode) && strlen($teacherCode) > 1 && $teacherCode[0] === '0') {
            $teacherCodeWithoutZero = ltrim($teacherCode, '0');
            $teacher = Teacher::where('kode_guru', $teacherCodeWithoutZero)->first();
        }
        
        if (!$teacher && is_numeric($teacherCode) && strlen($teacherCode) == 1) {
            $teacherCodeWithZero = str_pad($teacherCode, 2, '0', STR_PAD_LEFT);
            $teacher = Teacher::where('kode_guru', $teacherCodeWithZero)->first();
        }
        
        if (!$teacher) {
            throw new \Exception("Teacher with kode_guru '{$teacherCode}' not found.");
        }

        // Find subject
        $subject = Subject::where('code', $subjectCode)->first();
        if (!$subject) {
            throw new \Exception("Subject with code '{$subjectCode}' not found.");
        }

        // Create or find classroom with proper grade filtering
        $gradeMap = ['X' => '10', 'XI' => '11', 'XII' => '12'];
        $numericGrade = $gradeMap[$this->grade] ?? $this->grade;
        
        if (!$this->grade) {
            throw new \Exception("Grade is required to create new classroom '{$className}'.");
        }
        
        // First, try to find existing classroom with exact name and grade
        $classroom = Classroom::where('name', $className)
            ->where('grade', $numericGrade)
            ->first();
        
        if (!$classroom) {
            // Check if classroom exists with different grade
            $existingClassroom = Classroom::where('name', $className)->first();
            
            if ($existingClassroom) {
                // Classroom exists but with different grade
                Log::info("Classroom '{$className}' exists with grade {$existingClassroom->grade}, but we need grade {$numericGrade}. Creating new classroom with grade suffix.");
                
                // Create new classroom with grade suffix to avoid unique constraint violation
                $newClassName = $className . '-' . $this->grade;
                
                // Check if the new classroom name already exists
                $existingNewClassroom = Classroom::where('name', $newClassName)->first();
                if ($existingNewClassroom) {
                    // Use the existing classroom with grade suffix
                    $classroom = $existingNewClassroom;
                    Log::info("Found existing classroom with grade suffix: {$newClassName} with grade {$existingNewClassroom->grade}");
                } else {
                    // Create new classroom with grade suffix
                    $classroom = Classroom::create([
                        'name' => $newClassName,
                        'grade' => $numericGrade,
                    ]);
                    Log::info("Created new classroom: {$newClassName} with grade {$numericGrade}");
                }
            } else {
                // Classroom doesn't exist at all, create new one
                try {
                    $classroom = Classroom::create([
                        'name' => $className,
                        'grade' => $numericGrade,
                    ]);
                    Log::info("Created new classroom: {$className} with grade {$numericGrade}");
                } catch (\Illuminate\Database\QueryException $e) {
                    if ($e->getCode() == 23000) { // Duplicate entry
                        // Try to find the existing classroom
                        $classroom = Classroom::where('name', $className)->first();
                        if (!$classroom) {
                            throw new \Exception("Classroom '{$className}' already exists but could not be found.");
                        }
                    } else {
                        throw $e;
                    }
                }
            }
        } else {
            Log::info("Found existing classroom: {$className} with grade {$numericGrade}");
        }

        // Create class_subject
        $classSubject = ClassSubject::where('class_id', $classroom->id)
            ->where('subject_id', $subject->id)
            ->where('teacher_id', $teacher->user_id)
            ->first();

        if (!$classSubject) {
            $classSubject = ClassSubject::create([
                'class_id' => $classroom->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->user_id,
            ]);
        }

        // Create timetable
        $timetableData = [
            'term_id' => $termId,
            'class_subject_id' => $classSubject->id,
            'day_of_week' => $dayOfWeek,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'type' => 'teori',
        ];

        if (in_array($this->grade, ['XI', 'XII']) && $this->weekType) {
            $timetableData['week_type'] = $this->weekType;
        }

        $existingQuery = Timetable::where('term_id', $termId)
            ->where('class_subject_id', $classSubject->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', $startTime)
            ->where('end_time', $endTime);

        if (in_array($this->grade, ['XI', 'XII']) && $this->weekType) {
            $existingQuery->where('week_type', $this->weekType);
        }

        $existingTimetable = $existingQuery->first();

        if (!$existingTimetable) {
            Timetable::create($timetableData);
        }
    }

    public function getProcessedCount()
    {
        return $this->processedCount;
    }
}
