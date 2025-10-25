<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing XI import (Kelompok A - Senin)\n";

use App\Models\Term;
use App\Imports\XiTimetableImport;
use Maatwebsite\Excel\Facades\Excel;

$term = Term::where('is_active', true)->latest()->first();
if (!$term) {
    echo "❌ No active term found.\n";
    exit(1);
}

try {
    $import = new XiTimetableImport('A', null);
    Excel::import($import, 'public/data/kelasXI/jadwal kelompok a senin.xlsx');

    $processed = $import->getProcessedCount();
    echo "✅ Processed: {$processed}\n";

    if (method_exists($import, 'getErrors')) {
        $errors = $import->getErrors();
        echo "Errors: " . count($errors) . "\n";
        foreach ($errors as $e) {
            echo " - {$e}\n";
        }
    }
} catch (Throwable $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

?>


