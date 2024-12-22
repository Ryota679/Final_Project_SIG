<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location',           // Lokasi geospasial (Point)
        'description',        // Deskripsi lokasi (opsional)
    ];

    /**
     * Relasi dengan laporan (Report).
     * Setiap lokasi dapat memiliki banyak laporan.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Menambahkan accessor untuk mengakses lat/lon dari kolom 'location' (Point).
     * Ini akan mempermudah manipulasi data koordinat.
     */
    public function getLatitudeAttribute()
    {
        return $this->location->getLat();  // Mendapatkan latitude dari point
    }

    public function getLongitudeAttribute()
    {
        return $this->location->getLng();  // Mendapatkan longitude dari point
    }

    /**
     * Mengatur format kolom 'location' agar dapat disimpan dalam format geospasial.
     */
    protected $casts = [
        'location' => 'point', // Cast kolom 'location' ke tipe geospasial
    ];
}
