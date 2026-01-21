<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardContent;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DashboardContentController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_desc'  => ['nullable', 'string'],

            'edukasi_title' => ['required', 'array', 'size:6'],
            'edukasi_title.*' => ['nullable', 'string', 'max:120'],
            'edukasi_body' => ['required', 'array', 'size:6'],
            'edukasi_body.*' => ['nullable', 'string', 'max:500'],

            'dok_title' => ['required', 'array', 'size:3'],
            'dok_title.*' => ['nullable', 'string', 'max:120'],
            'dok_body' => ['required', 'array', 'size:3'],
            'dok_body.*' => ['nullable', 'string', 'max:500'],

            'dok_image' => ['nullable', 'array'],
            'dok_image.*' => ['nullable', 'image', 'max:4096'], // 4MB

            // tombol hapus/undo akan set nilai 1/0
            'dok_remove' => ['nullable', 'array'],
            'dok_remove.*' => ['nullable', 'in:0,1'],

            'tentang_desc' => ['nullable', 'string'],
            'kontak' => ['nullable', 'string', 'max:255'],
        ]);

        $content = DashboardContent::firstOrCreate(['id' => 1], DashboardContent::defaults());

        // ===== EDUKASI =====
        $edukasi = [];
        for ($i = 0; $i < 6; $i++) {
            $edukasi[] = [
                'title' => Arr::get($validated, "edukasi_title.$i"),
                'body'  => Arr::get($validated, "edukasi_body.$i"),
            ];
        }

        // ===== DOKUMENTASI =====
        $dokOld = $content->dokumentasi_cards ?? DashboardContent::defaults()['dokumentasi_cards'];
        $dok = [];

        // upload ke folder public/uploads/dashboard (yang sudah berhasil di kamu)
        $uploadDir = public_path('uploads/dashboard');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $files = $request->file('dok_image', []);
        $remove = $request->input('dok_remove', []); // array index => 0/1

        for ($i = 0; $i < 3; $i++) {
            $oldImage = $dokOld[$i]['image'] ?? null; // contoh: /uploads/dashboard/xxx.jpg
            $newImagePath = $oldImage;

            $wantRemove = isset($remove[$i]) && (string)$remove[$i] === '1';

            // ✅ kalau hapus aktif: hapus file lama dan set null (abaikan upload)
            if ($wantRemove) {
                if ($oldImage && str_starts_with($oldImage, '/uploads/dashboard/')) {
                    $oldFullPath = public_path(ltrim($oldImage, '/'));
                    if (is_file($oldFullPath)) {
                        @unlink($oldFullPath);
                    }
                }
                $newImagePath = null;
            } else {
                // ✅ kalau tidak hapus, boleh replace dengan upload baru
                if (isset($files[$i]) && $files[$i] && $files[$i]->isValid()) {
                    // hapus file lama kalau ada
                    if ($oldImage && str_starts_with($oldImage, '/uploads/dashboard/')) {
                        $oldFullPath = public_path(ltrim($oldImage, '/'));
                        if (is_file($oldFullPath)) {
                            @unlink($oldFullPath);
                        }
                    }

                    $ext = strtolower($files[$i]->getClientOriginalExtension() ?: 'jpg');
                    $safeName = 'dok_' . ($i + 1) . '_' . Str::uuid()->toString() . '.' . $ext;

                    $files[$i]->move($uploadDir, $safeName);
                    $newImagePath = '/uploads/dashboard/' . $safeName;
                }
            }

            $dok[] = [
                'title' => Arr::get($validated, "dok_title.$i"),
                'body'  => Arr::get($validated, "dok_body.$i"),
                'image' => $newImagePath,
            ];
        }

        $content->update([
            'hero_title' => $validated['hero_title'] ?? $content->hero_title,
            'hero_desc'  => $validated['hero_desc'] ?? $content->hero_desc,
            'edukasi_cards' => $edukasi,
            'dokumentasi_cards' => $dok,
            'tentang_desc' => $validated['tentang_desc'] ?? $content->tentang_desc,
            'kontak' => $validated['kontak'] ?? $content->kontak,
        ]);

        return redirect()->back()->with('success', 'Konten dashboard berhasil disimpan.');
    }
}
