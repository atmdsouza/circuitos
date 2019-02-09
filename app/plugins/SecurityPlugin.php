<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 20/11/18
 * Time: 14:48
 */

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

use Circuitos\Models\PhalconRoles;
use Circuitos\Models\PhalconAccessList;

use Auth\Autentica;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl()
    {
        $acl = new AclList();

        $acl->setDefaultAction(Acl::DENY);

        // Cadastro de Perfis
        $resultadoPerfil = PhalconRoles::find();

        foreach ($resultadoPerfil as $res) {
            $r = new Role($res->getName(), $res->getDescription());
            // nome => new role
            $acl->addRole($r);
        }
        // End Cadastro de Perfis

        // Cadastro de Modulos e Ações
        foreach ($resultadoPerfil as $perfil) {
            $resultadoModulo = PhalconAccessList::find("roles_name='{$perfil->getName()}'");
            $modulos = array();
            foreach ($resultadoModulo as $modulo) {
                $resultadoAction = PhalconAccessList::find("roles_name='{$perfil->getName()}' AND resources_name='{$modulo->getResourcesName()}'");
                $actions = array();
                foreach ($resultadoAction as $action) {
                    array_push($actions, $action->getAccessName());
                }
                array_push($modulos, array('nome' => $perfil->getName(), 'resource' => $modulo->getResourcesName(), 'actions' => $actions));
            }

            foreach ($modulos as $mod){
                $acl->addResource(new resource($mod['resource']), $mod['actions']);
                foreach ($mod['actions'] as $acao){
//                        echo $mod['nome'].'-'.$mod['resource'].'-'.$acao.'<br/>';
                    $acl->allow($mod['nome'], $mod['resource'], $acao);
                }
            }
        }

        $this->persistent->acl = $acl;
        return $this->persistent->acl;
    }

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return string
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $auth = new Autentica();
        $role = $auth->getRoles();

        if (!$role)
        {
            $role = "Convidado";
        }

        $controller = ( ($dispatcher->getControllerName()) ? $dispatcher->getControllerName() : "session" );
        $action = ( ($dispatcher->getActionName()) ? $dispatcher->getActionName() : "login" );

        $acl = $this->getAcl();

        if (!$acl->isResource($controller))
        {
            return $this->response->redirect("error/show404");
        }

        $allowed = $acl->isAllowed($role, $controller, $action);

        if (!$allowed)
        {
            if ($this->request->isAjax())
            {
                $this->view->disable();
                unset($this->persistent->acl);
                $this->response->setStatusCode(401);
                $this->response->send();
                return False;
            }
            else
            {
                unset($this->persistent->acl);
                return $this->response->redirect("error/show401");
            }
        }
    }
}