<?php

namespace Circuitos\Models;

class UnidadeConsumidora extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $codigo_conta_contrato;

    /**
     *
     * @var string
     */
    protected $conta_agrupadora;

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
     * Method to set the value of field codigo_conta_contrato
     *
     * @param string $codigo_conta_contrato
     * @return $this
     */
    public function setCodigoContaContrato($codigo_conta_contrato)
    {
        $this->codigo_conta_contrato = $codigo_conta_contrato;

        return $this;
    }

    /**
     * Method to set the value of field conta_agrupadora
     *
     * @param string $conta_agrupadora
     * @return $this
     */
    public function setContaAgrupadora($conta_agrupadora)
    {
        $this->conta_agrupadora = $conta_agrupadora;

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
     * Returns the value of field codigo_conta_contrato
     *
     * @return string
     */
    public function getCodigoContaContrato()
    {
        return $this->codigo_conta_contrato;
    }

    /**
     * Returns the value of field conta_agrupadora
     *
     * @return string
     */
    public function getContaAgrupadora()
    {
        return $this->conta_agrupadora;
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
        $this->setSource("unidade_consumidora");
        $this->hasMany('id', 'Circuitos\Models\EstacaoTelecon', 'id_unidade_consumidora', ['alias' => 'EstacaoTelecon']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'unidade_consumidora';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UnidadeConsumidora[]|UnidadeConsumidora|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UnidadeConsumidora|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
