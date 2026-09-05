<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Kopi Susu Aren',
                'category' => 'Cup Series',
                'cup_price' => 12000,
                'price_500' => 0,
                'price_1l' => 0,
                'description' => 'Kopi susu aren khas Saka dengan rasa creamy, manis, dan cocok untuk diminum santai.',
                'durability' => 'Produk cup dibuat fresh di hari yang sama dan sebaiknya langsung diminum setelah dibeli. Jangan disimpan terlalu lama di suhu ruang. Kalau pelanggan masih bingung, bisa tanya admin lewat WhatsApp.',
                'image' => '/img/kopisusuaren.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Es Kopi Susu',
                'category' => 'Cup Series',
                'cup_price' => 10000,
                'price_500' => 0,
                'price_1l' => 0,
                'description' => 'Es kopi susu klasik dengan rasa ringan, segar, dan cocok untuk diminum harian.',
                'durability' => 'Produk cup dibuat fresh di hari yang sama dan lebih baik langsung diminum. Jangan dibiarkan di suhu ruang terlalu lama. Jika pelanggan ragu, bisa langsung bertanya melalui WhatsApp Saka.',
                'image' => '/img/kopisusu.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Coklat Susu Aren',
                'category' => 'Cup Series',
                'cup_price' => 12000,
                'price_500' => 0,
                'price_1l' => 0,
                'description' => 'Minuman coklat susu aren dengan rasa manis, lembut, dan creamy untuk pelanggan non coffee.',
                'durability' => 'Produk cup dibuat fresh di hari yang sama dan sebaiknya langsung diminum. Jangan disimpan terlalu lama di suhu ruang. Jika pelanggan bingung, admin dapat membantu menjelaskan lewat WhatsApp.',
                'image' => '/img/coklatsusuaren.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Pinky Milky',
                'category' => 'Cup Series',
                'cup_price' => 10000,
                'price_500' => 0,
                'price_1l' => 0,
                'description' => 'Minuman susu manis berwarna pink dengan rasa lembut, segar.',
                'durability' => 'Produk cup dibuat fresh di hari yang sama dan disarankan langsung diminum. Jangan disimpan di suhu ruang terlalu lama karena kualitas rasa bisa menurun. Jika pelanggan banyak bertanya, arahkan untuk menghubungi WhatsApp Saka.',
                'image' => '/img/pinkymilky.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Creamy Butterscotch',
                'category' => 'Cup Series',
                'cup_price' => 13000,
                'price_500' => 0,
                'price_1l' => 0,
                'description' => 'Minuman creamy dengan rasa butterscotch yang manis, lembut, dan terasa premium.',
                'durability' => 'Produk cup dibuat fresh di hari yang sama dan sebaiknya langsung diminum. Jangan disimpan di suhu ruang terlalu lama karena rasa dan kualitas produk bisa berubah. Kalau pelanggan masih ragu, silakan tanya admin lewat WhatsApp.',
                'image' => '/img/butterscotch.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Kopi Susu Aren Literan',
                'category' => 'Literan',
                'cup_price' => 0,
                'price_500' => 36000,
                'price_1l' => 70000,
                'description' => 'Kopi susu aren dalam kemasan botol 500ml dan 1 liter. Cocok untuk stok minuman di rumah, kantor, atau acara kecil.',
                'durability' => 'Produk botol dibuat fresh di hari yang sama. Simpan di kulkas/chiller dan sebaiknya habiskan dalam 1-2 hari. Jika disimpan di freezer, perkiraan daya tahan 2-3 hari. Jangan disimpan di suhu ruang terlalu lama. Kalau pelanggan belum paham cara penyimpanan, bisa langsung tanya admin lewat WhatsApp.',
                'image' => '/img/kopisusuaren1L.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Es Kopi Susu Literan',
                'category' => 'Literan',
                'cup_price' => 0,
                'price_500' => 33000,
                'price_1l' => 65000,
                'description' => 'Es kopi susu dalam kemasan botol 500ml dan 1 liter dengan rasa ringan dan segar.',
                'durability' => 'Produk botol dibuat fresh di hari yang sama. Wajib disimpan di kulkas/chiller dan sebaiknya habiskan dalam 1-2 hari. Jika disimpan di freezer, perkiraan daya tahan 2-3 hari. Hindari menyimpan produk di suhu ruang. Jika pelanggan kurang paham, bisa bertanya melalui WhatsApp Saka.',
                'image' => '/img/kopisusu1L.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Coklat Susu Aren Literan',
                'category' => 'Literan',
                'cup_price' => 0,
                'price_500' => 36000,
                'price_1l' => 70000,
                'description' => 'Coklat susu aren dalam kemasan botol 500ml dan 1 liter. Cocok untuk pelanggan yang ingin minuman non coffee ukuran besar.',
                'durability' => 'Produk botol dibuat fresh di hari yang sama. Simpan di kulkas/chiller dan sebaiknya habiskan dalam 1-2 hari. Jika disimpan di freezer, perkiraan daya tahan 2-3 hari. Jangan dibiarkan di luar kulkas terlalu lama. Kalau pelanggan bingung, admin dapat membantu lewat WhatsApp.',
                'image' => '/img/coklatsusuaren1L.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Pinky Milky Literan',
                'category' => 'Literan',
                'cup_price' => 0,
                'price_500' => 33000,
                'price_1l' => 65000,
                'description' => 'Pinky Milky dalam kemasan botol 500ml dan 1 liter. Rasanya manis, lembut, dan cocok untuk pelanggan non coffee.',
                'durability' => 'Produk botol dibuat fresh di hari yang sama. Simpan di kulkas/chiller dan sebaiknya habiskan dalam 1-2 hari. Jika disimpan di freezer, perkiraan daya tahan 2-3 hari. Jangan disimpan di suhu ruang karena kualitas rasa bisa menurun. Jika pelanggan banyak bertanya, arahkan ke WhatsApp Saka.',
                'image' => '/img/pinkmilky1L.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Creamy Butterscotch Literan',
                'category' => 'Literan',
                'cup_price' => 0,
                'price_500' => 39000,
                'price_1l' => 78000,
                'description' => 'Creamy Butterscotch dalam kemasan botol 500ml dan 1 liter dengan rasa manis, creamy, dan premium.',
                'durability' => 'Produk botol dibuat fresh di hari yang sama. Simpan di kulkas/chiller dan sebaiknya habiskan dalam 1-2 hari. Jika disimpan di freezer, perkiraan daya tahan 2-3 hari. Jangan disimpan di suhu ruang terlalu lama karena rasa dan kualitas produk bisa berubah. Kalau pelanggan masih ragu, silakan tanya admin lewat WhatsApp.',
                'image' => '/img/butterscotch1L.jpeg',
                'status' => 'Aktif',
            ],
            [
                'name' => 'Donat',
                'category' => 'Snack',
                'cup_price' => 15000,
                'price_500' => 0,
                'price_1l' => 0,
                'description' => 'Donat SAKA sebagai menu pendamping minuman. Produk ini tersedia di beberapa cabang tertentu.',
                'durability' => 'Donat sebaiknya dikonsumsi di hari yang sama agar tekstur dan rasa tetap enak. Simpan di tempat bersih dan tertutup.',
                'image' => '/img/donat.jpeg',
                'status' => 'Aktif',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['name' => $menu['name']],
                $menu
            );
        }
    }
}