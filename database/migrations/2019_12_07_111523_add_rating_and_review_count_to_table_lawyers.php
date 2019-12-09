<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRatingAndReviewCountToTableLawyers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->float('rating')->default(5);
            $table->unsignedInteger('review_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->dropColumn('rating');
            $table->dropColumn('review_count');
        });
    }
}
