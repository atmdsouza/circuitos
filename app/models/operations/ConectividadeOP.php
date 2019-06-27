<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

use Circuitos\Models\Conectividade;

class ConectividadeOP extends \Phalcon\Mvc\Model
{

    public function __construct ()
    {
        parent::__construct ();
    }

    public function listar(Conectividade $objConectividade)
    {

    }

    public function cadastrar(Conectividade $objConectividade)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        $usuario = new Conectividade();

        $transaction->commit();
        $transaction->roolback('mensagem');

        try {

        } catch (TxFailed $e) {
            return $e->getMessage();
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
}