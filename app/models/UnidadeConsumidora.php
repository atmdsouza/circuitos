<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

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
     *
     * @var integer
     */
    protected $id_conta_agrupadora;

    /**
     *
     * @var string
     */
    protected $descricao;

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
     * Method to set the value of field id_conta_agrupadora
     *
     * @param integer $id_conta_agrupadora
     * @return $this
     */
    public function setIdContaAgrupadora($id_conta_agrupadora)
    {
        $this->id_conta_agrupadora = $id_conta_agrupadora;

        return $this;
    }

    /**
     * Method to set the value of field descricao
     *
     * @param string $descricao
     * @return $this
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

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
     * Returns the value of field id_conta_agrupadora
     *
     * @return integer
     */
    public function getIdContaAgrupadora()
    {
        return $this->id_conta_agrupadora;
    }

    /**
     * Returns the value of field descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Returns the value of field conta_agrupadora
     *
     * @return string
     */
    public function getContaAgrupadoraPai()
    {
        return $this->UnidadeConsumidora->codigo_conta_contrato;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("unidade_consumidora");
        $this->hasMany('id', 'Circuitos\Models\EstacaoTelecon', 'id_unidade_consumidora', ['alias' => 'EstacaoTelecon']);
        $this->hasMany('id', 'Circuitos\Models\UnidadeConsumidora', 'id_conta_agrupadora', ['alias' => 'UnidadeConsumidora']);
        $this->belongsTo('id_conta_agrupadora', 'Circuitos\Models\UnidadeConsumidora', 'id', ['alias' => 'UnidadeConsumidora']);
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

    /**
     * Consulta completa de UnidadeConsumidora, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return UnidadeConsumidora|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarUnidadeConsumidora($parameters = null)
    {
        $query = new Builder();
        $query->from(array("UnidadeConsumidora" => "Circuitos\Models\UnidadeConsumidora"));
        $query->columns("UnidadeConsumidora.*");
        $query->where("UnidadeConsumidora.excluido = 0 AND (CONVERT(UnidadeConsumidora.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(UnidadeConsumidora.codigo_conta_contrato USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(UnidadeConsumidora.id_conta_agrupadora USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("UnidadeConsumidora.id");
        $query->orderBy("UnidadeConsumidora.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
