<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ModeloController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for modelo
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Modelo', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $modelo = Modelo::find($parameters);
        if (count($modelo) == 0) {
            $this->flash->notice("The search did not find any modelo");

            $this->dispatcher->forward([
                "controller" => "modelo",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $modelo,
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
     * Edits a modelo
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $modelo = Modelo::findFirstByid($id);
            if (!$modelo) {
                $this->flash->error("modelo was not found");

                $this->dispatcher->forward([
                    'controller' => "modelo",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $modelo->id;

            $this->tag->setDefault("id", $modelo->id);
            $this->tag->setDefault("id_fabricante", $modelo->id_fabricante);
            $this->tag->setDefault("modelo", $modelo->modelo);
            $this->tag->setDefault("descricao", $modelo->descricao);
            $this->tag->setDefault("ativo", $modelo->ativo);
            
        }
    }

    /**
     * Creates a new modelo
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "modelo",
                'action' => 'index'
            ]);

            return;
        }

        $modelo = new Modelo();
        $modelo->idFabricante = $this->request->getPost("id_fabricante");
        $modelo->modelo = $this->request->getPost("modelo");
        $modelo->descricao = $this->request->getPost("descricao");
        $modelo->ativo = $this->request->getPost("ativo");
        

        if (!$modelo->save()) {
            foreach ($modelo->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "modelo",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("modelo was created successfully");

        $this->dispatcher->forward([
            'controller' => "modelo",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a modelo edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "modelo",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $modelo = Modelo::findFirstByid($id);

        if (!$modelo) {
            $this->flash->error("modelo does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "modelo",
                'action' => 'index'
            ]);

            return;
        }

        $modelo->idFabricante = $this->request->getPost("id_fabricante");
        $modelo->modelo = $this->request->getPost("modelo");
        $modelo->descricao = $this->request->getPost("descricao");
        $modelo->ativo = $this->request->getPost("ativo");
        

        if (!$modelo->save()) {

            foreach ($modelo->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "modelo",
                'action' => 'edit',
                'params' => [$modelo->id]
            ]);

            return;
        }

        $this->flash->success("modelo was updated successfully");

        $this->dispatcher->forward([
            'controller' => "modelo",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a modelo
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $modelo = Modelo::findFirstByid($id);
        if (!$modelo) {
            $this->flash->error("modelo was not found");

            $this->dispatcher->forward([
                'controller' => "modelo",
                'action' => 'index'
            ]);

            return;
        }

        if (!$modelo->delete()) {

            foreach ($modelo->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "modelo",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("modelo was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "modelo",
            'action' => "index"
        ]);
    }

}
