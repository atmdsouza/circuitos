<?php

namespace Circuitos\Models\Operations;

use Auth\Autentica;
use Circuitos\Models\ContratoPenalidade;
use Circuitos\Models\ContratoPenalidadeAnexo;
use Circuitos\Models\ContratoPenalidadeMovimento;
use Phalcon\Http\Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Util\Util;

class ContratoPenalidadeOP extends ContratoPenalidade
{
    private $encode = 'UTF-8';

    private $arqLog = BASE_PATH . '/logs/systemlog.log';

    public function listar($dados)
    {
        return ContratoPenalidade::pesquisarContratoPenalidade($dados);
    }

    public function cadastrar(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = new ContratoPenalidade();
            $objeto->setTransaction($transaction);
            $objeto->setIdContrato($objPenalidade->getIdContrato());
            $objeto->setIdServico($objPenalidade->getIdServico());
            $objeto->setNumeroProcesso(mb_strtoupper($objPenalidade->getNumeroProcesso(), $this->encode));
            $objeto->setNumeroNotificacao(mb_strtoupper($objPenalidade->getNumeroNotificacao(), $this->encode));
            $objeto->setNumeroRt(mb_strtoupper($objPenalidade->getNumeroRt(), $this->encode));
            $objeto->setNumeroOficio(mb_strtoupper($objPenalidade->getNumeroOficio(), $this->encode));
            $objeto->setMotivoPenalidade(mb_strtoupper($objPenalidade->getMotivoPenalidade(), $this->encode));
            $objeto->setNumeroOficioMulta(mb_strtoupper($objPenalidade->getNumeroOficioMulta(), $this->encode));
            $objeto->setValorMulta(($objPenalidade->getValorMulta()) ? $util->formataNumero($objPenalidade->getValorMulta()) : 0);
            $objeto->setObservacao(mb_strtoupper($objPenalidade->getObservacao(), $this->encode));
            $objeto->setDataCriacao(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '[' .$messages[$i]. '] ';
                }
                $transaction->rollback('Erro ao criar a penalidade: ' . $errors);
            }
            $objetoMovimento = new ContratoPenalidadeMovimento();
            $objetoMovimento->setIdContratoPenalidade($objeto->getId());
            $this->movimentar(1,$objeto, $objetoMovimento);
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function alterar(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
            $objeto->setTransaction($transaction);
            $objeto->setIdContrato($objPenalidade->getIdContrato());
            $objeto->setIdServico($objPenalidade->getIdServico());
            $objeto->setNumeroProcesso(mb_strtoupper($objPenalidade->getNumeroProcesso(), $this->encode));
            $objeto->setNumeroNotificacao(mb_strtoupper($objPenalidade->getNumeroNotificacao(), $this->encode));
            $objeto->setNumeroRt(mb_strtoupper($objPenalidade->getNumeroRt(), $this->encode));
            $objeto->setNumeroOficio(mb_strtoupper($objPenalidade->getNumeroOficio(), $this->encode));
            $objeto->setMotivoPenalidade(mb_strtoupper($objPenalidade->getMotivoPenalidade(), $this->encode));
            $objeto->setNumeroOficioMulta(mb_strtoupper($objPenalidade->getNumeroOficioMulta(), $this->encode));
            $objeto->setValorMulta(($objPenalidade->getValorMulta()) ? $util->formataNumero($objPenalidade->getValorMulta()) : 0);
            $objeto->setObservacao(mb_strtoupper($objPenalidade->getObservacao(), $this->encode));
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '[' .$messages[$i]. '] ';
                }
                $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
            }
            $objetoMovimento = new ContratoPenalidadeMovimento();
            $objetoMovimento->setIdContratoPenalidade($objeto->getId());
            $this->movimentar(2,$objeto, $objetoMovimento);
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function movimentar($tipo_movimento, ContratoPenalidade $objPenalidade, ContratoPenalidadeMovimento $objMovimento)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        $auth = new Autentica();
        $manager = new TxManager();
        $transaction = $manager->get();
        switch ($tipo_movimento) {
            case 1:
            case 2:
            case 3:
                try {
                    $objeto = new ContratoPenalidadeMovimento();
                    $objeto->setTransaction($transaction);
                    $objeto->setIdContratoPenalidade($objPenalidade->getId());
                    $objeto->setIdTipoMovimento($objeto->buscarIdTipoMovimento(34, $tipo_movimento));
                    $objeto->setIdUsuario($auth->getIdUsuario());
                    $objeto->setDataMovimento(date('Y-m-d H:i:s'));
                    if ($objeto->save() === false) {
                        $messages = $objeto->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                    }
                    $transaction->commit();
                    return $objeto;
                } catch (TxFailed $e) {
                    $logger->error($e->getMessage());
                    return false;
                }
                break;
            case 4:
                try {
                    $objetoPenalidade = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
                    $data_recebimento_oficio_notificacao_anterior = $objetoPenalidade->getDataRecebimentoOficioNotificacaoFormatada();
                    $data_prazo_resposta_anterior = $objetoPenalidade->getDataPrazoRespostaFormatada();
                    $objetoPenalidade->setTransaction($transaction);
                    $objetoPenalidade->setDataRecebimentoOficioNotificacao($util->converterDataUSA($objPenalidade->getDataRecebimentoOficioNotificacao()));
                    $objetoPenalidade->setDataPrazoResposta($util->converterDataUSA($objPenalidade->getDataPrazoResposta()));
                    $objetoPenalidade->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoPenalidade->save() === false) {
                        $messages = $objetoPenalidade->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
                    }
                    $objeto = new ContratoPenalidadeMovimento();
                    $objeto->setTransaction($transaction);
                    $objeto->setIdContratoPenalidade($objPenalidade->getId());
                    $objeto->setIdTipoMovimento($objeto->buscarIdTipoMovimento(34, $tipo_movimento));
                    $objeto->setIdUsuario($auth->getIdUsuario());
                    $objeto->setDataMovimento(date('Y-m-d H:i:s'));
                    $objeto->setValorAnterior(($data_recebimento_oficio_notificacao_anterior) ? $data_recebimento_oficio_notificacao_anterior.' e '.$data_prazo_resposta_anterior : 'Sem dados');
                    $objeto->setValorAtualizado($objetoPenalidade->getDataRecebimentoOficioNotificacaoFormatada().' e '.$objetoPenalidade->getDataPrazoRespostaFormatada());
                    $objeto->setObservacao(mb_strtoupper($objMovimento->getObservacao(), $this->encode));
                    if ($objeto->save() === false) {
                        $messages = $objeto->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                    }
                    $transaction->commit();
                    return $objeto;
                } catch (TxFailed $e) {
                    $logger->error($e->getMessage());
                    return false;
                }
                break;
            case 5:
                try {
                    $objetoPenalidade = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
                    $data_apresentacao_defesa_anterior = $objetoPenalidade->getDataApresentacaoDefesaFormatada();
                    $objetoPenalidade->setTransaction($transaction);
                    $objetoPenalidade->setDataApresentacaoDefesa($util->converterDataUSA($objPenalidade->getDataApresentacaoDefesa()));
                    $objetoPenalidade->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoPenalidade->save() === false) {
                        $messages = $objetoPenalidade->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
                    }
                    $objeto = new ContratoPenalidadeMovimento();
                    $objeto->setTransaction($transaction);
                    $objeto->setIdContratoPenalidade($objPenalidade->getId());
                    $objeto->setIdTipoMovimento($objeto->buscarIdTipoMovimento(34, $tipo_movimento));
                    $objeto->setIdUsuario($auth->getIdUsuario());
                    $objeto->setDataMovimento(date('Y-m-d H:i:s'));
                    $objeto->setValorAnterior(($data_apresentacao_defesa_anterior) ? $data_apresentacao_defesa_anterior : 'Sem dados');
                    $objeto->setValorAtualizado($objetoPenalidade->getDataApresentacaoDefesaFormatada());
                    $objeto->setObservacao(mb_strtoupper($objMovimento->getObservacao(), $this->encode));
                    if ($objeto->save() === false) {
                        $messages = $objeto->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                    }
                    $transaction->commit();
                    return $objeto;
                } catch (TxFailed $e) {
                    $logger->error($e->getMessage());
                    return false;
                }
                break;
            case 6:
                try {
                    $objetoPenalidade = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
                    $data_recebimento_oficio_multa_anterior = $objetoPenalidade->getDataRecebimentoOficioMultaFormatada();
                    $objetoPenalidade->setTransaction($transaction);
                    $objetoPenalidade->setDataRecebimentoOficioMulta($util->converterDataUSA($objPenalidade->getDataRecebimentoOficioMulta()));
                    $objetoPenalidade->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoPenalidade->save() === false) {
                        $messages = $objetoPenalidade->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
                    }
                    $objeto = new ContratoPenalidadeMovimento();
                    $objeto->setTransaction($transaction);
                    $objeto->setIdContratoPenalidade($objPenalidade->getId());
                    $objeto->setIdTipoMovimento($objeto->buscarIdTipoMovimento(34, $tipo_movimento));
                    $objeto->setIdUsuario($auth->getIdUsuario());
                    $objeto->setDataMovimento(date('Y-m-d H:i:s'));
                    $objeto->setValorAnterior(($data_recebimento_oficio_multa_anterior) ? $data_recebimento_oficio_multa_anterior : 'Sem dados');
                    $objeto->setValorAtualizado($objetoPenalidade->getDataRecebimentoOficioMultaFormatada());
                    $objeto->setObservacao(mb_strtoupper($objMovimento->getObservacao(), $this->encode));
                    if ($objeto->save() === false) {
                        $messages = $objeto->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                    }
                    $transaction->commit();
                    return $objeto;
                } catch (TxFailed $e) {
                    $logger->error($e->getMessage());
                    return false;
                }
                break;
            case 7:
                try {
                    $objetoPenalidade = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
                    $parecer_anterior = $objetoPenalidade->getParecer();
                    $objetoPenalidade->setTransaction($transaction);
                    $objetoPenalidade->setParecer(mb_strtoupper($objPenalidade->getParecer(), $this->encode));
                    $objetoPenalidade->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoPenalidade->save() === false) {
                        $messages = $objetoPenalidade->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
                    }
                    $objeto = new ContratoPenalidadeMovimento();
                    $objeto->setTransaction($transaction);
                    $objeto->setIdContratoPenalidade($objPenalidade->getId());
                    $objeto->setIdTipoMovimento($objeto->buscarIdTipoMovimento(34, $tipo_movimento));
                    $objeto->setIdUsuario($auth->getIdUsuario());
                    $objeto->setDataMovimento(date('Y-m-d H:i:s'));
                    $objeto->setValorAnterior(($parecer_anterior) ? mb_strimwidth($parecer_anterior, 0, 80, '...') : 'Sem dados');
                    $objeto->setValorAtualizado(mb_strimwidth($objetoPenalidade->getParecer(), 0, 80, '...'));
                    $objeto->setObservacao(mb_strtoupper($objMovimento->getObservacao(), $this->encode));
                    if ($objeto->save() === false) {
                        $messages = $objeto->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                    }
                    $transaction->commit();
                    return $objeto;
                } catch (TxFailed $e) {
                    $logger->error($e->getMessage());
                    return false;
                }
                break;
            case 8:
                try {
                    $objetoPenalidade = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
                    $status_anterior = $objetoPenalidade->getStatusDescricao();
                    $objetoPenalidade->setTransaction($transaction);
                    $objetoPenalidade->setStatus(1);
                    $objetoPenalidade->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoPenalidade->save() === false) {
                        $messages = $objetoPenalidade->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
                    }
                    $objeto = new ContratoPenalidadeMovimento();
                    $objeto->setTransaction($transaction);
                    $objeto->setIdContratoPenalidade($objPenalidade->getId());
                    $objeto->setIdTipoMovimento($objeto->buscarIdTipoMovimento(34, $tipo_movimento));
                    $objeto->setIdUsuario($auth->getIdUsuario());
                    $objeto->setDataMovimento(date('Y-m-d H:i:s'));
                    $objeto->setValorAnterior($status_anterior);
                    $objeto->setValorAtualizado($objetoPenalidade->getStatusDescricao());
                    $objeto->setObservacao(mb_strtoupper($objMovimento->getObservacao(), $this->encode));
                    if ($objeto->save() === false) {
                        $messages = $objeto->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                    }
                    $transaction->commit();
                    return $objeto;
                } catch (TxFailed $e) {
                    $logger->error($e->getMessage());
                    return false;
                }
                break;
            case 9:
                try {
                    $objetoPenalidade = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
                    $status_anterior = $objetoPenalidade->getStatusDescricao();
                    $objetoPenalidade->setTransaction($transaction);
                    $objetoPenalidade->setStatus(2);
                    $objetoPenalidade->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoPenalidade->save() === false) {
                        $messages = $objetoPenalidade->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
                    }
                    $objeto = new ContratoPenalidadeMovimento();
                    $objeto->setTransaction($transaction);
                    $objeto->setIdContratoPenalidade($objPenalidade->getId());
                    $objeto->setIdTipoMovimento($objeto->buscarIdTipoMovimento(34, $tipo_movimento));
                    $objeto->setIdUsuario($auth->getIdUsuario());
                    $objeto->setDataMovimento(date('Y-m-d H:i:s'));
                    $objeto->setValorAnterior($status_anterior);
                    $objeto->setValorAtualizado($objetoPenalidade->getStatusDescricao());
                    $objeto->setObservacao(mb_strtoupper($objMovimento->getObservacao(), $this->encode));
                    if ($objeto->save() === false) {
                        $messages = $objeto->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                    }
                    $transaction->commit();
                    return $objeto;
                } catch (TxFailed $e) {
                    $logger->error($e->getMessage());
                    return false;
                }
                break;
            case 10:
                try {
                    $objetoPenalidade = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
                    $status_anterior = $objetoPenalidade->getStatusDescricao();
                    $objetoPenalidade->setTransaction($transaction);
                    $objetoPenalidade->setStatus(0);
                    $objetoPenalidade->setDataUpdate(date('Y-m-d H:i:s'));
                    if ($objetoPenalidade->save() === false) {
                        $messages = $objetoPenalidade->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
                    }
                    $objeto = new ContratoPenalidadeMovimento();
                    $objeto->setTransaction($transaction);
                    $objeto->setIdContratoPenalidade($objPenalidade->getId());
                    $objeto->setIdTipoMovimento($objeto->buscarIdTipoMovimento(34, $tipo_movimento));
                    $objeto->setIdUsuario($auth->getIdUsuario());
                    $objeto->setDataMovimento(date('Y-m-d H:i:s'));
                    $objeto->setValorAnterior($status_anterior);
                    $objeto->setValorAtualizado($objetoPenalidade->getStatusDescricao());
                    $objeto->setObservacao(mb_strtoupper($objMovimento->getObservacao(), $this->encode));
                    if ($objeto->save() === false) {
                        $messages = $objeto->getMessages();
                        $errors = '';
                        for ($i = 0; $i < count($messages); $i++) {
                            $errors .= '[' .$messages[$i]. '] ';
                        }
                        $transaction->rollback('Erro ao criar o movimento: ' . $errors);
                    }
                    $transaction->commit();
                    return $objeto;
                } catch (TxFailed $e) {
                    $logger->error($e->getMessage());
                    return false;
                }
                break;
        }
    }

    public function ativar(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoPenalidade::findFirst($objPenalidade->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '['.$messages[$i].'] ';
                }
                $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function inativar(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoPenalidade::findFirst($objPenalidade->getId());
            $objeto->setTransaction($transaction);
            $objeto->setAtivo(0);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '[' .$messages[$i]. '] ';
                }
                $transaction->rollback('Erro ao alterar a penalidade: ' . $errors);
            }
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function excluir(ContratoPenalidade $objPenalidade)
    {
        $logger = new FileAdapter($this->arqLog);
        $manager = new TxManager();
        $transaction = $manager->get();
        try {
            $objeto = ContratoPenalidade::findFirst('id='.$objPenalidade->getId());
            $objeto->setTransaction($transaction);
            $objeto->setExcluido(1);
            $objeto->setDataUpdate(date('Y-m-d H:i:s'));
            if ($objeto->save() === false) {
                $messages = $objeto->getMessages();
                $errors = '';
                for ($i = 0; $i < count($messages); $i++) {
                    $errors .= '['.$messages[$i].'] ';
                }
                $transaction->rollback('Erro ao excluir a penalidade: ' . $errors);
            }
            $objetoMovimento = new ContratoPenalidadeMovimento();
            $objetoMovimento->setIdContratoPenalidade($objeto->getId());
            $this->movimentar(3,$objeto, $objetoMovimento);
            $transaction->commit();
            return $objeto;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoPenalidade($id)
    {
        $logger = new FileAdapter($this->arqLog);
        try {
            $objeto = ContratoPenalidade::findFirst('id='.$id);
            $objDescricao = new \stdClass();
            $objDescricao->ds_servico = $objeto->getServicoDescricao();
            $objDescricao->ds_status = $objeto->getStatusDescricao();
            $objDescricao->ds_contrato = $objeto->getNumeroAnoContrato();
            $objDescricao->valor_multa_formatado = $objeto->getValorMultaFormatado();
            $objDescricao->data_criacao_formatada = $objeto->getDataCriacaoFormatada();
            $objDescricao->data_recebimento_oficio_notificacao_formatada = $objeto->getDataRecebimentoOficioNotificacaoFormatada();
            $objDescricao->data_recebimento_oficio_multa_formatada = $objeto->getDataRecebimentoOficioMultaFormatada();
            $objDescricao->data_prazo_resposta_formatada = $objeto->getDataPrazoRespostaFormatada();
            $objDescricao->data_apresentacao_defesa_formatada = $objeto->getDataApresentacaoDefesaFormatada();
            $objMovimentos = ContratoPenalidadeMovimento::find('ativo=1 AND excluido=0 AND id_contrato_penalidade='.$id.' ORDER BY id DESC');
            $arrMovimentos = [];
            foreach ($objMovimentos as $objMovimento)
            {
                $objetoMovimento = new \stdClass();
                $objetoMovimento->data_movimento = $objMovimento->getDataMovimentoFormatada();
                $objetoMovimento->tipo_movimento = $objMovimento->getTipoMovimentoDescricao();
                $objetoMovimento->usuario_nome = $objMovimento->getUsuarioNome();
                $objetoMovimento->valor_atual = $objMovimento->getValorAnterior();
                $objetoMovimento->valor_anterior = $objMovimento->getValorAtualizado();
                $objetoMovimento->observacao = $objMovimento->getObservacao();
                array_push($arrMovimentos, $objetoMovimento);
            }
            $response = new Response();
            $response->setContent(json_encode(array('operacao' => True, 'dados' => $objeto, 'descricoes' => $objDescricao, 'movimentos' => $arrMovimentos, 'anexos' => $this->visualizarContratoPenalidadeAnexos($id, true))));
            return $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }

    public function visualizarContratoPenalidadeIdentificador($id)
    {
        $objeto = ContratoPenalidade::findFirst('id='.$id);
        $response = new Response();
        $response->setContent(json_encode(array('operacao' => True,'dados' => $objeto->getNumeroProcesso())));
        return $response;
    }

    public function visualizarContratoPenalidadeAnexos($id_contrato_penalidade, $visualizar = false)
    {
        $logger = new FileAdapter($this->arqLog);
        $util = new Util();
        try {
            $objetosComponentes = ContratoPenalidadeAnexo::find('id_contrato_penalidade= ' . $id_contrato_penalidade);
            $arrTransporte = [];
            foreach ($objetosComponentes as $objetoComponente){
                chmod($objetoComponente->getUrlAnexo(), 0777);
                $url_base = explode('/', $objetoComponente->getUrlAnexo());
                $url = $url_base[count($url_base)-5].'/'.$url_base[count($url_base)-4].'/'.$url_base[count($url_base)-3].'/'.$url_base[count($url_base)-2].'/'.$url_base[count($url_base)-1];
                $objTransporte = new \stdClass();
                $objTransporte->id_contrato_anexo = $objetoComponente->getId();
                $objTransporte->id_contrato = $objetoComponente->getIdContratoPenalidade();
                $objTransporte->id_anexo = $objetoComponente->getIdAnexo();
                $objTransporte->id_tipo_anexo = $objetoComponente->getIdTipoAnexo();
                $objTransporte->ds_tipo_anexo = $objetoComponente->getDescricaoTipoAnexo();
                $objTransporte->descricao = $objetoComponente->getDescricaoAnexo();
                $objTransporte->url = $url;
                $objTransporte->data_criacao = $util->converterDataHoraParaBr($objetoComponente->getDataCriacaoAnexo());
                array_push($arrTransporte, $objTransporte);
            }
            $response = new Response();
            $response->setContent(json_encode(array('operacao' => True,'dados' => $arrTransporte)));
            return ($visualizar) ? $arrTransporte : $response;
        } catch (TxFailed $e) {
            $logger->error($e->getMessage());
            return false;
        }
    }
}
