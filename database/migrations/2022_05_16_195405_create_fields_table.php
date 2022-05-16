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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->string('type');
            $table->string('value');
            $table->timestamps();
        });

        // Schema::table('data', function (Blueprint $table) {
        //     $table->dropColumn('data');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_field');
        Schema::dropIfExists('fields');
    }
};
