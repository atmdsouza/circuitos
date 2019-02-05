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
use Phalcon\Http\Response as Response;

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
        if (!isset($this->persistent->acl)) {

            $acl = new AclList();

            $acl->setDefaultAction(Acl::DENY);

            // Register roles
            $roles = [
                "Administrador"  => new Role(
                    "Administrador",
                    "Privilégios de Super Administrador. Usuário ROOT!"
                ),
                "Operacional" => new Role(
                    "Operacional",
                    "Privilégios de leitura e escrita para alguns casos. Usuário Operador!"
                ),
                "Analista" => new Role(
                    "Analista",
                    "Privilégios de Usuário de leitura. Usuário de acompanhamento!"
                ),
                "Convidado" => new Role(
                    "Convidado",
                    "Pode logar e ver erros!"
                )
            ];

            foreach ($roles as $role) {
                $acl->addRole($role);
            }

            // Resources para Administrador
            $superResources = [
                "cidade_digital"    => ["index", "formCidadeDigital", "criarCidadeDigital", "editarCidadeDigital", "ativarCidadeDigital", "inativarCidadeDigital", "deletarCidadeDigital", "deletarConectividade"],
                "circuitos"         => ["index", "formCircuitos", "visualizaCircuitos", "criarCircuitos", "editarCircuitos", "movCircuitos", "deletarCircuitos", "unidadeCliente", "modeloFabricante", "equipamentoModelo", "pdfCircuito", "cidadedigitalConectividade", "cidadedigitalAll"],
                "relatorios_gestao" => ["index", "relatorioCustomizado"],
                "cliente"           => ["index", "formCliente", "criarCliente", "editarCliente", "ativarCliente", "inativarCliente", "deletarCliente"],
                "cliente_unidade"   => ["index", "formClienteUnidade", "criarClienteUnidade", "editarClienteUnidade", "ativarClienteUnidade", "inativarClienteUnidade", "deletarClienteUnidade"],
                "core"              => ["ativarPessoa", "inativarPessoa", "deletarPessoa", "deletarPessoaEndereco", "deletarPessoaEmail", "deletarPessoaContato", "deletarPessoaTelefone", "validarEmail", "validarCNPJ", "validarCPF", "completaEndereco", "enviarEmail", "listaCidades"],
                "empresa"           => ["index", "formEmpresa", "criarEmpresa", "editarEmpresa", "deletarEmpresa"],
                "equipamento"       => ["index", "formEquipamento", "criarEquipamento", "editarEquipamento", "ativarEquipamento", "inativarEquipamento", "deletarEquipamento", "carregaModelos"],
                "error"             => ["show401", "show404"],
                "fabricante"        => ["index", "formFabricante", "criarFabricante", "editarFabricante", "ativarFabricante", "inativarFabricante", "deletarFabricante"],
                "index"             => ["index"],
                "lov"               => ["index", "formLov", "criarLov", "editarLov", "deletarLov"],
                "modelo"            => ["index", "formModelo", "criarModelo", "editarModelo", "ativarModelo", "inativarModelo", "deletarModelo"],
                "session"           => ["login", "logout", "sair", "recuperar", "inativo"],
                "usuario"           => ["index", "formUsuario", "validarLogin", "criarUsuario", "editarUsuario", "gerarSenha", "resetarSenha", "ativarUsuario", "inativarUsuario", "deletarUsuario", "alterarSenha", "primeiro", "redirecionaUsuario", "recuperarSenha", "trocar"],
            ];
            foreach ($superResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
                foreach ($actions as $action){
                    $acl->allow("Administrador", $resource, $action);
                }
            }

            // Resources para Operacional
            $operacionalResources = [
                "cidade_digital"    => ["index", "formCidadeDigital", "criarCidadeDigital", "editarCidadeDigital", "ativarCidadeDigital", "inativarCidadeDigital"],
                "circuitos"         => ["index", "formCircuitos", "visualizaCircuitos", "criarCircuitos", "editarCircuitos", "movCircuitos", "unidadeCliente", "modeloFabricante", "equipamentoModelo", "pdfCircuito", "cidadedigitalConectividade", "cidadedigitalAll"],
                "relatorios_gestao" => ["index", "relatorioCustomizado"],
                "cliente"           => ["index", "formCliente", "criarCliente", "editarCliente", "ativarCliente", "inativarCliente"],
                "cliente_unidade"   => ["index", "formClienteUnidade", "criarClienteUnidade", "editarClienteUnidade", "ativarClienteUnidade", "inativarClienteUnidade"],
                "core"              => ["ativarPessoa", "inativarPessoa", "deletarPessoaEndereco", "deletarPessoaEmail", "deletarPessoaContato", "deletarPessoaTelefone", "validarEmail", "validarCNPJ", "validarCPF", "completaEndereco", "enviarEmail", "listaCidades"],
//                "empresa"           => ["index", "formEmpresa", "criarEmpresa", "editarEmpresa", "deletarEmpresa"],
                "equipamento"       => ["index", "formEquipamento", "criarEquipamento", "editarEquipamento", "ativarEquipamento", "inativarEquipamento", "carregaModelos"],
                "error"             => ["show401", "show404"],
                "fabricante"        => ["index", "formFabricante", "criarFabricante", "editarFabricante", "ativarFabricante", "inativarFabricante"],
                "index"             => ["index"],
                "lov"               => ["index", "formLov", "criarLov", "editarLov"],
                "modelo"            => ["index", "formModelo", "criarModelo", "editarModelo", "ativarModelo", "inativarModelo"],
                "session"           => ["login", "logout", "sair", "recuperar", "inativo"],
                "usuario"           => ["gerarSenha", "resetarSenha", "alterarSenha", "primeiro", "redirecionaUsuario", "recuperarSenha", "trocar"],
            ];
            foreach ($operacionalResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
                foreach ($actions as $action){
                    $acl->allow("Operacional", $resource, $action);
                }
            }

            // Resources para Analista
            $analistaResources = [
                "cidade_digital"    => ["index", "formCidadeDigital"],
                "circuitos"         => ["index", "formCircuitos", "visualizaCircuitos", "pdfCircuito"],
                "relatorios_gestao" => ["index", "relatorioCustomizado", "listaCidades"],
                "cliente"           => ["index", "formCliente"],
                "cliente_unidade"   => ["index", "formClienteUnidade"],
                "core"              => ["enviarEmail", "listaCidades"],
//                "empresa"           => ["index", "formEmpresa", "criarEmpresa", "editarEmpresa", "deletarEmpresa"],
                "equipamento"       => ["index", "formEquipamento"],
                "error"             => ["show401", "show404"],
                "fabricante"        => ["index", "formFabricante"],
                "index"             => ["index"],
//                "lov"               => ["index", "formLov", "criarLov", "editarLov", "deletarLov"],
                "modelo"            => ["index", "formModelo"],
                "session"           => ["login", "logout", "sair", "recuperar", "inativo"],
                "usuario"           => ["gerarSenha", "resetarSenha", "alterarSenha", "primeiro", "redirecionaUsuario", "recuperarSenha", "trocar"],
            ];
            foreach ($analistaResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
                foreach ($actions as $action){
                    $acl->allow("Analista", $resource, $action);
                }
            }

            // Resources para Convidado
            $convidadoResources = [
                "session"   => ["login", "logout", "sair", "recuperar", "inativo"],
                "usuario"   => ["gerarSenha", "resetarSenha", "alterarSenha", "primeiro", "redirecionaUsuario", "recuperarSenha", "trocar"],
                "error"     => ["show401", "show404"]
            ];
            foreach ($convidadoResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
                foreach ($actions as $action){
                    $acl->allow("Convidado", $resource, $action);
                }
            }

            //The acl is stored in session, APC would be useful here too
            $this->persistent->acl = $acl;
        }
//		else {
//			unset($this->persistent->acl);
//		}

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