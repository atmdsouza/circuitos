<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\SetSeguranca;
use Circuitos\Models\SetSegurancaComponentes;
use Circuitos\Models\SetSegurancaContato;

class SetSegurancaOP extends SetSeguranca
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return SetSeguranca::pesquisarSetSeguranca($dados);
    }

    public function cadastrar(SetSeguranca $objArray, $arrayObjComponente, $arrayObjContato)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new SetSeguranca();
            $objeto->setTransaction($transaction);
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar o SetSeguranca!");
            }
            if (count($arrayObjComponente) > 0) {
                foreach($arrayObjComponente as $key => $objComponente){
                    $objetoComponente = new SetSegurancaComponentes();
                    $objetoComponente->setTransaction($transaction);
                    $objetoComponente->setIdSetSeguranca($objeto->getId());
                    $objetoComponente->setIdTipo($objComponente->getIdTipo());
                    $objetoComponente->setIdContrato($objComponente->getIdContrato());
                    $objetoComponente->setIdFornecedor($objComponente->getIdFornecedor());
                    $objetoComponente->setPropriedadeProdepa($objComponente->getPropriedadeProdepa());
                    $objetoComponente->setSenha($objComponente->getSenha());
                    $objetoComponente->setValidade($objComponente->getValidade());
                    $objetoComponente->setEnderecoChave(mb_strtoupper($objComponente->getEnderecoChave(), $this->encode));
                    $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoComponente->save() == false) {
                        $transaction->rollback("Não foi possível salvar o SetSegurancaComponente!");
                    }
                    if ($arrayObjContato[$key]->getNome()){
                        $objetoContato = new SetSegurancaContato();
                        $objetoContato->setTransaction($transaction);
                        $objetoContato->setIdSetSegurancaComponente($objetoComponente->getId());
                        $objetoContato->setNome(mb_strtoupper($arrayObjContato[$key]->getNome(), $this->encode));
                        $objetoContato->setTelefone(mb_strtoupper($arrayObjContato[$key]->getTelefone(), $this->encode));
                        $objetoContato->setEmail($arrayObjContato[$key]->getEmail());
                        $objetoContato->setDataUpdate(date('Y-m-d H:i:s'));
                        if ($objetoContato->save() == false) {
                            $messages = $objetoContato->getMessages();
                            $errors = "";
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= "[".$messages[$i]."] ";
                            }
                            $transaction->rollback("Não foi possível salvar o SetSegurancaContato!");
                        }
                    }
                }
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            exit;
            return false;
        }
    }

    public function alterar(SetSeguranca $objArray, $arrayObjComponente, $arrayObjContato)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetSeguranca::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdCidadeDigital($objArray->getIdCidadeDigital());
            $objeto->setIdTipo($objArray->getIdTipo());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setEndereco(mb_strtoupper($objArray->getEndereco(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o set de segurança!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function ativar(SetSeguranca $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetSeguranca::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o set de segurança!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function inativar(SetSeguranca $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetSeguranca::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o set de segurança!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function excluir(SetSeguranca $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetSeguranca::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir o set de segurança!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarSetSeguranca($id)
    {
        try {
            $objeto = SetSeguranca::findFirst("id={$id}");
            $objetosComponente = SetSegurancaComponentes::find('id_set_seguranca = ' . $objeto->getId());
            foreach ($objetosComponente as $key => $objetoComponente){
                $objTransporte = new \stdClass();
                $objTransporte->cont_id = $objetoComponente->getIdContato();
                $objTransporte->cont_nome = $objetoComponente->getContatoNome();
                $objTransporte->cont_email = $objetoComponente->getContatoEmail();
                $objTransporte->cont_telefone = $objetoComponente->getContatoTelefone();
                $objTransporte->desc_contrato = $objetoComponente->getContrato();
                $objTransporte->desc_fornecedor = $objetoComponente->getFornecedor();
                $objTransporte->desc_tipo = $objetoComponente->getTipo();
                $objTransporte->id_contrato = $objetoComponente->getIdContrato();
                $objTransporte->id_fornecedor = $objetoComponente->getIdFornecedor();
                $objTransporte->id_tipo = $objetoComponente->getIdTipo();
                $objTransporte->propriedade_prodepa = $objetoComponente->getPropriedadeProdepa();
                $objTransporte->senha = $objetoComponente->getSenha();
                $objTransporte->validade = $objetoComponente->getValidade();
                $objTransporte->endereco = $objetoComponente->getEnderecoChave();
                $objTransporte->id_componente = $objetoComponente->getId();
            }
            $arrayObjetos = array(
                'objPrincipal' => $objeto,
                'objComponente' => $objTransporte
            );
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $arrayObjetos)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }
}