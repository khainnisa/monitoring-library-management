<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Member::create([
                'membership_number' => 'MEM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'Member ' . $i,
                'email' => 'member' . $i . '@library.com',
                'phone' => '08123456' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'address' => 'Jl. Perpustakaan No. ' . $i . ', Yogyakarta',
                'join_date' => now()->subDays(rand(1, 365)),
                'status' => $i % 4 == 0 ? 'inactive' : 'active',
            ]);
        }
    }
}