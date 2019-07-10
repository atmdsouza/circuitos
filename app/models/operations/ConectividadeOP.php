<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\CidadeDigital;
use Circuitos\Models\Conectividade;
use Circuitos\Models\Lov;

class ConectividadeOP extends Conectividade
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return Conectividade::pesquisarConectividade($dados);
    }

    public function cadastrar(Conectividade $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new Conectividade();
            $objeto->setIdCidadeDigital($objArray->getIdCidadeDigital());
            $objeto->setIdTipo($objArray->getIdTipo());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setEndereco(mb_strtoupper($objArray->getEndereco(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterar(Conectividade $objArray)
    {

    }

    public function ativar(Conectividade $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Conectividade::findFirst($objArray->getId());
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

    public function inativar(Conectividade $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Conectividade::findFirst($objArray->getId());
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

    public function excluir(Conectividade $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Conectividade::findFirst($objArray->getId());
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

    public function consultar(Conectividade $objArray)
    {

    }

    public function cidadesDigitaisAtivas()
    {
        //Desabilita o layout para o ajax
        $response = new Response();
        $cidadedigital = CidadeDigital::find("excluido=0 AND ativo=1");
        $response->setContent(json_encode(array(
            "operacao" => True,
            "dados" => $cidadedigital
        )));
        return $response;
    }

    public function tiposCidadesDigitaisAtivas()
    {
        //Desabilita o layout para o ajax
        $response = new Response();
        $tipo = Lov::find("tipo=18 AND excluido=0 AND ativo=1");
        $response->setContent(json_encode(array(
            "operacao" => True,
            "dados" => $tipo
        )));
        return $response;
    }
}