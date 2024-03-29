<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m240328_205211_produto
 */
class m240328_205211_produto extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(){

        $this->createTable('produto',[
            'id' => $this->primaryKey()->notNull(),
            'nome' => $this->string()->notNull(),
            'preco' => $this->decimal(20, 2)->notNull(),
            'id_cliente' => $this->integer(),
            'foto' => $this->string(),
            'data_criacao' => $this->dateTime()->notNull()->defaultValue(new Expression('NOW()'))
        ]);

        $this->addForeignKey(
            'produto_cliente',
            'produto',
            'id_cliente',
            'cliente',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        $this->dropTable('produto');
    }

}
