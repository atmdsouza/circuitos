<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\CidadeDigital;
use Circuitos\Models\Conectividade;
use Circuitos\Models\EstacaoTelecon;

class CidadeDigitalOP extends CidadeDigital
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return CidadeDigital::pesquisarCidadeDigital($dados);
    }

    public function cadastrar(CidadeDigital $objArray, $arrayObjConectividade, $arrayObjETelecon)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new CidadeDigital();
            $objeto->setTransaction($transaction);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setIdCidade($objArray->getIdCidade());
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a Cidade Digital: " . $errors);
            }
            if (count($arrayObjConectividade) > 0){
                foreach($arrayObjConectividade as $objConectividade){
                    $objetoConectividade = new Conectividade();
                    $objetoConectividade->setTransaction($transaction);
                    $objetoConectividade->setIdCidadeDigital($objeto->getId());
                    $objetoConectividade->setIdTipo($objConectividade->getIdTipo());
                    $objetoConectividade->setDescricao(mb_strtoupper($objConectividade->getDescricao(), $this->encode));
                    $objetoConectividade->setEndereco(mb_strtoupper($objConectividade->getEndereco(), $this->encode));
                    $objetoConectividade->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoConectividade->save() == false) {
                        $messages = $objetoConectividade->getMessages();
                        $errors = "";
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= "[".$messages[$i]."] ";
                        }
                        $transaction->rollback("Erro ao criar a Conectividade: " . $errors);
                    }
                }
            }
            if (count($arrayObjETelecon) > 0){
                foreach($arrayObjETelecon as $objETelecon){
                    $id_contrato = ($objETelecon->getIdContrato()) ? $objETelecon->getIdContrato() : null;
                    $id_set_seguranca = ($objETelecon->getIdSetSeguranca()) ? $objETelecon->getIdSetSeguranca() : null;
                    $id_unidade_consumidora = ($objETelecon->getIdUnidadeConsumidora()) ? $objETelecon->getIdUnidadeConsumidora() : null;
                    $objetoETelecon = new EstacaoTelecon();
                    $objetoETelecon->setTransaction($transaction);
                    $objetoETelecon->setIdCidadeDigital($objeto->getId());
                    $objetoETelecon->setDescricao(mb_strtoupper($objETelecon->getDescricao(), $this->encode));
                    $objetoETelecon->setIdContrato($id_contrato);
                    $objetoETelecon->setIdTerreno($objETelecon->getIdTerreno());
                    $objetoETelecon->setIdTorre($objETelecon->getIdTorre());
                    $objetoETelecon->setIdSetEquipamento($objETelecon->getIdSetEquipamento());
                    $objetoETelecon->setIdSetSeguranca($id_set_seguranca);
                    $objetoETelecon->setIdUnidadeConsumidora($id_unidade_consumidora);
                    $objetoETelecon->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoETelecon->save() == false) {
                        $messages = $objetoConectividade->getMessages();
                        $errors = "";
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= "[".$messages[$i]."] ";
                        }
                        $transaction->rollback("Erro ao criar a Estação Telecon: " . $errors);
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

    public function alterar(CidadeDigital $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = CidadeDigital::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdCidadeDigital($objArray->getIdCidadeDigital());
            $objeto->setIdTipo($objArray->getIdTipo());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setEndereco(mb_strtoupper($objArray->getEndereco(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a cidade digital!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function ativar(CidadeDigital $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = CidadeDigital::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a cidade digital!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function inativar(CidadeDigital $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = CidadeDigital::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a cidade digital!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function excluir(CidadeDigital $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = CidadeDigital::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir a cidade digital!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarCidadeDigital($id)
    {
        try {
            $objeto = CidadeDigital::findFirst("id={$id}");
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