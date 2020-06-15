<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('firstname'); 
            $table->string('lastname');
            $table->date('dob')->nullable();
            $table->string('region')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); 
            $table->rememberToken();
            $table->timestamps();
            $table->string('verifytoken')->nullable();
        });

        // Create the initial admin user
        DB::table('users')->insert([
            'email' => 'admin@admin.com',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'password' => bcrypt('password'),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
