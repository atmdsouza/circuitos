<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;
use Usuario as Usuario;

require_once APP_PATH . '/library/util/Util.php';

class UsuarioController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $dados = filter_input_array(INPUT_POST);
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, Usuario, $dados);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";
        $usuarios = Usuario::find($parameters);
        $pessoas = Pessoa::find();
        $roles = PhalconRoles::find();
        $paginator = new Paginator([
            'data' => $usuarios,
            'limit'=> 500,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
        $this->view->pessoas = $pessoas;
        $this->view->roles = $roles;
    }

    public function formUsuarioAction()
    {

    }

    public function validarLoginAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $dados = filter_input_array(INPUT_POST);
        $login = Usuario::findFirst("login='{$dados["login"]}'");
        if ($login) {
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => False
            )));
            return $response;
        }
    }

    public function validarEmailAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $dados = filter_input_array(INPUT_POST);
        $email = PessoaEmail::findFirst("email='{$dados["email"]}'");
        if ($email) {
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => False
            )));
            return $response;
        }

    }

    public function criarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        try {
            $dados = filter_input_array(INPUT_POST);
            //Create a transaction manager
            $manager = new TxManager();
            //Request a transaction
            $transaction = $manager->get();
            $pessoa = new Pessoa();
            $pessoa->setTransaction($transaction);
            $pessoa->nome = $dados["nome_pessoa"];
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            $pessoaemail = new PessoaEmail();
            $pessoaemail->setTransaction($transaction);
            $pessoaemail->id_pessoa = $pessoa->id;
            $pessoaemail->id_tipoemail = 43;
            $pessoaemail->principal = 1;
            $pessoaemail->email = $dados["email"];
            $pessoaemail->ativo = 1;
            if ($pessoaemail->save() == false) {
                $transaction->rollback("Não foi possível salvar o email!");
            }
            $usuario = new Usuario();
            $usuario->setTransaction($transaction);
            $usuario->id_pessoa = $pessoa->id;
            $usuario->roles_name = $dados["roles_name"];
            $usuario->login = $dados["login"];
            $usuario->senha = $this->security->hash($dados["senha"]);
            if ($usuario->save() == false) {
                $transaction->rollback("Não foi possível salvar o usuário!");
            }
            //Commita a transação
            $transaction->commit();
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } catch (TxFailed $e) {
            //Instanciar a resposta HTTP
            $response = new Response();
            $response->setContent(json_encode(array(
                "operacao" => False,
                "mensagem" => "Ocorreu um erro: ", $e->getMessage()
            )));
            return $response;
        }
    }

    public function editarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        try {
            $dados = filter_input_array(INPUT_POST);
            $usuario = Usuario::findFirst("id={$dados["id_usuario"]}");
            $pessoa = Pessoa::findFirst("id={$usuario->id_pessoa}");
            $pessoaemail = PessoaEmail::findFirst("id_pessoa={$usuario->id_pessoa}");
            //Create a transaction manager
            $manager = new TxManager();
            //Request a transaction
            $transaction = $manager->get();
            $pessoa->setTransaction($transaction);
            $pessoa->nome = $dados["nome_pessoa"];
            $pessoa->update_at = date("Y-m-d H:i:s");
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            $pessoaemail->setTransaction($transaction);
            $pessoaemail->email = $dados["email"];
            if ($pessoaemail->save() == false) {
                $transaction->rollback("Não foi possível salvar o email!");
            }
            $usuario->setTransaction($transaction);
            $usuario->roles_name = $dados["roles_name"];
            $usuario->login = $dados["login"];
            if ($usuario->save() == false) {
                $transaction->rollback("Não foi possível salvar o usuário!");
            }
            //Commita a transação
            $transaction->commit();
            echo "success";
        } catch (TxFailed $e) {
            echo "Ocorreu um erro: ", $e->getMessage();
        }
    }

    public function ativarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        try {
            $dados = filter_input_array(INPUT_POST);
            $usuario = Usuario::findFirst("id={$dados["id_usuario"]}");
            $pessoa = Pessoa::findFirst("id={$usuario->id_pessoa}");
            //Create a transaction manager
            $manager = new TxManager();
            //Request a transaction
            $transaction = $manager->get();
            $pessoa->setTransaction($transaction);
            $pessoa->ativo = 1;
            $pessoa->update_at = date("Y-m-d H:i:s");
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            //Commita a transação
            $transaction->commit();
            echo "success";
        } catch (TxFailed $e) {
            echo "Ocorreu um erro: ", $e->getMessage();
        }
    }

    public function inativarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        try {
            $dados = filter_input_array(INPUT_POST);
            $usuario = Usuario::findFirst("id={$dados["id_usuario"]}");
            $pessoa = Pessoa::findFirst("id={$usuario->id_pessoa}");
            //Create a transaction manager
            $manager = new TxManager();
            //Request a transaction
            $transaction = $manager->get();
            $pessoa->setTransaction($transaction);
            $pessoa->ativo = 0;
            $pessoa->update_at = date("Y-m-d H:i:s");
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            //Commita a transação
            $transaction->commit();
            echo "success";
        } catch (TxFailed $e) {
            echo "Ocorreu um erro: ", $e->getMessage();
        }
    }

    /**
     * Deletes a usuario
     *
     * @param string $id
     */
    public function deletarUsuarioAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        try {
            $dados = filter_input_array(INPUT_POST);
            $usuario = Usuario::findFirst("id={$dados["id_usuario"]}");
            $pessoa = Pessoa::findFirst("id={$usuario->id_pessoa}");
            $pessoaemail = PessoaEmail::findFirst("id_pessoa={$usuario->id_pessoa}");
            //Create a transaction manager
            $manager = new TxManager();
            //Request a transaction
            $transaction = $manager->get();
            if ($usuario->delete() == false) {
                $transaction->rollback("Não foi possível deletar a pessoa!");
            }
            if ($pessoaemail->delete() == false) {
                $transaction->rollback("Não foi possível deletar o email!");
            }
            if ($pessoa->delete() == false) {
                $transaction->rollback("Não foi possível deletar a pessoa!");
            }
            //Commita a transação
            $transaction->commit();
            echo "success";
        } catch (TxFailed $e) {
            echo "Ocorreu um erro: ", $e->getMessage();
        }
    }
}
