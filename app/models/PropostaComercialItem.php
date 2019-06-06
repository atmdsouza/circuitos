<?php

namespace Circuitos\Models;

class PropostaComercialItem extends \Phalcon\Mvc\Model
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
    protected $id_proposta_comercial;

    /**
     *
     * @var integer
     */
    protected $id_proposta_comercial_servicos;

    /**
     *
     * @var integer
     */
    protected $imposto;

    /**
     *
     * @var integer
     */
    protected $reajuste;

    /**
     *
     * @var integer
     */
    protected $quantidade;

    /**
     *
     * @var integer
     */
    protected $mes_inicial;

    /**
     *
     * @var integer
     */
    protected $vigencia;

    /**
     *
     * @var double
     */
    protected $valor_unitario;

    /**
     *
     * @var double
     */
    protected $valor_total;

    /**
     *
     * @var double
     */
    protected $valor_total_reajuste;

    /**
     *
     * @var double
     */
    protected $valor_impostos;

    /**
     *
     * @var double
     */
    protected $valor_total_impostos;

    /**
     *
     * @var string
     */
    protected $data_update;

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
     * Method to set the value of field id_proposta_comercial
     *
     * @param integer $id_proposta_comercial
     * @return $this
     */
    public function setIdPropostaComercial($id_proposta_comercial)
    {
        $this->id_proposta_comercial = $id_proposta_comercial;

        return $this;
    }

    /**
     * Method to set the value of field id_proposta_comercial_servicos
     *
     * @param integer $id_proposta_comercial_servicos
     * @return $this
     */
    public function setIdPropostaComercialServicos($id_proposta_comercial_servicos)
    {
        $this->id_proposta_comercial_servicos = $id_proposta_comercial_servicos;

        return $this;
    }

    /**
     * Method to set the value of field imposto
     *
     * @param integer $imposto
     * @return $this
     */
    public function setImposto($imposto)
    {
        $this->imposto = $imposto;

        return $this;
    }

    /**
     * Method to set the value of field reajuste
     *
     * @param integer $reajuste
     * @return $this
     */
    public function setReajuste($reajuste)
    {
        $this->reajuste = $reajuste;

        return $this;
    }

    /**
     * Method to set the value of field quantidade
     *
     * @param integer $quantidade
     * @return $this
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Method to set the value of field mes_inicial
     *
     * @param integer $mes_inicial
     * @return $this
     */
    public function setMesInicial($mes_inicial)
    {
        $this->mes_inicial = $mes_inicial;

        return $this;
    }

    /**
     * Method to set the value of field vigencia
     *
     * @param integer $vigencia
     * @return $this
     */
    public function setVigencia($vigencia)
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    /**
     * Method to set the value of field valor_unitario
     *
     * @param double $valor_unitario
     * @return $this
     */
    public function setValorUnitario($valor_unitario)
    {
        $this->valor_unitario = $valor_unitario;

        return $this;
    }

    /**
     * Method to set the value of field valor_total
     *
     * @param double $valor_total
     * @return $this
     */
    public function setValorTotal($valor_total)
    {
        $this->valor_total = $valor_total;

        return $this;
    }

    /**
     * Method to set the value of field valor_total_reajuste
     *
     * @param double $valor_total_reajuste
     * @return $this
     */
    public function setValorTotalReajuste($valor_total_reajuste)
    {
        $this->valor_total_reajuste = $valor_total_reajuste;

        return $this;
    }

    /**
     * Method to set the value of field valor_impostos
     *
     * @param double $valor_impostos
     * @return $this
     */
    public function setValorImpostos($valor_impostos)
    {
        $this->valor_impostos = $valor_impostos;

        return $this;
    }

    /**
     * Method to set the value of field valor_total_impostos
     *
     * @param double $valor_total_impostos
     * @return $this
     */
    public function setValorTotalImpostos($valor_total_impostos)
    {
        $this->valor_total_impostos = $valor_total_impostos;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field id_proposta_comercial
     *
     * @return integer
     */
    public function getIdPropostaComercial()
    {
        return $this->id_proposta_comercial;
    }

    /**
     * Returns the value of field id_proposta_comercial_servicos
     *
     * @return integer
     */
    public function getIdPropostaComercialServicos()
    {
        return $this->id_proposta_comercial_servicos;
    }

    /**
     * Returns the value of field imposto
     *
     * @return integer
     */
    public function getImposto()
    {
        return $this->imposto;
    }

    /**
     * Returns the value of field reajuste
     *
     * @return integer
     */
    public function getReajuste()
    {
        return $this->reajuste;
    }

    /**
     * Returns the value of field quantidade
     *
     * @return integer
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Returns the value of field mes_inicial
     *
     * @return integer
     */
    public function getMesInicial()
    {
        return $this->mes_inicial;
    }

    /**
     * Returns the value of field vigencia
     *
     * @return integer
     */
    public function getVigencia()
    {
        return $this->vigencia;
    }

    /**
     * Returns the value of field valor_unitario
     *
     * @return double
     */
    public function getValorUnitario()
    {
        return $this->valor_unitario;
    }

    /**
     * Returns the value of field valor_total
     *
     * @return double
     */
    public function getValorTotal()
    {
        return $this->valor_total;
    }

    /**
     * Returns the value of field valor_total_reajuste
     *
     * @return double
     */
    public function getValorTotalReajuste()
    {
        return $this->valor_total_reajuste;
    }

    /**
     * Returns the value of field valor_impostos
     *
     * @return double
     */
    public function getValorImpostos()
    {
        return $this->valor_impostos;
    }

    /**
     * Returns the value of field valor_total_impostos
     *
     * @return double
     */
    public function getValorTotalImpostos()
    {
        return $this->valor_total_impostos;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("proposta_comercial_item");
        $this->belongsTo('id_proposta_comercial', 'Circuitos\Models\PropostaComercial', 'id', ['alias' => 'PropostaComercial']);
        $this->belongsTo('id_proposta_comercial_servicos', 'Circuitos\Models\PropostaComercialServico', 'id', ['alias' => 'PropostaComercialServico']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'proposta_comercial_item';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialItem[]|PropostaComercialItem|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialItem|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
