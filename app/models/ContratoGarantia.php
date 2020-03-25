<?php

namespace Circuitos\Models;

use Util\Infra;
use Util\Util;

class ContratoGarantia extends \Phalcon\Mvc\Model
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
    protected $id_modalidade;

    /**
     *
     * @var string
     */
    protected $data_criacao;

    /**
     *
     * @var integer
     */
    protected $garantia_concretizada;

    /**
     *
     * @var double
     */
    protected $percentual;

    /**
     *
     * @var double
     */
    protected $valor;

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
     * @var integer
     */
    protected $id_contrato;

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
     * Method to set the value of field id_modalidade
     *
     * @param integer $id_modalidade
     * @return $this
     */
    public function setIdModalidade($id_modalidade)
    {
        $this->id_modalidade = $id_modalidade;

        return $this;
    }

    /**
     * Method to set the value of field data_criacao
     *
     * @param string $data_criacao
     * @return $this
     */
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;

        return $this;
    }

    /**
     * Method to set the value of field garantia_concretizada
     *
     * @param integer $garantia_concretizada
     * @return $this
     */
    public function setGarantiaConcretizada($garantia_concretizada)
    {
        $this->garantia_concretizada = $garantia_concretizada;

        return $this;
    }

    /**
     * Method to set the value of field percentual
     *
     * @param double $percentual
     * @return $this
     */
    public function setPercentual($percentual)
    {
        $this->percentual = $percentual;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field id_modalidade
     *
     * @return integer
     */
    public function getIdModalidade()
    {
        return $this->id_modalidade;
    }

    /**
     * Returns the value of field data_criacao
     *
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    /**
     * Returns the value of field garantia_concretizada
     *
     * @return integer
     */
    public function getGarantiaConcretizada()
    {
        return $this->garantia_concretizada;
    }

    /**
     * Returns the value of field percentual
     *
     * @return double
     */
    public function getPercentual()
    {
        return $this->percentual;
    }

    /**
     * Returns the value of field percentual formatado
     *
     * @return double
     */
    public function getPercentualFormatado()
    {
        $util = new Util();
        return $util->formataMoedaReal($this->percentual);
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
     * Returns the value of field valor formatado
     *
     * @return double
     */
    public function getValorFromatado()
    {
        $util = new Util();
        return $util->formataMoedaReal($this->valor);
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
     * Returns the value of field id_contrato
     *
     * @return integer
     */
    public function getIdContrato()
    {
        return $this->id_contrato;
    }

    /**
     * Returns the value of field modalidade
     *
     * @return string
     */
    public function getModalidade()
    {
        return $this->Lov->descricao;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_garantia");
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_modalidade', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoGarantia[]|ContratoGarantia|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoGarantia|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_garantia';
    }

}
