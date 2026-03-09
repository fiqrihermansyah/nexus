<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memo_requests', function (Blueprint $table) {
            $table->id();
            $table->string('memo_number')->unique();
            $table->text('request_description');
            $table->enum('category', ['Quarterly', 'Monthly', 'Ad-Hoc']);
            $table->string('division');
            $table->string('pic_dtm');
            $table->date('received_date');
            $table->enum('status', ['Pending', 'On Progress', 'Done', 'Discard'])->default('Pending');
            $table->date('submitted_date')->nullable();
            $table->string('handover_memo_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('memo_number');
            $table->index('status');
            $table->index('category');
            $table->index('received_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memo_requests');
    }
};
