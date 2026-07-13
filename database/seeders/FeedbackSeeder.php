<?php

namespace Database\Seeders;

use App\Models\feedback;
use App\Models\Outlet;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $feedbackData = [
            [
                'customer_name' => 'Aulia',
                'phone' => '081234567890',
                'branch' => 'Cabang Rumbai',
                'type' => 'Review',
                'category' => 'Rasa Minuman',
                'rating' => 5,
                'message' => 'Kopinya enak, rasanya creamy dan tidak terlalu pahit.',
                'status' => 'Ditampilkan',
            ],
            [
                'customer_name' => 'Rizky',
                'phone' => '081234567891',
                'branch' => 'Cabang Stadion / Nagasakti',
                'type' => 'Masukan',
                'category' => 'Pelayanan',
                'rating' => 4,
                'message' => 'Pelayanannya sudah bagus, semoga stok donatnya bisa lebih banyak.',
                'status' => 'Ditampilkan',
            ],
            [
                'customer_name' => 'Nadia',
                'phone' => '081234567892',
                'branch' => 'Cabang Hang Tuah Ujung',
                'type' => 'Keluhan',
                'category' => 'Stok Habis',
                'rating' => 3,
                'message' => 'Menu favorit saya sempat habis saat datang ke cabang.',
                'status' => 'Pending',
            ],
        ];

        foreach ($feedbackData as $item) {
            $outlet = Outlet::where('branch', $item['branch'])->first();

            feedback::updateOrCreate(
                [
                    'customer_name' => $item['customer_name'],
                    'message' => $item['message'],
                ],
                [
                    'outlet_id' => $outlet?->id,
                    'phone' => $item['phone'],
                    'branch' => $item['branch'],
                    'type' => $item['type'],
                    'category' => $item['category'],
                    'rating' => $item['rating'],
                    'status' => $item['status'],
                    'feedback_date' => now()->toDateString(),
                ]
            );
        }
    }
}