<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\PropostaComercial;
use Circuitos\Models\PropostaComercialItem;
use Circuitos\Models\PropostaComercialValorMensal;
use Phalcon\Http\Response as Response;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Util\Util;

class PropostaComercialOP extends PropostaComercial
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return PropostaComercial::pesquisarPropostaComercial($dados);
    }

    public function cadastrar(PropostaComercial $objArray, PropostaComercialValorMensal $objArrayValorMensal, $arrObjItensProposta)
    {
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new PropostaComercial();
            $objeto->setTransaction($transaction);
            $objeto->setIdCliente($objArray->getIdCliente());
            $objeto->setIdTipoProposta($objArray->getIdTipoProposta());
            $objeto->setIdLocalizacao($objArray->getIdLocalizacao());
            $objeto->setIdStatus($objArray->getIdStatus());
            $objeto->setDataProposta($util->converterDataUSA($objArray->getDataProposta()));
            $objeto->setNumero(mb_strtoupper($objArray->getNumero(), $this->encode));
            $objeto->setVencimento($util->converterDataUSA($objArray->getVencimento()));
            $objeto->setReajuste((!empty($objArray->getReajuste())) ? $util->formataNumero($objArray->getReajuste()) : 0);
            $objeto->setImposto((!empty($objArray->getImposto())) ? $util->formataNumero($objArray->getImposto()) : 0);
            $objeto->setDesconto((!empty($objArray->getDesconto())) ? $util->formataNumero($objArray->getDesconto()) : 0);
            $objeto->setEncargos((!empty($objArray->getEncargos())) ? $util->formataNumero($objArray->getEncargos()) : 0);
            $objeto->setValorGlobal($util->formataNumero($objArray->getValorGlobal()));
            $objeto->setObjetivo(mb_strtoupper($objArray->getObjetivo(), $this->encode));
            $objeto->setObjetivoEspecifico(mb_strtoupper($objArray->getObjetivoEspecifico(), $this->encode));
            $objeto->setDescritivo(mb_strtoupper($objArray->getDescritivo(), $this->encode));
            $objeto->setResponsabilidade(mb_strtoupper($objArray->getResponsabilidade(), $this->encode));
            $objeto->setCondicoesPgto(mb_strtoupper($objArray->getCondicoesPgto(), $this->encode));
            $objeto->setPrazoExecucao(mb_strtoupper($objArray->getPrazoExecucao(), $this->encode));
            $objeto->setConsideracoes(mb_strtoupper($objArray->getConsideracoes(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a proposta: " . $errors);
            }
            $objetoValorMensal = new PropostaComercialValorMensal();
            $objetoValorMensal->setTransaction($transaction);
            $objetoValorMensal->setIdPropostaComercial($objeto->getId());
            $objetoValorMensal->setJan((!empty($objArrayValorMensal->getJan())) ? $util->formataNumero($objArrayValorMensal->getJan()) : 0);
            $objetoValorMensal->setFev((!empty($objArrayValorMensal->getFev())) ? $util->formataNumero($objArrayValorMensal->getFev()) : 0);
            $objetoValorMensal->setMar((!empty($objArrayValorMensal->getMar())) ? $util->formataNumero($objArrayValorMensal->getMar()) : 0);
            $objetoValorMensal->setAbr((!empty($objArrayValorMensal->getAbr())) ? $util->formataNumero($objArrayValorMensal->getAbr()) : 0);
            $objetoValorMensal->setMai((!empty($objArrayValorMensal->getMai())) ? $util->formataNumero($objArrayValorMensal->getMai()) : 0);
            $objetoValorMensal->setJun((!empty($objArrayValorMensal->getJun())) ? $util->formataNumero($objArrayValorMensal->getJun()) : 0);
            $objetoValorMensal->setJul((!empty($objArrayValorMensal->getJul())) ? $util->formataNumero($objArrayValorMensal->getJul()) : 0);
            $objetoValorMensal->setAgo((!empty($objArrayValorMensal->getAgo())) ? $util->formataNumero($objArrayValorMensal->getAgo()) : 0);
            $objetoValorMensal->setSet((!empty($objArrayValorMensal->getSet())) ? $util->formataNumero($objArrayValorMensal->getSet()) : 0);
            $objetoValorMensal->setOut((!empty($objArrayValorMensal->getOut())) ? $util->formataNumero($objArrayValorMensal->getOut()) : 0);
            $objetoValorMensal->setNov((!empty($objArrayValorMensal->getNov())) ? $util->formataNumero($objArrayValorMensal->getNov()) : 0);
            $objetoValorMensal->setDez((!empty($objArrayValorMensal->getDez())) ? $util->formataNumero($objArrayValorMensal->getDez()) : 0);
            $objetoValorMensal->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoValorMensal->save() == false) {
                $messages = $objetoValorMensal->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar os valores mensais: " . $errors);
            }
            if (count($arrObjItensProposta) > 0){
                foreach($arrObjItensProposta as $objItensProposta){
                    $objetoItemProposta = new PropostaComercialItem();
                    $objetoItemProposta->setTransaction($transaction);
                    $objetoItemProposta->setIdPropostaComercial($objeto->getId());
                    $objetoItemProposta->setIdPropostaComercialServicos($objItensProposta->getIdPropostaComercialServicos());
                    $objetoItemProposta->setImposto($objItensProposta->getImposto());
                    $objetoItemProposta->setReajuste($objItensProposta->getReajuste());
                    $objetoItemProposta->setQuantidade($objItensProposta->getQuantidade());
                    $objetoItemProposta->setMesInicial($objItensProposta->getMesInicial());
                    $objetoItemProposta->setVigencia($objItensProposta->getVigencia());
                    $objetoItemProposta->setValorUnitario((!empty($objItensProposta->getValorUnitario())) ? $util->formataNumero($objItensProposta->getValorUnitario()) : 0);
                    $objetoItemProposta->setValorTotal((!empty($objItensProposta->getValorTotal())) ? $util->formataNumero($objItensProposta->getValorTotal()) : 0);
                    $objetoItemProposta->setValorTotalReajuste((!empty($objItensProposta->getValorTotalReajuste())) ? $util->formataNumero($objItensProposta->getValorTotalReajuste()) : 0);
                    $objetoItemProposta->setValorImpostos((!empty($objItensProposta->getValorImpostos())) ? $util->formataNumero($objItensProposta->getValorImpostos()) : 0);
                    $objetoItemProposta->setValorTotalImpostos((!empty($objItensProposta->getValorTotalImpostos())) ? $util->formataNumero($objItensProposta->getValorTotalImpostos()) : 0);
                    $objetoItemProposta->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoItemProposta->save() == false) {
                        $messages = $objetoItemProposta->getMessages();
                        $errors = "";
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= "[".$messages[$i]."] ";
                        }
                        $transaction->rollback("Erro ao criar o item da proposta: " . $errors);
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

    public function alterar(PropostaComercial $objArray, PropostaComercialValorMensal $objArrayValorMensal, $arrObjItensProposta)
    {
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercial::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdCliente($objArray->getIdCliente());
            $objeto->setIdTipoProposta($objArray->getIdTipoProposta());
            $objeto->setIdLocalizacao($objArray->getIdLocalizacao());
            $objeto->setIdStatus($objArray->getIdStatus());
            $objeto->setDataProposta($util->converterDataUSA($objArray->getDataProposta()));
            $objeto->setNumero(mb_strtoupper($objArray->getNumero(), $this->encode));
            $objeto->setVencimento($util->converterDataUSA($objArray->getVencimento()));
            $objeto->setReajuste((!empty($objArray->getReajuste())) ? $util->formataNumero($objArray->getReajuste()) : 0);
            $objeto->setImposto((!empty($objArray->getImposto())) ? $util->formataNumero($objArray->getImposto()) : 0);
            $objeto->setDesconto((!empty($objArray->getDesconto())) ? $util->formataNumero($objArray->getDesconto()) : 0);
            $objeto->setEncargos((!empty($objArray->getEncargos())) ? $util->formataNumero($objArray->getEncargos()) : 0);
            $objeto->setValorGlobal($util->formataNumero($objArray->getValorGlobal()));
            $objeto->setObjetivo(mb_strtoupper($objArray->getObjetivo(), $this->encode));
            $objeto->setObjetivoEspecifico(mb_strtoupper($objArray->getObjetivoEspecifico(), $this->encode));
            $objeto->setDescritivo(mb_strtoupper($objArray->getDescritivo(), $this->encode));
            $objeto->setResponsabilidade(mb_strtoupper($objArray->getResponsabilidade(), $this->encode));
            $objeto->setCondicoesPgto(mb_strtoupper($objArray->getCondicoesPgto(), $this->encode));
            $objeto->setPrazoExecucao(mb_strtoupper($objArray->getPrazoExecucao(), $this->encode));
            $objeto->setConsideracoes(mb_strtoupper($objArray->getConsideracoes(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a proposta: " . $errors);
            }
            $objetoValorMensal = PropostaComercialValorMensal::findFirst('id_proposta_comercial='.$objArray->getId());
            $objetoValorMensal->setTransaction($transaction);
            $objetoValorMensal->setJan((!empty($objArrayValorMensal->getJan())) ? $util->formataNumeroMoeda($objArrayValorMensal->getJan()) : 0);
            $objetoValorMensal->setFev((!empty($objArrayValorMensal->getFev())) ? $util->formataNumeroMoeda($objArrayValorMensal->getFev()) : 0);
            $objetoValorMensal->setMar((!empty($objArrayValorMensal->getMar())) ? $util->formataNumeroMoeda($objArrayValorMensal->getMar()) : 0);
            $objetoValorMensal->setAbr((!empty($objArrayValorMensal->getAbr())) ? $util->formataNumeroMoeda($objArrayValorMensal->getAbr()) : 0);
            $objetoValorMensal->setMai((!empty($objArrayValorMensal->getMai())) ? $util->formataNumeroMoeda($objArrayValorMensal->getMai()) : 0);
            $objetoValorMensal->setJun((!empty($objArrayValorMensal->getJun())) ? $util->formataNumeroMoeda($objArrayValorMensal->getJun()) : 0);
            $objetoValorMensal->setJul((!empty($objArrayValorMensal->getJul())) ? $util->formataNumeroMoeda($objArrayValorMensal->getJul()) : 0);
            $objetoValorMensal->setAgo((!empty($objArrayValorMensal->getAgo())) ? $util->formataNumeroMoeda($objArrayValorMensal->getAgo()) : 0);
            $objetoValorMensal->setSet((!empty($objArrayValorMensal->getSet())) ? $util->formataNumeroMoeda($objArrayValorMensal->getSet()) : 0);
            $objetoValorMensal->setOut((!empty($objArrayValorMensal->getOut())) ? $util->formataNumeroMoeda($objArrayValorMensal->getOut()) : 0);
            $objetoValorMensal->setNov((!empty($objArrayValorMensal->getNov())) ? $util->formataNumeroMoeda($objArrayValorMensal->getNov()) : 0);
            $objetoValorMensal->setDez((!empty($objArrayValorMensal->getDez())) ? $util->formataNumeroMoeda($objArrayValorMensal->getDez()) : 0);
            $objetoValorMensal->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoValorMensal->save() == false) {
                $messages = $objetoValorMensal->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar os valores mensais: " . $errors);
            }
            if (count($arrObjItensProposta) > 0){
                foreach($arrObjItensProposta as $objItensProposta){
                    $objetoItemProposta = new PropostaComercialItem();
                    $objetoItemProposta->setTransaction($transaction);
                    $objetoItemProposta->setIdPropostaComercial($objeto->getId());
                    $objetoItemProposta->setIdPropostaComercialServicos($objItensProposta->getIdPropostaComercialServicos());
                    $objetoItemProposta->setImposto($objItensProposta->getImposto());
                    $objetoItemProposta->setReajuste($objItensProposta->getReajuste());
                    $objetoItemProposta->setQuantidade($objItensProposta->getQuantidade());
                    $objetoItemProposta->setMesInicial($objItensProposta->getMesInicial());
                    $objetoItemProposta->setVigencia($objItensProposta->getVigencia());
                    $objetoItemProposta->setValorUnitario((!empty($objItensProposta->getValorUnitario())) ? $util->formataNumero($objItensProposta->getValorUnitario()) : 0);
                    $objetoItemProposta->setValorTotal((!empty($objItensProposta->getValorTotal())) ? $util->formataNumero($objItensProposta->getValorTotal()) : 0);
                    $objetoItemProposta->setValorTotalReajuste((!empty($objItensProposta->getValorTotalReajuste())) ? $util->formataNumero($objItensProposta->getValorTotalReajuste()) : 0);
                    $objetoItemProposta->setValorImpostos((!empty($objItensProposta->getValorImpostos())) ? $util->formataNumero($objItensProposta->getValorImpostos()) : 0);
                    $objetoItemProposta->setValorTotalImpostos((!empty($objItensProposta->getValorTotalImpostos())) ? $util->formataNumero($objItensProposta->getValorTotalImpostos()) : 0);
                    $objetoItemProposta->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoItemProposta->save() == false) {
                        $messages = $objetoItemProposta->getMessages();
                        $errors = "";
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= "[".$messages[$i]."] ";
                        }
                        $transaction->rollback("Erro ao criar o item da proposta: " . $errors);
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

    public function ativar(PropostaComercial $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercial::findFirst($objArray->getId());
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

    public function inativar(PropostaComercial $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercial::findFirst($objArray->getId());
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

    public function excluir(PropostaComercial $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercial::findFirst($objArray->getId());
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

    public function visualizarPropostaComercial($id)
    {
        $util = new Util();
        try {
            $objeto = PropostaComercial::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'id_cliente' => $objeto->getIdCliente(),
                'ds_cliente' => $objeto->getCliente(),
                'id_status' => $objeto->getIdStatus(),
                'id_tipo_proposta' => $objeto->getIdTipoProposta(),
                'id_localizacao' => $objeto->getIdLocalizacao(),
                'data_proposta' => $util->converterDataParaBr($objeto->getDataProposta()),
                'numero' => $objeto->getNumero(),
                'vencimento' => $util->converterDataParaBr($objeto->getVencimento()),
                'reajuste' => $util->formataMoedaReal($objeto->getReajuste()),
                'desconto' => $util->formataMoedaReal($objeto->getDesconto()),
                'imposto' => $util->formataMoedaReal($objeto->getImposto()),
                'encargos' => $util->formataMoedaReal($objeto->getEncargos()),
                'valor_global' => $util->formataMoedaReal($objeto->getValorGlobal()),
                'objetivo' => $objeto->getObjetivo(),
                'objetivo_especifico' => $objeto->getObjetivoEspecifico(),
                'descritivo' => $objeto->getDescritivo(),
                'responsabilidade' => $objeto->getResponsabilidade(),
                'condicoes_pgto' => $objeto->getCondicoesPgto(),
                'prazo_execucao' => $objeto->getPrazoExecucao(),
                'consideracoes' => $objeto->getConsideracoes()
            );
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoArray)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarPropostaItens($id_proposta_comercial)
    {
        try {
            $objetosComponentes = PropostaComercialItem::find('id_proposta_comercial = ' . $id_proposta_comercial);
            $arrTransporte = [];
            foreach ($objetosComponentes as $objetoComponente){
                $objTransporte = new \stdClass();
                $objTransporte->id_proposta_comercial_item = $objetoComponente->getId();
                $objTransporte->id_proposta_comercial_servicos = $objetoComponente->getIdPropostaComercialServicos();
                $objTransporte->ds_codigo_servico = $objetoComponente->getCodigoServico();
                $objTransporte->ds_proposta_comercial_servicos = $objetoComponente->getServico();
                $objTransporte->ds_proposta_comercial_servicos_unidade = $objetoComponente->getServicoUnidade();
                $objTransporte->imposto = $objetoComponente->getImposto();
                $objTransporte->reajuste = $objetoComponente->getReajuste();
                $objTransporte->quantidade = $objetoComponente->getQuantidade();
                $objTransporte->mes_inicial = $objetoComponente->getMesInicial();
                $objTransporte->vigencia = $objetoComponente->getVigencia();
                $objTransporte->valor_unitario = $objetoComponente->getValorUnitario();
                $objTransporte->valor_total = $objetoComponente->getValorTotal();
                $objTransporte->valor_total_reajuste = $objetoComponente->getValorTotalReajuste();
                $objTransporte->valor_impostos = $objetoComponente->getValorImpostos();
                $objTransporte->valor_total_impostos = $objetoComponente->getValorTotalImpostos();
                array_push($arrTransporte, $objTransporte);
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $arrTransporte)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarPropostaItem($id_proposta_comercial_item)
    {
        try {
            $objetoComponente = PropostaComercialItem::findFirst('id=' . $id_proposta_comercial_item);
            $objTransporte = new \stdClass();
            $objTransporte->id_proposta_comercial_item = $objetoComponente->getId();
            $objTransporte->id_proposta_comercial_servicos = $objetoComponente->getIdPropostaComercialServicos();
            $objTransporte->ds_codigo_servico = $objetoComponente->getCodigoServico();
            $objTransporte->ds_proposta_comercial_servicos = $objetoComponente->getServico();
            $objTransporte->ds_proposta_comercial_servicos_unidade = $objetoComponente->getServicoUnidade();
            $objTransporte->imposto = $objetoComponente->getImposto();
            $objTransporte->reajuste = $objetoComponente->getReajuste();
            $objTransporte->quantidade = $objetoComponente->getQuantidade();
            $objTransporte->mes_inicial = $objetoComponente->getMesInicial();
            $objTransporte->vigencia = $objetoComponente->getVigencia();
            $objTransporte->valor_unitario = $objetoComponente->getValorUnitario();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objTransporte)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterarPropostaItem(PropostaComercialItem $objPropostaComercialItem)
    {
        $util = new Util();
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = PropostaComercialItem::findFirst('id='.$objPropostaComercialItem->getId());
            $objetoComponente->setTransaction($transaction);
            $objetoComponente->setIdPropostaComercialServicos($objPropostaComercialItem->getIdPropostaComercialServicos());
            $objetoComponente->setImposto($objPropostaComercialItem->getImposto());
            $objetoComponente->setReajuste($objPropostaComercialItem->getReajuste());
            $objetoComponente->setQuantidade($objPropostaComercialItem->getQuantidade());
            $objetoComponente->setMesInicial($objPropostaComercialItem->getMesInicial());
            $objetoComponente->setVigencia($objPropostaComercialItem->getVigencia());
            $objetoComponente->setValorUnitario((!empty($objPropostaComercialItem->getValorUnitario())) ? $util->formataNumero($objPropostaComercialItem->getValorUnitario()) : 0);
            $objetoComponente->setValorTotal((!empty($objPropostaComercialItem->getValorTotal())) ? $util->formataNumero($objPropostaComercialItem->getValorTotal()) : 0);
            $objetoComponente->setValorTotalReajuste((!empty($objPropostaComercialItem->getValorTotalReajuste())) ? $util->formataNumero($objPropostaComercialItem->getValorTotalReajuste()) : 0);
            $objetoComponente->setValorImpostos((!empty($objPropostaComercialItem->getValorImpostos())) ? $util->formataNumero($objPropostaComercialItem->getValorImpostos()) : 0);
            $objetoComponente->setValorTotalImpostos((!empty($objPropostaComercialItem->getValorTotalImpostos())) ? $util->formataNumero($objPropostaComercialItem->getValorTotalImpostos()) : 0);
            $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoComponente->save() == false) {
                $messages = $objetoComponente->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Não foi possível salvar o item da proposta: " . $errors);
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoComponente)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function deletarPropostaItem(PropostaComercialItem $objPropostaComercialItem)
    {
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = PropostaComercialItem::findFirst('id='.$objPropostaComercialItem->getId());
            $id_proposta_comercial = $objetoComponente->getIdPropostaComercial();
            $objetoComponente->setTransaction($transaction);
            if ($objetoComponente->delete() == false) {
                $transaction->rollback("Não foi possível deletar o item da proposta!");
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $id_proposta_comercial)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }
}