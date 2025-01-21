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
        Schema::create('nytimes_news', function (Blueprint $table) {
            $table->id();
            $table->text('section');
            $table->text('subsection');
            $table->text('title');
            $table->text('abstract');
            $table->text('url');
            $table->text('uri');
            $table->text('byline');
            $table->text('item_type');
            $table->text('published_date');
            $table->text('imageUrl');
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
        Schema::dropIfExists('nytimes_news');
    }
};
