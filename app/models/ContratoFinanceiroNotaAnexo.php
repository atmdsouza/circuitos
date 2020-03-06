<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoFinanceiroNotaAnexo extends \Phalcon\Mvc\Model
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
    protected $id_contrato_financeiro_nota;

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
     * Method to set the value of field id_contrato_financeiro_nota
     *
     * @param integer $id_contrato_financeiro_nota
     * @return $this
     */
    public function setIdContratoFinanceiroNota($id_contrato_financeiro_nota)
    {
        $this->id_contrato_financeiro_nota = $id_contrato_financeiro_nota;

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
     * Returns the value of field id_contrato_financeiro_nota
     *
     * @return integer
     */
    public function getIdContratoFinanceiroNota()
    {
        return $this->id_contrato_financeiro_nota;
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
        $this->setSource("contrato_financeiro_nota_anexo");
        $this->belongsTo('id_anexo', 'Circuitos\Models\Anexos', 'id', ['alias' => 'Anexos']);
        $this->belongsTo('id_contrato_financeiro_nota', 'Circuitos\Models\ContratoFinanceiroNota', 'id', ['alias' => 'ContratoFinanceiroNota']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_financeiro_nota_anexo';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFinanceiroNotaAnexo[]|ContratoFinanceiroNotaAnexo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFinanceiroNotaAnexo|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
