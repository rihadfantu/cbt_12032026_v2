<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Relasi;
use App\Models\BankSoal;
use App\Models\RuangUjian;
use App\Models\Pengumuman;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        Admin::create(['email' => 'admin@mtsn.com', 'name' => 'Admin Utama', 'password' => Hash::make('123')]);

        // Kelas
        $k7a = Kelas::create(['name' => '7A']);
        $k7b = Kelas::create(['name' => '7B']);

        // Mapel
        $mMath = Mapel::create(['name' => 'Matematika']);
        $mBindo = Mapel::create(['name' => 'Bahasa Indonesia']);
        $mArab = Mapel::create(['name' => 'Bahasa Arab']);

        // Guru
        $guru = Guru::create(['nik' => '1234567890123456', 'name' => 'Budi Santoso, S.Pd', 'password' => Hash::make('123')]);

        // Siswa
        Siswa::create(['nisn' => '1234567890', 'name' => 'Ahmad Dahlan', 'kelas_id' => $k7a->id, 'password' => Hash::make('123')]);

        // Relasi
        Relasi::create(['guru_id' => $guru->id, 'kelas_ids' => [$k7a->id, $k7b->id], 'mapel_ids' => [$mMath->id, $mBindo->id, $mArab->id]]);

        // Bank Soal Matematika
        $soalsMath = [
            ['tipe'=>'pg','soal'=>'Berapakah hasil dari 2 + 2?','opsi'=>['A'=>'3','B'=>'4','C'=>'5','D'=>'6','E'=>'7'],'kunci'=>'B'],
            ['tipe'=>'pg','soal'=>'Berapakah hasil dari 5 × 5?','opsi'=>['A'=>'20','B'=>'25','C'=>'30','D'=>'35','E'=>'40'],'kunci'=>'B'],
            ['tipe'=>'pg','soal'=>'Berapakah akar dari 144?','opsi'=>['A'=>'11','B'=>'12','C'=>'13','D'=>'14','E'=>'15'],'kunci'=>'B'],
            ['tipe'=>'pg','soal'=>'Berapakah hasil dari 100 ÷ 4?','opsi'=>['A'=>'20','B'=>'25','C'=>'30','D'=>'35','E'=>'40'],'kunci'=>'B'],
            ['tipe'=>'pg','soal'=>'Berapakah hasil dari 3³?','opsi'=>['A'=>'9','B'=>'18','C'=>'27','D'=>'36','E'=>'45'],'kunci'=>'C'],
            ['tipe'=>'pg','soal'=>'Berapakah 50% dari 200?','opsi'=>['A'=>'50','B'=>'75','C'=>'100','D'=>'125','E'=>'150'],'kunci'=>'C'],
            ['tipe'=>'pg','soal'=>'Berapakah hasil dari 1/2 + 1/4?','opsi'=>['A'=>'1/6','B'=>'2/6','C'=>'3/4','D'=>'1/2','E'=>'2/3'],'kunci'=>'C'],
            ['tipe'=>'pg','soal'=>'Jika sisi persegi = 6 cm, berapakah luasnya?','opsi'=>['A'=>'12 cm²','B'=>'24 cm²','C'=>'36 cm²','D'=>'48 cm²','E'=>'60 cm²'],'kunci'=>'C'],
            ['tipe'=>'pg','soal'=>'Segitiga siku-siku dengan alas 3 dan tinggi 4, berapakah hipotenusanya?','opsi'=>['A'=>'3','B'=>'4','C'=>'5','D'=>'6','E'=>'7'],'kunci'=>'C'],
            ['tipe'=>'pg','soal'=>'Berapakah hasil dari 10 - (-5)?','opsi'=>['A'=>'5','B'=>'10','C'=>'15','D'=>'20','E'=>'25'],'kunci'=>'C'],
        ];

        $bankMath = BankSoal::create([
            'guru_id' => $guru->id,
            'mapel_id' => $mMath->id,
            'title' => 'Ujian Matematika Semester 1',
            'timer' => 60,
            'bobot_pg' => 100,
            'bobot_essai' => 0,
            'bobot_bs' => 0,
            'bobot_jodoh' => 0,
            'soals' => $soalsMath,
            'is_archived' => false,
        ]);

        // Bank Soal Bahasa Arab
        $soalsArab = [
            ['tipe'=>'pg','soal'=>'Apa arti kata "كِتَابٌ" dalam bahasa Indonesia?','opsi'=>['A'=>'Pena','B'=>'Buku','C'=>'Meja','D'=>'Kursi','E'=>'Tas'],'kunci'=>'B'],
            ['tipe'=>'pg','soal'=>'Apa arti kata "مَدْرَسَةٌ" dalam bahasa Indonesia?','opsi'=>['A'=>'Rumah','B'=>'Masjid','C'=>'Sekolah','D'=>'Pasar','E'=>'Kantor'],'kunci'=>'C'],
            ['tipe'=>'pg','soal'=>'Apa arti kata "مُعَلِّمٌ" dalam bahasa Indonesia?','opsi'=>['A'=>'Murid','B'=>'Kepala Sekolah','C'=>'Penjaga','D'=>'Guru','E'=>'Teman'],'kunci'=>'D'],
            ['tipe'=>'pg','soal'=>'Apa arti kata "بَيْتٌ" dalam bahasa Indonesia?','opsi'=>['A'=>'Jalan','B'=>'Rumah','C'=>'Kebun','D'=>'Sawah','E'=>'Hutan'],'kunci'=>'B'],
            ['tipe'=>'pg','soal'=>'Apa arti kata "يَدٌ" dalam bahasa Indonesia?','opsi'=>['A'=>'Kaki','B'=>'Kepala','C'=>'Tangan','D'=>'Mata','E'=>'Hidung'],'kunci'=>'C'],
            ['tipe'=>'pg','soal'=>'Bagaimana kata "Air" dalam bahasa Arab?','opsi'=>['A'=>'نَارٌ','B'=>'مَاءٌ','C'=>'هَوَاءٌ','D'=>'تُرَابٌ','E'=>'شَجَرٌ'],'kunci'=>'B'],
            ['tipe'=>'pg','soal'=>'Apa arti kata "قَلَمٌ" dalam bahasa Indonesia?','opsi'=>['A'=>'Buku','B'=>'Penggaris','C'=>'Pena/Pulpen','D'=>'Pensil','E'=>'Penghapus'],'kunci'=>'C'],
            ['tipe'=>'pg','soal'=>'Apa arti kata "أُسْتَاذٌ" dalam bahasa Indonesia?','opsi'=>['A'=>'Siswa','B'=>'Pengawas','C'=>'Dokter','D'=>'Guru/Ustadz','E'=>'Wali murid'],'kunci'=>'D'],
            ['tipe'=>'pg','soal'=>'Bagaimana kata "Satu" dalam bahasa Arab?','opsi'=>['A'=>'اِثْنَانِ','B'=>'ثَلَاثَةٌ','C'=>'أَرْبَعَةٌ','D'=>'وَاحِدٌ','E'=>'خَمْسَةٌ'],'kunci'=>'D'],
            ['tipe'=>'pg','soal'=>'Apa arti kata "مَسْجِدٌ" dalam bahasa Indonesia?','opsi'=>['A'=>'Gereja','B'=>'Pura','C'=>'Mushola','D'=>'Masjid','E'=>'Vihara'],'kunci'=>'D'],
        ];

        $bankArab = BankSoal::create([
            'guru_id' => $guru->id,
            'mapel_id' => $mArab->id,
            'title' => 'Ujian Bahasa Arab Semester 1',
            'timer' => 45,
            'bobot_pg' => 100,
            'bobot_essai' => 0,
            'bobot_bs' => 0,
            'bobot_jodoh' => 0,
            'soals' => $soalsArab,
            'is_archived' => false,
        ]);

        // Ruang Ujian
        RuangUjian::create([
            'name' => 'CBT Matematika MTs',
            'token' => 'MATH99',
            'bank_id' => $bankMath->id,
            'login_limit' => 3,
            'min_time_submit' => 0,
            'classes' => [$k7a->id, $k7b->id],
            'start_at' => '2024-01-01 00:00:00',
            'end_at' => '2030-12-31 23:59:59',
            'random_soal' => true,
            'random_ops' => true,
        ]);

        RuangUjian::create([
            'name' => 'CBT B.Arab MTs',
            'token' => 'ARAB99',
            'bank_id' => $bankArab->id,
            'login_limit' => 3,
            'min_time_submit' => 0,
            'classes' => [$k7a->id, $k7b->id],
            'start_at' => '2024-01-01 00:00:00',
            'end_at' => '2030-12-31 23:59:59',
            'random_soal' => true,
            'random_ops' => true,
        ]);

        // Pengumuman
        Pengumuman::create([
            'title' => 'Selamat Datang',
            'content' => '<p>Ujian Semester Ganjil akan segera dimulai. Persiapkan diri Anda!</p>',
            'target_kelas' => [$k7a->id, $k7b->id],
        ]);

        // Settings
        Setting::create(['key' => 'exam_browser_only', 'value' => '0']);
    }
}
