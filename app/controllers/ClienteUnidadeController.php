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
use Circuitos\Models\PessoaContato;
use Circuitos\Models\PessoaTelefone;
use Circuitos\Models\PessoaJuridica;
use Circuitos\Models\Cliente;
use Circuitos\Models\ClienteUnidade;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Util;
use Util\TokenManager;


class ClienteUnidadeController extends ControllerBase
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
        $clienteunidades = ClienteUnidade::find();
        $tipocontato = Lov::find("tipo=13");
        $clientes = Cliente::buscaCompletaCliente(44);
        $paginator = new Paginator([
            'data' => $clienteunidades,
            'limit'=> 500,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->clientes = $clientes;
        $this->view->tipocontato = $tipocontato;
    }

    public function formClienteUnidadeAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $util = new Util();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $cliente = ClienteUnidade::findFirst("id={$dados["id_clienteunidade"]}");
        $pessoa = Pessoa::findFirst("id={$cliente->id_pessoa}");
        $pessoajuridica = PessoaJuridica::findFirst("id={$pessoa->id}");
        $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$pessoa->id}");
        $pessoaemail = PessoaEmail::buscaCompletaLov($pessoa->id);
        $pessoatelefone = PessoaTelefone::buscaCompletaLov($pessoa->id);
        $pessoacontato = PessoaContato::buscaCompletaLov($pessoa->id);
        $dados = array(
            "id" => $cliente->id,
            "id_cliente" => $cliente->id_cliente,
            "nome" => $pessoa->nome,
            "id_tipoesfera" => $pessoajuridica->id_tipoesfera,
            "id_setor" => $pessoajuridica->id_setor,
            "cnpj" => $cnpj = ($pessoajuridica->cnpj) ? $util->mask($pessoajuridica->cnpj, "##.###.###/####-##") : null,
            "razaosocial" => $pessoajuridica->razaosocial,
            "inscricaoestadual" => $pessoajuridica->inscricaoestadual,
            "inscricaomunicipal" => $pessoajuridica->inscricaomunicipal,
            "sigla" => $pessoajuridica->sigla,
            "pessoaendereco" => $pessoaendereco,
            "pessoaemail" => $pessoaemail,
            "pessoatelefone" => $pessoatelefone,
            "pessoacontato" => $pessoacontato,
        );
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function criarClienteUnidadeAction()
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
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa = new Pessoa();
                $pessoa->setTransaction($transaction);
                $pessoa->nome = $params["nome_pessoa"];
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $cnpj = ($params["cnpj"]) ? $util->formataCpfCnpj($params["cnpj"]) : null;
                $pessoajuridica = new PessoaJuridica();
                $pessoajuridica->setTransaction($transaction);
                $pessoajuridica->id = $pessoa->id;
                $pessoajuridica->cnpj = $cnpj;
                $pessoajuridica->razaosocial = $params["rzsocial"];
                $pessoajuridica->inscricaoestadual = $params["inscricaoestadual"];
                $pessoajuridica->inscricaomunicipal = $params["inscricaomunicipal"];
                $pessoajuridica->sigla = $params["sigla"];
                if ($pessoajuridica->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                }
                $pessoaendereco = new PessoaEndereco();
                $pessoaendereco->setTransaction($transaction);
                $pessoaendereco->id_pessoa = $pessoa->id;
                $pessoaendereco->id_tipoendereco = 50;//Comercial
                $pessoaendereco->cep = $util->formataCpfCnpj($params["cep"]);
                $pessoaendereco->principal = 1;//Principal
                $pessoaendereco->endereco = $params["endereco"];
                $pessoaendereco->numero = $params["numero"];
                $pessoaendereco->bairro = $params["bairro"];
                $pessoaendereco->cidade = $params["cidade"];
                $pessoaendereco->estado = $params["estado"];
                $pessoaendereco->complemento = $params["complemento"];
                $pessoaendereco->sigla_estado = $params["sigla_uf"];
                if ($pessoaendereco->save() == false) {
                    $transaction->rollback("Não foi possível salvar o pessoaendereco!");
                }
                if (!empty($params["email_pf"])) {
                    foreach($params["email_pf"] as $key => $email){
                        $pessoaemail = new PessoaEmail();
                        $pessoaemail->setTransaction($transaction);
                        $pessoaemail->id_pessoa = $pessoa->id;
                        $pessoaemail->id_tipoemail = 43;//Comercial
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
                        $pessoatelefone->id_tipotelefone = 51;//Fixo
                        $pessoatelefone->principal = $params["principal_tel"][$key];
                        $pessoatelefone->ddd = $tel["ddd"];
                        $pessoatelefone->telefone = $tel["fone"];
                        if ($pessoatelefone->save() == false) {
                            $transaction->rollback("Não foi possível salvar o pessoatelefone!");
                        }
                    }
                }
                if (!empty($params["nome_contato"])) {
                    foreach($params["nome_contato"] as $key => $nome_contato){
                        $tel1 = ($params["telefone_contato"][$key]) ? $util->formataFone($params["telefone_contato"][$key]) : null;
                        $tel = ($tel1) ? $tel1["ddd"] . $tel1["fone"] : null;
                        $email = ($params["email_contato"][$key]) ? $params["email_contato"][$key] : null;
                        $pessoacontato = new PessoaContato();
                        $pessoacontato->setTransaction($transaction);
                        $pessoacontato->id_pessoa = $pessoa->id;
                        $pessoacontato->id_tipocontato = $params["tipo_contato"][$key];
                        $pessoacontato->nome = $nome_contato;
                        $pessoacontato->principal = $params["principal_contato"][$key];
                        $pessoacontato->telefone = $tel;
                        $pessoacontato->email = $email;
                        if ($pessoacontato->save() == false) {
                            $transaction->rollback("Não foi possível salvar a pessoa de contato!");
                        }
                    }
                }
                $clienteunidade = new ClienteUnidade();
                $clienteunidade->setTransaction($transaction);
                $clienteunidade->id_pessoa = $pessoa->id;
                $clienteunidade->id_cliente = $params["cliente"];
                if ($clienteunidade->save() == false) {
                    $transaction->rollback("Não foi possível salvar a unidade!");
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

    public function editarClienteUnidadeAction()
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
        $cliente = ClienteUnidade::findFirst("id={$params["id"]}");
        $pessoa = Pessoa::findFirst("id={$cliente->id_pessoa}");
        $pessoajuridica = PessoaJuridica::findFirst("id={$cliente->id_pessoa}");
        $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$cliente->id_pessoa}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa->setTransaction($transaction);
                $pessoa->nome = $params["nome_pessoa"];
                $pessoa->update_at = date("Y-m-d H:i:s");
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $cnpj = ($params["cnpj"]) ? $util->formataCpfCnpj($params["cnpj"]) : null;
                $pessoajuridica->setTransaction($transaction);
                $pessoajuridica->cnpj = $cnpj;
                $pessoajuridica->razaosocial = $params["rzsocial"];
                $pessoajuridica->inscricaoestadual = $params["inscricaoestadual"];
                $pessoajuridica->inscricaomunicipal = $params["inscricaomunicipal"];
                $pessoajuridica->sigla = $params["sigla"];
                if ($pessoajuridica->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                }
                $pessoaendereco->setTransaction($transaction);
                $pessoaendereco->id_tipoendereco = 50;//Comercial
                $pessoaendereco->cep = $util->formataCpfCnpj($params["cep"]);
                $pessoaendereco->principal = 1;//Principal
                $pessoaendereco->endereco = $params["endereco"];
                $pessoaendereco->numero = $params["numero"];
                $pessoaendereco->bairro = $params["bairro"];
                $pessoaendereco->cidade = $params["cidade"];
                $pessoaendereco->estado = $params["estado"];
                $pessoaendereco->complemento = $params["complemento"];
                $pessoaendereco->sigla_estado = $params["sigla_uf"];
                if ($pessoaendereco->save() == false) {
                    $transaction->rollback("Não foi possível salvar o pessoaendereco!");
                }
                if (!empty($params["email_pf"])) {
                    foreach($params["email_pf"] as $key => $email){
                        $pessoaemail = new PessoaEmail();
                        $pessoaemail->setTransaction($transaction);
                        $pessoaemail->id_pessoa = $pessoa->id;
                        $pessoaemail->id_tipoemail = 43;//Comercial
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
                        $pessoatelefone->id_tipotelefone = 51;//Fixo
                        $pessoatelefone->principal = $params["principal_tel"][$key];
                        $pessoatelefone->ddd = $tel["ddd"];
                        $pessoatelefone->telefone = $tel["fone"];
                        if ($pessoatelefone->save() == false) {
                            $transaction->rollback("Não foi possível salvar o pessoatelefone!");
                        }
                    }
                }
                if (!empty($params["nome_contato"])) {
                    foreach($params["nome_contato"] as $key => $nome_contato){
                        $tel1 = ($params["telefone_contato"][$key]) ? $util->formataFone($params["telefone_contato"][$key]) : null;
                        $tel = ($tel1) ? $tel1["ddd"] . $tel1["fone"] : null;
                        $email = ($params["email_contato"][$key]) ? $params["email_contato"][$key] : null;
                        $pessoacontato = new PessoaContato();
                        $pessoacontato->setTransaction($transaction);
                        $pessoacontato->id_pessoa = $pessoa->id;
                        $pessoacontato->id_tipocontato = $params["tipo_contato"][$key];
                        $pessoacontato->nome = $nome_contato;
                        $pessoacontato->principal = $params["principal_contato"][$key];
                        $pessoacontato->telefone = $tel;
                        $pessoacontato->email = $email;
                        if ($pessoacontato->save() == false) {
                            $transaction->rollback("Não foi possível salvar a pessoa de contato!");
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

    public function ativarClienteUnidadeAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $cliente = ClienteUnidade::findFirst("id={$dado}");
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

    public function inativarClienteUnidadeAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $cliente = ClienteUnidade::findFirst("id={$dado}");
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

    public function deletarClienteUnidadeAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $cliente = ClienteUnidade::findFirst("id={$dado}");
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
