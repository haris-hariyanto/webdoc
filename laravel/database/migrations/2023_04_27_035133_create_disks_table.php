<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('access_key');
            $table->string('secret_key');
            $table->string('region')->nullable();
            $table->string('bucket');
            $table->string('endpoint');
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->bigInteger('total_size')->default(0);
            $table->integer('total_files')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disks');
    }
};
