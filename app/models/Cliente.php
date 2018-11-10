<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class Cliente extends \Phalcon\Mvc\Model
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
    protected $id_tipocliente;

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
     * Method to set the value of field id_tipocliente
     *
     * @param integer $id_tipocliente
     * @return $this
     */
    public function setIdTipocliente($id_tipocliente)
    {
        $this->id_tipocliente = $id_tipocliente;

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
     * Returns the value of field id_tipocliente
     *
     * @return integer
     */
    public function getIdTipocliente()
    {
        return $this->id_tipocliente;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("cliente");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_cliente', ['alias' => 'Circuitos']);
        $this->hasMany('id', 'Circuitos\Models\ClienteUnidade', 'id_cliente', ['alias' => 'ClienteUnidade']);
        $this->belongsTo('id_tipocliente', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
        $this->hasOne('id_pessoa', 'Circuitos\Models\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'cliente';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cliente[]|Cliente|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cliente|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta com o join na tabela com o nome do cliente 
     *
     * @param int $tipopessoa
     * @return Cliente|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaCompletaCliente($tipopessoa)
    {
        $query = new Builder();
        $query->from(array("Cliente" => "Circuitos\Models\Cliente"));
        $query->columns("Cliente.id, Pessoa.nome");
        $query->join("Circuitos\Models\Pessoa", "Pessoa.id = Cliente.id_pessoa", "Pessoa");
        $query->where("Cliente.id_tipocliente = :id:", array("id" => $tipopessoa));
        $query->orderBy("Pessoa.nome ASC");
        $resultado = $query->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS);
        return $resultado;
    }

    /**
     * Consulta com o join na tabela com o nome do cliente retornando somente os ativos
     *
     * @param int $tipopessoa
     * @return Cliente|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaClienteAtivo()
    {
        $query = new Builder();
        $query->from(array("Cliente" => "Circuitos\Models\Cliente"));
        $query->columns("Cliente.id, Pessoa.nome");
        $query->join("Circuitos\Models\Pessoa", "Pessoa.id = Cliente.id_pessoa", "Pessoa");
        $query->where("Pessoa.ativo = 1");
        $query->orderBy("Pessoa.nome ASC");
        $resultado = $query->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS);
        return $resultado;
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
            'id_tipocliente' => 'id_tipocliente'
        ];
    }

}
