<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\SetEquipamento;
use Circuitos\Models\SetEquipamentoComponentes;

use Util\Util;

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
            if (count($arrayObjComponente) > 0) {
                foreach($arrayObjComponente as $objComponente){
                    $objetoComponente = new SetEquipamentoComponentes();
                    $objetoComponente->setTransaction($transaction);
                    $objetoComponente->setIdSetEquipamento($objeto->getId());
                    $objetoComponente->setIdContrato(($objComponente->getIdContrato()) ? $objComponente->getIdContrato() : null);
                    $objetoComponente->setIdEquipamento($objComponente->getIdEquipamento());
                    $objetoComponente->setIdFornecedor($objComponente->getIdFornecedor());
                    $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoComponente->save() == false) {
                        $transaction->rollback("Não foi possível salvar o SetEquipamentoComponente!");
                    }
                }
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterar(SetEquipamento $objArray, $arrayObjComponente)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetEquipamento::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar o SetEquipamento!");
            }
            if (count($arrayObjComponente) > 0) {
                foreach($arrayObjComponente as $objComponente){
                    $objetoComponente = new SetEquipamentoComponentes();
                    $objetoComponente->setTransaction($transaction);
                    $objetoComponente->setIdSetEquipamento($objeto->getId());
                    $objetoComponente->setIdContrato(($objComponente->getIdContrato()) ? $objComponente->getIdContrato() : null);
                    $objetoComponente->setIdEquipamento($objComponente->getIdEquipamento());
                    $objetoComponente->setIdFornecedor($objComponente->getIdFornecedor());
                    $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoComponente->save() == false) {
                        $transaction->rollback("Não foi possível salvar o SetEquipamentoComponente!");
                    }
                }
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
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objeto)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarComponentesSetEquipamento($id_set_equipamento)
    {
        try {
            $objetosComponente = SetEquipamentoComponentes::find('id_set_equipamento = ' . $id_set_equipamento);
            $arrTransporte = [];
            foreach ($objetosComponente as $key => $objetoComponente){
                $objTransporte = new \stdClass();
                $objTransporte->id_componente = $objetoComponente->getId();
                $objTransporte->id_fabricante = $objetoComponente->getIdFabricante();
                $objTransporte->ds_fabricante = $objetoComponente->getFabricante();
                $objTransporte->id_modelo = $objetoComponente->getIdModelo();
                $objTransporte->ds_modelo = $objetoComponente->getModelo();
                $objTransporte->id_equipamento = $objetoComponente->getIdEquipamento();
                $objTransporte->ds_equipamento = $objetoComponente->getEquipamento();
                $objTransporte->numserie_patrimonio = $objetoComponente->getNumSerie() .' / '. $objetoComponente->getNumPatrimonio();
                $objTransporte->id_contrato = $objetoComponente->getIdContrato();
                $objTransporte->ds_contrato = $objetoComponente->getContrato();
                $objTransporte->id_fornecedor = $objetoComponente->getIdFornecedor();
                $objTransporte->ds_fornecedor = $objetoComponente->getFornecedor();
                array_push($arrTransporte, $objTransporte);
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $arrTransporte)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarComponenteSetEquipamento($id)
    {
        try {
            $objetoComponente = SetEquipamentoComponentes::findFirst('id= ' . $id);
            $objTransporte = new \stdClass();
            $objTransporte->id_componente = $objetoComponente->getId();
            $objTransporte->id_fabricante = $objetoComponente->getIdFabricante();
            $objTransporte->ds_fabricante = $objetoComponente->getFabricante();
            $objTransporte->id_modelo = $objetoComponente->getIdModelo();
            $objTransporte->ds_modelo = $objetoComponente->getModelo();
            $objTransporte->id_equipamento = $objetoComponente->getIdEquipamento();
            $objTransporte->ds_equipamento = $objetoComponente->getEquipamento();
            $objTransporte->numserie_patrimonio = $objetoComponente->getNumSerie() .' / '. $objetoComponente->getNumPatrimonio();
            $objTransporte->id_contrato = $objetoComponente->getIdContrato();
            $objTransporte->ds_contrato = $objetoComponente->getContrato();
            $objTransporte->id_fornecedor = $objetoComponente->getIdFornecedor();
            $objTransporte->ds_fornecedor = $objetoComponente->getFornecedor();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objTransporte)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterarComponenteEquipamento(SetEquipamentoComponentes $objComponente)
    {
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = SetEquipamentoComponentes::findFirst('id='.$objComponente->getId());
            $objetoComponente->setTransaction($transaction);
            $objetoComponente->setIdContrato(($objComponente->getIdContrato()) ? $objComponente->getIdContrato() : null);
            $objetoComponente->setIdEquipamento($objComponente->getIdEquipamento());
            $objetoComponente->setIdFornecedor($objComponente->getIdFornecedor());
            $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoComponente->save() == false) {
                $messages = $objetoComponente->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Não foi possível salvar o SetEquipamentoComponente: " . $errors);
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoComponente)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function deletarComponenteEquipamento(SetEquipamentoComponentes $objComponente)
    {
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = SetEquipamentoComponentes::findFirst('id='.$objComponente->getId());
            $id_set_equipamento = $objetoComponente->getIdSetEquipamento();
            $objetoComponente->setTransaction($transaction);
            if ($objetoComponente->delete() == false) {
                $transaction->rollback("Não foi possível deletar o SetEquipamentoComponente!");
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $id_set_equipamento)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

}