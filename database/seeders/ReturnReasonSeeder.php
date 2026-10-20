<?php

namespace Database\Seeders;

use App\Models\ReturnReason;
use Illuminate\Database\Seeder;

class ReturnReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            'Damaged Product' => 'The product arrived damaged or broken.',
            'Defective Product' => 'The product does not work as expected.',
            'Wrong Product Delivered' => 'The delivered product differs from the ordered item.',
            'Incorrect Size or Fit' => 'The product size or fit is unsuitable.',
            'Vehicle Compatibility Issue' => 'The part is not compatible with the intended vehicle.',
            'Missing Parts or Accessories' => 'Parts or accessories are missing from the package.',
            'Product Not as Described' => 'The product does not match its description.',
            'Quality Issue' => 'The product quality does not meet expectations.',
        ];
        $serial = 1;
        foreach ($reasons as $title => $description) {
            ReturnReason::firstOrCreate(['title' => $title], [
                'description' => $description, 'serial_no' => $serial++, 'status' => true,
            ]);
        }
    }
}
