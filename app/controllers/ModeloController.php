<?php

namespace Circuitos\Controllers;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\Modelo;
use Circuitos\Models\Fabricante;

use Auth\Autentica;
use Util\Util;
use Util\TokenManager;

class ModeloController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        //Voltando o usuário não autenticado para a página de login
        $auth = new Autentica();
        $identity = $auth->getIdentity();
        if (!is_array($identity)) {
            return $this->response->redirect('session/login');
        }
        $this->view->user = $identity["nome"];
        //CSRFToken
        $this->tokenManager = new TokenManager();
        if (!$this->tokenManager->doesUserHaveToken('User')) {
            $this->tokenManager->generateToken('User');
        }
        $this->view->token = $this->tokenManager->getToken('User');
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $numberPage = 1;
        $modelo = Modelo::find(array(
            "order" => "[id] DESC"
        ));
        $fabricante = Fabricante::buscaCompletaFabricante();
        $paginator = new Paginator([
            'data' => $modelo,
            'limit'=> 10000,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->fabricante = $fabricante;
    }

    public function formModeloAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $modelo = Modelo::findFirst("id={$dados["id_modelo"]}");
        $dados = array(
            "id" => $modelo->getId(),
            "id_fabricante" => $modelo->getIdFabricante(),
            "modelo" => $modelo->getModelo(),
            "descricao" => $modelo->getDescricao(),
        );
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function criarModeloAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados["dados"], $params);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $modelo = new Modelo();
                $modelo->setTransaction($transaction);
                $modelo->setIdFabricante($params["id_fabricante"]);
                $modelo->setModelo(mb_strtoupper($params["modelo"], $this->encode));
                $modelo->setDescricao(mb_strtoupper($params["descricao"], $this->encode));
                if ($modelo->save() == false) {
                    $transaction->rollback("Não foi possível salvar o modelo!");
                }
                //Commita a transação
                $transaction->commit();
                $response->setContent(json_encode(array(
                    "operacao" => True
                )));
                return $response;
            } catch (TxFailed $e) {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => $e->getMessage()
                )));
                return $response;
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Check de formulário inválido!"
            )));
            return $response;
        }
    }

    public function editarModeloAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados["dados"], $params);
        $modelo = Modelo::findFirst("id={$params["id"]}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $modelo->setTransaction($transaction);
                $modelo->setIdFabricante($params["id_fabricante"]);
                $modelo->setModelo(mb_strtoupper($params["modelo"], $this->encode));
                $modelo->setDescricao(mb_strtoupper($params["descricao"], $this->encode));
                if ($modelo->save() == false) {
                    $transaction->rollback("Não foi possível editar o modelo!");
                }
                //Commita a transação
                $transaction->commit();
                $response->setContent(json_encode(array(
                    "operacao" => True
                )));
                return $response;
            } catch (TxFailed $e) {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => $e->getMessage()
                )));
                return $response;
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Check de formulário inválido!"
            )));
            return $response;
        }
    }

    public function ativarModeloAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $dados = filter_input_array(INPUT_POST);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                foreach($dados["ids"] as $dado){
                    $modelo = Modelo::findFirst("id={$dado}");
                    $modelo->setTransaction($transaction);
                    $modelo->setAtivo(1);
                    if ($modelo->save() == false) {
                        $transaction->rollback("Não foi possível editar o modelo!");
                    }
                }
                //Commita a transação
                $transaction->commit();
                $response->setContent(json_encode(array(
                    "operacao" => True
                )));
                return $response;
            } catch (TxFailed $e) {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => $e->getMessage()
                )));
                return $response;
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Check de formulário inválido!"
            )));
            return $response;
        }
    }

    public function inativarModeloAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $dados = filter_input_array(INPUT_POST);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                foreach($dados["ids"] as $dado){
                    $modelo = Modelo::findFirst("id={$dado}");
                    $modelo->setTransaction($transaction);
                    $modelo->setAtivo(0);
                    if ($modelo->save() == false) {
                        $transaction->rollback("Não foi possível editar o modelo!");
                    }
                }
                //Commita a transação
                $transaction->commit();
                $response->setContent(json_encode(array(
                    "operacao" => True
                )));
                return $response;
            } catch (TxFailed $e) {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => $e->getMessage()
                )));
                return $response;
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Check de formulário inválido!"
            )));
            return $response;
        }
    }

    public function deletarModeloAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        foreach($dados["ids"] as $dado){
            $modelo = Modelo::findFirstByid($dado);
            $result = $modelo->delete();
            if (!$result) {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => "Erro ao tentar realizar a operação!"
                )));
                return $response;
            }
        }
        $response->setContent(json_encode(array(
            "operacao" => True
        )));
        return $response;
    }
}
