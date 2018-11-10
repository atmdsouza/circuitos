<?php

namespace Circuitos\Controllers;

use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;

use Circuitos\Controllers\ControllerBase;
use Auth\Autentica;

class IndexController extends ControllerBase
{

    public function initialize()
    {
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

}

