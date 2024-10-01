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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Account::class)->constrained();
            $table->foreignIdFor(\App\Models\DocumentType::class)->constrained();
            $table->unsignedBigInteger('project_stage_information_id');
            $table->foreign('project_stage_information_id')->references('id')->on('project_stage_information');
            $table->string('document_name');
            $table->string('document_link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
