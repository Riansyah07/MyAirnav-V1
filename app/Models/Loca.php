<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loca extends Model
{
    use HasFactory;

    protected $table = 'locas'; // pastikan nama tabel di DB adalah 'locas'

    protected $fillable = [
        'name',
        'category',
        'note',
        'file_path', 
        'file_type',
        'user_id',
    ];

    /**
     * Relasi ke tabel users (user yang mengunggah).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
