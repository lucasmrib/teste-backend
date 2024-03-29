<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Cliente extends ActiveRecord{

	public function rules(){
	    return [
	        [['nome', 'cpf', 'sexo'], 'required']
	    ];
	}

	public static function tableName(){
        return 'cliente';
    }

	public function getProduto(){
        return $this->hasMany(Produto::className(), ['id_cliente', 'id']);
    }
}