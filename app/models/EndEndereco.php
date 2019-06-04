<?php

namespace Circuitos\Models;

class EndEndereco extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $cep;

    /**
     *
     * @var string
     */
    protected $logradouro;

    /**
     *
     * @var string
     */
    protected $tipo_logradouro;

    /**
     *
     * @var string
     */
    protected $complemento;

    /**
     *
     * @var string
     */
    protected $local;

    /**
     *
     * @var integer
     */
    protected $id_cidade;

    /**
     *
     * @var integer
     */
    protected $id_bairro;

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
     * Method to set the value of field cep
     *
     * @param string $cep
     * @return $this
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Method to set the value of field logradouro
     *
     * @param string $logradouro
     * @return $this
     */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;

        return $this;
    }

    /**
     * Method to set the value of field tipo_logradouro
     *
     * @param string $tipo_logradouro
     * @return $this
     */
    public function setTipoLogradouro($tipo_logradouro)
    {
        $this->tipo_logradouro = $tipo_logradouro;

        return $this;
    }

    /**
     * Method to set the value of field complemento
     *
     * @param string $complemento
     * @return $this
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;

        return $this;
    }

    /**
     * Method to set the value of field local
     *
     * @param string $local
     * @return $this
     */
    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * Method to set the value of field id_cidade
     *
     * @param integer $id_cidade
     * @return $this
     */
    public function setIdCidade($id_cidade)
    {
        $this->id_cidade = $id_cidade;

        return $this;
    }

    /**
     * Method to set the value of field id_bairro
     *
     * @param integer $id_bairro
     * @return $this
     */
    public function setIdBairro($id_bairro)
    {
        $this->id_bairro = $id_bairro;

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
     * Returns the value of field cep
     *
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Returns the value of field logradouro
     *
     * @return string
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * Returns the value of field tipo_logradouro
     *
     * @return string
     */
    public function getTipoLogradouro()
    {
        return $this->tipo_logradouro;
    }

    /**
     * Returns the value of field complemento
     *
     * @return string
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Returns the value of field local
     *
     * @return string
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Returns the value of field id_cidade
     *
     * @return integer
     */
    public function getIdCidade()
    {
        return $this->id_cidade;
    }

    /**
     * Returns the value of field id_bairro
     *
     * @return integer
     */
    public function getIdBairro()
    {
        return $this->id_bairro;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("end_endereco");
        $this->belongsTo("id_cidade", "Circuitos\Models\EndCidade", "id_cidade", ["alias" => "EndCidade"]);
        $this->belongsTo("id_bairro", "Circuitos\Models\EndBairro", "id_bairro", ["alias" => "EndBairro"]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return "end_endereco";
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndEndereco[]|EndEndereco|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndEndereco|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
