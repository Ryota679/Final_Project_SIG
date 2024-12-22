<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();                    // Primary key
            $table->point('location');        // Kolom geospasial untuk menyimpan titik lokasi
            $table->string('description')->nullable();  // Deskripsi lokasi (opsional)
            $table->timestamps();             // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
