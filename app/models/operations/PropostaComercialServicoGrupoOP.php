<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\PropostaComercialServicoGrupo;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class PropostaComercialServicoGrupoOP extends PropostaComercialServicoGrupo
{
    private $encode = "UTF-8";

    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function listar($dados)
    {
        return PropostaComercialServicoGrupo::pesquisarPropostaComercialServicoGrupo($dados);
    }

    public function cadastrar(PropostaComercialServicoGrupo $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new PropostaComercialServicoGrupo();
            $id_grupo_pai = ($objArray->getIdGrupoPai()) ? $objArray->getIdGrupoPai() : null;
            $objeto->setTransaction($transaction);
            $objeto->setIdGrupoPai($id_grupo_pai);
            $objeto->setCodigoLegado($objArray->getCodigoLegado());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setCodigoContabil($objArray->getCodigoContabil());
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $messages = $objeto->getMessages();
                $errors = "";
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= "[".$messages[$i]."] ";
                }
                $transaction->rollback("Erro ao criar o grupo de serviço: " . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(PropostaComercialServicoGrupo $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServicoGrupo::findFirst($objArray->getId());
            $id_grupo_pai = ($objArray->getIdGrupoPai()) ? $objArray->getIdGrupoPai() : null;
            $objeto->setTransaction($transaction);
            $objeto->setIdGrupoPai($id_grupo_pai);
            $objeto->setCodigoLegado($objArray->getCodigoLegado());
            $objeto->setDescricao(mb_strtoupper($objArray->getDescricao(), $this->encode));
            $objeto->setCodigoContabil($objArray->getCodigoContabil());
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o grupo de serviço!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function ativar(PropostaComercialServicoGrupo $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServicoGrupo::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o grupo de serviço!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(PropostaComercialServicoGrupo $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServicoGrupo::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível alterar o grupo de serviço!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(PropostaComercialServicoGrupo $objArray)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = PropostaComercialServicoGrupo::findFirst($objArray->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() == false) {
                $transaction->rollback("Não foi possível excluir o grupo de serviço!");
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarPropostaComercialServicoGrupo($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = PropostaComercialServicoGrupo::findFirst("id={$id}");
            $objetoArray = array(
                'id' => $objeto->getId(),
                'desc_grupo_pai' => $objeto->getGrupoPai(),
                'id_grupo_pai' => $objeto->getIdGrupoPai(),
                'codigo_contabil' => $objeto->getCodigoContabil(),
                'codigo_legado' => $objeto->getCodigoLegado(),
                'descricao' => $objeto->getDescricao()
            );
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objetoArray)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function selectSubGrupo($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = PropostaComercialServicoGrupo::find("id_grupo_pai={$id} AND excluido=0 AND ativo=1");
            $response = new Response();
            $response->setContent(json_encode(array("operacao" => True,"dados" => $objeto)));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }
}