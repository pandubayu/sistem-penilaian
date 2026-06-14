<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\TechnicalCriteria;
use Illuminate\Database\Seeder;

class TechnicalCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $opMixer = Division::where('name', 'Operator Mixer')->first();
        $opExtruder = Division::where('name', 'Operator Extruder')->first();
        $staffQc = Division::where('name', 'Staff QC')->first();

        $criteria = [
            // Operator Mixer
            [
                'division_id' => $opMixer->id,
                'aspect_name' => 'Penguasaan Formula Campuran',
                'indicator_1' => 'Tidak hafal formula, sering salah komposisi bahan baku',
                'indicator_2' => 'Hafal sebagian formula, masih perlu cek ulang ke SPK',
                'indicator_3' => 'Hafal formula utama, jarang salah komposisi',
                'indicator_4' => 'Sangat hafal semua formula, tidak pernah salah komposisi',
                'order_number' => 1,
            ],
            [
                'division_id' => $opMixer->id,
                'aspect_name' => 'Ketepatan Waktu Mixing',
                'indicator_1' => 'Sering melebihi waktu standar mixing tanpa alasan jelas',
                'indicator_2' => 'Kadang melebihi waktu standar, masih dalam toleransi',
                'indicator_3' => 'Selalu sesuai waktu standar mixing',
                'indicator_4' => 'Selalu sesuai waktu standar dan mampu efisienkan proses',
                'order_number' => 2,
            ],
            [
                'division_id' => $opMixer->id,
                'aspect_name' => 'Perawatan Mesin Mixer',
                'indicator_1' => 'Tidak pernah membersihkan/cek mesin setelah pakai',
                'indicator_2' => 'Kadang lupa membersihkan mesin setelah pakai',
                'indicator_3' => 'Rutin membersihkan dan cek mesin setelah pakai',
                'indicator_4' => 'Rutin membersihkan, cek, dan lapor kondisi mesin secara detail',
                'order_number' => 3,
            ],

            // Operator Extruder
            [
                'division_id' => $opExtruder->id,
                'aspect_name' => 'Setting Suhu Ekstrusi',
                'indicator_1' => 'Tidak bisa setting suhu sendiri, selalu minta bantuan',
                'indicator_2' => 'Bisa setting suhu dengan bantuan/pengawasan',
                'indicator_3' => 'Bisa setting suhu sendiri dengan benar',
                'indicator_4' => 'Bisa setting suhu sendiri dan mampu adjust untuk berbagai jenis bahan',
                'order_number' => 1,
            ],
            [
                'division_id' => $opExtruder->id,
                'aspect_name' => 'Penanganan Hasil Cetakan (Pellet)',
                'indicator_1' => 'Banyak pellet reject, tidak tahu cara menangani',
                'indicator_2' => 'Cukup banyak pellet reject, perlu bimbingan',
                'indicator_3' => 'Pellet reject minim, bisa menangani sendiri',
                'indicator_4' => 'Pellet reject sangat minim, mampu optimasi hasil cetakan',
                'order_number' => 2,
            ],
            [
                'division_id' => $opExtruder->id,
                'aspect_name' => 'Penanganan Troubleshoot Mesin',
                'indicator_1' => 'Tidak bisa identifikasi masalah mesin sama sekali',
                'indicator_2' => 'Bisa identifikasi masalah ringan dengan bantuan',
                'indicator_3' => 'Bisa identifikasi dan atasi masalah ringan sendiri',
                'indicator_4' => 'Bisa identifikasi dan atasi masalah ringan-menengah sendiri',
                'order_number' => 3,
            ],

            // Staff QC
            [
                'division_id' => $staffQc->id,
                'aspect_name' => 'Akurasi Pengujian Sample',
                'indicator_1' => 'Hasil pengujian sering tidak akurat/berbeda dari standar',
                'indicator_2' => 'Hasil pengujian kadang tidak akurat, perlu cek ulang',
                'indicator_3' => 'Hasil pengujian akurat dan sesuai standar',
                'indicator_4' => 'Hasil pengujian sangat akurat, mampu deteksi anomali kualitas',
                'order_number' => 1,
            ],
            [
                'division_id' => $staffQc->id,
                'aspect_name' => 'Dokumentasi Hasil QC',
                'indicator_1' => 'Dokumentasi tidak lengkap atau sering terlambat',
                'indicator_2' => 'Dokumentasi cukup lengkap, kadang terlambat',
                'indicator_3' => 'Dokumentasi lengkap dan tepat waktu',
                'indicator_4' => 'Dokumentasi lengkap, tepat waktu, dan rapi/mudah ditelusuri',
                'order_number' => 2,
            ],
            [
                'division_id' => $staffQc->id,
                'aspect_name' => 'Pemahaman Standar Mutu Produk',
                'indicator_1' => 'Tidak paham standar mutu yang berlaku',
                'indicator_2' => 'Paham sebagian standar mutu',
                'indicator_3' => 'Paham seluruh standar mutu yang berlaku',
                'indicator_4' => 'Paham seluruh standar mutu dan bisa menjelaskan ke tim lain',
                'order_number' => 3,
            ],
        ];

        foreach ($criteria as $item) {
            TechnicalCriteria::create($item);
        }
    }
}
