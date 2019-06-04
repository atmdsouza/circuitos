<?php

namespace Circuitos\Models;

class EndFaixaBairros extends \Phalcon\Mvc\Model
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
    protected $cep_inicial;

    /**
     *
     * @var string
     */
    protected $cep_final;

    /**
     *
     * @var integer
     */
    protected $id_bairro;

    /**
     *
     * @var integer
     */
    protected $id_cidade;

    /**
     *
     * @var string
     */
    protected $uf;

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
     * Method to set the value of field cep_inicial
     *
     * @param string $cep_inicial
     * @return $this
     */
    public function setCepInicial($cep_inicial)
    {
        $this->cep_inicial = $cep_inicial;

        return $this;
    }

    /**
     * Method to set the value of field cep_final
     *
     * @param string $cep_final
     * @return $this
     */
    public function setCepFinal($cep_final)
    {
        $this->cep_final = $cep_final;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field cep_inicial
     *
     * @return string
     */
    public function getCepInicial()
    {
        return $this->cep_inicial;
    }

    /**
     * Returns the value of field cep_final
     *
     * @return string
     */
    public function getCepFinal()
    {
        return $this->cep_final;
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
     * Returns the value of field id_cidade
     *
     * @return integer
     */
    public function getIdCidade()
    {
        return $this->id_cidade;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("end_faixa_bairros");
        $this->belongsTo('id_cidade', 'Circuitos\Models\EndCidade', 'id_cidade', ['alias' => 'EndCidade']);
        $this->belongsTo('id_bairro', 'Circuitos\Models\EndBairro', 'id_bairro', ['alias' => 'EndBairro']);
        $this->belongsTo('uf', 'Circuitos\Models\EndEstado', 'uf', ['alias' => 'EndEstado']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'end_faixa_bairros';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndFaixaBairros[]|EndFaixaBairros|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndFaixaBairros|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
