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

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Unidades do Cliente");
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
        $clienteunidades = ClienteUnidade::pesquisarClientesUnidades($dados["pesquisa"]);
        $tipocontato = Lov::find("tipo=13");
        $clientes = Cliente::buscaCompletaCliente(43);
        $paginator = new Paginator([
            'data' => $clienteunidades,
            'limit'=> 100,
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
        $pessoa = Pessoa::findFirst("id={$cliente->getIdPessoa()}");
        $pessoajuridica = PessoaJuridica::findFirst("id={$pessoa->getId()}");
        $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$pessoa->getId()}");
        $pessoaemail = PessoaEmail::buscaCompletaLov($pessoa->getId());
        $pessoatelefone = PessoaTelefone::buscaCompletaLov($pessoa->getId());
        $pessoacontato = PessoaContato::buscaCompletaLov($pessoa->getId());
        $dados = array(
            "id" => $cliente->getId(),
            "id_cliente" => $cliente->getIdCliente(),
            "nome" => $pessoa->getNome(),
            "id_tipoesfera" => $pessoajuridica->getIdTipoesfera(),
            "id_setor" => $pessoajuridica->getIdSetor(),
            "cnpj" => $cnpj = ($pessoajuridica->getCnpj()) ? $util->mask($pessoajuridica->getCnpj(), "##.###.###/####-##") : null,
            "razaosocial" => $pessoajuridica->getRazaosocial(),
            "inscricaoestadual" => $pessoajuridica->getInscricaoestadual(),
            "inscricaomunicipal" => $pessoajuridica->getInscricaomunicipal(),
            "sigla" => $pessoajuridica->getSigla(),
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
                $pessoa->setNome(mb_strtoupper($params["nome_pessoa"], $this->encode));
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $cnpj = ($params["cnpj"]) ? $util->formataCpfCnpj($params["cnpj"]) : null;
                $pessoajuridica = new PessoaJuridica();
                $pessoajuridica->setTransaction($transaction);
                $pessoajuridica->setId($pessoa->getId());
                $pessoajuridica->setCnpj($cnpj);
                $pessoajuridica->setRazaosocial(mb_strtoupper($params["rzsocial"], $this->encode));
                $pessoajuridica->setInscricaoestadual($params["inscricaoestadual"]);
                $pessoajuridica->setInscricaomunicipal($params["inscricaomunicipal"]);
                $pessoajuridica->setSigla($params["sigla"]);
                if ($pessoajuridica->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                }
                $cep = (!empty($params["cep"])) ? $util->formataCpfCnpj($params["cep"]) : null;
                $pessoaendereco = new PessoaEndereco();
                $pessoaendereco->setTransaction($transaction);
                $pessoaendereco->setIdPessoa($pessoa->getId());
                $pessoaendereco->setIdTipoendereco(49);//Comercial
                $pessoaendereco->setCep($cep);
                $pessoaendereco->setPrincipal(1);//Principal
                $pessoaendereco->setEndereco(mb_strtoupper($params["endereco"], $this->encode));
                $pessoaendereco->setNumero(mb_strtoupper($params["numero"], $this->encode));
                $pessoaendereco->setBairro(mb_strtoupper($params["bairro"], $this->encode));
                $pessoaendereco->setCidade(mb_strtoupper($params["cidade"], $this->encode));
                $pessoaendereco->setEstado(mb_strtoupper($params["estado"], $this->encode));
                $pessoaendereco->setComplemento(mb_strtoupper($params["complemento"], $this->encode));
                $pessoaendereco->setSiglaEstado(mb_strtoupper($params["sigla_uf"], $this->encode));
                if ($pessoaendereco->save() == false) {
                    $transaction->rollback("Não foi possível salvar o pessoaendereco!");
                }
                if (!empty($params["email_pf"])) {
                    foreach($params["email_pf"] as $key => $email){
                        $pessoaemail = new PessoaEmail();
                        $pessoaemail->setTransaction($transaction);
                        $pessoaemail->setIdPessoa($pessoa->getId());
                        $pessoaemail->setIdTipoemail(42);//Comercial
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
                        $pessoatelefone->setIdTipotelefone(50);//Fixo
                        $pessoatelefone->setPrincipal($params["principal_tel"][$key]);
                        $pessoatelefone->setDdd($tel["ddd"]);
                        $pessoatelefone->setTelefone($tel["fone"]);
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
                        $pessoacontato->setIdPessoa($pessoa->getId());
                        $pessoacontato->setIdTipocontato($params["tipo_contato"][$key]);
                        $pessoacontato->setNome(mb_strtoupper($nome_contato, $this->encode));
                        $pessoacontato->setPrincipal($params["principal_contato"][$key]);
                        $pessoacontato->setTelefone($tel);
                        $pessoacontato->setEmail($email);
                        if ($pessoacontato->save() == false) {
                            $transaction->rollback("Não foi possível salvar a pessoa de contato!");
                        }
                    }
                }
                $clienteunidade = new ClienteUnidade();
                $clienteunidade->setTransaction($transaction);
                $clienteunidade->setIdPessoa($pessoa->getId());
                $clienteunidade->setIdCliente($params["cliente"]);
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
        $pessoa = Pessoa::findFirst("id={$cliente->getIdPessoa()}");
        $pessoajuridica = PessoaJuridica::findFirst("id={$cliente->getIdPessoa()}");
        $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$cliente->getIdPessoa()}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa->setTransaction($transaction);
                $pessoa->setNome(mb_strtoupper($params["nome_pessoa"], $this->encode));
                $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $cnpj = ($params["cnpj"]) ? $util->formataCpfCnpj($params["cnpj"]) : null;
                $pessoajuridica->setTransaction($transaction);
                $pessoajuridica->setCnpj($cnpj);
                $pessoajuridica->setRazaosocial(mb_strtoupper($params["rzsocial"], $this->encode));
                $pessoajuridica->setInscricaoestadual($params["inscricaoestadual"]);
                $pessoajuridica->setInscricaomunicipal($params["inscricaomunicipal"]);
                $pessoajuridica->setSigla($params["sigla"]);
                if ($pessoajuridica->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa juridica!");
                }
                $cep = (!empty($params["cep"])) ? $util->formataCpfCnpj($params["cep"]) : null;
                $pessoaendereco->setTransaction($transaction);
                $pessoaendereco->setIdTipoendereco(49);//Comercial
                $pessoaendereco->setCep($cep);
                $pessoaendereco->setPrincipal(1);//Principal
                $pessoaendereco->setEndereco(mb_strtoupper($params["endereco"], $this->encode));
                $pessoaendereco->setNumero(mb_strtoupper($params["numero"], $this->encode));
                $pessoaendereco->setBairro(mb_strtoupper($params["bairro"], $this->encode));
                $pessoaendereco->setCidade(mb_strtoupper($params["cidade"], $this->encode));
                $pessoaendereco->setEstado(mb_strtoupper($params["estado"], $this->encode));
                $pessoaendereco->setComplemento(mb_strtoupper($params["complemento"], $this->encode));
                $pessoaendereco->setSiglaEstado(mb_strtoupper($params["sigla_uf"], $this->encode));
                if ($pessoaendereco->save() == false) {
                    $transaction->rollback("Não foi possível salvar o pessoaendereco!");
                }
                if (!empty($params["email_pf"])) {
                    foreach($params["email_pf"] as $key => $email){
                        $pessoaemail = new PessoaEmail();
                        $pessoaemail->setTransaction($transaction);
                        $pessoaemail->setIdPessoa($pessoa->getId());
                        $pessoaemail->setIdTipoemail(42);//Comercial
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
                        $pessoatelefone->setIdTipotelefone(50);//Fixo
                        $pessoatelefone->setPrincipal($params["principal_tel"][$key]);
                        $pessoatelefone->setDdd($tel["ddd"]);
                        $pessoatelefone->setTelefone($tel["fone"]);
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
                        $pessoacontato->setIdPessoa($pessoa->getId());
                        $pessoacontato->setIdTipocontato($params["tipo_contato"][$key]);
                        $pessoacontato->setNome(mb_strtoupper($nome_contato, $this->encode));
                        $pessoacontato->setPrincipal($params["principal_contato"][$key]);
                        $pessoacontato->setTelefone($tel);
                        $pessoacontato->setEmail($email);
                        if ($pessoacontato->save() == false) {
                            $transaction->rollback("Não foi possível salvar a pessoa de contato!");
                        }
                    }
                }
                $cliente->setTransaction($transaction);
                $cliente->setIdCliente($params["cliente"]);
                if ($cliente->save() == false) {
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
