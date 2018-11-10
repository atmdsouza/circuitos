<?php

namespace Circuitos\Models;

class CidadeDigital extends \Phalcon\Mvc\Model
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
    protected $id_tipo;

    /**
     *
     * @var integer
     */
    protected $id_cidade;

    /**
     *
     * @var string
     */
    protected $descricao;

    /**
     *
     * @var string
     */
    protected $endereco;

    /**
     *
     * @var integer
     */
    protected $excluido;

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
     * Method to set the value of field id_tipo
     *
     * @param integer $id_tipo
     * @return $this
     */
    public function setIdTipo($id_tipo)
    {
        $this->id_tipo = $id_tipo;

        return $this;
    }

    /**
     * Method to set the value of field id_cidade
     *
     * @param integer $id_cidade
     * @return $this
     */
    public function setIdCidade($id_cidade)
    {
        $this->id_cidade = $id_cidade;

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
     * Method to set the value of field endereco
     *
     * @param string $endereco
     * @return $this
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

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
     * Returns the value of field id_tipo
     *
     * @return integer
     */
    public function getIdTipo()
    {
        return $this->id_tipo;
    }

    /**
     * Returns the value of field id_cidade
     *
     * @return integer
     */
    public function getIdCidade()
    {
        return $this->id_cidade;
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
     * Returns the value of field endereco
     *
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
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
        $this->setSource("cidade_digital");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_cidadedigital', ['alias' => 'Circuitos']);
        $this->belongsTo('id_cidade', 'Circuitos\Models\EndCidade', 'id', ['alias' => 'EndCidade']);
        $this->belongsTo('id_tipo', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'cidade_digital';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CidadeDigital[]|CidadeDigital|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CidadeDigital|\Phalcon\Mvc\Model\ResultInterface
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
            'id_tipo' => 'id_tipo',
            'id_cidade' => 'id_cidade',
            'descricao' => 'descricao',
            'endereco' => 'endereco',
            'excluido' => 'excluido',
            'ativo' => 'ativo'
        ];
    }

}
