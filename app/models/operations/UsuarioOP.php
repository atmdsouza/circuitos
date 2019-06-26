<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

use Circuitos\Models\Pessoa;
use Circuitos\Models\Usuario;

class UsuarioOP
{

    public function __construct ()
    {
        parent::__construct ();
    }

    public function cadastrar(Usuario $objUsuario)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        $usuario = new Usuario();

        $transaction->commit();
        $transaction->roolback('mensagem');

        try {

        } catch (TxFailed $e) {
            return $e->getMessage();
        }

    }

    public function alterar(Usuario $objUsuario)
    {

    }

    public function consultar(Usuario $objUsuario)
    {

    }

    public function ativar(Usuario $objUsuario)
    {

    }

    public function inativar(Usuario $objUsuario)
    {

    }

    public function excluir(Usuario $objUsuario)
    {

    }
}