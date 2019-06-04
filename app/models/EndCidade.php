<?php

namespace Circuitos\Models;

class EndCidade extends \Phalcon\Mvc\Model
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
    protected $id_cidade;

    /**
     *
     * @var string
     */
    protected $cidade;

    /**
     *
     * @var string
     */
    protected $uf;

    /**
     *
     * @var string
     */
    protected $cod_ibge;

    /**
     *
     * @var string
     */
    protected $area;

    /**
     *
     * @var string
     */
    protected $id_municipio_subordinado;

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
     * Method to set the value of field cidade
     *
     * @param string $cidade
     * @return $this
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    /**
     * Method to set the value of field uf
     *
     * @param string $uf
     * @return $this
     */
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Method to set the value of field cod_ibge
     *
     * @param string $cod_ibge
     * @return $this
     */
    public function setCodIbge($cod_ibge)
    {
        $this->cod_ibge = $cod_ibge;

        return $this;
    }

    /**
     * Method to set the value of field area
     *
     * @param string $area
     * @return $this
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Method to set the value of field id_municipio_subordinado
     *
     * @param string $id_municipio_subordinado
     * @return $this
     */
    public function setIdMunicipioSubordinado($id_municipio_subordinado)
    {
        $this->id_municipio_subordinado = $id_municipio_subordinado;

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
     * Returns the value of field id_cidade
     *
     * @return integer
     */
    public function getIdCidade()
    {
        return $this->id_cidade;
    }

    /**
     * Returns the value of field cidade
     *
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * Returns the value of field uf
     *
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * Returns the value of field cod_ibge
     *
     * @return string
     */
    public function getCodIbge()
    {
        return $this->cod_ibge;
    }

    /**
     * Returns the value of field area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Returns the value of field id_municipio_subordinado
     *
     * @return string
     */
    public function getIdMunicipioSubordinado()
    {
        return $this->id_municipio_subordinado;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("end_cidade");
        $this->hasMany('id_cidade', 'Circuitos\Models\EndBairro', 'id_cidade', ['alias' => 'EndBairro']);
        $this->hasMany('id_cidade', 'Circuitos\Models\EndEndereco', 'id_cidade', ['alias' => 'EndEndereco']);
        $this->hasMany('id_cidade', 'Circuitos\Models\EndFaixaBairros', 'id_cidade', ['alias' => 'EndFaixaBairros']);
        $this->hasMany('id_cidade', 'Circuitos\Models\EndFaixaCidades', 'id_cidade', ['alias' => 'EndFaixaCidades']);
        $this->belongsTo('uf', 'Circuitos\Models\EndEstado', 'uf', ['alias' => 'EndEstado']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'end_cidade';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndCidade[]|EndCidade|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndCidade|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
