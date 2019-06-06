<?php

namespace Circuitos\Models;

class ContratoExercicio extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    protected $exercicio;

    /**
     *
     * @var double
     */
    protected $valor_previsto;

    /**
     *
     * @var string
     */
    protected $data_parcela;

    /**
     *
     * @var double
     */
    protected $valor_parcela;

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
     * Method to set the value of field exercicio
     *
     * @param integer $exercicio
     * @return $this
     */
    public function setExercicio($exercicio)
    {
        $this->exercicio = $exercicio;

        return $this;
    }

    /**
     * Method to set the value of field valor_previsto
     *
     * @param double $valor_previsto
     * @return $this
     */
    public function setValorPrevisto($valor_previsto)
    {
        $this->valor_previsto = $valor_previsto;

        return $this;
    }

    /**
     * Method to set the value of field data_parcela
     *
     * @param string $data_parcela
     * @return $this
     */
    public function setDataParcela($data_parcela)
    {
        $this->data_parcela = $data_parcela;

        return $this;
    }

    /**
     * Method to set the value of field valor_parcela
     *
     * @param double $valor_parcela
     * @return $this
     */
    public function setValorParcela($valor_parcela)
    {
        $this->valor_parcela = $valor_parcela;

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
     * Returns the value of field exercicio
     *
     * @return integer
     */
    public function getExercicio()
    {
        return $this->exercicio;
    }

    /**
     * Returns the value of field valor_previsto
     *
     * @return double
     */
    public function getValorPrevisto()
    {
        return $this->valor_previsto;
    }

    /**
     * Returns the value of field data_parcela
     *
     * @return string
     */
    public function getDataParcela()
    {
        return $this->data_parcela;
    }

    /**
     * Returns the value of field valor_parcela
     *
     * @return double
     */
    public function getValorParcela()
    {
        return $this->valor_parcela;
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
        $this->setSource("contrato_exercicio");
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_exercicio';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoExercicio[]|ContratoExercicio|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoExercicio|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
