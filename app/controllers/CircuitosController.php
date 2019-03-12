<?php

namespace Circuitos\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\Circuitos;
use Circuitos\Models\CidadeDigital;
use Circuitos\Models\Conectividade;
use Circuitos\Models\Movimentos;
use Circuitos\Models\Cliente;
use Circuitos\Models\ClienteUnidade;
use Circuitos\Models\Fabricante;
use Circuitos\Models\Modelo;
use Circuitos\Models\Equipamento;
use Circuitos\Models\Lov;
use Circuitos\Models\PessoaEndereco;
use Circuitos\Models\PessoaContato;

use Auth\Autentica;
use Util\Util;
use Util\TokenManager;
use Util\Relatorio;

class CircuitosController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Circuitos");
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
        $numberPage = 1;
        $dados = filter_input_array(INPUT_POST);

        $circuitos = Circuitos::pesquisarCircuitos($dados["pesquisa"]);

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
            "tipo = 16 AND valor >= 4",
            "order" => "descricao"
        ));
        $cidadedigital = CidadeDigital::find(array(
            "excluido = 0 AND ativo = 1",
            "order" => "descricao"
        ));
        $clientes = Cliente::buscaClienteAtivo();
        $unidades = ClienteUnidade::buscaUnidadeAtiva();
        $fabricantes = Fabricante::buscaFabricanteAtivo();
        $modelos = Modelo::find();
        $equipamentos = Equipamento::find();
        $paginator = new Paginator([
            "data" => $circuitos,
            "limit"=> 100,
            "page" => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
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
    }

    public function formCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $circuitos = Circuitos::findFirst("id={$dados["id_circuitos"]}");
        $dados = array(
            "id" => $circuitos->getId(),
            "id_tipocliente" => $circuitos->getIdTipoCliente(),
            "id_cliente" => $circuitos->getIdCliente(),
            "id_cliente_unidade" => $circuitos->getIdClienteUnidade(),
            "lid_cliente" => $circuitos->getClienteNome(),
            "lid_cliente_unidade" => $circuitos->getClienteUnidadeNome(),
            "id_fabricante" => $circuitos->getIdFabricante(),
            "id_modelo" => $circuitos->getIdModelo(),
            "id_equipamento" => $circuitos->getIdEquipamento(),
            "lid_fabricante" => $circuitos->getFabricanteNome(),
            "lid_modelo" => $circuitos->getModeloNome(),
            "lid_equipamento" => $circuitos->getEquipamentoNome(),
            "patr_equip" => $circuitos->getEquipamentoPatrimonio(),
            "nums_equip" => $circuitos->getEquipamentoSerie(),
            "id_contrato" => $circuitos->getIdContrato(),
            "id_status" => $circuitos->getIdStatus(),
            "id_funcao" => $circuitos->getIdFuncao(),
            "id_tipoacesso" => $circuitos->getIdTipoacesso(),
            "id_tipolink" => $circuitos->getIdTipolink(),
            "id_cidadedigital" => $circuitos->getIdCidadedigital(),
            "id_conectividade" => $circuitos->getIdConectividade(),
            "lid_cidadedigital" => $circuitos->getCidadeDigitalNome(),
            "lid_conectividade" => $circuitos->getConectividadeNome(),
            "designacao" => $circuitos->getDesignacao(),
            "designacao_anterior" => $circuitos->getDesignacaoAnterior(),
            "uf" => $circuitos->getUf(),
            "cidade" => $circuitos->getCidade(),
            "chamado" => $circuitos->getChamado(),
            "ssid" => $circuitos->getSsid(),
            "ip_redelocal" => $circuitos->getIpRedelocal(),
            "ip_gerencia" => $circuitos->getIpGerencia(),
            "tag" => $circuitos->getTag(),
            "id_banda" => $circuitos->getIdBanda(),
            "observacao" => $circuitos->getObservacao(),
            "data_ativacao" => $circuitos->getDataAtivacao(),
        );
        $banda = Lov::find(array(
            "tipo = 17"
        ));
        $response->setContent(json_encode(array(
            "dados" => $dados,
            "banda" => $banda
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

        $parameters = [];
        $parameters["order"] = "[data_movimento] DESC";
        $parameters["conditions"] = " id_circuitos = :id_circuitos:";
        $parameters["bind"]["id_circuitos"] = $circuitos->getId();
        $movimentos = Movimentos::find($parameters);
        $parameters_end = [];
        $parameters_end["order"] = "[id] DESC";
        $parameters_end["conditions"] = " id_pessoa = :id_pessoa:";
        if ($circuitos->Cliente->id_tipocliente == 43)//Se PJ
        {
            $parameters_end["bind"]["id_pessoa"] = $circuitos->ClienteUnidade->id_pessoa;
        }
        else//Se PF
        {
            $parameters_end["bind"]["id_pessoa"] = $circuitos->Cliente->id_pessoa;
        }
        $enderecos = PessoaEndereco::find($parameters_end);
        $parameters_cont = [];
        $parameters_cont["order"] = "[id] DESC";
        $parameters_cont["conditions"] = " id_pessoa = :id_pessoa:";
        $parameters_cont["bind"]["id_pessoa"] = $circuitos->ClienteUnidade->id_pessoa;
        $contatos = PessoaContato::find($parameters_cont);
        $dados = array(
            "id" => $circuitos->getId(),
            "id_cliente" => $circuitos->getIdCliente(),
            "id_cliente_unidade" => $circuitos->getIdClienteUnidade(),
            "lid_cliente" => $circuitos->getClienteNome(),
            "lid_cliente_unidade" => $circuitos->getClienteUnidadeNome(),
            "id_fabricante" => $circuitos->getIdFabricante(),
            "id_modelo" => $circuitos->getIdModelo(),
            "id_equipamento" => $circuitos->getIdEquipamento(),
            "lid_equipamento" => $circuitos->getEquipamentoNome(),
            "lid_fabricante" => $circuitos->getFabricanteNome(),
            "lid_modelo" => $circuitos->getModeloNome(),
            "id_contrato" => $circuitos->getIdContrato(),
            "id_status" => $circuitos->getIdStatus(),
            "id_funcao" => $circuitos->getIdFuncao(),
            "id_tipoacesso" => $circuitos->getIdTipoacesso(),
            "id_tipolink" => $circuitos->getIdTipolink(),
            "id_cidadedigital" => $circuitos->getIdCidadedigital(),
            "id_conectividade" => $circuitos->getIdConectividade(),
            "lid_cidadedigital" => $circuitos->getCidadeDigitalNome(),
            "lid_conectividade" => $circuitos->getConectividadeNome(),
            "designacao" => $circuitos->getDesignacao(),
            "designacao_anterior" => $circuitos->getDesignacaoAnterior(),
            "uf" => $circuitos->getUf(),
            "cidade" => $circuitos->getCidade(),
            "ssid" => $circuitos->getSsid(),
            "chamado" => $circuitos->getChamado(),
            "ip_redelocal" => $circuitos->getIpRedelocal(),
            "ip_gerencia" => $circuitos->getIpGerencia(),
            "tag" => $circuitos->getTag(),
            "id_banda" => $circuitos->getIdBanda(),
            "observacao" => $circuitos->getObservacao(),
            "data_ativacao" => $util->converterDataHoraParaBr($circuitos->getDataAtivacao()),
            "data_atualizacao" => $util->converterDataHoraParaBr($circuitos->getDataAtualizacao()),
            "numserie" => $circuitos->getEquipamentoSerie(),
            "numpatrimonio" => $circuitos->getEquipamentoPatrimonio()
        );
        $mov = array();
        foreach($movimentos as $movimento){
            array_push($mov, array(
                "id" => $movimento->getId(),
                "id_circuitos" => $movimento->getIdCircuitos(),
                "id_tipomovimento" => $movimento->getTipoMovimento(),
                "id_usuario" => $movimento->getUsuarioMovimento(),
                "data_movimento" => $util->converterDataHoraParaBr($movimento->getDataMovimento()),
                "osocomon" => $movimento->getOsocomon(),
                "valoranterior" => $movimento->getValoranterior(),
                "valoratualizado" => $movimento->getValoratualizado(),
                "observacao" => $movimento->getObservacao()
            ));
        }
        $cont = array();
        foreach($contatos as $contato){
            $principal = ($contato->getPrincipal() == 0) ? "Sim" : "Não";
            array_push($cont, array(
                "id" => $contato->getId(),
                "id_pessoa" => $contato->getIdPessoa(),
                "id_tipocontato" => $contato->getTipoContato(),
                "principal" => $principal,
                "nome" => $contato->getNome(),
                "telefone" => $contato->getTelefone(),
                "email" => $contato->getEmail()
            ));
        }
        $end = array();
        foreach($enderecos as $endereco){
            array_push($end, array(
                "id" => $endereco->getId(),
                "endereco" => $endereco->getEndereco(),
                "numero" => $endereco->getNumero(),
                "bairro" => $endereco->getBairro(),
                "complemento" => $endereco->getComplemento(),
                "cep" => $endereco->getCep()
            ));
        }
        $conec = Conectividade::find("id_cidade_digital={$circuitos->getIdCidadedigital()}");
        $conectividade = array();
        foreach ($conec as $c){
            $conectividades = array(
                "id" => $c->getId(),
                "descricao" => $c->getDescricao(),
                "tipo" => $c->Lov->descricao
            );
            array_push($conectividade,$conectividades);
        }

        $equip = ($circuitos->getIdEquipamento()) ? Equipamento::findFirst("id={$circuitos->getIdEquipamento()}") : null;
        $response->setContent(json_encode(array(
            "dados" => $dados,
            "equip" => $equip,
            "mov" => $mov,
            "cont" => $cont,
            "endereco" => $end,
            "conectividade" => $conectividade
        )));
        return $response;
    }

    public function criarCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $auth = new Autentica();
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
                $unidade = (isset($params["id_cliente_unidade"])) ? $params["id_cliente_unidade"] : null ;
                //Coletando a cidade e estado com base na cidade digital escolhida
                $cidade_estado = CidadeDigital::CidadeUfporCidadeDigital($params["id_cidadedigital"]);
                //Coletando a última designação
                $circuito = Circuitos::findFirst("designacao = (SELECT MAX(designacao) FROM Circuitos\Models\Circuitos)");
                $vl_designacao = ($circuito) ? $circuito->getDesignacao() + 1 : 1;
                //Status Inicial de Cadastro
                $status_inicial = $circuito->getIdStatusInicialCircuito();
                //Criando o Circuito
                $circuitos = new Circuitos();
                $circuitos->setTransaction($transaction);
                $circuitos->setIdCliente($params["id_cliente"]);
                $circuitos->setIdClienteUnidade($unidade);
                $circuitos->setIdEquipamento($params["id_equipamento"]);
                $circuitos->setIdContrato($params["id_contrato"]);
                $circuitos->setIdStatus($status_inicial);//Ativo por Default
                $circuitos->setIdFuncao($params["id_funcao"]);
                $circuitos->setIdTipoacesso($params["id_tipoacesso"]);
                $circuitos->setIdTipolink($params["id_tipolink"]);
                $circuitos->setIdCidadedigital($params["id_cidadedigital"]);
                $circuitos->setIdConectividade($params["id_conectividade"]);
                $circuitos->setDesignacao($vl_designacao);
                $circuitos->setDesignacaoAnterior(mb_strtoupper($params["designacao_anterior"], $this->encode));
                $circuitos->setUf(mb_strtoupper($cidade_estado[0]["uf"], $this->encode));
                $circuitos->setCidade(mb_strtoupper($cidade_estado[0]["cidade"], $this->encode));
                $circuitos->setSsid($params["ssid"]);
                $circuitos->setChamado($params["chamado"]);
                $circuitos->setIpRedelocal($params["ip_redelocal"]);
                $circuitos->setIpGerencia($params["ip_gerencia"]);
                $circuitos->setTag($params["tag"]);
                $circuitos->setIdBanda($params["banda"]);
                $circuitos->setObservacao(mb_strtoupper($params["observacao"], $this->encode));
                $circuitos->setDataAtivacao(date("Y-m-d H:i:s"));
                if ($circuitos->save() == false) {
                    $messages = $circuitos->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback('Erro ao criar o circuito: ' . $errors);
                }
                //Registrando o movimento de entrada do circuito
                $tipo_movimento = $circuitos->getIdMovimentoCriacaoCircuito();
                $movimento = new Movimentos();
                $movimento->setTransaction($transaction);
                $movimento->setIdCircuitos($circuitos->getId());
                $movimento->setIdTipomovimento($tipo_movimento);//Criação
                $movimento->setIdUsuario($identity["id"]);
                $movimento->setDataMovimento(date("Y-m-d H:i:s"));
                if ($movimento->save() == false) {
                    $messages = $movimento->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback("Erro ao criar o movimento: " . $errors);
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
        $auth = new Autentica();
        $util = new Util();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $identity = $auth->getIdentity();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados["dados"], $params);
        $circuitos = Circuitos::findFirst("id={$params["id"]}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                //Coletando a cidade e estado com base na cidade digital escolhida
                $cidade_estado = CidadeDigital::CidadeUfporCidadeDigital($params["id_cidadedigital"]);
                $unidade = (isset($params["id_cliente_unidade"])) ? $params["id_cliente_unidade"] : null ;
                //Editando Circuitos
                $circuitos->setTransaction($transaction);
                $circuitos->setIdCliente($params["id_cliente"]);
                $circuitos->setIdClienteUnidade($unidade);
                $circuitos->setIdContrato($params["id_contrato"]);
                $circuitos->setIdFuncao($params["id_funcao"]);
                $circuitos->setIdTipoacesso($params["id_tipoacesso"]);
                $circuitos->setIdTipolink($params["id_tipolink"]);
                $circuitos->setIdCidadedigital($params["id_cidadedigital"]);
                $circuitos->setIdConectividade($params["id_conectividade"]);
                $circuitos->setDesignacaoAnterior(mb_strtoupper($params["designacao_anterior"], $this->encode));
                $circuitos->setUf(mb_strtoupper($cidade_estado[0]["uf"], $this->encode));
                $circuitos->setCidade(mb_strtoupper($cidade_estado[0]["cidade"], $this->encode));
                $circuitos->setSsid($params["ssid"]);
                $circuitos->setChamado($params["chamado"]);
                $circuitos->setTag($params["tag"]);
                $circuitos->setObservacao(mb_strtoupper($params["observacao"], $this->encode));
                $circuitos->setDataAtualizacao(date("Y-m-d H:i:s"));
                if ($circuitos->save() == false) {
                    $messages = $circuitos->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback("Erro ao editar o circuito: " . $errors);
                }
                //Registrando o movimento de alteração do circuito
                $tipo_movimento = $circuitos->getIdMovimentoEdicaoCircuito();
                $movimento = new Movimentos();
                $movimento->setTransaction($transaction);
                $movimento->setIdCircuitos($circuitos->getId());
                $movimento->setIdTipomovimento($tipo_movimento);//Atualização
                $movimento->setIdUsuario($identity["id"]);
                $movimento->setDataMovimento(date("Y-m-d H:i:s"));
                if ($movimento->save() == false) {
                    $messages = $movimento->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback("Erro ao criar o movimento: " . $errors);
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
        //Instanciando classes
        $response = new Response();
        $auth = new Autentica();
        $manager = new TxManager();
        //Coletando Dados
        $identity = $auth->getIdentity();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados["dados"], $params);
        //Instanciando Objeto
        $id_circuito = (int) $params["id_circuito"];
        $circuito = Circuitos::findFirst("id={$id_circuito}");
        $vl_anterior = null;
        $vl_novo = null;
        $tipo_movimento = null;
        switch($params["id_tipomovimento"])
        {
            case "4"://Alteração de Banda
                $vl_anterior = $circuito->getBandaCircuito();
                break;
            case "5"://Mudança de Status do Circuito
                $vl_anterior = $circuito->getStatusCircuito();
                break;
            case "6"://Alteração de IP Gerencial
                $vl_anterior = $circuito->getIpGerencia();
                break;
            case "7"://Alteração de IP Local
                $vl_anterior = $circuito->getIpRedelocal();
                break;
            case "8"://Alteração de Equipamento
                $vl_anterior = $circuito->getEquipamentoNome();
                break;
            case "9"://Alteração de Cliente
                $vl_anterior = $circuito->getClienteNome();
                break;
            case "10"://Alteração de Unidade de Cliente
                $vl_anterior = $circuito->getClienteUnidadeNome();
                break;
            case "11"://Alteração de Cidade Digital
                $vl_anterior = $circuito->getCidadeDigitalNome();
                break;
            case "12"://Alteração de Conectividade
                $vl_anterior = $circuito->getConectividadeNome();
                break;
        }
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                //Alterando o Circuito
                $transaction = $manager->get();
                $circuito->setTransaction($transaction);
                switch($params["id_tipomovimento"])
                {
                    case "4"://Alteração de Banda
                        $circuito->setIdBanda((int) $params["bandamov"]);
                        break;
                    case "5"://Mudança de Status do Circuito
                        $circuito->setIdStatus($params["id_statusmov"]);
                        break;
                    case "6"://Alteração de IP Gerencial
                        $circuito->setIpGerencia($params["ip_gerenciamov"]);
                        break;
                    case "7"://Alteração de IP Local
                        $circuito->setIpRedelocal($params["ip_redelocalmov"]);
                        break;
                    case "8"://Alteração de Equipamento
                        $circuito->setIdEquipamento($params["id_equipamentomov"]);
                        break;
                    case "9"://Alteração de Cliente
                        $circuito->setIdCliente($params["id_clientemov"]);
                        $circuito->setIdClienteUnidade($params["id_cliente_unidademov"]);
                        break;
                    case "10"://Alteração de Unidade de Cliente
                        $circuito->setIdClienteUnidade($params["id_cliente_unidademov"]);
                        break;
                    case "11"://Alteração de Cidade Digital
                        $circuito->setIdCidadedigital($params["id_cidadedigitalmov"]);
                        $circuito->setIdConectividade($params["id_conectividademov"]);
                        break;
                    case "12"://Alteração de Conectividade
                        $circuito->setIdConectividade($params["id_conectividademov"]);
                        break;
                }
                $circuito->setDataAtualizacao(date("Y-m-d H:i:s"));
                if ($circuito->save() == false) {
                    $messages = $circuito->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback('Erro ao editar o circuito: ' . $errors);
                }
                //Registrando o movimento de entrada do circuito
                $circuito_atualizado = Circuitos::findFirst("id={$id_circuito}");
                switch($params["id_tipomovimento"])
                {
                    case "4"://Alteração de Banda
                        $tipo_movimento = $circuito_atualizado->getIdMovimentoBandaCircuito();
                        $vl_novo = $circuito_atualizado->getBandaCircuito();
                        break;
                    case "5"://Mudança de Status do Circuito
                        $tipo_movimento = $circuito_atualizado->getIdMovimentoStatusCircuito();
                        $vl_novo = $circuito_atualizado->getStatusCircuito();
                        break;
                    case "6"://Alteração de IP Gerencial
                        $tipo_movimento = $circuito_atualizado->getIdMovimentoIpGerenciaCircuito();
                        $vl_novo = $params["ip_gerenciamov"];
                        break;
                    case "7"://Alteração de IP Local
                        $tipo_movimento = $circuito_atualizado->getIdMovimentoIpLocalCircuito();
                        $vl_novo = $params["ip_redelocalmov"];
                        break;
                    case "8"://Alteração de Equipamento
                        $tipo_movimento = $circuito_atualizado->getIdMovimentoEquipamentoCircuito();
                        $vl_novo = $circuito_atualizado->getEquipamentoNome();
                        break;
                    case "9"://Alteração de Cliente
                        $tipo_movimento = $circuito_atualizado->getIdMovimentoClienteCircuito();
                        $vl_novo = $circuito_atualizado->getClienteNome();
                        break;
                    case "10"://Alteração de Unidade de Cliente
                        $tipo_movimento = $circuito_atualizado->getIdMovimentoClienteUnidadeCircuito();
                        $vl_novo = $circuito_atualizado->getClienteUnidadeNome();
                        break;
                    case "11"://Alteração de Cidade Digital
                        $tipo_movimento = $circuito_atualizado->getIdMovimentoCidadeDigitalCircuito();
                        $vl_novo = $circuito_atualizado->getCidadeDigitalNome();
                        break;
                    case "12"://Alteração de Conectividade
                        $tipo_movimento = $circuito_atualizado->getIdMovimentoConectividadeCircuito();
                        $vl_novo = $circuito_atualizado->getConectividadeNome();
                        break;
                }
                $movimento = new Movimentos();
                $movimento->setTransaction($transaction);
                $movimento->setIdCircuitos($id_circuito);
                $movimento->setIdTipomovimento($tipo_movimento);
                $movimento->setIdUsuario($identity["id"]);
                $movimento->setDataMovimento(date("Y-m-d H:i:s"));
                $movimento->setOsocomon($params["osocomon"]);
                $movimento->setValoranterior($vl_anterior);
                $movimento->setValoratualizado($vl_novo);
                $movimento->setObservacao(mb_strtoupper($params["observacaomov"], $this->encode));
                if ($movimento->save() == false) {
                    $messages = $movimento->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                }
                $transaction->commit();
                $response->setContent(json_encode(array(
                    "operacao" => True
                )));
            } catch (TxFailed $e) {
                $response->setContent(json_encode(array(
                    "operacao" => False,
                    "mensagem" => $e->getMessage()
                )));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Check de formulário inválido!"
            )));
        }
        return $response;
    }

    public function deletarCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $auth = new Autentica();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $identity = $auth->getIdentity();
        $dados = filter_input_array(INPUT_POST);
        try {
            foreach($dados["ids"] as $dado){
                $circuitos = Circuitos::findFirst("id={$dado}");
                $circuitos->setTransaction($transaction);
                $circuitos->setExcluido(1);
                $circuitos->setIdStatus(32);//Desativado
                $circuitos->setDataAtualizacao(date("Y-m-d H:i:s"));
                if ($circuitos->save() == false) {
                    $messages = $circuitos->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback('Erro ao editar o circuito: ' . $errors);
                }
                //Registrando o movimento de exclusão do circuito
                $movimento = new Movimentos();
                $movimento->setTransaction($transaction);
                $movimento->setIdCircuitos($circuitos->getId());
                $movimento->setIdTipomovimento(61);//Exclusão
                $movimento->setIdUsuario($identity["id"]);
                $movimento->setDataMovimento(date("Y-m-d H:i:s"));
                if ($movimento->save() == false) {
                    $messages = $movimento->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                }
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
            switch($cliente->getIdTipocliente())
            {
                case "44"://Pessoa Física
                    $response->setContent(json_encode(array(
                        "operacao" => False,
                        "tipocliente" => $cliente->getIdTipocliente()
                    )));
                    return $response;
                    break;
                case "43"://Pessoa Jurídica
                    $response->setContent(json_encode(array(
                        "operacao" => True,
                        "dados" => $unidade,
                        "tipocliente" => $cliente->getIdTipocliente()
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

    public function equipamentoNumeroSerieAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if ($dados["numero_serie"]) {
            $equipamentos = Equipamento::findFirst("numserie={$dados["numero_serie"]}");
            if ($equipamentos) {
                $response->setContent(json_encode(array(
                    "operacao" => True,
                    "id_equipamento" => $equipamentos->getId(),
                    "nome_equipamento" => $equipamentos->getNome(),
                    "numero_patrimonio" => $equipamentos->getNumpatrimonio(),
                    "id_fabricante" => $equipamentos->getIdFabricante(),
                    "nome_fabricante" => $equipamentos->Fabricante->Pessoa->nome,
                    "id_modelo" => $equipamentos->getIdModelo(),
                    "nome_modelo" => $equipamentos->Modelo->modelo
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

    public function validarEquipamentoCircuitoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $equipamentos = Circuitos::findFirst("id_equipamento={$dados["id_equipamento"]}");
        if ($equipamentos) {
            $response->setContent(json_encode(True));
            return $response;
        } else {
            $response->setContent(json_encode(False));
            return $response;
        }
    }

    public function cidadedigitalAllAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $cidadedigital = CidadeDigital::find("excluido=0 AND ativo=1");
        $response->setContent(json_encode(array(
            "operacao" => True,
            "dados" => $cidadedigital
        )));
        return $response;
    }

    public function clienteAllAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $cliente = Cliente::buscarClientes();
        $response->setContent(json_encode(array(
            "operacao" => True,
            "dados" => $cliente
        )));
        return $response;
    }

    public function fabricanteAllAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $fabricante = Fabricante::buscarFabricantes();
        $response->setContent(json_encode(array(
            "operacao" => True,
            "dados" => $fabricante
        )));
        return $response;
    }

    public function cidadedigitalConectividadeAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if ($dados["id_cidadedigital"]) {
            $conec = Conectividade::find("id_cidade_digital={$dados["id_cidadedigital"]}");
            $conectividade = array();
            foreach ($conec as $c){
                $conectividades = array(
                    "id" => $c->getId(),
                    "descricao" => $c->getDescricao(),
                    "tipo" => $c->Lov->descricao
                );
                array_push($conectividade,$conectividades);
            }
            if (isset($conectividade[0])) {
                $response->setContent(json_encode(array(
                    "operacao" => True,
                    "dados" => $conectividade
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

    public function getClienteCircuitoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if ($dados["id_circuito"])
        {
            $circuito = Circuitos::findFirst("id={$dados["id_circuito"]}");
            $response->setContent(json_encode(array(
                "cliente_nome" => $circuito->getClienteNome(),
                "cliente_id" => $circuito->getIdCliente()
            )));
            return $response;
        }
    }

    public function getCidadeDigitalCircuitoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if ($dados["id_circuito"])
        {
            $circuito = Circuitos::findFirst("id={$dados["id_circuito"]}");
            $response->setContent(json_encode(array(
                "cidade_digital_nome" => $circuito->getCidadeDigitalNome(),
                "cidade_digital_id" => $circuito->getIdCidadedigital()
            )));
            return $response;
        }
    }

    public function pdfCircuitoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $gerar = new Relatorio();
        $dados = filter_input_array(INPUT_POST);
        $url = $gerar->gerarPDFCircuito($dados["id_circuito"]);
        ## Enviando os dados via JSON ##
        $response->setContent(json_encode(array(
            'url' => $url
        )));
        return $response;
    }
}