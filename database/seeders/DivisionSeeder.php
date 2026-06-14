<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            ['name' => 'Operator Mixer', 'description' => 'Bagian pencampuran bahan baku masterbatch'],
            ['name' => 'Operator Extruder', 'description' => 'Bagian proses ekstrusi/pelelehan masterbatch'],
            ['name' => 'Operator Timbang & WH RM', 'description' => 'Bagian penimbangan dan gudang bahan baku (Raw Material)'],
            ['name' => 'Operator Sample', 'description' => 'Bagian pembuatan dan pengujian sample produk'],
            ['name' => 'Delivery & WH FG', 'description' => 'Bagian pengiriman dan gudang barang jadi (Finished Goods)'],
            ['name' => 'Staff Marketing', 'description' => 'Bagian pemasaran dan penjualan produk'],
            ['name' => 'Staff General Admin', 'description' => 'Bagian administrasi umum perusahaan'],
            ['name' => 'Staff QC', 'description' => 'Bagian quality control / pengendalian kualitas'],
            ['name' => 'Ka. Shift', 'description' => 'Kepala shift produksi (Level 2)'],
            ['name' => 'Ka. PPIC', 'description' => 'Kepala Production Planning & Inventory Control (Level 2)'],
            ['name' => 'Ka. Purchasing & Accounting', 'description' => 'Kepala bagian pembelian dan akunting (Level 2)'],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
