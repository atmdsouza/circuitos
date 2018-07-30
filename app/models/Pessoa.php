<?php

use Usuario;

class Pessoa extends \Phalcon\Mvc\Model
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
    protected $nome;

    /**
     *
     * @var integer
     */
    protected $ativo;

    /**
     *
     * @var string
     */
    protected $create_at;

    /**
     *
     * @var string
     */
    protected $update_at;

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
     * Method to set the value of field create_at
     *
     * @param string $create_at
     * @return $this
     */
    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;

        return $this;
    }

    /**
     * Method to set the value of field update_at
     *
     * @param string $update_at
     * @return $this
     */
    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;

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
     * Returns the value of field nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
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
     * Returns the value of field create_at
     *
     * @return string
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * Returns the value of field update_at
     *
     * @return string
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("pessoa");
        $this->hasOne('id', Cliente, 'id_pessoa', ['alias' => 'Cliente']);
        $this->hasOne('id', ClienteUnidade, 'id_pessoa', ['alias' => 'ClienteUnidade']);
        $this->hasOne('id', Fabricante, 'id_pessoa', ['alias' => 'Fabricante']);
        $this->hasMany('id', PessoaContato, 'id_pessoa', ['alias' => 'PessoaContato']);
        $this->hasMany('id', PessoaEmail, 'id_pessoa', ['alias' => 'PessoaEmail']);
        $this->hasMany('id', PessoaEndereco, 'id_pessoa', ['alias' => 'PessoaEndereco']);
        $this->hasOne('id', PessoaFisica, 'id', ['alias' => 'PessoaFisica']);
        $this->hasOne('id', PessoaJuridica, 'id', ['alias' => 'PessoaJuridica']);
        $this->hasMany('id',PessoaTelefone, 'id_pessoa', ['alias' => 'PessoaTelefone']);
        $this->hasOne('id', Usuario, 'id_pessoa', ['alias' => 'Usuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pessoa';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Pessoa[]|Pessoa|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Pessoa|\Phalcon\Mvc\Model\ResultInterface
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
            'nome' => 'nome',
            'ativo' => 'ativo',
            'create_at' => 'create_at',
            'update_at' => 'update_at'
        ];
    }

}
