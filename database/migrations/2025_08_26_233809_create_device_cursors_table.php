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
        Schema::create('device_cursors', function (Blueprint $table) {
            $table->id();

            $table->string('device_id')->unique();
            $table->uuid('last_change_id');
            $table->timestamp('update_date');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_cursors');
    }
};
