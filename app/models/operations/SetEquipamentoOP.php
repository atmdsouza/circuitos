<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\SetEquipamento;
use Circuitos\Models\SetEquipamentoComponentes;

class SetEquipamentoOP extends SetEquipamento
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return SetEquipamento::pesquisarSetEquipamento($dados);
    }

    public function cadastrar(SetEquipamento $objArray, $arrayObjComponente)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new SetEquipamento();
            $objeto->setTransaction($transaction);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar o SetEquipamento!");
            }
            foreach($arrayObjComponente as $key => $objComponente){
                $objetoComponente = new SetEquipamentoComponentes();
                $objetoComponente->setTransaction($transaction);
                $objetoComponente->setIdSetEquipamento($objeto->getId());
                $objetoComponente->setIdContrato($objComponente->getIdContrato());
                $objetoComponente->setIdEquipamento($objComponente->getIdEquipamento());
                $objetoComponente->setIdFornecedor($objComponente->getIdFornecedor());
                $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
                if ($objetoComponente->save() == false) {
                    $transaction->rollback("Não foi possível salvar o SetEquipamentoComponente!");
                }
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterar(SetEquipamento $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetEquipamento::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdCidadeDigital($objArray->getIdCidadeDigital());
            $objeto->setIdTipo($objArray->getIdTipo());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setEndereco(mb_strtoupper($objArray->getEndereco(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o SetEquipamento!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function ativar(SetEquipamento $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetEquipamento::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o SetEquipamento!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function inativar(SetEquipamento $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetEquipamento::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o SetEquipamento!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function excluir(SetEquipamento $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetEquipamento::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir o SetEquipamento!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarSetEquipamento($id)
    {
        try {
            $objeto = SetEquipamento::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'id_cidade_digital' => $objeto->getIdCidadeDigital(),
                'desc_cidade_digital' => $objeto->getNomeCidadeDigital(),
                'id_tipo' => $objeto->getIdTipo(),
                'descricao' => $objeto->getDescricao(),
                'endereco' => $objeto->getEndereco()
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