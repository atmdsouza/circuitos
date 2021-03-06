<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\EmpresaDepartamento;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class EmpresaDepartamentoOP extends EmpresaDepartamento
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return EmpresaDepartamento::pesquisarEmpresaDepartamento($dados);
    }

    public function cadastrar(EmpresaDepartamento $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new EmpresaDepartamento();
            $objeto->setIdEmpresa($objArray->getIdEmpresa());
            $objeto->setIdDepartamentoPai(($objArray->getIdDepartamentoPai()) ? $objArray->getIdDepartamentoPai() : null);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar o departamento!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(EmpresaDepartamento $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = EmpresaDepartamento::findFirst($objArray->getId());
            $objeto->setIdEmpresa($objArray->getIdEmpresa());
            $objeto->setIdDepartamentoPai(($objArray->getIdDepartamentoPai()) ? $objArray->getIdDepartamentoPai() : null);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o departamento!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(EmpresaDepartamento $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = EmpresaDepartamento::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o departamento!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(EmpresaDepartamento $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = EmpresaDepartamento::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o departamento!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(EmpresaDepartamento $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = EmpresaDepartamento::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir o departamento!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarEmpresaDepartamento($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = EmpresaDepartamento::findFirst("id={$id}");
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