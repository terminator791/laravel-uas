<?php

namespace App\Models;

use Database\Factories\WargaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'warga';

    protected static function newFactory(): WargaFactory
    {
        return WargaFactory::new();
    }

    protected $fillable = [
        'nama',
        'alamat',
    ];

    public function iurans(): HasMany
    {
        return $this->hasMany(Iuran::class, 'id_warga');
    }
}
