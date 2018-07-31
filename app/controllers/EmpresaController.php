<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class EmpresaController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for empresa
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Empresa', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $empresa = Empresa::find($parameters);
        if (count($empresa) == 0) {
            $this->flash->notice("The search did not find any empresa");

            $this->dispatcher->forward([
                "controller" => "empresa",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $empresa,
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
     * Edits a empresa
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $empresa = Empresa::findFirstByid($id);
            if (!$empresa) {
                $this->flash->error("empresa was not found");

                $this->dispatcher->forward([
                    'controller' => "empresa",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $empresa->id;

            $this->tag->setDefault("id", $empresa->id);
            $this->tag->setDefault("id_pessoa", $empresa->id_pessoa);
            
        }
    }

    /**
     * Creates a new empresa
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "empresa",
                'action' => 'index'
            ]);

            return;
        }

        $empresa = new Empresa();
        $empresa->idPessoa = $this->request->getPost("id_pessoa");
        

        if (!$empresa->save()) {
            foreach ($empresa->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "empresa",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("empresa was created successfully");

        $this->dispatcher->forward([
            'controller' => "empresa",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a empresa edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "empresa",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $empresa = Empresa::findFirstByid($id);

        if (!$empresa) {
            $this->flash->error("empresa does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "empresa",
                'action' => 'index'
            ]);

            return;
        }

        $empresa->idPessoa = $this->request->getPost("id_pessoa");
        

        if (!$empresa->save()) {

            foreach ($empresa->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "empresa",
                'action' => 'edit',
                'params' => [$empresa->id]
            ]);

            return;
        }

        $this->flash->success("empresa was updated successfully");

        $this->dispatcher->forward([
            'controller' => "empresa",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a empresa
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $empresa = Empresa::findFirstByid($id);
        if (!$empresa) {
            $this->flash->error("empresa was not found");

            $this->dispatcher->forward([
                'controller' => "empresa",
                'action' => 'index'
            ]);

            return;
        }

        if (!$empresa->delete()) {

            foreach ($empresa->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "empresa",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("empresa was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "empresa",
            'action' => "index"
        ]);
    }

}
