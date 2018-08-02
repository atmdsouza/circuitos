<?php

require_once APP_PATH . '/library/Auth/Auth.php';

class IndexController extends ControllerBase
{

    public function initialize()
    {
        //Voltando o usuário não autenticado para a página de login
        $identity = $this->auth->getIdentity();
        if (!is_array($identity)) {
            return $this->response->redirect('session/login');
        }
        $this->view->user = $identity["nome"];
    }

    public function indexAction()
    {
        
    }

}

