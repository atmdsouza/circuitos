<?php

namespace Circuitos\Controllers;

use Phalcon\Http\Response as Response;

use Circuitos\Controllers\ControllerBase;

use Circuitos\Models\Circuitos;

use Auth\Autentica;
use Util\TokenManager;
use Util\Util;
use Util\Relatorio;

class RelatoriosGestaoController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Relatórios");
        parent::initialize();
        //Voltando o usuário não autenticado para a página de login
        $auth = new Autentica();
        $identity = $auth->getIdentity();
        if (!is_array($identity)) {
            return $this->response->redirect("session/login");
        }
        $this->view->user = $identity["nome"];
        //CSRFToken
        $this->tokenManager = new TokenManager();
        if (!$this->tokenManager->doesUserHaveToken("User")) {
            $this->tokenManager->generateToken("User");
        }
        $this->view->token = $this->tokenManager->getToken("User");
    }

    /**
     * Index action
     */
    public function indexAction()
    {

    }

    public function relatorioCustomizadoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $orderby = $dados["ordenar_campo"] . " " . $dados["ordenar_sentido"];
        $where = null;
        if (!isset($dados["eixo_y"])) {
            $dados_relatorio = Circuitos::pesquisarRelatorioCircuitosSF($dados["eixo_x"], $orderby);
        } else {
            $dados_relatorio = Circuitos::pesquisarRelatorioCircuitos($dados["eixo_x"], $where, $orderby);
        }

        $response->setContent(json_encode(array(
            "operacao" => True,
            "dados" => $dados_relatorio
        )));
        return $response;
    }

}
