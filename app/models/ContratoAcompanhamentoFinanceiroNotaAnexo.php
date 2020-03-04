<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoAcompanhamentoFinanceiroNotaAnexo extends \Phalcon\Mvc\Model
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
    protected $id_contrato_acompanhamento_financeiro_nota;

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
     * Method to set the value of field id_contrato_acompanhamento_financeiro_nota
     *
     * @param integer $id_contrato_acompanhamento_financeiro_nota
     * @return $this
     */
    public function setIdContratoAcompanhamentoFinanceiroNota($id_contrato_acompanhamento_financeiro_nota)
    {
        $this->id_contrato_acompanhamento_financeiro_nota = $id_contrato_acompanhamento_financeiro_nota;

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
     * Returns the value of field id_contrato_acompanhamento_financeiro_nota
     *
     * @return integer
     */
    public function getIdContratoAcompanhamentoFinanceiroNota()
    {
        return $this->id_contrato_acompanhamento_financeiro_nota;
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
        $this->setSource("contrato_acompanhamento_financeiro_nota_anexo");
        $this->belongsTo('id_anexo', 'Circuitos\Models\Anexos', 'id', ['alias' => 'Anexos']);
        $this->belongsTo('id_contrato_acompanhamento_financeiro_nota', 'Circuitos\Models\ContratoAcompanhamentoFinanceiroNota', 'id', ['alias' => 'ContratoAcompanhamentoFinanceiroNota']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_acompanhamento_financeiro_nota_anexo';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoAcompanhamentoFinanceiroNotaAnexo[]|ContratoAcompanhamentoFinanceiroNotaAnexo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoAcompanhamentoFinanceiroNotaAnexo|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
