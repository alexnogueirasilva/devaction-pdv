<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_notas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('razao_social', 100);
            $table->string('nome_fantasia', 80);
            $table->string('cnpj', 19);
            $table->string('ie', 20);
            $table->string('logradouro', 80);

            $table->string('numero', 10);
            $table->string('bairro', 50);
            $table->string('fone', 20);
            $table->string('cep', 10);
            $table->string('pais', 20);
            $table->string('municipio', 30);
            $table->integer('codPais');
            $table->integer('codMun');
            $table->char('UF', 2);

            $table->string('CST_CSOSN_padrao', 3);
            $table->string('CST_COFINS_padrao', 3);
            $table->string('CST_PIS_padrao', 3);
            $table->string('CST_IPI_padrao', 3);
            $table->integer('frete_padrao');
            $table->string('tipo_pagamento_padrao', 2);
            $table->integer('nat_op_padrao');
            $table->integer('ambiente');
            $table->string('cUF', 2);
            $table->string('numero_serie_nfe', 3);
            $table->string('numero_serie_nfce', 3);

            $table->integer('ultimo_numero_nfe');
            $table->integer('ultimo_numero_nfce');
            $table->integer('ultimo_numero_cte');
            $table->integer('ultimo_numero_mdfe');

            
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
        Schema::dropIfExists('config_notas');
    }
}
