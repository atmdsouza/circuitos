<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;
use Usuario as Usuario;
use CoreController as Core;

require_once APP_PATH . '/library/util/Util.php';

class UsuarioController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $dados = filter_input_array(INPUT_POST);
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
        $dados = filter_input_array(INPUT_POST);
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
        $dados = filter_input_array(INPUT_POST);
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
        try {
            $dados = filter_input_array(INPUT_POST);
            //Create a transaction manager
            $manager = new TxManager();
            //Request a transaction
            $transaction = $manager->get();
            $pessoa = new Pessoa();
            $pessoa->setTransaction($transaction);
            $pessoa->nome = $dados["nome_pessoa"];
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            $pessoaemail = new PessoaEmail();
            $pessoaemail->setTransaction($transaction);
            $pessoaemail->id_pessoa = $pessoa->id;
            $pessoaemail->id_tipoemail = 43;
            $pessoaemail->principal = 1;
            $pessoaemail->email = $dados["email"];
            $pessoaemail->ativo = 1;
            if ($pessoaemail->save() == false) {
                $transaction->rollback("Não foi possível salvar o email!");
            }
            $usuario = new Usuario();
            $usuario->setTransaction($transaction);
            $usuario->id_pessoa = $pessoa->id;
            $usuario->roles_name = $dados["roles_name"];
            $usuario->login = $dados["login"];
            $usuario->senha = $this->security->hash($dados["senha"]);
            if ($usuario->save() == false) {
                $transaction->rollback("Não foi possível salvar o usuário!");
            }
            //Commita a transação
            $transaction->commit();
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } catch (TxFailed $e) {
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Erro ao tentar realizar o cadastro!"
            )));
            return $response;
        }
    }

    public function editarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        try {
            $dados = filter_input_array(INPUT_POST);
            $usuario = Usuario::findFirst("id={$dados["id"]}");
            $pessoa = Pessoa::findFirst("id={$usuario->id_pessoa}");
            $pessoaemail = PessoaEmail::findFirst("id_pessoa={$usuario->id_pessoa}");
            //Create a transaction manager
            $manager = new TxManager();
            //Request a transaction
            $transaction = $manager->get();
            $pessoa->setTransaction($transaction);
            $pessoa->nome = $dados["nome_pessoa"];
            $pessoa->update_at = date("Y-m-d H:i:s");
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            $pessoaemail->setTransaction($transaction);
            $pessoaemail->email = $dados["email"];
            if ($pessoaemail->save() == false) {
                $transaction->rollback("Não foi possível salvar o email!");
            }
            $usuario->setTransaction($transaction);
            $usuario->roles_name = $dados["roles_name"];
            $usuario->login = $dados["login"];
            if ($usuario->save() == false) {
                $transaction->rollback("Não foi possível salvar o usuário!");
            }
            //Commita a transação
            $transaction->commit();
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } catch (TxFailed $e) {
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Erro ao tentar realizar a edição!"
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
        $core = new Core;
        try {
            $dados = filter_input_array(INPUT_POST);
            $usuario = Usuario::findFirst("id={$dados["id"]}");
            $pessoa = Pessoa::findFirst("id={$usuario->id_pessoa}");
            $pessoaemail = PessoaEmail::findFirst("id_pessoa={$usuario->id_pessoa}");
            $senha = $this->gerarSenhaAction(6,true,true,false);
            //Create a transaction manager
            $manager = new TxManager();
            //Request a transaction
            $transaction = $manager->get();
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
            //Corpo do email
            $html = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'";
            $html .= "'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
            $html .= "<html xmlns='http://www.w3.org/1999/xhtml' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>";
            $html .= "<head>";
            $html .= "<meta name='viewport' content='width=device-width'/>";
            $html .= "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
            $html .= "<title>Abstack - Responsive Web App Kit</title>";
            $html .= "<style type='text/css'>";
            $html .= "img {";
            $html .= "max-width: 100%;";
            $html .= "}";
            $html .= "body {";
            $html .= "-webkit-font-smoothing: antialiased;";
            $html .= "-webkit-text-size-adjust: none;";
            $html .= "width: 100% !important;";
            $html .= "height: 100%;";
            $html .= "line-height: 1.6em;";
            $html .= "}";   
            $html .= "body {";
            $html .= "background-color: #f6f6f6;";
            $html .= "}";
            $html .= "@media only screen and (max-width: 640px) {";
            $html .= "body {";
            $html .= "padding: 0 !important;";
            $html .= "}";   
            $html .= "h1 {";
            $html .= "font-weight: 800 !important;";
            $html .= "margin: 20px 0 5px !important;";
            $html .= "}";
            $html .= "h2 {";
            $html .= "font-weight: 800 !important;";
            $html .= "margin: 20px 0 5px !important;";
            $html .= "}";
            $html .= "h3 {";
            $html .= "font-weight: 800 !important;";
            $html .= "margin: 20px 0 5px !important;";
            $html .= "}";
            $html .= "h4 {";
            $html .= "font-weight: 800 !important;";
            $html .= "margin: 20px 0 5px !important;";
            $html .= "}";
            $html .= "h1 {";
            $html .= "font-size: 22px !important;";
            $html .= "}";
            $html .= "h2 {";
            $html .= "font-size: 18px !important;";
            $html .= "}";    
            $html .= "h3 {";
            $html .= "font-size: 16px !important;";
            $html .= "}";    
            $html .= ".container {";
            $html .= "padding: 0 !important;";
            $html .= "width: 100% !important;";
            $html .= "}";    
            $html .= ".content {";
            $html .= "padding: 0 !important;";
            $html .= "}";    
            $html .= ".content-wrap {";
            $html .= "padding: 10px !important;";
            $html .= "}";    
            $html .= ".invoice {";
            $html .= "width: 100% !important;";
            $html .= "}";
            $html .= "}";
            $html .= "</style>";
            $html .= "</head>";    
            $html .= "<body itemscope itemtype='http://schema.org/EmailMessage' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;'bgcolor='#f6f6f6'>";    
            $html .= "<table class='body-wrap' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;' bgcolor='#f6f6f6'>";
            $html .= "<tr style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>";
            $html .= "<td style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;' valign='top'></td>";
            $html .= "<td class='container' width='600' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;' valign='top'>"; 
            $html .= "<div class='content' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;'>"; 
            $html .= "<table class='main' width='100%' cellpadding='0' cellspacing='0' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;' bgcolor='#fff'>"; 
            $html .= "<tr style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>"; 
            $html .= "<td class='alert alert-warning' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #5d6dc3; margin: 0; padding: 20px;' align='center' bgcolor='#5d6dc3' valign='top'>"; 
            $html .= "ATENÇÃO: Você acaba de receber novas informações sobre seu acesso."; 
            $html .= "</td>"; 
            $html .= "</tr>"; 
            $html .= "<tr style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>"; 
            $html .= "<td class='content-wrap' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;' valign='top'>"; 
            $html .= "<table width='100%' cellpadding='0' cellspacing='0'style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>"; 
            $html .= "<tr style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>"; 
            $html .= "<td class='content-block' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;' valign='top'>"; 
            $html .= "Foi solicitado um <strong style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>reset de sua senha</strong>!"; 
            $html .= "</td>"; 
            $html .= "</tr>"; 
            $html .= "<tr style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>"; 
            $html .= "<td class='content-block' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;' valign='top'>"; 
            $html .= "Estamos enviando este e-mail para informar que sua senha foi redefinida através de uma solicitação de um dos administradores do sistema.</br> Sua agora é <b>'{$senha}'</b> e você precisa alterá-la em seu próximo acesso!"; 
            $html .= "</td>"; 
            $html .= "</tr>"; 
            $html .= "<tr style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>"; 
            $html .= "<td class='content-block' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;' valign='top'>"; 
            $html .= "<a href='http://2imagem.com.br/connecta' class='btn-primary' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 7px; text-transform: capitalize; background-color: #ed8e7b;background: linear-gradient(to top, #5d6dc3, #3c86d8); margin: 0; border-style: solid; padding: 8px 16px;'>Acessar o Sistema Connecta agora</a>"; 
            $html .= "</td>"; 
            $html .= "</tr>"; 
            $html .= "<tr style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>"; 
            $html .= "<td class='content-block' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;' valign='top'>"; 
            $html .= "Equipe de Suporte <b>PRODEPA</b>."; 
            $html .= "</td>"; 
            $html .= "</tr>"; 
            $html .= "</table>"; 
            $html .= "</td>"; 
            $html .= "</tr>"; 
            $html .= "</table>"; 
            $html .= "<div class='footer' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;'>"; 
            $html .= "<table width='100%' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>"; 
            $html .= "<tr style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;'>"; 
            $html .= "<td class='aligncenter content-block' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;' align='center' valign='top'><a href='#' style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0;'>Unsubscribe</a>"; 
            $html .= "from these alerts."; 
            $html .= "</td>"; 
            $html .= "</tr>"; 
            $html .= "</table>"; 
            $html .= "</div>"; 
            $html .= "</div>";
            $html .= "</td>"; 
            $html .= "<td style='font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;' valign='top'></td>"; 
            $html .= "</tr>"; 
            $html .= "</table>"; 
            $html .= "</body>"; 
            $html .= "</html>";     
            $core->enviarEmailAction(1,$pessoaemail->email,$pessoa->nome,null,"E-mail de Segurança",$html);
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } catch (TxFailed $e) {
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Erro ao tentar realizar o reset da senha!"
            )));
            return $response;
        }
    }

    public function ativarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core;
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $usuario = Usuario::findFirst("id={$dado}");
            $del = $core->ativarPessoaAction($usuario->id_pessoa);
        }
        if($del){
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
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $usuario = Usuario::findFirst("id={$dado}");
            $del = $core->inativarPessoaAction($usuario->id_pessoa);
        }
        if($del){
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
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $usuario = Usuario::findFirst("id={$dado}");
            $del = $core->deletarPessoaAction($usuario->id_pessoa);
        }
        if($del){
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
                "operacao" => False,
                "mensagem" => "Erro ao tentar realizar a operação!"
            )));
            return $response;
        }
    }
}
