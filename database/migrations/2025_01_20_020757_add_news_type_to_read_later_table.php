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
        Schema::table('read_later', function (Blueprint $table) {
            $table->string('news_type'); // Add the news_type column for polymorphic relationships
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('read_later', function (Blueprint $table) {
            $table->dropColumn('news_type');
        });
    }
};
