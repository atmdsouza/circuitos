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
use Circuitos\Models\PessoaContato;

use Auth\Autentica;
use Util\Util;
use Util\TokenManager;
use Util\Relatorio;
use Circuitos\Models\PessoaEndereco;

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
            "tipo = 16 AND valor IS NULL",
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
        $util = new Util();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $circuitos = Circuitos::findFirst("id={$dados["id_circuitos"]}");
        $dados = array(
            "id" => $circuitos->getId(),
            "id_cliente" => $circuitos->getIdCliente(),
            "id_cliente_unidade" => $circuitos->getIdClienteUnidade(),
            "id_equipamento" => $circuitos->getIdEquipamento(),
            "desc_modelo" => ($circuitos->getIdEquipamento()) ? $circuitos->Equipamento->Modelo->modelo : null,
            "desc_equip" => ($circuitos->getIdEquipamento()) ? $circuitos->Equipamento->nome : null,
            "patr_equip" => ($circuitos->getIdEquipamento()) ? $circuitos->Equipamento->numpatrimonio : null,
            "nums_equip" => ($circuitos->getIdEquipamento()) ? $circuitos->Equipamento->numserie : null,
            "id_contrato" => $circuitos->getIdContrato(),
            "id_status" => $circuitos->getIdStatus(),
            "id_cluster" => $circuitos->getIdCluster(),
            "id_funcao" => $circuitos->getIdFuncao(),
            "id_tipoacesso" => $circuitos->getIdTipoacesso(),
            "id_tipolink" => $circuitos->getIdTipolink(),
            "id_cidadedigital" => $circuitos->getIdCidadedigital(),
            "id_conectividade" => $circuitos->getIdConectividade(),
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
        $cliente = Cliente::findFirst("id={$circuitos->getIdCliente()}");
        $unidades = ClienteUnidade::buscaClienteUnidade($circuitos->getIdCliente());
        $equipamentos = Equipamento::find();
        $equip = ($circuitos->getIdEquipamento()) ? Equipamento::findFirst("id={$circuitos->getIdEquipamento()}") : null;
        $banda = Lov::find(array(
            "tipo = 17"
        ));

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

        $response->setContent(json_encode(array(
            "dados" => $dados,
            "cliente" => $cliente,
            "equipamentos" => $equipamentos,
            "equip" => $equip,
            "unidadescli" => $unidades,
            "banda" => $banda,
            "conectividade" => $conectividade
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
        $parameters_cont = [];
        $parameters_cont["order"] = "[id] DESC";
        $parameters_cont["conditions"] = " id_pessoa = :id_pessoa:";
        $parameters_cont["bind"]["id_pessoa"] = $circuitos->ClienteUnidade->id_pessoa;
        $contatos = PessoaContato::find($parameters_cont);
        $dados = array(
            "id" => $circuitos->getId(),
            "id_cliente" => $circuitos->getIdCliente(),
            "id_cliente_unidade" => $circuitos->getIdClienteUnidade(),
            "id_equipamento" => $circuitos->getIdEquipamento(),
            "id_contrato" => $circuitos->getIdContrato(),
            "id_status" => $circuitos->getIdStatus(),
            "id_cluster" => $circuitos->getIdCluster(),
            "id_funcao" => $circuitos->getIdFuncao(),
            "id_tipoacesso" => $circuitos->getIdTipoacesso(),
            "id_tipolink" => $circuitos->getIdTipolink(),
            "id_cidadedigital" => $circuitos->getIdCidadedigital(),
            "id_conectividade" => $circuitos->getIdConectividade(),
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
            "numserie" => ($circuitos->getIdEquipamento()) ? $circuitos->Equipamento->numserie : null,
            "numpatrimonio" => ($circuitos->getIdEquipamento()) ? $circuitos->Equipamento->numpatrimonio : null
        );
        $mov = array();
        foreach($movimentos as $movimento){
            array_push($mov, array(
                "id" => $movimento->getId(),
                "id_circuitos" => $movimento->getIdCircuitos(),
                "id_tipomovimento" => $movimento->Lov->descricao,
                "id_usuario" => $movimento->Usuario->Pessoa->nome,
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
                "id_tipocontato" => $contato->Lov->descricao,
                "principal" => $principal,
                "nome" => $contato->getNome(),
                "telefone" => $contato->getTelefone(),
                "email" => $contato->getEmail()
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
                $unidade = (isset($params["id_cliente_unidade"])) ? $params["id_cliente_unidade"] : null ;
                //Coletando a cidade e estado com base na cidade digital escolhida
                $cidade_estado = CidadeDigital::CidadeUfporCidadeDigital($params["id_cidadedigital"]);
                //Coletando a última designação
                $circuito = Circuitos::findFirst("designacao = (SELECT MAX(designacao) FROM Circuitos\Models\Circuitos)");
                $vl_designacao = $circuito->getDesignacao() + 1;
                //Criando o Circuito
                $circuitos = new Circuitos();
                $circuitos->setTransaction($transaction);
                $circuitos->setIdCliente($params["id_cliente"]);
                $circuitos->setIdClienteUnidade($unidade);
                $circuitos->setIdEquipamento($params["id_equipamento"]);
                $circuitos->setIdContrato($params["id_contrato"]);
                $circuitos->setIdStatus(31);//Ativo por Default
                $circuitos->setIdCluster($params["id_cluster"]);
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
                $movimento = new Movimentos();
                $movimento->setTransaction($transaction);
                $movimento->setIdCircuitos($circuitos->getId());
                $movimento->setIdTipomovimento(60);//Criação
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
                $circuitos->setIdCluster($params["id_cluster"]);
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
                    $transaction->rollback('Erro ao editar o circuito: ' . $errors);
                }
                //Registrando o movimento de alteração do circuito
                $movimento = new Movimentos();
                $movimento->setTransaction($transaction);
                $movimento->setIdCircuitos($circuitos->getId());
                $movimento->setIdTipomovimento(62);//Atualização
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
        $auth = new Autentica();
        $util = new Util();
        $response = new Response();
        $manager = new TxManager();
        $transaction = $manager->get();
        $identity = $auth->getIdentity();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados["dados"], $params);
        $circuitos = Circuitos::findFirst("id={$params["id_circuito"]}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                switch($params["id_tipomovimento"])
                {
                    case "63"://Alteração de Banda
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->Lov7->descricao;
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->setIdBanda($params["bandamov"]);
                        $circuitos->setDataAtualizacao(date("Y-m-d H:i:s"));
                        if ($circuitos->save() == false) {
                            $messages = $circuitos->getMessages();
                            $errors = '';
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= '['.$messages[$i].'] ';
                            }
                            $transaction->rollback('Erro ao editar o circuito: ' . $errors);
                        }
                        //Registrando o movimento de entrada do circuito
                        $bd = Lov::findFirst("id={$params["bandamov"]}");
                        $vl_novo = $bd->getDescricao();
                        $movimento = new Movimentos();
                        $movimento->setTransaction($transaction);
                        $movimento->setIdCircuitos($circuitos->getId());
                        $movimento->setIdTipomovimento(63);//Alteração de Banda
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
                    break;
                    case "64"://Mudança de Status do Circuito
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->Lov2->descricao;
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->setIdStatus($params["id_statusmov"]);
                        $circuitos->setDataAtualizacao(date("Y-m-d H:i:s"));
                        if ($circuitos->save() == false) {
                            $messages = $circuitos->getMessages();
                            $errors = '';
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= '['.$messages[$i].'] ';
                            }
                            $transaction->rollback('Erro ao editar o circuito: ' . $errors);
                        }
                        //Registrando o movimento de entrada do circuito
                        $bd = Lov::findFirst("id={$params["id_statusmov"]}");
                        $vl_novo = $bd->getDescricao();
                        $movimento = new Movimentos();
                        $movimento->setTransaction($transaction);
                        $movimento->setIdCircuitos($circuitos->getId());
                        $movimento->setIdTipomovimento(64);//Alteração de Status do Circuito
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
                    break;
                    case "65"://Alteração de IP Gerencial
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->getIpGerencia();
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->setIpGerencia($params["ip_gerenciamov"]);
                        $circuitos->setDataAtualizacao(date("Y-m-d H:i:s"));
                        if ($circuitos->save() == false) {
                            $messages = $circuitos->getMessages();
                            $errors = '';
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= '['.$messages[$i].'] ';
                            }
                            $transaction->rollback('Erro ao editar o circuito: ' . $errors);
                        }
                        //Registrando o movimento de entrada do circuito
                        $vl_novo = $params["ip_gerenciamov"];
                        $movimento = new Movimentos();
                        $movimento->setTransaction($transaction);
                        $movimento->setIdCircuitos($circuitos->getId());
                        $movimento->setIdTipomovimento(65);//Alteração de IP Gerencial
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
                    break;
                    case "66"://Alteração de IP Local
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->getIpRedelocal();
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->setIpRedelocal($params["ip_redelocalmov"]);
                        $circuitos->setDataAtualizacao(date("Y-m-d H:i:s"));
                        if ($circuitos->save() == false) {
                            $messages = $circuitos->getMessages();
                            $errors = '';
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= '['.$messages[$i].'] ';
                            }
                            $transaction->rollback('Erro ao editar o circuito: ' . $errors);
                        }
                        //Registrando o movimento de entrada do circuito
                        $vl_novo = $params["ip_redelocalmov"];
                        $movimento = new Movimentos();
                        $movimento->setTransaction($transaction);
                        $movimento->setIdCircuitos($circuitos->getId());
                        $movimento->setIdTipomovimento(66);//Alteração de IP Local
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
                    break;
                    case "67"://Alteração de Equipamento
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->Equipamento->nome;
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->setIdEquipamento($params["id_equipamentomov"]);
                        $circuitos->setDataAtualizacao(date("Y-m-d H:i:s"));
                        if ($circuitos->save() == false) {
                            $messages = $circuitos->getMessages();
                            $errors = '';
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= '['.$messages[$i].'] ';
                            }
                            $transaction->rollback('Erro ao editar o circuito: ' . $errors);
                        }
                        //Registrando o movimento de entrada do circuito
                        $novo_equip = Equipamento::findFirst("id={$params["id_equipamentomov"]}");
                        $vl_novo = $novo_equip->getNome();
                        $movimento = new Movimentos();
                        $movimento->setTransaction($transaction);
                        $movimento->setIdCircuitos($circuitos->getId());
                        $movimento->setIdTipomovimento(67);//Alteração de Equipamento
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
                    break;
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

    public function deletarCircuitosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $auth = new Autentica();
        $response = new Response();
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