<?php

namespace Circuitos\Models;

class ContratoOrcamento extends \Phalcon\Mvc\Model
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
    protected $id_contrato;

    /**
     *
     * @var string
     */
    protected $funcional_programatica;

    /**
     *
     * @var string
     */
    protected $fonte_orcamentaria;

    /**
     *
     * @var string
     */
    protected $programa_trabalho;

    /**
     *
     * @var string
     */
    protected $elemento_despesa;

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
     * Method to set the value of field funcional_programatica
     *
     * @param string $funcional_programatica
     * @return $this
     */
    public function setFuncionalProgramatica($funcional_programatica)
    {
        $this->funcional_programatica = $funcional_programatica;

        return $this;
    }

    /**
     * Method to set the value of field fonte_orcamentaria
     *
     * @param string $fonte_orcamentaria
     * @return $this
     */
    public function setFonteOrcamentaria($fonte_orcamentaria)
    {
        $this->fonte_orcamentaria = $fonte_orcamentaria;

        return $this;
    }

    /**
     * Method to set the value of field programa_trabalho
     *
     * @param string $programa_trabalho
     * @return $this
     */
    public function setProgramaTrabalho($programa_trabalho)
    {
        $this->programa_trabalho = $programa_trabalho;

        return $this;
    }

    /**
     * Method to set the value of field elemento_despesa
     *
     * @param string $elemento_despesa
     * @return $this
     */
    public function setElementoDespesa($elemento_despesa)
    {
        $this->elemento_despesa = $elemento_despesa;

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
     * Returns the value of field id_contrato
     *
     * @return integer
     */
    public function getIdContrato()
    {
        return $this->id_contrato;
    }

    /**
     * Returns the value of field funcional_programatica
     *
     * @return string
     */
    public function getFuncionalProgramatica()
    {
        return $this->funcional_programatica;
    }

    /**
     * Returns the value of field fonte_orcamentaria
     *
     * @return string
     */
    public function getFonteOrcamentaria()
    {
        return $this->fonte_orcamentaria;
    }

    /**
     * Returns the value of field programa_trabalho
     *
     * @return string
     */
    public function getProgramaTrabalho()
    {
        return $this->programa_trabalho;
    }

    /**
     * Returns the value of field elemento_despesa
     *
     * @return string
     */
    public function getElementoDespesa()
    {
        return $this->elemento_despesa;
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
        $this->setSource("contrato_orcamento");
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_orcamento';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoOrcamento[]|ContratoOrcamento|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoOrcamento|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
