<?php

namespace App\Imports;

use App\Models\Subject;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SubjectsImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 8;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty(array_filter($row->toArray()))) {
                continue;
            }

            $code = trim($row[0] ?? '');
            $name = trim($row[3] ?? '');

            if (!empty($code) && !empty($name)) {
                // PENYESUAIAN: Mencari berdasarkan kolom 'code' yang sudah ada.
                // Jika nama kolom Anda bukan 'code', ganti di baris ini. Contoh: ['kode_mapel' => $code]
                Subject::updateOrCreate(
                    ['code' => $code],
                    ['name' => $name]
                );
            }
        }
    }
}
