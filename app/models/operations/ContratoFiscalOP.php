<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\ContratoFiscal;
use Circuitos\Models\ContratoFiscalAnexo;
use Circuitos\Models\ContratoFiscalHasContrato;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Util\Util;

class ContratoFiscalOP extends ContratoFiscal
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return ContratoFiscal::pesquisarContratoFiscal($dados);
    }

    public function cadastrar(ContratoFiscal $objArray, ContratoFiscalHasContrato $objVicunlo)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new ContratoFiscal();
            $objeto->setTransaction($transaction);
            $objeto->setIdUsuario($objArray->getIdUsuario());
            $objeto->setIdFiscalSuplente(($objArray->getIdFiscalSuplente()) ? $objArray->getIdFiscalSuplente() : null);
            $objeto->setTipoFiscal($objArray->getTipoFiscal());
            $objeto->setDataNomeacao(($objArray->getDataNomeacao()) ? $util->converterDataUSA($objArray->getDataNomeacao()) : null);
            $objeto->setDocumentoNomeacao(mb_strtoupper($objArray->getDocumentoNomeacao(), $this->encode));
            $objeto->setDataCriacao(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar o fiscal: " . $errors);
            }
            $objetoVicunlo = new ContratoFiscalHasContrato();
            $objetoVicunlo->setTransaction($transaction);
            $objetoVicunlo->setIdContrato($objVicunlo->getIdContrato());
            $objetoVicunlo->setIdContratoFiscal($objeto->getId());
            if ($objetoVicunlo->save() === false) {
                $messages = $objetoVicunlo->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar o vinculo do fiscal: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(ContratoFiscal $objArray, ContratoFiscalHasContrato $objVicunlo)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFiscal::findFirst('id='.$objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdUsuario($objArray->getIdUsuario());
            $objeto->setIdFiscalSuplente(($objArray->getIdFiscalSuplente()) ? $objArray->getIdFiscalSuplente() : null);
            $objeto->setTipoFiscal($objArray->getTipoFiscal());
            $objeto->setDataNomeacao(($objArray->getDataNomeacao()) ? $util->converterDataUSA($objArray->getDataNomeacao()) : null);
            $objeto->setDocumentoNomeacao(mb_strtoupper($objArray->getDocumentoNomeacao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao alterar o fiscal: " . $errors);
            }
            $objetoVicunlo = ContratoFiscalHasContrato::findFirst('id_contrato_fiscal='.$objeto->getId().' AND id_contrato='.$objVicunlo->getIdContrato());
            $objetoVicunlo->setTransaction($transaction);
            $objetoVicunlo->setIdContrato($objVicunlo->getIdContrato());
            $objetoVicunlo->setIdContratoFiscal($objeto->getId());
            if ($objetoVicunlo->save() === false) {
                $messages = $objetoVicunlo->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar o vinculo do fiscal: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(ContratoFiscal $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFiscal::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao alterar o fiscal: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(ContratoFiscal $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFiscal::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataInativacao(date('Y-m-d H:i:s'));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao alterar o fiscal: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(ContratoFiscal $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFiscal::findFirst('id='.$objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao excluir o fiscal: " . $errors);
            }
            $objetoVinculo = ContratoFiscalHasContrato::findFirst('id_contrato_fiscal='.$objeto->getId().' AND id_contrato='.$objeto->getIdContrato());
            $objetoVinculo->setTransaction($transaction);
            if ($objetoVinculo->delete() === false) {
                $messages = $objetoVinculo->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao excluir o vinculo: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoFiscal($id)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $objeto = ContratoFiscal::findFirst("id={$id}");
            $objDescricao = new \stdClass();
            $objDescricao->nome_fiscal = $objeto->getNomeFiscal();
            $objDescricao->nome_fiscal_suplente = $objeto->getNomeFiscalSuplente();
            $objDescricao->ds_contrato = $objeto->getNumeroContrato();
            $objDescricao->id_contrato = $objeto->getIdContrato();
            $objDescricao->ds_data_nomeacao = ($objeto->getDataNomeacao()) ? $util->converterDataParaBr($objeto->getDataNomeacao()) : null;
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True, "dados" => $objeto, "descricoes" => $objDescricao)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoFiscalNome($id)
    {
        $objeto = ContratoFiscal::findFirst("id={$id}");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True,"dados" => $objeto->getNomeFiscal())));
        return $response;
    }

    public function visualizarContratoFiscalAnexos($id_contrato_fiscal)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $objetosComponentes = ContratoFiscalAnexo::find('id_contrato_fiscal= ' . $id_contrato_fiscal);
            $arrTransporte = [];
            foreach ($objetosComponentes as $objetoComponente){
                chmod($objetoComponente->getUrlAnexo(), 0777);
                $url_base = explode("/", $objetoComponente->getUrlAnexo());
                $url = $url_base[count($url_base)-5].'/'.$url_base[count($url_base)-4].'/'.$url_base[count($url_base)-3].'/'.$url_base[count($url_base)-2].'/'.$url_base[count($url_base)-1];
                $objTransporte = new \stdClass();
                $objTransporte->id_contrato_anexo = $objetoComponente->getId();
                $objTransporte->id_contrato = $objetoComponente->getIdContratoFiscal();
                $objTransporte->id_anexo = $objetoComponente->getIdAnexo();
                $objTransporte->id_tipo_anexo = $objetoComponente->getIdTipoAnexo();
                $objTransporte->ds_tipo_anexo = $objetoComponente->getDescricaoTipoAnexo();
                $objTransporte->descricao = $objetoComponente->getDescricaoAnexo();
                $objTransporte->url = $url;
                $objTransporte->data_criacao = $util->converterDataHoraParaBr($objetoComponente->getDataCriacaoAnexo());
                array_push($arrTransporte, $objTransporte);
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $arrTransporte)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function validacaoFiscalTitular($id_contrato)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetosFiscais = ContratoFiscalHasContrato::find('id_contrato= ' . $id_contrato);
            $temTitular = false;
            foreach ($objetosFiscais as $objetoFiscal)
            {
                $fiscal = ContratoFiscal::findFirst('id='.$objetoFiscal->getIdContratoFiscal());
                if ($fiscal->getTipoFiscal() == 1){
                    $temTitular = true;
                    $id_usuario = $fiscal->getIdUsuario();
                    $nome_usuario = $fiscal->getNomeFiscal();
                }
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"temTitular" => $temTitular, "id_usuario" => $id_usuario, "nome_usuario" => $nome_usuario)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }
}