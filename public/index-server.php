<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/**
 * CONFIGURASI UNTUK STRUKTUR TERPISAH
 * 
 * Jika folder project Anda ada di lokasi berbeda dari public_html,
 * sesuaikan path di bawah ini.
 * 
 * Contoh:
 * - public_html ada di: /home2/fathanco/public_html/
 * - Project ada di: /home2/fathanco/website_absensi_smkn_4_kendari/
 * 
 * Maka path relatif: ../website_absensi_smkn_4_kendari
 * Atau path absolut: /home2/fathanco/website_absensi_smkn_4_kendari
 */

// SESUAIKAN PATH INI DENGAN LOKASI FOLDER PROJECT ANDA
// Opsi 1: Path relatif (jika folder project sejajar dengan public_html)
$projectPath = __DIR__ . '/../website_absensi_smkn_4_kendari';

// Opsi 2: Path absolut (jika tahu lokasi tepat)
// $projectPath = '/home2/fathanco/website_absensi_smkn_4_kendari';

// Opsi 3: Auto-detect dari environment variable
// $projectPath = $_ENV['APP_BASE_PATH'] ?? __DIR__ . '/../website_absensi_smkn_4_kendari';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $projectPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $projectPath . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $projectPath . '/bootstrap/app.php')
    ->handleRequest(Request::capture());

