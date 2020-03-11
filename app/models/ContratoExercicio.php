<?php

namespace Circuitos\Models;

use Util\Infra;
use Util\Util;

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
     *
     * @var string
     */
    protected $competencia_inicial;

    /**
     *
     * @var string
     */
    protected $competencia_final;

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
     * Method to set the value of field competencia_inicial
     *
     * @param string $competencia_inicial
     * @return $this
     */
    public function setCompetenciaInicial($competencia_inicial)
    {
        $this->competencia_inicial = $competencia_inicial;

        return $this;
    }

    /**
     * Method to set the value of field competencia_final
     *
     * @param string $competencia_final
     * @return $this
     */
    public function setCompetenciaFinal($competencia_final)
    {
        $this->competencia_final = $competencia_final;

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
     * Returns the value of field competencia_inicial
     *
     * @return string
     */
    public function getCompetenciaInicial()
    {
        return $this->competencia_inicial;
    }

    /**
     * Returns the value of field competencia_final
     *
     * @return string
     */
    public function getCompetenciaFinal()
    {
        return $this->competencia_final;
    }

    /**
     * Returns the value of field valor_previsto
     *
     * @return double
     */
    public function getValorPrevistoFormatado()
    {
        $util = new Util();
        return $util->formataMoedaReal($this->valor_previsto);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_exercicio");
        $this->hasMany('id', 'Circuitos\Models\ContratoFinanceiro', 'id_exercicio', ['alias' => 'ContratoFinanceiro']);
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
