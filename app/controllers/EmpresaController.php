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
use Circuitos\Models\PessoaEmail;
use Circuitos\Models\Lov;

use Auth\Autentica;
use Util\Util;
use Util\TemplatesEmails;
use Util\TokenManager;

class EmpresaController extends ControllerBase
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
        $empresa = Empresa::find();
        $setor = Lov::find("tipo=5");
        $esfera = Lov::find("tipo=4");
        $paginator = new Paginator([
            'data' => $empresa,
            'limit'=> 10,
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
        $dados = array(
            "id" => $empresa->id,
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
                $pessoa->nome = $params["nome_pessoa"];
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $pessoajuridica = new PessoaJuridica();
                $pessoajuridica->setTransaction($transaction);
                $pessoajuridica->id = $pessoa->id;
                $pessoajuridica->id_tipoesfera = $params["esfera"];
                $pessoajuridica->id_setor = $params["setor"];
                $pessoajuridica->cnpj = $util->formataCpfCnpj($params["cnpj"]);
                $pessoajuridica->razaosocial = $params["razaosocial"];
                $pessoajuridica->inscricaoestadual = $params["inscestadual"];
                $pessoajuridica->inscricaomunicipal = $params["inscmunicipal"];
                $pessoajuridica->datafund = $util->converterDataUSA($params["fundacao"]);
                if ($pessoajuridica->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pj!");
                }
                $pessoaemail = new PessoaEmail();
                $pessoaemail->setTransaction($transaction);
                $pessoaemail->id_pessoa = $pessoa->id;
                $pessoaemail->id_tipoemail = 43;
                $pessoaemail->principal = 1;
                $pessoaemail->email = $params["email"];
                $pessoaemail->ativo = 1;
                if ($pessoaemail->save() == false) {
                    $transaction->rollback("Não foi possível salvar o email!");
                }
                $empresa = new Empresa();
                $empresa->setTransaction($transaction);
                $empresa->id_pessoa = $pessoa->id;
                if ($empresa->save() == false) {
                    $transaction->rollback("Não foi possível salvar o usuário!");
                }
                $empresaparams = new EmpresaParametros();
                $empresaparams->setTransaction($transaction);
                $empresaparams->id_empresa = $empresa->id;
                $empresaparams->mail_host = $params["mail_host"];
                $empresaparams->mail_smtpssl = $params["mail_smtpssl"];
                $empresaparams->mail_user = $params["mail_user"];
                $empresaparams->mail_passwrd = $params["mail_passwrd"];
                $empresaparams->mail_port = $params["mail_port"];
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
                    "mensagem" => $e->getMessages()
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
        $pessoa = Pessoa::findFirst("id={$empresa->id_pessoa}");
        $pessoajuridica = PessoaJuridica::findFirst("id={$empresa->id_pessoa}");
        $pessoaemail = PessoaEmail::findFirst("id_pessoa={$empresa->id_pessoa}");
        $empresaparams = EmpresaParametros::findFirst("id_empresa={$params["id"]}");
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            try {
                $pessoa->setTransaction($transaction);
                $pessoa->nome = $params["nome_pessoa"];
                $pessoa->update_at = date("Y-m-d H:i:s");
                if ($pessoa->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pessoa!");
                }
                $pessoajuridica->setTransaction($transaction);
                $pessoajuridica->id_tipoesfera = $params["esfera"];
                $pessoajuridica->id_setor = $params["setor"];
                if ($pessoajuridica->cnpj != $util->formataCpfCnpj($params["cnpj"])) {
                    $pessoajuridica->cnpj = $util->formataCpfCnpj($params["cnpj"]);
                }
                $pessoajuridica->razaosocial = $params["razaosocial"];
                $pessoajuridica->inscricaoestadual = $params["inscestadual"];
                $pessoajuridica->inscricaomunicipal = $params["inscmunicipal"];
                $pessoajuridica->datafund = $util->converterDataUSA($params["fundacao"]);
                if ($pessoajuridica->save() == false) {
                    $transaction->rollback("Não foi possível salvar a pj!");
                }
                if($params["email"] != $pessoaemail->email){
                    $pessoaemail->setTransaction($transaction);
                    $pessoaemail->email = $params["email"];
                    if ($pessoaemail->save() == false) {
                        $transaction->rollback("Não foi possível salvar o email!");
                    }
                }
                $empresaparams->setTransaction($transaction);
                $empresaparams->mail_host = $params["mail_host"];
                $empresaparams->mail_smtpssl = $params["mail_smtpssl"];
                $empresaparams->mail_user = $params["mail_user"];
                $empresaparams->mail_passwrd = $params["mail_passwrd"];
                $empresaparams->mail_port = $params["mail_port"];
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
            $del = $core->deletarPessoaAction($empresa->id_pessoa);
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
