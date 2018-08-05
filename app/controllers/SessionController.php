<?php

namespace Circuitos\Controllers;

use Phalcon\Mvc\View;
use Phalcon\Mvc\Controller;

use Auth\Autentica;
use Util\TokenManager;

use Circuitos\Controllers\ControllerBase;
use Circuitos\Controllers\UsuarioController as Usuario;
use Circuitos\Models\Usuario as ModelUser;
use Circuitos\Models\PessoaEmail;

class SessionController extends ControllerBase {

    public $tokenManager;

    public function initialize()
    {
        $this->tokenManager = new TokenManager();
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
            if ($this->request->isPost()) {
                if ($this->security->checkToken()) {
                    $auth = new Autentica();
                    $check = $auth->check(array(
                        'login' => $this->request->getPost('login'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ));
                    if(!$check) {
                        $this->flash->error("Combinação de usuário e senha inválida!");
                        $this->dispatcher->forward([
                            "controller" => "session",
                            "action" => "login"
                        ]);
                    } else {
                        $usuario = ModelUser::findFirst("login='{$this->request->getPost('login')}'");
                        $usuario->data_ultimoacesso = date("Y-m-d H:i:s");
                        $usuario->save();
                        $user = new Usuario();
                        $redirect = $user->redirecionaUsuarioAction($this->request->getPost('login'));
                        return $this->response->redirect($redirect);
                    }
                }
            }
        } catch (Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }

    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->session->destroy();
        $auth = new Autentica();
        $auth->remove();
        return $this->response->redirect('session/sair');
    }

    public function sairAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    public function esqueceuAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $pessoaemail = PessoaEmail::findFirst("email='{$this->request->getPost('email')}'");
        $user = ModelUser::findFirst("id_pessoa={$pessoaemail->id_pessoa}");
        $usuario = new Usuario();
        $usuario->recuperarSenhaAction($user->id);
    }

    public function recuperarAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        if ($this->request->isPost()) {
            $this->flash->notice("Senha resetada e enviada para seu e-mail. Por favor, verifique a nova senha para fazer o login!");
            $this->dispatcher->forward([
                "controller" => "session",
                "action" => "recuperar"
            ]);
        }
    }

    public function inativoAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

}
