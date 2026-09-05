<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Outlet;
use App\Models\Rider;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $outlets = Outlet::all();
        $menus = Menu::all();

        foreach ($outlets as $outlet) {
            // Find active rider for this outlet if any
            $rider = Rider::where('outlet_id', $outlet->id)->first();

            foreach ($menus as $menu) {
                $isAllowed = false;

                // Rule 1: Cup Series is allowed for all outlets
                if ($menu->category === 'Cup Series') {
                    $isAllowed = true;
                }

                // Rule 2: Donat (Snack) is only allowed for OUTLET SAKA DAHLIA, Cabang Stadion / Nagasakti, Cabang Rumbai, Cabang Hang Tuah Ujung
                if ($menu->name === 'Donat' || $menu->category === 'Snack') {
                    $allowedBranches = [
                        'OUTLET SAKA DAHLIA',
                        'Cabang Stadion / Nagasakti',
                        'Cabang Rumbai',
                        'Cabang Hang Tuah Ujung',
                    ];
                    if (in_array($outlet->branch, $allowedBranches)) {
                        $isAllowed = true;
                    }
                }

                // Rule 3: Literan is only allowed for OUTLET SAKA DAHLIA
                if ($menu->category === 'Literan') {
                    if ($outlet->branch === 'OUTLET SAKA DAHLIA') {
                        $isAllowed = true;
                    }
                }

                if ($isAllowed) {
                    Stock::updateOrCreate(
                        [
                            'outlet_id' => $outlet->id,
                            'menu_id' => $menu->id,
                        ],
                        [
                            'rider_id' => $rider?->id,
                            'quantity' => 20,
                            'stock_status' => 'Tersedia',
                            'note' => null,
                        ]
                    );
                }
            }
        }
    }
}