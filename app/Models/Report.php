<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'disaster_id',
        'description',
        'location',
        'latitude',
        'longitude',
        'status',
        'image1',
        'image2',
        'image3'
    ];

    public function disaster()
    {
        return $this->belongsTo(Disaster::class);
    }
}