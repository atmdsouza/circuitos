<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;
use Util\Infra;

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
     *
     * @var integer
     */
    protected $classificacao;

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
     * Returns the value of Nome do Cliente
     *
     * @return string
     */
    public function getClienteNome()
    {
        return $this->Pessoa->nome;
    }

    /**
     * @return int
     */
    public function getClassificacao()
    {
        return $this->classificacao;
    }

    /**
     * @param int $classificacao
     */
    public function setClassificacao($classificacao)
    {
        $this->classificacao = $classificacao;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("cliente");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_cliente', ['alias' => 'Circuitos']);
        $this->hasMany('id', 'Circuitos\Models\Cliente', 'id_cliente', ['alias' => 'Cliente']);
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
     * Consulta completa de clientes, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Cliente|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarClientes($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Cliente" => "Circuitos\Models\Cliente"));
        $query->columns("Cliente.*");

        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = Cliente.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\PessoaFisica", "Pessoa2.id = PessoaFisica2.id", "PessoaFisica2");
        $query->leftJoin("Circuitos\Models\PessoaEndereco", "Pessoa2.id = PessoaEndereco2.id_pessoa", "PessoaEndereco2");

        $query->where("(CONVERT(Cliente.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.cnpj USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.sigla USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaFisica2.cpf USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaFisica2.rg USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaEndereco2.cidade USING utf8) LIKE '%{$parameters}%')");

        $query->groupBy("Cliente.id");

        $query->orderBy("Cliente.id DESC");

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta completa de clientes, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Cliente|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarClientesAtivos($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Cliente" => "Circuitos\Models\Cliente"));
        $query->columns("Cliente.*");

        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = Cliente.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\PessoaFisica", "Pessoa2.id = PessoaFisica2.id", "PessoaFisica2");
        $query->where("Pessoa2.excluido=0 AND Pessoa2.ativo=1 AND Cliente.classificacao = 0 AND (CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("Cliente.id");
        $query->orderBy("Cliente.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta completa de todos os tipos clientes, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Cliente|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarClientesFornecedoresParceirosAtivos($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Cliente" => "Circuitos\Models\Cliente"));
        $query->columns("Cliente.*");

        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = Cliente.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\PessoaFisica", "Pessoa2.id = PessoaFisica2.id", "PessoaFisica2");
        $query->where("Pessoa2.excluido=0 AND Pessoa2.ativo=1 AND (CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("Cliente.id");
        $query->orderBy("Cliente.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta completa de fornecedores, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Cliente|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarFornecedoresAtivos($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Cliente" => "Circuitos\Models\Cliente"));
        $query->columns("Cliente.*");

        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = Cliente.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\PessoaFisica", "Pessoa2.id = PessoaFisica2.id", "PessoaFisica2");
        $query->where("Pessoa2.excluido=0 AND Pessoa2.ativo=1 AND Cliente.classificacao <> 0 AND (CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("Cliente.id");
        $query->orderBy("Cliente.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta com o join na tabela com o nome do cliente
     *
     * @param int $tipopessoa
     * @return Cliente|\Phalcon\Mvc\Model\Resultset
     */
    public static function allCompClientes($parameters = null)
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
     * Consulta com o join na tabela com o nome do cliente
     * @return Cliente|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscarClientes()
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

}
