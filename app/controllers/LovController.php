<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class LovController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for lov
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Lov', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $lov = Lov::find($parameters);
        if (count($lov) == 0) {
            $this->flash->notice("The search did not find any lov");

            $this->dispatcher->forward([
                "controller" => "lov",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $lov,
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
     * Edits a lov
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $lov = Lov::findFirstByid($id);
            if (!$lov) {
                $this->flash->error("lov was not found");

                $this->dispatcher->forward([
                    'controller' => "lov",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $lov->id;

            $this->tag->setDefault("id", $lov->id);
            $this->tag->setDefault("tipo", $lov->tipo);
            $this->tag->setDefault("descricao", $lov->descricao);
            $this->tag->setDefault("codigoespecifico", $lov->codigoespecifico);
            $this->tag->setDefault("valor", $lov->valor);
            $this->tag->setDefault("duracao", $lov->duracao);
            
        }
    }

    /**
     * Creates a new lov
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "lov",
                'action' => 'index'
            ]);

            return;
        }

        $lov = new Lov();
        $lov->tipo = $this->request->getPost("tipo");
        $lov->descricao = $this->request->getPost("descricao");
        $lov->codigoespecifico = $this->request->getPost("codigoespecifico");
        $lov->valor = $this->request->getPost("valor");
        $lov->duracao = $this->request->getPost("duracao");
        

        if (!$lov->save()) {
            foreach ($lov->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "lov",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("lov was created successfully");

        $this->dispatcher->forward([
            'controller' => "lov",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a lov edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "lov",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $lov = Lov::findFirstByid($id);

        if (!$lov) {
            $this->flash->error("lov does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "lov",
                'action' => 'index'
            ]);

            return;
        }

        $lov->tipo = $this->request->getPost("tipo");
        $lov->descricao = $this->request->getPost("descricao");
        $lov->codigoespecifico = $this->request->getPost("codigoespecifico");
        $lov->valor = $this->request->getPost("valor");
        $lov->duracao = $this->request->getPost("duracao");
        

        if (!$lov->save()) {

            foreach ($lov->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "lov",
                'action' => 'edit',
                'params' => [$lov->id]
            ]);

            return;
        }

        $this->flash->success("lov was updated successfully");

        $this->dispatcher->forward([
            'controller' => "lov",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a lov
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $lov = Lov::findFirstByid($id);
        if (!$lov) {
            $this->flash->error("lov was not found");

            $this->dispatcher->forward([
                'controller' => "lov",
                'action' => 'index'
            ]);

            return;
        }

        if (!$lov->delete()) {

            foreach ($lov->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "lov",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("lov was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "lov",
            'action' => "index"
        ]);
    }

}
