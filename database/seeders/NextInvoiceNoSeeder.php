<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NextInvoiceNoSeeder extends Seeder
{
    public function run(): void
    {
        if (!DB::table('next_invoice_nos')->where('type', 'Order')->where('year', '2026')->exists()) {
            DB::table('next_invoice_nos')->insertOrIgnore([
                'type' => 'Order', 'year' => '2026', 'next_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
