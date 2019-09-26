<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\UnidadeConsumidora;

use Util\Util;

class UnidadeConsumidoraOP extends UnidadeConsumidora
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return UnidadeConsumidora::pesquisarUnidadeConsumidora($dados);
    }

    public function cadastrar(UnidadeConsumidora $objArray)
    {
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new UnidadeConsumidora();
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

    public function alterar(UnidadeConsumidora $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = UnidadeConsumidora::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdCidadeDigital($objArray->getIdCidadeDigital());
            $objeto->setIdTipo($objArray->getIdTipo());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setEndereco(mb_strtoupper($objArray->getEndereco(), $this->encode));
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

    public function ativar(UnidadeConsumidora $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = UnidadeConsumidora::findFirst($objArray->getId());
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

    public function inativar(UnidadeConsumidora $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = UnidadeConsumidora::findFirst($objArray->getId());
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

    public function excluir(UnidadeConsumidora $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = UnidadeConsumidora::findFirst($objArray->getId());
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

    public function visualizarUnidadeConsumidora($id)
    {
        try {
            $objeto = UnidadeConsumidora::findFirst("id={$id}");
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