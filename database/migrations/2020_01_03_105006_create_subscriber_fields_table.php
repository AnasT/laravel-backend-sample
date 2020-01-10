<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriberFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable();
            $table->unsignedBigInteger('subscriber_id');
            $table->foreign('subscriber_id')
                ->references('id')
                ->on('subscribers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('field_id');
            $table->foreign('field_id')
                ->references('id')
                ->on('fields')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unique(['subscriber_id', 'field_id']);
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
        Schema::dropIfExists('subscriber_fields');
    }
}
