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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_storage_id');
            $table->foreignId('storage_id');
            $table->string('file_code')->required();
            $table->string('file_name')->required();
            $table->string('file_description');
            $table->string('file_source');
            $table->string('file_destination')->nullable();
            $table->date('file_receivedDate');
            $table->date('file_releasedDate')->nullable();
            $table->string('file_receivedBy');
            $table->string('file_status');
            $table->longText('file_images')->default('default/no_image.jpg');
            $table->longText('documents')->default('default/no_image.jpg');
            $table->longText('file_link')->nullable();
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
        Schema::dropIfExists('files');
    }
};
