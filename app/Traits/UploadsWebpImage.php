<?php

namespace App\Traits;

use Buglinjo\LaravelWebp\Facades\Webp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait UploadsWebpImage
{
    /**
     * Upload gambar dan konversi ke WebP.
     *
     * @param  UploadedFile  $file   File dari request
     * @param  string  $folder      Sub-folder di storage/app/public (misal: 'banners')
     * @param  int  $quality        Kualitas WebP (0-100)
     * @return string               Relative path untuk disimpan di DB (misal: 'banners/abc123.webp')
     */
    protected function uploadWebp(UploadedFile $file, string $folder, int $quality = 80): string
    {
        $filename = uniqid() . '.webp';
        $directory = storage_path("app/public/{$folder}");

        // Pastikan folder tujuan ada
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $outputPath = "{$directory}/{$filename}";

        // Konversi dan simpan sebagai WebP
        Webp::make($file)->quality($quality)->save($outputPath);

        return "{$folder}/{$filename}";
    }

    /**
     * Hapus file gambar dari storage public.
     *
     * @param  string|null  $path  Relative path (misal: 'banners/abc123.webp')
     */
    protected function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
