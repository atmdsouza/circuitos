<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\PropostaComercialServico;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class PropostaComercialServicoOP extends PropostaComercialServico
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return PropostaComercialServico::pesquisarPropostaComercialServico($dados);
    }

    public function cadastrar(PropostaComercialServico $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new PropostaComercialServico();
            $objeto->setTransaction($transaction);
            $objeto->setIdPropostaComercialServicoGrupo($objArray->getIdPropostaComercialServicoGrupo());
            $objeto->setIdPropostaComercialServicoUnidade($objArray->getIdPropostaComercialServicoUnidade());
            $objeto->setCodigoLegado($objArray->getCodigoLegado());
            $objeto->setCodigoContabil($objArray->getCodigoContabil());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setImplantacao($objArray->getImplantacao());
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível salvar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(PropostaComercialServico $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServico::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdPropostaComercialServicoGrupo($objArray->getIdPropostaComercialServicoGrupo());
            $objeto->setIdPropostaComercialServicoUnidade($objArray->getIdPropostaComercialServicoUnidade());
            $objeto->setCodigoLegado($objArray->getCodigoLegado());
            $objeto->setCodigoContabil($objArray->getCodigoContabil());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setImplantacao($objArray->getImplantacao());
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(PropostaComercialServico $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServico::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(PropostaComercialServico $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServico::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(PropostaComercialServico $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServico::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir a conectividade!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarPropostaComercialServico($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = PropostaComercialServico::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'id_proposta_comercial_servico_grupo' => $objeto->getIdPropostaComercialServicoGrupo(),
                'id_proposta_comercial_servico_unidade' => $objeto->getIdPropostaComercialServicoUnidade(),
                'desc_proposta_comercial_servico_grupo' => $objeto->getGrupo(),
                'desc_proposta_comercial_servico_unidade' => $objeto->getUnidade(),
                'codigo_legado' => $objeto->getCodigoLegado(),
                'codigo_contabil' => $objeto->getCodigoContabil(),
                'descricao' => $objeto->getDescricao(),
                'implantacao' => $objeto->getImplantacao()
            );
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoArray)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function selectIdServico($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = PropostaComercialServico::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'codigo_legado' => $objeto->getCodigoLegado(),
                'descricao' => $objeto->getDescricao(),
                'grandeza' => $objeto->getUnidade()
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