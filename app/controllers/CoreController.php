<?php

namespace Circuitos\Controllers;

use Phalcon\Http\Response as Response;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

use Circuitos\Controllers\ControllerBase;
use Circuitos\Models\Pessoa;
use Circuitos\Models\PessoaEmail;
use Circuitos\Models\PessoaJuridica;
use Circuitos\Models\PessoaFisica;
use Circuitos\Models\Empresa;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require APP_PATH . '/library/PHPMailer/src/Exception.php';
require APP_PATH . '/library/PHPMailer/src/PHPMailer.php';
require APP_PATH . '/library/PHPMailer/src/SMTP.php';

class CoreController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function ativarPessoaAction($id_pessoa)
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        try {
            $pessoa = Pessoa::findFirst("id={$id_pessoa}");
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
            return True;
        } catch (TxFailed $e) {
            return False;
        }

    }

    public function inativarPessoaAction($id_pessoa)
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        try {
            $pessoa = Pessoa::findFirst("id={$id_pessoa}");
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
            return True;
        } catch (TxFailed $e) {
            return False;
        }

    }

    public function deletarPessoaAction($id_pessoa)
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        try {
            $pessoa = Pessoa::findFirst("id={$id_pessoa}");
            //Create a transaction manager
            $manager = new TxManager();
            //Request a transaction
            $transaction = $manager->get();
            if ($pessoa->delete() == false) {
                $transaction->rollback("Não foi possível deletar a pessoa!");
            }
            //Commita a transação
            $transaction->commit();
            return True;
        } catch (TxFailed $e) {
            return False;
        }

    }

    public function validarEmailAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $dados = filter_input_array(INPUT_GET);
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

    public function enviarEmailAction($id_empresa=null, $address=null, $address_name=null, $attach=null, $subject=null, $content=null)
    {
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
            var_dump('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
            return False;
            exit;
        }
    }

}

