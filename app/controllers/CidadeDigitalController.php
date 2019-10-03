<?php

namespace Circuitos\Controllers;

use Circuitos\Models\Conectividade;
use Circuitos\Models\EstacaoTelecon;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Http\Response as Response;

use Circuitos\Models\CidadeDigital;
use Circuitos\Models\Operations\CidadeDigitalOP;
use Circuitos\Models\EndEstado;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Infra;
use Util\TokenManager;

class CidadeDigitalController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Cidade Digital");
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

    /**
     * Index action
     */
    public function indexAction()
    {
        $infra = new Infra();
        $cidadedigitalOP = new CidadeDigitalOP();
        $dados = filter_input_array(INPUT_POST);
        $cidadedigital = $cidadedigitalOP->listar($dados["pesquisa"]);
        $tipocd = Lov::find(array(
            "tipo=18",
            "order" => "descricao"
        ));
        $estado = EndEstado::find(array(
            "order" => "estado"
        ));
        $paginator = new Paginator([
            'data' => $cidadedigital,
            'limit'=> $infra->getLimitePaginacao(),
            'page' => $infra->getPaginaInicial()
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->tipocd = $tipocd;
        $this->view->estado = $estado;
    }

    /**
     * Criado por Fábrica Emage.
     * Codificador: André Souza
     * Data: 19/09/2019
     * Objetivo: Ser responsável pela renderização da view de exibição das cidades digitais no mapa
     */
    public function localizarAction()
    {

    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Cidade Digital';
        $msg = 'Cidade Digital cadastrada com sucesso!';
        $error_msg = 'Erro ao cadastrar uma Cidade Digital!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $cidadedigitalOP = new CidadeDigitalOP();
            $cidadedigital = new CidadeDigital();
            $cidadedigital->setDescricao($params['descricao']);
            $cidadedigital->setIdCidade($params['id_cidade']);
            $arrayConectividade = array();
            $arrayETelecon = array();
            foreach ($params['conectividade'] as $key => $conect){
                $conectividade = new Conectividade();
                $conectividade->setIdTipo($params['tipo_conectividade'][$key]);
                $conectividade->setDescricao($conect);
                $conectividade->setEndereco($params['endereco'][$key]);
                array_push($arrayConectividade, $conectividade);
            }
            foreach ($params['estelecon'] as $key => $etelecon){
                $estacaotelecon = new EstacaoTelecon();
                $estacaotelecon->setDescricao($etelecon);
                $estacaotelecon->setIdContrato($params['id_contrato'][$key]);
                $estacaotelecon->setIdTerreno($params['id_terreno'][$key]);
                $estacaotelecon->setIdTorre($params['id_torre'][$key]);
                $estacaotelecon->setIdSetEquipamento($params['id_set_equipamento'][$key]);
                $estacaotelecon->setIdSetSeguranca($params['id_set_seguranca'][$key]);
                $estacaotelecon->setIdUnidadeConsumidora($params['id_unidade_consumidora'][$key]);
                array_push($arrayETelecon, $estacaotelecon);
            }
            if($cidadedigitalOP->cadastrar($cidadedigital, $arrayConectividade, $arrayETelecon)){//Cadastrou com sucesso
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
        $titulo = 'Alteração de Cidade Digital';
        $msg = 'Cidade Digital alterada com sucesso!';
        $error_msg = 'Erro ao alterar uma Cidade Digital!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $cidadedigitalOP = new CidadeDigitalOP();
            $cidadedigital = new CidadeDigital();
            $cidadedigital->setId($params['id']);
            $cidadedigital->setDescricao($params['descricao']);
            $cidadedigital->setIdCidade($params['id_cidade']);
            $arrayConectividade = array();
            $arrayETelecon = array();
            foreach ($params['conectividade'] as $key => $conect){
                $conectividade = new Conectividade();
                $conectividade->setIdTipo($params['tipo_conectividade'][$key]);
                $conectividade->setDescricao($conect);
                $conectividade->setEndereco($params['endereco'][$key]);
                array_push($arrayConectividade, $conectividade);
            }
            foreach ($params['estelecon'] as $key => $etelecon){
                $estacaotelecon = new EstacaoTelecon();
                $estacaotelecon->setDescricao($etelecon);
                $estacaotelecon->setIdContrato($params['id_contrato'][$key]);
                $estacaotelecon->setIdTerreno($params['id_terreno'][$key]);
                $estacaotelecon->setIdTorre($params['id_torre'][$key]);
                $estacaotelecon->setIdSetEquipamento($params['id_set_equipamento'][$key]);
                $estacaotelecon->setIdSetSeguranca($params['id_set_seguranca'][$key]);
                $estacaotelecon->setIdUnidadeConsumidora($params['id_unidade_consumidora'][$key]);
                array_push($arrayETelecon, $estacaotelecon);
            }
            if($cidadedigitalOP->alterar($cidadedigital, $arrayConectividade, $arrayETelecon)){//Altera com sucesso
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
        $titulo = 'Reativação de Cidade Digital';
        $msg = 'Cidade Digital reativada com sucesso!';
        $error_msg = 'Erro ao reativar uma Cidade Digital!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $cidadedigitalOP = new CidadeDigitalOP();
            $cidadedigital = new CidadeDigital($dados['dados']);
            if($cidadedigitalOP->ativar($cidadedigital)){//Cadastrou com sucesso
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
        $titulo = 'Desativação de Cidade Digital';
        $msg = 'Cidade Digital desativada com sucesso!';
        $error_msg = 'Erro ao desativar uma Cidade Digital!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $cidadedigitalOP = new CidadeDigitalOP();
            $cidadedigital = new CidadeDigital($dados['dados']);
            if($cidadedigitalOP->inativar($cidadedigital)){//Cadastrou com sucesso
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
        $titulo = 'Exclusão de Cidade Digital';
        $msg = 'Cidade Digital excluída com sucesso!';
        $error_msg = 'Erro ao excluir a Cidade Digital!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $cidadedigitalOP = new CidadeDigitalOP();
            $cidadedigital = new CidadeDigital($dados['dados']);
            if($cidadedigitalOP->excluir($cidadedigital)){//Cadastrou com sucesso
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
