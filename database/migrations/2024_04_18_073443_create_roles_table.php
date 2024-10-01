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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
        DB::table('roles')->insert([
            array('name'=>'Super Admin'),
            array('name'=>'Admin'),
            array('name'=>'Sales Manager'),
            array('name'=>'Sales Person'),
            array('name'=>'CRM'),
            array('name'=>'Pre Sales'),
            array('name'=>'Marketing'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
