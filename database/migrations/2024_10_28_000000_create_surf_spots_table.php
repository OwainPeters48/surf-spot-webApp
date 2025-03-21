<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurfSpotsTable extends Migration
{
    public function up()
    {
        Schema::create('surf_spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('location');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced']);
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surf_spots');
    }
}
