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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('image');
            $table->text('description');
            $table->unsignedBigInteger('parent_id')->nullable;
            $table->boolean('is_active')->default(true);  // BU QO'SHILISHI KERAK
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }
    public function down():void
    {
        schema::dropIfExists('categories');
    }

    /**
     * Reverse the migrations.
     */

};
