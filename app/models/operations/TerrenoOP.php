<?php

namespace Circuitos\Models\Operations;

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Http\Response as Response;

use Circuitos\Models\Terreno;

use Util\Util;

class TerrenoOP extends Terreno
{
    private $encode = "UTF-8";

    public function listar($dados)
    {
        return Terreno::pesquisarTerreno($dados);
    }

    public function cadastrar(Terreno $objArray)
    {
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new Terreno();
            $fornecedor = ($objArray->getIdFornecedor()) ? $objArray->getIdFornecedor() : null;
            $contrato = ($objArray->getIdContrato()) ? $objArray->getIdContrato() : null;
            $objeto->setTransaction($transaction);
            $objeto->setIdFornecedor($fornecedor);
            $objeto->setIdContrato($contrato);
            $objeto->setPropriedadeProdepa($objArray->getPropriedadeProdepa());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setComprimento($util->formataNumero($objArray->getComprimento()));
            $objeto->setLargura($util->formataNumero($objArray->getLargura()));
            $objeto->setArea($util->formataNumero($objArray->getArea()));
            $objeto->setCep($util->formataCpfCnpj($objArray->getCep()));
            $objeto->setEndereco(mb_strtoupper($objArray->getEndereco(), $this->encode));
            $objeto->setNumero(mb_strtoupper($objArray->getNumero(), $this->encode));
            $objeto->setBairro(mb_strtoupper($objArray->getBairro(), $this->encode));
            $objeto->setComplemento(mb_strtoupper($objArray->getComplemento(), $this->encode));
            $objeto->setCidade(mb_strtoupper($objArray->getCidade(), $this->encode));
            $objeto->setEstado(mb_strtoupper($objArray->getEstado(), $this->encode));
            $objeto->setSiglaEstado(mb_strtoupper($objArray->getSiglaEstado(), $this->encode));
            $objeto->setLatitude($objArray->getLatitude());
            $objeto->setLongitude($objArray->getLongitude());
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar o terreno!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function alterar(Terreno $objArray)
    {
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Terreno::findFirst($objArray->getId());
            $fornecedor = ($objArray->getIdFornecedor()) ? $objArray->getIdFornecedor() : null;
            $contrato = ($objArray->getIdContrato()) ? $objArray->getIdContrato() : null;
            $objeto->setTransaction($transaction);
            $objeto->setIdFornecedor($fornecedor);
            $objeto->setIdContrato($contrato);
            $objeto->setPropriedadeProdepa($objArray->getPropriedadeProdepa());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setComprimento($util->formataNumero($objArray->getComprimento()));
            $objeto->setLargura($util->formataNumero($objArray->getLargura()));
            $objeto->setArea($util->formataNumero($objArray->getArea()));
            $objeto->setCep($util->formataCpfCnpj($objArray->getCep()));
            $objeto->setEndereco(mb_strtoupper($objArray->getEndereco(), $this->encode));
            $objeto->setNumero(mb_strtoupper($objArray->getNumero(), $this->encode));
            $objeto->setBairro(mb_strtoupper($objArray->getBairro(), $this->encode));
            $objeto->setComplemento(mb_strtoupper($objArray->getComplemento(), $this->encode));
            $objeto->setCidade(mb_strtoupper($objArray->getCidade(), $this->encode));
            $objeto->setEstado(mb_strtoupper($objArray->getEstado(), $this->encode));
            $objeto->setSiglaEstado(mb_strtoupper($objArray->getSiglaEstado(), $this->encode));
            $objeto->setLatitude($objArray->getLatitude());
            $objeto->setLongitude($objArray->getLongitude());
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o terreno!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function ativar(Terreno $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Terreno::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o terreno!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function inativar(Terreno $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Terreno::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o terreno!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function excluir(Terreno $objArray)
    {
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = Terreno::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir o terreno!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function visualizarTerreno($id)
    {
        try {
            $objeto = Terreno::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'id_contrato' => $objeto->getIdContrato(),
                'desc_contrato' => $objeto->getContrato(),
                'id_fornecedor' => $objeto->getIdFornecedor(),
                'desc_fornecedor' => $objeto->getFornecedor(),
                'descricao' => $objeto->getDescricao(),
                'comprimento' => $objeto->getComprimento(),
                'largura' => $objeto->getLargura(),
                'area' => $objeto->getArea(),
                'cep' => $objeto->getCep(),
                'endereco' => $objeto->getEndereco(),
                'numero' => $objeto->getNumero(),
                'bairro' => $objeto->getBairro(),
                'complemento' => $objeto->getComplemento(),
                'cidade' => $objeto->getCidade(),
                'estado' => $objeto->getEstado(),
                'sigla_estado' => $objeto->getSiglaEstado(),
                'latitude' => $objeto->getLatitude(),
                'longitude' => $objeto->getLongitude(),
                'propriedade_prodepa' => $objeto->getPropriedadeProdepa()
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