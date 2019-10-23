<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\PropostaComercialServicoUnidade;

class PropostaComercialServicoUnidadeOP extends PropostaComercialServicoUnidade
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return PropostaComercialServicoUnidade::pesquisarPropostaComercialServicoUnidade($dados);
    }

    public function cadastrar(PropostaComercialServicoUnidade $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new PropostaComercialServicoUnidade();
            $objeto->setTransaction($transaction);
            $objeto->setSigla(mb_strtoupper($objArray->getSigla(), $this->encode));
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar a Unidade de serviço: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterar(PropostaComercialServicoUnidade $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServicoUnidade::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setSigla(mb_strtoupper($objArray->getSigla(), $this->encode));
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a unidade de serviço!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function ativar(PropostaComercialServicoUnidade $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServicoUnidade::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a unidade de serviço!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function inativar(PropostaComercialServicoUnidade $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServicoUnidade::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a unidade de serviço!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function excluir(PropostaComercialServicoUnidade $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServicoUnidade::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir a unidade de serviço!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarPropostaComercialServicoUnidade($id)
    {
        try {
            $objeto = PropostaComercialServicoUnidade::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'descricao' => $objeto->getDescricao(),
                'sigla' => $objeto->getSigla()
            );
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoArray)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }
}