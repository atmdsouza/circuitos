<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Circuitos\Controllers\CoreController as Core;
use Circuitos\Models\Empresa;
use Circuitos\Models\Operations\UsuarioOP;
use Circuitos\Models\Pessoa;
use Circuitos\Models\PessoaEmail;
use Circuitos\Models\PhalconRoles;
use Circuitos\Models\Usuario;
use Phalcon\Http\Response as Response;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Util\TemplatesEmails;
use Util\TokenManager;

class UsuarioController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Usuários");
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
        $this->persistent->parameters = null;
        $numberPage = 1;
        $dados = filter_input_array(INPUT_POST);
        $usuarios = Usuario::pesquisarUsuarios($dados["pesquisa"]);
        $roles = PhalconRoles::find("excluido=0");
        $paginator = new Paginator([
            'data' => $usuarios,
            'limit'=> 100,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->roles = $roles;
    }

    public function formUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $dados = filter_input_array(INPUT_GET);
        $usuario = Usuario::findFirst("id={$dados["id_usuario"]}");
        $pessoa = Pessoa::findFirst("id={$usuario->getIdPessoa()}");
        $pessoaemail = PessoaEmail::findFirst("id_pessoa={$pessoa->getId()}");
        $dados = array(
            "id" => $usuario->getId(),
            "login" => $usuario->getLogin(),
            "email" => $pessoaemail->getEmail(),
            "perfil" => $usuario->getRolesName(),
            "nome" => $pessoa->getNome(),
        );
        //Instanciar a resposta HTTP
        $response = new Response();
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function validarLoginAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $login = Usuario::findFirst("login='{$dados["login"]}'");
        if ($login) {
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False
            )));
            return $response;
        }
    }

    public function criarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $core = new Core();
        $template = new TemplatesEmails();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados["dados"], $params);
        $senha = $this->gerarSenhaAction(6,true,true,false);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa = new Pessoa();
                $pessoa->setTransaction($transaction);
                $pessoa->setNome(mb_strtoupper($params["nome_pessoa"], $this->encode));
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $pessoaemail = new PessoaEmail();
                $pessoaemail->setTransaction($transaction);
                $pessoaemail->setIdPessoa($pessoa->getId());
                $pessoaemail->setIdTipoemail(42);
                $pessoaemail->setPrincipal(1);
                $pessoaemail->setEmail($params["email"]);
                $pessoaemail->setAtivo(1);
                if ($pessoaemail->save() == false) {
                    $transaction->rollback("Não foi possível salvar o email!");
                }
                $usuario = new Usuario();
                $usuario->setTransaction($transaction);
                $usuario->setIdPessoa($pessoa->getId());
                $usuario->setRolesName($params["roles_name"]);
                $usuario->setLogin($params["login"]);
                $usuario->setSenha($this->security->hash($senha));
                if ($usuario->save() == false) {
                    $transaction->rollback("Não foi possível salvar o usuário!");
                }
                //Commita a transação
                $transaction->commit();
                //E-mail de cadastro do Usuário
                $infos = array(
                    "nome" => $params["nome_pessoa"],
                    "login" => $params["login"],
                    "senha" => $senha
                );
                $html = $template->novoUsuario($infos);
                $empresas = Empresa::find();
                foreach ($empresas as $key => $empresa)
                {
                    $id_empresa = $empresa->getId();
                }
                $core->enviarEmailAction($id_empresa,$params["email"],$params["nome_pessoa"],null,"E-mail de Segurança",$html);
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

    public function editarUsuarioAction()
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
        $usuario = Usuario::findFirst("id={$params["id"]}");
        $pessoa = Pessoa::findFirst("id={$usuario->getIdPessoa()}");
        $pessoaemail = PessoaEmail::findFirst("id_pessoa={$usuario->getIdPessoa()}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa->setTransaction($transaction);
                $pessoa->setNome(mb_strtoupper($params["nome_pessoa"], $this->encode));
                $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                if($params["email"] != $pessoaemail->getEmail()){
                    $pessoaemail->setTransaction($transaction);
                    $pessoaemail->setEmail($params["email"]);
                    if ($pessoaemail->save() == false) {
                        $transaction->rollback("Não foi possível salvar o email!");
                    }
                }
                $usuario->setTransaction($transaction);
                $usuario->setRolesName($params["roles_name"]);
                if($usuario->getLogin() != $params["login"]){
                    $usuario->setLogin($params["login"]);
                }
                if ($usuario->save() == false) {
                    $transaction->rollback("Não foi possível salvar o usuário!");
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

    /**
     * Função para gerar senhas aleatórias
     *
     * @author    Thiago Belem <contato@thiagobelem.net>
     *
     * @param integer $tamanho Tamanho da senha a ser gerada
     * @param boolean $maiusculas Se terá letras maiúsculas
     * @param boolean $numeros Se terá números
     * @param boolean $simbolos Se terá símbolos
     *
     * @return string A senha gerada
     */
    function gerarSenhaAction($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
    {
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
        $retorno = '';
        $caracteres = '';
        $caracteres .= $lmin;
        if ($maiusculas) $caracteres .= $lmai;
        if ($numeros) $caracteres .= $num;
        if ($simbolos) $caracteres .= $simb;
        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand-1];
        }
        return $retorno;
    }

    public function resetarSenhaAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $core = new Core();
        $template = new TemplatesEmails();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $dados = filter_input_array(INPUT_POST);
        $usuario = Usuario::findFirst("id={$dados["id"]}");
        $pessoa = Pessoa::findFirst("id={$usuario->getIdPessoa()}");
        $pessoaemail = PessoaEmail::findFirst("id_pessoa={$usuario->getIdPessoa()}");
        $senha = $this->gerarSenhaAction(6,true,true,false);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa->setTransaction($transaction);
                $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $usuario->setTransaction($transaction);
                $usuario->setSenha($this->security->hash($senha));
                $usuario->setPrimeiroacesso(0);
                if ($usuario->save() == false) {
                    $transaction->rollback("Não foi possível salvar o usuário!");
                }
                //Commita a transação
                $transaction->commit();
                $html = $template->recuperaSenha($senha);
                $empresas = Empresa::find();
                foreach ($empresas as $key => $empresa)
                {
                    $id_empresa = $empresa->getId();
                }
                $core->enviarEmailAction($id_empresa,$pessoaemail->getEmail(),$pessoa->getNome(),null,"E-mail de Segurança",$html);
                $response->setContent(json_encode(array(
                    "operacao" => True
                )));
                return $response;
            } catch (TxFailed $e) {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => "Erro ao tentar realizar o reset da senha!"
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

    public function ativarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $usuario = Usuario::findFirst("id={$dado}");
            $del = $core->ativarPessoaAction($usuario->getIdPessoa());
        }
        if($del){
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Erro ao tentar realizar a operação!"
            )));
            return $response;
        }
    }

    public function inativarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $usuario = Usuario::findFirst("id={$dado}");
            $del = $core->inativarPessoaAction($usuario->getIdPessoa());
        }
        if($del){
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Erro ao tentar realizar a operação!"
            )));
            return $response;
        }
    }

    public function deletarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $usuario = Usuario::findFirst("id={$dado}");
            $del = $core->deletarPessoaAction($usuario->getIdPessoa());
        }
        if($del){
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Erro ao tentar realizar a operação!"
            )));
            return $response;
        }
    }

    public function alterarSenhaAction($id_usuario = null, $senha = null)
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $usuario = Usuario::findFirst("id={$id_usuario}");
        $pessoa = Pessoa::findFirst("id={$usuario->getIdPessoa()}");
        try {
            $pessoa->setTransaction($transaction);
            $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            $usuario->setTransaction($transaction);
            $usuario->setSenha($this->security->hash($senha));
            if ($usuario->save() == false) {
                $transaction->rollback("Não foi possível salvar o usuário!");
            }
            //Commita a transação
            $transaction->commit();
            return true;
        } catch (TxFailed $e) {
            throw new Exception('Erro ao tentar alterar a senha!');
            return false;
        }

    }

    public function primeiroAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        //Pegar o login na session auth;
        $auth = new Autentica();
        $manager = new TxManager();
        $transaction = $manager->get();
        $identity = $auth->getIdentity();
        $user = Usuario::findFirst("id={$identity["id"]}");
        $pessoa = Pessoa::findFirst("id={$user->getIdPessoa()}");
        $this->view->nome = $user->getNomePessoaUsuario();
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                if (!$this->request->getPost('password') && !$this->request->getPost('password2')){
                    $this->flash->error("Os campos senha e confirmação de senha não podem ser vazios!");
                    $this->dispatcher->forward([
                        "controller" => "usuario",
                        "action" => "primeiro"
                    ]);
                } else if (!$this->request->getPost('password')) {
                    $this->flash->error("O campo senha não pode ser vazio!");
                    $this->dispatcher->forward([
                        "controller" => "usuario",
                        "action" => "primeiro"
                    ]);
                } else if (!$this->request->getPost('password2')) {
                    $this->flash->error("O campo confirmação de senha não pode ser vazio!");
                    $this->dispatcher->forward([
                        "controller" => "usuario",
                        "action" => "primeiro"
                    ]);
                } else {
                    if($this->request->getPost('password') === $this->request->getPost('password2')) {
                        try {
                            $pessoa->setTransaction($transaction);
                            $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
                            if ($pessoa->save() == false) {
                                $transaction->rollback("Não foi possível salvar a pessoa!");
                            }
                            $user->setTransaction($transaction);
                            $user->setSenha($this->security->hash($this->request->getPost('password')));
                            $user->setPrimeiroacesso(1);
                            if ($user->save() == false) {
                                $transaction->rollback("Não foi possível salvar o usuário!");
                            }
                            //Commita a transação
                            $transaction->commit();
                            return $this->response->redirect("index/index");
                        } catch (TxFailed $e) {
                            $this->flash->error("Erro ao tentar trocar a senha! Por favor, tente novamente!");
                            $this->dispatcher->forward([
                                "controller" => "usuario",
                                "action" => "primeiro"
                            ]);
                        }
                    } else {
                        $this->flash->error("As senhas não conferem! Por favor, tente novamente!");
                        $this->dispatcher->forward([
                            "controller" => "usuario",
                            "action" => "primeiro"
                        ]);
                    }
                }
            }
        }
    }

    public function redirecionaUsuarioAction($login)
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $usuario = Usuario::findFirst("login='{$login}'");
        $redirect = null;
        if ($usuario->Pessoa->ativo == 0) {
            $redirect = "session/inativo";
        } else {
            switch ($usuario->getPrimeiroacesso()) {
                case '1':
                    $redirect = "index";
                    break;
                case '0':
                    $redirect = "usuario/primeiro";
                    break;
            }
        }
        return $redirect;
    }

    public function recuperarSenhaAction($id_usuario)
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $core = new Core();
        $template = new TemplatesEmails();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $usuario = Usuario::findFirst("id={$id_usuario}");
        $pessoa = Pessoa::findFirst("id={$usuario->getIdPessoa()}");
        $pessoaemail = PessoaEmail::findFirst("id_pessoa={$usuario->getIdPessoa()}");
        $senha = $this->gerarSenhaAction(6,true,true,false);
        try {
            $pessoa->setTransaction($transaction);
            $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            $usuario->setTransaction($transaction);
            $usuario->setSenha($this->security->hash($senha));
            $usuario->setPrimeiroacesso(0);
            if ($usuario->save() == false) {
                $transaction->rollback("Não foi possível salvar o usuário!");
            }
            //Commita a transação
            $transaction->commit();
            $html = $template->recuperaSenha($senha);
            $empresas = Empresa::find();
            foreach ($empresas as $key => $empresa)
            {
                $id_empresa = $empresa->getId();
            }
            $email = $core->enviarEmailAction($id_empresa,$pessoaemail->getEmail(),$pessoa->getNome(),null,"E-mail de Segurança",$html);
            if ($email) {
                return True;
            } else {
                return False;
            }
        } catch (TxFailed $e) {
            return False;
        }
    }

    public function trocarAction()
    {
        //Pegar o login na session auth;
        $auth = new Autentica();
        $identity = $auth->getIdentity();
        $user = Usuario::findFirst("id={$identity["id"]}");
        $this->view->nome = $user->Pessoa->nome;
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                if (!$this->request->getPost('password') && !$this->request->getPost('password2')){
                    $this->flash->error("Os campos senha e confirmação de senha não podem ser vazios!");
                    $this->dispatcher->forward([
                        "controller" => "usuario",
                        "action" => "trocar"
                    ]);
                } else if (!$this->request->getPost('password')) {
                    $this->flash->error("O campo senha não pode ser vazio!");
                    $this->dispatcher->forward([
                        "controller" => "usuario",
                        "action" => "trocar"
                    ]);
                } else if (!$this->request->getPost('password2')) {
                    $this->flash->error("O campo confirmação de senha não pode ser vazio!");
                    $this->dispatcher->forward([
                        "controller" => "usuario",
                        "action" => "trocar"
                    ]);
                } else {
                    if($this->request->getPost('password') === $this->request->getPost('password2')) {
                        $this->alterarSenhaAction($identity["id"], $this->request->getPost('password'));
                        return $this->response->redirect("index/index");
                    } else {
                        $this->flash->error("As senhas não conferem! Por favor, tente novamente!");
                        $this->dispatcher->forward([
                            "controller" => "usuario",
                            "action" => "trocar"
                        ]);
                    }
                }
            }
        }
    }
}
