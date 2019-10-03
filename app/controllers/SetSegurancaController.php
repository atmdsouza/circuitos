<?php

namespace Circuitos\Controllers;

use Circuitos\Models\SetSegurancaComponentes;
use Circuitos\Models\SetSegurancaContato;
use Phalcon\Http\Response as Response;

use Circuitos\Models\Lov;
use Circuitos\Models\SetSeguranca;
use Circuitos\Models\Operations\SetSegurancaOP;

use Auth\Autentica;
use Util\TokenManager;
use Util\Util;

class SetSegurancaController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle("Set's de Segurança");
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
        $setsegurancaOP = new SetSegurancaOP();
        $setseguranca = $setsegurancaOP->listar($dados['pesquisa']);
        $tipos = Lov::find("tipo=21 AND excluido=0 AND ativo=1");
        $this->view->tipos = $tipos;
        $this->view->page = $setseguranca;
    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $util = new Util();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Set de Seguranca';
        $msg = 'Set de Seguranca cadastrado com sucesso!';
        $error_msg = 'Erro ao cadastrar um Set de Seguranca!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setsegurancaOP = new SetSegurancaOP();
            $setseguranca = new SetSeguranca();
            $setseguranca->setDescricao($params['descricao']);
            $arrayComponente = array();
            $arrayComponenteContato = array();
            foreach ($params['id_contrato'] as $key => $id_contrato){
                $validade = ($params['validade'][$key]) ? $util->converterDataUSA($params['validade'][$key]) : null;
                $contrato = ($id_contrato) ? $id_contrato : null;
                $tipo = ($params['id_tipo'][$key]) ? $params['id_tipo'][$key] : null;
                $fornecedor = ($params['id_fornecedor'][$key]) ? $params['id_fornecedor'][$key] : null;
                $componente = new SetSegurancaComponentes();
                $componente->setIdContrato($contrato);
                $componente->setEnderecoChave($params['endereco_chave'][$key]);
                $componente->setSenha($params['senha'][$key]);
                $componente->setValidade($validade);
                $componente->setIdFornecedor($fornecedor);
                $componente->setIdTipo($tipo);
                $componente->setPropriedadeProdepa($params['propriedade_prodepa'][$key]);
                array_push($arrayComponente, $componente);
                $componenteContato = new SetSegurancaContato();
                $tel1 = ($params["telefone"][$key]) ? $util->formataFone($params["telefone"][$key]) : null;
                $tel = ($tel1) ? $tel1["ddd"] . $tel1["fone"] : null;
                $componenteContato->setNome($params['nome'][$key]);
                $componenteContato->setTelefone($tel);
                $componenteContato->setEmail($params['email'][$key]);
                array_push($arrayComponenteContato, $componenteContato);
            }
            if($setsegurancaOP->cadastrar($setseguranca, $arrayComponente, $arrayComponenteContato)){//Cadastrou com sucesso
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
        $util = new Util();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Alteração de Set de Seguranca';
        $msg = 'Set de Seguranca alterada com sucesso!';
        $error_msg = 'Erro ao alterar uma Set de Seguranca!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setsegurancaOP = new SetSegurancaOP();
            $setseguranca = new SetSeguranca();
            $setseguranca->setId($params['id']);
            $setseguranca->setDescricao($params['descricao']);
            $arrayComponente = array();
            $arrayComponenteContato = array();
            foreach ($params['id_contrato'] as $key => $id_contrato){
                $validade = ($params['validade'][$key]) ? $util->converterDataUSA($params['validade'][$key]) : null;
                $contrato = ($id_contrato) ? $id_contrato : null;
                $tipo = ($params['id_tipo'][$key]) ? $params['id_tipo'][$key] : null;
                $fornecedor = ($params['id_fornecedor'][$key]) ? $params['id_fornecedor'][$key] : null;
                $componente = new SetSegurancaComponentes();
                $componente->setIdContrato($contrato);
                $componente->setEnderecoChave($params['endereco_chave'][$key]);
                $componente->setSenha($params['senha'][$key]);
                $componente->setValidade($validade);
                $componente->setIdFornecedor($fornecedor);
                $componente->setIdTipo($tipo);
                $componente->setPropriedadeProdepa($params['propriedade_prodepa'][$key]);
                array_push($arrayComponente, $componente);
                $componenteContato = new SetSegurancaContato();
                $tel1 = ($params["telefone"][$key]) ? $util->formataFone($params["telefone"][$key]) : null;
                $tel = ($tel1) ? $tel1["ddd"] . $tel1["fone"] : null;
                $componenteContato->setNome($params['nome'][$key]);
                $componenteContato->setTelefone($tel);
                $componenteContato->setEmail($params['email'][$key]);
                array_push($arrayComponenteContato, $componenteContato);
            }
            if($setsegurancaOP->alterar($setseguranca, $arrayComponente, $arrayComponenteContato)){//Altera com sucesso
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
        $titulo = 'Reativação de Set de Seguranca';
        $msg = 'Set de Seguranca reativado com sucesso!';
        $error_msg = 'Erro ao reativar uma Set de Seguranca!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setsegurancaOP = new SetSegurancaOP();
            $setseguranca = new SetSeguranca($dados['dados']);
            if($setsegurancaOP->ativar($setseguranca)){//Cadastrou com sucesso
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
        $titulo = 'Desativação de Set de Seguranca';
        $msg = 'Set de Seguranca desativado com sucesso!';
        $error_msg = 'Erro ao desativar uma Set de Seguranca!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setsegurancaOP = new SetSegurancaOP();
            $setseguranca = new SetSeguranca($dados['dados']);
            if($setsegurancaOP->inativar($setseguranca)){//Cadastrou com sucesso
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
        $titulo = 'Exclusão de Set de Seguranca';
        $msg = 'Set de Seguranca excluído com sucesso!';
        $error_msg = 'Erro ao excluir a Set de Seguranca!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $setsegurancaOP = new SetSegurancaOP();
            $setseguranca = new SetSeguranca($dados['dados']);
            if($setsegurancaOP->excluir($setseguranca)){//Cadastrou com sucesso
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
