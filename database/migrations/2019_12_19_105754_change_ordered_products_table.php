<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOrderedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordered_products', function (Blueprint $table) {

            $table->dropColumn('name');
            $table->dropColumn('price');

            $table->unsignedBigInteger('user_id')->after('id');
            $table->unsignedBigInteger('product_id')->after('user_id');
            $table->integer('product_quantity')->after('product_id');


            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordered_products', function (Blueprint $table) {
            $table->string('name');
            $table->integer('price');

            $table->dropColumn('user_id');
            $table->dropColumn('product_id');
            $table->dropColumn('product_quantity');

            $table->dropForeign('user_id');
            $table->dropForeign('product_id');


        });
    }
}
