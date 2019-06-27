<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

use Circuitos\Models\CidadeDigital;

class CidadeDigitalOP extends \Phalcon\Mvc\Model
{

    public function __construct ()
    {
        parent::__construct ();
    }

    public function listar(CidadeDigital $objCidadeDigital)
    {

    }

    public function cadastrar(CidadeDigital $objCidadeDigital)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        $usuario = new CidadeDigital();

        $transaction->commit();
        $transaction->roolback('mensagem');

        try {

        } catch (TxFailed $e) {
            return $e->getMessage();
        }

    }

    public function alterar(CidadeDigital $objCidadeDigital)
    {

    }

    public function consultar(CidadeDigital $objCidadeDigital)
    {

    }

    public function ativar(CidadeDigital $objCidadeDigital)
    {

    }

    public function inativar(CidadeDigital $objCidadeDigital)
    {

    }

    public function excluir(CidadeDigital $objCidadeDigital)
    {

    }
}