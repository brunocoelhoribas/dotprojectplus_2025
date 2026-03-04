<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('dotp_raci', static function (Blueprint $table) {
            $table->id();
            $table->integer('human_resource_id');
            $table->integer('project_id');
            $table->string('activity_name');
            $table->enum('raci_role', ['R', 'A', 'C', 'I']);
            $table->timestamps();

            $table->foreign('human_resource_id')
                ->references('human_resource_id')
                ->on('dotp_human_resource')
                ->onDelete('cascade');

            $table->foreign('project_id')
                ->references('project_id')
                ->on('dotp_projects')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('dotp_raci');
    }
};
