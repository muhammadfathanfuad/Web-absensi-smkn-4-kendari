<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Console\Command;
use App\Imports\TimetableImport;
use Maatwebsite\Excel\Facades\Excel;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    Excel::import(new TimetableImport, 'public/data/jadwal senin.xlsx');
    echo "Import berhasil!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
