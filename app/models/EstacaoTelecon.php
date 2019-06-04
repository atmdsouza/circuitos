<?php

namespace Circuitos\Models;

class EstacaoTelecon extends \Phalcon\Mvc\Model
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
    protected $id_cidade_digital;

    /**
     *
     * @var integer
     */
    protected $id_contrato;

    /**
     *
     * @var integer
     */
    protected $id_terreno;

    /**
     *
     * @var integer
     */
    protected $id_torre;

    /**
     *
     * @var integer
     */
    protected $id_set_equipamento;

    /**
     *
     * @var integer
     */
    protected $id_set_seguranca;

    /**
     *
     * @var integer
     */
    protected $id_unidade_consumidora;

    /**
     *
     * @var string
     */
    protected $descricao;

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
     * Method to set the value of field id_cidade_digital
     *
     * @param integer $id_cidade_digital
     * @return $this
     */
    public function setIdCidadeDigital($id_cidade_digital)
    {
        $this->id_cidade_digital = $id_cidade_digital;

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
     * Method to set the value of field id_terreno
     *
     * @param integer $id_terreno
     * @return $this
     */
    public function setIdTerreno($id_terreno)
    {
        $this->id_terreno = $id_terreno;

        return $this;
    }

    /**
     * Method to set the value of field id_torre
     *
     * @param integer $id_torre
     * @return $this
     */
    public function setIdTorre($id_torre)
    {
        $this->id_torre = $id_torre;

        return $this;
    }

    /**
     * Method to set the value of field id_set_equipamento
     *
     * @param integer $id_set_equipamento
     * @return $this
     */
    public function setIdSetEquipamento($id_set_equipamento)
    {
        $this->id_set_equipamento = $id_set_equipamento;

        return $this;
    }

    /**
     * Method to set the value of field id_set_seguranca
     *
     * @param integer $id_set_seguranca
     * @return $this
     */
    public function setIdSetSeguranca($id_set_seguranca)
    {
        $this->id_set_seguranca = $id_set_seguranca;

        return $this;
    }

    /**
     * Method to set the value of field id_unidade_consumidora
     *
     * @param integer $id_unidade_consumidora
     * @return $this
     */
    public function setIdUnidadeConsumidora($id_unidade_consumidora)
    {
        $this->id_unidade_consumidora = $id_unidade_consumidora;

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
     * Returns the value of field id_cidade_digital
     *
     * @return integer
     */
    public function getIdCidadeDigital()
    {
        return $this->id_cidade_digital;
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
     * Returns the value of field id_terreno
     *
     * @return integer
     */
    public function getIdTerreno()
    {
        return $this->id_terreno;
    }

    /**
     * Returns the value of field id_torre
     *
     * @return integer
     */
    public function getIdTorre()
    {
        return $this->id_torre;
    }

    /**
     * Returns the value of field id_set_equipamento
     *
     * @return integer
     */
    public function getIdSetEquipamento()
    {
        return $this->id_set_equipamento;
    }

    /**
     * Returns the value of field id_set_seguranca
     *
     * @return integer
     */
    public function getIdSetSeguranca()
    {
        return $this->id_set_seguranca;
    }

    /**
     * Returns the value of field id_unidade_consumidora
     *
     * @return integer
     */
    public function getIdUnidadeConsumidora()
    {
        return $this->id_unidade_consumidora;
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
        $this->setSource("estacao_telecon");
        $this->belongsTo('id_cidade_digital', 'CircuitosModels\CidadeDigital', 'id', ['alias' => 'CidadeDigital']);
        $this->belongsTo('id_contrato', 'CircuitosModels\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_set_equipamento', 'CircuitosModels\SetEquipamento', 'id', ['alias' => 'SetEquipamento']);
        $this->belongsTo('id_set_seguranca', 'CircuitosModels\SetSeguranca', 'id', ['alias' => 'SetSeguranca']);
        $this->belongsTo('id_terreno', 'CircuitosModels\Terreno', 'id', ['alias' => 'Terreno']);
        $this->belongsTo('id_torre', 'CircuitosModels\Torre', 'id', ['alias' => 'Torre']);
        $this->belongsTo('id_unidade_consumidora', 'CircuitosModels\UnidadeConsumidora', 'id', ['alias' => 'UnidadeConsumidora']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'estacao_telecon';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EstacaoTelecon[]|EstacaoTelecon|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EstacaoTelecon|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
