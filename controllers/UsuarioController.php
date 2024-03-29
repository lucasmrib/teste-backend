<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Usuario;

class UsuarioController extends Controller{

	public function actionLogin(){

		Yii::$app->response->format = Response::FORMAT_JSON;
        $login = Yii::$app->request->post('login');
        $senha = Yii::$app->request->post('senha');

        $usuario = Usuario::findByLogin($login);

        if ($usuario !== null && $usuario->validatePassword($senha)) {
            $token = $this->generateToken();
            $usuario->token = $token;
            $usuario->save();

            return ['token' => $token];
        } else {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Unauthorized'];
        }
    }

    protected function generateToken()
    {
        return Yii::$app->security->generateRandomString();
    }
}