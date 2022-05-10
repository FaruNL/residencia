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
        Schema::create('group_assignments', function (Blueprint $table) {
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
            $table->foreignId('group_id')
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
        Schema::dropIfExists('group_assignments');
    }
};
