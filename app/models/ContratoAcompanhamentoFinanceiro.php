<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoAcompanhamentoFinanceiro extends \Phalcon\Mvc\Model
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
    protected $id_exercicio;

    /**
     *
     * @var integer
     */
    protected $mes_competencia;

    /**
     *
     * @var integer
     */
    protected $status_pagamento;

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
     * Method to set the value of field id_exercicio
     *
     * @param integer $id_exercicio
     * @return $this
     */
    public function setIdExercicio($id_exercicio)
    {
        $this->id_exercicio = $id_exercicio;

        return $this;
    }

    /**
     * Method to set the value of field mes_competencia
     *
     * @param integer $mes_competencia
     * @return $this
     */
    public function setMesCompetencia($mes_competencia)
    {
        $this->mes_competencia = $mes_competencia;

        return $this;
    }

    /**
     * Method to set the value of field status_pagamento
     *
     * @param integer $status_pagamento
     * @return $this
     */
    public function setStatusPagamento($status_pagamento)
    {
        $this->status_pagamento = $status_pagamento;

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
     * Returns the value of field id_exercicio
     *
     * @return integer
     */
    public function getIdExercicio()
    {
        return $this->id_exercicio;
    }

    /**
     * Returns the value of field mes_competencia
     *
     * @return integer
     */
    public function getMesCompetencia()
    {
        return $this->mes_competencia;
    }

    /**
     * Returns the value of field status_pagamento
     *
     * @return integer
     */
    public function getStatusPagamento()
    {
        return $this->status_pagamento;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_acompanhamento_financeiro");
        $this->hasMany('id', 'Circuitos\Models\ContratoAcompanhamentoFinanceiroNota', 'id_contrato_acompanhamento_financeiro', ['alias' => 'ContratoAcompanhamentoFinanceiroNota']);
        $this->belongsTo('id_exercicio', 'Circuitos\Models\ContratoExercicio', 'id', ['alias' => 'ContratoExercicio']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_acompanhamento_financeiro';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoAcompanhamentoFinanceiro[]|ContratoAcompanhamentoFinanceiro|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoAcompanhamentoFinanceiro|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
