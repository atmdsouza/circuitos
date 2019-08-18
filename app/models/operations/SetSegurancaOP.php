<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\CidadeDigital;
use Circuitos\Models\SetSeguranca;
use Circuitos\Models\Lov;

class SetSegurancaOP extends SetSeguranca
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return SetSeguranca::pesquisarSetSeguranca($dados);
    }

    public function cadastrar(SetSeguranca $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new SetSeguranca();
            $objeto->setIdCidadeDigital($objArray->getIdCidadeDigital());
            $objeto->setIdTipo($objArray->getIdTipo());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setEndereco(mb_strtoupper($objArray->getEndereco(), $this->encode));
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

    public function alterar(SetSeguranca $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetSeguranca::findFirst($objArray->getId());
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

    public function ativar(SetSeguranca $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetSeguranca::findFirst($objArray->getId());
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

    public function inativar(SetSeguranca $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetSeguranca::findFirst($objArray->getId());
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

    public function excluir(SetSeguranca $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetSeguranca::findFirst($objArray->getId());
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

    public function visualizarSetSeguranca($id)
    {
        try {
            $objeto = SetSeguranca::findFirst("id={$id}");
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