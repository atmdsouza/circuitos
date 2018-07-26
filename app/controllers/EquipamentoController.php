<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class EquipamentoController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for equipamento
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Equipamento', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $equipamento = Equipamento::find($parameters);
        if (count($equipamento) == 0) {
            $this->flash->notice("The search did not find any equipamento");

            $this->dispatcher->forward([
                "controller" => "equipamento",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $equipamento,
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
     * Edits a equipamento
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $equipamento = Equipamento::findFirstByid($id);
            if (!$equipamento) {
                $this->flash->error("equipamento was not found");

                $this->dispatcher->forward([
                    'controller' => "equipamento",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $equipamento->id;

            $this->tag->setDefault("id", $equipamento->id);
            $this->tag->setDefault("id_fabricante", $equipamento->id_fabricante);
            $this->tag->setDefault("id_modelo", $equipamento->id_modelo);
            $this->tag->setDefault("nome", $equipamento->nome);
            $this->tag->setDefault("descricao", $equipamento->descricao);
            $this->tag->setDefault("ativo", $equipamento->ativo);
            
        }
    }

    /**
     * Creates a new equipamento
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "equipamento",
                'action' => 'index'
            ]);

            return;
        }

        $equipamento = new Equipamento();
        $equipamento->idFabricante = $this->request->getPost("id_fabricante");
        $equipamento->idModelo = $this->request->getPost("id_modelo");
        $equipamento->nome = $this->request->getPost("nome");
        $equipamento->descricao = $this->request->getPost("descricao");
        $equipamento->ativo = $this->request->getPost("ativo");
        

        if (!$equipamento->save()) {
            foreach ($equipamento->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "equipamento",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("equipamento was created successfully");

        $this->dispatcher->forward([
            'controller' => "equipamento",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a equipamento edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "equipamento",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $equipamento = Equipamento::findFirstByid($id);

        if (!$equipamento) {
            $this->flash->error("equipamento does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "equipamento",
                'action' => 'index'
            ]);

            return;
        }

        $equipamento->idFabricante = $this->request->getPost("id_fabricante");
        $equipamento->idModelo = $this->request->getPost("id_modelo");
        $equipamento->nome = $this->request->getPost("nome");
        $equipamento->descricao = $this->request->getPost("descricao");
        $equipamento->ativo = $this->request->getPost("ativo");
        

        if (!$equipamento->save()) {

            foreach ($equipamento->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "equipamento",
                'action' => 'edit',
                'params' => [$equipamento->id]
            ]);

            return;
        }

        $this->flash->success("equipamento was updated successfully");

        $this->dispatcher->forward([
            'controller' => "equipamento",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a equipamento
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $equipamento = Equipamento::findFirstByid($id);
        if (!$equipamento) {
            $this->flash->error("equipamento was not found");

            $this->dispatcher->forward([
                'controller' => "equipamento",
                'action' => 'index'
            ]);

            return;
        }

        if (!$equipamento->delete()) {

            foreach ($equipamento->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "equipamento",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("equipamento was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "equipamento",
            'action' => "index"
        ]);
    }

}
