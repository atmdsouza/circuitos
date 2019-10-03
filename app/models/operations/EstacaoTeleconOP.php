<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\EstacaoTelecon;

class EstacaoTeleconOP extends EstacaoTelecon
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return EstacaoTelecon::pesquisarEstacaoTelecon($dados);
    }

    public function cadastrar(EstacaoTelecon $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $cidadedigital = ($objArray->getIdCidadeDigital()) ? $objArray->getIdCidadeDigital() : null;
            $contrato = ($objArray->getIdContrato()) ? $objArray->getIdContrato() : null;
            $terreno = ($objArray->getIdTerreno()) ? $objArray->getIdTerreno() : null;
            $torre = ($objArray->getIdTorre()) ? $objArray->getIdTorre() : null;
            $setequipamento = ($objArray->getIdSetEquipamento()) ? $objArray->getIdSetEquipamento() : null;
            $setseguranca = ($objArray->getIdSetSeguranca()) ? $objArray->getIdSetSeguranca() : null;
            $unidadeconsumidora = ($objArray->getIdUnidadeConsumidora()) ? $objArray->getIdUnidadeConsumidora() : null;
            $objeto = new EstacaoTelecon();
            $objeto->setTransaction($transaction);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setIdCidadeDigital($cidadedigital);
            $objeto->setIdContrato($contrato);
            $objeto->setIdTerreno($terreno);
            $objeto->setIdTorre($torre);
            $objeto->setIdSetEquipamento($setequipamento);
            $objeto->setIdSetSeguranca($setseguranca);
            $objeto->setIdUnidadeConsumidora($unidadeconsumidora);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar a estação telecon!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterar(EstacaoTelecon $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $cidadedigital = ($objArray->getIdCidadeDigital()) ? $objArray->getIdCidadeDigital() : null;
            $contrato = ($objArray->getIdContrato()) ? $objArray->getIdContrato() : null;
            $terreno = ($objArray->getIdTerreno()) ? $objArray->getIdTerreno() : null;
            $torre = ($objArray->getIdTorre()) ? $objArray->getIdTorre() : null;
            $setequipamento = ($objArray->getIdSetEquipamento()) ? $objArray->getIdSetEquipamento() : null;
            $setseguranca = ($objArray->getIdSetSeguranca()) ? $objArray->getIdSetSeguranca() : null;
            $unidadeconsumidora = ($objArray->getIdUnidadeConsumidora()) ? $objArray->getIdUnidadeConsumidora() : null;
            $objeto = EstacaoTelecon::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setIdCidadeDigital($cidadedigital);
            $objeto->setIdContrato($contrato);
            $objeto->setIdTerreno($terreno);
            $objeto->setIdTorre($torre);
            $objeto->setIdSetEquipamento($setequipamento);
            $objeto->setIdSetSeguranca($setseguranca);
            $objeto->setIdUnidadeConsumidora($unidadeconsumidora);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a estação telecon!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function ativar(EstacaoTelecon $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = EstacaoTelecon::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a estação telecon!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function inativar(EstacaoTelecon $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = EstacaoTelecon::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a estação telecon!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function excluir(EstacaoTelecon $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = EstacaoTelecon::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir a estação telecon!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarEstacaoTelecon($id)
    {
        try {
            $objeto = EstacaoTelecon::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'descricao' => $objeto->getDescricao(),
                'id_cidade_digital' => $objeto->getIdCidadeDigital(),
                'desc_cidade_digital' => $objeto->getNomeCidadeDigital(),
                'id_contrato' => ($objeto->getIdContrato()) ? $objeto->getIdContrato() : null,
                'desc_contrato' => ($objeto->getContrato()) ? $objeto->getContrato() : null,
                'id_terreno' => $objeto->getIdTerreno(),
                'desc_terreno' => $objeto->getTerreno(),
                'id_torre' => $objeto->getIdTorre(),
                'desc_torre' => $objeto->getTorre(),
                'id_set_equipamento' => $objeto->getIdSetEquipamento(),
                'desc_set_equipamento' => $objeto->getSetEquipamento(),
                'id_set_seguranca' => $objeto->getIdSetSeguranca(),
                'desc_set_seguranca' => $objeto->getSetSeguranca(),
                'id_unidade_consumidora' => $objeto->getIdUnidadeConsumidora(),
                'desc_unidade_consumidora' => $objeto->getUnidadeConsumidora()
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