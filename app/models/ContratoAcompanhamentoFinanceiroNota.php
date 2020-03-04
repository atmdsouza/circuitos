<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoAcompanhamentoFinanceiroNota extends \Phalcon\Mvc\Model
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
    protected $id_contrato_acompanhamento_financeiro;

    /**
     *
     * @var string
     */
    protected $numero_nota_fiscal;

    /**
     *
     * @var string
     */
    protected $data_pagamento;

    /**
     *
     * @var double
     */
    protected $valor;

    /**
     *
     * @var string
     */
    protected $observacao;

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
     * Method to set the value of field id_contrato_acompanhamento_financeiro
     *
     * @param integer $id_contrato_acompanhamento_financeiro
     * @return $this
     */
    public function setIdContratoAcompanhamentoFinanceiro($id_contrato_acompanhamento_financeiro)
    {
        $this->id_contrato_acompanhamento_financeiro = $id_contrato_acompanhamento_financeiro;

        return $this;
    }

    /**
     * Method to set the value of field numero_nota_fiscal
     *
     * @param string $numero_nota_fiscal
     * @return $this
     */
    public function setNumeroNotaFiscal($numero_nota_fiscal)
    {
        $this->numero_nota_fiscal = $numero_nota_fiscal;

        return $this;
    }

    /**
     * Method to set the value of field data_pagamento
     *
     * @param string $data_pagamento
     * @return $this
     */
    public function setDataPagamento($data_pagamento)
    {
        $this->data_pagamento = $data_pagamento;

        return $this;
    }

    /**
     * Method to set the value of field valor
     *
     * @param double $valor
     * @return $this
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Method to set the value of field observacao
     *
     * @param string $observacao
     * @return $this
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

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
     * Returns the value of field id_contrato_acompanhamento_financeiro
     *
     * @return integer
     */
    public function getIdContratoAcompanhamentoFinanceiro()
    {
        return $this->id_contrato_acompanhamento_financeiro;
    }

    /**
     * Returns the value of field numero_nota_fiscal
     *
     * @return string
     */
    public function getNumeroNotaFiscal()
    {
        return $this->numero_nota_fiscal;
    }

    /**
     * Returns the value of field data_pagamento
     *
     * @return string
     */
    public function getDataPagamento()
    {
        return $this->data_pagamento;
    }

    /**
     * Returns the value of field valor
     *
     * @return double
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Returns the value of field observacao
     *
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_acompanhamento_financeiro_nota");
        $this->hasMany('id', 'Circuitos\Models\ContratoAcompanhamentoFinanceiroNotaAnexo', 'id_contrato_acompanhamento_financeiro_nota', ['alias' => 'ContratoAcompanhamentoFinanceiroNotaAnexo']);
        $this->belongsTo('id_contrato_acompanhamento_financeiro', 'Circuitos\Models\ContratoAcompanhamentoFinanceiro', 'id', ['alias' => 'ContratoAcompanhamentoFinanceiro']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_acompanhamento_financeiro_nota';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoAcompanhamentoFinanceiroNota[]|ContratoAcompanhamentoFinanceiroNota|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoAcompanhamentoFinanceiroNota|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
