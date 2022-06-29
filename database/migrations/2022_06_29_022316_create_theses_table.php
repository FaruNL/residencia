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
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->string('thesis_code')->index();
            $table->string('title');
            $table->boolean('state')->default(true);
            $table->timestamps();
            $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->onDelete('set null');
            $table->foreignId('course_detail_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theses');
    }
};
