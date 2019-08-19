<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class SetEquipamento extends \Phalcon\Mvc\Model
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
    protected $id_contrato;

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
     * Returns the value of field id_contrato
     *
     * @return integer
     */
    public function getIdContrato()
    {
        return $this->id_contrato;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("set_equipamento");
        $this->hasMany('id', 'Circuitos\Models\EstacaoTelecon', 'id_set_equipamento', ['alias' => 'EstacaoTelecon']);
        $this->hasMany('id', 'Circuitos\Models\SetEquipamentoComponentes', 'id_set_equipamento', ['alias' => 'SetEquipamentoComponentes']);
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'set_equipamento';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SetEquipamento[]|SetEquipamento|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SetEquipamento|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de SetEquipamento, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return SetEquipamento|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarSetEquipamento($parameters = null)
    {
        $query = new Builder();
        $query->from(array("SetEquipamento" => "Circuitos\Models\SetEquipamento"));
        $query->columns("SetEquipamento.*");
        $query->leftJoin("Circuitos\Models\SetEquipamentoComponentes", "SetEquipamentoComponentes.id_set_equipamento = SetEquipamento.id", "SetEquipamentoComponentes");
        $query->leftJoin("Circuitos\Models\Cliente", "Fornecedor.id = SetEquipamentoComponentes.id_fornecedor", "Fornecedor");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa.id = Fornecedor.id_pessoa", "Pessoa");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "PessoaJuridica.id = Pessoa.id", "PessoaJuridica");
        $query->where("SetEquipamento.excluido = 0 AND (CONVERT(SetEquipamento.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(SetEquipamento.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica.razaosocial USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("SetEquipamento.id");
        $query->orderBy("SetEquipamento.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
