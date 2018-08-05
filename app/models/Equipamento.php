<?php

namespace Circuitos\Models;

class Equipamento extends \Phalcon\Mvc\Model
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
    protected $id_fabricante;

    /**
     *
     * @var integer
     */
    protected $id_modelo;

    /**
     *
     * @var string
     */
    protected $nome;

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
     * Method to set the value of field id_fabricante
     *
     * @param integer $id_fabricante
     * @return $this
     */
    public function setIdFabricante($id_fabricante)
    {
        $this->id_fabricante = $id_fabricante;

        return $this;
    }

    /**
     * Method to set the value of field id_modelo
     *
     * @param integer $id_modelo
     * @return $this
     */
    public function setIdModelo($id_modelo)
    {
        $this->id_modelo = $id_modelo;

        return $this;
    }

    /**
     * Method to set the value of field nome
     *
     * @param string $nome
     * @return $this
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field id_fabricante
     *
     * @return integer
     */
    public function getIdFabricante()
    {
        return $this->id_fabricante;
    }

    /**
     * Returns the value of field id_modelo
     *
     * @return integer
     */
    public function getIdModelo()
    {
        return $this->id_modelo;
    }

    /**
     * Returns the value of field nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("equipamento");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_equipamento', ['alias' => 'Circuitos']);
        $this->belongsTo('id_fabricante', 'Circuitos\Models\Fabricante', 'id', ['alias' => 'Fabricante']);
        $this->belongsTo('id_modelo', 'Circuitos\Models\Modelo', 'id', ['alias' => 'Modelo']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'equipamento';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Equipamento[]|Equipamento|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Equipamento|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'id_fabricante' => 'id_fabricante',
            'id_modelo' => 'id_modelo',
            'nome' => 'nome',
            'descricao' => 'descricao',
            'ativo' => 'ativo'
        ];
    }

}
