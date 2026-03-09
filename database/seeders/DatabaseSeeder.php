<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MemoRequest;
use App\Models\MemoActivity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        $admin = User::create([
            'name'     => 'Admin Nexus',
            'email'    => 'admin@nexus.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'division' => 'DTM',
        ]);

        // Create regular user
        $user = User::create([
            'name'     => 'Ezra Pratama',
            'email'    => 'ezra@nexus.id',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'division' => 'DTM',
        ]);

        // Sample memos
        $memos = [
            ['memo_number' => '001/M/CMG/I/2026', 'request_description' => 'Permintaan Data All CIF BSN', 'category' => 'Quarterly', 'division' => 'CMG', 'pic_dtm' => 'EZRA', 'received_date' => '2026-01-02', 'status' => 'Done', 'submitted_date' => '2026-01-13'],
            ['memo_number' => '002/M/RMG/I/2026', 'request_description' => 'Data Nasabah Aktif Bulan Januari', 'category' => 'Monthly', 'division' => 'RMG', 'pic_dtm' => 'EZRA', 'received_date' => '2026-01-05', 'status' => 'On Progress'],
            ['memo_number' => '003/M/CMG/I/2026', 'request_description' => 'Laporan Transaksi Harian', 'category' => 'Ad-Hoc', 'division' => 'CMG', 'pic_dtm' => 'BUDI', 'received_date' => '2026-01-08', 'status' => 'Pending'],
            ['memo_number' => '004/M/SME/II/2026', 'request_description' => 'Data Portfolio SME Q4 2025', 'category' => 'Quarterly', 'division' => 'SME', 'pic_dtm' => 'RINA', 'received_date' => '2026-02-01', 'status' => 'Done', 'submitted_date' => '2026-02-10'],
            ['memo_number' => '005/M/CMG/II/2026', 'request_description' => 'Rekap Data Kredit Konsumer', 'category' => 'Monthly', 'division' => 'CMG', 'pic_dtm' => 'EZRA', 'received_date' => '2026-02-03', 'status' => 'Discard', 'notes' => 'Data tidak tersedia untuk periode tersebut'],
        ];

        foreach ($memos as $data) {
            $data['created_by'] = $admin->id;
            $memo = MemoRequest::create($data);

            MemoActivity::create([
                'memo_request_id' => $memo->id,
                'user_id'         => $admin->id,
                'action'          => 'created',
                'description'     => 'Memo request dibuat',
                'new_status'      => 'Pending',
            ]);

            if ($memo->status !== 'Pending') {
                MemoActivity::create([
                    'memo_request_id' => $memo->id,
                    'user_id'         => $user->id,
                    'action'          => 'status_changed',
                    'description'     => "Status diubah ke {$memo->status}",
                    'old_status'      => 'Pending',
                    'new_status'      => $memo->status,
                ]);
            }
        }
    }
}
