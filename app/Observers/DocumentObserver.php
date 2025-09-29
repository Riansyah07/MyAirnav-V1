<?php

namespace App\Observers;

use App\Models\Document;
use App\Services\VsmService;

class DocumentObserver
{
    protected $vsmService;

    public function __construct(VsmService $vsmService)
    {
        $this->vsmService = $vsmService;
    }

    // Akan berjalan setelah dokumen baru dibuat
    public function created(Document $document): void
    {
        $this->vsmService->generateAndSaveVector($document);
    }

    // Akan berjalan setelah dokumen di-update
    public function updated(Document $document): void
    {
        $this->vsmService->generateAndSaveVector($document);
    }
}