<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('emp_id');
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
            $table->string('attend_date')->nullable();
            $table->integer('overtime')->nullable();
            $table->integer('undertime')->nullable();
            $table->integer('pay_for_day')->nullable();
            $table->integer('otpay_for_day')->nullable();
            $table->integer('ded_for_day')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
