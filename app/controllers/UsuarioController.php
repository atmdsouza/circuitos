<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Usuario as Usuario;

class UsuarioController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $dados = filter_input_array(INPUT_POST);
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, Usuario, $dados);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";
        $usuario = Usuario::find($parameters);
        $pessoas = Pessoa::find();
        $roles = PhalconRoles::find();
        $paginator = new Paginator([
            'data' => $usuario,
            'limit'=> 10,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->pessoas = $pessoas;
        $this->view->roles = $roles;
    }

    /**
     * Searches for usuario
     */
    public function searchAction()
    {
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a usuario
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $usuario = Usuario::findFirstByid($id);
            if (!$usuario) {
                $this->flash->error("Usuário não localizado!");

                $this->dispatcher->forward([
                    'controller' => "usuario",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $usuario->id;

            $this->tag->setDefault("id", $usuario->id);
            $this->tag->setDefault("id_pessoa", $usuario->id_pessoa);
            $this->tag->setDefault("roles_name", $usuario->roles_name);
            $this->tag->setDefault("login", $usuario->login);
            $this->tag->setDefault("senha", $usuario->senha);
            
        }
    }

    /**
     * Creates a new usuario
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "usuario",
                'action' => 'index'
            ]);

            return;
        }

        $usuario = new Usuario();
        $usuario->idPessoa = $this->request->getPost("id_pessoa");
        $usuario->rolesName = $this->request->getPost("roles_name");
        $usuario->login = $this->request->getPost("login");
        $usuario->senha = $this->security->hash($this->request->getPost("senha"));
        

        if (!$usuario->save()) {
            foreach ($usuario->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "usuario",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Usuário criado com sucesso!");

        $this->dispatcher->forward([
            'controller' => "usuario",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a usuario edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "usuario",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $usuario = Usuario::findFirstByid($id);

        if (!$usuario) {
            $this->flash->error("O usuário não existe " . $id);

            $this->dispatcher->forward([
                'controller' => "usuario",
                'action' => 'index'
            ]);

            return;
        }

        $usuario->idPessoa = $this->request->getPost("id_pessoa");
        $usuario->rolesName = $this->request->getPost("roles_name");
        $usuario->login = $this->request->getPost("login");
        $usuario->senha = $this->security->hash($this->request->getPost("senha"));
        

        if (!$usuario->save()) {

            foreach ($usuario->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "usuario",
                'action' => 'edit',
                'params' => [$usuario->id]
            ]);

            return;
        }

        $this->flash->success("Usuário editado com sucesso!");

        $this->dispatcher->forward([
            'controller' => "usuario",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a usuario
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $usuario = Usuario::findFirstByid($id);
        if (!$usuario) {
            $this->flash->error("Usuário não localizado!");

            $this->dispatcher->forward([
                'controller' => "usuario",
                'action' => 'index'
            ]);

            return;
        }

        if (!$usuario->delete()) {

            foreach ($usuario->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "usuario",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Usuário deletado com sucesso!");

        $this->dispatcher->forward([
            'controller' => "usuario",
            'action' => "index"
        ]);
    }

}
