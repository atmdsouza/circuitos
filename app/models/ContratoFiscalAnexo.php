<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoFiscalAnexo extends \Phalcon\Mvc\Model
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
    protected $id_anexo;

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
     * Method to set the value of field id_anexo
     *
     * @param integer $id_anexo
     * @return $this
     */
    public function setIdAnexo($id_anexo)
    {
        $this->id_anexo = $id_anexo;

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
     * Returns the value of field id_contrato_fiscal
     *
     * @return integer
     */
    public function getIdContratoFiscal()
    {
        return $this->id_contrato_fiscal;
    }

    /**
     * Returns the value of field id_anexo
     *
     * @return integer
     */
    public function getIdAnexo()
    {
        return $this->id_anexo;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_fiscal_anexo");
        $this->belongsTo('id_anexo', 'Circuitos\Models\Anexos', 'id', ['alias' => 'Anexos']);
        $this->belongsTo('id_contrato_fiscal', 'Circuitos\Models\ContratoFiscal', 'id', ['alias' => 'ContratoFiscal']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_fiscal_anexo';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFiscalAnexo[]|ContratoFiscalAnexo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFiscalAnexo|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}