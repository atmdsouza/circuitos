<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\Anexos;
use Circuitos\Models\ContratoFinanceiro;
use Circuitos\Models\ContratoFinanceiroNota;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Util\Util;

class ContratoFinanceiroOP extends ContratoFinanceiro
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return ContratoFinanceiro::pesquisarContratoFinanceiro($dados);
    }

    public function cadastrar(ContratoFinanceiro $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new ContratoFinanceiro();
            $objeto->setTransaction($transaction);
            $objeto->setIdExercicio($objArray->getIdExercicio());
            $objeto->setMesCompetencia($objArray->getMesCompetencia());
            $objeto->setStatusPagamento(1);
            $objeto->setValorPagamento($util->formataNumero($objArray->getValorPagamento()));
            $objeto->setDataCriacao(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar o pagamento: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(ContratoFinanceiro $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFinanceiro::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdExercicio($objArray->getIdExercicio());
            $objeto->setMesCompetencia($objArray->getMesCompetencia());
            $objeto->setStatusPagamento(1);
            $objeto->setValorPagamento($util->formataNumero($objArray->getValorPagamento()));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao editar o pagamento: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(ContratoFinanceiro $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFinanceiro::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao ativar o pagamento: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(ContratoFinanceiro $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFinanceiro::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '[' .$messages[$i]. '] ';
                }
                $transaction->rollback('Erro ao inativar o pagamento: ' . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(ContratoFinanceiro $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFinanceiro::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '[' .$messages[$i]. ']';
                }
                $transaction->rollback('Erro ao excluiro pagamento: ' . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoFinanceiro($id)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $objeto = ContratoFinanceiro::findFirst("id={$id}");
            $arrValidaQuitacao = $this->verificarQuitacaoPagamento($objeto->getId());
            $objetoDescricao = array(
                'id_contrato' => $objeto->getIdContrato(),
                'ds_contrato' => $objeto->getNumeroAnoContrato(),
                'ds_exercicio' => $objeto->getExercicio(),
                'ds_status_pagamento' => $objeto->getStatusDescricao(),
                'valor_pagamento_formatado' => $util->formataMoedaReal($objeto->getValorPagamento()),
                'valor_pagamento_realizado_formatado' => $util->formataMoedaReal($arrValidaQuitacao['valor_pago']),
                'valor_pagamento_pendente_formatado' => $util->formataMoedaReal($objeto->getValorPagamento() - $arrValidaQuitacao['valor_pago']),
            );
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True, "quitado" => $arrValidaQuitacao['quitado'], "dados_objeto" => $objeto, "dados_descricoes" => $objetoDescricao)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoFinanceiroNotas($id)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $objetos = ContratoFinanceiroNota::find("ativo=1 AND excluido=0 AND id_contrato_financeiro={$id}");
            $objetosDescricoes = [];
            foreach ($objetos as $objeto)
            {
                $objetoDescricao = new \stdClass();
                $objetoDescricao->url_anexo = $objeto->getUrlAnexo();
                $objetoDescricao->url_anexo_formatado = $objeto->getUrlFormatadaAnexo();
                $objetoDescricao->data_pagamento_formatada = $util->converterDataParaBr($objeto->getDataPagamento());
                $objetoDescricao->valor_pagamento_formatado = $util->formataMoedaReal($objeto->getValorNota());
                $objetoDescricao->id_anexo = $objeto->getIdAnexo();
                $objetoDescricao->id_tipo_anexo = $objeto->getIdTipoAnexo();
                $objetoDescricao->ds_tipo_anexo = $objeto->getDescricaoTipoAnexo();
                $objetoDescricao->descricao = $objeto->getDescricaoAnexo();
                $objetoDescricao->data_criacao = $util->converterDataHoraParaBr($objeto->getDataCriacaoAnexo());
                array_push($objetosDescricoes, $objetoDescricao);
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True, "dados_objeto" => $objetos, "dados_descricoes" => $objetosDescricoes)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se um pagamento já possui sua quitação através do total de notas fiscais lançadas
     * return @array
     **/
    private function verificarQuitacaoPagamento($id_contrato_financeiro)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = ContratoFinanceiro::findFirst('id='.$id_contrato_financeiro);
            return [
                'quitado' => $objeto->getValorPagamento() <= $objeto->getValorPago(),
                'valor_pago' => $objeto->getValorPago()
            ];
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function validarCompetenciaExercicio($arrDados)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetoFinanceiro = ContratoFinanceiro::findFirst('ativo=1 AND excluido=0 AND id_exercicio='.$arrDados['id_exercicio'].' AND mes_competencia="'.$arrDados['mes_competencia'].'"');
            $competenciaUtilizada = false;
            if ($objetoFinanceiro){
                $competenciaUtilizada = true;
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True, "competenciaUtilizada" => $competenciaUtilizada)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function cadastrarBaixa(ContratoFinanceiroNota $objContratoFinanceiroNota)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new ContratoFinanceiroNota();
            $objeto->setTransaction($transaction);
            $objeto->setIdContratoFinanceiro($objContratoFinanceiroNota->getIdContratoFinanceiro());
            $objeto->setNumeroNotaFiscal($objContratoFinanceiroNota->getNumeroNotaFiscal());
            $objeto->setDataPagamento($util->converterDataUSA($objContratoFinanceiroNota->getDataPagamento()));
            $objeto->setValorNota($util->formataNumero($objContratoFinanceiroNota->getValorNota()));
            $objeto->setObservacao(mb_strtoupper($objContratoFinanceiroNota->getObservacao(), $this->encode));
            $objeto->setDataCriacao(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a nota: " . $errors);
            }
            $arrQuitacao = $this->verificarQuitacaoPagamento($objeto->getIdContratoFinanceiro());
            $objetoPai = ContratoFinanceiro::findFirst($objContratoFinanceiroNota->getIdContratoFinanceiro());
            $objetoPai->setTransaction($transaction);
            $objetoPai->setStatusPagamento(($arrQuitacao['quitado']) ? 2 : 3);
            $objetoPai->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoPai->save() === false) {
                $messages = $objetoPai->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao alterar o pagamento: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluirBaixa(ContratoFinanceiroNota $objContratoFinanceiroNota)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFinanceiroNota::findFirst('id='.$objContratoFinanceiroNota->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataCriacao(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '[' .$messages[$i]. '] ';
                }
                $transaction->rollback('Erro ao excluir o pagamento: ' . $errors);
            }
            if ($objeto->getIdAnexo() !== null) {
                $objetoAnexo = Anexos::findFirst('id='.$objeto->getIdAnexo());
                unlink($objetoAnexo->getUrl());
                $objetoAnexo->setTransaction($transaction);
                $objetoAnexo->setExcluido(1);
                $objetoAnexo->setDataCriacao(date('Y-m-d H:i:s'));
                if ($objetoAnexo->save() === false) {
                    $messages = $objetoAnexo->getMessages();
                    $errors = '';
                    for ($i = 0; $i < count($messages); $i++) {
                        $errors .= '[' .$messages[$i]. '] ';
                    }
                    $transaction->rollback('Erro ao excluir o anexo: ' . $errors);
                }
            }
            $arrQuitacao = $this->verificarQuitacaoPagamento($objeto->getIdContratoFinanceiro());
            $objetoPai = ContratoFinanceiro::findFirst($objeto->getIdContratoFinanceiro());
            $objetoPai->setTransaction($transaction);
            $objetoPai->setStatusPagamento(($arrQuitacao['quitado']) ? 2 : 3);
            $objetoPai->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoPai->save() === false) {
                $messages = $objetoPai->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '[' .$messages[$i]. '] ';
                }
                $transaction->rollback('Erro ao alterar o pagamento: ' . $errors);
            }
            $transaction->commit();
            return $objeto->getIdContratoFinanceiro();
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }
}