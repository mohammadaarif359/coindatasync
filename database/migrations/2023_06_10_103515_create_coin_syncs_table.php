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
        Schema::create('coin_syncs', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('requested_by')->nullable();
			$table->string('status');
			$table->text('errors')->nullable();
			$table->timestamp('started_at')->nullable();
			$table->timestamp('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_syncs');
    }
};
