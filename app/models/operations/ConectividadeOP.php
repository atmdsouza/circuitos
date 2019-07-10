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

    public function cadastrar(Conectividade $objConectividade)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $conectividade = new Conectividade();
            $conectividade->setIdCidadeDigital($objConectividade->getIdCidadeDigital());
            $conectividade->setIdTipo($objConectividade->getIdTipo());
            $conectividade->setDescricao(mb_strtoupper($objConectividade->getDescricao(), $this->encode));
            $conectividade->setEndereco(mb_strtoupper($objConectividade->getEndereco(), $this->encode));
            $conectividade->setDataUpdate(date('Y-m-d H:i:s'));
            if ($conectividade->save() == false) {
                $transaction->rollback("Não foi possível salvar a pessoa!");
            }
            $transaction->commit();
            return true;
        } catch (TxFailed $e) {
            return false;
        }
    }

    public function alterar(Conectividade $objConectividade)
    {

    }

    public function consultar(Conectividade $objConectividade)
    {

    }

    public function ativar(Conectividade $objConectividade)
    {

    }

    public function inativar(Conectividade $objConectividade)
    {

    }

    public function excluir(Conectividade $objConectividade)
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