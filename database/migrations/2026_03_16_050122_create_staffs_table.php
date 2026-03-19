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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
			$table->string('name');
        $table->string('email')->index();
		$table->string('phone')->index()->nullable();
        $table->string('image')->nullable();
        $table->string('password');
        $table->tinyInteger('is_super_admin')->default(0);
        $table->string('status')->default('active');
        $table->string('forget_password_token')->nullable();
        $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
