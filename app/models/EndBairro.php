<?php

namespace Circuitos\Models;

class EndBairro extends \Phalcon\Mvc\Model
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
    protected $id_bairro;

    /**
     *
     * @var string
     */
    protected $bairro;

    /**
     *
     * @var integer
     */
    protected $id_cidade;

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
     * Method to set the value of field bairro
     *
     * @param string $bairro
     * @return $this
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Returns the value of field bairro
     *
     * @return string
     */
    public function getBairro()
    {
        return $this->bairro;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("end_bairro");
        $this->hasMany('id_bairro', 'Circuitos\Models\EndFaixaBairros', 'id_bairro', ['alias' => 'EndFaixaBairros']);
        $this->belongsTo('id_cidade', 'Circuitos\Models\EndCidade', 'id_cidade', ['alias' => 'EndCidade']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'end_bairro';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndBairro[]|EndBairro|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndBairro|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
