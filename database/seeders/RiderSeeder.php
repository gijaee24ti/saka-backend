<?php

namespace Database\Seeders;

use App\Models\Outlet;
use App\Models\Rider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RiderSeeder extends Seeder
{
    public function run(): void
    {
        $riders = [
            [
                'name' => 'Rider Cut Nyak Dien',
                'username' => 'rider_cutnyakdien',
                'password' => 'rider123',
                'phone' => '081234567001',
                'branch' => 'Cabang Cut Nyak Dien',
                'account_status' => 'Aktif',
                'operational_status' => 'Tutup',
            ],
            [
                'name' => 'Rider Patimura',
                'username' => 'rider_patimura',
                'password' => 'rider123',
                'phone' => '081234567002',
                'branch' => 'Cabang Patimura',
                'account_status' => 'Aktif',
                'operational_status' => 'Tutup',
            ],
            [
                'name' => 'Rider Rajawali',
                'username' => 'rider_rajawali',
                'password' => 'rider123',
                'phone' => '081234567003',
                'branch' => 'Cabang Rajawali',
                'account_status' => 'Aktif',
                'operational_status' => 'Tutup',
            ],
            [
                'name' => 'Rider Riau',
                'username' => 'rider_riau',
                'password' => 'rider123',
                'phone' => '081234567004',
                'branch' => 'Cabang Riau',
                'account_status' => 'Aktif',
                'operational_status' => 'Tutup',
            ],
            [
                'name' => 'Rider Arifin Ahmad',
                'username' => 'rider_arifin',
                'password' => 'rider123',
                'phone' => '081234567005',
                'branch' => 'Cabang Arifin Ahmad',
                'account_status' => 'Aktif',
                'operational_status' => 'Tutup',
            ],
            [
                'name' => 'Rider Rumbai',
                'username' => 'rider_rumbai',
                'password' => 'rider123',
                'phone' => '081234567006',
                'branch' => 'Cabang Rumbai',
                'account_status' => 'Aktif',
                'operational_status' => 'Tutup',
            ],
            [
                'name' => 'Rider Stadion / Nagasakti',
                'username' => 'rider_stadion',
                'password' => 'rider123',
                'phone' => '081234567007',
                'branch' => 'Cabang Stadion / Nagasakti',
                'account_status' => 'Aktif',
                'operational_status' => 'Tutup',
            ],
            [
                'name' => 'Rider Hang Tuah Ujung',
                'username' => 'rider_hangtuah',
                'password' => 'rider123',
                'phone' => '081234567008',
                'branch' => 'Cabang Hang Tuah Ujung',
                'account_status' => 'Aktif',
                'operational_status' => 'Tutup',
            ],
        ];

        foreach ($riders as $riderData) {
            $outlet = Outlet::where('branch', $riderData['branch'])->first();

            Rider::updateOrCreate(
                ['username' => $riderData['username']],
                [
                    'outlet_id' => $outlet?->id,
                    'name' => $riderData['name'],
                    'password' => Hash::make($riderData['password']),
                    'phone' => $riderData['phone'],
                    'account_status' => $riderData['account_status'],
                    'operational_status' => $riderData['operational_status'],
                    'note' => null,
                ]
            );
        }
    }
}