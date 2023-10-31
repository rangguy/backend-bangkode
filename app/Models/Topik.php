<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Topik extends Model
{
    use HasFactory;

    protected $table = 'topik';

    protected $primaryKey = 'id_topik';

    protected $fillable = ['nama_topik', 'logo_topik', 'id_kategori'];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function materi(): HasMany
    {
        return $this->hasMany(Materi::class, 'id_topik');
    }
}
