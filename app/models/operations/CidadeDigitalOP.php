<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\CidadeDigital;
use Circuitos\Models\Conectividade;
use Circuitos\Models\EstacaoTelecon;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class CidadeDigitalOP extends CidadeDigital
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return CidadeDigital::pesquisarCidadeDigital($dados);
    }

    public function cadastrar(CidadeDigital $objArray, $arrayObjConectividade, $arrayObjETelecon)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(CidadeDigital $objArray, $arrayObjConectividade, $arrayObjETelecon)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = CidadeDigital::findFirst($objArray->getId());
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(CidadeDigital $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(CidadeDigital $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(CidadeDigital $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarCidadeDigital($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = CidadeDigital::findFirst("id={$id}");
            $objTransporte = new \stdClass();
            $objTransporte->id = $objeto->getId();
            $objTransporte->descricao = $objeto->getDescricao();
            $objTransporte->id_cidade = $objeto->getIdCidade();
            $objTransporte->ds_cidade = $objeto->getCidade();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objTransporte)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarCdConectividades($id_cidade_digital)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetosComponente = Conectividade::find('id_cidade_digital = ' . $id_cidade_digital);
            $arrTransporte = [];
            foreach ($objetosComponente as $key => $objetoComponente){
                $objTransporte = new \stdClass();
                $objTransporte->id_conectividade = $objetoComponente->getId();
                $objTransporte->id_tipo = $objetoComponente->getIdTipo();
                $objTransporte->ds_tipo = $objetoComponente->getTipoConectividade();
                $objTransporte->descricao = $objetoComponente->getDescricao();
                $objTransporte->endereco = $objetoComponente->getEndereco();
                array_push($arrTransporte, $objTransporte);
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $arrTransporte)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarCdConectividade($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetoComponente = Conectividade::findFirst('id= ' . $id);
            $objTransporte = new \stdClass();
            $objTransporte->id_conectividade = $objetoComponente->getId();
            $objTransporte->id_tipo = $objetoComponente->getIdTipo();
            $objTransporte->descricao = $objetoComponente->getDescricao();
            $objTransporte->endereco = $objetoComponente->getEndereco();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objTransporte)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterarCdConectividade(Conectividade $objConectividade)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = Conectividade::findFirst('id='.$objConectividade->getId());
            $objetoComponente->setTransaction($transaction);
            $objetoComponente->setIdTipo(($objConectividade->getIdTipo()) ? $objConectividade->getIdTipo() : null);
            $objetoComponente->setDescricao(mb_strtoupper($objConectividade->getDescricao(), $this->encode));
            $objetoComponente->setEndereco(mb_strtoupper($objConectividade->getEndereco(), $this->encode));
            $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoComponente->save() == false) {
                $messages = $objetoComponente->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Não foi possível salvar a Conectividade: " . $errors);
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoComponente)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function deletarCdConectividade(Conectividade $objConectividade)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = Conectividade::findFirst('id='.$objConectividade->getId());
            $id_cidade_digital = $objetoComponente->getIdCidadeDigital();
            $objetoComponente->setTransaction($transaction);
            if ($objetoComponente->delete() == false) {
                $transaction->rollback("Não foi possível deletar a Conectividade!");
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $id_cidade_digital)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarCdETelecons($id_cidade_digital)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetosComponente = EstacaoTelecon::find('id_cidade_digital = ' . $id_cidade_digital);
            $arrTransporte = [];
            foreach ($objetosComponente as $key => $objetoComponente){
                $objTransporte = new \stdClass();
                $objTransporte->id_etelecon = $objetoComponente->getId();
                $objTransporte->descricao = $objetoComponente->getDescricao();
                $objTransporte->ds_contrato = $objetoComponente->getContrato();
                $objTransporte->id_contrato = $objetoComponente->getIdContrato();
                $objTransporte->ds_terreno = $objetoComponente->getTerreno();
                $objTransporte->id_terreno = $objetoComponente->getIdTerreno();
                $objTransporte->ds_torre = $objetoComponente->getTorre();
                $objTransporte->id_torre = $objetoComponente->getIdTorre();
                $objTransporte->ds_set_equipamento = $objetoComponente->getSetEquipamento();
                $objTransporte->id_set_equipamento = $objetoComponente->getIdSetEquipamento();
                $objTransporte->ds_set_seguranca = $objetoComponente->getSetSeguranca();
                $objTransporte->id_set_seguranca = $objetoComponente->getIdSetSeguranca();
                $objTransporte->ds_unidade_consumidora = $objetoComponente->getUnidadeConsumidora();
                $objTransporte->id_unidade_consumidora = $objetoComponente->getIdUnidadeConsumidora();
                array_push($arrTransporte, $objTransporte);
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $arrTransporte)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarCdETelecon($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetoComponente = EstacaoTelecon::findFirst('id= ' . $id);
            $objTransporte = new \stdClass();
            $objTransporte->id_etelecon = $objetoComponente->getId();
            $objTransporte->descricao = $objetoComponente->getDescricao();
            $objTransporte->ds_contrato = $objetoComponente->getContrato();
            $objTransporte->id_contrato = $objetoComponente->getIdContrato();
            $objTransporte->ds_terreno = $objetoComponente->getTerreno();
            $objTransporte->id_terreno = $objetoComponente->getIdTerreno();
            $objTransporte->ds_torre = $objetoComponente->getTorre();
            $objTransporte->id_torre = $objetoComponente->getIdTorre();
            $objTransporte->ds_set_equipamento = $objetoComponente->getSetEquipamento();
            $objTransporte->id_set_equipamento = $objetoComponente->getIdSetEquipamento();
            $objTransporte->ds_set_seguranca = $objetoComponente->getSetSeguranca();
            $objTransporte->id_set_seguranca = $objetoComponente->getIdSetSeguranca();
            $objTransporte->ds_unidade_consumidora = $objetoComponente->getUnidadeConsumidora();
            $objTransporte->id_unidade_consumidora = $objetoComponente->getIdUnidadeConsumidora();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objTransporte)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterarCdETelecon(EstacaoTelecon $objETelecon)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = EstacaoTelecon::findFirst('id='.$objETelecon->getId());
            $objetoComponente->setTransaction($transaction);
            $objetoComponente->setDescricao(mb_strtoupper($objETelecon->getDescricao(), $this->encode));
            $objetoComponente->setIdContrato(($objETelecon->getIdContrato()) ? $objETelecon->getIdContrato() : null);
            $objetoComponente->setIdTerreno(($objETelecon->getIdTerreno()) ? $objETelecon->getIdTerreno() : null);
            $objetoComponente->setIdTorre(($objETelecon->getIdTorre()) ? $objETelecon->getIdTorre() : null);
            $objetoComponente->setIdSetEquipamento(($objETelecon->getIdSetEquipamento()) ? $objETelecon->getIdSetEquipamento() : null);
            $objetoComponente->setIdSetSeguranca(($objETelecon->getIdSetSeguranca()) ? $objETelecon->getIdSetSeguranca() : null);
            $objetoComponente->setIdUnidadeConsumidora(($objETelecon->getIdUnidadeConsumidora()) ? $objETelecon->getIdUnidadeConsumidora() : null);
            $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoComponente->save() == false) {
                $messages = $objetoComponente->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Não foi possível salvar a EstacaoTelecon: " . $errors);
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoComponente)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function deletarCdETelecon(EstacaoTelecon $objETelecon)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = EstacaoTelecon::findFirst('id='.$objETelecon->getId());
            $id_cidade_digital = $objetoComponente->getIdCidadeDigital();
            $objetoComponente->setTransaction($transaction);
            if ($objetoComponente->delete() == false) {
                $transaction->rollback("Não foi possível deletar a EstacaoTelecon!");
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $id_cidade_digital)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function getNomeCidadeCidadeDigital(CidadeDigital $objetoCidadeDigital)
    {
        $objeto = CidadeDigital::findFirst('id='.$objetoCidadeDigital->getId());
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True,"dados" => $objeto->getCidade())));
        return $response;
    }
}