<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;
use Util\Infra;

class ClienteUnidade extends \Phalcon\Mvc\Model
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
    protected $id_cliente;

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
     * Method to set the value of field id_cliente
     *
     * @param integer $id_cliente
     * @return $this
     */
    public function setIdCliente($id_cliente)
    {
        $this->id_cliente = $id_cliente;

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
     * Returns the value of field id_cliente
     *
     * @return integer
     */
    public function getIdCliente()
    {
        return $this->id_cliente;
    }

    /**
     * Returns the value of Nome do Cliente Unidade
     *
     * @return string
     */
    public function getClienteUnidadeNome()
    {
        return $this->Pessoa->nome;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("cliente_unidade");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_cliente_unidade', ['alias' => 'Circuitos']);
        $this->belongsTo('id_cliente', 'Circuitos\Models\Cliente', 'id', ['alias' => 'Cliente']);
        $this->hasOne('id_pessoa', 'Circuitos\Models\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'cliente_unidade';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClienteUnidade[]|ClienteUnidade|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClienteUnidade|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de unidade de clientes, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return ClienteUnidade|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarClientesUnidades($parameters = null)
    {
        $query = new Builder();
        $query->from(array("ClienteUnidade" => "Circuitos\Models\ClienteUnidade"));
        $query->columns("ClienteUnidade.*");

        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = ClienteUnidade.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\PessoaEndereco", "Pessoa2.id = PessoaEndereco2.id_pessoa", "PessoaEndereco2");
        $query->leftJoin("Circuitos\Models\Cliente", "Cliente.id = ClienteUnidade.id_cliente", "Cliente");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa1.id = Cliente.id_pessoa", "Pessoa1");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa1.id = PessoaJuridica1.id", "PessoaJuridica1");

        $query->where("(CONVERT(ClienteUnidade.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa1.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica1.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaEndereco2.cidade USING utf8) LIKE '%{$parameters}%')");

        $query->groupBy("ClienteUnidade.id");

        $query->orderBy("ClienteUnidade.id DESC");

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta com o join na tabela com o nome do cliente unidade
     *
     * @param int $tipopessoa
     * @return ClienteUnidade|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaClienteUnidade($id_cliente)
    {
        $query = new Builder();
        $query->from(array("ClienteUnidade" => "Circuitos\Models\ClienteUnidade"));
        $query->columns("ClienteUnidade.id, Pessoa.nome");
        $query->join("Circuitos\Models\Pessoa", "Pessoa.id = ClienteUnidade.id_pessoa", "Pessoa");
        $query->where("ClienteUnidade.id_cliente = :id:", array("id" => $id_cliente));
        $query->orderBy("Pessoa.nome ASC");
        $resultado = $query->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS);
        return $resultado;
    }

    /**
     * Consulta com o join na tabela com o nome do cliente unidade
     *
     * @param int $tipopessoa
     * @return ClienteUnidade|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaUnidadeAtiva()
    {
        $query = new Builder();
        $query->from(array("ClienteUnidade" => "Circuitos\Models\ClienteUnidade"));
        $query->columns("ClienteUnidade.id, Pessoa.nome");
        $query->join("Circuitos\Models\Pessoa", "Pessoa.id = ClienteUnidade.id_pessoa", "Pessoa");
        $query->where("Pessoa.ativo = 1");
        $query->orderBy("Pessoa.nome ASC");
        $resultado = $query->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS);
        return $resultado;
    }

}
