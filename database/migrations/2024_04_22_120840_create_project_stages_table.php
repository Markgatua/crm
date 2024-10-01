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
        Schema::create('project_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        DB::table('project_stages')->insert([
            array('name'=>'Prospects'),
            array('name'=>'Scooping'),
            array('name'=>'Evaluation'),
            array('name'=>'Approval'),
            array('name'=>'Closed Deal'),
            array('name'=>'Lost Deal'),
            array('name'=>'Presale/BDM'),
            array('name'=>'Projects'),
            array('name'=>'Overdue'),
            array('name'=>'CRM')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_stages');
    }
};
