<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;
use Usuario as Usuario;
use CoreController as Core;
use App\Library\TokenManager;

require_once APP_PATH . '/library/Util/Util.php';
require_once APP_PATH . '/library/Util/TemplatesEmails.php';
require_once APP_PATH . '/library/CSRFToken/CSRFToken.php';

class UsuarioController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tokenManager = new TokenManager;
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
        $dados = filter_input_array(INPUT_GET);
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, Usuario, $dados);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";
        $usuarios = Usuario::find($parameters);
        $pessoas = Pessoa::find();
        $roles = PhalconRoles::find();
        $paginator = new Paginator([
            'data' => $usuarios,
            'limit'=> 500,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->pessoas = $pessoas;
        $this->view->roles = $roles;
    }

    public function formUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $dados = filter_input_array(INPUT_GET);
        $usuario = Usuario::findFirst("id={$dados["id_usuario"]}");
        $pessoa = Pessoa::findFirst("id={$usuario->id_pessoa}");
        $pessoaemail = PessoaEmail::findFirst("id_pessoa={$pessoa->id}");
        $dados = array(
            "id" => $usuario->id,
            "login" => $usuario->login,
            "email" => $pessoaemail->email,
            "perfil" => $usuario->roles_name,
            "nome" => $pessoa->nome,
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
        $dados = filter_input_array(INPUT_GET);
        $login = Usuario::findFirst("login='{$dados["login"]}'");
        if ($login) {
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            //Instanciar a resposta HTTP
            $response = new Response();
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
        $core = new Core;
        $template = new TemplatesEmails;
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
                $pessoa->nome = $params["nome_pessoa"];
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $pessoaemail = new PessoaEmail();
                $pessoaemail->setTransaction($transaction);
                $pessoaemail->id_pessoa = $pessoa->id;
                $pessoaemail->id_tipoemail = 43;
                $pessoaemail->principal = 1;
                $pessoaemail->email = $params["email"];
                $pessoaemail->ativo = 1;
                if ($pessoaemail->save() == false) {
                    $transaction->rollback("Não foi possível salvar o email!");
                }
                $usuario = new Usuario();
                $usuario->setTransaction($transaction);
                $usuario->id_pessoa = $pessoa->id;
                $usuario->roles_name = $params["roles_name"];
                $usuario->login = $params["login"];
                $usuario->senha = $this->security->hash($senha);
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
                $core->enviarEmailAction(1,$params["email"],$params["nome_pessoa"],null,"E-mail de Segurança",$html);
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
        $pessoa = Pessoa::findFirst("id={$usuario->id_pessoa}");
        $pessoaemail = PessoaEmail::findFirst("id_pessoa={$usuario->id_pessoa}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa->setTransaction($transaction);
                $pessoa->nome = $params["nome_pessoa"];
                $pessoa->update_at = date("Y-m-d H:i:s");
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $pessoaemail->setTransaction($transaction);
                $pessoaemail->email = $params["email"];
                if ($pessoaemail->save() == false) {
                    $transaction->rollback("Não foi possível salvar o email!");
                }
                $usuario->setTransaction($transaction);
                $usuario->roles_name = $params["roles_name"];
                $usuario->login = $params["login"];
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
        $core = new Core;
        $template = new TemplatesEmails;
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $dados = filter_input_array(INPUT_POST);
        $usuario = Usuario::findFirst("id={$dados["id"]}");
        $pessoa = Pessoa::findFirst("id={$usuario->id_pessoa}");
        $pessoaemail = PessoaEmail::findFirst("id_pessoa={$usuario->id_pessoa}");
        $senha = $this->gerarSenhaAction(6,true,true,false);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa->setTransaction($transaction);
                $pessoa->update_at = date("Y-m-d H:i:s");
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $usuario->setTransaction($transaction);
                $usuario->senha = $this->security->hash($senha);
                if ($usuario->save() == false) {
                    $transaction->rollback("Não foi possível salvar o usuário!");
                }
                //Commita a transação
                $transaction->commit();
                $html = $template->recuperaSenha($senha);
                $core->enviarEmailAction(1,$pessoaemail->email,$pessoa->nome,null,"E-mail de Segurança",$html);
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
        $core = new Core;
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $usuario = Usuario::findFirst("id={$dado}");
            $del = $core->ativarPessoaAction($usuario->id_pessoa);
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
        $core = new Core;
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $usuario = Usuario::findFirst("id={$dado}");
            $del = $core->inativarPessoaAction($usuario->id_pessoa);
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
        $core = new Core;
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $usuario = Usuario::findFirst("id={$dado}");
            $del = $core->deletarPessoaAction($usuario->id_pessoa);
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
}
