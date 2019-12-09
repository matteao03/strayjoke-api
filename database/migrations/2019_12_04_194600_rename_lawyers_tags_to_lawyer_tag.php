<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLawyersTagsToLawyerTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('lawyers_tags', function (Blueprint $table) {

        // });
        Schema::rename('lawyers_tags', 'lawyer_tag');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('lawyer_tag', 'lawyers_tags');
    }
}
