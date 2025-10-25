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

class TimetableImport implements ToCollection
{
    private $headers = [];
    private $processedCount = 0;
    private $grade;
    private $weekType;
    private $className;
    private $currentHari;

    public function __construct($grade = null, $weekType = null)
    {
        $this->grade = $grade;
        $this->weekType = $weekType;
        $this->className = null;
        $this->currentHari = null;
    }

    public function collection(Collection $rows)
    {
        $term = Term::where('is_active', true)->latest()->first();
        if (!$term) {
            throw new \Exception('Tidak ada term aktif. Silakan buat term aktif terlebih dahulu.');
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

        foreach ($rows as $index => $row) {
            // Debug: Log the row data
            Log::info("Processing row {$index}: " . json_encode($row->toArray()));

            // Get headers from first row
            if ($index === 0) {
                $this->headers = $row->toArray();
                Log::info("Headers detected: " . json_encode($this->headers));
                continue;
            }

            // Skip empty rows
            if (empty($row[0]) && empty($row[1])) {
                continue;
            }

            $hari = strtolower(trim($row[0] ?? ''));
            $waktu = trim($row[1] ?? '');

            // If hari is empty but waktu is not, use the previous day
            if (empty($hari) && !empty($waktu)) {
                $hari = $this->currentHari;
            }

            // Update current day if we have a new day
            if (!empty($hari)) {
                $this->currentHari = $hari;
            }

            // Skip if hari or waktu is empty
            if (empty($hari) || empty($waktu)) {
                continue;
            }

            // Parse waktu (format: "07.00 - 08.00" or "08.00 - 08.40")
            // Handle both "07.00 - 08.00" and "07.00-08.00" formats
            $timeParts = preg_split('/\s*-\s*/', $waktu);
            if (count($timeParts) !== 2) {
                Log::warning("Invalid time format in row {$index}: {$waktu}");
                continue;
            }

            $startTime = str_replace('.', ':', $timeParts[0]) . ':00';
            $endTime = str_replace('.', ':', $timeParts[1]) . ':00';

            // Get day of week
            $dayOfWeek = $daysMap[$hari] ?? null;
            if (!$dayOfWeek) {
                Log::warning("Invalid day in row {$index}: {$hari}");
                continue;
            }

            // Process each class column (starting from index 2: TKJA, TKJB, etc.)
            for ($colIndex = 2; $colIndex < count($row); $colIndex++) {
                $classInfo = trim($row[$colIndex] ?? '');
                if (empty($classInfo)) {
                    continue;
                }

                $className = trim($this->headers[$colIndex] ?? '');
                if (empty($className)) {
                    Log::warning("Empty class name at column {$colIndex} in row {$index}");
                    continue;
                }

                // Skip special entries that don't follow the subject/teacher format
                if ($this->isSpecialEntry($classInfo)) {
                    Log::info("Skipping special entry: {$classInfo} for class {$className}");
                    continue;
                }

                Log::info("Processing class info: {$classInfo} for class {$className} at column {$colIndex}");
                
                try {
                    // Parse class info (format: "A4/39")
                    $this->processClassInfo($className, $classInfo, $term->id, $dayOfWeek, $startTime, $endTime);
                    $this->processedCount++;
                } catch (\Exception $e) {
                    $errorMsg = "Error processing class {$className} in row {$index}: " . $e->getMessage();
                    Log::error($errorMsg);
                    $errors[] = $errorMsg;
                }
            }
        }

        Log::info("Timetable import completed. Processed {$this->processedCount} entries.");
        
        if (!empty($errors)) {
            Log::warning("Import completed with errors: " . implode('; ', $errors));
        }
    }

    private function processClassInfo($className, $classInfo, $termId, $dayOfWeek, $startTime, $endTime)
    {
        // Expected format: "A4/39", "A4 \/ 39", or "A4 / 39" (subject code / teacher code)
        $classInfo = str_replace([' ', '\\/'], ['', '/'], $classInfo); // Remove spaces and convert \/ to /
        $parts = explode('/', $classInfo);
        if (count($parts) !== 2) {
            throw new \Exception("Invalid class info format: {$classInfo}. Expected format: 'SubjectCode/TeacherCode'");
        }

        $subjectCode = trim($parts[0]); // e.g., "A4"
        $teacherCode = trim($parts[1]); // e.g., "39"

        Log::info("Processing: Class {$className}, Subject {$subjectCode}, Teacher {$teacherCode}");

        // Find teacher by kode_guru
        $teacher = Teacher::where('kode_guru', $teacherCode)->first();
        
        // If not found, try with leading zero removed (e.g., '04' -> '4')
        if (!$teacher && is_numeric($teacherCode) && strlen($teacherCode) > 1 && $teacherCode[0] === '0') {
            $teacherCodeWithoutZero = ltrim($teacherCode, '0');
            $teacher = Teacher::where('kode_guru', $teacherCodeWithoutZero)->first();
            if ($teacher) {
                Log::info("Found teacher with code '{$teacherCodeWithoutZero}' for input '{$teacherCode}'");
            }
        }
        
        // If still not found, try with leading zero added (e.g., '4' -> '04')
        if (!$teacher && is_numeric($teacherCode) && strlen($teacherCode) == 1) {
            $teacherCodeWithZero = str_pad($teacherCode, 2, '0', STR_PAD_LEFT);
            $teacher = Teacher::where('kode_guru', $teacherCodeWithZero)->first();
            if ($teacher) {
                Log::info("Found teacher with code '{$teacherCodeWithZero}' for input '{$teacherCode}'");
            }
        }
        
        if (!$teacher) {
            throw new \Exception("Teacher with kode_guru '{$teacherCode}' not found. Please ensure the teacher exists in the database.");
        }

        // Find subject by code
        $subject = Subject::where('code', $subjectCode)->first();
        if (!$subject) {
            throw new \Exception("Subject with code '{$subjectCode}' not found. Please ensure the subject exists in the database.");
        }

        Log::info("Found teacher: " . ($teacher->user ? $teacher->user->full_name : 'N/A') . " (ID: {$teacher->user_id})");
        Log::info("Found subject: {$subject->name} (ID: {$subject->id})");

        // Convert grade to numeric value for storage
        $gradeMap = ['X' => '10', 'XI' => '11', 'XII' => '12'];
        $numericGrade = $gradeMap[$this->grade] ?? $this->grade;
        
        // Find or create classroom with base name (TKJA) and numeric grade
        $classroom = Classroom::where('name', $className)
            ->where('grade', $numericGrade)
            ->first();
        
        if (!$classroom) {
            // Create new classroom with base name and numeric grade
            if (!$this->grade) {
                throw new \Exception("Grade is required to create new classroom '{$className}'. Please specify grade in import form.");
            }
            
            $classroom = Classroom::create([
                'name' => $className, // Store as TKJA (without grade suffix)
                'grade' => $numericGrade, // Store as 10, 11, 12
            ]);
            Log::info("Created new classroom: {$classroom->name} with grade {$numericGrade}");
        } else {
            // Verify the grade matches
            if ($classroom->grade !== $numericGrade) {
                Log::warning("Classroom '{$className}' exists but with different grade. Expected: {$numericGrade}, Found: {$classroom->grade}");
                // Update the grade to match the import
                $classroom->update(['grade' => $numericGrade]);
                Log::info("Updated classroom '{$className}' grade to {$numericGrade}");
            }
        }

        // Find or create class_subject
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
            Log::info("Created new class_subject: {$classSubject->id}");
        }

        // Prepare timetable data
        $timetableData = [
            'term_id' => $termId,
            'class_subject_id' => $classSubject->id,
            'day_of_week' => $dayOfWeek,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'type' => 'teori', // Default to teori
        ];

        // Add week_type for XI and XII grades
        if (in_array($this->grade, ['XI', 'XII']) && $this->weekType) {
            $timetableData['week_type'] = $this->weekType;
        }

        // Check if timetable entry already exists to avoid duplicates
        $existingQuery = Timetable::where('term_id', $termId)
            ->where('class_subject_id', $classSubject->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', $startTime)
            ->where('end_time', $endTime);

        // Add week_type filter for XI and XII
        if (in_array($this->grade, ['XI', 'XII']) && $this->weekType) {
            $existingQuery->where('week_type', $this->weekType);
        }

        $existingTimetable = $existingQuery->first();

        if (!$existingTimetable) {
            // Create timetable entry
            $timetable = Timetable::create($timetableData);
            Log::info("Created timetable entry: {$timetable->id} for class {$className}, subject {$subjectCode}, teacher {$teacherCode}" . 
                     ($this->weekType ? " (week: {$this->weekType})" : ""));
        } else {
            Log::info("Timetable entry already exists for class {$className}, subject {$subjectCode}, teacher {$teacherCode}" . 
                     ($this->weekType ? " (week: {$this->weekType})" : ""));
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
        
        // Check for exact matches
        if (in_array($classInfoUpper, $specialEntries)) {
            return true;
        }
        
        // Check for partial matches (contains special keywords)
        foreach ($specialEntries as $special) {
            if (strpos($classInfoUpper, $special) !== false) {
                return true;
            }
        }
        
        // Check if it doesn't contain the expected format (SubjectCode/TeacherCode)
        if (strpos($classInfo, '/') === false) {
            return true;
        }
        
        return false;
    }

    public function getProcessedCount()
    {
        return $this->processedCount;
    }
}
