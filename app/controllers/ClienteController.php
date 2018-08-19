<?php

namespace Circuitos\Controllers;
 
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Controllers\ControllerBase;
use Circuitos\Controllers\CoreController as Core;
use Circuitos\Models\Pessoa;
use Circuitos\Models\PessoaEmail;
use Circuitos\Models\PessoaEndereco;
use Circuitos\Models\PessoaTelefone;
use Circuitos\Models\PessoaFisica;
use Circuitos\Models\PessoaJuridica;
use Circuitos\Models\Cliente;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Util;
use Util\TemplatesEmails;
use Util\TokenManager;

class ClienteController extends ControllerBase
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
        $this->persistent->parameters = null;
        $numberPage = 1;
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";
        $clientes = Cliente::find($parameters);
        $tipocliente = Lov::find("tipo=9");
        $tipoendereco = Lov::find("tipo=11");
        $tipotelefone = Lov::find("tipo=12");
        $tipoemail = Lov::find("tipo=8");
        $esfera = Lov::find("tipo=4");
        $setor = Lov::find("tipo=5");
        $sexo = Lov::find("tipo=10");
        $paginator = new Paginator([
            'data' => $clientes,
            'limit'=> 500,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->tipocliente = $tipocliente;
        $this->view->tipoendereco = $tipoendereco;
        $this->view->tipotelefone = $tipotelefone;
        $this->view->tipoemail = $tipoemail;
        $this->view->esfera = $esfera;
        $this->view->setor = $setor;
        $this->view->sexo = $sexo;
    }

    public function formClienteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $util = new Util();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $cliente = Cliente::findFirst("id={$dados["id_cliente"]}");
        $pessoa = Pessoa::findFirst("id={$cliente->id_pessoa}");
        switch ($cliente->id_tipocliente) {
                case "44"://Pessoa Jurídica
                $pessoajuridica = PessoaJuridica::findFirst("id={$pessoa->id}");
                $dados = array(
                    "id" => $cliente->id,
                    "tipo" => $cliente->id_tipocliente,
                    "nome" => $pessoa->nome,
                    "id_tipoesfera" => $pessoajuridica->id_tipoesfera,
                    "id_setor" => $pessoajuridica->id_setor,
                    "cnpj" => $util->mask($pessoajuridica->cnpj, "##.###.###/####-##"),
                    "razaosocial" => $pessoajuridica->razaosocial,
                    "inscricaoestadual" => $pessoajuridica->inscricaoestadual,
                    "inscricaomunicipal" => $pessoajuridica->inscricaomunicipal,
                    "datafund" => $util->converterDataParaBr($pessoajuridica->datafund),
                    "sigla" => $pessoajuridica->sigla
                );
                $response->setContent(json_encode(array(
                    "dados" => $dados
                )));
                return $response;
            break;
            case "45"://Pessoa Física
                $pessoafisica = PessoaFisica::findFirst("id={$pessoa->id}");
                $pessoaendereco = PessoaEndereco::buscaCompletaLov($pessoa->id);
                $pessoaemail = PessoaEmail::buscaCompletaLov($pessoa->id);
                $pessoatelefone = PessoaTelefone::buscaCompletaLov($pessoa->id);
                $dados = array(
                    "id" => $cliente->id,
                    "tipo" => $cliente->id_tipocliente,
                    "nome" => $pessoa->nome,
                    "id_sexo" => $pessoafisica->id_sexo,
                    "cpf" => $util->mask($pessoafisica->cpf, "###.###.###-##"),
                    "rg" => $pessoafisica->rg,
                    "datanasc" => $util->converterDataParaBr($pessoafisica->datanasc),
                    "pessoaendereco" => $pessoaendereco,
                    "pessoaemail" => $pessoaemail,
                    "pessoatelefone" => $pessoatelefone,
                );
                $response->setContent(json_encode(array(
                    "dados" => $dados
                )));
                return $response;
            break;
        }
    }

    public function criarClienteAction()
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
        switch($params["tipocliente"]){
            case "44"://Pessoa Jurídica
            //CSRF Token Check
            if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                try {
                    $pessoa = new Pessoa();
                    $pessoa->setTransaction($transaction);
                    $pessoa->nome = $params["nome_pessoa"];
                    if ($pessoa->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa!");
                    }
                    $datafund = ($params["datafund"]) ? $util->converterDataUSA($params["datafund"]) : null;
                    $pessoajuridica = new PessoaJuridica();
                    $pessoajuridica->setTransaction($transaction);
                    $pessoajuridica->id = $pessoa->id;
                    $pessoajuridica->id_tipoesfera = $params["esfera"];
                    $pessoajuridica->id_setor = $params["setor"];
                    $pessoajuridica->cnpj = $util->formataCpfCnpj($params["cnpj"]);
                    $pessoajuridica->razaosocial = $params["rzsocial"];
                    $pessoajuridica->inscricaoestadual = $params["inscricaoestadual"];
                    $pessoajuridica->inscricaomunicipal = $params["inscricaomunicipal"];
                    $pessoajuridica->datafund = $datafund;
                    $pessoajuridica->sigla = $params["sigla"];
                    if ($pessoajuridica->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                    }
                    $cliente = new Cliente();
                    $cliente->setTransaction($transaction);
                    $cliente->id_pessoa = $pessoa->id;
                    $cliente->id_tipocliente = $params["tipocliente"];
                    if ($cliente->save() == false) {
                        $transaction->rollback("Não foi possível salvar o cliente!");
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
                        "mensagem" => "Erro ao tentar realizar o cadastro!"
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
            break;
            case "45"://Pessoa Física
            //CSRF Token Check
            if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                try {
                    $pessoa = new Pessoa();
                    $pessoa->setTransaction($transaction);
                    $pessoa->nome = $params["nome_pessoa2"];
                    if ($pessoa->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa!");
                    }
                    $datanasc = ($params["datanasc"]) ? $util->converterDataUSA($params["datanasc"]) : null;
                    $pessoafisica = new PessoaFisica();
                    $pessoafisica->setTransaction($transaction);
                    $pessoafisica->id = $pessoa->id;
                    $pessoafisica->id_sexo = $params["sexo"];
                    $pessoafisica->cpf = $util->formataCpfCnpj($params["cpf"]);
                    $pessoafisica->rg = $params["rg"];
                    $pessoafisica->datanasc = $datanasc;
                    if ($pessoafisica->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                    }
                    if (!empty($params["email_pf"])) {
                        foreach($params["email_pf"] as $key => $email){
                            $pessoaemail = new PessoaEmail();
                            $pessoaemail->setTransaction($transaction);
                            $pessoaemail->id_pessoa = $pessoa->id;
                            $pessoaemail->id_tipoemail = $params["tipoemailpf"][$key];
                            $pessoaemail->principal = $params["principal_email"][$key];
                            $pessoaemail->email = $email;
                            if ($pessoaemail->save() == false) {
                                $transaction->rollback("Não foi possível salvar o pessoaemail!");
                            }
                        }
                    }
                    if (!empty($params["telefone"])) {
                        foreach($params["telefone"] as $key => $telefone){
                            $tel = $util->formataFone($telefone);
                            $pessoatelefone = new PessoaTelefone();
                            $pessoatelefone->setTransaction($transaction);
                            $pessoatelefone->id_pessoa = $pessoa->id;
                            $pessoatelefone->id_tipotelefone = $params["tipotelefone"][$key];
                            $pessoatelefone->principal = $params["principal_tel"][$key];
                            $pessoatelefone->ddd = $tel["ddd"];
                            $pessoatelefone->telefone = $tel["fone"];
                            if ($pessoatelefone->save() == false) {
                                $transaction->rollback("Não foi possível salvar o pessoatelefone!");
                            }
                        }
                    }
                    if (!empty($params["cep"])) {
                        foreach($params["cep"] as $key => $cep){
                            $pessoaendereco = new PessoaEndereco();
                            $pessoaendereco->setTransaction($transaction);
                            $pessoaendereco->id_pessoa = $pessoa->id;
                            $pessoaendereco->id_tipoendereco = $params["tipoendereco"][$key];
                            $pessoaendereco->cep = $util->formataCpfCnpj($cep);
                            $pessoaendereco->principal = $params["principal_end"][$key];
                            $pessoaendereco->endereco = $params["endereco"][$key];
                            $pessoaendereco->numero = $params["numero"][$key];
                            $pessoaendereco->bairro = $params["bairro"][$key];
                            $pessoaendereco->cidade = $params["cidade"][$key];
                            $pessoaendereco->estado = $params["estado"][$key];
                            $pessoaendereco->complemento = $params["complemento"][$key];
                            $pessoaendereco->sigla_estado = $params["sigla_uf"][$key];
                            if ($pessoaendereco->save() == false) {
                                $transaction->rollback("Não foi possível salvar o pessoaendereco!");
                            }
                        }
                    }
                    $cliente = new Cliente();
                    $cliente->setTransaction($transaction);
                    $cliente->id_pessoa = $pessoa->id;
                    $cliente->id_tipocliente = $params["tipocliente"];
                    if ($cliente->save() == false) {
                        $transaction->rollback("Não foi possível salvar o cliente!");
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
                        "mensagem" => "Erro ao tentar realizar o cadastro!"
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
            break;
        }
    }

    public function editarClienteAction()
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
        $cliente = Cliente::findFirst("id={$params["id"]}");
        $pessoa = Pessoa::findFirst("id={$cliente->id_pessoa}");
        switch($params["tipocliente"]){
            case "44"://Pessoa Jurídica
            $pessoajuridica = PessoaJuridica::findFirst("id={$cliente->id_pessoa}");
            //CSRF Token Check
            if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                try {
                    $pessoa->setTransaction($transaction);
                    $pessoa->nome = $params["nome_pessoa"];
                    $pessoa->update_at = date("Y-m-d H:i:s");
                    if ($pessoa->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa!");
                    }
                    $datafund = ($params["datafund"]) ? $util->converterDataUSA($params["datafund"]) : null;
                    $pessoajuridica->setTransaction($transaction);
                    $pessoajuridica->id_tipoesfera = $params["esfera"];
                    $pessoajuridica->id_setor = $params["setor"];
                    $pessoajuridica->cnpj = $util->formataCpfCnpj($params["cnpj"]);
                    $pessoajuridica->razaosocial = $params["rzsocial"];
                    $pessoajuridica->inscricaoestadual = $params["inscricaoestadual"];
                    $pessoajuridica->inscricaomunicipal = $params["inscricaomunicipal"];
                    $pessoajuridica->datafund = $datafund;
                    $pessoajuridica->sigla = $params["sigla"];
                    if ($pessoajuridica->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa juridica!");
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
                        "mensagem" => "Erro ao tentar realizar o cadastro!"
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
            break;
            case "45"://Pessoa Física
            $pessoafisica = PessoaFisica::findFirst("id={$cliente->id_pessoa}");
            //CSRF Token Check
            if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                try {
                    $pessoa->setTransaction($transaction);
                    $pessoa->nome = $params["nome_pessoa2"];
                    $pessoa->update_at = date("Y-m-d H:i:s");
                    if ($pessoa->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa!");
                    }
                    $datanasc = ($params["datanasc"]) ? $util->converterDataUSA($params["datanasc"]) : null;
                    $pessoafisica->setTransaction($transaction);
                    $pessoafisica->id_sexo = $params["sexo"];
                    $pessoafisica->cpf = $util->formataCpfCnpj($params["cpf"]);
                    $pessoafisica->rg = $params["rg"];
                    $pessoafisica->datanasc = $datanasc;
                    if ($pessoafisica->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                    }
                    if (!empty($params["email_pf"])) {
                        foreach($params["email_pf"] as $key => $email){
                            $pessoaemail = new PessoaEmail();
                            $pessoaemail->setTransaction($transaction);
                            $pessoaemail->id_pessoa = $pessoa->id;
                            $pessoaemail->id_tipoemail = $params["tipoemailpf"][$key];
                            $pessoaemail->principal = $params["principal_email"][$key];
                            $pessoaemail->email = $email;
                            if ($pessoaemail->save() == false) {
                                $transaction->rollback("Não foi possível salvar o pessoaemail!");
                            }
                        }
                    }
                    if (!empty($params["telefone"])) {
                        foreach($params["telefone"] as $key => $telefone){
                            $tel = $util->formataFone($telefone);
                            $pessoatelefone = new PessoaTelefone();
                            $pessoatelefone->setTransaction($transaction);
                            $pessoatelefone->id_pessoa = $pessoa->id;
                            $pessoatelefone->id_tipotelefone = $params["tipotelefone"][$key];
                            $pessoatelefone->principal = $params["principal_tel"][$key];
                            $pessoatelefone->ddd = $tel["ddd"];
                            $pessoatelefone->telefone = $tel["fone"];
                            if ($pessoatelefone->save() == false) {
                                $transaction->rollback("Não foi possível salvar o pessoatelefone!");
                            }
                        }
                    }
                    if (!empty($params["cep"])) {
                        foreach($params["cep"] as $key => $cep){
                            $pessoaendereco = new PessoaEndereco();
                            $pessoaendereco->setTransaction($transaction);
                            $pessoaendereco->id_pessoa = $pessoa->id;
                            $pessoaendereco->id_tipoendereco = $params["tipoendereco"][$key];
                            $pessoaendereco->cep = $util->formataCpfCnpj($cep);
                            $pessoaendereco->principal = $params["principal_end"][$key];
                            $pessoaendereco->endereco = $params["endereco"][$key];
                            $pessoaendereco->numero = $params["numero"][$key];
                            $pessoaendereco->bairro = $params["bairro"][$key];
                            $pessoaendereco->cidade = $params["cidade"][$key];
                            $pessoaendereco->estado = $params["estado"][$key];
                            $pessoaendereco->complemento = $params["complemento"][$key];
                            $pessoaendereco->sigla_estado = $params["sigla_uf"][$key];
                            if ($pessoaendereco->save() == false) {
                                $transaction->rollback("Não foi possível salvar o pessoaendereco!");
                            }
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
                        "mensagem" => "Erro ao tentar realizar o cadastro!"
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
            break;
        }
    }

    public function ativarClienteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $cliente = Cliente::findFirst("id={$dado}");
            $del = $core->ativarPessoaAction($cliente->id_pessoa);
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

    public function inativarClienteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $cliente = Cliente::findFirst("id={$dado}");
            $del = $core->inativarPessoaAction($cliente->id_pessoa);
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

    public function deletarClienteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $cliente = Cliente::findFirst("id={$dado}");
            $del = $core->deletarPessoaAction($cliente->id_pessoa);
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
}