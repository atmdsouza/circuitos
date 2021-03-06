<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\UnidadeConsumidora;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class UnidadeConsumidoraOP extends UnidadeConsumidora
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return UnidadeConsumidora::pesquisarUnidadeConsumidora($dados);
    }

    public function cadastrar(UnidadeConsumidora $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new UnidadeConsumidora();
            $id_conta_agrupadora = ($objArray->getIdContaAgrupadora()) ? $objArray->getIdContaAgrupadora() : null;
            $objeto->setTransaction($transaction);
            $objeto->setIdContaAgrupadora($id_conta_agrupadora);
            $objeto->setCodigoContaContrato($objArray->getCodigoContaContrato());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setObservacao(mb_strtoupper($objArray->getObservacao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a Unidade Consumidora: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(UnidadeConsumidora $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = UnidadeConsumidora::findFirst($objArray->getId());
            $id_conta_agrupadora = ($objArray->getIdContaAgrupadora()) ? $objArray->getIdContaAgrupadora() : null;
            $objeto->setTransaction($transaction);
            $objeto->setIdContaAgrupadora($id_conta_agrupadora);
            $objeto->setCodigoContaContrato($objArray->getCodigoContaContrato());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setObservacao(mb_strtoupper($objArray->getObservacao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a unidade consumidora!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(UnidadeConsumidora $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = UnidadeConsumidora::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a unidade consumidora!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(UnidadeConsumidora $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = UnidadeConsumidora::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a unidade consumidora!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(UnidadeConsumidora $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = UnidadeConsumidora::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir a unidade consumidora!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarUnidadeConsumidora($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = UnidadeConsumidora::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'id_conta_agrupadora' => $objeto->getIdContaAgrupadora(),
                'desc_conta_agrupadora' => $objeto->getContaAgrupadoraPai(),
                'codigo_conta_contrato' => $objeto->getCodigoContaContrato(),
                'descricao' => $objeto->getDescricao(),
                'observacao' => $objeto->getObservacao()
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