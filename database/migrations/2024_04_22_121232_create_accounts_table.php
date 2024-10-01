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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Client::class)->constrained();
            $table->foreignIdFor(\App\Models\ClientType::class)->constrained();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->foreignIdFor(\App\Models\SolutionType::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Solution::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\SolutionSubType::class)->nullable()->constrained();
            $table->string('business_name')->nullable();
            $table->json('contact_information')->nullable();
            $table->text('comments')->nullable();
            $table->string('solution_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
