<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;
use Util\Infra;

class Conectividade extends \Phalcon\Mvc\Model
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
    protected $id_cidade_digital;

    /**
     *
     * @var integer
     */
    protected $id_tipo;

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
    protected $ativo;

    /**
     *
     * @var integer
     */
    protected $excluido;

    /**
     *
     * @var string
     */
    protected $data_update;

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
     * Method to set the value of field id_cidade_digital
     *
     * @param integer $id_cidade_digital
     * @return $this
     */
    public function setIdCidadeDigital($id_cidade_digital)
    {
        $this->id_cidade_digital = $id_cidade_digital;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field id_cidade_digital
     *
     * @return integer
     */
    public function getIdCidadeDigital()
    {
        return $this->id_cidade_digital;
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
     * Returns the value of field ativo
     *
     * @return integer
     */
    public function getAtivo()
    {
        return $this->ativo;
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
     * Returns the value of tipo de conectividade
     *
     * @return string
     */
    public function getTipoConectividade()
    {
        return $this->Lov->descricao;
    }

    /**
     * Returns the value of tipo de conectividade
     *
     * @return string
     */
    public function getNomeCidadeDigital()
    {
        return $this->CidadeDigital->descricao;
    }

    /**
     * @return string
     */
    public function getDataUpdate()
    {
        return $this->data_update;
    }

    /**
     * @param string $data_update
     */
    public function setDataUpdate($data_update)
    {
        $this->data_update = $data_update;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("conectividade");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_conectividade', ['alias' => 'Circuitos']);
        $this->belongsTo('id_cidade_digital', 'Circuitos\Models\CidadeDigital', 'id', ['alias' => 'CidadeDigital']);
        $this->belongsTo('id_tipo', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'conectividade';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Conectividade[]|Conectividade|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Conectividade|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta com o join na tabela lov
     *
     * @param int $id_cidadedigital
     * @return Conectividade|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaCompletaConectividade($id_cidadedigital)
    {
        $query = new Builder();
        $query->from(array("Conectividade" => "Circuitos\Models\Conectividade"));
        $query->columns("Conectividade.*, Lov.descricao");
        $query->join("Circuitos\Models\Lov", "Lov.id = Conectividade.id_tipo", "Lov");
        $query->where("Conectividade.id_cidade_digital = :id: AND Conectividade.excluido = :excluido:", array("id" => $id_cidadedigital, "excluido" => 0));
        $resultado = $query->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS);
        return $resultado;
    }

    /**
     * Consulta completa de Conectividades, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Conectividades|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarConectividade($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Conectividade" => "Circuitos\Models\Conectividade"));
        $query->columns("Conectividade.*");
        $query->leftJoin("Circuitos\Models\Lov", "Lov.id = Conectividade.id_tipo", "Lov");
        $query->leftJoin("Circuitos\Models\CidadeDigital", "CidadeDigital.id = Conectividade.id_cidade_digital", "CidadeDigital");
        $query->where("Conectividade.excluido = 0 AND (CONVERT(Conectividade.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Conectividade.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(CidadeDigital.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov.descricao USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("Conectividade.id");
        $query->orderBy("Conectividade.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
