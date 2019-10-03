<?php

namespace Circuitos\Models;

class ContratoProcesso extends \Phalcon\Mvc\Model
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
    protected $id_tipo_processo;

    /**
     *
     * @var integer
     */
    protected $id_proposta_comercial;

    /**
     *
     * @var string
     */
    protected $codigo_processo;

    /**
     *
     * @var string
     */
    protected $numero_processo;

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
     * Method to set the value of field id_tipo_processo
     *
     * @param integer $id_tipo_processo
     * @return $this
     */
    public function setIdTipoProcesso($id_tipo_processo)
    {
        $this->id_tipo_processo = $id_tipo_processo;

        return $this;
    }

    /**
     * Method to set the value of field id_proposta_comercial
     *
     * @param integer $id_proposta_comercial
     * @return $this
     */
    public function setIdPropostaComercial($id_proposta_comercial)
    {
        $this->id_proposta_comercial = $id_proposta_comercial;

        return $this;
    }

    /**
     * Method to set the value of field codigo_processo
     *
     * @param string $codigo_processo
     * @return $this
     */
    public function setCodigoProcesso($codigo_processo)
    {
        $this->codigo_processo = $codigo_processo;

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
     * Returns the value of field id_tipo_processo
     *
     * @return integer
     */
    public function getIdTipoProcesso()
    {
        return $this->id_tipo_processo;
    }

    /**
     * Returns the value of field id_proposta_comercial
     *
     * @return integer
     */
    public function getIdPropostaComercial()
    {
        return $this->id_proposta_comercial;
    }

    /**
     * Returns the value of field codigo_processo
     *
     * @return string
     */
    public function getCodigoProcesso()
    {
        return $this->codigo_processo;
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
        $this->setSource("contrato_processo");
        $this->hasMany('id', 'Circuitos\Models\Contrato', 'id_processo_contratacao', ['alias' => 'Contrato']);
        $this->belongsTo('id_proposta_comercial', 'Circuitos\Models\PropostaComercial', 'id', ['alias' => 'PropostaComercial']);
        $this->belongsTo('id_tipo_processo', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_processo';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoProcesso[]|ContratoProcesso|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoProcesso|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
