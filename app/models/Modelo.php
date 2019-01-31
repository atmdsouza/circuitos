<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class Modelo extends \Phalcon\Mvc\Model
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
     * @var string
     */
    protected $modelo;

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
     * Method to set the value of field modelo
     *
     * @param string $modelo
     * @return $this
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

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
     * Returns the value of field modelo
     *
     * @return string
     */
    public function getModelo()
    {
        return $this->modelo;
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
        $this->setSource("modelo");
        $this->hasMany('id', 'Circuitos\Models\Equipamento', 'id_modelo', ['alias' => 'Equipamento']);
        $this->belongsTo('id_fabricante', 'Circuitos\Models\Fabricante', 'id', ['alias' => 'Fabricante']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'modelo';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Modelo[]|Modelo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Modelo|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de modelos, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Modelo|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarModelos($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Modelo" => "Circuitos\Models\Modelo"));
        $query->columns("Modelo.*");

        $query->leftJoin("Circuitos\Models\Fabricante", "Fabricante.id = Modelo.id_fabricante", "Fabricante");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = Fabricante.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\PessoaFisica", "Pessoa2.id = PessoaFisica2.id", "PessoaFisica2");
        $query->leftJoin("Circuitos\Models\PessoaEndereco", "Pessoa2.id = PessoaEndereco2.id_pessoa", "PessoaEndereco2");

        $query->where("(CONVERT(Modelo.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Modelo.modelo USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Modelo.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.cnpj USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.sigla USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaEndereco2.cidade USING utf8) LIKE '%{$parameters}%')");

        $query->orderBy("Modelo.id DESC");

        $resultado = $query->getQuery()->execute();
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
            'id_fabricante' => 'id_fabricante',
            'modelo' => 'modelo',
            'descricao' => 'descricao',
            'ativo' => 'ativo'
        ];
    }

}
