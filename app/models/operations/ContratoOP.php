<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\Contrato;
use Circuitos\Models\ContratoAnexo;
use Circuitos\Models\ContratoArquivoFisico;
use Circuitos\Models\ContratoExercicio;
use Circuitos\Models\ContratoFiscal;
use Circuitos\Models\ContratoFiscalHasContrato;
use Circuitos\Models\ContratoGarantia;
use Circuitos\Models\ContratoOrcamento;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Util\Util;

class ContratoOP extends Contrato
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return Contrato::pesquisarContrato($dados);
    }

    public function cadastrar(Contrato $objArray, ContratoArquivoFisico $objArquivo, $arrObjContratoOrcamento, $arrObjContratoExercicio, $arrObjContratoGarantia)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $objeto->setValorGlobal($util->formataNumero($objArray->getValorGlobal()));
            $objeto->setValorMensal($util->formataNumero($objArray->getValorMensal()));
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
            $objetoArquivo = new ContratoArquivoFisico();
            $objetoArquivo->setTransaction($transaction);
            $objetoArquivo->setId($objeto->getId());
            $objetoArquivo->setCorredor(mb_strtoupper($objArquivo->getCorredor(), $this->encode));
            $objetoArquivo->setArmario(mb_strtoupper($objArquivo->getArmario(), $this->encode));
            $objetoArquivo->setPrateleira(mb_strtoupper($objArquivo->getPrateleira(), $this->encode));
            $objetoArquivo->setCodigo(mb_strtoupper($objArquivo->getCodigo(), $this->encode));
            if ($objetoArquivo->save() == false) {
                $messages = $objetoArquivo->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar o contrato arquivo: " . $errors);
            }
            //Contrato Orçamento
            if (count($arrObjContratoOrcamento) > 0){
                foreach($arrObjContratoOrcamento as $objOrcamento){
                    $objContratoOrcamento = new ContratoOrcamento();
                    $objContratoOrcamento->setTransaction($transaction);
                    $objContratoOrcamento->setIdContrato($objeto->getId());
                    $objContratoOrcamento->setFuncionalProgramatica($objOrcamento->getFuncionalProgramatica());
                    $objContratoOrcamento->setFonteOrcamentaria($objOrcamento->getFonteOrcamentaria());
                    $objContratoOrcamento->setProgramaTrabalho($objOrcamento->getProgramaTrabalho());
                    $objContratoOrcamento->setElementoDespesa($objOrcamento->getElementoDespesa());
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
                    $objContratoExercicio->setValorPrevisto($util->formataNumero($objExercicio->getValorPrevisto()));
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
                    $objContratoGarantia->setPercentual($util->formataNumero($objGarantia->getPercentual()));
                    $objContratoGarantia->setValor($util->formataNumero($objGarantia->getValor()));
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(Contrato $objArray, ContratoArquivoFisico $objArquivo, $arrObjContratoOrcamento, $arrObjContratoExercicio, $arrObjContratoGarantia)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Contrato::findFirst('id='.$objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdContratoPrincipal(($objArray->getIdContratoPrincipal()) ? $objArray->getIdContratoPrincipal() : null);
            $objeto->setOrdem($this->getOrdemContrato($objArray->getIdContratoPrincipal()));
            $objeto->setIdTipoContrato($objArray->getIdTipoContrato());
            $objeto->setIdTipoProcesso($objArray->getIdTipoProcesso());
            $objeto->setNumeroProcesso($objArray->getNumeroProcesso());
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
            $objeto->setValorGlobal($util->formataNumero($objArray->getValorGlobal()));
            $objeto->setValorMensal($util->formataNumero($objArray->getValorMensal()));
            $objeto->setObjeto(mb_strtoupper($objArray->getObjeto()));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao alterar o contrato: " . $errors);
            }
            $objetoArquivo = ContratoArquivoFisico::findFirst('id='.$objArray->getId());
            if (!$objetoArquivo){
                $objetoArquivo = new ContratoArquivoFisico();
            }
            $objetoArquivo->setTransaction($transaction);
            $objetoArquivo->setId($objeto->getId());
            $objetoArquivo->setCorredor(mb_strtoupper($objArquivo->getCorredor(), $this->encode));
            $objetoArquivo->setArmario(mb_strtoupper($objArquivo->getArmario(), $this->encode));
            $objetoArquivo->setPrateleira(mb_strtoupper($objArquivo->getPrateleira(), $this->encode));
            $objetoArquivo->setCodigo(mb_strtoupper($objArquivo->getCodigo(), $this->encode));
            if ($objetoArquivo->save() == false) {
                $messages = $objetoArquivo->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar o contrato arquivo: " . $errors);
            }
            //Contrato Orçamento
            if (count($arrObjContratoOrcamento) > 0){
                foreach($arrObjContratoOrcamento as $objOrcamento){
                    $objContratoOrcamento = new ContratoOrcamento();
                    $objContratoOrcamento->setTransaction($transaction);
                    $objContratoOrcamento->setIdContrato($objeto->getId());
                    $objContratoOrcamento->setFuncionalProgramatica($objOrcamento->getFuncionalProgramatica());
                    $objContratoOrcamento->setFonteOrcamentaria($objOrcamento->getFonteOrcamentaria());
                    $objContratoOrcamento->setProgramaTrabalho($objOrcamento->getProgramaTrabalho());
                    $objContratoOrcamento->setElementoDespesa($objOrcamento->getElementoDespesa());
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
                    $objContratoExercicio->setValorPrevisto($util->formataNumero($objExercicio->getValorPrevisto()));
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
                    $objContratoGarantia->setPercentual($util->formataNumero($objGarantia->getPercentual()));
                    $objContratoGarantia->setValor($util->formataNumero($objGarantia->getValor()));
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(Contrato $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(Contrato $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(Contrato $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
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
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContrato($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = Contrato::findFirst("id={$id}");
            $objDescricao = new \stdClass();
            $objDescricao->ds_cliente = $objeto->getCliente();
            $objDescricao->ds_contrato_principal = $objeto->getContratoPrincipal();
            $objDescricao->ds_contrato = $objeto->getContrato();
            $objDescricao->ds_status = $objeto->getStatus();
            $objDescricao->ds_tipo_contrato = $objeto->getTipoContrato();
            $objDescricao->ds_tipo_processo = $objeto->getTipoProcesso();
            $objDescricao->corredor = $objeto->getCorredor();
            $objDescricao->armario = $objeto->getArmario();
            $objDescricao->prateleira = $objeto->getPrateleira();
            $objDescricao->codigo = $objeto->getCodigo();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objeto,"descricao" => $objDescricao)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoNumero($id)
    {
        $objeto = Contrato::findFirst("id={$id}");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True,"dados" => $objeto->getNumero().'/'.$objeto->getAno())));
        return $response;
    }

    public function visualizarContratoOrcamentos($id_contrato)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetosComponentes = ContratoOrcamento::find('id_contrato = ' . $id_contrato);
            $arrTransporte = [];
            foreach ($objetosComponentes as $objetoComponente){
                $objTransporte = new \stdClass();
                $objTransporte->id_contrato_orcamento = $objetoComponente->getId();
                $objTransporte->funcional_programatica = $objetoComponente->getFuncionalProgramatica();
                $objTransporte->fonte_orcamentaria = $objetoComponente->getFonteOrcamentaria();
                $objTransporte->programa_trabalho = $objetoComponente->getProgramaTrabalho();
                $objTransporte->elemento_despesa = $objetoComponente->getElementoDespesa();
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

    public function visualizarContratoGarantias($id_contrato)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetosComponentes = ContratoGarantia::find('id_contrato = ' . $id_contrato);
            $arrTransporte = [];
            foreach ($objetosComponentes as $objetoComponente){
                $objTransporte = new \stdClass();
                $objTransporte->id_contrato_garantia = $objetoComponente->getId();
                $objTransporte->garantia_concretizada = $objetoComponente->getGarantiaConcretizada();
                $objTransporte->id_modalidade = $objetoComponente->getIdModalidade();
                $objTransporte->ds_modalidade = $objetoComponente->getModalidade();
                $objTransporte->percentual = $objetoComponente->getPercentual();
                $objTransporte->valor = $objetoComponente->getValor();
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

    public function visualizarContratoExercicios($id_contrato)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetosComponentes = ContratoExercicio::find('id_contrato = ' . $id_contrato);
            $arrTransporte = [];
            foreach ($objetosComponentes as $objetoComponente){
                $objTransporte = new \stdClass();
                $objTransporte->id_contrato_exercicio = $objetoComponente->getId();
                $objTransporte->exercicio = $objetoComponente->getExercicio();
                $objTransporte->competencia_inicial = $objetoComponente->getCompetenciaInicial();
                $objTransporte->competencia_final = $objetoComponente->getCompetenciaFinal();
                $objTransporte->valor_previsto = $objetoComponente->getValorPrevisto();
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

    public function visualizarContratoOrcamento($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetoComponente = ContratoOrcamento::findFirst('id=' . $id);
            $objTransporte = new \stdClass();
            $objTransporte->id_contrato_orcamento = $objetoComponente->getId();
            $objTransporte->funcional_programatica = $objetoComponente->getFuncionalProgramatica();
            $objTransporte->fonte_orcamentaria = $objetoComponente->getFonteOrcamentaria();
            $objTransporte->programa_trabalho = $objetoComponente->getProgramaTrabalho();
            $objTransporte->elemento_despesa = $objetoComponente->getElementoDespesa();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objTransporte)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoGarantia($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetoComponente = ContratoGarantia::findFirst('id=' . $id);
            $objTransporte = new \stdClass();
            $objTransporte->id_contrato_garantia = $objetoComponente->getId();
            $objTransporte->garantia_concretizada = $objetoComponente->getGarantiaConcretizada();
            $objTransporte->id_modalidade = $objetoComponente->getIdModalidade();
            $objTransporte->ds_modalidade = $objetoComponente->getModalidade();
            $objTransporte->percentual = $objetoComponente->getPercentual();
            $objTransporte->valor = $objetoComponente->getValor();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objTransporte)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoExercicio($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetoComponente = ContratoExercicio::findFirst('id=' . $id);
            $objTransporte = new \stdClass();
            $objTransporte->id_contrato_exercicio = $objetoComponente->getId();
            $objTransporte->exercicio = $objetoComponente->getExercicio();
            $objTransporte->competencia_inicial = $objetoComponente->getCompetenciaInicial();
            $objTransporte->competencia_final = $objetoComponente->getCompetenciaFinal();
            $objTransporte->valor_previsto = $objetoComponente->getValorPrevisto();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objTransporte)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterarContratoOrcamento(ContratoOrcamento $objCom)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = ContratoOrcamento::findFirst('id='.$objCom->getId());
            $objetoComponente->setTransaction($transaction);
            $objetoComponente->setFuncionalProgramatica($objCom->getFuncionalProgramatica());
            $objetoComponente->setFonteOrcamentaria($objCom->getFonteOrcamentaria());
            $objetoComponente->setProgramaTrabalho($objCom->getProgramaTrabalho());
            $objetoComponente->setElementoDespesa($objCom->getElementoDespesa());
            $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoComponente->save() == false) {
                $messages = $objetoComponente->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Não foi possível salvar o orçamento: " . $errors);
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

    public function deletarContratoOrcamento(ContratoOrcamento $objCom)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = ContratoOrcamento::findFirst('id='.$objCom->getId());
            $id_contrato = $objetoComponente->getIdContrato();
            $objetoComponente->setTransaction($transaction);
            if ($objetoComponente->delete() == false) {
                $transaction->rollback("Não foi possível deletar o orçamento!");
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $id_contrato)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterarContratoExercicio(ContratoExercicio $objCom)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = ContratoExercicio::findFirst('id='.$objCom->getId());
            $objetoComponente->setTransaction($transaction);
            $objetoComponente->setExercicio($objCom->getExercicio());
            $objetoComponente->setCompetenciaFinal($objCom->getCompetenciaFinal());
            $objetoComponente->getCompetenciaInicial($objCom->getCompetenciaInicial());
            $objetoComponente->setValorPrevisto($util->formataNumero($objCom->getValorPrevisto()));
            $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoComponente->save() == false) {
                $messages = $objetoComponente->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Não foi possível salvar o exercício: " . $errors);
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

    public function deletarContratoExercicio(ContratoExercicio $objCom)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = ContratoExercicio::findFirst('id='.$objCom->getId());
            $id_contrato = $objetoComponente->getIdContrato();
            $objetoComponente->setTransaction($transaction);
            if ($objetoComponente->delete() == false) {
                $transaction->rollback("Não foi possível deletar o exercício!");
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $id_contrato)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterarContratoGarantia(ContratoGarantia $objCom)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = ContratoGarantia::findFirst('id='.$objCom->getId());
            $objetoComponente->setTransaction($transaction);
            $objetoComponente->setIdModalidade($objCom->getIdModalidade());
            $objetoComponente->setGarantiaConcretizada($objCom->getGarantiaConcretizada());
            $objetoComponente->setPercentual($util->formataNumero($objCom->getPercentual()));
            $objetoComponente->setValor($util->formataNumero($objCom->getValor()));
            $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoComponente->save() == false) {
                $messages = $objetoComponente->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Não foi possível salvar a garantia: " . $errors);
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

    public function deletarContratoGarantia(ContratoGarantia $objCom)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = ContratoGarantia::findFirst('id='.$objCom->getId());
            $id_contrato = $objetoComponente->getIdContrato();
            $objetoComponente->setTransaction($transaction);
            if ($objetoComponente->delete() == false) {
                $transaction->rollback("Não foi possível deletar a garantia!");
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $id_contrato)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
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

    public function visualizarContratoAnexos($id_contrato)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $objetosComponentes = ContratoAnexo::find('id_contrato = ' . $id_contrato);
            $arrTransporte = [];
            foreach ($objetosComponentes as $objetoComponente){
                chmod($objetoComponente->getUrlAnexo(), 0777);
                $url_base = explode("/", $objetoComponente->getUrlAnexo());
                $url = $url_base[count($url_base)-5].'/'.$url_base[count($url_base)-4].'/'.$url_base[count($url_base)-3].'/'.$url_base[count($url_base)-2].'/'.$url_base[count($url_base)-1];
                $objTransporte = new \stdClass();
                $objTransporte->id_contrato_anexo = $objetoComponente->getId();
                $objTransporte->id_contrato = $objetoComponente->getIdContrato();
                $objTransporte->id_anexo = $objetoComponente->getIdAnexo();
                $objTransporte->id_tipo_anexo = $objetoComponente->getIdTipoAnexo();
                $objTransporte->ds_tipo_anexo = $objetoComponente->getDescricaoTipoAnexo();
                $objTransporte->descricao = $objetoComponente->getDescricaoAnexo();
                $objTransporte->url = $url;
                $objTransporte->data_criacao = $util->converterDataHoraParaBr($objetoComponente->getDataCriacaoAnexo());
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

    public function visualizarContratosFiscais($id_contrato)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetos = ContratoFiscalHasContrato::find('id_contrato='.$id_contrato);
            $arrObjetos = [];
            $arrDescricoes = [];
            foreach ($objetos as $objeto)
            {
                $objetoFiscal = ContratoFiscal::findFirst('id='.$objeto->getIdContratoFiscal());
                $objetoDescricao = new \stdClass();
                $objetoDescricao->tipo_fiscal = $objetoFiscal->getTipoFiscalDescricao();
                $objetoDescricao->nome_fiscal = $objetoFiscal->getNomeFiscal();
                $objetoDescricao->data_nomeacao_formatada = $objetoFiscal->getDataNomeacaoFormatada();
                array_push($arrObjetos, $objetoFiscal);
                array_push($arrDescricoes, $objetoDescricao);
            }
            $response = new Response();
            $response->setContent(json_encode(array('operacao' => True, 'dados_objeto' => $arrObjetos, 'dados_descricao' => $arrDescricoes)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratosFinanceiros($id_contrato)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $objFilho = Contrato::findFirst('id='.$id_contrato);
            $objetoPai = new \stdClass();
            if (!empty($objFilho->getIdContratoPrincipal())){
                $objPai = Contrato::findFirst('id='.$objFilho->getIdContratoPrincipal());
                $objetoPai->tipo_vinculo = 'Pai';
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True, "dados_pai" => $objetoPai)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratosVinculados($id_contrato)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            //Objetos Filhos
            $objetosFilhos = Contrato::find('id_contrato_principal = ' . $id_contrato);
            $arrTransporteFilhos = [];
            foreach ($objetosFilhos as $objetoFilho){
                $objTransporte = new \stdClass();
                $objTransporte->tipo_vinculo = 'Filho';
                $objTransporte->tipo_documento = $objetoFilho->getTipoContrato();
                $objTransporte->numero_ano = $objetoFilho->getNumero() . '/' .$objetoFilho->getAno();
                $objTransporte->data_assinatura = $util->converterDataParaBr($objetoFilho->getDataAssinatura());
                $objTransporte->data_encerramento = $util->converterDataParaBr($objetoFilho->getDataEncerramento());
                $objTransporte->data_publicacao = $util->converterDataParaBr($objetoFilho->getDataPublicacao());
                $objTransporte->numero_diario = $objetoFilho->getNumDiarioOficial();
                array_push($arrTransporteFilhos, $objTransporte);
            }
            $objFilho = Contrato::findFirst('id='.$id_contrato);
            $objetoPai = new \stdClass();
            if (!empty($objFilho->getIdContratoPrincipal())){
                $objPai = Contrato::findFirst('id='.$objFilho->getIdContratoPrincipal());
                $objetoPai->tipo_vinculo = 'Pai';
                $objetoPai->tipo_documento = $objPai->getTipoContrato();
                $objetoPai->numero_ano = $objPai->getNumero() . '/' .$objPai->getAno();
                $objetoPai->data_assinatura = $util->converterDataParaBr($objPai->getDataAssinatura());
                $objetoPai->data_encerramento = $util->converterDataParaBr($objPai->getDataEncerramento());
                $objetoPai->data_publicacao = $util->converterDataParaBr($objPai->getDataPublicacao());
                $objetoPai->numero_diario = $objPai->getNumDiarioOficial();
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados_filhos" => $arrTransporteFilhos, "dados_pai" => $objetoPai)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }
}