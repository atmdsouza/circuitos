<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;

class PropostaComercialServicoUnidade extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $sigla;

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
     * Method to set the value of field sigla
     *
     * @param string $sigla
     * @return $this
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;

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
     * Returns the value of field sigla
     *
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
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
        $this->setSource("proposta_comercial_servico_unidade");
        $this->hasMany('id', 'Circuitos\Models\PropostaComercialServico', 'id_proposta_comercial_servico_unidade', ['alias' => 'PropostaComercialServico']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'proposta_comercial_servico_unidade';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialServicoUnidade[]|PropostaComercialServicoUnidade|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialServicoUnidade|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de PropostaComercialServicoUnidade, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return PropostaComercialServicoUnidade|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarPropostaComercialServicoUnidade($parameters = null)
    {
        $query = new Builder();
        $query->from(array("PropostaComercialServicoUnidade" => "Circuitos\Models\PropostaComercialServicoUnidade"));
        $query->columns("PropostaComercialServicoUnidade.*");
        $query->where("PropostaComercialServicoUnidade.excluido = 0 AND (CONVERT(PropostaComercialServicoUnidade.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PropostaComercialServicoUnidade.codigo_conta_contrato USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PropostaComercialServicoUnidade.id_conta_agrupadora USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("PropostaComercialServicoUnidade.id");
        $query->orderBy("PropostaComercialServicoUnidade.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
