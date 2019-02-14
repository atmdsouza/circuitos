<?php

namespace Circuitos\Controllers;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\PhalconRoles;
use Circuitos\Models\PhalconResources;
use Circuitos\Models\PhalconAccessList;

use Auth\Autentica;
use Util\TokenManager;

class ControleAcessoController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Perfis e Acessos");
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

    public function indexAction()
    {
        $numberPage = 1;
        $dados = filter_input_array(INPUT_POST);

        $controleacesso = PhalconRoles::pesquisarControleAcesso($dados["pesquisa"]);

        $modulos = PhalconResources::find(array("order" => "name"));

        $paginator = new Paginator([
            'data' => $controleacesso,
            'limit'=> 100,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->modulos = $modulos;

    }

    public function formControleAcessoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $controleacesso = PhalconRoles::findFirst("id={$dados["name"]}");
        $dados = array(
            "id" => $controleacesso->getId(),
            "id_cidade" => $controleacesso->getIdCidade(),
            "cidade" => $controleacesso->EndCidade->Cidade,
            "descricao" => $controleacesso->getDescricao()
        );
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function criarControleAcessoAction()
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
                $controleacesso = new PhalconRoles();
                $controleacesso->setTransaction($transaction);
                $controleacesso->setName($params["nome_perfil"]);
                $controleacesso->setDescription($params["descricao_perfil"]);
                $controleacesso->setExcluido(0);
                if ($controleacesso->save() == false) {
                    $messages = $controleacesso->getMessages();
                    $errors = "";
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= "[".$messages[$i]."] ";
                    }
                    $transaction->rollback("Erro ao criar o perfil: " . $errors);
                }
                //Commita a transação
                $transaction->commit();
                //Gravar Permissões Padrão
                $this->permissoesPadraoAction($controleacesso->getName());
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

    public function editarControleAcessoAction()
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
        $controleacesso = PhalconRoles::findFirst("id={$params["id"]}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $controleacesso->setTransaction($transaction);
                $controleacesso->setIdCidade($params["id_cidade"]);
                $controleacesso->setDescricao(mb_strtoupper($params["descricao"], $this->encode));
                if ($controleacesso->save() == false) {
                    $messages = $controleacesso->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback('Erro ao criar a cidade digital: ' . $errors);
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

    public function deletarControleAcessoAction()
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
                    $controleacesso = PhalconRoles::findFirst("id={$dado}");
                    $controleacesso->setTransaction($transaction);
                    $controleacesso->setExcluido(1);
                    if ($controleacesso->save() == false) {
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

    private function permissoesPadraoAction($roles)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        $permissoes = array(
            "session"           => ["login", "logout", "sair", "recuperar", "inativo"],
            "usuario"           => ["gerarSenha", "resetarSenha", "alterarSenha", "primeiro", "redirecionaUsuario", "recuperarSenha", "trocar"],
            "core"              => ["enviarEmail", "listaCidades", "listaEstados"],
            "error"             => ["show401", "show404"],
            "index"             => ["index"],
//            "relatorios_gestao" => ["index", "relatorioCustomizado"],
//            "cidade_digital"    => ["index", "formCidadeDigital"],
//            "circuitos"         => ["index", "formCircuitos", "visualizaCircuitos", "pdfCircuito"],
//            "cliente"           => ["index", "formCliente"],
//            "cliente_unidade"   => ["index", "formClienteUnidade"],
//            "equipamento"       => ["index", "formEquipamento"],
//            "fabricante"        => ["index", "formFabricante"],
//            "index"             => ["index", "circuitoStatusMes", "circuitoStatus", "circuitoLinkMes", "circuitoLink", "circuitoHotzoneCidade", "circuitoFuncao", "circuitoEsfera", "circuitoConectividade", "circuitoAcesso", "cidadedigitalStatus"],
//            "modelo"            => ["index", "formModelo"],
        );
        //Permissões Padrão
        foreach($permissoes as $permissao)
        {
            //session
            foreach($permissoes["session"] as $session)
            {
                $permissoespadrao = new PhalconAccessList();
                $permissoespadrao->setTransaction($transaction);
                $permissoespadrao->setAccessName($session);
                $permissoespadrao->setResourcesName("session");
                $permissoespadrao->setRolesName($roles);
                $permissoespadrao->setAllowed(0);
                if ($permissoespadrao->save() == false) {
                    $messages = $permissoespadrao->getMessages();
                    $errors = "";
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= "[".$messages[$i]."] ";
                    }
                    $transaction->rollback("Erro ao criar a permissão: " . $errors);
                }
            }
            //usuario
            foreach($permissoes["usuario"] as $session)
            {
                $permissoespadrao = new PhalconAccessList();
                $permissoespadrao->setTransaction($transaction);
                $permissoespadrao->setAccessName($session);
                $permissoespadrao->setResourcesName("usuario");
                $permissoespadrao->setRolesName($roles);
                $permissoespadrao->setAllowed(0);
                if ($permissoespadrao->save() == false) {
                    $messages = $permissoespadrao->getMessages();
                    $errors = "";
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= "[".$messages[$i]."] ";
                    }
                    $transaction->rollback("Erro ao criar a permissão: " . $errors);
                }
            }
            //error
            foreach($permissoes["error"] as $session)
            {
                $permissoespadrao = new PhalconAccessList();
                $permissoespadrao->setTransaction($transaction);
                $permissoespadrao->setAccessName($session);
                $permissoespadrao->setResourcesName("error");
                $permissoespadrao->setRolesName($roles);
                $permissoespadrao->setAllowed(0);
                if ($permissoespadrao->save() == false) {
                    $messages = $permissoespadrao->getMessages();
                    $errors = "";
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= "[".$messages[$i]."] ";
                    }
                    $transaction->rollback("Erro ao criar a permissão: " . $errors);
                }
            }
            //core
            foreach($permissoes["core"] as $session)
            {
                $permissoespadrao = new PhalconAccessList();
                $permissoespadrao->setTransaction($transaction);
                $permissoespadrao->setAccessName($session);
                $permissoespadrao->setResourcesName("core");
                $permissoespadrao->setRolesName($roles);
                $permissoespadrao->setAllowed(0);
                if ($permissoespadrao->save() == false) {
                    $messages = $permissoespadrao->getMessages();
                    $errors = "";
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= "[".$messages[$i]."] ";
                    }
                    $transaction->rollback("Erro ao criar a permissão: " . $errors);
                }
            }
            //index
            foreach($permissoes["index"] as $session)
            {
                $permissoespadrao = new PhalconAccessList();
                $permissoespadrao->setTransaction($transaction);
                $permissoespadrao->setAccessName($session);
                $permissoespadrao->setResourcesName("index");
                $permissoespadrao->setRolesName($roles);
                $permissoespadrao->setAllowed(0);
                if ($permissoespadrao->save() == false) {
                    $messages = $permissoespadrao->getMessages();
                    $errors = "";
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= "[".$messages[$i]."] ";
                    }
                    $transaction->rollback("Erro ao criar a permissão: " . $errors);
                }
            }
        }
        //Commita a transação
        $transaction->commit();
        return true;
    }

}

