<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class Torre extends \Phalcon\Mvc\Model
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
    protected $id_contrato;

    /**
     *
     * @var integer
     */
    protected $id_fornecedor;

    /**
     *
     * @var string
     */
    protected $descricao;

    /**
     *
     * @var double
     */
    protected $altura;

    /**
     *
     * @var integer
     */
    protected $propriedade_prodepa;

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
     * Method to set the value of field id_contrato
     *
     * @param integer $id_contrato
     * @return $this
     */
    public function setIdContrato($id_contrato)
    {
        $this->id_contrato = $id_contrato;

        return $this;
    }

    /**
     * Method to set the value of field id_fornecedor
     *
     * @param integer $id_fornecedor
     * @return $this
     */
    public function setIdFornecedor($id_fornecedor)
    {
        $this->id_fornecedor = $id_fornecedor;

        return $this;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * Method to set the value of field altura
     *
     * @param double $altura
     * @return $this
     */
    public function setAltura($altura)
    {
        $this->altura = $altura;

        return $this;
    }

    /**
     * Method to set the value of field propriedade_prodepa
     *
     * @param integer $propriedade_prodepa
     * @return $this
     */
    public function setPropriedadeProdepa($propriedade_prodepa)
    {
        $this->propriedade_prodepa = $propriedade_prodepa;

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
     * Method to set the value of field data_update
     *
     * @param string $data_update
     * @return $this
     */
    public function setDataUpdate($data_update)
    {
        $this->data_update = $data_update;

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
     * Returns the value of field id_contrato
     *
     * @return integer
     */
    public function getIdContrato()
    {
        return $this->id_contrato;
    }

    /**
     * Returns the value of field id_fornecedor
     *
     * @return integer
     */
    public function getIdFornecedor()
    {
        return $this->id_fornecedor;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Returns the value of field altura
     *
     * @return double
     */
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * Returns the value of field propriedade_prodepa
     *
     * @return integer
     */
    public function getPropriedadeProdepa()
    {
        return $this->propriedade_prodepa;
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
     * Returns the value of field data_update
     *
     * @return string
     */
    public function getDataUpdate()
    {
        return $this->data_update;
    }

    /**
     * Returns the value of field descrição tipo
     *
     * @return string
     */
    public function getTipoTorre()
    {
        return $this->Lov->descricao;
    }

    /**
     * Returns the value of field nome do Fornecedor
     *
     * @return string
     */
    public function getFornecedor()
    {
        return $this->Fornecedor->Pessoa->nome;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("torre");
        $this->hasMany('id', 'Circuitos\Models\EstacaoTelecon', 'id_torre', ['alias' => 'EstacaoTelecon']);
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_fornecedor', 'Circuitos\Models\Cliente', 'id', ['alias' => 'Fornecedor']);
        $this->belongsTo('id_tipo', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'torre';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Torre[]|Torre|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Torre|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de Torre, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Torre|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarTorre($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Torre" => "Circuitos\Models\Torre"));
        $query->columns("Torre.*");
        $query->leftJoin("Circuitos\Models\Lov", "Lov.id = Torre.id_tipo", "Lov");
        $query->leftJoin("Circuitos\Models\Cliente", "Fornecedor.id = Torre.id_fornecedor", "Fornecedor");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa.id = Fornecedor.id_pessoa", "Pessoa");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "PessoaJuridica.id = Fornecedor.id_pessoa", "PessoaJuridica");
        $query->where("Torre.excluido = 0 AND Torre.ativo = 1 AND(CONVERT(Torre.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Torre.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Torre.altura USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov.descricao USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("Torre.id");
        $query->orderBy("Torre.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
