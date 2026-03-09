<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // Isi username dari email untuk user yang sudah ada
    DB::table('users')->get()->each(function ($user) {
        DB::table('users')->where('id', $user->id)->update([
            'username' => explode('@', $user->email)[0] . '_' . $user->id
        ]);
    });

    // Tambah unique saja karena kolom sudah ada
    Schema::table('users', function (Blueprint $table) {
        $table->unique('username');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('username');
    });
}
};
