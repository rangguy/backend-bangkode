<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $primaryKey = 'id_kategori';

    protected $fillable = ['nama_kategori', 'foto'];

    public function topik(): HasMany
    {
        return $this->hasMany(Topik::class, 'id_kategori');
    }

    public function materi(): HasMany
    {
        return $this->hasMany(Materi::class, 'id_kategori');
    }
}
