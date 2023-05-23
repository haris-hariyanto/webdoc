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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('slug');
            $table->string('alt_slug_1')->nullable();
            $table->string('alt_slug_2')->nullable();
            $table->string('alt_slug_3')->nullable();
            $table->string('alt_slug_4')->nullable();
            $table->string('alt_slug_5')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('author')->nullable();
            $table->integer('downloads')->default(0);
            $table->integer('views')->default(0);
            $table->integer('file_size')->default(0);
            $table->integer('disk_id');
            $table->string('file_url');
            $table->string('file_path');
            $table->string('text_url')->nullable();
            $table->string('text_path')->nullable();
            $table->text('description')->nullable();
            $table->string('file_type');
            $table->string('source')->nullable();
            $table->string('source_id')->nullable();
            $table->string('document_type');
            $table->integer('pages')->default(0);
            $table->string('language')->nullable();
            $table->text('additional_data')->nullable();
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
        Schema::dropIfExists('documents');
    }
};
