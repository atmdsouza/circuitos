<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoPenalidade extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $id_contrato;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var string
     */
    protected $numero_processo;

    /**
     *
     * @var string
     */
    protected $numero_notificacao;

    /**
     *
     * @var string
     */
    protected $numero_rt;

    /**
     *
     * @var string
     */
    protected $numero_oficio;

    /**
     *
     * @var string
     */
    protected $data_criacao;

    /**
     *
     * @var string
     */
    protected $data_recebimento_oficio_notificacao;

    /**
     *
     * @var string
     */
    protected $data_prazo_resposta;

    /**
     *
     * @var string
     */
    protected $data_apresentacao_defesa;

    /**
     *
     * @var string
     */
    protected $numero_oficio_multa;

    /**
     *
     * @var double
     */
    protected $valor_multa;

    /**
     *
     * @var string
     */
    protected $data_recebimento_oficio_multa;

    /**
     *
     * @var string
     */
    protected $parecer;

    /**
     *
     * @var string
     */
    protected $observacao;

    /**
     *
     * @var integer
     */
    protected $id_servico;

    /**
     *
     * @var integer
     */
    protected $ativo;

    /**
     *
     * @var integer
     */
    protected $excluido;

    /**
     *
     * @var string
     */
    protected $data_update;

    /**
     *
     * @var string
     */
    protected $motivo_penalidade;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field id_contrato
     *
     * @param integer $id_contrato
     * @return $this
     */
    public function setIdContrato($id_contrato)
    {
        $this->id_contrato = $id_contrato;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field numero_processo
     *
     * @param string $numero_processo
     * @return $this
     */
    public function setNumeroProcesso($numero_processo)
    {
        $this->numero_processo = $numero_processo;

        return $this;
    }

    /**
     * Method to set the value of field numero_notificacao
     *
     * @param string $numero_notificacao
     * @return $this
     */
    public function setNumeroNotificacao($numero_notificacao)
    {
        $this->numero_notificacao = $numero_notificacao;

        return $this;
    }

    /**
     * Method to set the value of field numero_rt
     *
     * @param string $numero_rt
     * @return $this
     */
    public function setNumeroRt($numero_rt)
    {
        $this->numero_rt = $numero_rt;

        return $this;
    }

    /**
     * Method to set the value of field numero_oficio
     *
     * @param string $numero_oficio
     * @return $this
     */
    public function setNumeroOficio($numero_oficio)
    {
        $this->numero_oficio = $numero_oficio;

        return $this;
    }

    /**
     * Method to set the value of field data_criacao
     *
     * @param string $data_criacao
     * @return $this
     */
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;

        return $this;
    }

    /**
     * Method to set the value of field data_recebimento_oficio_notificacao
     *
     * @param string $data_recebimento_oficio_notificacao
     * @return $this
     */
    public function setDataRecebimentoOficioNotificacao($data_recebimento_oficio_notificacao)
    {
        $this->data_recebimento_oficio_notificacao = $data_recebimento_oficio_notificacao;

        return $this;
    }

    /**
     * Method to set the value of field data_prazo_resposta
     *
     * @param string $data_prazo_resposta
     * @return $this
     */
    public function setDataPrazoResposta($data_prazo_resposta)
    {
        $this->data_prazo_resposta = $data_prazo_resposta;

        return $this;
    }

    /**
     * Method to set the value of field data_apresentacao_defesa
     *
     * @param string $data_apresentacao_defesa
     * @return $this
     */
    public function setDataApresentacaoDefesa($data_apresentacao_defesa)
    {
        $this->data_apresentacao_defesa = $data_apresentacao_defesa;

        return $this;
    }

    /**
     * Method to set the value of field numero_oficio_multa
     *
     * @param string $numero_oficio_multa
     * @return $this
     */
    public function setNumeroOficioMulta($numero_oficio_multa)
    {
        $this->numero_oficio_multa = $numero_oficio_multa;

        return $this;
    }

    /**
     * Method to set the value of field valor_multa
     *
     * @param double $valor_multa
     * @return $this
     */
    public function setValorMulta($valor_multa)
    {
        $this->valor_multa = $valor_multa;

        return $this;
    }

    /**
     * Method to set the value of field data_recebimento_oficio_multa
     *
     * @param string $data_recebimento_oficio_multa
     * @return $this
     */
    public function setDataRecebimentoOficioMulta($data_recebimento_oficio_multa)
    {
        $this->data_recebimento_oficio_multa = $data_recebimento_oficio_multa;

        return $this;
    }

    /**
     * Method to set the value of field parecer
     *
     * @param string $parecer
     * @return $this
     */
    public function setParecer($parecer)
    {
        $this->parecer = $parecer;

        return $this;
    }

    /**
     * Method to set the value of field observacao
     *
     * @param string $observacao
     * @return $this
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

        return $this;
    }

    /**
     * Method to set the value of field id_servico
     *
     * @param integer $id_servico
     * @return $this
     */
    public function setIdServico($id_servico)
    {
        $this->id_servico = $id_servico;

        return $this;
    }

    /**
     * Method to set the value of field ativo
     *
     * @param integer $ativo
     * @return $this
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Method to set the value of field excluido
     *
     * @param integer $excluido
     * @return $this
     */
    public function setExcluido($excluido)
    {
        $this->excluido = $excluido;

        return $this;
    }

    /**
     * Method to set the value of field data_update
     *
     * @param string $data_update
     * @return $this
     */
    public function setDataUpdate($data_update)
    {
        $this->data_update = $data_update;

        return $this;
    }

    /**
     * Method to set the value of field motivo_penalidade
     *
     * @param string $motivo_penalidade
     * @return $this
     */
    public function setMotivoPenalidade($motivo_penalidade)
    {
        $this->motivo_penalidade = $motivo_penalidade;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field id_contrato
     *
     * @return integer
     */
    public function getIdContrato()
    {
        return $this->id_contrato;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field numero_processo
     *
     * @return string
     */
    public function getNumeroProcesso()
    {
        return $this->numero_processo;
    }

    /**
     * Returns the value of field numero_notificacao
     *
     * @return string
     */
    public function getNumeroNotificacao()
    {
        return $this->numero_notificacao;
    }

    /**
     * Returns the value of field numero_rt
     *
     * @return string
     */
    public function getNumeroRt()
    {
        return $this->numero_rt;
    }

    /**
     * Returns the value of field numero_oficio
     *
     * @return string
     */
    public function getNumeroOficio()
    {
        return $this->numero_oficio;
    }

    /**
     * Returns the value of field data_criacao
     *
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    /**
     * Returns the value of field data_recebimento_oficio_notificacao
     *
     * @return string
     */
    public function getDataRecebimentoOficioNotificacao()
    {
        return $this->data_recebimento_oficio_notificacao;
    }

    /**
     * Returns the value of field data_prazo_resposta
     *
     * @return string
     */
    public function getDataPrazoResposta()
    {
        return $this->data_prazo_resposta;
    }

    /**
     * Returns the value of field data_apresentacao_defesa
     *
     * @return string
     */
    public function getDataApresentacaoDefesa()
    {
        return $this->data_apresentacao_defesa;
    }

    /**
     * Returns the value of field numero_oficio_multa
     *
     * @return string
     */
    public function getNumeroOficioMulta()
    {
        return $this->numero_oficio_multa;
    }

    /**
     * Returns the value of field valor_multa
     *
     * @return double
     */
    public function getValorMulta()
    {
        return $this->valor_multa;
    }

    /**
     * Returns the value of field data_recebimento_oficio_multa
     *
     * @return string
     */
    public function getDataRecebimentoOficioMulta()
    {
        return $this->data_recebimento_oficio_multa;
    }

    /**
     * Returns the value of field parecer
     *
     * @return string
     */
    public function getParecer()
    {
        return $this->parecer;
    }

    /**
     * Returns the value of field observacao
     *
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * Returns the value of field id_servico
     *
     * @return integer
     */
    public function getIdServico()
    {
        return $this->id_servico;
    }

    /**
     * Returns the value of field ativo
     *
     * @return integer
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Returns the value of field excluido
     *
     * @return integer
     */
    public function getExcluido()
    {
        return $this->excluido;
    }

    /**
     * Returns the value of field data_update
     *
     * @return string
     */
    public function getDataUpdate()
    {
        return $this->data_update;
    }

    /**
     * Returns the value of field motivo_penalidade
     *
     * @return string
     */
    public function getMotivoPenalidade()
    {
        return $this->motivo_penalidade;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_penalidade");
        $this->hasMany('id', 'Circuitos\Models\ContratoPenalidadeAnexo', 'id_contrato_penalidade', ['alias' => 'ContratoPenalidadeAnexo']);
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_servico', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_penalidade';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoPenalidade[]|ContratoPenalidade|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoPenalidade|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
