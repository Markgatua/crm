<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_stage_information', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\ProjectStage::class)->constrained();
            $table->foreignIdFor(\App\Models\Account::class)->constrained();
            $table->string('stage_information');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_stage_information');
    }
};
