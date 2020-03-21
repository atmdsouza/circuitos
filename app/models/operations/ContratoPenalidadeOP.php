<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\ContratoPenalidade;
use Circuitos\Models\ContratoPenalidadeAnexo;
use Phalcon\Http\Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Util\Util;

class ContratoPenalidadeOP extends ContratoPenalidade
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return ContratoPenalidade::pesquisarContratoPenalidade($dados);
    }

    public function cadastrar(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new ContratoPenalidade();
            $objeto->setTransaction($transaction);
            $objeto->setIdContrato($objPenalidade->getIdContrato());
            $objeto->setIdServico($objPenalidade->getIdServico());
            $objeto->setNumeroProcesso(mb_strtoupper($objPenalidade->getNumeroProcesso(), $this->encode));
            $objeto->setNumeroNotificacao(mb_strtoupper($objPenalidade->getNumeroNotificacao(), $this->encode));
            $objeto->setNumeroRt(mb_strtoupper($objPenalidade->getNumeroRt(), $this->encode));
            $objeto->setNumeroOficio(mb_strtoupper($objPenalidade->getNumeroOficio(), $this->encode));
            $objeto->setMotivoPenalidade(mb_strtoupper($objPenalidade->getMotivoPenalidade(), $this->encode));
            $objeto->setNumeroOficioMulta(mb_strtoupper($objPenalidade->getNumeroOficioMulta(), $this->encode));
            $objeto->setValorMulta(($objPenalidade->getValorMulta()) ? $util->formataNumero($objPenalidade->getValorMulta()) : 0);
            $objeto->setObservacao(mb_strtoupper($objPenalidade->getObservacao(), $this->encode));
            $objeto->setDataCriacao(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '[' .$messages[$i]. '] ';
                }
                $transaction->rollback('Erro ao criar a penalidade: ' . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdUsuario($objPenalidade->getIdUsuario());
            $objeto->setIdFiscalSuplente(($objPenalidade->getIdFiscalSuplente()) ? $objPenalidade->getIdFiscalSuplente() : null);
            $objeto->setTipoFiscal($objPenalidade->getTipoFiscal());
            $objeto->setDataNomeacao(($objPenalidade->getDataNomeacao()) ? $util->converterDataUSA($objPenalidade->getDataNomeacao()) : null);
            $objeto->setDocumentoNomeacao(mb_strtoupper($objPenalidade->getDocumentoNomeacao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao alterar a penalidade: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoPenalidade::findFirst($objPenalidade->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao alterar a penalidade: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoPenalidade::findFirst($objPenalidade->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '[' .$messages[$i]. '] ';
                }
                $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao excluir a penalidade: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoPenalidade($id)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $objeto = ContratoPenalidade::findFirst("id={$id}");
            $objDescricao = new \stdClass();
            $objDescricao->nome_fiscal = $objeto->getNomeFiscal();
            $objDescricao->nome_fiscal_suplente = $objeto->getNomeFiscalSuplente();
            $objDescricao->ds_contrato = $objeto->getNumeroContrato();
            $objDescricao->id_contrato = $objeto->getIdContrato();
            $objDescricao->ds_data_nomeacao = ($objeto->getDataNomeacao()) ? $util->converterDataParaBr($objeto->getDataNomeacao()) : null;
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True, "dados" => $objeto, "descricoes" => $objDescricao)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoPenalidadeNome($id)
    {
        $objeto = ContratoPenalidade::findFirst("id={$id}");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True,"dados" => $objeto->getNomeFiscal())));
        return $response;
    }

    public function visualizarContratoPenalidadeAnexos($id_contrato_fiscal)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $objetosComponentes = ContratoPenalidadeAnexo::find('id_contrato_fiscal= ' . $id_contrato_fiscal);
            $arrTransporte = [];
            foreach ($objetosComponentes as $objetoComponente){
                chmod($objetoComponente->getUrlAnexo(), 0777);
                $url_base = explode("/", $objetoComponente->getUrlAnexo());
                $url = $url_base[count($url_base)-5].'/'.$url_base[count($url_base)-4].'/'.$url_base[count($url_base)-3].'/'.$url_base[count($url_base)-2].'/'.$url_base[count($url_base)-1];
                $objTransporte = new \stdClass();
                $objTransporte->id_contrato_anexo = $objetoComponente->getId();
                $objTransporte->id_contrato = $objetoComponente->getIdContratoPenalidade();
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
}