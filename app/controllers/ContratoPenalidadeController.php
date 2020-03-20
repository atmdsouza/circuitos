<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Circuitos\Models\Anexos;
use Circuitos\Models\ContratoPenalidade;
use Circuitos\Models\ContratoPenalidadeAnexo;
use Circuitos\Models\Lov;
use Circuitos\Models\Operations\AnexosOP;
use Circuitos\Models\Operations\ContratoPenalidadeOP;
use Circuitos\Models\Operations\CoreOP;
use Util\TokenManager;

class ContratoPenalidadeController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle("Penalidades de Contratos");
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
        $contratpenalidadeOP = new ContratoPenalidadeOP();
        $contratpenalidade = $contratpenalidadeOP->listar($dados['pesquisa']);
        $tipos_anexos = Lov::find(array(
            'tipo = 20 AND excluido = 0 AND ativo = 1',
            'order' => 'descricao'
        ));
        $tipos_servicos = Lov::find(array(
            'tipo = 33 AND excluido = 0 AND ativo = 1',
            'order' => 'descricao'
        ));
        $this->view->page = $contratpenalidade;
        $this->view->tipos_servicos = $tipos_servicos;
        $this->view->tipos_anexos = $tipos_anexos;
    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Penalidade de Contrato';
        $msg = 'Penalidade de Contrato cadastrado com sucesso!';
        $error_msg = 'Erro ao cadastrar um Penalidade de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratpenalidadeOP = new ContratoPenalidadeOP();
            $contratpenalidade = new ContratoPenalidade($params);
            $vinculocontrato = new ContratoPenalidadeHasContrato($params);
            if($contratpenalidadeOP->cadastrar($contratpenalidade, $vinculocontrato)){//Cadastrou com sucesso
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
        $titulo = 'Alteração de Penalidade de Contrato';
        $msg = 'Penalidade de Contrato alterado com sucesso!';
        $error_msg = 'Erro ao alterar um Penalidade de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratpenalidadeOP = new ContratoPenalidadeOP();
            $contratpenalidade = new ContratoPenalidade($params);
            $vinculocontrato = new ContratoPenalidadeHasContrato($params);
            if($contratpenalidadeOP->alterar($contratpenalidade, $vinculocontrato)){//Altera com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function movimentarAction()
    {

    }

    public function ativarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $titulo = 'Reativação de Penalidade de Contrato';
        $msg = 'Penalidade de Contrato reativado com sucesso!';
        $error_msg = 'Erro ao reativar um Penalidade de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratpenalidadeOP = new ContratoPenalidadeOP();
            $contratpenalidade = new ContratoPenalidade($dados['dados']);
            if($contratpenalidadeOP->ativar($contratpenalidade)){//Cadastrou com sucesso
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
        $titulo = 'Desativação de Penalidade de Contrato';
        $msg = 'Penalidade de Contrato desativado com sucesso!';
        $error_msg = 'Erro ao desativar um Penalidade de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratpenalidadeOP = new ContratoPenalidadeOP();
            $contratpenalidade = new ContratoPenalidade($dados['dados']);
            if($contratpenalidadeOP->inativar($contratpenalidade)){//Cadastrou com sucesso
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
        $titulo = 'Exclusão de Penalidade de Contrato';
        $msg = 'Penalidade de Contrato excluído com sucesso!';
        $error_msg = 'Erro ao excluir o Penalidade de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratpenalidadeOP = new ContratoPenalidadeOP();
            $contratpenalidade = new ContratoPenalidade($dados['dados']);
            if($contratpenalidadeOP->excluir($contratpenalidade)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    /**
     * Created by PhpStorm.
     * User: André Souza
     * Date: 06/02/2020
     * Responsável por vincular arquivos a um fiscal
     */
    public function subirAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $anexosOP = new AnexosOP();
        $modulo = $this->router->getControllerName();
        $action = $this->router->getActionName();
        $request = $this->request;
        $id_contrato_fiscal = $request->get('id_contrato_fiscal');
        $id_tipo_anexo = $request->get('id_tipo_anexo');
        $descricao = $request->get('descricao');
        $coreOP = new CoreOP();
        $files = $coreOP->servicoUpload($request, $modulo, $action, $id_contrato_fiscal, null);
        foreach ($files as $key => $file)
        {
            $anexos = new Anexos();
            $anexos->setDescricao($descricao[$key]);
            $anexos->setIdTipoAnexo($id_tipo_anexo[$key]);
            $anexos->setUrl($file['path']);
            $anexo_cadastrado = $anexosOP->cadastrar($anexos);
            $vinculoanexos = new ContratoPenalidadeAnexo();
            $vinculoanexos->setIdAnexo($anexo_cadastrado->getId());
            $vinculoanexos->setIdContratoPenalidade($id_contrato_fiscal);
            $anexosOP->cadastrarContratoPenalidadeAnexo($vinculoanexos);
        }
        $this->response->redirect('contrato_fiscal');
    }
}
