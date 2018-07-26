<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class FabricanteController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for fabricante
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Fabricante', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $fabricante = Fabricante::find($parameters);
        if (count($fabricante) == 0) {
            $this->flash->notice("The search did not find any fabricante");

            $this->dispatcher->forward([
                "controller" => "fabricante",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $fabricante,
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
     * Edits a fabricante
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $fabricante = Fabricante::findFirstByid($id);
            if (!$fabricante) {
                $this->flash->error("fabricante was not found");

                $this->dispatcher->forward([
                    'controller' => "fabricante",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $fabricante->id;

            $this->tag->setDefault("id", $fabricante->id);
            $this->tag->setDefault("id_pessoa", $fabricante->id_pessoa);
            
        }
    }

    /**
     * Creates a new fabricante
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "fabricante",
                'action' => 'index'
            ]);

            return;
        }

        $fabricante = new Fabricante();
        $fabricante->idPessoa = $this->request->getPost("id_pessoa");
        

        if (!$fabricante->save()) {
            foreach ($fabricante->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "fabricante",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("fabricante was created successfully");

        $this->dispatcher->forward([
            'controller' => "fabricante",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a fabricante edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "fabricante",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $fabricante = Fabricante::findFirstByid($id);

        if (!$fabricante) {
            $this->flash->error("fabricante does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "fabricante",
                'action' => 'index'
            ]);

            return;
        }

        $fabricante->idPessoa = $this->request->getPost("id_pessoa");
        

        if (!$fabricante->save()) {

            foreach ($fabricante->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "fabricante",
                'action' => 'edit',
                'params' => [$fabricante->id]
            ]);

            return;
        }

        $this->flash->success("fabricante was updated successfully");

        $this->dispatcher->forward([
            'controller' => "fabricante",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a fabricante
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $fabricante = Fabricante::findFirstByid($id);
        if (!$fabricante) {
            $this->flash->error("fabricante was not found");

            $this->dispatcher->forward([
                'controller' => "fabricante",
                'action' => 'index'
            ]);

            return;
        }

        if (!$fabricante->delete()) {

            foreach ($fabricante->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "fabricante",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("fabricante was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "fabricante",
            'action' => "index"
        ]);
    }

}
