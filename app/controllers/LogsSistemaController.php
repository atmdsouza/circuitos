<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Util\TokenManager;

class LogsSistemaController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Logs de Sistema");
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

    public function indexAction()
    {
        $logs = file(APP_PATH . "/../logs/systemlog.log");
        $linhas = [];
        foreach ($logs AS $log)
        {
            array_push($linhas, $log);
        }
        $this->view->page = $linhas;
    }

}

