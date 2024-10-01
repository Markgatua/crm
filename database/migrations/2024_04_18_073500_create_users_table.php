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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Role::class)->constrained();
            $table->foreignIdFor(\App\Models\Department::class)->constrained();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('calling_code')->default('254');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->boolean('sales_rep');
            $table->string('designation')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_phone_verified')->default(0);
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        DB::table('users')->insert([
            array('role_id'=>1,
            'department_id'=>1,
            'first_name'=>'Xtranet',
            'last_name'=>'Dev',
            'email'=>'david.gakobo@xtranet.co.ke',
            'email_verified_at'=>'2023-04-08 20:20:20',
            'phone_verified_at'=>'2023-04-08 20:20:20',
            'password'=>'$2y$12$d.5HM.0rQvCMvCBf8kHzGOOSoYz8NfGAo5mP6IcSpoNxVrHSKleqK', //Gakobo@24
            'calling_code'=>'254',
            'designation'=>'Developer',
            'phone'=>'0711532131',
            'is_phone_verified'=>1,
            'is_active'=>1,
            'sales_rep'=>0,
        ),
        array('role_id'=>1,
            'department_id'=>1,
            'first_name'=>'Xtranet',
            'last_name'=>'Dev',
            'email'=>'hemstone456@gmail.com',
            'email_verified_at'=>'2023-04-08 20:20:20',
            'phone_verified_at'=>'2023-04-08 20:20:20',
            'password'=>'$2y$12$d.5HM.0rQvCMvCBf8kHzGOOSoYz8NfGAo5mP6IcSpoNxVrHSKleqK', //Gakobo@24
            'calling_code'=>'254',
            'designation'=>'Developer',
            'phone'=>'0711532132',
            'is_phone_verified'=>1,
            'is_active'=>1,
            'sales_rep'=>1,
        )

    ]);

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
