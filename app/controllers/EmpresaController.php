<?php

namespace Circuitos\Controllers;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Controllers\ControllerBase;
use Circuitos\Controllers\CoreController as Core;
use Circuitos\Models\Empresa;
use Circuitos\Models\EmpresaParametros;
use Circuitos\Models\Pessoa;
use Circuitos\Models\PessoaJuridica;
use Circuitos\Models\PessoaEndereco;
use Circuitos\Models\PessoaEmail;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Util;
use Util\TemplatesEmails;
use Util\TokenManager;

class EmpresaController extends ControllerBase
{
    public $tokenManager;

    private $encode = "UTF-8";

    public function initialize()
    {
        $this->tag->setTitle("Empresa");
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
        $empresa = Empresa::find(array(
            "order" => "[id] DESC"
        ));
        $setor = Lov::find("tipo=5");
        $esfera = Lov::find("tipo=4");
        $paginator = new Paginator([
            'data' => $empresa,
            'limit'=> 100,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->setor = $setor;
        $this->view->esfera = $esfera;
    }

    public function formEmpresaAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $empresa = Empresa::findFirst("id={$dados["id_empresa"]}");
        $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$empresa->getIdPessoa()}");
        $dados = array(
            "id" => $empresa->getId(),
            "nome_pessoa" => $empresa->Pessoa->nome,
            "razaosocial" => $empresa->Pessoa->PessoaJuridica->razaosocial,
            "cnpj" => $empresa->Pessoa->PessoaJuridica->cnpj,
            "inscestadual" => $empresa->Pessoa->PessoaJuridica->inscricaoestadual,
            "inscmunicipal" => $empresa->Pessoa->PessoaJuridica->inscricaomunicipal,
            "fundacao" => $empresa->Pessoa->PessoaJuridica->datafund,
            "setor" => $empresa->Pessoa->PessoaJuridica->id_setor,
            "esfera" => $empresa->Pessoa->PessoaJuridica->id_tipoesfera,
            "email" => $empresa->Pessoa->PessoaEmail[0]->email,
            "mail_host" => $empresa->EmpresaParametros->mail_host,
            "mail_port" => $empresa->EmpresaParametros->mail_port,
            "mail_smtpssl" => $empresa->EmpresaParametros->mail_smtpssl,
            "mail_user" => $empresa->EmpresaParametros->mail_user,
            "mail_passwrd" => $empresa->EmpresaParametros->mail_passwrd,
            "pessoaendereco" => $pessoaendereco,
        );
        $response->setContent(json_encode(array(
            "dados" => $dados
        )));
        return $response;
    }

    public function criarEmpresaAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $response = new Response();
        $manager = new TxManager();
        $util = new Util();
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
                $datafund = $params["fundacao"] ? $util->converterDataUSA($params["fundacao"]) : null;
                $pessoajuridica = new PessoaJuridica();
                $pessoajuridica->setTransaction($transaction);
                $pessoajuridica->setId($pessoa->getId());
                $pessoajuridica->setIdTipoesfera($params["esfera"]);
                $pessoajuridica->setIdSetor($params["setor"]);
                $pessoajuridica->setCnpj($cnpj);
                $pessoajuridica->setRazaosocial(mb_strtoupper($params["razaosocial"], $this->encode));
                $pessoajuridica->setInscricaoestadual($params["inscestadual"]);
                $pessoajuridica->setInscricaomunicipal($params["inscmunicipal"]);
                $pessoajuridica->setDatafund($datafund);
                if ($pessoajuridica->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pj!");
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
                $pessoaemail = new PessoaEmail();
                $pessoaemail->setTransaction($transaction);
                $pessoaemail->setIdPessoa($pessoa->getId());
                $pessoaemail->setIdTipoemail(43);
                $pessoaemail->setPrincipal(1);
                $pessoaemail->setEmail($params["email"]);
                $pessoaemail->setAtivo(1);
                if ($pessoaemail->save() == false) {
                    $transaction->rollback("Não foi possível salvar o email!");
                }
                $empresa = new Empresa();
                $empresa->setTransaction($transaction);
                $empresa->setIdPessoa($pessoa->getId());
                if ($empresa->save() == false) {
                    $transaction->rollback("Não foi possível salvar o usuário!");
                }
                $empresaparams = new EmpresaParametros();
                $empresaparams->setTransaction($transaction);
                $empresaparams->setIdEmpresa($empresa->getId());
                $empresaparams->setMailHost($params["mail_host"]);
                $empresaparams->setMailSmtpssl($params["mail_smtpssl"]);
                $empresaparams->setMailUser($params["mail_user"]);
                $empresaparams->setMailPasswrd($params["mail_passwrd"]);
                $empresaparams->setMailPort($params["mail_port"]);
                if ($empresaparams->save() == false) {
                    $transaction->rollback("Não foi possível salvar o usuário!");
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

    public function editarEmpresaAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        //Instanciando classes
        $response = new Response();
        $manager = new TxManager();
        $util = new Util();
        $transaction = $manager->get();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados["dados"], $params);
        $empresa = Empresa::findFirst("id={$params["id"]}");
        $pessoa = Pessoa::findFirst("id={$empresa->getIdPessoa()}");
        $pessoajuridica = PessoaJuridica::findFirst("id={$empresa->getIdPessoa()}");
        $pessoaemail = PessoaEmail::findFirst("id_pessoa={$empresa->getIdPessoa()}");
        $pessoaendereco = PessoaEndereco::findFirst("id_pessoa={$empresa->getIdPessoa()}");
        $empresaparams = EmpresaParametros::findFirst("id_empresa={$params["id"]}");
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
                $pessoajuridica->setIdTipoesfera($params["esfera"]);
                $pessoajuridica->setIdSetor($params["setor"]);
                if ($pessoajuridica->getCnpj() != $cnpj) {
                    $pessoajuridica->setCnpj($cnpj);
                }
                $datafund = $params["fundacao"] ? $util->converterDataUSA($params["fundacao"]) : null;
                $pessoajuridica->setRazaosocial(mb_strtoupper($params["razaosocial"], $this->encode));
                $pessoajuridica->setInscricaoestadual($params["inscestadual"]);
                $pessoajuridica->setInscricaomunicipal($params["inscmunicipal"]);
                $pessoajuridica->setDatafund($datafund);
                if ($pessoajuridica->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pj!");
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
                    $messages = $pessoaendereco->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '['.$messages[$i].'] ';
                    }
                    $transaction->rollback('Erro ao editar o endereço: ' . $errors);
                }
                if($params["email"] != $pessoaemail->getEmail()){
                    $pessoaemail->setTransaction($transaction);
                    $pessoaemail->setEmail($params["email"]);
                    if ($pessoaemail->save() == false) {
                        $transaction->rollback("Não foi possível salvar o email!");
                    }
                }
                $empresaparams->setTransaction($transaction);
                $empresaparams->setMailHost($params["mail_host"]);
                $empresaparams->setMailSmtpssl($params["mail_smtpssl"]);
                $empresaparams->setMailUser($params["mail_user"]);
                $empresaparams->setMailPasswrd($params["mail_passwrd"]);
                $empresaparams->setMailPort($params["mail_port"]);
                if ($empresaparams->save() == false) {
                    $transaction->rollback("Não foi possível salvar o usuário!");
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
                    "mensagem" => $e->getTrace()
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

    public function deletarEmpresaAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $core = new Core();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $del = null;
        foreach($dados["ids"] as $dado){
            $empresa = Empresa::findFirst("id={$dado}");
            $del = $core->deletarPessoaAction($empresa->getIdPessoa());
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
