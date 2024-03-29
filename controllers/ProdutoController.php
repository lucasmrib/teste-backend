<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Produto;
use app\models\Cliente;
use app\models\Usuario;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class ProdutoController extends Controller{

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
        $preco = Yii::$app->request->post('preco');
        $id_cliente = Yii::$app->request->post('id_cliente');
        $foto = Yii::$app->request->post('foto');

        try{
			$produto = new Produto();
			$produto->nome = $nome;
			$produto->preco = $preco;
	        $produto->id_cliente = $id_cliente;
	        $produto->foto = $foto;
	        $produto->save();

	        Yii::$app->response->statusCode = 200;
	        return ['message' => 'Produto criado com sucesso.'];

	    }catch(Exception $e){
	    	Yii::$app->response->statusCode = 500;
	    	Yii::error($e->getMessage());
            return ['error' => 'Ocorreu um erro ao criar o produto.'];
	    }
	}

	public function actionListar($filtro = null, $pagina = 1){

		Yii::$app->response->format = Response::FORMAT_JSON;

        // Configuração da paginação
        $pagination = new Pagination([
            'defaultPageSize' => 10, // Número de produtos por página
        ]);

        $pagination->setPage($pagina - 1);

        $query = Produto::find()->select(['`cliente`.`nome` AS cliente', 'produto.*'])->leftJoin('cliente', 'produto.id_cliente = cliente.id')->asArray();

        if ($filtro !== null) {
            $query->andWhere(['cliente.nome' => $filtro]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => $pagination,
        ]);

        $produtos = $dataProvider->getModels();

        return [
            'produtos' => $produtos,
            'paginaAtual' => $pagina,
            'totalPaginas' => $pagination->getPageCount(),
            'totalProdutos' => $pagination->totalCount,
        ];
    }

}