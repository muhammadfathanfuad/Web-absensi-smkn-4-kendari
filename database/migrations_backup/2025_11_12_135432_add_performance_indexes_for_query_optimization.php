<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan index untuk optimasi query performance
     */
    public function up(): void
    {
        // Index untuk tabel attendances
        Schema::table('attendances', function (Blueprint $table) {
            // Index untuk query berdasarkan student_id (sering digunakan)
            if (!$this->hasIndex('attendances', 'attendances_student_id_index')) {
                $table->index('student_id', 'attendances_student_id_index');
            }
            
            // Index untuk query berdasarkan status (filter status)
            if (!$this->hasIndex('attendances', 'attendances_status_index')) {
                $table->index('status', 'attendances_status_index');
            }
            
            // Index untuk query berdasarkan created_at (sorting dan filtering tanggal)
            if (!$this->hasIndex('attendances', 'attendances_created_at_index')) {
                $table->index('created_at', 'attendances_created_at_index');
            }
            
            // Composite index untuk query: student_id + status (sering digunakan bersama)
            if (!$this->hasIndex('attendances', 'attendances_student_id_status_index')) {
                $table->index(['student_id', 'status'], 'attendances_student_id_status_index');
            }
            
            // Composite index untuk query: class_session_id + status
            if (!$this->hasIndex('attendances', 'attendances_class_session_id_status_index')) {
                $table->index(['class_session_id', 'status'], 'attendances_class_session_id_status_index');
            }
        });

        // Index untuk tabel class_sessions
        Schema::table('class_sessions', function (Blueprint $table) {
            // Index untuk query berdasarkan date (filter tanggal)
            if (!$this->hasIndex('class_sessions', 'class_sessions_date_index')) {
                $table->index('date', 'class_sessions_date_index');
            }
            
            // Composite index untuk query: timetable_id + date (sering digunakan bersama)
            if (!$this->hasIndex('class_sessions', 'class_sessions_timetable_id_date_index')) {
                $table->index(['timetable_id', 'date'], 'class_sessions_timetable_id_date_index');
            }
        });

        // Index untuk tabel students
        Schema::table('students', function (Blueprint $table) {
            // Index untuk query berdasarkan class_id (filter per kelas)
            if (!$this->hasIndex('students', 'students_class_id_index')) {
                $table->index('class_id', 'students_class_id_index');
            }
        });

        // Index untuk tabel timetables
        Schema::table('timetables', function (Blueprint $table) {
            // Index untuk query berdasarkan day_of_week (filter hari)
            if (!$this->hasIndex('timetables', 'timetables_day_of_week_index')) {
                $table->index('day_of_week', 'timetables_day_of_week_index');
            }
            
            // Index untuk query berdasarkan is_active (filter aktif/non-aktif)
            if (!$this->hasIndex('timetables', 'timetables_is_active_index')) {
                $table->index('is_active', 'timetables_is_active_index');
            }
            
            // Composite index untuk query: day_of_week + is_active
            if (!$this->hasIndex('timetables', 'timetables_day_of_week_is_active_index')) {
                $table->index(['day_of_week', 'is_active'], 'timetables_day_of_week_is_active_index');
            }
        });

        // Cek apakah tabel class_subjects memiliki kolom class_subject_id di timetables
        // Jika menggunakan relasi melalui class_subjects, tambahkan index
        if (Schema::hasTable('timetables') && Schema::hasColumn('timetables', 'class_subject_id')) {
            Schema::table('timetables', function (Blueprint $table) {
                if (!$this->hasIndex('timetables', 'timetables_class_subject_id_index')) {
                    $table->index('class_subject_id', 'timetables_class_subject_id_index');
                }
            });
        }

        // Index untuk tabel class_subjects
        Schema::table('class_subjects', function (Blueprint $table) {
            // Index untuk query berdasarkan class_id
            if (!$this->hasIndex('class_subjects', 'class_subjects_class_id_index')) {
                $table->index('class_id', 'class_subjects_class_id_index');
            }
            
            // Index untuk query berdasarkan subject_id
            if (!$this->hasIndex('class_subjects', 'class_subjects_subject_id_index')) {
                $table->index('subject_id', 'class_subjects_subject_id_index');
            }
            
            // Index untuk query berdasarkan teacher_id
            if (!$this->hasIndex('class_subjects', 'class_subjects_teacher_id_index')) {
                $table->index('teacher_id', 'class_subjects_teacher_id_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus index dari tabel attendances
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_student_id_index');
            $table->dropIndex('attendances_status_index');
            $table->dropIndex('attendances_created_at_index');
            $table->dropIndex('attendances_student_id_status_index');
            $table->dropIndex('attendances_class_session_id_status_index');
        });

        // Hapus index dari tabel class_sessions
        Schema::table('class_sessions', function (Blueprint $table) {
            $table->dropIndex('class_sessions_date_index');
            $table->dropIndex('class_sessions_timetable_id_date_index');
        });

        // Hapus index dari tabel students
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_class_id_index');
        });

        // Hapus index dari tabel timetables
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropIndex('timetables_day_of_week_index');
            $table->dropIndex('timetables_is_active_index');
            $table->dropIndex('timetables_day_of_week_is_active_index');
            
            if (Schema::hasColumn('timetables', 'class_subject_id')) {
                $table->dropIndex('timetables_class_subject_id_index');
            }
        });

        // Hapus index dari tabel class_subjects
        Schema::table('class_subjects', function (Blueprint $table) {
            $table->dropIndex('class_subjects_class_id_index');
            $table->dropIndex('class_subjects_subject_id_index');
            $table->dropIndex('class_subjects_teacher_id_index');
        });
    }

    /**
     * Helper method untuk mengecek apakah index sudah ada
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            $connection = Schema::getConnection();
            $database = $connection->getDatabaseName();
            
            $result = $connection->select(
                "SELECT COUNT(*) as count FROM information_schema.statistics 
                 WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                [$database, $table, $indexName]
            );
            
            return isset($result[0]) && $result[0]->count > 0;
        } catch (\Exception $e) {
            // Jika error, assume index tidak ada dan lanjutkan
            return false;
        }
    }
};
