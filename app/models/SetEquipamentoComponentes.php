<?php

namespace Circuitos\Models;

use Util\Infra;

class SetEquipamentoComponentes extends \Phalcon\Mvc\Model
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
    protected $id_set_equipamento;

    /**
     *
     * @var integer
     */
    protected $id_equipamento;

    /**
     *
     * @var integer
     */
    protected $id_contrato;

    /**
     *
     * @var integer
     */
    protected $id_fornecedor;

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
     * Method to set the value of field id_equipamento
     *
     * @param integer $id_equipamento
     * @return $this
     */
    public function setIdEquipamento($id_equipamento)
    {
        $this->id_equipamento = $id_equipamento;

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
     * @param int $id_fornecedor
     * @return $this
     */
    public function setIdFornecedor($id_fornecedor)
    {
        $this->id_fornecedor = $id_fornecedor;

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
     * Returns the value of field id_set_equipamento
     *
     * @return integer
     */
    public function getIdSetEquipamento()
    {
        return $this->id_set_equipamento;
    }

    /**
     * Returns the value of field id_equipamento
     *
     * @return integer
     */
    public function getIdEquipamento()
    {
        return $this->id_equipamento;
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
     * @return int
     */
    public function getIdFornecedor()
    {
        return $this->id_fornecedor;
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
     * Returns the value of field Fabricante do Equipamento
     *
     * @return string
     */
    public function getIdFabricante()
    {
        return $this->Equipamento->id_fabricante;
    }

    /**
     * Returns the value of field Fabricante do Equipamento
     *
     * @return string
     */
    public function getFabricante()
    {
        return $this->Equipamento->Fabricante->Pessoa->nome;
    }

    /**
     * Returns the value of field Modelo do Equipamento
     *
     * @return string
     */
    public function getIdModelo()
    {
        return $this->Equipamento->id_modelo;
    }

    /**
     * Returns the value of field Modelo do Equipamento
     *
     * @return string
     */
    public function getModelo()
    {
        return $this->Equipamento->Modelo->modelo;
    }

    /**
     * Returns the value of field Equipamento
     *
     * @return string
     */
    public function getEquipamento()
    {
        return $this->Equipamento->nome;
    }

    /**
     * Returns the value of field Numero Serie
     *
     * @return string
     */
    public function getNumSerie()
    {
        return $this->Equipamento->numserie;
    }

    /**
     * Returns the value of field Numero Patrimonio
     *
     * @return string
     */
    public function getNumPatrimonio()
    {
        return $this->Equipamento->numpatrimonio;
    }

    /**
     * Returns the value of field Nome do Fornecedor
     *
     * @return string
     */
    public function getFornecedor()
    {
        return $this->Fornecedor->Pessoa->nome;
    }

    /**
     * Returns the value of field Contrato
     *
     * @return string
     */
    public function getContrato()
    {
        return $this->Contrato->numero;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("set_equipamento_componentes");
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_equipamento', 'Circuitos\Models\Equipamento', 'id', ['alias' => 'Equipamento']);
        $this->belongsTo('id_set_equipamento', 'Circuitos\Models\SetEquipamento', 'id', ['alias' => 'SetEquipamento']);
        $this->belongsTo('id_fornecedor', 'Circuitos\Models\Cliente', 'id', ['alias' => 'Fornecedor']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'set_equipamento_componentes';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SetEquipamentoComponentes[]|SetEquipamentoComponentes|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SetEquipamentoComponentes|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
