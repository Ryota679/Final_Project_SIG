<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disaster extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',               // Nama bencana (e.g., Kebakaran, Banjir, Longsor)
        'description',        // Deskripsi bencana
    ];

    /**
     * Relasi dengan laporan (Report).
     * Setiap jenis bencana dapat memiliki banyak laporan.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
