<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardContent extends Model
{
    protected $table = 'dashboard_contents';

    protected $fillable = [
        'hero_title',
        'hero_desc',
        'edukasi_cards',
        'dokumentasi_cards',
        'tentang_desc',
        'kontak',
    ];

    protected $casts = [
        'edukasi_cards' => 'array',
        'dokumentasi_cards' => 'array',
    ];

    public static function defaults(): array
    {
        return [
            'hero_title' => 'Pendampingan Pengelolaan Sampah Berkelanjutan',
            'hero_desc'  => 'Fokus program: edukasi pengelolaan sampah, penerapan biopori, dan perencanaan tungku pembakaran minim asap. Dashboard ini bersifat publik untuk menampilkan titik-titik proker secara transparan dan mudah dipahami.',
            'edukasi_cards' => [
                ['title' => 'Apa itu Biopori?', 'body' => 'Lubang resapan yang membantu air meresap ke tanah dan dapat dimanfaatkan untuk penguraian sampah organik.'],
                ['title' => 'Manfaat Biopori', 'body' => 'Mengurangi genangan, memperbaiki struktur tanah, dan mengolah sampah organik secara lebih ramah lingkungan.'],
                ['title' => 'Tungku Minim Asap', 'body' => 'Rancangan pembakaran yang lebih terkendali sehingga mengurangi asap dan risiko pembakaran terbuka di lingkungan.'],
                ['title' => 'Pemilahan Sampah', 'body' => 'Pisahkan organik dan anorganik sejak awal. Organik bisa diolah, anorganik bisa dipilah untuk didaur ulang.'],
                ['title' => 'Kebiasaan Kecil', 'body' => 'Kurangi sampah sekali pakai, gunakan ulang wadah, dan mulai dari langkah sederhana yang konsisten.'],
                ['title' => 'Tujuan Pendampingan', 'body' => 'Mendorong perubahan perilaku berbasis edukasi + solusi sederhana yang bisa dilakukan dan dipelihara bersama.'],
            ],
            'dokumentasi_cards' => [
                ['title' => 'Sosialisasi & Edukasi', 'body' => 'Kegiatan edukasi pengelolaan sampah kepada warga. (Tanggal: —)', 'image' => null],
                ['title' => 'Penerapan Biopori', 'body' => 'Pembuatan titik biopori sebagai solusi resapan & pengolahan organik. (Tanggal: —)', 'image' => null],
                ['title' => 'Perencanaan Tungku', 'body' => 'Diskusi dan rancangan tungku minim asap. (Tanggal: —)', 'image' => null],
            ],
            'tentang_desc' => 'Program pendampingan ini mendorong pengelolaan sampah berkelanjutan melalui edukasi, penerapan biopori, serta perencanaan tungku pembakaran minim asap. Dashboard publik ini dibuat untuk memudahkan masyarakat melihat sebaran titik program dan materi edukasi.',
            'kontak' => '(isi email resmi di sini)',
        ];
    }
}
