<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ClienteController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for cliente
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Cliente', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $cliente = Cliente::find($parameters);
        if (count($cliente) == 0) {
            $this->flash->notice("The search did not find any cliente");

            $this->dispatcher->forward([
                "controller" => "cliente",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $cliente,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a cliente
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $cliente = Cliente::findFirstByid($id);
            if (!$cliente) {
                $this->flash->error("cliente was not found");

                $this->dispatcher->forward([
                    'controller' => "cliente",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $cliente->id;

            $this->tag->setDefault("id", $cliente->id);
            $this->tag->setDefault("id_pessoa", $cliente->id_pessoa);
            $this->tag->setDefault("id_tipocliente", $cliente->id_tipocliente);
            
        }
    }

    /**
     * Creates a new cliente
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "cliente",
                'action' => 'index'
            ]);

            return;
        }

        $cliente = new Cliente();
        $cliente->idPessoa = $this->request->getPost("id_pessoa");
        $cliente->idTipocliente = $this->request->getPost("id_tipocliente");
        

        if (!$cliente->save()) {
            foreach ($cliente->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "cliente",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("cliente was created successfully");

        $this->dispatcher->forward([
            'controller' => "cliente",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a cliente edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "cliente",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $cliente = Cliente::findFirstByid($id);

        if (!$cliente) {
            $this->flash->error("cliente does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "cliente",
                'action' => 'index'
            ]);

            return;
        }

        $cliente->idPessoa = $this->request->getPost("id_pessoa");
        $cliente->idTipocliente = $this->request->getPost("id_tipocliente");
        

        if (!$cliente->save()) {

            foreach ($cliente->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "cliente",
                'action' => 'edit',
                'params' => [$cliente->id]
            ]);

            return;
        }

        $this->flash->success("cliente was updated successfully");

        $this->dispatcher->forward([
            'controller' => "cliente",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a cliente
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $cliente = Cliente::findFirstByid($id);
        if (!$cliente) {
            $this->flash->error("cliente was not found");

            $this->dispatcher->forward([
                'controller' => "cliente",
                'action' => 'index'
            ]);

            return;
        }

        if (!$cliente->delete()) {

            foreach ($cliente->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "cliente",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("cliente was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "cliente",
            'action' => "index"
        ]);
    }

}
