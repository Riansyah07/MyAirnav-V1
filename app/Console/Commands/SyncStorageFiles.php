<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\User;
use App\Services\VsmService; // DITAMBAHKAN: Import VsmService

class SyncStorageFiles extends Command
{
    /**
     * DIUBAH: Nama command disesuaikan agar lebih merefleksikan tujuannya
     */
    protected $signature = 'documents:index';

    /**
     * DIUBAH: Deskripsi diperjelas
     */
    protected $description = 'Sinkronisasi file dari storage dan generate VSM vector untuk semua dokumen';

    /**
     * Jalankan logic command.
     */
    public function handle(VsmService $vsmService) // DITAMBAHKAN: Inject VsmService
    {
        $this->info('Memulai proses sinkronisasi dan indexing...');

        // === BAGIAN 1: SINKRONISASI FILE BARU ===
        $this->line('Mencari file baru di storage...');
        $files = Storage::disk('public')->files('documents');
        $defaultUserId = User::first()->id ?? 1;

        foreach ($files as $filePath) {
            if (!Document::where('file_path', $filePath)->exists()) {
                $this->warn(" -> File baru ditemukan: {$filePath}. Menambahkan ke database...");
                Document::create([
                    'title'       => pathinfo($filePath, PATHINFO_FILENAME),
                    'category'    => 'Pengantar', // Kategori default untuk file baru
                    'file_path'   => $filePath,
                    'file_type'   => pathinfo($filePath, PATHINFO_EXTENSION),
                    'uploaded_by' => $defaultUserId,
                ]);
            }
        }
        $this->info('Sinkronisasi file baru selesai.');

        // === BAGIAN 2: RE-INDEXING SEMUA DOKUMEN ===
        $this->line("\nMemulai proses indexing VSM untuk semua dokumen...");
        $allDocuments = Document::all();
        
        // Membuat progress bar agar proses terlihat lebih interaktif
        $bar = $this->output->createProgressBar($allDocuments->count());
        $bar->start();

        foreach ($allDocuments as $document) {
            // Memanggil fungsi generateAndSaveVector untuk setiap dokumen
            $vsmService->generateAndSaveVector($document);
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n\nProses indexing untuk " . $allDocuments->count() . " dokumen telah selesai.");

        return 0;
    }
}