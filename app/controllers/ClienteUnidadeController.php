<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ClienteUnidadeController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for cliente_unidade
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'ClienteUnidade', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $cliente_unidade = ClienteUnidade::find($parameters);
        if (count($cliente_unidade) == 0) {
            $this->flash->notice("The search did not find any cliente_unidade");

            $this->dispatcher->forward([
                "controller" => "cliente_unidade",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $cliente_unidade,
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
     * Edits a cliente_unidade
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $cliente_unidade = ClienteUnidade::findFirstByid($id);
            if (!$cliente_unidade) {
                $this->flash->error("cliente_unidade was not found");

                $this->dispatcher->forward([
                    'controller' => "cliente_unidade",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $cliente_unidade->id;

            $this->tag->setDefault("id", $cliente_unidade->id);
            $this->tag->setDefault("id_pessoa", $cliente_unidade->id_pessoa);
            $this->tag->setDefault("id_cliente", $cliente_unidade->id_cliente);
            
        }
    }

    /**
     * Creates a new cliente_unidade
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "cliente_unidade",
                'action' => 'index'
            ]);

            return;
        }

        $cliente_unidade = new ClienteUnidade();
        $cliente_unidade->idPessoa = $this->request->getPost("id_pessoa");
        $cliente_unidade->idCliente = $this->request->getPost("id_cliente");
        

        if (!$cliente_unidade->save()) {
            foreach ($cliente_unidade->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "cliente_unidade",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("cliente_unidade was created successfully");

        $this->dispatcher->forward([
            'controller' => "cliente_unidade",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a cliente_unidade edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "cliente_unidade",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $cliente_unidade = ClienteUnidade::findFirstByid($id);

        if (!$cliente_unidade) {
            $this->flash->error("cliente_unidade does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "cliente_unidade",
                'action' => 'index'
            ]);

            return;
        }

        $cliente_unidade->idPessoa = $this->request->getPost("id_pessoa");
        $cliente_unidade->idCliente = $this->request->getPost("id_cliente");
        

        if (!$cliente_unidade->save()) {

            foreach ($cliente_unidade->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "cliente_unidade",
                'action' => 'edit',
                'params' => [$cliente_unidade->id]
            ]);

            return;
        }

        $this->flash->success("cliente_unidade was updated successfully");

        $this->dispatcher->forward([
            'controller' => "cliente_unidade",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a cliente_unidade
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $cliente_unidade = ClienteUnidade::findFirstByid($id);
        if (!$cliente_unidade) {
            $this->flash->error("cliente_unidade was not found");

            $this->dispatcher->forward([
                'controller' => "cliente_unidade",
                'action' => 'index'
            ]);

            return;
        }

        if (!$cliente_unidade->delete()) {

            foreach ($cliente_unidade->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "cliente_unidade",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("cliente_unidade was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "cliente_unidade",
            'action' => "index"
        ]);
    }

}
