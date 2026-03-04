<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('dotp_human_resource_performance', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('human_resource_id');

            $table->tinyInteger('performance_score')->comment('1: Low, 2: Medium, 3: High');
            $table->tinyInteger('potential_score')->comment('1: Low, 2: Medium, 3: High');

            $table->text('facilitator_notes')->nullable();

            $table->date('evaluation_date');
            $table->timestamps();

            $table->foreign('company_id')->references('company_id')->on('dotp_companies')->onDelete('cascade');
            $table->foreign('human_resource_id')->references('human_resource_id')->on('dotp_human_resources')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('dotp_human_resource_performance');
    }
};
