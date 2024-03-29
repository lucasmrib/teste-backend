<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m240328_205949_usuario
 */
class m240328_205949_usuario extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(){

        $this->createTable('usuario',[
            'id' => $this->primaryKey()->notNull(),
            'nome' => $this->string()->notNull(),
            'login' => $this->string()->notNull()->unique(),
            'senha' => $this->string()->notNull(),
            'token' => $this->string()->defaultValue(NULL),
            'data_criacao' => $this->dateTime()->notNull()->defaultValue(new Expression('NOW()'))
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        $this->dropTable('usuario');
    }

}
