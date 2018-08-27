<?php

namespace Circuitos\Controllers;
 
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Controllers\ControllerBase;

use Circuitos\Models\Circuitos;
use Circuitos\Models\Cliente;
use Circuitos\Models\ClienteUnidade;
use Circuitos\Models\Usuario;
use Circuitos\Models\Fabricante;
use Circuitos\Models\Modelo;
use Circuitos\Models\Equipamento;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Util;
use Util\TokenManager;
use Circuitos\Models\PessoaEndereco;

class CircuitosController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        //Voltando o usuário não autenticado para a página de login
        $auth = new Autentica();
        $identity = $auth->getIdentity();
        if (!is_array($identity)) {
            return $this->response->redirect('session/login');
        }
        $this->view->user = $identity["nome"];
        //CSRFToken
        $this->tokenManager = new TokenManager();
        if (!$this->tokenManager->doesUserHaveToken('User')) {
            $this->tokenManager->generateToken('User');
        }
        $this->view->token = $this->tokenManager->getToken('User');
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $numberPage = 1;
        $circuitos = Circuitos::find("excluido = 0");
        $statuscircuito = Lov::find(array(
            "tipo=6",
            "order" => "descricao"
        ));
        $tipounindade = Lov::find(array(
            "tipo = 1",
            "order" => "descricao"
        ));
        $usacontrato = Lov::find("tipo=2");
        $funcao = Lov::find(array(
            "tipo = 3",
            "order" => "descricao"
        ));
        $enlace = Lov::find(array(
            "tipo = 7",
            "order" => "descricao"
        ));
        $cluster = Lov::find(array(
            "tipo = 14",
            "order" => "descricao"
        ));
        $banda = Lov::find(array(
            "tipo = 17"
        ));
        $tipomovimento = Lov::find(array(
            "tipo = 16",
            "order" => "descricao"
        ));
        $clientes = Cliente::buscaClienteAtivo();
        $unidades = ClienteUnidade::buscaUnidadeAtiva();
        $fabricantes = Fabricante::buscaFabricanteAtivo();
        $modelos = Modelo::find();
        $equipamentos = Equipamento::find();
        $paginator = new Paginator([
            'data' => $circuitos,
            'limit'=> 500,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->clientes = $clientes;
        $this->view->unidades = $unidades;
        $this->view->fabricantes = $fabricantes;
        $this->view->modelos = $modelos;
        $this->view->equipamentos = $equipamentos;
        $this->view->statuscircuito = $statuscircuito;
        $this->view->tipounindade = $tipounindade;
        $this->view->usacontrato = $usacontrato;
        $this->view->funcao = $funcao;
        $this->view->enlace = $enlace;
        $this->view->cluster = $cluster;
        $this->view->banda = $banda;
        $this->view->tipomovimento = $tipomovimento;
    }

    public function formCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $util = new Util();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $circuitos = Circuitos::findFirst("id={$dados["id_circuitos"]}");
        $dados = array(
            'id' => $circuitos->id,
            'id_cliente' => $circuitos->id_cliente,
            'id_cliente_unidade' => $circuitos->id_cliente_unidade,
            'id_equipamento' => $circuitos->id_equipamento,
            'id_contrato' => $circuitos->id_contrato,
            'id_status' => $circuitos->id_status,
            'id_cluster' => $circuitos->id_cluster,
            'id_tipounidade' => $circuitos->id_tipounidade,
            'id_funcao' => $circuitos->id_funcao,
            'id_enlace' => $circuitos->id_enlace,
            'id_usuario_criacao' => $circuitos->id_usuario_criacao,
            'id_usuario_atualizacao' => $circuitos->id_usuario_atualizacao,
            'designacao' => $circuitos->designacao,
            'uf' => $circuitos->uf,
            'cidade' => $circuitos->cidade,
            'vlan' => $circuitos->vlan,
            'ccode' => $circuitos->ccode,
            'ip_redelocal' => $circuitos->ip_redelocal,
            'ip_gerencia' => $circuitos->ip_gerencia,
            'tag' => $circuitos->tag,
            'id_banda' => $circuitos->id_banda,
            'observacao' => $circuitos->observacao,
            'data_ativacao' => $circuitos->data_ativacao,
            'data_atualizacao' => $circuitos->data_atualizacao,
            'excluido' => $circuitos->excluido
        );
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function visualizaCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $util = new Util();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $circuitos = Circuitos::findFirst("id={$dados["id_circuitos"]}");
        $dados = array(
            'id' => $circuitos->id,
            'id_cliente' => $circuitos->id_cliente,
            'id_cliente_unidade' => $circuitos->id_cliente_unidade,
            'id_equipamento' => $circuitos->id_equipamento,
            'id_contrato' => $circuitos->id_contrato,
            'id_status' => $circuitos->id_status,
            'id_cluster' => $circuitos->id_cluster,
            'id_tipounidade' => $circuitos->id_tipounidade,
            'id_funcao' => $circuitos->id_funcao,
            'id_enlace' => $circuitos->id_enlace,
            'id_usuario_criacao' => $circuitos->id_usuario_criacao,
            'id_usuario_atualizacao' => $circuitos->id_usuario_atualizacao,
            'designacao' => $circuitos->designacao,
            'uf' => $circuitos->uf,
            'cidade' => $circuitos->cidade,
            'vlan' => $circuitos->vlan,
            'ccode' => $circuitos->ccode,
            'ip_redelocal' => $circuitos->ip_redelocal,
            'ip_gerencia' => $circuitos->ip_gerencia,
            'tag' => $circuitos->tag,
            'id_banda' => $circuitos->id_banda,
            'observacao' => $circuitos->observacao,
            'data_ativacao' => $circuitos->data_ativacao,
            'data_atualizacao' => $circuitos->data_atualizacao,
            'excluido' => $circuitos->excluido
        );
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function criarCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $auth = new Autentica();
        $util = new Util();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $identity = $auth->getIdentity();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados["dados"], $params);
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $cliente = Cliente::findFirst("id={$params["id_cliente"]}");
                $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$cliente->id_pessoa}");
                $unidade = (isset($params["id_cliente_unidade"])) ? $params["id_cliente_unidade"] : null ;
                $uf = ($pessoaendereco->sigla_estado) ? $pessoaendereco->sigla_estado : null;
                $cidade = ($pessoaendereco->cidade) ? $pessoaendereco->cidade : null;
                $circuitos = new Circuitos();
                $circuitos->setTransaction($transaction);
                $circuitos->id_cliente = $params["id_cliente"];
                $circuitos->id_cliente_unidade = $unidade;
                $circuitos->id_equipamento = $params["id_equipamento"];
                $circuitos->id_contrato = $params["id_contrato"];
                $circuitos->id_status = 34;//Ativo por Default
                $circuitos->id_cluster = $params["id_cluster"];
                $circuitos->id_tipounidade = $params["id_tipounidade"];
                $circuitos->id_funcao = $params["id_funcao"];
                $circuitos->id_enlace = $params["id_enlace"];
                $circuitos->id_usuario_criacao = $identity["id"];
                $circuitos->designacao = $params["designacao"];
                $circuitos->uf = $uf;
                $circuitos->cidade = $cidade;
                $circuitos->vlan = $params["vlan"];
                $circuitos->ccode = $params["ccode"];
                $circuitos->ip_redelocal = $params["ip_redelocal"];
                $circuitos->ip_gerencia = $params["ip_gerencia"];
                $circuitos->tag = $params["tag"];
                $circuitos->id_banda = $params["banda"];
                $circuitos->observacao = $params["observacao"];
                $circuitos->data_ativacao = date("Y-m-d H:i:s");
                if ($circuitos->save() == false) {
                    $transaction->rollback("Não foi possível salvar o circuito!");
                }
                //Commita a transação
                $transaction->commit();
                $response->setContent(json_encode(array(
                    "operacao" => True
                )));
                return $response;
            } catch (TxFailed $e) {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => $e->getMessage()
                )));
                return $response;
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Check de formulário inválido!"
            )));
            return $response;
        }
    }

    public function editarCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $util = new Util();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados["dados"], $params);
        $circuitos = Circuitos::findFirst("id={$params["id"]}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $cliente = Cliente::findFirst("id={$params["id_cliente"]}");
                $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$cliente->id_pessoa}");
                $unidade = (isset($params["id_cliente_unidade"])) ? $params["id_cliente_unidade"] : null ;
                $uf = ($pessoaendereco->sigla_estado) ? $pessoaendereco->sigla_estado : null;
                $cidade = ($pessoaendereco->cidade) ? $pessoaendereco->cidade : null;
                $circuitos->setTransaction($transaction);
                $circuitos->id_cliente = $params["id_cliente"];
                $circuitos->id_cliente_unidade = $unidade;
                $circuitos->id_equipamento = $params["id_equipamento"];
                $circuitos->id_contrato = $params["id_contrato"];
                $circuitos->id_status = 34;//Ativo por Default
                $circuitos->id_cluster = $params["id_cluster"];
                $circuitos->id_tipounidade = $params["id_tipounidade"];
                $circuitos->id_funcao = $params["id_funcao"];
                $circuitos->id_enlace = $params["id_enlace"];
                $circuitos->id_usuario_criacao = $identity["id"];
                $circuitos->designacao = $params["designacao"];
                $circuitos->uf = $uf;
                $circuitos->cidade = $cidade;
                $circuitos->vlan = $params["vlan"];
                $circuitos->ccode = $params["ccode"];
                $circuitos->ip_redelocal = $params["ip_redelocal"];
                $circuitos->ip_gerencia = $params["ip_gerencia"];
                $circuitos->tag = $params["tag"];
                $circuitos->id_banda = $params["banda"];
                $circuitos->observacao = $params["observacao"];
                $circuitos->data_ativacao = date("Y-m-d H:i:s");
                if ($circuitos->save() == false) {
                    $transaction->rollback("Não foi possível salvar o circuito!");
                }
                //Commita a transação
                $transaction->commit();
                $response->setContent(json_encode(array(
                    "operacao" => True
                )));
                return $response;
            } catch (TxFailed $e) {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => $e->getMessage()
                )));
                return $response;
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Check de formulário inválido!"
            )));
            return $response;
        }
    }

    public function movCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $circuitos = Circuitos::findFirst("id={$dado}");
            $del = $core->ativarPessoaAction($circuitos->id_pessoa);
        }
        if($del){
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Erro ao tentar realizar a operação!"
            )));
            return $response;
        }
    }

    public function deletarCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $circuitos = Circuitos::findFirst("id={$dado}");
            $del = $core->deletarPessoaAction($circuitos->id_pessoa);
        }
        if($del){
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Erro ao tentar realizar a operação!"
            )));
            return $response;
        }
    }

    public function unidadeClienteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if (!empty($dados["id_cliente"])) {
            $cliente = Cliente::findFirst("id={$dados["id_cliente"]}");
            $unidade = ClienteUnidade::buscaClienteUnidade($dados["id_cliente"]);
            switch($cliente->id_tipocliente)
            {
                case "45"://Pessoa Física
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "tipocliente" => $cliente->id_tipocliente
                )));
                return $response;
                break;
                case "44"://Pessoa Jurídica
                $response->setContent(json_encode(array(
                    "operacao" => True,
                    "dados" => $unidade,
                    "tipocliente" => $cliente->id_tipocliente
                )));
                return $response;
                break;
            }
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False
            )));
            return $response;
        }
    }

    public function modeloFabricanteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if ($dados["id_fabricante"]) {
            $modelos = Modelo::find("id_fabricante={$dados["id_fabricante"]}");
            if (isset($modelos[0])) {
                $response->setContent(json_encode(array(
                    "operacao" => True,
                    "dados" => $modelos
                )));
                return $response;
            } else {
                $response->setContent(json_encode(array(
                    "operacao" => False
                )));
                return $response;
            }
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False
            )));
            return $response;
        }
    }

    public function equipamentoModeloAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if ($dados["id_modelo"]) {
            $equipamentos = Equipamento::find("id_modelo={$dados["id_modelo"]}");
            if (isset($equipamentos[0])) {
                $response->setContent(json_encode(array(
                    "operacao" => True,
                    "dados" => $equipamentos
                )));
                return $response;
            } else {
                $response->setContent(json_encode(array(
                    "operacao" => False
                )));
                return $response;
            }
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False
            )));
            return $response;
        }
    }
}