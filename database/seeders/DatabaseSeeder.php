<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $catServer = Category::create([
            'name' => 'Server & Cloud',
            'code' => 'SRV',
        ]);

        $catIot = Category::create([
            'name' => 'Internet of Things',
            'code' => 'IOT',
        ]);

        $catDrone = Category::create([
            'name' => 'Multimedia & Drone',
            'code' => 'DRN',
        ]);

        Equipment::create([
            'category_id' => $catServer->id,
            'asset_code' => 'EQ-SRV-001',
            'name' => 'Dell PowerEdge T40 Server',
            'specification' => 'Intel Xeon E-2224G, 16GB RAM, 1TB SATA HDD, Single Power Supply 300W',
            'condition' => 'baik',
            'status' => 'dipinjam',
            'daily_rental_fee' => 50000,
            'image_path' => null,
        ]);

        Equipment::create([
            'category_id' => $catServer->id,
            'asset_code' => 'EQ-SRV-002',
            'name' => 'HPE ProLiant MicroServer Gen10 Plus',
            'specification' => 'Intel Xeon E-2224, 16GB DDR4 ECC, 4 LFF Drive Bays',
            'condition' => 'rusak_ringan',
            'status' => 'dalam_perbaikan',
            'daily_rental_fee' => 45000,
            'image_path' => null,
        ]);

        Equipment::create([
            'category_id' => $catIot->id,
            'asset_code' => 'EQ-IOT-001',
            'name' => 'ESP32 LoRa WAN Gateway & Sensor Node Kit',
            'specification' => 'SX1276 LoRa transceiver 915MHz, OLED display 0.96 inch, DHT22, GPS module',
            'condition' => 'baik',
            'status' => 'tersedia',
            'daily_rental_fee' => 20000,
            'image_path' => null,
        ]);

        Equipment::create([
            'category_id' => $catIot->id,
            'asset_code' => 'EQ-IOT-002',
            'name' => 'Arduino Prototyping Comprehensive Kit',
            'specification' => 'Arduino Uno R3 original, Breadboard, 65 jumper wires, modul sensor lengkap',
            'condition' => 'rusak_berat',
            'status' => 'dalam_perbaikan',
            'daily_rental_fee' => 10000,
            'image_path' => null,
        ]);

        Equipment::create([
            'category_id' => $catDrone->id,
            'asset_code' => 'EQ-DRN-001',
            'name' => 'DJI Mavic Air 2 Fly More Combo',
            'specification' => 'Sensor 1/2-inch 48MP, 4K 60fps video, 3 baterai cerdas, filter ND, tas ransel',
            'condition' => 'baik',
            'status' => 'tersedia',
            'daily_rental_fee' => 50000,
            'image_path' => null,
        ]);

        Equipment::create([
            'category_id' => $catDrone->id,
            'asset_code' => 'EQ-DRN-003',
            'name' => 'FPV Racing Drone BetaFPV Cetus Pro',
            'specification' => 'Brushless motor 1102 18000KV, FPV Goggles VR02, LiteRadio2 SE transmitter',
            'condition' => 'baik',
            'status' => 'tersedia',
            'daily_rental_fee' => 25000,
            'image_path' => null,
        ]);
    }
}
