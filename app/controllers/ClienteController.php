<?php

namespace Circuitos\Controllers;

use Phalcon\Mvc\Model\Criteria;
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
use Util\TokenManager;

class ClienteController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Cliente");
        parent::initialize();
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
        $dados = filter_input_array(INPUT_POST);

        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Circuitos\Models\Cliente", $dados);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
            $parameters["order"] = "[id] DESC";
        } else {
            $parameters["order"] = "[id] DESC";
        }
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
            'limit'=> 100,
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
        $pessoa = Pessoa::findFirst("id={$cliente->getIdPessoa()}");
        switch ($cliente->getIdTipocliente()) {
                case "43"://Pessoa Jurídica
                $pessoajuridica = PessoaJuridica::findFirst("id={$pessoa->getId()}");
                $datafund = (!empty($pessoajuridica->getDatafund())) ? $util->converterDataParaBr($pessoajuridica->getDatafund()) : null;
                $dados = array(
                    "id" => $cliente->getId(),
                    "tipo" => $cliente->getIdTipocliente(),
                    "nome" => $pessoa->getNome(),
                    "id_tipoesfera" => $pessoajuridica->getIdTipoesfera(),
                    "id_setor" => $pessoajuridica->getIdSetor(),
                    "cnpj" => $util->mask($pessoajuridica->getCnpj(), "##.###.###/####-##"),
                    "razaosocial" => $pessoajuridica->getRazaosocial(),
                    "inscricaoestadual" => $pessoajuridica->getInscricaoestadual(),
                    "inscricaomunicipal" => $pessoajuridica->getInscricaomunicipal(),
                    "datafund" => $datafund,
                    "sigla" => $pessoajuridica->getSigla()
                );
                $response->setContent(json_encode(array(
                    "dados" => $dados
                )));
                return $response;
            break;
            case "44"://Pessoa Física
                $pessoafisica = PessoaFisica::findFirst("id={$pessoa->getId()}");
                $pessoaendereco = PessoaEndereco::buscaCompletaLov($pessoa->getId());
                $pessoaemail = PessoaEmail::buscaCompletaLov($pessoa->getId());
                $pessoatelefone = PessoaTelefone::buscaCompletaLov($pessoa->getId());
                $datanasc = (!empty($pessoafisica->getDatanasc())) ? $util->converterDataParaBr($pessoafisica->getDatanasc()) : null;
                $dados = array(
                    "id" => $cliente->getId(),
                    "tipo" => $cliente->getIdTipocliente(),
                    "nome" => $pessoa->getNome(),
                    "id_sexo" => $pessoafisica->getIdSexo(),
                    "cpf" => $util->mask($pessoafisica->getCpf(), "###.###.###-##"),
                    "rg" => $pessoafisica->getRg(),
                    "datanasc" => $datanasc,
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
            case "43"://Pessoa Jurídica
            //CSRF Token Check
            if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                try {
                    $pessoa = new Pessoa();
                    $pessoa->setTransaction($transaction);
                    $pessoa->setNome(mb_strtoupper($params["nome_pessoa"], $this->encode));
                    if ($pessoa->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa!");
                    }
                    $datafund = ($params["datafund"]) ? $util->converterDataUSA($params["datafund"]) : null;
                    $pessoajuridica = new PessoaJuridica();
                    $pessoajuridica->setTransaction($transaction);
                    $pessoajuridica->setId($pessoa->getId());
                    $pessoajuridica->setIdTipoesfera($params["esfera"]);
                    $pessoajuridica->setIdSetor($params["setor"]);
                    $pessoajuridica->setCnpj($util->formataCpfCnpj($params["cnpj"]));
                    $pessoajuridica->setRazaosocial(mb_strtoupper($params["rzsocial"], $this->encode));
                    $pessoajuridica->setInscricaoestadual($params["inscricaoestadual"]);
                    $pessoajuridica->setInscricaomunicipal($params["inscricaomunicipal"]);
                    $pessoajuridica->setDatafund($datafund);
                    $pessoajuridica->setSigla($params["sigla"]);
                    if ($pessoajuridica->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                    }
                    $cliente = new Cliente();
                    $cliente->setTransaction($transaction);
                    $cliente->setIdPessoa($pessoa->getId());
                    $cliente->setIdTipocliente($params["tipocliente"]);
                    if ($cliente->save() == false) {
                        $transaction->rollback("Não foi possível salvar o cliente!");
                    }
                    //Commit da transação
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
            case "44"://Pessoa Física
            //CSRF Token Check
            if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                try {
                    $pessoa = new Pessoa();
                    $pessoa->setTransaction($transaction);
                    $pessoa->setNome(mb_strtoupper($params["nome_pessoa2"], $this->encode));
                    if ($pessoa->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa!");
                    }
                    $datanasc = ($params["datanasc"]) ? $util->converterDataUSA($params["datanasc"]) : null;
                    $pessoafisica = new PessoaFisica();
                    $pessoafisica->setTransaction($transaction);
                    $pessoafisica->setId($pessoa->getId());
                    $pessoafisica->setIdSexo($params["sexo"]);
                    $pessoafisica->setCpf($util->formataCpfCnpj($params["cpf"]));
                    $pessoafisica->setRg($params["rg"]);
                    $pessoafisica->setDatanasc($datanasc);
                    if ($pessoafisica->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                    }
                    if (!empty($params["email_pf"])) {
                        foreach($params["email_pf"] as $key => $email){
                            $pessoaemail = new PessoaEmail();
                            $pessoaemail->setTransaction($transaction);
                            $pessoaemail->setIdPessoa($pessoa->getId());
                            $pessoaemail->setIdTipoemail($params["tipoemailpf"][$key]);
                            $pessoaemail->setPrincipal($params["principal_email"][$key]);
                            $pessoaemail->setEmail($email);
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
                            $pessoatelefone->setIdPessoa($pessoa->getId());
                            $pessoatelefone->setIdTipotelefone($params["tipotelefone"][$key]);
                            $pessoatelefone->setPrincipal($params["principal_tel"][$key]);
                            $pessoatelefone->setDdd($tel["ddd"]);
                            $pessoatelefone->setTelefone($tel["fone"]);
                            if ($pessoatelefone->save() == false) {
                                $transaction->rollback("Não foi possível salvar o pessoatelefone!");
                            }
                        }
                    }
                    if (!empty($params["cep"])) {
                        foreach($params["cep"] as $key => $cep){
                            $pessoaendereco = new PessoaEndereco();
                            $pessoaendereco->setTransaction($transaction);
                            $pessoaendereco->setIdPessoa($pessoa->getId());
                            $pessoaendereco->setIdTipoendereco($params["tipoendereco"][$key]);
                            $pessoaendereco->setCep($util->formataCpfCnpj($cep));
                            $pessoaendereco->setPrincipal($params["principal_end"][$key]);
                            $pessoaendereco->setEndereco(mb_strtoupper($params["endereco"][$key], $this->encode));
                            $pessoaendereco->setNumero(mb_strtoupper($params["numero"][$key], $this->encode));
                            $pessoaendereco->setBairro(mb_strtoupper($params["bairro"][$key], $this->encode));
                            $pessoaendereco->setCidade(mb_strtoupper($params["cidade"][$key], $this->encode));
                            $pessoaendereco->setEstado(mb_strtoupper($params["estado"][$key], $this->encode));
                            $pessoaendereco->setComplemento(mb_strtoupper($params["complemento"][$key], $this->encode));
                            $pessoaendereco->setSiglaEstado(mb_strtoupper($params["sigla_uf"][$key], $this->encode));
                            if ($pessoaendereco->save() == false) {
                                $transaction->rollback("Não foi possível salvar o pessoaendereco!");
                            }
                        }
                    }
                    $cliente = new Cliente();
                    $cliente->setTransaction($transaction);
                    $cliente->setIdPessoa($pessoa->getId());
                    $cliente->setIdTipocliente($params["tipocliente"]);
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
        $pessoa = Pessoa::findFirst("id={$cliente->getIdPessoa()}");
        switch($params["tipocliente"]){
            case "43"://Pessoa Jurídica
            $pessoajuridica = PessoaJuridica::findFirst("id={$cliente->getIdPessoa()}");
            //CSRF Token Check
            if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                try {
                    $pessoa->setTransaction($transaction);
                    $pessoa->setNome(mb_strtoupper($params["nome_pessoa"], $this->encode));
                    $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
                    if ($pessoa->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa!");
                    }
                    $datafund = ($params["datafund"]) ? $util->converterDataUSA($params["datafund"]) : null;
                    $pessoajuridica->setTransaction($transaction);
                    $pessoajuridica->setIdTipoesfera($params["esfera"]);
                    $pessoajuridica->setIdSetor($params["setor"]);
                    $pessoajuridica->setCnpj($util->formataCpfCnpj($params["cnpj"]));
                    $pessoajuridica->setRazaosocial(mb_strtoupper($params["rzsocial"], $this->encode));
                    $pessoajuridica->setInscricaoestadual($params["inscricaoestadual"]);
                    $pessoajuridica->setInscricaomunicipal($params["inscricaomunicipal"]);
                    $pessoajuridica->setDatafund($datafund);
                    $pessoajuridica->setSigla($params["sigla"]);
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
            case "44"://Pessoa Física
            $pessoafisica = PessoaFisica::findFirst("id={$cliente->getIdPessoa()}");
            //CSRF Token Check
            if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
                try {
                    $pessoa->setTransaction($transaction);
                    $pessoa->setNome(mb_strtoupper($params["nome_pessoa2"], $this->encode));
                    $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
                    if ($pessoa->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa!");
                    }
                    $datanasc = ($params["datanasc"]) ? $util->converterDataUSA($params["datanasc"]) : null;
                    $pessoafisica->setTransaction($transaction);
                    $pessoafisica->setIdSexo($params["sexo"]);
                    $pessoafisica->setCpf($util->formataCpfCnpj($params["cpf"]));
                    $pessoafisica->setRg($params["rg"]);
                    $pessoafisica->setDatanasc($datanasc);
                    if ($pessoafisica->save() == false) {
                        $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                    }
                    if (!empty($params["email_pf"])) {
                        foreach($params["email_pf"] as $key => $email){
                            $pessoaemail = new PessoaEmail();
                            $pessoaemail->setTransaction($transaction);
                            $pessoaemail->setIdPessoa($pessoa->getId());
                            $pessoaemail->setIdTipoemail($params["tipoemailpf"][$key]);
                            $pessoaemail->setPrincipal($params["principal_email"][$key]);
                            $pessoaemail->setEmail($email);
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
                            $pessoatelefone->setIdPessoa($pessoa->getId());
                            $pessoatelefone->setIdTipotelefone($params["tipotelefone"][$key]);
                            $pessoatelefone->setPrincipal($params["principal_tel"][$key]);
                            $pessoatelefone->setDdd($tel["ddd"]);
                            $pessoatelefone->setTelefone($tel["fone"]);
                            if ($pessoatelefone->save() == false) {
                                $transaction->rollback("Não foi possível salvar o pessoatelefone!");
                            }
                        }
                    }
                    if (!empty($params["cep"])) {
                        foreach($params["cep"] as $key => $cep){
                            $pessoaendereco = new PessoaEndereco();
                            $pessoaendereco->setTransaction($transaction);
                            $pessoaendereco->setIdPessoa($pessoa->getId());
                            $pessoaendereco->setIdTipoendereco($params["tipoendereco"][$key]);
                            $pessoaendereco->setCep($util->formataCpfCnpj($cep));
                            $pessoaendereco->setPrincipal($params["principal_end"][$key]);
                            $pessoaendereco->setEndereco(mb_strtoupper($params["endereco"][$key], $this->encode));
                            $pessoaendereco->setNumero(mb_strtoupper($params["numero"][$key], $this->encode));
                            $pessoaendereco->setBairro(mb_strtoupper($params["bairro"][$key], $this->encode));
                            $pessoaendereco->setCidade(mb_strtoupper($params["cidade"][$key], $this->encode));
                            $pessoaendereco->setEstado(mb_strtoupper($params["estado"][$key], $this->encode));
                            $pessoaendereco->setComplemento(mb_strtoupper($params["complemento"][$key], $this->encode));
                            $pessoaendereco->setSiglaEstado(mb_strtoupper($params["sigla_uf"][$key], $this->encode));
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
            $del = $core->ativarPessoaAction($cliente->getIdPessoa());
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
            $del = $core->inativarPessoaAction($cliente->getIdPessoa());
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
            $del = $core->deletarPessoaAction($cliente->getIdPessoa());
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