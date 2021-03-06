<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Circuitos\Models\Anexos;
use Circuitos\Models\ContratoFiscal;
use Circuitos\Models\ContratoFiscalAnexo;
use Circuitos\Models\ContratoFiscalHasContrato;
use Circuitos\Models\Lov;
use Circuitos\Models\Operations\AnexosOP;
use Circuitos\Models\Operations\ContratoFiscalOP;
use Circuitos\Models\Operations\CoreOP;
use Phalcon\Http\Response;
use Util\TokenManager;

class ContratoFiscalController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle("Fiscais de Contratos");
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
        $contratofiscalOP = new ContratoFiscalOP();
        $contratofiscal = $contratofiscalOP->listar($dados['pesquisa']);
        $tipos_anexos = Lov::find(array(
            "tipo = 20 AND excluido = 0 AND ativo = 1",
            "order" => "descricao"
        ));
        $this->view->page = $contratofiscal;
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
        $titulo = 'Cadastro de Fiscal de Contrato';
        $msg = 'Fiscal de Contrato cadastrado com sucesso!';
        $error_msg = 'Erro ao cadastrar um Fiscal de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofiscalOP = new ContratoFiscalOP();
            $contratofiscal = new ContratoFiscal($params);
            $vinculocontrato = new ContratoFiscalHasContrato($params);
            if($contratofiscalOP->cadastrar($contratofiscal, $vinculocontrato)){//Cadastrou com sucesso
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
        $titulo = 'Alteração de Fiscal de Contrato';
        $msg = 'Fiscal de Contrato alterado com sucesso!';
        $error_msg = 'Erro ao alterar um Fiscal de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofiscalOP = new ContratoFiscalOP();
            $contratofiscal = new ContratoFiscal($params);
            $vinculocontrato = new ContratoFiscalHasContrato($params);
            if($contratofiscalOP->alterar($contratofiscal, $vinculocontrato)){//Altera com sucesso
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
        $titulo = 'Reativação de Fiscal de Contrato';
        $msg = 'Fiscal de Contrato reativado com sucesso!';
        $error_msg = 'Erro ao reativar um Fiscal de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofiscalOP = new ContratoFiscalOP();
            $contratofiscal = new ContratoFiscal($dados['dados']);
            if($contratofiscalOP->ativar($contratofiscal)){//Cadastrou com sucesso
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
        $titulo = 'Desativação de Fiscal de Contrato';
        $msg = 'Fiscal de Contrato desativado com sucesso!';
        $error_msg = 'Erro ao desativar um Fiscal de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofiscalOP = new ContratoFiscalOP();
            $contratofiscal = new ContratoFiscal($dados['dados']);
            if($contratofiscalOP->inativar($contratofiscal)){//Cadastrou com sucesso
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
        $titulo = 'Exclusão de Fiscal de Contrato';
        $msg = 'Fiscal de Contrato excluído com sucesso!';
        $error_msg = 'Erro ao excluir o Fiscal de Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofiscalOP = new ContratoFiscalOP();
            $contratofiscal = new ContratoFiscal($dados['dados']);
            if($contratofiscalOP->excluir($contratofiscal)){//Cadastrou com sucesso
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
            $vinculoanexos = new ContratoFiscalAnexo();
            $vinculoanexos->setIdAnexo($anexo_cadastrado->getId());
            $vinculoanexos->setIdContratoFiscal($id_contrato_fiscal);
            $anexosOP->cadastrarContratoFiscalAnexo($vinculoanexos);
        }
        $this->response->redirect('contrato_fiscal');
    }
}
