<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

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
    protected $id_cidade;

    /**
     *
     * @var string
     */
    protected $descricao;

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
        $this->hasMany('id', 'Circuitos\Models\Conectividade', 'id_cidade_digital', ['alias' => 'Conectividade']);
        $this->belongsTo('id_cidade', 'Circuitos\Models\EndCidade', 'id', ['alias' => 'EndCidade']);
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
     * Consulta completa de Cidades Digitais, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Cliente|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarCidadeDigital($parameters = null)
    {
        $query = new Builder();
        $query->from(array("CidadeDigital" => "Circuitos\Models\CidadeDigital"));
        $query->columns("CidadeDigital.*");

        $query->leftJoin("Circuitos\Models\EndCidade", "CidadeDigital.id_cidade = EndCidade.id", "EndCidade");
        $query->leftJoin("Circuitos\Models\Conectividade", "CidadeDigital.id = Conectividade.id_cidade_digital", "Conectividade");

        $query->where("CidadeDigital.excluido = 0 AND (CONVERT(CidadeDigital.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(CidadeDigital.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(EndCidade.cidade USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Conectividade.descricao USING utf8) LIKE '%{$parameters}%')");

        $query->groupBy("CidadeDigital.id");

        $query->orderBy("CidadeDigital.id DESC");

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta retornar a cidade e o estado do circuito
     *
     * @return CidadeDigital|\Phalcon\Mvc\Model\Resultset
     */
    public static function CidadeUfporCidadeDigital($id_cidadedigital)
    {
        $query = new Builder();
        $query->from(array("CidadeDigital" => "Circuitos\Models\CidadeDigital"));
        $query->columns("EndCidade.cidade, EndCidade.uf");
        $query->innerJoin("Circuitos\Models\EndCidade", "CidadeDigital.id_cidade = EndCidade.id", "EndCidade");
        $query->where("CidadeDigital.id = {$id_cidadedigital}");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de cidades digitais por status
     *
     * @return CidadeDigital|\Phalcon\Mvc\Model\Resultset
     */
    public static function cidadedigitalStatus()
    {
        $query = new Builder();
        $query->from(array("CidadeDigital" => "Circuitos\Models\CidadeDigital"));
        $query->columns("CASE CidadeDigital.ativo WHEN 1 THEN 'ATIVO' ELSE 'INATIVO' END AS status, count(CidadeDigital.ativo) AS total");
        $query->where("CidadeDigital.excluido = 0");
        $query->groupBy("CidadeDigital.ativo");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
