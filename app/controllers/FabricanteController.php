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
use Circuitos\Models\Fabricante;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Util;
use Util\TokenManager;


class FabricanteController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Fabricante");
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
        $numberPage = 1;
        $fabricantes = Fabricante::find(array(
            "order" => "[id] DESC"
        ));
        $tipocontato = Lov::find("tipo=13");
        $paginator = new Paginator([
            'data' => $fabricantes,
            'limit'=> 100,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->tipocontato = $tipocontato;
    }

    public function formFabricanteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $util = new Util();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $fabricante = Fabricante::findFirst("id={$dados["id_fabricante"]}");
        $pessoa = Pessoa::findFirst("id={$fabricante->getIdPessoa()}");
        $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$pessoa->getId()}");
        $pessoaemail = PessoaEmail::buscaCompletaLov($pessoa->getId());
        $pessoatelefone = PessoaTelefone::buscaCompletaLov($pessoa->getId());
        $pessoacontato = PessoaContato::buscaCompletaLov($pessoa->getId());
        $dados = array(
            "id" => $fabricante->getId(),
            "nome" => $pessoa->getNome(),
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

    public function criarFabricanteAction()
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
                $pessoaendereco = new PessoaEndereco();
                $pessoaendereco->setTransaction($transaction);
                $pessoaendereco->setIdPessoa($pessoa->getId());
                $pessoaendereco->setIdTipoendereco(49);//Comercial
                $pessoaendereco->setCep($util->formataCpfCnpj($params["cep"]));
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
                        $pessoaendereco->setIdPessoa($pessoa->getId());
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
                        $pessoaendereco->setIdPessoa($pessoa->getId());
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
                        $pessoaendereco->setIdPessoa($pessoa->getId());
                        $pessoacontato->setIdTipocontato($params["tipo_contato"][$key]);
                        $pessoacontato->setNome($nome_contato);
                        $pessoacontato->setPrincipal($params["principal_contato"][$key]);
                        $pessoacontato->setTelefone($tel);
                        $pessoacontato->setEmail($email);
                        if ($pessoacontato->save() == false) {
                            $transaction->rollback("Não foi possível salvar a pessoa de contato!");
                        }
                    }
                }
                $fabricante = new Fabricante();
                $fabricante->setTransaction($transaction);
                $fabricante->setIdPessoa($pessoa->getId());
                if ($fabricante->save() == false) {
                    $messages = $fabricante->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback('Erro ao criar o fabricante: ' . $errors);
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

    public function editarFabricanteAction()
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
        $fabricante = Fabricante::findFirst("id={$params["id"]}");
        $pessoa = Pessoa::findFirst("id={$fabricante->getIdPessoa()}");
        $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$fabricante->getIdPessoa()}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa->setTransaction($transaction);
                $pessoa->setNome(mb_strtoupper($params["nome_pessoa"], $this->encode));
                $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $pessoaendereco->setTransaction($transaction);
                $pessoaendereco->setIdTipoendereco(49);//Comercial
                $pessoaendereco->setCep($util->formataCpfCnpj($params["cep"]));
                $pessoaendereco->setPrincipal(1);//Principal
                $pessoaendereco->setEndereco(mb_strtoupper($params["endereco"], $this->encode));
                $pessoaendereco->setNumero(mb_strtoupper($params["numero"], $this->encode));
                $pessoaendereco->setBairro(mb_strtoupper($params["bairro"], $this->encode));
                $pessoaendereco->setCidade(mb_strtoupper($params["cidade"], $this->encode));
                $pessoaendereco->setEstado(mb_strtoupper($params["estado"], $this->encode));
                $pessoaendereco->setComplemento(mb_strtoupper($params["complemento"], $this->encode));
                $pessoaendereco->setSiglaEstado(mb_strtoupper($params["sigla_uf"], $this->encode));
                if ($pessoaendereco->save() == false) {
                    $transaction->rollback("Não foi possível salvar o pessoa fabricante!");
                }
                if (!empty($params["email_pf"])) {
                    foreach($params["email_pf"] as $key => $email){
                        $pessoaemail = new PessoaEmail();
                        $pessoaemail->setTransaction($transaction);
                        $pessoaendereco->setIdPessoa($pessoa->getId());
                        $pessoaemail->setIdTipoemail(42);//Comercial
                        $pessoaemail->setPrincipal($params["principal_email"][$key]);
                        $pessoaemail->setEmail($email);
                        if ($pessoaemail->save() == false) {
                            $transaction->rollback("Não foi possível salvar o e-mail!");
                        }
                    }
                }
                if (!empty($params["telefone"])) {
                    foreach($params["telefone"] as $key => $telefone){
                        $tel = $util->formataFone($telefone);
                        $pessoatelefone = new PessoaTelefone();
                        $pessoatelefone->setTransaction($transaction);
                        $pessoaendereco->setIdPessoa($pessoa->getId());
                        $pessoatelefone->setIdTipotelefone(50);//Fixo
                        $pessoatelefone->setPrincipal($params["principal_tel"][$key]);
                        $pessoatelefone->setDdd($tel["ddd"]);
                        $pessoatelefone->setTelefone($tel["fone"]);
                        if ($pessoatelefone->save() == false) {
                            $transaction->rollback("Não foi possível salvar o telefone!");
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
                        $pessoaendereco->setIdPessoa($pessoa->getId());
                        $pessoacontato->setIdTipocontato($params["tipo_contato"][$key]);
                        $pessoacontato->setNome($nome_contato);
                        $pessoacontato->setPrincipal($params["principal_contato"][$key]);
                        $pessoacontato->setTelefone($tel);
                        $pessoacontato->setEmail($email);
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

    public function ativarFabricanteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $fabricante = Fabricante::findFirst("id={$dado}");
            $del = $core->ativarPessoaAction($fabricante->getIdPessoa());
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

    public function inativarFabricanteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $fabricante = Fabricante::findFirst("id={$dado}");
            $del = $core->inativarPessoaAction($fabricante->getIdPessoa());
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

    public function deletarFabricanteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $fabricante = Fabricante::findFirst("id={$dado}");
            $del = $core->deletarPessoaAction($fabricante->getIdPessoa());
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
