<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\Anexos;
use Circuitos\Models\PropostaComercialAnexo;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class AnexosOP extends Anexos
{
    private $encode = "UTF-8";

    public function cadastrar(Anexos $objArray)
    {
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
            var_dump($e->getMessage());
            return false;
        }
    }

    public function cadastrarPropostaComercialAnexo(PropostaComercialAnexo $objArray)
    {
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
            var_dump($e->getMessage());
            return false;
        }
    }
}