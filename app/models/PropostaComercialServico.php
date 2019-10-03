<?php

namespace Circuitos\Models;

class PropostaComercialServico extends \Phalcon\Mvc\Model
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
    protected $id_proposta_comercial_servico_grupo;

    /**
     *
     * @var integer
     */
    protected $id_proposta_comercial_servico_unidade;

    /**
     *
     * @var string
     */
    protected $codigo_legado;

    /**
     *
     * @var string
     */
    protected $codigo_contabil;

    /**
     *
     * @var string
     */
    protected $descricao;

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
     * Method to set the value of field id_proposta_comercial_servico_grupo
     *
     * @param integer $id_proposta_comercial_servico_grupo
     * @return $this
     */
    public function setIdPropostaComercialServicoGrupo($id_proposta_comercial_servico_grupo)
    {
        $this->id_proposta_comercial_servico_grupo = $id_proposta_comercial_servico_grupo;

        return $this;
    }

    /**
     * Method to set the value of field id_proposta_comercial_servico_unidade
     *
     * @param integer $id_proposta_comercial_servico_unidade
     * @return $this
     */
    public function setIdPropostaComercialServicoUnidade($id_proposta_comercial_servico_unidade)
    {
        $this->id_proposta_comercial_servico_unidade = $id_proposta_comercial_servico_unidade;

        return $this;
    }

    /**
     * Method to set the value of field codigo_legado
     *
     * @param string $codigo_legado
     * @return $this
     */
    public function setCodigoLegado($codigo_legado)
    {
        $this->codigo_legado = $codigo_legado;

        return $this;
    }

    /**
     * Method to set the value of field codigo_contabil
     *
     * @param string $codigo_contabil
     * @return $this
     */
    public function setCodigoContabil($codigo_contabil)
    {
        $this->codigo_contabil = $codigo_contabil;

        return $this;
    }

    /**
     * Method to set the value of field descricao
     *
     * @param string $descricao
     * @return $this
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field id_proposta_comercial_servico_grupo
     *
     * @return integer
     */
    public function getIdPropostaComercialServicoGrupo()
    {
        return $this->id_proposta_comercial_servico_grupo;
    }

    /**
     * Returns the value of field id_proposta_comercial_servico_unidade
     *
     * @return integer
     */
    public function getIdPropostaComercialServicoUnidade()
    {
        return $this->id_proposta_comercial_servico_unidade;
    }

    /**
     * Returns the value of field codigo_legado
     *
     * @return string
     */
    public function getCodigoLegado()
    {
        return $this->codigo_legado;
    }

    /**
     * Returns the value of field codigo_contabil
     *
     * @return string
     */
    public function getCodigoContabil()
    {
        return $this->codigo_contabil;
    }

    /**
     * Returns the value of field descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("proposta_comercial_servico");
        $this->hasMany('id', 'Circuitos\Models\PropostaComercialItem', 'id_proposta_comercial_servicos', ['alias' => 'PropostaComercialItem']);
        $this->belongsTo('id_proposta_comercial_servico_unidade', 'Circuitos\Models\PropostaComercialServicoUnidade', 'id', ['alias' => 'PropostaComercialServicoUnidade']);
        $this->belongsTo('id_proposta_comercial_servico_grupo', 'Circuitos\Models\PropostaComercialServicoGrupo', 'id', ['alias' => 'PropostaComercialServicoGrupo']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'proposta_comercial_servico';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialServico[]|PropostaComercialServico|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialServico|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
