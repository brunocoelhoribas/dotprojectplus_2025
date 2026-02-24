<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dotp_skills', static function (Blueprint $table) {
            $table->id('skill_id');
            $table->string('skill_name');
            $table->enum('skill_type', ['technical', 'behavioral'])->default('technical');
            $table->timestamps();
        });

        Schema::create('dotp_human_resource_skills', static function (Blueprint $table) {
            $table->id();

            $table->integer('human_resource_id');

            $table->unsignedBigInteger('skill_id');
            $table->tinyInteger('proficiency_level')->default(1);

            // Chaves Estrangeiras
            $table->foreign('human_resource_id')
                ->references('human_resource_id')
                ->on('dotp_human_resource')
                ->onDelete('cascade');

            $table->foreign('skill_id')
                ->references('skill_id')
                ->on('dotp_skills')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('dotp_skills_tables');
    }
};
