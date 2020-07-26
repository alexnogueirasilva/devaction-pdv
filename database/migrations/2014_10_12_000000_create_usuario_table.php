<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('login');
            $table->boolean('adm');
            $table->string('senha');
            $table->string('ativo');

            $table->boolean('acesso_cliente');
            $table->boolean('acesso_fornecedor');
            $table->boolean('acesso_produto');
            $table->boolean('acesso_financeiro');
            $table->boolean('acesso_caixa');
            $table->boolean('acesso_estoque');
            $table->boolean('acesso_compra');
            $table->boolean('acesso_fiscal');
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
        Schema::dropIfExists('usuarios');
    }
}
