<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoFinanceiroNota extends \Phalcon\Mvc\Model
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
    protected $id_contrato_financeiro;

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
    protected $valor_nota;

    /**
     *
     * @var string
     */
    protected $observacao;

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
     * Method to set the value of field id_contrato_financeiro
     *
     * @param integer $id_contrato_financeiro
     * @return $this
     */
    public function setIdContratoFinanceiro($id_contrato_financeiro)
    {
        $this->id_contrato_financeiro = $id_contrato_financeiro;

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
     * Method to set the value of field valor_nota
     *
     * @param double $valor_nota
     * @return $this
     */
    public function setValorNota($valor_nota)
    {
        $this->valor_nota = $valor_nota;

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
     * Returns the value of field id_contrato_financeiro
     *
     * @return integer
     */
    public function getIdContratoFinanceiro()
    {
        return $this->id_contrato_financeiro;
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
     * Returns the value of field valor_nota
     *
     * @return double
     */
    public function getValorNota()
    {
        return $this->valor_nota;
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
     * @return int
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param int $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * @return int
     */
    public function getExcluido()
    {
        return $this->excluido;
    }

    /**
     * @param int $excluido
     */
    public function setExcluido($excluido)
    {
        $this->excluido = $excluido;

        return $this;
    }

    /**
     * @return string
     */
    public function getDataUpdate()
    {
        return $this->data_update;
    }

    /**
     * @param string $data_update
     */
    public function setDataUpdate($data_update)
    {
        $this->data_update = $data_update;

        return $this;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_financeiro_nota");
        $this->hasMany('id', 'Circuitos\Models\ContratoFinanceiroNotaAnexo', 'id_contrato_financeiro_nota', ['alias' => 'ContratoFinanceiroNotaAnexo']);
        $this->belongsTo('id_contrato_financeiro', 'Circuitos\Models\ContratoFinanceiro', 'id', ['alias' => 'ContratoFinanceiro']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_financeiro_nota';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFinanceiroNota[]|ContratoFinanceiroNota|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFinanceiroNota|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
