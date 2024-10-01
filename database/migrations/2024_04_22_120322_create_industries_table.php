<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        DB::table('industries')->insert([
            array('name'=>'Public Sector'),
            array('name'=>'Saccos'),
            array('name'=>'Banking'),
            array('name'=>'Agriculture'),
            array('name'=>'Education'),
            array('name'=>'NGOs'),
            array('name'=>'Parastatals'),
            array('name'=>'Building & Construction'),
            array('name'=>'Insuarance'),
            array('name'=>'Health Care'),
            array('name'=>'Micro Finance'),
            array('name'=>'Manufacturing'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industries');
    }
};
