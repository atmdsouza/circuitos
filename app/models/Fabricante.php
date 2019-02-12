<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class Fabricante extends \Phalcon\Mvc\Model
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
        $this->setSource("fabricante");
        $this->hasMany('id', 'Circuitos\Models\Equipamento', 'id_fabricante', ['alias' => 'Equipamento']);
        $this->hasMany('id', 'Circuitos\Models\Modelo', 'id_fabricante', ['alias' => 'Modelo']);
        $this->hasOne('id_pessoa', 'Circuitos\Models\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'fabricante';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Fabricante[]|Fabricante|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Fabricante|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de fabricantes, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Fabricante|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarFabricantes($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Fabricante" => "Circuitos\Models\Fabricante"));
        $query->columns("Fabricante.*");

        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = Fabricante.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\PessoaEndereco", "Pessoa2.id = PessoaEndereco2.id_pessoa", "PessoaEndereco2");

        $query->where("(CONVERT(Fabricante.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.cnpj USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.sigla USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaEndereco2.cidade USING utf8) LIKE '%{$parameters}%')");

        $query->groupBy("Fabricante.id, Fabricante.id_pessoa");

        $query->orderBy("Fabricante.id DESC");

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta com o join na tabela com o nome do fabricante 
     *
     * @param int $tipopessoa
     * @return Fabricante|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaCompletaFabricante()
    {
        $query = new Builder();
        $query->from(array("Fabricante" => "Circuitos\Models\Fabricante"));
        $query->columns("Fabricante.id, Pessoa.nome");
        $query->join("Circuitos\Models\Pessoa", "Pessoa.id = Fabricante.id_pessoa", "Pessoa");
        $query->orderBy("Pessoa.nome ASC");
        $resultado = $query->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS);
        return $resultado;
    }

    /**
     * Consulta com o join na tabela com o nome do fabricante ativo
     *
     * @param int $tipopessoa
     * @return Fabricante|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaFabricanteAtivo()
    {
        $query = new Builder();
        $query->from(array("Fabricante" => "Circuitos\Models\Fabricante"));
        $query->columns("Fabricante.id, Pessoa.nome");
        $query->join("Circuitos\Models\Pessoa", "Pessoa.id = Fabricante.id_pessoa", "Pessoa");
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
            'id_pessoa' => 'id_pessoa'
        ];
    }

}
