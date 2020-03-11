<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoFiscalHasContrato extends \Phalcon\Mvc\Model
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
    protected $id_contrato_fiscal;

    /**
     *
     * @var integer
     */
    protected $id_contrato;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field id_contrato_fiscal
     *
     * @param integer $id_contrato_fiscal
     * @return $this
     */
    public function setIdContratoFiscal($id_contrato_fiscal)
    {
        $this->id_contrato_fiscal = $id_contrato_fiscal;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field id_contrato_fiscal
     *
     * @return integer
     */
    public function getIdContratoFiscal()
    {
        return $this->id_contrato_fiscal;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_fiscal_has_contrato");
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_contrato_fiscal', 'Circuitos\Models\ContratoFiscal', 'id', ['alias' => 'ContratoFiscal']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_fiscal_has_contrato';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFiscalHasContrato[]|ContratoFiscalHasContrato|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFiscalHasContrato|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
