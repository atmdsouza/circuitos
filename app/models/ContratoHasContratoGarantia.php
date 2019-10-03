<?php

namespace Circuitos\Models;

class ContratoHasContratoGarantia extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id_contrato;

    /**
     *
     * @var integer
     */
    protected $id_contrato_garantia;

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
     * Method to set the value of field id_contrato_garantia
     *
     * @param integer $id_contrato_garantia
     * @return $this
     */
    public function setIdContratoGarantia($id_contrato_garantia)
    {
        $this->id_contrato_garantia = $id_contrato_garantia;

        return $this;
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
     * Returns the value of field id_contrato_garantia
     *
     * @return integer
     */
    public function getIdContratoGarantia()
    {
        return $this->id_contrato_garantia;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("contrato_has_contrato_garantia");
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_contrato_garantia', 'Circuitos\Models\ContratoGarantia', 'id', ['alias' => 'ContratoGarantia']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_has_contrato_garantia';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoHasContratoGarantia[]|ContratoHasContratoGarantia|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoHasContratoGarantia|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
