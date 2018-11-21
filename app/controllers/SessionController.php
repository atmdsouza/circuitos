<?php

namespace Circuitos\Controllers;

use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;
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

    /**
     * Login Action System
     */
    public function loginAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $logger = new FileAdapter(BASE_PATH . "/logs/systemlog.log");
        try {
            if ($this->request->isPost()) {
                if ($this->security->checkToken()) {
                    $auth = new Autentica();
                    if (!$this->request->getPost('login') && !$this->request->getPost('password')){
                        $this->flash->error("Os campos login e senha não podem ser vazios!");
                        $this->dispatcher->forward([
                            "controller" => "session",
                            "action" => "login"
                        ]);
                    } else if (!$this->request->getPost('password')) {
                        $this->flash->error("O campo senha não pode ser vazio!");
                        $this->dispatcher->forward([
                            "controller" => "session",
                            "action" => "login"
                        ]);
                    } else if (!$this->request->getPost('login')) {
                        $this->flash->error("O campo login não pode ser vazio!");
                        $this->dispatcher->forward([
                            "controller" => "session",
                            "action" => "login"
                        ]);
                    } else {
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
                            $usuario->setDataUltimoacesso(date("Y-m-d H:i:s"));
                            $usuario->save();
                            $user = new Usuario();
                            $redirect = $user->redirecionaUsuarioAction($this->request->getPost('login'));
                            $logger->info("Acesso do usuário {$this->request->getPost('login')} ao sistema.");
                            return $this->response->redirect($redirect);
                        }
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
        $logger = new FileAdapter(BASE_PATH . "/logs/systemlog.log");
        $auth = new Autentica();
        $identity = $auth->getIdentity();
        $logger->info("Saída do usuário {$identity["login"]} do sistema.");
        $auth->remove();
        return $this->response->redirect('session/sair');
    }

    public function sairAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    public function recuperarAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $logger = new FileAdapter(BASE_PATH . "/logs/systemlog.log");
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                if (!$this->request->getPost('email')){
                    $this->flash->error("O campo endereço de e-mail não pode ser vazio!");
                    $this->dispatcher->forward([
                        "controller" => "session",
                        "action" => "recuperar"
                    ]);
                } else {
                    $pessoaemail = PessoaEmail::findFirst("email='{$this->request->getPost('email')}'");
                    if (!$pessoaemail){
                        $this->flash->error("O endereço de e-mail não existe para nenhum de nossos usuários!");
                        $this->dispatcher->forward([
                            "controller" => "session",
                            "action" => "recuperar"
                        ]);
                    } else {
                        $user = ModelUser::findFirst("id_pessoa={$pessoaemail->getIdPessoa()}");
                        $usuario = new Usuario();
                        $check = $usuario->recuperarSenhaAction($user->getId());
                        if ($check){
                            $conteudo = "<script>swal('Sucesso!', 'Senha recuperada com sucesso! Volte e tente novamente fazer login!', 'success')</script>";
                            echo $conteudo;
                            $this->flash->success("Senha recuperada com sucesso! Volte e tente novamente fazer login!");
                            $this->response->redirect('session/login');
                        } else {
                            $this->flash->error("Erro ao tentar recuperar a senha! Tente novamente após alguns minutos!");
                            $this->response->redirect('session/recuperar');
                        }
                    }
                }
            }
        }
    }

    public function inativoAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

}
