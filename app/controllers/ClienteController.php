<?php

namespace Circuitos\Controllers;
 
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Controllers\ControllerBase;
use Circuitos\Controllers\CoreController as Core;
use Circuitos\Models\Pessoa;
use Circuitos\Models\PessoaEmail;
use Circuitos\Models\PessoaEndereco;
use Circuitos\Models\PessoaContato;
use Circuitos\Models\PessoaTelefone;
use Circuitos\Models\PessoaFisica;
use Circuitos\Models\PessoaJuridica;
use Circuitos\Models\Cliente;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Util;
use Util\TemplatesEmails;
use Util\TokenManager;

class ClienteController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
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
        $this->persistent->parameters = null;
        $numberPage = 1;
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";
        $usuarios = Cliente::find($parameters);
        $paginator = new Paginator([
            'data' => $usuarios,
            'limit'=> 500,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
    }
}
