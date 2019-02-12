<?php

namespace Circuitos\Controllers;

use Phalcon\Http\Response as Response;

//use Circuitos\Controllers\ControllerBase;

use Circuitos\Models\Circuitos;
use Circuitos\Models\CidadeDigital;
use Circuitos\Models\Cliente;
use Circuitos\Models\ClienteUnidade;
use Circuitos\Models\Fabricante;
use Circuitos\Models\Modelo;
use Circuitos\Models\Equipamento;
use Circuitos\Models\Lov;
use Circuitos\Models\EndEstado;

use Auth\Autentica;
use Util\TokenManager;
use Util\Util;
use Util\Relatorio;

class RelatoriosGestaoController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Relatórios");
        parent::initialize();
        //Voltando o usuário não autenticado para a página de login
        $auth = new Autentica();
        $identity = $auth->getIdentity();
        if (!is_array($identity)) {
            return $this->response->redirect("session/login");
        }
        $this->view->user = $identity["nome"];
        //CSRFToken
        $this->tokenManager = new TokenManager();
        if (!$this->tokenManager->doesUserHaveToken("User")) {
            $this->tokenManager->generateToken("User");
        }
        $this->view->token = $this->tokenManager->getToken("User");
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $statuscircuito = Lov::find(array(
            "tipo=6",
            "order" => "descricao"
        ));
        $usacontrato = Lov::find("tipo=2");
        $funcao = Lov::find(array(
            "tipo = 3",
            "order" => "descricao"
        ));
        $tipoacesso = Lov::find(array(
            "tipo = 7",
            "order" => "descricao"
        ));
        $banda = Lov::find(array(
            "tipo = 17"
        ));
        $tipolink = Lov::find(array(
            "tipo = 19"
        ));
        $tipomovimento = Lov::find(array(
            "tipo = 16 AND valor IS NULL",
            "order" => "descricao"
        ));
        $cidadedigital = CidadeDigital::find(array(
            "excluido = 0 AND ativo = 1",
            "order" => "descricao"
        ));
        $esfera = Lov::find(array(
            "tipo=4",
            "order" => "descricao"
        ));

        $setor = Lov::find(array(
            "tipo=5",
            "order" => "descricao"
        ));

        $clientes = Cliente::buscaClienteAtivo();
        $unidades = ClienteUnidade::buscaUnidadeAtiva();
        $fabricantes = Fabricante::buscaFabricanteAtivo();
        $modelos = Modelo::find();
        $equipamentos = Equipamento::find();
        $estados = EndEstado::find();

        $this->view->clientes = $clientes;
        $this->view->fabricantes = $fabricantes;
        $this->view->modelos = $modelos;
        $this->view->equipamentos = $equipamentos;
        $this->view->statuscircuito = $statuscircuito;
        $this->view->usacontrato = $usacontrato;
        $this->view->funcao = $funcao;
        $this->view->tipoacesso = $tipoacesso;
        $this->view->banda = $banda;
        $this->view->tipomovimento = $tipomovimento;
        $this->view->tipolink = $tipolink;
        $this->view->unidades = $unidades;
        $this->view->cidadedigital = $cidadedigital;
        $this->view->estados = $estados;
        $this->view->esfera = $esfera;
        $this->view->setor = $setor;

    }

    public function relatorioCustomizadoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $util = new Util();
        $gerar = new Relatorio();
        $dados = filter_input_array(INPUT_GET);
        $params = array();
        parse_str($dados["dados"], $params);

        $where = "Circuitos.excluido = 0";

        if (!empty($params["id_cliente"]))
        {
            $where .= " AND Circuitos.id_cliente = {$params["id_cliente"]}";
        }
        if (!empty($params["id_cliente_unidade"]))
        {
            $where .= " AND Circuitos.id_cliente_unidade = {$params["id_cliente_unidade"]}";
        }
        if (!empty($params["fieldDesignacao"]))
        {
            $where .= " AND Circuitos.designacao = {$params["fieldDesignacao"]}";
        }
        if (!empty($params["fieldDesignacaoAnterior"]))
        {
            $where .= " AND Circuitos.designacao_anterior = '{$params["fieldDesignacaoAnterior"]}'";
        }
        if (!empty($params["id_cidadedigital"]))
        {
            $where .= " AND Circuitos.id_cidadedigital = {$params["id_cidadedigital"]}";
        }
        if (!empty($params["id_conectividade"]))
        {
            $where .= " AND Circuitos.id_conectividade = {$params["id_conectividade"]}";
        }
        if (!empty($params["id_contrato"]))
        {
            $where .= " AND Circuitos.id_contrato = {$params["id_contrato"]}";
        }
        if (!empty($params["id_tipolink"]))
        {
            $where .= " AND Circuitos.id_tipolink = {$params["id_tipolink"]}";
        }
        if (!empty($params["id_funcao"]))
        {
            $where .= " AND Circuitos.id_funcao = {$params["id_funcao"]}";
        }
        if (!empty($params["id_tipoacesso"]))
        {
            $where .= " AND Circuitos.id_tipoacesso = {$params["id_tipoacesso"]}";
        }
        if (!empty($params["id_fabricante"]))
        {
            $where .= " AND Circuitos.id_fabricante = {$params["id_fabricante"]}";
        }
        if (!empty($params["id_modelo"]))
        {
            $where .= " AND Circuitos.id_modelo = {$params["id_modelo"]}";
        }
        if (!empty($params["id_equipamento"]))
        {
            $where .= " AND Circuitos.id_equipamento = {$params["id_equipamento"]}";
        }
        if (!empty($params["fieldIpRedelocal"]))
        {
            $where .= " AND Circuitos.ip_redelocal = '{$params["fieldIpRedelocal"]}'";
        }
        if (!empty($params["fieldIpGerencia"]))
        {
            $where .= " AND Circuitos.ip_gerencia = '{$params["fieldIpGerencia"]}'";
        }
        if (!empty($params["banda"]))
        {
            $where .= " AND Circuitos.id_banda = {$params["banda"]}";
        }
        if (!empty($params["fieldTag"]))
        {
            $where .= " AND Circuitos.tag = '{$params["fieldTag"]}'";
        }
        if (!empty($params["fieldSsid"]))
        {
            $where .= " AND Circuitos.ssid = '{$params["fieldSsid"]}'";
        }
        if (!empty($params["fieldChamado"]))
        {
            $where .= " AND Circuitos.chamado = '{$params["fieldChamado"]}'";
        }
        if (!empty($params["id_status"]))
        {
            $where .= " AND Circuitos.id_status = {$params["id_status"]}";
        }
        if (!empty($params["id_tipoesfera"]))
        {
            $where .= " AND PessoaJuridica1.id_tipoesfera = {$params["id_tipoesfera"]}";
        }
        if (!empty($params["id_setor"]))
        {
            $where .= " AND PessoaJuridica1.id_setor = {$params["id_setor"]}";
        }
        if (!empty($params["fieldDataAtivacao"]))
        {
            $datas = explode(" - ", $params["fieldDataAtivacao"]);
            $data1 = $util->converterDataUSA($datas[0]);
            $data2 = $util->converterDataUSA($datas[1]);
            $where .= " AND Circuitos.data_ativacao BETWEEN '{$data1} 00:00:00' AND {$data2} 23:59:59'";
        }
        if (!empty($params["fieldDataAtualizacao"]))
        {
            $datas = explode(" - ", $params["fieldDataAtualizacao"]);
            $data1 = $util->converterDataUSA($datas[0]);
            $data2 = $util->converterDataUSA($datas[1]);
            $where .= " AND Circuitos.data_atualizacao BETWEEN '{$data1} 00:00:00' AND {$data2} 23:59:59'";
        }

        $orderby = $dados["ordenar_campo"] . " " . $dados["ordenar_sentido"];

        $dados_relatorio = Circuitos::pesquisarRelatorioCircuitos($dados["eixo_x"], $where, $orderby);

        $orientacao = null;

        if (count($dados["eixo_x"]) > 5)
        {
            $orientacao = "L";
        }
        else
        {
            $orientacao = "P";
        }

        $url = $gerar->gerarPDFRelatório($dados["eixo_x"], $dados_relatorio, $orientacao);
        ## Enviando os dados via JSON ##
        $response->setContent(json_encode(array(
            'url' => $url
        )));
        return $response;
    }

}
