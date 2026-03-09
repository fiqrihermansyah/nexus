<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('division');
            $table->string('job_name');
            $table->string('request_subject')->comment('Request Data / Subject Email');
            $table->enum('delivery_type', ['Email', 'SFTP', 'API', 'Manual'])->default('Email');
            $table->string('email_pic')->nullable();
            $table->string('cc_req')->nullable()->comment('CC / REQ');
            $table->string('file_table_name')->nullable()->comment('Name File / Table');
            $table->string('schedule')->nullable()->comment('Cron atau deskripsi schedule');
            $table->time('jam')->nullable()->comment('Jam pengiriman');
            $table->string('day')->nullable()->comment('Hari pengiriman (misal: Senin, 1, dll)');
            $table->string('pic_dtm')->nullable();
            $table->text('query')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Active');

            // Frequency checkboxes
            $table->boolean('is_monthly')->default(false);
            $table->boolean('is_weekly')->default(false);
            $table->boolean('is_daily')->default(false);

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('division');
            $table->index('status');
            $table->index('pic_dtm');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_schedules');
    }
};
