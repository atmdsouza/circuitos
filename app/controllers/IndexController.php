<?php

require_once APP_PATH . '/library/Auth/Auth.php';

class IndexController extends ControllerBase
{
    public function initialize()
    {
        // Get the current identity
        $identity = $this->auth->getIdentity();
        // If there is no identity available the user is redirected to session/login
        if (!is_array($identity)) {
            return $this->response->redirect('session/login');
        }
    }

    public function indexAction()
    {
        
    }

}

