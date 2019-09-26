<?php

namespace Circuitos\Controllers;

use Phalcon\Http\Response as Response;

use Circuitos\Models\Operations\SetEquipamentoOP;
use Circuitos\Models\SetEquipamento;
use Circuitos\Models\SetEquipamentoComponentes;

use Util\TokenManager;
use Auth\Autentica;

class SetEquipamentoController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle("Set's de Equipamentos");
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
        $setequipamentoOP = new SetEquipamentoOP();
        $setequipamento = $setequipamentoOP->listar($dados['pesquisa']);
        $this->view->page = $setequipamento;
    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Set de Equipamento';
        $msg = 'Set de Equipamento cadastrado com sucesso!';
        $error_msg = 'Erro ao cadastrar um Set de Equipamento!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setequipamentoOP = new SetEquipamentoOP();
            $setequipamento = new SetEquipamento();
            $setequipamento->setDescricao($params['descricao']);
            $arrayComponente = array();
            foreach ($params['id_contrato'] as $key => $id_contrato){
                $contrato = ($id_contrato) ? $id_contrato : null;
                $equipamento = ($params['id_equipamento'][$key]) ? $params['id_equipamento'][$key] : null;
                $fornecedor = ($params['id_fornecedor'][$key]) ? $params['id_fornecedor'][$key] : null;
                $componente = new SetEquipamentoComponentes();
                $componente->setIdContrato($contrato);
                $componente->setIdFornecedor($fornecedor);
                $componente->setIdEquipamento($equipamento);
                array_push($arrayComponente, $componente);
            }
            if($setequipamentoOP->cadastrar($setequipamento, $arrayComponente)){//Cadastrou com sucesso
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
        $titulo = 'Alteração de Set de Equipamento';
        $msg = 'Set de Equipamento alterada com sucesso!';
        $error_msg = 'Erro ao alterar uma Set de Equipamento!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setequipamentoOP = new SetEquipamentoOP();
            $setequipamento = new SetEquipamento($params);
            if($setequipamentoOP->alterar($setequipamento)){//Altera com sucesso
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
        $titulo = 'Reativação de Set de Equipamento';
        $msg = 'Set de Equipamento reativada com sucesso!';
        $error_msg = 'Erro ao reativar uma Set de Equipamento!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setequipamentoOP = new SetEquipamentoOP();
            $setequipamento = new SetEquipamento($dados['dados']);
            if($setequipamentoOP->ativar($setequipamento)){//Cadastrou com sucesso
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
        $titulo = 'Desativação de Set de Equipamento';
        $msg = 'Set de Equipamento desativada com sucesso!';
        $error_msg = 'Erro ao desativar uma Set de Equipamento!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setequipamentoOP = new SetEquipamentoOP();
            $setequipamento = new SetEquipamento($dados['dados']);
            if($setequipamentoOP->inativar($setequipamento)){//Cadastrou com sucesso
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
        $titulo = 'Exclusão de Set de Equipamento';
        $msg = 'Set de Equipamento excluída com sucesso!';
        $error_msg = 'Erro ao excluir a Set de Equipamentos!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setequipamentoOP = new SetEquipamentoOP();
            $setequipamento = new SetEquipamento($dados['dados']);
            if($setequipamentoOP->excluir($setequipamento)){//Cadastrou com sucesso
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

