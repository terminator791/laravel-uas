<?php

namespace App\Models;

use Database\Factories\IuranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Iuran extends Model
{
    use HasFactory;

    protected static function newFactory(): IuranFactory
    {
        return IuranFactory::new();
    }

    protected $table = 'iuran';

    protected $fillable = [
        'id_warga',
        'bulan',
        'jumlah_iuran',
        'status',
    ];

    protected $casts = [
        'bulan' => 'date',
        'jumlah_iuran' => 'integer',
    ];

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'id_warga');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByYear($query, int $year)
    {
        return $query->whereYear('bulan', $year);
    }
}
