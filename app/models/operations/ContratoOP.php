<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\Contrato;
use Circuitos\Models\ContratoExercicio;
use Circuitos\Models\ContratoGarantia;
use Circuitos\Models\ContratoOrcamento;
use Phalcon\Http\Response as Response;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Util\Util;

class ContratoOP extends Contrato
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return Contrato::pesquisarContrato($dados);
    }

    public function cadastrar(Contrato $objArray, $arrObjContratoOrcamento, $arrObjContratoExercicio, $arrObjContratoGarantia)
    {
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            //Contrato
            $objeto = new Contrato();
            $objeto->setTransaction($transaction);
            $objeto->setIdContratoPrincipal(($objArray->getIdContratoPrincipal()) ? $objArray->getIdContratoPrincipal() : null);
            $objeto->setOrdem($this->getOrdemContrato($objArray->getIdContratoPrincipal()));
            $objeto->setIdTipoContrato($objArray->getIdTipoContrato());
            $objeto->setIdTipoProcesso($objArray->getIdTipoProcesso());
            $objeto->setNumeroProcesso($objArray->getNumeroProcesso());
            $objeto->setIdPropostaComercial(($objArray->getIdPropostaComercial()) ? $objArray->getIdPropostaComercial() : null);
            $objeto->setIdCliente($objArray->getIdCliente());
            $objeto->setIdStatus($objArray->getIdStatus());
            $objeto->setDataCriacao(date('Y-m-d H:i:s'));
            $objeto->setDataAssinatura($util->converterDataUSA($objArray->getDataAssinatura()));
            $objeto->setDataPublicacao($util->converterDataUSA($objArray->getDataPublicacao()));
            $objeto->setNumDiarioOficial(mb_strtoupper($objArray->getNumDiarioOficial(), $this->encode));
            $objeto->setDataEncerramento($util->converterDataUSA($objArray->getDataEncerramento()));
            $objeto->setVigenciaTipo($objArray->getVigenciaTipo());
            $objeto->setVigenciaPrazo($objArray->getVigenciaPrazo());
            $objeto->setNumero(mb_strtoupper($objArray->getNumero(), $this->encode));
            $objeto->setAno($objArray->getAno());
            $objeto->setValorGlobal($util->formataNumeroMoeda($objArray->getValorGlobal()));
            $objeto->setValorMensal($util->formataNumeroMoeda($objArray->getValorMensal()));
            $objeto->setObjeto(mb_strtoupper($objArray->getObjeto()));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar o contrato: " . $errors);
            }
            //Contrato Orçamento
            if (count($arrObjContratoOrcamento) > 0){
                foreach($arrObjContratoOrcamento as $objOrcamento){
                    $objContratoOrcamento = new ContratoOrcamento();
                    $objContratoOrcamento->setTransaction($transaction);
                    $objContratoOrcamento->setIdContrato($objeto->getId());
                    $objContratoOrcamento->setUnidadeOrcamentaria($objOrcamento->getUnidadeOrcamentaria());
                    $objContratoOrcamento->setFonteOrcamentaria($objOrcamento->getFonteOrcamentaria());
                    $objContratoOrcamento->setProgramaTrabalho($objOrcamento->getProgramaTrabalho());
                    $objContratoOrcamento->setElementoDespesa($objOrcamento->getElementoDespesa());
                    $objContratoOrcamento->setPi($objOrcamento->getPi());
                    $objContratoOrcamento->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objContratoOrcamento->save() == false) {
                        $messages = $objContratoOrcamento->getMessages();
                        $errors = "";
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= "[".$messages[$i]."] ";
                        }
                        $transaction->rollback("Erro ao criar o contrato: " . $errors);
                    }
                }
            }
            //Contrato Exercício
            if (count($arrObjContratoExercicio) > 0){
                foreach($arrObjContratoExercicio as $objExercicio){
                    $objContratoExercicio = new ContratoExercicio();
                    $objContratoExercicio->setTransaction($transaction);
                    $objContratoExercicio->setIdContrato($objeto->getId());
                    $objContratoExercicio->setExercicio($objExercicio->getExercicio());
                    $objContratoExercicio->setCompetenciaInicial($objExercicio->getCompetenciaInicial());
                    $objContratoExercicio->setCompetenciaFinal($objExercicio->getCompetenciaFinal());
                    $objContratoExercicio->setValorPrevisto($util->formataNumeroMoeda($objExercicio->getValorPrevisto()));
                    $objContratoExercicio->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objContratoExercicio->save() == false) {
                        $messages = $objContratoExercicio->getMessages();
                        $errors = "";
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= "[".$messages[$i]."] ";
                        }
                        $transaction->rollback("Erro ao criar o contrato: " . $errors);
                    }
                }
            }
            //Contrato Garantia
            if (count($arrObjContratoGarantia) > 0){
                foreach($arrObjContratoGarantia as $objGarantia){
                    $objContratoGarantia = new ContratoGarantia();
                    $objContratoGarantia->setTransaction($transaction);
                    $objContratoGarantia->setIdContrato($objeto->getId());
                    $objContratoGarantia->setIdModalidade($objGarantia->getIdModalidade());
                    $objContratoGarantia->setGarantiaConcretizada($objGarantia->getGarantiaConcretizada());
                    $objContratoGarantia->setPercentual($util->formataNumeroMoeda($objGarantia->getPercentual()));
                    $objContratoGarantia->setValor($util->formataNumeroMoeda($objGarantia->getValor()));
                    $objContratoGarantia->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objContratoGarantia->save() == false) {
                        $messages = $objContratoGarantia->getMessages();
                        $errors = "";
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= "[".$messages[$i]."] ";
                        }
                        $transaction->rollback("Erro ao criar o contrato: " . $errors);
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

    public function alterar(Contrato $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Contrato::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdCidadeDigital($objArray->getIdCidadeDigital());
            $objeto->setIdTipo($objArray->getIdTipo());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setEndereco(mb_strtoupper($objArray->getEndereco(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o contrato!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function ativar(Contrato $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Contrato::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o contrato!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function inativar(Contrato $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Contrato::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o contrato!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function excluir(Contrato $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Contrato::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir o contrato!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarContrato($id)
    {
        try {
            $objeto = Contrato::findFirst("id={$id}");
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

    private function getOrdemContrato($id_contrato_principal = null)
    {
        $num_ordem = 1;
//        if ($id_contrato_principal){
//            $total = Contrato::totalContratoAgrupados($id_contrato_principal);
//            if ($total === 0){
//                $num_ordem = $total + 2;
//            } else {
//                $num_ordem = $total + 1;
//            }
//        } else {
//            $num_ordem = 1;
//        }
        return $num_ordem;
    }
}