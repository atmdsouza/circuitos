<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\Anexos;
use Circuitos\Models\CircuitosAnexo;
use Circuitos\Models\ContratoAnexo;
use Circuitos\Models\PropostaComercialAnexo;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class AnexosOP extends Anexos
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function cadastrar(Anexos $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new Anexos();
            $objeto->setTransaction($transaction);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setIdTipoAnexo($objArray->getIdTipoAnexo());
            $objeto->setUrl($objArray->getUrl());
            $objeto->setDataCriacao(date('Y-m-d H:i:s'));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar o anexo!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function cadastrarPropostaComercialAnexo(PropostaComercialAnexo $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new PropostaComercialAnexo();
            $objeto->setTransaction($transaction);
            $objeto->setIdAnexo($objArray->getIdAnexo());
            $objeto->setIdPropostaComercial($objArray->getIdPropostaComercial());
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar o vinculo da proposta com o anexo!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function cadastrarContratoAnexo(ContratoAnexo $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new ContratoAnexo();
            $objeto->setTransaction($transaction);
            $objeto->setIdAnexo($objArray->getIdAnexo());
            $objeto->setIdContrato($objArray->getIdContrato());
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar o vinculo da proposta com o anexo!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function cadastrarCircuitosAnexo(CircuitosAnexo $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new CircuitosAnexo();
            $objeto->setTransaction($transaction);
            $objeto->setIdAnexo($objArray->getIdAnexo());
            $objeto->setIdCircuitos($objArray->getIdCircuitos());
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar o vinculo da proposta com o anexo!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluirPropostaComercialAnexo (PropostaComercialAnexo $objPrincipal)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        $id_proposta = $objPrincipal->getIdPropostaComercial();
        try {
            $objPrincipal->setTransaction($transaction);
            $objPrincipal->delete();
            $objAnexo = Anexos::findFirst('id='.$objPrincipal->getIdAnexo());
            unlink($objAnexo->getUrl());
            if ($objAnexo->delete() == false) {
                $transaction->rollback("Não foi possível deletar o vinculo da proposta com o anexo!");
            }
            $transaction->commit();
            return $id_proposta;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluirContratoAnexo (ContratoAnexo $objPrincipal)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        $id = $objPrincipal->getIdContrato();
        try {
            $objPrincipal->setTransaction($transaction);
            $objPrincipal->delete();
            $objAnexo = Anexos::findFirst('id='.$objPrincipal->getIdAnexo());
            unlink($objAnexo->getUrl());
            if ($objAnexo->delete() == false) {
                $transaction->rollback("Não foi possível deletar o vinculo da proposta com o anexo!");
            }
            $transaction->commit();
            return $id;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluirCircuitosAnexo (CircuitosAnexo $objPrincipal)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        $id = $objPrincipal->getIdCircuitos();
        try {
            $objPrincipal->setTransaction($transaction);
            $objPrincipal->delete();
            $objAnexo = Anexos::findFirst('id='.$objPrincipal->getIdAnexo());
            unlink($objAnexo->getUrl());
            if ($objAnexo->delete() == false) {
                $transaction->rollback("Não foi possível deletar o vinculo da proposta com o anexo!");
            }
            $transaction->commit();
            return $id;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }
}