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

        $query->leftJoin("Circuitos\Models\Cidade", "CidadeDigital.id_cidadedigital = Cidade.id", "CidadeDigital");
        $query->leftJoin("Circuitos\Models\Conectividade", "CidadeDigital.id_conectividade = Conectividade.id", "Conectividade");
        $query->leftJoin("Circuitos\Models\Lov", "CidadeDigital.id_contrato = Lov.id", "Lov");

        $query->where("(CONVERT(Circuitos.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa1.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica1.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Equipamento.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Equipamento.numserie USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Equipamento.numpatrimonio USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov1.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov2.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov3.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov4.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov5.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov6.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov7.descricao USING utf8) LIKE '%{$parameters}%'                        
                        OR CONVERT(CidadeDigital.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Conectividade.descricao USING utf8) LIKE '%{$parameters}%'                        
                        OR CONVERT(Circuitos.designacao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.designacao_anterior USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.uf USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.cidade USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.ssid USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.ip_gerencia USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.ip_redelocal USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.tag USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.chamado USING utf8) LIKE '%{$parameters}%')");
        $query->orderBy("Circuitos.id DESC");

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
            'id_cidade' => 'id_cidade',
            'descricao' => 'descricao',
            'excluido' => 'excluido',
            'ativo' => 'ativo'
        ];
    }

}
