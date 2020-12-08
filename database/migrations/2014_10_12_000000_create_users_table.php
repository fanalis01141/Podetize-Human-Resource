<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('fname');
            $table->string('lname')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('prof_pic')->nullable();;
            $table->string('username')->unique();
            $table->string('password');
            $table->string('date_hired')->nullable();
            $table->string('workversary')->nullable();
            $table->string('emp_status')->nullable();
            $table->string('weeks_of_training')->nullable();
            $table->string('daily_rate')->nullable();
            $table->string('bi_weekly_rate')->nullable();
            $table->string('monthly_rate')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('referred_by')->nullable();
            $table->string('priority')->nullable();
            $table->string('active')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
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
