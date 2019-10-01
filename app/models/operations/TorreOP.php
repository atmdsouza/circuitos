<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\Torre;

use Util\Util;

class TorreOP extends Torre
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return Torre::pesquisarTorre($dados);
    }

    public function cadastrar(Torre $objArray)
    {
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new Torre();
            $fornecedor = ($objArray->getIdFornecedor()) ? $objArray->getIdFornecedor() : null;
            $contrato = ($objArray->getIdContrato()) ? $objArray->getIdContrato() : null;
            $objeto->setTransaction($transaction);
            $objeto->setIdFornecedor($fornecedor);
            $objeto->setIdContrato($contrato);
            $objeto->setIdTipo($objArray->getIdTipo());
            $objeto->setPropriedadeProdepa($objArray->getPropriedadeProdepa());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setAltura($util->formataNumero($objArray->getAltura()));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterar(Torre $objArray)
    {
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Torre::findFirst($objArray->getId());
            $fornecedor = ($objArray->getIdFornecedor()) ? $objArray->getIdFornecedor() : null;
            $contrato = ($objArray->getIdContrato()) ? $objArray->getIdContrato() : null;
            $objeto->setTransaction($transaction);
            $objeto->setIdFornecedor($fornecedor);
            $objeto->setIdContrato($contrato);
            $objeto->setIdTipo($objArray->getIdTipo());
            $objeto->setPropriedadeProdepa($objArray->getPropriedadeProdepa());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setAltura($util->formataNumero($objArray->getAltura()));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function ativar(Torre $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Torre::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function inativar(Torre $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Torre::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function excluir(Torre $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Torre::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarTorre($id)
    {
        $util = new Util();
        try {
            $objeto = Torre::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'id_tipo' => $objeto->getIdTipo(),
                'id_contrato' => $objeto->getIdContrato(),
                'desc_contrato' => $objeto->getContrato(),
                'id_fornecedor' => $objeto->getIdFornecedor(),
                'desc_fornecedor' => $objeto->getFornecedor(),
                'descricao' => $objeto->getDescricao(),
                'altura' => $util->formataMoedaReal($objeto->getAltura()),
                'propriedade_prodepa' => $objeto->getPropriedadeProdepa()
            );
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoArray)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }
}