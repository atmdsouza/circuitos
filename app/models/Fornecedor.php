<?php

namespace Circuitos\Models;

class Fornecedor extends \Phalcon\Mvc\Model
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
    protected $id_pessoa;

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
     * Method to set the value of field id_pessoa
     *
     * @param integer $id_pessoa
     * @return $this
     */
    public function setIdPessoa($id_pessoa)
    {
        $this->id_pessoa = $id_pessoa;

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
     * Returns the value of field id_pessoa
     *
     * @return integer
     */
    public function getIdPessoa()
    {
        return $this->id_pessoa;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("fornecedor");
        $this->hasMany('id', 'Circuitos\Contrato', 'id_fornecedor', ['alias' => 'Contrato']);
        $this->hasMany('id', 'Circuitos\Equipamento', 'id_fornecedor', ['alias' => 'Equipamento']);
        $this->hasMany('id', 'Circuitos\SetSegurancaComponentes', 'id_fornecedor', ['alias' => 'SetSegurancaComponentes']);
        $this->hasMany('id', 'Circuitos\Terreno', 'id_fornecedor', ['alias' => 'Terreno']);
        $this->hasMany('id', 'Circuitos\Torre', 'id_fornecedor', ['alias' => 'Torre']);
        $this->belongsTo('id_pessoa', 'Circuitos\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'fornecedor';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Fornecedor[]|Fornecedor|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Fornecedor|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
