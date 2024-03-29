<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m240328_205153_cliente
 */
class m240328_205153_cliente extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(){

        $this->createTable('cliente',[
            'id' => $this->primaryKey()->notNull(),
            'nome' => $this->string()->notNull(),
            'cpf' => $this->string(11)->notNull()->unique(),
            'cep' => $this->string(8)->defaultValue(NULL),
            'logradouro' => $this->string(500)->defaultValue(NULL),
            'numero' => $this->integer()->defaultValue(NULL),
            'cidade' => $this->string()->defaultValue(NULL),
            'estado' => $this->string(2)->defaultValue(NULL),
            'complemento' => $this->string()->defaultValue(NULL),
            'foto' => $this->string()->defaultValue(NULL),
            'sexo' => $this->char(1)->defaultValue(NULL),
            'data_criacao' => $this->dateTime()->notNull()->defaultValue(new Expression('NOW()'))
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        $this->dropTable('cliente');
    }

}
