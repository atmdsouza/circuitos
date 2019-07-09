<?php

namespace Circuitos\Controllers;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\Conectividade;
use Circuitos\Models\CidadeDigital;
use Circuitos\Models\EndCidade;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Infra;
use Util\TokenManager;

class ConectividadeController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Conectividade");
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
        $infra = new Infra();
        $dados = filter_input_array(INPUT_POST);
        $conectividade = Conectividade::pesquisarConectividade($dados["pesquisa"]);
        $tipocd = Lov::find(array(
            "tipo=18",
            "order" => "descricao"
        ));
        $paginator = new Paginator([
            'data' => $conectividade,
            'limit'=> $infra->getLimitePaginacao(),
            'page' => $infra->getPaginaInicial()
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->tipocd = $tipocd;
    }

}

