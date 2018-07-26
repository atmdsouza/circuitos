<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PhalconResourcesController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for phalcon_resources
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'PhalconResources', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "name";

        $phalcon_resources = PhalconResources::find($parameters);
        if (count($phalcon_resources) == 0) {
            $this->flash->notice("The search did not find any phalcon_resources");

            $this->dispatcher->forward([
                "controller" => "phalcon_resources",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $phalcon_resources,
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
     * Edits a phalcon_resource
     *
     * @param string $name
     */
    public function editAction($name)
    {
        if (!$this->request->isPost()) {

            $phalcon_resource = PhalconResources::findFirstByname($name);
            if (!$phalcon_resource) {
                $this->flash->error("phalcon_resource was not found");

                $this->dispatcher->forward([
                    'controller' => "phalcon_resources",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->name = $phalcon_resource->name;

            $this->tag->setDefault("name", $phalcon_resource->name);
            $this->tag->setDefault("description", $phalcon_resource->description);
            
        }
    }

    /**
     * Creates a new phalcon_resource
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "phalcon_resources",
                'action' => 'index'
            ]);

            return;
        }

        $phalcon_resource = new PhalconResources();
        $phalcon_resource->name = $this->request->getPost("name");
        $phalcon_resource->description = $this->request->getPost("description");
        

        if (!$phalcon_resource->save()) {
            foreach ($phalcon_resource->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "phalcon_resources",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("phalcon_resource was created successfully");

        $this->dispatcher->forward([
            'controller' => "phalcon_resources",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a phalcon_resource edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "phalcon_resources",
                'action' => 'index'
            ]);

            return;
        }

        $name = $this->request->getPost("name");
        $phalcon_resource = PhalconResources::findFirstByname($name);

        if (!$phalcon_resource) {
            $this->flash->error("phalcon_resource does not exist " . $name);

            $this->dispatcher->forward([
                'controller' => "phalcon_resources",
                'action' => 'index'
            ]);

            return;
        }

        $phalcon_resource->name = $this->request->getPost("name");
        $phalcon_resource->description = $this->request->getPost("description");
        

        if (!$phalcon_resource->save()) {

            foreach ($phalcon_resource->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "phalcon_resources",
                'action' => 'edit',
                'params' => [$phalcon_resource->name]
            ]);

            return;
        }

        $this->flash->success("phalcon_resource was updated successfully");

        $this->dispatcher->forward([
            'controller' => "phalcon_resources",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a phalcon_resource
     *
     * @param string $name
     */
    public function deleteAction($name)
    {
        $phalcon_resource = PhalconResources::findFirstByname($name);
        if (!$phalcon_resource) {
            $this->flash->error("phalcon_resource was not found");

            $this->dispatcher->forward([
                'controller' => "phalcon_resources",
                'action' => 'index'
            ]);

            return;
        }

        if (!$phalcon_resource->delete()) {

            foreach ($phalcon_resource->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "phalcon_resources",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("phalcon_resource was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "phalcon_resources",
            'action' => "index"
        ]);
    }

}
