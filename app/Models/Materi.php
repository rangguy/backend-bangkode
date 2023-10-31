<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materi extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_materi';
    protected $fillable = [
        'id_materi',
        'judul_materi',
        'url_materi',
        'deskripsi_materi',
        'id_topik'
    ];

    public function topik(): BelongsTo
    {
        return $this->belongsTo(Topik::class, 'id_topik');
    }
}
