<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus data lama yang tidak sesuai dulu (opsional, skip jika tabel masih kosong)
        // DB::statement("UPDATE job_schedules SET delivery_type = 'CSV' WHERE delivery_type NOT IN ('CSV','PDF','DOCX','TXT','ODT')");

        DB::statement("ALTER TABLE job_schedules MODIFY COLUMN delivery_type ENUM('CSV','PDF','DOCX','TXT','ODT') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE job_schedules MODIFY COLUMN delivery_type ENUM('email','sftp') NOT NULL");
    }
};