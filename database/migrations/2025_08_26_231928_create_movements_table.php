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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('period_id')->constrained('periods');
            $table->foreignId('concept_id')->constrained('concepts');

            $table->decimal('amount', 14);
            $table->text('observations');
            $table->json('labels')->default('[]');
            $table->json('extra')->default('[]');
            $table->bigInteger('version')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
