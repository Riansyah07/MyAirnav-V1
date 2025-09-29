<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_id',
        'action',
    ];

    protected $appends = ['message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function getMessageAttribute()
    {
        $actionVerb = match ($this->action) {
            'create' => 'mengunggah',
            'update' => 'memperbarui',
            'delete' => 'menghapus',
            default => 'melakukan aksi pada',
        };

        $userName = $this->user->name ?? 'Pengguna tidak dikenal';
        $documentTitle = $this->document->title ?? '<em>dokumen telah dihapus</em>';

        return "{$userName} {$actionVerb} dokumen <strong>{$documentTitle}</strong>";
    }
}
