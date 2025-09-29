<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Isr extends Model
{
    use HasFactory;

    protected $table = 'isrs';

    protected $fillable = [
        'name',
        'note',
        'file_path',
        'file_type',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
