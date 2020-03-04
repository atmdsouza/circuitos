<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Circuitos\Models\Lov;
use Phalcon\Http\Response as Response;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Paginator\Adapter\Model as Paginator;
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
        $dados = filter_input_array(INPUT_POST);

        $lov = Lov::pesquisarLovs($dados["pesquisa"]);

        $tipos_lov = [
            "1" => "Tipo de Unidade",
            "2" => "Usa Contrato",
            "3" => "Função",
            "4" => "Esfera",
            "5" => "Setor",
            "6" => "Status",
            "7" => "Enlace",
            "8" => "Tipo de Email",
            "9" => "Tipo de Cliente (PF/PJ)",
            "10" => "Sexo",
            "11" => "Tipo de Endereço",
            "12" => "Tipo de Telefone",
            "13" => "Tipo de Contato (Cargo Empresa)",
            "14" => "Cluster",
            "15" => "Tipo de Equipamento",
            "17" => "Tipo de Banda",
            "18" => "Tipo de Conectividade",
            "19" => "Tipo de Link",
            "20" => "Tipo de Anexo",
            "21" => "Tipo Componente de Set de Segurança",
            "22" => "Tipo de Torre",
            "23" => "Tipo de Proposta",
            "26" => "Tipo de Contrato",
            "27" => "Tipo de Processo de Contratação",
            "29" => "Tipo de Modalidade de Contratação de Garantia",
            "31" => "Tipo de Fiscal de Contrato",
            "32" => "Tipo de Estação Telecom"
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
