<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('disasters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert default disaster types dengan created_at dan updated_at
        $now = now();
        DB::table('disasters')->insert([
            ['name' => 'Gempa', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kebakaran', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Banjir', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Puting Beliung', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pohon Tumbang', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tanah Longsor', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kekeringan', 'created_at' => $now, 'updated_at' => $now]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('disasters');
    }
};
