<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnsignedToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->unsigned()->change();
            $table->integer('period_value')->unsigned()->change();
            $table->boolean('is_deleted')->unsigned()->default(false);
            $table->boolean('on_sale')->unsigned()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
            $table->integer('period_value')->change();
            $table->dropColumn('is_deleted');
            $table->dropColumn('on_sale');
        });
    }
}
