<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Circuitos\Models\Anexos;
use Circuitos\Models\ContratoFinanceiro;
use Circuitos\Models\ContratoFinanceiroNota;
use Circuitos\Models\Operations\AnexosOP;
use Circuitos\Models\Operations\ContratoFinanceiroOP;
use Circuitos\Models\Operations\CoreOP;
use Phalcon\Http\Response as Response;
use Util\TokenManager;

class ContratoFinanceiroController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle("Financeiro de Contratos");
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
        $contratofinanceiroOP = new ContratoFinanceiroOP();
        $contratofinanceiro = $contratofinanceiroOP->listar($dados['pesquisa']);
        $this->view->page = $contratofinanceiro;
    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Lançamento Financeiro';
        $msg = 'Lançamento Financeiro cadastrado com sucesso!';
        $error_msg = 'Erro ao cadastrar um Lançamento Financeiro!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofinanceiroOP = new ContratoFinanceiroOP();
            $contratofinanceiro = new ContratoFinanceiro($params);
            if($contratofinanceiroOP->cadastrar($contratofinanceiro)){//Cadastrou com sucesso
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
        $titulo = 'Alteração de Lançamento Financeiro';
        $msg = 'Lançamento Financeiro alterado com sucesso!';
        $error_msg = 'Erro ao alterar um Lançamento Financeiro!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofinanceiroOP = new ContratoFinanceiroOP();
            $contratofinanceiro = new ContratoFinanceiro($params);
            if($contratofinanceiroOP->alterar($contratofinanceiro)){//Altera com sucesso
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
        $titulo = 'Reativação de Lançamento Financeiro';
        $msg = 'Lançamento Financeiro reativado com sucesso!';
        $error_msg = 'Erro ao reativar um Lançamento Financeiro!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofinanceiroOP = new ContratoFinanceiroOP();
            $contratofinanceiro = new ContratoFinanceiro($dados['dados']);
            if($contratofinanceiroOP->ativar($contratofinanceiro)){//Cadastrou com sucesso
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
        $titulo = 'Desativação de Lançamento Financeiro';
        $msg = 'Lançamento Financeiro desativado com sucesso!';
        $error_msg = 'Erro ao desativar um Lançamento Financeiro!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofinanceiroOP = new ContratoFinanceiroOP();
            $contratofinanceiro = new ContratoFinanceiro($dados['dados']);
            if($contratofinanceiroOP->inativar($contratofinanceiro)){//Cadastrou com sucesso
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
        $titulo = 'Exclusão de Lançamento Financeiro';
        $msg = 'Lançamento Financeiro excluído com sucesso!';
        $error_msg = 'Erro ao excluir o Lançamento Financeiro!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofinanceiroOP = new ContratoFinanceiroOP();
            $contratofinanceiro = new ContratoFinanceiro($dados['dados']);
            if($contratofinanceiroOP->excluir($contratofinanceiro)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function baixarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Lançamento Financeiro';
        $msg = 'Lançamento Financeiro cadastrado com sucesso!';
        $error_msg = 'Erro ao cadastrar um Lançamento Financeiro!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofinanceiroOP = new ContratoFinanceiroOP();
            $contratofinanceironota = new ContratoFinanceiroNota($params);
            if($contratofinanceiroOP->cadastrarBaixa($contratofinanceironota)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function baixarExcluirAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $titulo = 'Exclusão de Pagamento';
        $msg = 'Pagamento excluído com sucesso!';
        $error_msg = 'Erro ao excluir um Pagamento!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratofinanceiroOP = new ContratoFinanceiroOP();
            $contratofinanceironota = new ContratoFinanceiroNota($dados['dados']);
            if($contratofinanceiroOP->excluirBaixa($contratofinanceironota)){//Cadastrou com sucesso
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
     * User: andre
     * Date: 06/11/2019
     * Time: 19:00
     * Responsável por vincular arquivos a um contrato
     */
    public function subirAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $anexosOP = new AnexosOP();
        $modulo = $this->router->getControllerName();
        $action = $this->router->getActionName();
        $request = $this->request;
        $id_contrato_financeiro = $request->get('id_contrato_financeiro');
        $id_contrato_financeiro_nota = $request->get('id_contrato_financeiro_nota');
        $id_tipo_anexo = $request->get('id_tipo_anexo');
        $descricao = $request->get('descricao');
        $coreOP = new CoreOP();
        $files = $coreOP->servicoUpload($request, $modulo, $action, $id_contrato_financeiro, null);
        foreach ($files as $key => $file)
        {
            $anexos = new Anexos();
            $anexos->setDescricao($descricao[$key]);
            $anexos->setIdTipoAnexo($id_tipo_anexo[$key]);
            $anexos->setUrl($file['path']);
            $anexo_cadastrado = $anexosOP->cadastrar($anexos);
            $contratofinanceironotaanexos = new ContratoFinanceiroNota();
            $contratofinanceironotaanexos->setId($id_contrato_financeiro_nota[$key]);
            $contratofinanceironotaanexos->setIdAnexo($anexo_cadastrado->getId());
            $anexosOP->cadastrarContratoFinanceiroNotaAnexo($contratofinanceironotaanexos);
        }
        $this->response->redirect('contrato_financeiro');
    }
}


