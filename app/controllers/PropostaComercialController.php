<?php

namespace Circuitos\Controllers;

use Phalcon\Http\Response as Response;

use Circuitos\Models\Lov;
use Circuitos\Models\Operations\PropostaComercialOP;
use Circuitos\Models\PropostaComercial;

use Auth\Autentica;
use Util\TokenManager;

class PropostaComercialController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle("Gestão Comercial");
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
        $dados = filter_input_array(INPUT_POST);
        $propostacomercialOP = new PropostaComercialOP();
        $propostacomercial = $propostacomercialOP->listar($dados['pesquisa']);
        $tipos = Lov::find("tipo=23 AND excluido=0 AND ativo=1");
        $this->view->tipos = $tipos;
        $this->view->page = $propostacomercial;
    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Proposta Comercial';
        $msg = 'Proposta Comercial cadastrada com sucesso!';
        $error_msg = 'Erro ao cadastrar uma Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercialOP = new PropostaComercialOP();
            $propostacomercial = new PropostaComercial($params);
            if($propostacomercialOP->cadastrar($propostacomercial)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function editarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Alteração de Proposta Comercial';
        $msg = 'Proposta Comercial alterada com sucesso!';
        $error_msg = 'Erro ao alterar uma Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercialOP = new PropostaComercialOP();
            $propostacomercial = new PropostaComercial($params);
            if($propostacomercialOP->alterar($propostacomercial)){//Altera com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function ativarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $titulo = 'Reativação de Proposta Comercial';
        $msg = 'Proposta Comercial reativada com sucesso!';
        $error_msg = 'Erro ao reativar uma Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercialOP = new PropostaComercialOP();
            $propostacomercial = new PropostaComercial($dados['dados']);
            if($propostacomercialOP->ativar($propostacomercial)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function inativarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $titulo = 'Desativação de Proposta Comercial';
        $msg = 'Proposta Comercial desativada com sucesso!';
        $error_msg = 'Erro ao desativar uma Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercialOP = new PropostaComercialOP();
            $propostacomercial = new PropostaComercial($dados['dados']);
            if($propostacomercialOP->inativar($propostacomercial)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function excluirAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $titulo = 'Exclusão de Proposta Comercial';
        $msg = 'Proposta Comercial excluída com sucesso!';
        $error_msg = 'Erro ao excluir a Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercialOP = new PropostaComercialOP();
            $propostacomercial = new PropostaComercial($dados['dados']);
            if($propostacomercialOP->excluir($propostacomercial)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }
}
