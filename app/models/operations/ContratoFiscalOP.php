<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\ContratoFiscal;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class ContratoFiscalOP extends ContratoFiscal
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return ContratoFiscal::pesquisarContratoFiscal($dados);
    }

    public function cadastrar(ContratoFiscal $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new ContratoFiscal();
            $objeto->setIdEmpresa($objArray->getIdEmpresa());
            $objeto->setIdDepartamentoPai(($objArray->getIdDepartamentoPai()) ? $objArray->getIdDepartamentoPai() : null);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a proposta: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(ContratoFiscal $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFiscal::findFirst($objArray->getId());
            $objeto->setIdEmpresa($objArray->getIdEmpresa());
            $objeto->setIdDepartamentoPai(($objArray->getIdDepartamentoPai()) ? $objArray->getIdDepartamentoPai() : null);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a proposta: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(ContratoFiscal $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFiscal::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a proposta: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(ContratoFiscal $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFiscal::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a proposta: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(ContratoFiscal $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFiscal::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a proposta: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoFiscal($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = ContratoFiscal::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'id_departamento_pai' => $objeto->getIdDepartamentoPai(),
                'ds_departamento_pai' => $objeto->getNomeDepartamentoPai(),
                'descricao' => $objeto->getDescricao(),
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