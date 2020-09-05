<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->foreignId('category_id')->unsigned()->index()->default('0');  
            $table->foreignId('sub_category_id')->unsigned()->index()->default('0');  
            $table->string('product_code')->default('');
            $table->float('weight')->default('0');
            $table->string('stone')->default('');
            $table->string('kt')->default('');
            $table->string('image')->default('');
            $table->integer('is_popular')->default('0');
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
        Schema::dropIfExists('products');
    }
}
