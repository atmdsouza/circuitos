<?php

namespace Circuitos\Controllers;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Util;
use Util\TemplatesEmails;
use Util\TokenManager;

class LovController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Lista de Valores");
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
        $lov = Lov::find(array(
            "codigoespecifico <> 'SYS' OR codigoespecifico IS NULL",
            "order" => "[id] DESC"
        ));
        $tipos_lov = [
            "1" => "Tipo Unidade",
            "2" => "Usa Contrato",
            "3" => "Função",
            "4" => "Esfera",
            "5" => "Setor",
            "6" => "Status",
            "7" => "Enlace",
            "8" => "Tipo Email",
            "9" => "Tipo Cliente (PF/PJ)",
            "10" => "Sexo",
            "11" => "Tipo Endereço",
            "12" => "Tipo Telefone",
            "13" => "Tipo Contato (Cargo Empresa)",
            "14" => "Cluster",
            "15" => "Tipo Equipamento",
            "16" => "Tipo Movimento",
            "17" => "Tipo Banda",
            "18" => "Tipo Cidade Digital",
            "19" => "Tipo Link"
        ];
        $paginator = new Paginator([
            'data' => $lov,
            'limit'=> 100,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->tipos_lov = $tipos_lov;
    }

    public function formLovAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $lov = Lov::findFirst("id={$dados["id_lov"]}");
        $dados = array(
            "id" => $lov->getId(),
            "tipo" => $lov->getTipo(),
            "descricao" => $lov->getDescricao(),
            "codigoespecifico" => $lov->getCodigoespecifico(),
            "valor" => $lov->getValor(),
            "duracao" => $lov->getDuracao()
        );
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;

    }

    public function criarLovAction()
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
                $lov = new Lov();
                $lov->setTransaction($transaction);
                $lov->setTipo($params["tipo"]);
                $lov->setDescricao(mb_strtoupper($params["descricao"], $this->encode));
                $lov->setCodigoespecifico(mb_strtoupper($params["codigoespecifico"], $this->encode));
                $lov->setValor(mb_strtoupper($params["valor"], $this->encode));
                $lov->setDuracao($params["duracao"]);
                if ($lov->save() == false) {
                    $transaction->rollback("Não foi possível salvar o registro!");
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
                    "mensagem" => "Erro ao tentar realizar o cadastro!"
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

    public function editarLovAction()
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
        $lov = Lov::findFirst("id={$params["id"]}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $lov->setTransaction($transaction);
                $lov->setTipo($params["tipo"]);
                $lov->setDescricao(mb_strtoupper($params["descricao"], $this->encode));
                $lov->setCodigoespecifico(mb_strtoupper($params["codigoespecifico"], $this->encode));
                $lov->setValor(mb_strtoupper($params["valor"], $this->encode));
                $lov->setDuracao($params["duracao"]);
                if ($lov->save() == false) {
                    $transaction->rollback("Não foi possível salvar o registro!");
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
                    "mensagem" => "Erro ao tentar realizar a edição!"
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

    public function deletarLovAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        foreach($dados["ids"] as $dado){
            $lov = Lov::findFirstByid($dado);
            $result = $lov->delete();
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
