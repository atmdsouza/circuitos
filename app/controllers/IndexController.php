<?php

namespace Circuitos\Controllers;

use Phalcon\Logger;
use Phalcon\Http\Response as Response;

use Circuitos\Models\Circuitos;
use Circuitos\Models\CidadeDigital;

use Auth\Autentica;

class IndexController extends ControllerBase
{

    public function initialize()
    {
        $this->tag->setTitle("Sistema de Gestão de Circuitos do Navega Pará");
        parent::initialize();
        //Voltando o usuário não autenticado para a página de login
        $auth = new Autentica();
        $identity = $auth->getIdentity();
        if (!is_array($identity)) {
            return $this->response->redirect('session/login');
        }
        $this->view->user = $identity["nome"];
    }

    public function indexAction()
    {

    }

    public function circuitoStatusAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = Circuitos::circuitoStatus();
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function circuitoFuncaoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = Circuitos::circuitoFuncao();
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function circuitoAcessoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = Circuitos::circuitoAcesso();
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function circuitoEsferaAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = Circuitos::circuitoEsfera();
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function circuitoLinkAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = Circuitos::circuitoLink();
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function circuitoLinkMesAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $primeiro_dia = date("Y") . "-" . date("m") . "-01";
        $hoje = date("Y-m-d");
        $dados = Circuitos::circuitoLinkMes($primeiro_dia, $hoje);
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function circuitoStatusMesAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $primeiro_dia = date("Y") . "-" . date("m") . "-01";
        $hoje = date("Y-m-d");
        $dados = Circuitos::circuitoStatusMes($primeiro_dia, $hoje);
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function circuitoConectividadeAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = Circuitos::circuitoConectividade();
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function cidadedigitalStatusAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = CidadeDigital::cidadedigitalStatus();
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function circuitoHotzoneCidadeAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = Circuitos::circuitoHotzoneCidade();
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

}

