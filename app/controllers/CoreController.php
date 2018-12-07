<?php

namespace Circuitos\Controllers;

use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Http\Response as Response;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

use Circuitos\Controllers\ControllerBase;

use Circuitos\Models\Pessoa;
use Circuitos\Models\PessoaJuridica;
use Circuitos\Models\PessoaFisica;
use Circuitos\Models\PessoaEndereco;
use Circuitos\Models\PessoaEmail;
use Circuitos\Models\PessoaTelefone;
use Circuitos\Models\PessoaContato;
use Circuitos\Models\Empresa;
use Circuitos\Models\EndEndereco;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Util\Util;

require APP_PATH . '/library/PHPMailer/src/Exception.php';
require APP_PATH . '/library/PHPMailer/src/PHPMailer.php';
require APP_PATH . '/library/PHPMailer/src/SMTP.php';

class CoreController extends ControllerBase
{

    public function initialize()
    {
        $this->tag->setTitle("Circuitos");
        parent::initialize();
    }

    public function ativarPessoaAction($id_pessoa)
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $manager = new TxManager();
        try {
            $pessoa = Pessoa::findFirst("id={$id_pessoa}");
            $transaction = $manager->get();
            $pessoa->setTransaction($transaction);
            $pessoa->setAtivo(1);
            $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            $transaction->commit();
            return True;
        } catch (TxFailed $e) {
            return False;
        }
    }

    public function inativarPessoaAction($id_pessoa)
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $manager = new TxManager();
        try {
            $pessoa = Pessoa::findFirst("id={$id_pessoa}");
            $transaction = $manager->get();
            $pessoa->setTransaction($transaction);
            $pessoa->setAtivo(0);
            $pessoa->setUpdateAt(date("Y-m-d H:i:s"));
            if ($pessoa->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            $transaction->commit();
            return True;
        } catch (TxFailed $e) {
            return False;
        }
    }

    public function deletarPessoaAction($id_pessoa)
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $manager = new TxManager();
        try {
            $pessoa = Pessoa::findFirst("id={$id_pessoa}");
            $transaction = $manager->get();
            if ($pessoa->delete() == false) {
                $transaction->rollback("Não foi possível deletar a pessoa!");
            }
            $transaction->commit();
            return True;
        } catch (TxFailed $e) {
            return False;
        }
    }

    public function deletarPessoaEnderecoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $manager = new TxManager();
        $dados = filter_input_array(INPUT_POST);
        try {
            $p = PessoaEndereco::findFirst("id={$dados["id"]}");
            $transaction = $manager->get();
            if ($p->delete() == false) {
                $transaction->rollback("Não foi possível deletar a registro!");
            }
            $transaction->commit();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } catch (TxFailed $e) {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "message" => "Ocorreu um erro ao tentar apagar o registro, por favor, tente novamente!"
            )));
            return $response;
        }
    }

    public function deletarPessoaEmailAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $manager = new TxManager();
        $dados = filter_input_array(INPUT_POST);
        try {
            $p = PessoaEmail::findFirst("id={$dados["id"]}");
            $transaction = $manager->get();
            if ($p->delete() == false) {
                $transaction->rollback("Não foi possível deletar a registro!");
            }
            $transaction->commit();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } catch (TxFailed $e) {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "message" => "Ocorreu um erro ao tentar apagar o registro, por favor, tente novamente!"
            )));
            return $response;
        }
    }

    public function deletarPessoaContatoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $manager = new TxManager();
        $dados = filter_input_array(INPUT_POST);
        try {
            $p = PessoaContato::findFirst("id={$dados["id"]}");
            $transaction = $manager->get();
            if ($p->delete() == false) {
                $transaction->rollback("Não foi possível deletar a registro!");
            }
            $transaction->commit();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } catch (TxFailed $e) {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "message" => "Ocorreu um erro ao tentar apagar o registro, por favor, tente novamente!"
            )));
            return $response;
        }
    }

    public function deletarPessoaTelefoneAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $manager = new TxManager();
        $dados = filter_input_array(INPUT_POST);
        try {
            $p = PessoaTelefone::findFirst("id={$dados["id"]}");
            $transaction = $manager->get();
            if ($p->delete() == false) {
                $transaction->rollback("Não foi possível deletar a registro!");
            }
            $transaction->commit();
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } catch (TxFailed $e) {
            $response->setContent(json_encode(array(
                "operacao" => False,
                "message" => "Ocorreu um erro ao tentar apagar o registro, por favor, tente novamente!"
            )));
            return $response;
        }
    }

    public function validarEmailAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $util = new Util();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if ($dados["email"]) {
            $email = PessoaEmail::findFirst("email='{$dados["email"]}'");
            if($util->validate_email($dados["email"])) {
                if ($email) {
                    $response->setContent(json_encode(array(
                        "operacao" => True,
                        "message" => "O e-mail digitado já existe, por favor, escolha um novo!"
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
                    "operacao" => True,
                    "message" => "O e-mail digitado não é válido, por favor, tente novamente!"
                )));
                return $response;
            }
        }
    }

    public function validarCNPJAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $cnpj = PessoaJuridica::findFirst("cnpj='{$dados["cnpj"]}'");
        if ($cnpj) {
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False
            )));
            return $response;
        }
    }

    public function validarCPFAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $cpf = PessoaFisica::findFirst("cpf='{$dados["cpf"]}'");
        if ($cpf) {
            $response->setContent(json_encode(array(
                "operacao" => True
            )));
            return $response;
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False
            )));
            return $response;
        }
    }

    public function completaEnderecoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $endereco = EndEndereco::findFirst("cep='{$dados["cep"]}'");
        $end = [
            "cep" => $endereco->getCep(),
            "logradouro" => $endereco->getTipoLogradouro() . " " . $endereco->getLogradouro(),
            "bairro" => $endereco->EndBairro->bairro,
            "cidade" => $endereco->EndCidade->cidade,
            "uf" => $endereco->EndCidade->EndEstado->estado,
            "sigla_uf" => $endereco->EndCidade->EndEstado->uf
        ];
        if ($endereco) {
            $response->setContent(json_encode(array(
                "endereco" => $end,
                "operacao" => True
            )));
            return $response;
        } else {
            $response->setContent(json_encode(array(
                "operacao" => False
            )));
            return $response;
        }
    }

    public function enviarEmailAction($id_empresa=null, $address=null, $address_name=null, $attach=null, $subject=null, $content=null)
    {
        $logger = new FileAdapter(BASE_PATH . "/logs/systemlog.log");
        $empresa = Empresa::findFirst("id={$id_empresa}");
        $host=$empresa->EmpresaParametros->mail_host;
        $user=$empresa->EmpresaParametros->mail_user;
        $pass=$empresa->EmpresaParametros->mail_passwrd;
        $smtlssl=$empresa->EmpresaParametros->mail_smtpssl;
        $port=$empresa->EmpresaParametros->mail_port;
        $from=$empresa->Pessoa->PessoaEmail[0]->email;
        $from_name=$empresa->Pessoa->nome;

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $user;
            $mail->Password = $pass;
            $mail->SMTPSecure = $smtlssl;
            $mail->Port = $port;
            //Recipients
            $mail->setFrom($from,  $from_name);
            $mail->addAddress($address, $address_name);        
            //Attachments
            if($attach){
                $mail->addAttachment($attach);
            }        
            //Content
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $content;
            //Send
            $mail->send();
            $mail->ClearAllRecipients();
            $mail->ClearAttachments();
            return True;
        } catch (Exception $e) {
            $logger->error('Mensagem não enviada. Mailer Error: ' . $mail->ErrorInfo);
            return False;
        }
    }

}

