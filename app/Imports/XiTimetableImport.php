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
    private $duplicateCount = 0; // Counter for duplicate entries
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
        $this->duplicateCount = 0;
        $this->errors = [];

        // Auto-detect group type from filename if not provided
        if (!$this->groupType) {
            $this->groupType = $this->detectGroupType();
        }

        // Detect format first
        $this->detectFormat($rows);

        foreach ($rows as $index => $row) {
            // Convert row to array if it's a collection
            $rowArray = is_array($row) ? $row : $row->toArray();
            Log::info("Processing XI row {$index}: " . json_encode($rowArray));

            // Skip empty rows
            if (empty($rowArray[0]) && empty($rowArray[1])) {
                continue;
            }
            
            // Convert row to collection for processing
            $rowCollection = is_array($row) ? collect($row) : $row;

            // Process based on detected format
            if ($this->detectedFormat === 'format1') {
                $this->processFormat1($rowCollection, $index, $term->id, $daysMap, $this->errors);
            } elseif ($this->detectedFormat === 'format2') {
                $this->processFormat2($rowCollection, $index, $term->id, $daysMap, $this->errors);
            } elseif ($this->detectedFormat === 'format3') {
                $this->processFormat3($rowCollection, $index, $term->id, $daysMap, $this->errors);
            } else {
                // Try both formats
                $this->processFormat1($rowCollection, $index, $term->id, $daysMap, $this->errors);
            }
        }

        Log::info("XI timetable import completed. Processed {$this->processedCount} entries, {$this->duplicateCount} duplicates skipped.");
        
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
        // Convert collection to array if needed
        $rowsArray = $rows->toArray();
        $firstRow = $rowsArray[0] ?? null;
        
        if (!$firstRow) {
            $this->detectedFormat = 'format1';
            return;
        }
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
            $row = $rows->get($i) ?? $rows[$i] ?? null;
            if (!$row) continue;
            
            $rowArray = is_array($row) ? $row : $row->toArray();
            $rowStr = strtolower(implode('|', array_filter($rowArray, function($cell) {
                return $cell !== null && trim($cell) !== '';
            })));
            
            if (strpos($rowStr, 'jadwal') !== false || strpos($rowStr, 'kelas') !== false) {
                $titleRowFound = true;
            }
            
            // Check for HARI and WAKTU, and also check for JAM or JAM KE-
            $hasHari = strpos($rowStr, 'hari') !== false;
            $hasWaktu = strpos($rowStr, 'waktu') !== false;
            $hasJam = (strpos($rowStr, 'jam') !== false);
            
            if ($hasHari && $hasWaktu && $hasJam) {
                $headerRowFound = true;
                $this->headers = $rowArray;
                // Next row likely contains class names; store for later mapping
                $nextRow = $rows->get($i+1) ?? $rows[$i+1] ?? null;
                if ($nextRow) {
                    $this->classHeaderRow = is_array($nextRow) ? $nextRow : $nextRow->toArray();
                    $classRowFound = true;
                }
                // Week indicators may be in the next-next row
                $nextNextRow = $rows->get($i+2) ?? $rows[$i+2] ?? null;
                if ($nextNextRow) {
                    $this->weekIndicators = is_array($nextNextRow) ? $nextNextRow : $nextNextRow->toArray();
                }
                break;
            }
        }

        if ($titleRowFound && $headerRowFound) {
            // Check if this is actually Format 3 (Class XI specific)
            if (isset($this->classHeaderRow) && isset($this->weekIndicators)) {
                // Check if we have the Class XI specific pattern
                // Check for any XI class pattern (TKJA, TKJB, TKJC, RPLA, RPLB, RPLC, KTA, KTB, KK, DKVA, DKVB, PSPTA, PSPTB)
                $hasClassXiPattern = false;
                $xiClassPatterns = ['tkja', 'tkjb', 'tkjc', 'rpla', 'rplb', 'rplc', 'kta', 'ktb', 'kk', 'dkva', 'dkvb', 'pspta', 'psptb'];
                foreach ($this->classHeaderRow as $cell) {
                    $cellLower = strtolower($cell ?? '');
                    foreach ($xiClassPatterns as $pattern) {
                        if (strpos($cellLower, $pattern) !== false) {
                            $hasClassXiPattern = true;
                            break 2;
                        }
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
                $row = $rows->get($i) ?? $rows[$i] ?? null;
                if (!$row) continue;
                
                $rowArray = is_array($row) ? $row : $row->toArray();
                
                // Check if this row has HARI, JAM, WAKTU pattern
                if (isset($rowArray[0]) && isset($rowArray[1]) && isset($rowArray[2])) {
                    $col0 = strtolower(trim($rowArray[0]));
                    $col1 = strtolower(trim($rowArray[1]));
                    $col2 = strtolower(trim($rowArray[2]));
                    
                    // Check for HARI, JAM (or JAM KE-), WAKTU pattern
                    // Handle both "JAM" and "JAM KE-" variations
                    $col1Normalized = strtolower(preg_replace('/\s+/', ' ', trim($col1)));
                    $isJamColumn = ($col1 === 'jam' || strpos($col1Normalized, 'jam') !== false);
                    
                    if ($col0 === 'hari' && $isJamColumn && $col2 === 'waktu') {
                        // This is the header row, next row should have class names
                        $this->detectedFormat = 'format3';
                        $this->headers = $rowArray; // Store header row
                        
                        // Store class names from next row
                        $nextRow = $rows->get($i+1) ?? $rows[$i+1] ?? null;
                        if ($nextRow) {
                            $this->classHeaderRow = is_array($nextRow) ? $nextRow : $nextRow->toArray();
                            Log::info("Stored classHeaderRow from row " . ($i+1) . ": " . json_encode($this->classHeaderRow));
                        }
                        
                        // Store week indicators from next-next row
                        $nextNextRow = $rows->get($i+2) ?? $rows[$i+2] ?? null;
                        if ($nextNextRow) {
                            $this->weekIndicators = is_array($nextNextRow) ? $nextNextRow : $nextNextRow->toArray();
                            Log::info("Stored weekIndicators from row " . ($i+2) . ": " . json_encode($this->weekIndicators));
                        }
                        
                        Log::info("Detected Format 3: Class XI specific format with HARI/JAM/WAKTU structure at row {$i}");
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

        $rowArray = is_array($row) ? $row : $row->toArray();
        $hari = strtolower(trim($rowArray[0] ?? ''));
        $waktu = trim($rowArray[2] ?? ''); // Format 2: HARI, JAM, WAKTU, KELAS

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

        $rowArray = is_array($row) ? $row : $row->toArray();
        $hari = strtolower(trim($rowArray[0] ?? ''));
        $waktu = trim($rowArray[2] ?? ''); // Format 3: HARI, JAM, WAKTU, then class columns

        // If hari is empty but waktu is not, use the previous day (carry forward)
        if (empty($hari) && !empty($waktu)) {
            if ($this->currentHari) {
                $hari = $this->currentHari;
                Log::info("Row {$index}: Using previous hari '{$hari}' for waktu '{$waktu}'");
            } else {
                Log::warning("Row {$index}: No hari and no previous hari available, skipping row with waktu '{$waktu}'");
                return;
            }
        }

        // Update current day if we have a new day
        if (!empty($hari)) {
            $this->currentHari = $hari;
            Log::info("Row {$index}: Set current hari to '{$hari}'");
        }

        // Skip if both hari and waktu are empty
        if (empty($hari) && empty($waktu)) {
            Log::info("Row {$index}: Skipping empty row");
            return;
        }

        // Skip if waktu is empty (even if hari exists)
        if (empty($waktu)) {
            Log::info("Row {$index}: Skipping row with hari '{$hari}' but no waktu");
            return;
        }

        $this->processTimeSlot($row, $index, $termId, $daysMap, $hari, $waktu, $errors);
    }

    private function processTimeSlot($row, $index, $termId, $daysMap, $hari, $waktu, &$errors)
    {
        // Normalize waktu - handle cases like "13.20- 14.00" (space after minus)
        $waktu = preg_replace('/\s*-\s*/', '-', $waktu);
        
        // Log the waktu value for debugging
        Log::info("Processing time slot - Row {$index}, Hari: {$hari}, Waktu: '{$waktu}'");
        
        // Split by minus (now normalized)
        $timeParts = preg_split('/-/', $waktu);
        if (count($timeParts) !== 2) {
            Log::warning("Invalid time format in row {$index}: '{$waktu}' - expected format: 'HH.MM-HH.MM' or 'HH:MM-HH:MM'");
            return;
        }

        // Clean and format time parts
        $startTimeRaw = trim($timeParts[0]);
        $endTimeRaw = trim($timeParts[1]);
        
        Log::info("Parsed time parts - Start: '{$startTimeRaw}', End: '{$endTimeRaw}'");
        
        // Handle different time formats
        $startTime = $this->formatTime($startTimeRaw);
        $endTime = $this->formatTime($endTimeRaw);
        
        if (!$startTime || !$endTime) {
            Log::warning("Failed to parse time in row {$index}: Start='{$startTimeRaw}' -> '{$startTime}', End='{$endTimeRaw}' -> '{$endTime}'");
            return;
        }
        
        Log::info("Formatted times - Start: {$startTime}, End: {$endTime}");

        $dayOfWeek = $daysMap[$hari] ?? null;
        if (!$dayOfWeek) {
            Log::warning("Invalid day in row {$index}: '{$hari}'");
            return;
        }

        // Process class columns with week indicators
        $startCol = $this->detectedFormat === 'format2' ? 3 : ($this->detectedFormat === 'format3' ? 3 : 2);
        
        // For format3, we need to process pairs of columns (GJL and GNP for each class)
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

        // Check for existing entry with exact match on all key fields
        // This allows multiple time slots for the same subject on the same day
        $existingQuery = XiTimetable::where('term_id', $termId)
            ->where('class_subject_id', $classSubjectId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('group_type', $this->groupType)
            ->where('week_type', $weekType)
            ->where('location_type', $locationType);

        $existingTimetable = $existingQuery->first();

        if (!$existingTimetable) {
            $created = XiTimetable::create($timetableData);
            Log::info("Created XI timetable entry ID {$created->id}: Day {$dayOfWeek}, Time {$startTime}-{$endTime}, Group {$this->groupType}, Week {$weekType}, Location {$locationType}");
            return true; // Entry created
        } else {
            $this->duplicateCount++;
            Log::info("Skipped duplicate XI timetable entry: Day {$dayOfWeek}, Time {$startTime}-{$endTime}, Group {$this->groupType}, Week {$weekType}, Location {$locationType} (existing ID: {$existingTimetable->id})");
            return false; // Duplicate entry skipped
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

    public function getDuplicateCount()
    {
        return $this->duplicateCount;
    }

    private function processFormat3ClassColumns($row, $index, $termId, $dayOfWeek, $startTime, $endTime, $startCol, &$errors)
    {
        // Process pairs of columns for each class (GJL and GNP)
        // Get class names dynamically from classHeaderRow (row 5 in Excel)
        // Each class has 2 columns: GJL (ganjil/lab) and GNP (genap/teori)
        // IMPORTANT: Both columns must be processed as they represent different weeks (ganjil/genap)
        
        // Convert row to array for processing
        $rowArray = is_array($row) ? $row : $row->toArray();
        
        // Extract class names from classHeaderRow (starting from column index 3)
        // Class names are in every other column (GJL column), skipping GNP columns
        $classNames = [];
        if (!empty($this->classHeaderRow)) {
            Log::info("classHeaderRow: " . json_encode($this->classHeaderRow));
            for ($col = $startCol; $col < count($this->classHeaderRow); $col += 2) {
                $className = trim($this->classHeaderRow[$col] ?? '');
                // Skip empty cells and week indicators
                if (!empty($className) && 
                    !in_array(strtoupper($className), ['GJL', 'GNP']) &&
                    strtoupper($className) !== 'GJL' &&
                    strtoupper($className) !== 'GNP') {
                    $classNames[] = strtoupper($className);
                    Log::info("Found class name at column {$col}: {$className}");
                }
            }
        }
        
        // Fallback to default class names if classHeaderRow is empty or not detected
        if (empty($classNames)) {
            // Default class names based on group type
            if ($this->groupType === 'A') {
                $classNames = ['TKJA', 'TKJC', 'RPLA', 'RPLC', 'KTA', 'DKVA', 'PSPTA'];
            } else {
                $classNames = ['TKJB', 'RPLB', 'KK', 'KTB', 'DKVB', 'PSPTB'];
            }
            Log::warning("Class names not detected from Excel, using default for group {$this->groupType}: " . implode(', ', $classNames));
        } else {
            Log::info("Detected class names from Excel: " . implode(', ', $classNames));
        }
        
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
            
            // Process GJL (Ganjil/Lab) - this column contains data for odd weeks
            $gjlClassInfo = trim($rowArray[$gjlColIndex] ?? '');
            if (!empty($gjlClassInfo) && !$this->isSpecialEntry($gjlClassInfo)) {
                try {
                    $this->processClassInfo($className, $gjlClassInfo, $termId, $dayOfWeek, $startTime, $endTime, 'GJL');
                    $this->processedCount++;
                    Log::info("Processed GJL (minggu ganjil) for {$className} at row {$index}: '{$gjlClassInfo}'");
                } catch (\Exception $e) {
                    $errorMsg = "Error processing XI class {$className} (GJL/minggu ganjil) in row {$index}: " . $e->getMessage();
                    Log::error($errorMsg);
                    $errors[] = $errorMsg;
                }
            }
            
            // Process GNP (Genap/Teori) - this column contains data for even weeks
            $gnpClassInfo = trim($rowArray[$gnpColIndex] ?? '');
            if (!empty($gnpClassInfo) && !$this->isSpecialEntry($gnpClassInfo)) {
                try {
                    $this->processClassInfo($className, $gnpClassInfo, $termId, $dayOfWeek, $startTime, $endTime, 'GNP');
                    $this->processedCount++;
                    Log::info("Processed GNP (minggu genap) for {$className} at row {$index}: '{$gnpClassInfo}'");
                } catch (\Exception $e) {
                    $errorMsg = "Error processing XI class {$className} (GNP/minggu genap) in row {$index}: " . $e->getMessage();
                    Log::error($errorMsg);
                    $errors[] = $errorMsg;
                }
            }
        }
    }

    private function formatTime($timeString)
    {
        // Remove any extra spaces and normalize
        $timeString = trim($timeString);
        
        // Handle Excel time values (decimal like 0.333333 for 08:00)
        if (is_numeric($timeString) && $timeString < 1 && $timeString > 0) {
            $totalSeconds = round($timeString * 86400); // Convert to seconds in a day
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':00';
        }
        
        // Handle formats like "07.00", "7.00", "07:00", "7:00", "08.00", "8.00"
        if (preg_match('/^(\d{1,2})[.:](\d{2})$/', $timeString, $matches)) {
            $hour = intval($matches[1]);
            $minute = intval($matches[2]);
            
            // Validate hour and minute ranges
            if ($hour < 0 || $hour > 23 || $minute < 0 || $minute > 59) {
                Log::warning("Invalid time format: {$timeString} (hour: {$hour}, minute: {$minute})");
                return null;
            }
            
            return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT) . ':00';
        }
        
        // Handle formats like "07.00-08.00" (shouldn't happen here but just in case)
        if (preg_match('/^(\d{1,2})[.:](\d{2})/', $timeString, $matches)) {
            $hour = intval($matches[1]);
            $minute = intval($matches[2]);
            
            if ($hour < 0 || $hour > 23 || $minute < 0 || $minute > 59) {
                Log::warning("Invalid time format: {$timeString}");
                return null;
            }
            
            return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT) . ':00';
        }
        
        Log::warning("Could not parse time format: {$timeString}");
        return null;
    }
}
