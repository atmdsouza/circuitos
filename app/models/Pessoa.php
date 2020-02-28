<?php

namespace Circuitos\Models;

use Util\Infra;

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
     *
     * @var string
     */
    protected $imagem;

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
     * Method to set the value of field immagem
     *
     * @param string $imagem
     * @return $this
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;

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
     * Returns the value of field imagem
     *
     * @return string
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("pessoa");
        $this->hasOne('id', 'Circuitos\Models\Cliente', 'id_pessoa', ['alias' => 'Cliente']);
        $this->hasOne('id', 'Circuitos\Models\ClienteUnidade', 'id_pessoa', ['alias' => 'ClienteUnidade']);
        $this->hasOne('id', 'Circuitos\Models\Fabricante', 'id_pessoa', ['alias' => 'Fabricante']);
        $this->hasMany('id', 'Circuitos\Models\PessoaContato', 'id_pessoa', ['alias' => 'PessoaContato']);
        $this->hasMany('id', 'Circuitos\Models\PessoaEmail', 'id_pessoa', ['alias' => 'PessoaEmail']);
        $this->hasMany('id', 'Circuitos\Models\PessoaEndereco', 'id_pessoa', ['alias' => 'PessoaEndereco']);
        $this->hasOne('id', 'Circuitos\Models\PessoaFisica', 'id', ['alias' => 'PessoaFisica']);
        $this->hasOne('id', 'Circuitos\Models\PessoaJuridica', 'id', ['alias' => 'PessoaJuridica']);
        $this->hasMany('id','Circuitos\Models\PessoaTelefone', 'id_pessoa', ['alias' => 'PessoaTelefone']);
        $this->hasOne('id', 'Circuitos\Models\Usuario', 'id_pessoa', ['alias' => 'Usuario']);
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

}
