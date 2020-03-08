<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\ContratoFinanceiro;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

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
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new ContratoFinanceiro();
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

    public function alterar(ContratoFinanceiro $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoFinanceiro::findFirst($objArray->getId());
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

    public function visualizarContratoFinanceiro($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = ContratoFinanceiro::findFirst("id={$id}");
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

    public function validarCompetenciaExercicio($arrDados)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objetoFinanceiro = ContratoFinanceiro::findFirst('id_exercicio='.$arrDados['id_exercicio'].' AND mes_competencia="'.$arrDados['mes_competencia'].'"');
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
}