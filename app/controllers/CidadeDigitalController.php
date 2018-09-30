<?php

namespace Circuitos\Controllers;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\CidadeDigital;
use Circuitos\Models\Lov;
use Circuitos\Models\EndCidade;

use Auth\Autentica;
use Util\Util;
use Util\TokenManager;

class CidadeDigitalController extends ControllerBase
{
    public $tokenManager;

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
        $cidadedigital = CidadeDigital::find("excluido = 0");
        $cidades = EndCidade::find(array(
            "uf='PA'",
            "order" => "cidade"
        ));
        $tipocd = Lov::find(array(
            "tipo=18",
            "order" => "descricao"
        ));
        $paginator = new Paginator([
            'data' => $cidadedigital,
            'limit'=> 500,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->cidades = $cidades;
        $this->view->tipocd = $tipocd;
    }

    public function formCidadeDigitalAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $cidadedigital = CidadeDigital::findFirst("id={$dados["id_cidadedigital"]}");
        $dados = array(
            "id" => $cidadedigital->id,
            "id_tipo" => $cidadedigital->id_tipo,
            "id_cidade" => $cidadedigital->id_cidade,
            "descricao" => $cidadedigital->descricao,
            "endereco" => $cidadedigital->endereco
        );
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function criarCidadeDigitalAction()
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
                $cidadedigital = new CidadeDigital();
                $cidadedigital->setTransaction($transaction);
                $cidadedigital->id_tipo = $params["id_tipo"];
                $cidadedigital->id_cidade = $params["id_cidade"];
                $cidadedigital->descricao = $params["descricao"];
                $cidadedigital->endereco = $params["endereco"];
                if ($cidadedigital->save() == false) {
                    $transaction->rollback("Não foi possível salvar o cidadedigital!");
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

    public function editarCidadeDigitalAction()
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
        $cidadedigital = CidadeDigital::findFirst("id={$params["id"]}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $cidadedigital->setTransaction($transaction);
                $cidadedigital->id_tipo = $params["id_tipo"];
                $cidadedigital->id_cidade = $params["id_cidade"];
                $cidadedigital->descricao = $params["descricao"];
                $cidadedigital->endereco = $params["endereco"];
                if ($cidadedigital->save() == false) {
                    $transaction->rollback("Não foi possível editar o cidadedigital!");
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

    public function ativarCidadeDigitalAction()
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
                    $cidadedigital = CidadeDigital::findFirst("id={$dado}");
                    $cidadedigital->setTransaction($transaction);
                    $cidadedigital->ativo = 1;
                    if ($cidadedigital->save() == false) {
                        $transaction->rollback("Não foi possível editar o cidadedigital!");
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

    public function inativarCidadeDigitalAction()
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
                    $cidadedigital = CidadeDigital::findFirst("id={$dado}");
                    $cidadedigital->setTransaction($transaction);
                    $cidadedigital->ativo = 0;
                    if ($cidadedigital->save() == false) {
                        $transaction->rollback("Não foi possível editar o cidadedigital!");
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

    public function deletarCidadeDigitalAction()
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
                    $cidadedigital = CidadeDigital::findFirst("id={$dado}");
                    $cidadedigital->setTransaction($transaction);
                    $cidadedigital->excluido = 1;
                    if ($cidadedigital->save() == false) {
                        $transaction->rollback("Não foi possível editar o cidadedigital!");
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
}