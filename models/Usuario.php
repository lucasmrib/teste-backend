<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Usuario extends ActiveRecord implements IdentityInterface{

	public function rules(){
	    return [
	        [['nome', 'login', 'senha'], 'required']
	    ];
	}

    public static function tableName(){
        return 'usuario';
    }

	public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['token' => $token]);
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        return $this->token;
    }

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    public static function findByLogin($login){
        return static::findOne(['login' => $login]);
    }

    public function validatePassword($senha){
        return Yii::$app->security->validatePassword($senha, $this->senha);
    }

}