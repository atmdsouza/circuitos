<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\SetSeguranca;
use Circuitos\Models\SetSegurancaComponentes;
use Circuitos\Models\SetSegurancaContato;

use Util\Util;

class SetSegurancaOP extends SetSeguranca
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return SetSeguranca::pesquisarSetSeguranca($dados);
    }

    public function cadastrar(SetSeguranca $objArray, $arrayObjComponente, $arrayObjContato)
    {
        $util = new Util();
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
                    $objetoComponente->setValidade(($objComponente->getValidade()) ? $util->converterDataUSA($objComponente->getValidade()) : null);
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
                        $objetoContato->setTelefone($util->formataNumeroTelefone($arrayObjContato[$key]->getTelefone()));
                        $objetoContato->setEmail($arrayObjContato[$key]->getEmail());
                        $objetoContato->setDataUpdate(date('Y-m-d H:i:s'));
                        if ($objetoContato->save() == false) {
                            $messages = $objetoContato->getMessages();
                            $errors = "";
                            for ($i = 0; $i < count($messages); $i++) {
                                $errors .= "[".$messages[$i]."] ";
                            }
                            $transaction->rollback("Não foi possível salvar o SetSegurancaContato: " . $errors);
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
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = SetSeguranca::findFirst($objArray->getId());
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
                    $objetoComponente->setValidade(($objComponente->getValidade()) ? $util->converterDataUSA($objComponente->getValidade()) : null);
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
                        $objetoContato->setTelefone($util->formataNumeroTelefone($arrayObjContato[$key]->getTelefone()));
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
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objeto)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarComponentesSetSeguranca($id_set_seguranca)
    {
        $util = new Util();
        try {
            $objetosComponente = SetSegurancaComponentes::find('id_set_seguranca = ' . $id_set_seguranca);
            $arrTransporte = [];
            foreach ($objetosComponente as $key => $objetoComponente){
                $objTransporte = new \stdClass();
                $objTransporte->cont_id = $objetoComponente->getIdContato();
                $objTransporte->cont_nome = $objetoComponente->getContatoNome();
                $objTransporte->cont_email = $objetoComponente->getContatoEmail();
                $objTransporte->cont_telefone = $util->mask($objetoComponente->getContatoTelefone(), '(##) ####-#####');
                $objTransporte->id_componente = $objetoComponente->getId();
                $objTransporte->id_contrato = $objetoComponente->getIdContrato();
                $objTransporte->desc_contrato = $objetoComponente->getContrato();
                $objTransporte->id_tipo = $objetoComponente->getIdTipo();
                $objTransporte->desc_tipo = $objetoComponente->getTipo();
                $objTransporte->id_fornecedor = $objetoComponente->getIdFornecedor();
                $objTransporte->desc_fornecedor = $objetoComponente->getFornecedor();
                $objTransporte->propriedade_prodepa = $objetoComponente->getPropriedadeProdepa();
                $objTransporte->senha = $objetoComponente->getSenha();
                $objTransporte->validade = $util->converterDataParaBr($objetoComponente->getValidade());
                $objTransporte->endereco = $objetoComponente->getEnderecoChave();
                array_push($arrTransporte, $objTransporte);
            }
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $arrTransporte)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarComponenteSetSeguranca($id)
    {
        $util = new Util();
        try {
            $objetoComponente = SetSegurancaComponentes::findFirst('id= ' . $id);
            $objTransporte = new \stdClass();
            $objTransporte->cont_id = $objetoComponente->getIdContato();
            $objTransporte->cont_nome = $objetoComponente->getContatoNome();
            $objTransporte->cont_email = $objetoComponente->getContatoEmail();
            $objTransporte->cont_telefone = $util->mask($objetoComponente->getContatoTelefone(), '(##) ####-#####');
            $objTransporte->id_componente = $objetoComponente->getId();
            $objTransporte->id_contrato = $objetoComponente->getIdContrato();
            $objTransporte->desc_contrato = $objetoComponente->getContrato();
            $objTransporte->id_tipo = $objetoComponente->getIdTipo();
            $objTransporte->desc_tipo = $objetoComponente->getTipo();
            $objTransporte->id_fornecedor = $objetoComponente->getIdFornecedor();
            $objTransporte->desc_fornecedor = $objetoComponente->getFornecedor();
            $objTransporte->propriedade_prodepa = $objetoComponente->getPropriedadeProdepa();
            $objTransporte->senha = $objetoComponente->getSenha();
            $objTransporte->validade = $util->converterDataParaBr($objetoComponente->getValidade());
            $objTransporte->endereco = $objetoComponente->getEnderecoChave();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objTransporte)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterarComponenteSeguranca(SetSegurancaComponentes $objComponente, SetSegurancaContato $objContato)
    {
        try {
            $util = new Util();
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = SetSegurancaComponentes::findFirst('id='.$objComponente->getId());
            $objetoComponente->setTransaction($transaction);
            $objetoComponente->setIdTipo($objComponente->getIdTipo());
            $objetoComponente->setIdContrato(($objComponente->getIdContrato()) ? $objComponente->getIdContrato() : null);
            $objetoComponente->setIdFornecedor($objComponente->getIdFornecedor());
            $objetoComponente->setPropriedadeProdepa($objComponente->getPropriedadeProdepa());
            $objetoComponente->setSenha($objComponente->getSenha());
            $objetoComponente->setValidade(($objComponente->getValidade()) ? $util->converterDataUSA($objComponente->getValidade()) : null);
            $objetoComponente->setEnderecoChave(mb_strtoupper($objComponente->getEnderecoChave(), $this->encode));
            $objetoComponente->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoComponente->save() == false) {
                $transaction->rollback("Não foi possível salvar o SetSegurancaComponente!");
            }
            if ($objContato->getId()) {
                $objetoContato = SetSegurancaContato::findFirst('id='.$objContato->getId());
                $objetoContato->setTransaction($transaction);
            } else {
                $objetoContato = new SetSegurancaContato();
                $objetoContato->setTransaction($transaction);
                $objetoContato->setIdSetSegurancaComponente($objComponente->getId());
            }
            $objetoContato->setNome(mb_strtoupper($objContato->getNome(), $this->encode));
            $objetoContato->setTelefone($util->formataNumeroTelefone($objContato->getTelefone()));
            $objetoContato->setEmail($objContato->getEmail());
            $objetoContato->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objetoContato->save() == false) {
                $messages = $objetoContato->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Não foi possível salvar o SetSegurancaContato: " . $errors);
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoComponente)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function deletarComponenteSeguranca(SetSegurancaComponentes $objComponente)
    {
        try {
            $manager = new TxManager();
            $transaction = $manager->get();
            $objetoComponente = SetSegurancaComponentes::findFirst('id='.$objComponente->getId());
            $id_set_seguranca = $objetoComponente->getIdSetSeguranca();
            $objetoComponente->setTransaction($transaction);
            if ($objetoComponente->delete() == false) {
                $transaction->rollback("Não foi possível deletar o SetSegurancaComponente!");
            }
            $transaction->commit();
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $id_set_seguranca)));
            return $response;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }
}