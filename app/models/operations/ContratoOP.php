<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\Contrato;
use Circuitos\Models\ContratoAnexo;
use Circuitos\Models\ContratoArquivoFisico;
use Circuitos\Models\ContratoExercicio;
use Circuitos\Models\ContratoFinanceiro;
use Circuitos\Models\ContratoFinanceiroNota;
use Circuitos\Models\ContratoFiscal;
use Circuitos\Models\ContratoFiscalHasContrato;
use Circuitos\Models\ContratoGarantia;
use Circuitos\Models\ContratoOrcamento;
use Circuitos\Models\ContratoPenalidade;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Util\Infra;
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
            $objeto = Contrato::findFirst('id='.$id);
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
            $dados_penalidades = $this->visualizarContratoPenalidades($id);
            $dados_fiscais = $this->visualizarContratosFiscais($id);
            $response = new Response();
            $response->setContent(json_encode(array(
                'operacao' => True,
                'dados' => $objeto,
                'descricao' => $objDescricao,
                'fiscais' => $dados_fiscais['fiscais'],
                'descricoes_fiscais' => $dados_fiscais['descricoes'],
                'penalidades' => $dados_penalidades['penalidades'],
                'valor_penalidade_aberta' => $dados_penalidades['valor_penalidade_aberta'],
                'valor_penalidade_executada' => $dados_penalidades['valor_penalidade_executada'],
                'valor_penalidade_cancelada' => $dados_penalidades['valor_penalidade_cancelada'],
                'valor_penalidade_total' =>$dados_penalidades['valor_penalidade_total'],
                'anexos' => $this->visualizarContratoAnexos($id, true)
            )));
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

    public function visualizarContratoAnexos($id_contrato, $visualizar = false)
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
            return ($visualizar) ? $arrTransporte : $response;
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
                $objetoDescricao->data_inativacao_formatada = $objetoFiscal->getDataInativacaoFormatada();
                array_push($arrObjetos, $objetoFiscal);
                array_push($arrDescricoes, $objetoDescricao);
            }
            return [
                'fiscais' => $arrObjetos,
                'descricoes' => $arrDescricoes
            ];
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratosFinanceiros($id_contrato)
    {
        $logger = new FileAdapter($this->arqLog);
        $infra = new Infra();
        $caminho = $infra->getCaminhoAnexos();
        try {
            $arrDadosCompletos = [];
            $objExercicios = ContratoExercicio::find('ativo=1 AND excluido=0 AND id_contrato='.$id_contrato);
            if ($objExercicios) {
                foreach ($objExercicios as $key1 => $objExercicio)
                {
                    $arrExercicio = [
                        'exercicio' => $objExercicio->getExercicio(),
                        'valor_exercicio_formatado' => $objExercicio->getValorPrevistoFormatado()
                    ];
                    $arrDadosCompletos[$key1]= $arrExercicio;
                    $objFinanceiros = ContratoFinanceiro::find('ativo=1 AND excluido=0 AND id_exercicio='.$objExercicio->getId());
                    foreach($objFinanceiros as $key2 => $objFinanceiro)
                    {
                        $arrFinanceiro = [
                            'competencia' => $objFinanceiro->getMesCompetencia(),
                            'status_descricao' => $objFinanceiro->getStatusDescricao(),
                            'valor_pagamento_formatado' => $objFinanceiro->getValorPagamentoFormatado()
                        ];
                        $arrDadosCompletos[$key1][$key2]= $arrFinanceiro;
                        $objPagamentos = ContratoFinanceiroNota::find('ativo=1 AND excluido=0 AND id_contrato_financeiro='.$objFinanceiro->getId());
                        foreach($objPagamentos as $key3 => $objPagamento)
                        {
                            $arrPagamento = [
                                'numero_nota_fiscal' => $objPagamento->getNumeroNotaFiscal(),
                                'data_pagamento_formatada' => $objPagamento->getDataPagamentoFormatada(),
                                'valor_nota_formatado' => $objPagamento->getValorNotaFormatado(),
                                'observacao' => $objPagamento->getObservacao(),
                                'id_anexo' => $objPagamento->getIdAnexo(),
                                'url' => $objPagamento->getUrlFormatadaAnexo()
                            ];
                            $arrDadosCompletos[$key1][$key2][$key3]= $arrPagamento;
                        }
                    }
                }
            }
            $response = new Response();
            $response->setContent(json_encode(array('operacao' => True, 'dados' => $arrDadosCompletos, 'caminho_anexo' => $caminho)));
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
            $objetosFilhos = Contrato::find('ativo=1 AND excluido=0 AND id_contrato_principal = ' . $id_contrato);
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

    public function visualizarContratoPenalidades($id_contrato)
    {
        $util = new Util();
        $objPenalidades = ContratoPenalidade::find('id_contrato='.$id_contrato);
        $arrPenalidades = [];
        $valor_penalidade_aberta = 0;
        $valor_penalidade_executada = 0;
        $valor_penalidade_cancelada = 0;
        foreach ($objPenalidades as $objPenalidade)
        {
            $objetoPenalidade = new \stdClass();
            $objetoPenalidade->data_penalidade = $objPenalidade->getDataCriacaoFormatada();
            $objetoPenalidade->servico_penalidade = $objPenalidade->getServicoDescricao();
            $objetoPenalidade->status_penalidade = $objPenalidade->getStatusDescricao();
            $objetoPenalidade->nro_processo_penalidade = $objPenalidade->getNumeroProcesso();
            $objetoPenalidade->nro_notificacao_penalidade = $objPenalidade->getNumeroNotificacao();
            $objetoPenalidade->nro_rt_penalidade = $objPenalidade->getNumeroRt();
            $objetoPenalidade->data_recebimento_notificacao_penalidade = $objPenalidade->getDataRecebimentoOficioNotificacaoFormatada();
            $objetoPenalidade->data_prazo_defesa_penalidade = $objPenalidade->getDataPrazoRespostaFormatada();
            $objetoPenalidade->data_apresentacao_defesa_penalidade = $objPenalidade->getDataApresentacaoDefesaFormatada();
            $objetoPenalidade->valor_multa_penalidade = $objPenalidade->getValorMultaFormatado();
            array_push($arrPenalidades, $objetoPenalidade);
            switch ($objPenalidade->getStatus())
            {
                case 0://Aberta
                    $valor_penalidade_aberta += $objPenalidade->getValorMulta();
                    break;
                case 1://Executada
                    $valor_penalidade_executada += $objPenalidade->getValorMulta();
                    break;
                case 2://Cancelada
                    $valor_penalidade_cancelada += $objPenalidade->getValorMulta();
                    break;
            }
        }
        $valor_penalidade_total = $valor_penalidade_aberta + $valor_penalidade_executada + $valor_penalidade_cancelada;
        return [
            'penalidades' => $arrPenalidades,
            'valor_penalidade_aberta' => $util->formataMoedaReal($valor_penalidade_aberta),
            'valor_penalidade_executada' => $util->formataMoedaReal($valor_penalidade_executada),
            'valor_penalidade_cancelada' => $util->formataMoedaReal($valor_penalidade_cancelada),
            'valor_penalidade_total' => $util->formataMoedaReal($valor_penalidade_total)
        ];
    }
}