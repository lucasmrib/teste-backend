<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Produto extends ActiveRecord{

	public function rules(){
	    return [
	        [['nome', 'preco'], 'required'],
	    ];
	}

	public static function tableName(){
        return 'produto';
    }

	public function getCliente(){
        return $this->hasOne(Cliente::className(), ['id' => 'id_cliente']);
    }
}