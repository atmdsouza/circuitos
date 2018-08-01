<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Controller;
use App\Library\TokenManager;

require_once APP_PATH . '/library/Acl/Acl.php';
require_once APP_PATH . '/library/Auth/Auth.php';
require_once APP_PATH . '/library/CSRFToken/CSRFToken.php';

class SessionController extends ControllerBase {

    public $tokenManager;

    public function initialize()
    {
        $this->tokenManager = new TokenManager;
        if (!$this->tokenManager->doesUserHaveToken('User')) {
            $this->tokenManager->generateToken('User');
        }
        $this->view->token = $this->tokenManager->getToken('User');
    }

    public function indexAction() {

    }

    /**
     * Login Action System
     */
    public function loginAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        try {
            if (!$this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {
                $this->auth->check(array(
                    'login' => $this->request->getPost('login'),
                    'password' => $this->request->getPost('password'),
                    'remember' => $this->request->getPost('remember')
                ));
                return $this->response->redirect("/");
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }
    }

    /**
     * Closes the session
     */
    public function logoutAction() 
    {
        $this->session->destroy();
        $this->auth->remove();
        return $this->response->redirect('session/sair');
    }

    public function sairAction() 
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

    }

    public function recuperarAction() 
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

    }

}
