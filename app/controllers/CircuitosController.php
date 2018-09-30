<?php

namespace Circuitos\Controllers;
 
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Controllers\ControllerBase;

use Circuitos\Models\Circuitos;
use Circuitos\Models\Movimentos;
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
use Util\Relatorio;
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
        $usacontrato = Lov::find("tipo=2");
        $funcao = Lov::find(array(
            "tipo = 3",
            "order" => "descricao"
        ));
        $tipoacesso = Lov::find(array(
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
        $tipolink = Lov::find(array(
            "tipo = 19"
        ));
        $tipomovimento = Lov::find(array(
            "tipo = 16 AND valor IS NULL",
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
        $this->view->fabricantes = $fabricantes;
        $this->view->modelos = $modelos;
        $this->view->equipamentos = $equipamentos;
        $this->view->statuscircuito = $statuscircuito;
        $this->view->usacontrato = $usacontrato;
        $this->view->funcao = $funcao;
        $this->view->tipoacesso = $tipoacesso;
        $this->view->cluster = $cluster;
        $this->view->banda = $banda;
        $this->view->tipomovimento = $tipomovimento;
        $this->view->tipolink = $tipolink;
        $this->view->unidades = $unidades;
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
            'id_funcao' => $circuitos->id_funcao,
            'id_tipoacesso' => $circuitos->id_tipoacesso,
            'id_tipolink' => $circuitos->id_tipolink,
            'designacao' => $circuitos->designacao,
            'designacao_anterior' => $circuitos->designacao_anterior,
            'uf' => $circuitos->uf,
            'cidade' => $circuitos->cidade,
            'chamado' => $circuitos->chamado,
            'ssid' => $circuitos->ssid,
            'ip_redelocal' => $circuitos->ip_redelocal,
            'ip_gerencia' => $circuitos->ip_gerencia,
            'tag' => $circuitos->tag,
            'id_banda' => $circuitos->id_banda,
            'observacao' => $circuitos->observacao,
            'data_ativacao' => $circuitos->data_ativacao,
        );
        $cliente = Cliente::findFirst("id={$circuitos->id_cliente}");
        $unidades = ClienteUnidade::buscaClienteUnidade($circuitos->id_cliente);
        $equipamentos = Equipamento::find();
        $modelos = Modelo::find();
        $equip = Equipamento::findFirst("id={$circuitos->id_equipamento}");
        $banda = Lov::find(array(
            "tipo = 17"
        ));
        $response->setContent(json_encode(array(
            "dados" => $dados,
            "cliente" => $cliente,
            "equipamentos" => $equipamentos,
            "modelos" => $modelos,
            "equip" => $equip,
            "unidadescli" => $unidades,
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
        $movimentos = Movimentos::find("id_circuitos={$circuitos->id}");
        $dados = array(
            'id' => $circuitos->id,
            'id_cliente' => $circuitos->id_cliente,
            'id_cliente_unidade' => $circuitos->id_cliente_unidade,
            'id_equipamento' => $circuitos->id_equipamento,
            'id_contrato' => $circuitos->id_contrato,
            'id_status' => $circuitos->id_status,
            'id_cluster' => $circuitos->id_cluster,
            'id_funcao' => $circuitos->id_funcao,
            'id_tipoacesso' => $circuitos->id_tipoacesso,
            'id_tipolink' => $circuitos->id_tipolink,
            'designacao' => $circuitos->designacao,
            'designacao_anterior' => $circuitos->designacao_anterior,
            'uf' => $circuitos->uf,
            'cidade' => $circuitos->cidade,
            'ssid' => $circuitos->ssid,
            'ip_redelocal' => $circuitos->ip_redelocal,
            'ip_gerencia' => $circuitos->ip_gerencia,
            'tag' => $circuitos->tag,
            'id_banda' => $circuitos->id_banda,
            'observacao' => $circuitos->observacao,
            'data_ativacao' => $util->converterDataHoraParaBr($circuitos->data_ativacao),
            'data_atualizacao' => $util->converterDataHoraParaBr($circuitos->data_atualizacao),
            'numserie' => $circuitos->Equipamento->numserie,
            'numpatrimonio' => $circuitos->Equipamento->numpatrimonio
        );
        $mov = array();
        foreach($movimentos as $movimento){
            array_push($mov, array(
                'id' => $movimento->id,
                'id_circuitos' => $movimento->id_circuitos,
                'id_tipomovimento' => $movimento->Lov->descricao,
                'id_usuario' => $movimento->Usuario->Pessoa->nome,
                'data_movimento' => $util->converterDataHoraParaBr($movimento->data_movimento),
                'osocomon' => $movimento->osocomon,
                'valoranterior' => $movimento->valoranterior,
                'valoratualizado' => $movimento->valoratualizado,
                'observacao' => $movimento->observacao
            ));
        }
        $equip = Equipamento::findFirst("id={$circuitos->id_equipamento}");
        $response->setContent(json_encode(array(
            "dados" => $dados,
            "equip" => $equip,
            "mov" => $mov
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
                $circuitos->id_funcao = $params["id_funcao"];
                $circuitos->id_tipoacesso = $params["id_tipoacesso"];
                $circuitos->id_tipolink = $params["id_tipolink"];
                $circuitos->designacao = $params["designacao"];
                $circuitos->designacao_anterior = $params["designacao_anterior"];
                $circuitos->uf = $uf;
                $circuitos->cidade = $cidade;
                $circuitos->ssid = $params["ssid"];
                $circuitos->chamado = $params["chamado"];
                $circuitos->ip_redelocal = $params["ip_redelocal"];
                $circuitos->ip_gerencia = $params["ip_gerencia"];
                $circuitos->tag = $params["tag"];
                $circuitos->id_banda = $params["banda"];
                $circuitos->observacao = $params["observacao"];
                $circuitos->data_ativacao = date("Y-m-d H:i:s");
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
                $movimento->id_circuitos = $circuitos->id;
                $movimento->id_tipomovimento = 86;//Criação
                $movimento->id_usuario = $identity["id"];
                $movimento->data_movimento = date("Y-m-d H:i:s");
                $movimento->osocomon = null;
                $movimento->valoranterior = null;
                $movimento->valoratualizado = null;
                $movimento->observacao = null;
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
                $cliente = Cliente::findFirst("id={$params["id_cliente"]}");
                $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$cliente->id_pessoa}");
                $unidade = (isset($params["id_cliente_unidade"])) ? $params["id_cliente_unidade"] : null ;
                $uf = ($pessoaendereco->sigla_estado) ? $pessoaendereco->sigla_estado : null;
                $cidade = ($pessoaendereco->cidade) ? $pessoaendereco->cidade : null;
                $circuitos->setTransaction($transaction);
                $circuitos->id_cliente = $params["id_cliente"];
                $circuitos->id_cliente_unidade = $unidade;
                $circuitos->id_contrato = $params["id_contrato"];
                $circuitos->id_cluster = $params["id_cluster"];
                $circuitos->id_funcao = $params["id_funcao"];
                $circuitos->id_tipoacesso = $params["id_tipoacesso"];
                $circuitos->id_tipolink = $params["id_tipolink"];
                $circuitos->designacao_anterior = $params["designacao_anterior"];
                $circuitos->uf = $uf;
                $circuitos->cidade = $cidade;
                $circuitos->ssid = $params["ssid"];
                $circuitos->chamado = $params["chamado"];
                $circuitos->tag = $params["tag"];
                $circuitos->observacao = $params["observacao"];
                $circuitos->data_atualizacao = date("Y-m-d H:i:s");
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
                $movimento->id_circuitos = $circuitos->id;
                $movimento->id_tipomovimento = 88;//Atualização
                $movimento->id_usuario = $identity["id"];
                $movimento->data_movimento = date("Y-m-d H:i:s");
                $movimento->osocomon = null;
                $movimento->valoranterior = null;
                $movimento->valoratualizado = null;
                $movimento->observacao = null;
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
                    case "61"://Alteração de Banda
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->Lov7->descricao;
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->id_banda = $params["bandamov"];
                        $circuitos->data_atualizacao = date("Y-m-d H:i:s");
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
                        $vl_novo = $bd->descricao;
                        $movimento = new Movimentos();
                        $movimento->setTransaction($transaction);
                        $movimento->id_circuitos = $circuitos->id;
                        $movimento->id_tipomovimento = 61;//Alteração de Banda
                        $movimento->id_usuario = $identity["id"];
                        $movimento->data_movimento = date("Y-m-d H:i:s");
                        $movimento->osocomon = $params["osocomon"];
                        $movimento->valoranterior = $vl_anterior;
                        $movimento->valoratualizado = $vl_novo;
                        $movimento->observacao = $params["observacaomov"];
                        if ($movimento->save() == false) {
                            $messages = $movimento->getMessages();
                            $errors = '';
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= '['.$messages[$i].'] ';
                            }
                            $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                        }
                    break;
                    case "62"://Mudança de Status do Circuito
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->Lov2->descricao;
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->id_status = $params["id_statusmov"];
                        $circuitos->data_atualizacao = date("Y-m-d H:i:s");
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
                        $vl_novo = $bd->descricao;
                        $movimento = new Movimentos();
                        $movimento->setTransaction($transaction);
                        $movimento->id_circuitos = $circuitos->id;
                        $movimento->id_tipomovimento = 62;//Alteração de Status do Circuito
                        $movimento->id_usuario = $identity["id"];
                        $movimento->data_movimento = date("Y-m-d H:i:s");
                        $movimento->osocomon = $params["osocomon"];
                        $movimento->valoranterior = $vl_anterior;
                        $movimento->valoratualizado = $vl_novo;
                        $movimento->observacao = $params["observacaomov"];
                        if ($movimento->save() == false) {
                            $messages = $movimento->getMessages();
                            $errors = '';
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= '['.$messages[$i].'] ';
                            }
                            $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                        }
                    break;
                    case "63"://Alteração de IP Gerencial
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->ip_gerencia;
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->ip_gerencia = $params["ip_gerenciamov"];
                        $circuitos->data_atualizacao = date("Y-m-d H:i:s");
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
                        $movimento->id_circuitos = $circuitos->id;
                        $movimento->id_tipomovimento = 63;//Alteração de IP Gerencial
                        $movimento->id_usuario = $identity["id"];
                        $movimento->data_movimento = date("Y-m-d H:i:s");
                        $movimento->osocomon = $params["osocomon"];
                        $movimento->valoranterior = $vl_anterior;
                        $movimento->valoratualizado = $vl_novo;
                        $movimento->observacao = $params["observacaomov"];
                        if ($movimento->save() == false) {
                            $messages = $movimento->getMessages();
                            $errors = '';
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= '['.$messages[$i].'] ';
                            }
                            $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                        }
                    break;
                    case "64"://Alteração de IP Local
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->ip_redelocal;
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->ip_redelocal = $params["ip_redelocalmov"];
                        $circuitos->data_atualizacao = date("Y-m-d H:i:s");
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
                        $movimento->id_circuitos = $circuitos->id;
                        $movimento->id_tipomovimento = 64;//Alteração de IP Local
                        $movimento->id_usuario = $identity["id"];
                        $movimento->data_movimento = date("Y-m-d H:i:s");
                        $movimento->osocomon = $params["osocomon"];
                        $movimento->valoranterior = $vl_anterior;
                        $movimento->valoratualizado = $vl_novo;
                        $movimento->observacao = $params["observacaomov"];
                        if ($movimento->save() == false) {
                            $messages = $movimento->getMessages();
                            $errors = '';
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= '['.$messages[$i].'] ';
                            }
                            $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                        }
                    break;
                    case "89"://Alteração de Equipamento
                        $anterior = Circuitos::findFirst("id={$params["id_circuito"]}");
                        $vl_anterior = $anterior->Equipamento->nome;
                        //Alterando o Circuito
                        $circuitos->setTransaction($transaction);
                        $circuitos->id_equipamento = $params["id_equipamentomov"];
                        $circuitos->data_atualizacao = date("Y-m-d H:i:s");
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
                        $vl_novo = $novo_equip->nome;
                        $movimento = new Movimentos();
                        $movimento->setTransaction($transaction);
                        $movimento->id_circuitos = $circuitos->id;
                        $movimento->id_tipomovimento = 89;//Alteração de Equipamento
                        $movimento->id_usuario = $identity["id"];
                        $movimento->data_movimento = date("Y-m-d H:i:s");
                        $movimento->osocomon = $params["osocomon"];
                        $movimento->valoranterior = $vl_anterior;
                        $movimento->valoratualizado = $vl_novo;
                        $movimento->observacao = $params["observacaomov"];
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
                $circuitos->excluido = 1;
                $circuitos->id_status = 35;//Desativado
                $circuitos->data_atualizacao = date("Y-m-d H:i:s");
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
                $movimento->id_circuitos = $circuitos->id;
                $movimento->id_tipomovimento = 87;//Exclusão
                $movimento->id_usuario = $identity["id"];
                $movimento->data_movimento = date("Y-m-d H:i:s");
                $movimento->osocomon = null;
                $movimento->valoranterior = null;
                $movimento->valoratualizado = null;
                $movimento->observacao = null;
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