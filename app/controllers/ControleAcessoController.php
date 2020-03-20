<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Circuitos\Models\PhalconAccessList;
use Circuitos\Models\PhalconResources;
use Circuitos\Models\PhalconRoles;
use Phalcon\Http\Response as Response;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Paginator\Adapter\Model as Paginator;
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
                $controleacesso->setAtivo(1);
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
                foreach($dados['ids'] as $dado){
                    $controleacesso = PhalconRoles::findFirst('name="'.$dado.'"');
                    $permissoes = PhalconAccessList::find('roles_name="'.$controleacesso->getName().'"');
                    foreach ($permissoes as $permissao)
                    {
                        $permissao->setTransaction($transaction);
                        if ($permissao->delete() == false) {
                            $transaction->rollback('Não foi possível excluir a permissão!');
                        }
                    }
                    $controleacesso->setTransaction($transaction);
                    if ($controleacesso->delete() == false) {
                        $transaction->rollback('Não foi possível excluir o perfil!');
                    }
                }
                //Commita a transação
                $transaction->commit();
                $response->setContent(json_encode(array('operacao' => True)));
            } catch (TxFailed $e) {
                $response->setContent(json_encode(array('operacao' => False, 'mensagem' => $e->getMessage())));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'mensagem' => 'Check de formulário inválido!')));
        }
        return $response;
    }

    private function permissoesPadraoAction($roles)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        $permissoes = array(
            "session"                                       => ["login", "logout", "sair", "recuperar", "inativo"],
            "usuario"                                       => ["gerarSenha", "resetarSenha", "alterarSenha", "primeiro", "redirecionaUsuario", "recuperarSenha", "trocar", "validarLogin", "formUsuario"],
            "core"                                          => ["ativarPessoa", "inativarPessoa", "deletarPessoa", "deletarPessoaEndereco", "deletarPessoaEmail", "deletarPessoaContato", "deletarPessoaTelefone", "validarEmail", "validarCNPJ", "validarCPF", "completaEndereco", "enviarEmail", "upload", "processarAjaxAcao", "processarAjaxVisualizar", "processarAjaxAutocomplete", "processarAjaxSelect"],
            "error"                                         => ["show401", "show404"],
            "index"                                         => ["index"],
            "cidade_digital"                                => ["formCidadeDigital"],
            "circuitos"                                     => ["equipamentoSeriePatrimonio", "formCircuitos", "clienteAll", "getCidadeDigitalCircuito", "getClienteCircuito", "fabricanteAll", "cidadedigitalAll", "cidadedigitalConectividade", "equipamentoModelo", "equipamentoNumeroSerie", "modeloFabricante", "unidadeCliente", "validarEquipamentoCircuito", "visualizaCircuitos"],
            "cliente"                                       => ["formCliente"],
            "cliente_unidade"                               => ["formClienteUnidade"],
            "empresa"                                       => ["formEmpresa"],
            "lov"                                           => ["formLov"],
            "controle_acesso"                               => ["buscarPermissoes"],
            "equipamento"                                   => ["formEquipamento", "carregaModelos", "validaNumeroPatrimonio", "validaNumeroSerie"],
            "fabricante"                                    => ["formFabricante"],
            "modelo"                                        => ["formModelo"]
        );
        $modulos = ["session", "usuario", "core", "error", "index", "cidade_digital", "circuitos", "cliente", "cliente_unidade", "empresa", "lov", "controle_acesso", "equipamento", "fabricante", "modelo",
                    "conectividade", "contrato", "estacao_telecon", "proposta_comercial", "proposta_comercial_servico", "proposta_comercial_servico_grupo", "proposta_comercial_servico_unidade", "set_seguranca",
                    "set_equipamento", "terreno", "torre", "unidade_consumidora"];
        //Permissões Padrão
        foreach ($modulos as $modulo)
        {
            foreach($permissoes[$modulo] as $session)
            {
                $permissoespadrao = new PhalconAccessList();
                $permissoespadrao->setTransaction($transaction);
                $permissoespadrao->setAccessName($session);
                $permissoespadrao->setResourcesName($modulo);
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

    public function adicionarPermissaoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $manager = new TxManager();
        $dados = filter_input_array(INPUT_POST);
        foreach ($dados["arrayRoles"] as $key => $role)
        {
            $valida_controleacesso = PhalconAccessList::findFirst([
                "conditions" => "access_name = ?1 AND roles_name = ?2 AND resources_name = ?3",
                'bind'       => [
                    1 => $dados["arrayAccessNames"][$key],
                    2 => $role,
                    3 => $dados["arrayResources"][$key]
                ]
            ]);
            //CSRF Token Check
            if (!$valida_controleacesso)
            {
                if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                    try {
                        $transaction = $manager->get();
                        $controleacesso = new PhalconAccessList();
                        $controleacesso->setTransaction($transaction);
                        $controleacesso->setRolesName($role);
                        $controleacesso->setResourcesName($dados["arrayResources"][$key]);
                        $controleacesso->setAccessName($dados["arrayAccessNames"][$key]);
                        $controleacesso->setAllowed(0);
                        if ($controleacesso->save() == false) {
                            $transaction->rollback("Não foi possível cadastrar as permissões!");
                        }
                        //Commita a transação
                        $transaction->commit();
                        $response->setContent(json_encode(array(
                            "operacao" => True
                        )));
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
        return $response;
    }

    public function removerPermissaoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $manager = new TxManager();
        $dados = filter_input_array(INPUT_POST);
        foreach ($dados["arrayRoles"] as $key => $role)
        {
            //CSRF Token Check
            if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                try {
                    $controleacesso = PhalconAccessList::findFirst([
                        "conditions" => "access_name = ?1 AND roles_name = ?2 AND resources_name = ?3",
                        'bind' => [
                            1 => $dados["arrayAccessNames"][$key],
                            2 => $role,
                            3 => $dados["arrayResources"][$key]
                        ]
                    ]);
                    $transaction = $manager->get();
                    if ($controleacesso->delete() == false) {
                        $transaction->rollback("Não foi possível deletar a pessoa!");
                    }
                    $transaction->commit();
                    $response->setContent(json_encode(array(
                        "operacao" => True
                    )));
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
        return $response;
    }

    public function buscarPermissoesAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $controleacesso = PhalconAccessList::find([
            "conditions" => "roles_name = ?1",
            'bind'       => [
                1 => $dados["role"]
            ]
        ]);
        $response->setContent(json_encode(array(
            "operacao" => True,
            "controleacesso" => $controleacesso
        )));
        return $response;
    }

}

