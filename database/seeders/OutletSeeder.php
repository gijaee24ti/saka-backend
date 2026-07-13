<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    public function run(): void
    {
        Outlet::where('branch', 'Outlet Utama SAKA')->update([
            'branch' => 'OUTLET SAKA DAHLIA',
        ]);

        Outlet::where('branch', 'Cabang Kaharuddin Nasution / Simpang')->update([
            'branch' => 'Cabang Kharudin Nasution / Simpang',
        ]);

        $outlets = [
            [
                'branch' => 'Cabang Cut Nyak Dien',
                'vehicle' => 'Sepeda',
                'open_time' => '10:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Cut Nyak Dien',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Patimura',
                'vehicle' => 'Sepeda',
                'open_time' => '10:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Patimura',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Rajawali',
                'vehicle' => 'Sepeda',
                'open_time' => '10:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Rajawali',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Riau',
                'vehicle' => 'Sepeda',
                'open_time' => '10:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Riau',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Kharudin Nasution / Simpang',
                'vehicle' => 'Sepeda',
                'open_time' => '10:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Kharudin Nasution / Simpang',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Arifin Ahmad',
                'vehicle' => 'Bajaj',
                'open_time' => '10:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Arifin Ahmad',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Rumbai',
                'vehicle' => 'Tenda',
                'open_time' => '10:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Rumbai',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Stadion / Nagasakti',
                'vehicle' => 'Bajaj',
                'open_time' => '11:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Stadion / Nagasakti',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Tuanku Tambusai / Nangka',
                'vehicle' => 'Sepeda',
                'open_time' => '09:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Tuanku Tambusai / Nangka',
                'maps_link' => null,
                'note' => 'Dekat RS Andini',
            ],
            [
                'branch' => 'Cabang Nangka Ujung',
                'vehicle' => 'Sepeda',
                'open_time' => '09:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Nangka Ujung',
                'maps_link' => null,
                'note' => 'Seberang Simpang Srikandi',
            ],
            [
                'branch' => 'Cabang Hang Tuah Ujung',
                'vehicle' => 'Bajaj',
                'open_time' => '09:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Hang Tuah Ujung',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Parit Indah',
                'vehicle' => 'Sepeda',
                'open_time' => '09:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Parit Indah',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang HR. Soebrantas',
                'vehicle' => 'Sepeda',
                'open_time' => '09:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'HR. Soebrantas',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Soekarno Hatta',
                'vehicle' => 'Sepeda',
                'open_time' => '09:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Soekarno Hatta',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Cabang Hangtuah',
                'vehicle' => 'Sepeda',
                'open_time' => '10:00',
                'close_time' => '18:00',
                'status' => 'Aktif',
                'address' => 'Hangtuah',
                'maps_link' => null,
                'note' => null,
            ],
            [
                'branch' => 'Bajaj Dipo Malam',
                'vehicle' => 'Bajaj',
                'open_time' => '20:00',
                'close_time' => '23:00',
                'status' => 'Aktif',
                'address' => 'Dipo',
                'maps_link' => "https://www.google.com/maps?q=0.5115768313407898,101.4520492553711&z=17&hl=en",
                'note' => 'Operasional malam',
            ],
            [
                'branch' => 'OUTLET SAKA DAHLIA',
                'vehicle' => 'Outlet',
                'open_time' => '10:00',
                'close_time' => '23:00',
                'status' => 'Aktif',
                'address' => 'Dahlia',
                'maps_link' => null,
                'note' => 'Outlet utama SAKA',
            ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::updateOrCreate(
                ['branch' => $outlet['branch']],
                $outlet
            );
        }
    }
}