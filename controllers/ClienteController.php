<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Cliente;
use app\models\Usuario;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;

class ClienteController extends Controller{

	public function beforeAction($action){

	    if (parent::beforeAction($action)) {

	        $authHeader = Yii::$app->request->headers->get('Authorization');

	        if (empty($authHeader)) {
                throw new \yii\web\UnauthorizedHttpException('Token de acesso não fornecido.');
            }

	        $token = str_replace('Bearer ', '', $authHeader);
	        
	        $usuario = Usuario::findIdentityByAccessToken($token);
	        if ($usuario === null) {
	            throw new \yii\web\UnauthorizedHttpException('Unauthorized');
	        }

	    	return true;
	    }
	    return false;
	}

	public function actionCriar(){

		Yii::$app->response->format = Response::FORMAT_JSON;

		$nome = Yii::$app->request->post('nome');
        $cpf = Yii::$app->request->post('cpf');
        $cep = Yii::$app->request->post('cep');
        $logradouro = Yii::$app->request->post('logradouro');
        $numero = Yii::$app->request->post('numero');
        $cidade = Yii::$app->request->post('cidade');
        $estado = Yii::$app->request->post('estado');
        $complemento = Yii::$app->request->post('complemento');
        $foto = Yii::$app->request->post('foto');
        $sexo = Yii::$app->request->post('sexo');

        if(!self::validarCpf($cpf)){
        	Yii::$app->response->statusCode = 400;
            return ['error' => 'CPF incorreto'];
        }

        try{
			$cliente = new Cliente();
			$cliente->nome = $nome;
	        $cliente->cpf = $cpf;
	        $cliente->cep = $cep;
	        $cliente->logradouro = $logradouro;
	        $cliente->numero = $numero;
	        $cliente->cidade = $cidade;
	        $cliente->estado = $estado;
	        $cliente->complemento = $complemento;
	        $cliente->foto = $foto;
	        $cliente->sexo = $sexo;
	        $cliente->save();

	        Yii::$app->response->statusCode = 200;
	        return ['message' => 'Cliente criado com sucesso.'];
	        
	    }catch(Exception $e){
	    	Yii::$app->response->statusCode = 500;
	    	Yii::error($e->getMessage());
            return ['error' => 'Ocorreu um erro ao criar o cliente.'];
	    }
	}

	public function actionListar($pagina = 1){

		Yii::$app->response->format = Response::FORMAT_JSON;

        // Configuração da paginação
        $pagination = new Pagination([
            'defaultPageSize' => 10, // Número de clientes por página
        ]);

        $pagination->setPage($pagina - 1);

        $query = Cliente::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => $pagination,
        ]);

        $clientes = $dataProvider->getModels();

        return ([
            'clientes' => $clientes,
            'paginaAtual' => $pagina,
            'totalPaginas' => $pagination->getPageCount(),
            'totalClientes' => $pagination->totalCount,
        ]);
    }

	protected function validarCpf($cpf){

	    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

	    if (strlen($cpf) != 11) {
	        return false;
	    }

	    if (preg_match('/(\d)\1{10}/', $cpf)) {
	        return false;
	    }

	    for ($t = 9; $t < 11; $t++) {
	        for ($d = 0, $c = 0; $c < $t; $c++) {
	            $d += $cpf[$c] * (($t + 1) - $c);
	        }
	        $d = ((10 * $d) % 11) % 10;
	        if ($cpf[$c] != $d) {
	            return false;
	        }
	    }
	    return true;
	}

}