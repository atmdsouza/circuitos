<?php

namespace Circuitos\Controllers;

use Phalcon\Http\Response as Response;

use Circuitos\Models\Conectividade;
use Circuitos\Models\Operations\ConectividadeOP;

use Auth\Autentica;
use Util\TokenManager;

class ConectividadeController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle('Conectividade');
        parent::initialize();
        //Voltando o usuário não autenticado para a página de login
        $auth = new Autentica();
        $identity = $auth->getIdentity();
        if (!is_array($identity)) {
            return $this->response->redirect('session/login');
        }
        $this->view->user = $identity['nome'];
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
        $conectividadeOP = new ConectividadeOP();
        $conectividade = $conectividadeOP->listar($dados['pesquisa']);
        $this->view->page = $conectividade;
    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $conectividadeOP = new ConectividadeOP();
            $conectividade = new Conectividade($params);
            if($conectividadeOP->cadastrar($conectividade)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False,'mensagem' => 'Erro ao cadastrar uma Conectividade!')));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False,'mensagem' => 'Check de formulário inválido!')));
        }
        return $response;
    }

    public function editarAction()
    {

    }

    public function ativarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $conectividadeOP = new ConectividadeOP();
            $conectividade = new Conectividade($dados['dados']);
            if($conectividadeOP->ativar($conectividade)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False,'mensagem' => 'Erro ao cadastrar uma Conectividade!')));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False,'mensagem' => 'Check de formulário inválido!')));
        }
        return $response;
    }

    public function inativarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $conectividadeOP = new ConectividadeOP();
            $conectividade = new Conectividade($dados['dados']);
            if($conectividadeOP->inativar($conectividade)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False,'mensagem' => 'Erro ao cadastrar uma Conectividade!')));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False,'mensagem' => 'Check de formulário inválido!')));
        }
        return $response;
    }

    public function excluirAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $conectividadeOP = new ConectividadeOP();
            $conectividade = new Conectividade($dados['dados']);
            if($conectividadeOP->excluir($conectividade)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False,'mensagem' => 'Erro ao cadastrar uma Conectividade!')));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False,'mensagem' => 'Check de formulário inválido!')));
        }
        return $response;
    }

    public function visualizarAction()
    {

    }

}

