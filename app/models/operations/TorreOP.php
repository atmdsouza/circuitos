<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\Torre;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Util\Util;

class TorreOP extends Torre
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return Torre::pesquisarTorre($dados);
    }

    public function cadastrar(Torre $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $objeto->setLatitude($objArray->getLatitude());
            $objeto->setLongitude($objArray->getLongitude());
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(Torre $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $objeto->setLatitude($objArray->getLatitude());
            $objeto->setLongitude($objArray->getLongitude());
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(Torre $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(Torre $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(Torre $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarTorre($id)
    {
        $logger = new FileAdapter($this->arqLog);
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
                'latitude' => $objeto->getLatitude(),
                'longitude' => $objeto->getLongitude(),
                'propriedade_prodepa' => $objeto->getPropriedadeProdepa()
            );
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoArray)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }
}