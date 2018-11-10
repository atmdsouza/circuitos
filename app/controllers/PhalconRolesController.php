<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PhalconRolesController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for phalcon_roles
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'PhalconRoles', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "name";

        $phalcon_roles = PhalconRoles::find($parameters);
        if (count($phalcon_roles) == 0) {
            $this->flash->notice("The search did not find any phalcon_roles");

            $this->dispatcher->forward([
                "controller" => "phalcon_roles",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $phalcon_roles,
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
     * Edits a phalcon_role
     *
     * @param string $name
     */
    public function editAction($name)
    {
        if (!$this->request->isPost()) {

            $phalcon_role = PhalconRoles::findFirstByname($name);
            if (!$phalcon_role) {
                $this->flash->error("phalcon_role was not found");

                $this->dispatcher->forward([
                    'controller' => "phalcon_roles",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->name = $phalcon_role->name;

            $this->tag->setDefault("name", $phalcon_role->name);
            $this->tag->setDefault("description", $phalcon_role->description);
            
        }
    }

    /**
     * Creates a new phalcon_role
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "phalcon_roles",
                'action' => 'index'
            ]);

            return;
        }

        $phalcon_role = new PhalconRoles();
        $phalcon_role->name = $this->request->getPost("name");
        $phalcon_role->description = $this->request->getPost("description");
        

        if (!$phalcon_role->save()) {
            foreach ($phalcon_role->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "phalcon_roles",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("phalcon_role was created successfully");

        $this->dispatcher->forward([
            'controller' => "phalcon_roles",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a phalcon_role edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "phalcon_roles",
                'action' => 'index'
            ]);

            return;
        }

        $name = $this->request->getPost("name");
        $phalcon_role = PhalconRoles::findFirstByname($name);

        if (!$phalcon_role) {
            $this->flash->error("phalcon_role does not exist " . $name);

            $this->dispatcher->forward([
                'controller' => "phalcon_roles",
                'action' => 'index'
            ]);

            return;
        }

        $phalcon_role->name = $this->request->getPost("name");
        $phalcon_role->description = $this->request->getPost("description");
        

        if (!$phalcon_role->save()) {

            foreach ($phalcon_role->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "phalcon_roles",
                'action' => 'edit',
                'params' => [$phalcon_role->name]
            ]);

            return;
        }

        $this->flash->success("phalcon_role was updated successfully");

        $this->dispatcher->forward([
            'controller' => "phalcon_roles",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a phalcon_role
     *
     * @param string $name
     */
    public function deleteAction($name)
    {
        $phalcon_role = PhalconRoles::findFirstByname($name);
        if (!$phalcon_role) {
            $this->flash->error("phalcon_role was not found");

            $this->dispatcher->forward([
                'controller' => "phalcon_roles",
                'action' => 'index'
            ]);

            return;
        }

        if (!$phalcon_role->delete()) {

            foreach ($phalcon_role->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "phalcon_roles",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("phalcon_role was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "phalcon_roles",
            'action' => "index"
        ]);
    }

}
