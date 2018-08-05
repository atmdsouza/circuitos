<?php

namespace Circuitos\Models;

class PessoaTelefone extends \Phalcon\Mvc\Model
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
     *
     * @var integer
     */
    protected $id_tipotelefone;

    /**
     *
     * @var integer
     */
    protected $principal;

    /**
     *
     * @var string
     */
    protected $ddd;

    /**
     *
     * @var string
     */
    protected $telefone;

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
     * Method to set the value of field id_tipotelefone
     *
     * @param integer $id_tipotelefone
     * @return $this
     */
    public function setIdTipotelefone($id_tipotelefone)
    {
        $this->id_tipotelefone = $id_tipotelefone;

        return $this;
    }

    /**
     * Method to set the value of field principal
     *
     * @param integer $principal
     * @return $this
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

        return $this;
    }

    /**
     * Method to set the value of field ddd
     *
     * @param string $ddd
     * @return $this
     */
    public function setDdd($ddd)
    {
        $this->ddd = $ddd;

        return $this;
    }

    /**
     * Method to set the value of field telefone
     *
     * @param string $telefone
     * @return $this
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

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
     * Returns the value of field id_pessoa
     *
     * @return integer
     */
    public function getIdPessoa()
    {
        return $this->id_pessoa;
    }

    /**
     * Returns the value of field id_tipotelefone
     *
     * @return integer
     */
    public function getIdTipotelefone()
    {
        return $this->id_tipotelefone;
    }

    /**
     * Returns the value of field principal
     *
     * @return integer
     */
    public function getPrincipal()
    {
        return $this->principal;
    }

    /**
     * Returns the value of field ddd
     *
     * @return string
     */
    public function getDdd()
    {
        return $this->ddd;
    }

    /**
     * Returns the value of field telefone
     *
     * @return string
     */
    public function getTelefone()
    {
        return $this->telefone;
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
        $this->setSource("pessoa_telefone");
        $this->belongsTo('id_tipotelefone', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_pessoa', 'Circuitos\Models\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pessoa_telefone';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaTelefone[]|PessoaTelefone|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaTelefone|\Phalcon\Mvc\Model\ResultInterface
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
            'id_pessoa' => 'id_pessoa',
            'id_tipotelefone' => 'id_tipotelefone',
            'principal' => 'principal',
            'ddd' => 'ddd',
            'telefone' => 'telefone',
            'ativo' => 'ativo'
        ];
    }

}
