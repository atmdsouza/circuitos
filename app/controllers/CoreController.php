<?php

namespace Circuitos\Controllers;

use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Http\Response as Response;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

use Circuitos\Models\Conectividade;
use Circuitos\Models\Empresa;
use Circuitos\Models\EndEndereco;
use Circuitos\Models\EndEstado;
use Circuitos\Models\EndCidade;
use Circuitos\Models\EstacaoTelecon;
use Circuitos\Models\Pessoa;
use Circuitos\Models\PessoaJuridica;
use Circuitos\Models\PessoaFisica;
use Circuitos\Models\PessoaEndereco;
use Circuitos\Models\PessoaEmail;
use Circuitos\Models\PessoaTelefone;
use Circuitos\Models\PessoaContato;
use Circuitos\Models\SetEquipamentoComponentes;
use Circuitos\Models\SetSegurancaComponentes;
use Circuitos\Models\SetSegurancaContato;

use Circuitos\Models\Operations\CidadeDigitalOP;
use Circuitos\Models\Operations\CoreOP;
use Circuitos\Models\Operations\ConectividadeOP;
use Circuitos\Models\Operations\ContratoOP;
use Circuitos\Models\Operations\EstacaoTeleconOP;
use Circuitos\Models\Operations\PropostaComercialOP;
use Circuitos\Models\Operations\PropostaComercialServicoOP;
use Circuitos\Models\Operations\PropostaComercialServicoGrupoOP;
use Circuitos\Models\Operations\PropostaComercialServicoUnidadeOP;
use Circuitos\Models\Operations\SetSegurancaOP;
use Circuitos\Models\Operations\SetEquipamentoOP;
use Circuitos\Models\Operations\TerrenoOP;
use Circuitos\Models\Operations\TorreOP;
use Circuitos\Models\Operations\UnidadeConsumidoraOP;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Util\Util;

require APP_PATH . "/library/PHPMailer/src/Exception.php";
require APP_PATH . "/library/PHPMailer/src/PHPMailer.php";
require APP_PATH . "/library/PHPMailer/src/SMTP.php";

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

    public function listaEstadosAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $estados = EndEstado::find();
        $est = [
            "uf" => $estados->getUf()
        ];
        $response->setContent(json_encode(array(
            "estados" => $est,
            "operacao" => True
        )));
        return $response;
    }

    public function listaCidadesAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $cidade = EndCidade::find("uf='{$dados["uf"]}'");
        if ($cidade) {
            $response->setContent(json_encode(array(
                "cidade" => $cidade,
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

    public function uploadAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();

        $modulo = $this->router->getControllerName();
        $action = $this->router->getActionName();

        $diretorio = BASE_PATH . "/data/" . $modulo . "/" . $action;

        if($this->request->hasFiles() !== false) {

            // get uploader service or \Uploader\Uploader
            $uploader = $this->di->get("uploader");

            // setting up uloader rules
            $uploader->setRules([
                "dynamic"   =>  $diretorio,
                "mimes"     =>  [       // any allowed mime types
                    "image/gif",
                    "image/jpeg",
                    "image/png",
                ],
                "extensions"     =>  [  // any allowed extensions
                    "gif",
                    "jpeg",
                    "jpg",
                    "png",
                ],
                "sanitize" => true,
                "hash"     => "md5"
            ]);

            if($uploader->isValid() === true) {

                $uploader->move(); // upload files array result

                $uploader->getInfo(); // var dump to see upload files

            }
            else {
                $uploader->getErrors(); // var_dump errors
            }
        }

        var_dump($diretorio);
        exit;

        $response->setContent(json_encode(array(
            "operacao" => False
        )));
        return $response;
    }

    public function enviarEmailAction($id_empresa=null, $address=null, $address_name=null, $attach=null, $subject=null, $content=null)
    {
        $logger = new FileAdapter(BASE_PATH . "/logs/systemlog.log");
        $empresa = Empresa::findFirst("id={$id_empresa}");
        $host=$empresa->getHostEmpresa();
        $user=$empresa->getMailUserEmpresa();
        $pass=$empresa->getMailPswEmpresa();
        $smtlssl=$empresa->getMailSmtpEmpresa();
        $port=$empresa->getMailPortEmpresa();
        $from=$empresa->getEMailEmpresa();
        $from_name=$empresa->getNomeEmpresa();

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

    public function processarAjaxAutocompleteAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $dados = filter_input_array(INPUT_GET);
        switch ($dados['metodo'])
        {
            case 'completarEndereco':
                $objeto = new CoreOP();
                return $objeto->completarEndereco();
                break;
            case 'completarCidades':
                $objeto = new CoreOP();
                return $objeto->completarCidades();
                break;
            case 'cidadesDigitaisAtivas':
                $objeto = new CoreOP();
                return $objeto->cidadesDigitaisAtivas();
                break;
            case 'estacoesTeleconAtivas':
                $objeto = new CoreOP();
                return $objeto->estacoesTeleconAtivas();
                break;
            case 'tiposCidadesDigitaisAtivas':
                $objeto = new CoreOP();
                return $objeto->tiposCidadesDigitaisAtivas();
                break;
            case 'equipamentoSeriePatrimonioAtivos':
                $objeto = new CoreOP();
                return $objeto->equipamentoSeriePatrimonioAtivos();
                break;
            case 'equipamentoNumeroSerie':
                $objeto = new CoreOP();
                return $objeto->equipamentoNumeroSerie();
                break;
            case 'fabricantesAtivos':
                $objeto = new CoreOP();
                return $objeto->fabricantesAtivos();
                break;
            case 'fornecedoresAtivos':
                $objeto = new CoreOP();
                return $objeto->fornecedoresAtivos();
                break;
            case 'modelosAtivos':
                $objeto = new CoreOP();
                return $objeto->modelosAtivos();
                break;
            case 'terrenosAtivos':
                $objeto = new CoreOP();
                return $objeto->terrenosAtivos();
                break;
            case 'torresAtivas':
                $objeto = new CoreOP();
                return $objeto->torresAtivas();
                break;
            case 'setsEquipamentosAtivos':
                $objeto = new CoreOP();
                return $objeto->setsEquipamentosAtivos();
                break;
            case 'setsSegurancaAtivos':
                $objeto = new CoreOP();
                return $objeto->setsSegurancaAtivos();
                break;
            case 'equipamentosAtivos':
                $objeto = new CoreOP();
                return $objeto->equipamentosAtivos();
                break;
            case 'contasAgrupadorasAtivas':
                $objeto = new CoreOP();
                return $objeto->contasAgrupadorasAtivas();
                break;
            case 'unidadeConsumidorasAtivas':
                $objeto = new CoreOP();
                return $objeto->unidadeConsumidorasAtivas();
                break;
            case 'gruposServicoAtivos':
                $objeto = new CoreOP();
                return $objeto->gruposServicoAtivos();
                break;
            case 'servicoGruposAtivos':
                $objeto = new CoreOP();
                return $objeto->servicoGruposAtivos();
                break;
            case 'servicoUnidadesAtivos':
                $objeto = new CoreOP();
                return $objeto->servicoUnidadesAtivos();
                break;
        }
    }

    public function processarAjaxVisualizarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $dados = filter_input_array(INPUT_GET);
        switch ($dados['metodo'])
        {
            case 'visualizarCidadeDigital':
                $objeto = new CidadeDigitalOP();
                return $objeto->visualizarCidadeDigital($dados['id']);
                break;
            case 'visualizarCdConectividades':
                $objeto = new CidadeDigitalOP();
                return $objeto->visualizarCdConectividades($dados['id']);
                break;
            case 'visualizarCdConectividade':
                $objeto = new CidadeDigitalOP();
                return $objeto->visualizarCdConectividade($dados['id']);
                break;
            case 'visualizarCdETelecons':
                $objeto = new CidadeDigitalOP();
                return $objeto->visualizarCdETelecons($dados['id']);
                break;
            case 'visualizarCdETelecon':
                $objeto = new CidadeDigitalOP();
                return $objeto->visualizarCdETelecon($dados['id']);
                break;
            case 'visualizarContrato':
                $objeto = new ContratoOP();
                return $objeto->visualizarContrato($dados['id']);
                break;
            case 'visualizarConectividade':
                $objeto = new ConectividadeOP();
                return $objeto->visualizarConectividade($dados['id']);
                break;
            case 'visualizarEstacaoTelecon':
                $objeto = new EstacaoTeleconOP();
                return $objeto->visualizarEstacaoTelecon($dados['id']);
                break;
            case 'visualizarPropostaComercial':
                $objeto = new PropostaComercialOP();
                return $objeto->visualizarPropostaComercial($dados['id']);
                break;
            case 'visualizarSetSeguranca':
                $objeto = new SetSegurancaOP();
                return $objeto->visualizarSetSeguranca($dados['id']);
                break;
            case 'visualizarComponentesSetSeguranca':
                $objeto = new SetSegurancaOP();
                return $objeto->visualizarComponentesSetSeguranca($dados['id']);
                break;
            case 'visualizarComponenteSetSeguranca':
                $objeto = new SetSegurancaOP();
                return $objeto->visualizarComponenteSetSeguranca($dados['id']);
                break;
            case 'visualizarSetEquipamento':
                $objeto = new SetEquipamentoOP();
                return $objeto->visualizarSetEquipamento($dados['id']);
                break;
            case 'visualizarComponentesSetEquipamento':
                $objeto = new SetEquipamentoOP();
                return $objeto->visualizarComponentesSetEquipamento($dados['id']);
                break;
            case 'visualizarComponenteSetEquipamento':
                $objeto = new SetEquipamentoOP();
                return $objeto->visualizarComponenteSetEquipamento($dados['id']);
                break;
            case 'visualizarPropostaComercialServico':
                $objeto = new PropostaComercialServicoOP();
                return $objeto->visualizarPropostaComercialServico($dados['id']);
                break;
            case 'visualizarPropostaComercialServicoGrupo':
                $objeto = new PropostaComercialServicoGrupoOP();
                return $objeto->visualizarPropostaComercialServicoGrupo($dados['id']);
                break;
            case 'visualizarPropostaComercialServicoUnidade':
                $objeto = new PropostaComercialServicoUnidadeOP();
                return $objeto->visualizarPropostaComercialServicoUnidade($dados['id']);
                break;
            case 'visualizarTerreno':
                $objeto = new TerrenoOP();
                return $objeto->visualizarTerreno($dados['id']);
                break;
            case 'visualizarTorre':
                $objeto = new TorreOP();
                return $objeto->visualizarTorre($dados['id']);
                break;
            case 'visualizarUnidadeConsumidora':
                $objeto = new UnidadeConsumidoraOP();
                return $objeto->visualizarUnidadeConsumidora($dados['id']);
                break;
        }
    }

    public function processarAjaxAcaoAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $dados = filter_input_array(INPUT_POST);
        switch ($dados['metodo'])
        {
            case 'alterarComponenteSeguranca':
                $dados_form = $dados['array_dados'];
                $objeto = new SetSegurancaOP();
                $objComponente = new SetSegurancaComponentes();
                $objComponente->setId($dados_form['id']);
                $objComponente->setIdTipo($dados_form['id_tipo']);
                $objComponente->setIdContrato($dados_form['id_contrato']);
                $objComponente->setIdFornecedor($dados_form['id_fornecedor']);
                $objComponente->setPropriedadeProdepa($dados_form['propriedade_prodepa']);
                $objComponente->setSenha($dados_form['senha']);
                $objComponente->setValidade($dados_form['validade']);
                $objComponente->setEnderecoChave($dados_form['endereco_chave']);
                $objComponenteContato = new SetSegurancaContato();
                if (!empty($dados_form['cont_id'])){
                    $objComponenteContato->setId($dados_form['cont_id']);
                }
                $objComponenteContato->setNome($dados_form['nome']);
                $objComponenteContato->setTelefone($dados_form['telefone']);
                $objComponenteContato->setEmail($dados_form['email']);
                return $objeto->alterarComponenteSeguranca($objComponente, $objComponenteContato);
                break;
            case 'deletarComponenteSeguranca':
                $objeto = new SetSegurancaOP();
                $objComponente = new SetSegurancaComponentes();
                $objComponente->setId($dados['id']);
                return $objeto->deletarComponenteSeguranca($objComponente);
                break;
            case 'alterarComponenteEquipamento':
                $dados_form = $dados['array_dados'];
                $objeto = new SetEquipamentoOP();
                $objComponente = new SetEquipamentoComponentes();
                $objComponente->setId($dados_form['id']);
                $objComponente->setIdContrato(($dados_form['id_contrato']) ? $dados_form['id_contrato'] : null);
                $objComponente->setIdEquipamento($dados_form['id_equipamento']);
                $objComponente->setIdFornecedor($dados_form['id_fornecedor']);
                return $objeto->alterarComponenteEquipamento($objComponente);
                break;
            case 'deletarComponenteEquipamento':
                $objeto = new SetEquipamentoOP();
                $objComponente = new SetEquipamentoComponentes();
                $objComponente->setId($dados['id']);
                return $objeto->deletarComponenteEquipamento($objComponente);
                break;
            case 'alterarCdConectividade':
                $dados_form = $dados['array_dados'];
                $objeto = new CidadeDigitalOP();
                $objComponente = new Conectividade();
                $objComponente->setId($dados_form['id']);
                $objComponente->setIdTipo($dados_form['id_tipo']);
                $objComponente->setDescricao($dados_form['descricao']);
                $objComponente->setEndereco($dados_form['endereco']);
                return $objeto->alterarCdConectividade($objComponente);
                break;
            case 'deletarCdConectividade':
                $objeto = new CidadeDigitalOP();
                $objComponente = new Conectividade();
                $objComponente->setId($dados['id']);
                return $objeto->deletarCdConectividade($objComponente);
                break;
            case 'alterarCdETelecon':
                $dados_form = $dados['array_dados'];
                $objeto = new CidadeDigitalOP();
                $objComponente = new EstacaoTelecon();
                $objComponente->setId($dados_form['id']);
                $objComponente->setDescricao($dados_form['descricao']);
                $objComponente->setIdContrato($dados_form['id_contrato']);
                $objComponente->setIdTerreno($dados_form['id_terreno']);
                $objComponente->setIdTorre($dados_form['id_torre']);
                $objComponente->setIdSetEquipamento($dados_form['id_set_equipamento']);
                $objComponente->setIdSetSeguranca($dados_form['id_set_seguranca']);
                $objComponente->setIdUnidadeConsumidora($dados_form['id_unidade_consumidora']);
                return $objeto->alterarCdETelecon($objComponente);
                break;
            case 'deletarCdETelecon':
                $objeto = new CidadeDigitalOP();
                $objComponente = new EstacaoTelecon();
                $objComponente->setId($dados['id']);
                return $objeto->deletarCdETelecon($objComponente);
                break;
        }
    }

}

