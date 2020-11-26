<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoDeletesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_deletes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('produto', 100);
            $table->integer('pedido_id');
            $table->decimal('valor', 10, 2);
            $table->string('data_insercao', 20);
            $table->decimal('quantidade', 10, 2);
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
        Schema::dropIfExists('pedido_deletes');
    }
}
