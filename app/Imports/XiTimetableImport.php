<?php

namespace App\Imports;

use App\Models\XiTimetable;
use App\Models\XiClass;
use App\Models\Term;
use App\Models\ClassSubject;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class XiTimetableImport implements ToCollection
{
    private $headers = [];
    private $processedCount = 0;
    private $groupType;
    private $termId;
    private $currentHari;
    private $detectedFormat = null;
    private $errors = [];
    private $weekIndicators = [];
    private $classHeaderRow = [];
    private $grade;

    public function __construct($groupType = null, $grade = null, $termId = null)
    {
        $this->groupType = $groupType;
        $this->grade = $grade;
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
        $this->errors = [];

        // Auto-detect group type from filename if not provided
        if (!$this->groupType) {
            $this->groupType = $this->detectGroupType();
        }

        // Detect format first
        $this->detectFormat($rows);

        foreach ($rows as $index => $row) {
            Log::info("Processing XI row {$index}: " . json_encode($row->toArray()));

            // Skip empty rows
            if (empty($row[0]) && empty($row[1])) {
                continue;
            }

            // Process based on detected format
            if ($this->detectedFormat === 'format1') {
                $this->processFormat1($row, $index, $term->id, $daysMap, $this->errors);
            } elseif ($this->detectedFormat === 'format2') {
                $this->processFormat2($row, $index, $term->id, $daysMap, $this->errors);
            } elseif ($this->detectedFormat === 'format3') {
                $this->processFormat3($row, $index, $term->id, $daysMap, $this->errors);
            } else {
                // Try both formats
                $this->processFormat1($row, $index, $term->id, $daysMap, $this->errors);
            }
        }

        Log::info("XI timetable import completed. Processed {$this->processedCount} entries.");
        
        if (!empty($this->errors)) {
            Log::warning("XI import completed with errors: " . implode('; ', $this->errors));
        }
    }

    private function detectGroupType()
    {
        // Auto-detect from filename or other indicators
        // This can be enhanced based on file naming convention
        return 'A'; // Default to A, can be overridden
    }

    private function detectFormat(Collection $rows)
    {
        if (count($rows) < 3) {
            $this->detectedFormat = 'format1';
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
        $classRowFound = false;
        
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
                // Next row likely contains class names; store for later mapping
                if (isset($rows[$i+1])) {
                    $this->classHeaderRow = $rows[$i+1]->toArray();
                    $classRowFound = true;
                }
                // Week indicators may be in the next-next row
                if (isset($rows[$i+2])) {
                    $this->weekIndicators = $rows[$i+2]->toArray();
                }
                break;
            }
        }

        if ($titleRowFound && $headerRowFound) {
            // Check if this is actually Format 3 (Class XI specific)
            if (isset($this->classHeaderRow) && isset($this->weekIndicators)) {
                // Check if we have the Class XI specific pattern
                $hasClassXiPattern = false;
                foreach ($this->classHeaderRow as $cell) {
                    if (strpos(strtolower($cell ?? ''), 'tkja') !== false || 
                        strpos(strtolower($cell ?? ''), 'rpla') !== false) {
                        $hasClassXiPattern = true;
                        break;
                    }
                }
                
                if ($hasClassXiPattern) {
                    $this->detectedFormat = 'format3';
                    Log::info("Detected Format 3: Class XI specific format with HARI/JAM/WAKTU structure");
                    return;
                }
            }
            
            $this->detectedFormat = 'format2';
            Log::info("Detected Format 2: Title + HARI, JAM, WAKTU, KELAS format");
            return;
        }

        // Check for Format 3: Class XI specific format with title rows, then HARI/JAM/WAKTU/KELAS
        if (count($rows) >= 6) {
            // Look for the pattern: title rows, then HARI, JAM, WAKTU, KELAS XI
            for ($i = 0; $i < min(6, count($rows)); $i++) {
                $row = $rows[$i];
                $rowArray = $row->toArray();
                
                // Check if this row has HARI, JAM, WAKTU pattern
                if (isset($rowArray[0]) && isset($rowArray[1]) && isset($rowArray[2])) {
                    $col0 = strtolower(trim($rowArray[0]));
                    $col1 = strtolower(trim($rowArray[1]));
                    $col2 = strtolower(trim($rowArray[2]));
                    
                    if ($col0 === 'hari' && $col1 === 'jam' && $col2 === 'waktu') {
                        // This is the header row, next row should have class names
                        $this->detectedFormat = 'format3';
                        $this->headers = $rowArray; // Store header row
                        
                        // Store class names from next row
                        if (isset($rows[$i+1])) {
                            $this->classHeaderRow = $rows[$i+1]->toArray();
                        }
                        
                        // Store week indicators from next-next row
                        if (isset($rows[$i+2])) {
                            $this->weekIndicators = $rows[$i+2]->toArray();
                        }
                        
                        Log::info("Detected Format 3: Class XI specific format with HARI/JAM/WAKTU structure");
                        return;
                    }
                }
            }
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
        // Skip title rows and header rows (first 6 rows typically)
        if ($index < 6) {
            return;
        }

        $hari = strtolower(trim($row[0] ?? ''));
        $waktu = trim($row[2] ?? ''); // Format 3: HARI, JAM, WAKTU, then class columns

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

        // Clean and format time parts
        $startTimeRaw = trim($timeParts[0]);
        $endTimeRaw = trim($timeParts[1]);
        
        // Handle different time formats
        $startTime = $this->formatTime($startTimeRaw);
        $endTime = $this->formatTime($endTimeRaw);
        
        if (!$startTime || !$endTime) {
            return;
        }

        $dayOfWeek = $daysMap[$hari] ?? null;
        if (!$dayOfWeek) {
            return;
        }

        // Process class columns with week indicators
        $startCol = $this->detectedFormat === 'format2' ? 3 : ($this->detectedFormat === 'format3' ? 3 : 2);
        
        // For format3, we need to process pairs of columns (GJ and GNP for each class)
        if ($this->detectedFormat === 'format3') {
            $this->processFormat3ClassColumns($row, $index, $termId, $dayOfWeek, $startTime, $endTime, $startCol, $errors);
        } else {
            // Original processing for other formats
            for ($colIndex = $startCol; $colIndex < count($row); $colIndex++) {
                $classInfo = trim($row[$colIndex] ?? '');
                if (empty($classInfo)) {
                    continue;
                }

                $className = $this->getClassName($colIndex);
                if (empty($className)) {
                    continue;
                }

                // Filter by grade if specified (for XI import, grade should be 11)
                if (!empty($this->grade)) {
                    $expectedGrade = $this->grade === 'XI' ? '11' : $this->grade;
                    // For XI import, we only process classes with grade 11
                    if ($expectedGrade !== '11') {
                        continue;
                    }
                }

                if ($this->isSpecialEntry($classInfo)) {
                    continue;
                }

                // Get week indicator from the row above (GJL/GNP)
                $weekIndicator = $this->getWeekIndicator($colIndex, $row, $index);
                
                try {
                    $this->processClassInfo($className, $classInfo, $termId, $dayOfWeek, $startTime, $endTime, $weekIndicator);
                    $this->processedCount++;
                } catch (\Exception $e) {
                    $errorMsg = "Error processing XI class {$className} in row {$index}: " . $e->getMessage();
                    Log::error($errorMsg);
                    $errors[] = $errorMsg;
                }
            }
        }
    }

    private function getWeekIndicator($colIndex, $currentRow, $currentIndex)
    {
        // Default to GJL (Ganjil/Lab) for all entries
        return 'GJL';

        // Priority 2: read from captured weekIndicators row (e.g., GJL/GNP under each class column)
        if (!empty($this->weekIndicators) && isset($this->weekIndicators[$colIndex])) {
			$raw = strtoupper(trim((string)$this->weekIndicators[$colIndex]));
			// Normalize by stripping non-letters
			$val = preg_replace('/[^A-Z]/', '', $raw);
			// Accept common variants: GJ, GJL, GANJIL => GJL; GN, GNP, GENAP => GNP
			if ($val === 'GJL' || $val === 'GJ' || $val === 'GANJIL') {
				return 'GJL';
			}
			if ($val === 'GNP' || $val === 'GN' || $val === 'GENAP') {
				return 'GNP';
			}
        }

        // Priority 3: fallback to default GJL
        return 'GJL';
    }

    private function getClassName($colIndex)
    {
		if ($this->detectedFormat === 'format2') {
			// For format 2, class names are provided in a dedicated row captured during detection
			return $this->classHeaderRow[$colIndex] ?? ($this->headers[$colIndex] ?? '');
		} elseif ($this->detectedFormat === 'format3') {
			// For format 3, class names are in the classHeaderRow (row after HARI/JAM/WAKTU)
			return $this->classHeaderRow[$colIndex] ?? '';
		} else {
			// For format 1, class names are in the header row itself
			return $this->headers[$colIndex] ?? '';
		}
	}

    private function isSpecialEntry($classInfo)
    {
        $specialEntries = [
            'UPACARA BENDERA',
            'ISTRAHAT, SHOLAT DAN MAKAN',
            'ISTRAHAT',
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

    /**
     * Normalize subject code by removing dots between letters and numbers
     * Example: A.4 -> A4, B.1 -> B1, A.10 -> A10
     * Only removes dots that are between a single letter and numbers (not multiple dots)
     */
    private function normalizeSubjectCode($subjectCode)
    {
        // Remove dots that are between a single letter and numbers
        // This pattern matches: letter + dot + numbers (but not if there are multiple dots)
        $normalized = preg_replace('/([A-Za-z])\.(\d+)(?![\.\d])/', '$1$2', $subjectCode);
        
        // Log the normalization if it changed
        if ($normalized !== $subjectCode) {
            Log::info("Normalized subject code: '{$subjectCode}' -> '{$normalized}'");
        }
        
        return $normalized;
    }

    private function processClassInfo($className, $classInfo, $termId, $dayOfWeek, $startTime, $endTime, $weekIndicator)
    {
        $classInfo = str_replace([' ', '\\/'], ['', '/'], $classInfo);
        $parts = explode('/', $classInfo);
        if (count($parts) !== 2) {
            throw new \Exception("Invalid class info format: {$classInfo}. Expected format: 'SubjectCode/TeacherCode'");
        }

        $subjectCode = trim($parts[0]);
        $teacherCode = trim($parts[1]);
        
        // Normalize subject code to handle dots between letters and numbers
        $subjectCode = $this->normalizeSubjectCode($subjectCode);

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

        // Find or create subject (auto-create if missing like kelas X logic)
        $subject = Subject::where('code', $subjectCode)->first();
        if (!$subject) {
            $subject = Subject::create([
                'code' => $subjectCode,
                'name' => $subjectCode, // fallback name same as code
            ]);
            Log::info("Created new subject with code '{$subjectCode}' for XI import");
        }

		// Find existing classroom with grade 11 only
		$classroom = XiClass::where('name', $className)
			->where('grade', '11')
			->first();
			
		if (!$classroom) {
			// If no grade 11 class found, throw error instead of creating new one
			throw new \Exception("Kelas '{$className}' dengan grade 11 tidak ditemukan di database. Silakan tambahkan kelas tersebut terlebih dahulu.");
		}
		
		// Update only group_type and location_preference for existing grade 11 class
		$updates = [];
		if (empty($classroom->group_type) && !empty($this->groupType)) {
			$updates['group_type'] = $this->groupType;
		}
		if (empty($classroom->location_preference)) {
			$updates['location_preference'] = $this->determineLocationPreference($weekIndicator);
		}
		if (!empty($updates)) {
			$classroom->update($updates);
			Log::info("Updated XI classroom '{$classroom->name}' with group {$this->groupType} and location preference");
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

        // Determine week type and location based on group and week indicator
        $weekType = $this->determineWeekType($weekIndicator);
        $locationType = $this->determineLocationType($weekType);

        // Create timetable entries
        $this->createTimetableEntry($termId, $classSubject->id, $dayOfWeek, $startTime, $endTime, $weekType, $locationType);
    }

    private function determineLocationPreference($weekIndicator)
    {
        // Determine location preference based on group type and week indicator
        if ($this->groupType === 'A') {
            return $weekIndicator === 'GJL' ? 'lab' : 'theory';
        } else {
            return $weekIndicator === 'GJL' ? 'theory' : 'lab';
        }
    }

    private function determineWeekType($weekIndicator)
    {
        return $weekIndicator === 'GJL' ? 'ganjil' : 'genap';
    }

    private function determineLocationType($weekType)
    {
        if ($this->groupType === 'A') {
            return $weekType === 'ganjil' ? 'lab' : 'theory';
        } else {
            return $weekType === 'ganjil' ? 'theory' : 'lab';
        }
    }

    private function createTimetableEntry($termId, $classSubjectId, $dayOfWeek, $startTime, $endTime, $weekType, $locationType)
    {
        $timetableData = [
            'term_id' => $termId,
            'class_subject_id' => $classSubjectId,
            'day_of_week' => $dayOfWeek,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'type' => 'teori',
            'week_type' => $weekType,
            'group_type' => $this->groupType,
            'location_type' => $locationType,
            'week_alternation' => $weekType
        ];

        $existingQuery = XiTimetable::where('term_id', $termId)
            ->where('class_subject_id', $classSubjectId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('group_type', $this->groupType)
            ->where('week_type', $weekType);

        $existingTimetable = $existingQuery->first();

        if (!$existingTimetable) {
            XiTimetable::create($timetableData);
            Log::info("Created XI timetable entry for group {$this->groupType}, week {$weekType}, location {$locationType}");
        }
    }

    public function getProcessedCount()
    {
        return $this->processedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function processFormat3ClassColumns($row, $index, $termId, $dayOfWeek, $startTime, $endTime, $startCol, &$errors)
    {
        // Process pairs of columns for each class (GJL and GNP)
        // Based on the Excel structure: TKJA, TKJC, RPLA, RPLC, KTA, DKVA, PSPTA
        // Each class has 2 columns: GJL (ganjil/lab) and GNP (genap/teori)
        
        $classNames = ['TKJA', 'TKJC', 'RPLA', 'RPLC', 'KTA', 'DKVA', 'PSPTA'];
        
        for ($i = 0; $i < count($classNames); $i++) {
            $className = $classNames[$i];
            
            // Filter by grade if specified (for XI import, grade should be 11)
            if (!empty($this->grade)) {
                $expectedGrade = $this->grade === 'XI' ? '11' : $this->grade;
                // For XI import, we only process classes with grade 11
                if ($expectedGrade !== '11') {
                    continue;
                }
            }
            
            $gjlColIndex = $startCol + ($i * 2); // GJL column (ganjil/lab)
            $gnpColIndex = $startCol + ($i * 2) + 1; // GNP column (genap/teori)
            
            // Process GJL (Ganjil/Lab) - this column contains the class name
            $gjlClassInfo = trim($row[$gjlColIndex] ?? '');
            if (!empty($gjlClassInfo) && !$this->isSpecialEntry($gjlClassInfo)) {
                try {
                    $this->processClassInfo($className, $gjlClassInfo, $termId, $dayOfWeek, $startTime, $endTime, 'GJL');
                    $this->processedCount++;
                } catch (\Exception $e) {
                    $errorMsg = "Error processing XI class {$className} (GJL) in row {$index}: " . $e->getMessage();
                    Log::error($errorMsg);
                    $errors[] = $errorMsg;
                }
            }
            
            // Process GNP (Genap/Teori) - this column contains the same class info but for genap/teori
            $gnpClassInfo = trim($row[$gnpColIndex] ?? '');
            Log::info("Processing GNP for {$className}: col {$gnpColIndex}, data: '{$gnpClassInfo}'");
            if (!empty($gnpClassInfo) && !$this->isSpecialEntry($gnpClassInfo)) {
                try {
                    $this->processClassInfo($className, $gnpClassInfo, $termId, $dayOfWeek, $startTime, $endTime, 'GNP');
                    $this->processedCount++;
                    Log::info("Successfully processed GNP for {$className}");
                } catch (\Exception $e) {
                    $errorMsg = "Error processing XI class {$className} (GNP) in row {$index}: " . $e->getMessage();
                    Log::error($errorMsg);
                    $errors[] = $errorMsg;
                }
            } else {
                Log::info("Skipping GNP for {$className}: empty or special entry");
            }
        }
    }

    private function formatTime($timeString)
    {
        // Remove any extra spaces and normalize
        $timeString = trim($timeString);
        
        // Handle formats like "07.00", "7.00", "07:00", "7:00"
        if (preg_match('/^(\d{1,2})[.:](\d{2})$/', $timeString, $matches)) {
            $hour = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $minute = $matches[2];
            return $hour . ':' . $minute . ':00';
        }
        
        // Handle formats like "07.00-08.00" (shouldn't happen here but just in case)
        if (preg_match('/^(\d{1,2})[.:](\d{2})/', $timeString, $matches)) {
            $hour = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $minute = $matches[2];
            return $hour . ':' . $minute . ':00';
        }
        
        return null;
    }
}
