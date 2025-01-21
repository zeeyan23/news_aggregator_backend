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
        Schema::create('guardian_news', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('sectionId');
            $table->string('sectionName');
            $table->text('webPublicationDate');
            $table->string('webTitle');
            $table->string('webUrl');
            $table->dateTime('apiUrl');
            $table->text('isHosted');
            $table->text('pillarId');
            $table->text('pillarName');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guardian_news');
    }
};
