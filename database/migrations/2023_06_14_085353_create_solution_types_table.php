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
        Schema::create('solution_types', function (Blueprint $table) {
            $table->id();
            $table->string('solution_type_name');
            $table->timestamps();
        });
        DB::table('solution_types')->insert([
            array('solution_type_name'=>'Internet & Broadband'),
            array('solution_type_name'=>'IP Infrastructure'),
            array('solution_type_name'=>'Security'),
            array('solution_type_name'=>'Cloud Solutions'),
            array('solution_type_name'=>'Software & App Development'),
            array('solution_type_name'=>'Print & Content Management'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solution_types');
    }
};
