<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class CircuitosController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for circuitos
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Circuitos', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $circuitos = Circuitos::find($parameters);
        if (count($circuitos) == 0) {
            $this->flash->notice("The search did not find any circuitos");

            $this->dispatcher->forward([
                "controller" => "circuitos",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $circuitos,
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
     * Edits a circuito
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $circuito = Circuitos::findFirstByid($id);
            if (!$circuito) {
                $this->flash->error("circuito was not found");

                $this->dispatcher->forward([
                    'controller' => "circuitos",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $circuito->id;

            $this->tag->setDefault("id", $circuito->id);
            $this->tag->setDefault("id_cliente", $circuito->id_cliente);
            $this->tag->setDefault("id_cliente_unidade", $circuito->id_cliente_unidade);
            $this->tag->setDefault("id_equipamento", $circuito->id_equipamento);
            $this->tag->setDefault("id_contrato", $circuito->id_contrato);
            $this->tag->setDefault("id_status", $circuito->id_status);
            $this->tag->setDefault("id_cluster", $circuito->id_cluster);
            $this->tag->setDefault("id_tipounidade", $circuito->id_tipounidade);
            $this->tag->setDefault("id_funcao", $circuito->id_funcao);
            $this->tag->setDefault("id_enlace", $circuito->id_enlace);
            $this->tag->setDefault("id_usuario_criacao", $circuito->id_usuario_criacao);
            $this->tag->setDefault("id_usuario_atualizacao", $circuito->id_usuario_atualizacao);
            $this->tag->setDefault("designacao", $circuito->designacao);
            $this->tag->setDefault("uf", $circuito->uf);
            $this->tag->setDefault("cidade", $circuito->cidade);
            $this->tag->setDefault("vlan", $circuito->vlan);
            $this->tag->setDefault("ccode", $circuito->ccode);
            $this->tag->setDefault("ip_redelocal", $circuito->ip_redelocal);
            $this->tag->setDefault("ip_gerencia", $circuito->ip_gerencia);
            $this->tag->setDefault("tag", $circuito->tag);
            $this->tag->setDefault("banda", $circuito->banda);
            $this->tag->setDefault("observacao", $circuito->observacao);
            $this->tag->setDefault("data_ativacao", $circuito->data_ativacao);
            $this->tag->setDefault("data_atualizacao", $circuito->data_atualizacao);
            $this->tag->setDefault("ativo", $circuito->ativo);
            
        }
    }

    /**
     * Creates a new circuito
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "circuitos",
                'action' => 'index'
            ]);

            return;
        }

        $circuito = new Circuitos();
        $circuito->idCliente = $this->request->getPost("id_cliente");
        $circuito->idClienteUnidade = $this->request->getPost("id_cliente_unidade");
        $circuito->idEquipamento = $this->request->getPost("id_equipamento");
        $circuito->idContrato = $this->request->getPost("id_contrato");
        $circuito->idStatus = $this->request->getPost("id_status");
        $circuito->idCluster = $this->request->getPost("id_cluster");
        $circuito->idTipounidade = $this->request->getPost("id_tipounidade");
        $circuito->idFuncao = $this->request->getPost("id_funcao");
        $circuito->idEnlace = $this->request->getPost("id_enlace");
        $circuito->idUsuarioCriacao = $this->request->getPost("id_usuario_criacao");
        $circuito->idUsuarioAtualizacao = $this->request->getPost("id_usuario_atualizacao");
        $circuito->designacao = $this->request->getPost("designacao");
        $circuito->uf = $this->request->getPost("uf");
        $circuito->cidade = $this->request->getPost("cidade");
        $circuito->vlan = $this->request->getPost("vlan");
        $circuito->ccode = $this->request->getPost("ccode");
        $circuito->ipRedelocal = $this->request->getPost("ip_redelocal");
        $circuito->ipGerencia = $this->request->getPost("ip_gerencia");
        $circuito->tag = $this->request->getPost("tag");
        $circuito->banda = $this->request->getPost("banda");
        $circuito->observacao = $this->request->getPost("observacao");
        $circuito->dataAtivacao = $this->request->getPost("data_ativacao");
        $circuito->dataAtualizacao = $this->request->getPost("data_atualizacao");
        $circuito->ativo = $this->request->getPost("ativo");
        

        if (!$circuito->save()) {
            foreach ($circuito->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "circuitos",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("circuito was created successfully");

        $this->dispatcher->forward([
            'controller' => "circuitos",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a circuito edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "circuitos",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $circuito = Circuitos::findFirstByid($id);

        if (!$circuito) {
            $this->flash->error("circuito does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "circuitos",
                'action' => 'index'
            ]);

            return;
        }

        $circuito->idCliente = $this->request->getPost("id_cliente");
        $circuito->idClienteUnidade = $this->request->getPost("id_cliente_unidade");
        $circuito->idEquipamento = $this->request->getPost("id_equipamento");
        $circuito->idContrato = $this->request->getPost("id_contrato");
        $circuito->idStatus = $this->request->getPost("id_status");
        $circuito->idCluster = $this->request->getPost("id_cluster");
        $circuito->idTipounidade = $this->request->getPost("id_tipounidade");
        $circuito->idFuncao = $this->request->getPost("id_funcao");
        $circuito->idEnlace = $this->request->getPost("id_enlace");
        $circuito->idUsuarioCriacao = $this->request->getPost("id_usuario_criacao");
        $circuito->idUsuarioAtualizacao = $this->request->getPost("id_usuario_atualizacao");
        $circuito->designacao = $this->request->getPost("designacao");
        $circuito->uf = $this->request->getPost("uf");
        $circuito->cidade = $this->request->getPost("cidade");
        $circuito->vlan = $this->request->getPost("vlan");
        $circuito->ccode = $this->request->getPost("ccode");
        $circuito->ipRedelocal = $this->request->getPost("ip_redelocal");
        $circuito->ipGerencia = $this->request->getPost("ip_gerencia");
        $circuito->tag = $this->request->getPost("tag");
        $circuito->banda = $this->request->getPost("banda");
        $circuito->observacao = $this->request->getPost("observacao");
        $circuito->dataAtivacao = $this->request->getPost("data_ativacao");
        $circuito->dataAtualizacao = $this->request->getPost("data_atualizacao");
        $circuito->ativo = $this->request->getPost("ativo");
        

        if (!$circuito->save()) {

            foreach ($circuito->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "circuitos",
                'action' => 'edit',
                'params' => [$circuito->id]
            ]);

            return;
        }

        $this->flash->success("circuito was updated successfully");

        $this->dispatcher->forward([
            'controller' => "circuitos",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a circuito
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $circuito = Circuitos::findFirstByid($id);
        if (!$circuito) {
            $this->flash->error("circuito was not found");

            $this->dispatcher->forward([
                'controller' => "circuitos",
                'action' => 'index'
            ]);

            return;
        }

        if (!$circuito->delete()) {

            foreach ($circuito->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "circuitos",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("circuito was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "circuitos",
            'action' => "index"
        ]);
    }

}
