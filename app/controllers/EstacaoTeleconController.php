<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Circuitos\Models\EstacaoTelecon;
use Circuitos\Models\Lov;
use Circuitos\Models\Operations\EstacaoTeleconOP;
use Phalcon\Http\Response as Response;
use Util\TokenManager;

class EstacaoTeleconController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle("Estação Telecom");
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
        $estacaoteleconOP = new EstacaoTeleconOP();
        $estacaotelecon = $estacaoteleconOP->listar($dados['pesquisa']);
        $tipo_estacaotelecon = Lov::find(array(
            "tipo = 32",
            "order" => "descricao"
        ));
        $this->view->page = $estacaotelecon;
        $this->view->tipo_estacaotelecon = $tipo_estacaotelecon;
    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Estação Telecom';
        $msg = 'Estação Telecom cadastrada com sucesso!';
        $error_msg = 'Erro ao cadastrar uma Estação Telecom!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $estacaoteleconOP = new EstacaoTeleconOP();
            $estacaotelecon = new EstacaoTelecon($params);
            if($estacaoteleconOP->cadastrar($estacaotelecon)){//Cadastrou com sucesso
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
        $titulo = 'Alteração de Estação Telecom';
        $msg = 'Estação Telecom alterada com sucesso!';
        $error_msg = 'Erro ao alterar uma Estação Telecom!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $estacaoteleconOP = new EstacaoTeleconOP();
            $estacaotelecon = new EstacaoTelecon($params);
            if($estacaoteleconOP->alterar($estacaotelecon)){//Altera com sucesso
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
        $titulo = 'Reativação de Estação Telecom';
        $msg = 'Estação Telecom reativada com sucesso!';
        $error_msg = 'Erro ao reativar uma Estação Telecom!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $estacaoteleconOP = new EstacaoTeleconOP();
            $estacaotelecon = new EstacaoTelecon($dados['dados']);
            if($estacaoteleconOP->ativar($estacaotelecon)){//Cadastrou com sucesso
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
        $titulo = 'Desativação de Estação Telecom';
        $msg = 'Estação Telecom desativada com sucesso!';
        $error_msg = 'Erro ao desativar uma Estação Telecom!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $estacaoteleconOP = new EstacaoTeleconOP();
            $estacaotelecon = new EstacaoTelecon($dados['dados']);
            if($estacaoteleconOP->inativar($estacaotelecon)){//Cadastrou com sucesso
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
        $titulo = 'Exclusão de Estação Telecom';
        $msg = 'Estação Telecom excluída com sucesso!';
        $error_msg = 'Erro ao excluir a Estação Telecom!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $estacaoteleconOP = new EstacaoTeleconOP();
            $estacaotelecon = new EstacaoTelecon($dados['dados']);
            if($estacaoteleconOP->excluir($estacaotelecon)){//Cadastrou com sucesso
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
