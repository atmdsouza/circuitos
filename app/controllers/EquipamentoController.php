<?php

namespace Circuitos\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\Equipamento;
use Circuitos\Models\Modelo;
use Circuitos\Models\Fabricante;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\TokenManager;

class EquipamentoController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Equipamento");
        parent::initialize();
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
        $dados = filter_input_array(INPUT_POST);
        $equipamento = Equipamento::pesquisarEquipamentos($dados["pesquisa"]);
        $tipoequipamento = Lov::find(array(
            "tipo = 15",
            "order" => "descricao"
        ));
        $fabricante = Fabricante::buscaCompletaFabricante();
        $paginator = new Paginator([
            'data' => $equipamento,
            'limit'=> 100,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->fabricante = $fabricante;
        $this->view->tipoequipamento = $tipoequipamento;
    }

    public function formEquipamentoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $equipamento = Equipamento::findFirst("id={$dados["id_equipamento"]}");
        $dados = array(
            "id" => $equipamento->getId(),
            "id_fabricante" => $equipamento->getIdFabricante(),
            "id_modelo" => $equipamento->getIdModelo(),
            "nome" => $equipamento->getNome(),
            "id_tipoequipamento" => $equipamento->getIdTipoequipamento(),
            "numserie" => $equipamento->getNumserie(),
            "numpatrimonio" => $equipamento->getNumpatrimonio(),
            "descricao" => $equipamento->getDescricao(),
        );
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function criarEquipamentoAction()
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
                $equipamento = new Equipamento();
                $equipamento->setTransaction($transaction);
                $equipamento->setIdFabricante($params["id_fabricante"]);
                $equipamento->setIdModelo($params["id_modelo"]);
                $equipamento->setNome(mb_strtoupper($params["nome"], $this->encode));
                $equipamento->setDescricao(mb_strtoupper($params["descricao"], $this->encode));
                $equipamento->setIdTipoequipamento($params["id_tipoequipamento"]);
                $equipamento->setNumserie(mb_strtoupper($params["numserie"], $this->encode));
                $equipamento->setNumpatrimonio(mb_strtoupper($params["numpatrimonio"], $this->encode));
                if ($equipamento->save() == false) {
                    $transaction->rollback("Não foi possível salvar o equipamento!");
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

    public function editarEquipamentoAction()
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
        $equipamento = Equipamento::findFirst("id={$params["id"]}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $equipamento->setTransaction($transaction);
                $equipamento->setIdFabricante($params["id_fabricante"]);
                $equipamento->setIdModelo($params["id_modelo"]);
                $equipamento->setNome(mb_strtoupper($params["nome"], $this->encode));
                $equipamento->setDescricao(mb_strtoupper($params["descricao"], $this->encode));
                $equipamento->setIdTipoequipamento($params["id_tipoequipamento"]);
                $equipamento->setNumserie(mb_strtoupper($params["numserie"], $this->encode));
                $equipamento->setNumpatrimonio(mb_strtoupper($params["numpatrimonio"], $this->encode));
                if ($equipamento->save() == false) {
                    $transaction->rollback("Não foi possível editar o equipamento!");
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

    public function ativarEquipamentoAction()
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
                    $equipamento = Equipamento::findFirst("id={$dado}");
                    $equipamento->setTransaction($transaction);
                    $equipamento->setAtivo(1);
                    if ($equipamento->save() == false) {
                        $transaction->rollback("Não foi possível editar o equipamento!");
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

    public function inativarEquipamentoAction()
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
                    $equipamento = Equipamento::findFirst("id={$dado}");
                    $equipamento->setTransaction($transaction);
                    $equipamento->setAtivo(0);
                    if ($equipamento->save() == false) {
                        $transaction->rollback("Não foi possível editar o equipamento!");
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

    public function deletarEquipamentoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        foreach($dados["ids"] as $dado){
            $equipamento = Equipamento::findFirstByid($dado);
            $result = $equipamento->delete();
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

    public function carregaModelosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if ($dados["id_fabricante"]) {
            $modelos = Modelo::find("id_fabricante={$dados["id_fabricante"]}");
            if (isset($modelos[0])) {
                $response->setContent(json_encode(array(
                    "operacao" => True,
                    "dados" => $modelos
                )));
                return $response;
            } else {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => "Não existem modelos cadastrados para esse fabricante!"
                )));
                return $response;
            }
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Você precisa selecionar um fabricante!"
            )));
            return $response;
        }
    }
}
