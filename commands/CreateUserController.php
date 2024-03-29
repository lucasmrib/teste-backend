<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Usuario;

/**
 * This command creates a user. parameters: name, login, password.
 *
 */
class CreateUserController extends Controller
{
    public function actionIndex($nome, $login, $senha)
    {

        $user = new Usuario();
        $user->nome = $nome;
        $user->login = $login;
        $user->senha = password_hash($senha, PASSWORD_DEFAULT);
        $user->data_criacao = date('Y-m-d H:i:s');
        $user->save();

        return ExitCode::OK;
    }
}
