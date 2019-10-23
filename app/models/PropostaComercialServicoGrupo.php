<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;

class PropostaComercialServicoGrupo extends \Phalcon\Mvc\Model
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
    protected $id_grupo_pai;

    /**
     *
     * @var string
     */
    protected $codigo_legado;

    /**
     *
     * @var string
     */
    protected $codigo_contabil;

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
     * Method to set the value of field codigo_legado
     *
     * @param string $codigo_legado
     * @return $this
     */
    public function setCodigoLegado($codigo_legado)
    {
        $this->codigo_legado = $codigo_legado;

        return $this;
    }

    /**
     * Method to set the value of field codigo_contabil
     *
     * @param string $codigo_contabil
     * @return $this
     */
    public function setCodigoContabil($codigo_contabil)
    {
        $this->codigo_contabil = $codigo_contabil;

        return $this;
    }

    /**
     * Method to set the value of field descricao
     *
     * @param string $descricao
     * @return $this
     */
    public function setdescricao($descricao)
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
     * Returns the value of field codigo_legado
     *
     * @return string
     */
    public function getCodigoLegado()
    {
        return $this->codigo_legado;
    }

    /**
     * Returns the value of field codigo_contabil
     *
     * @return string
     */
    public function getCodigoContabil()
    {
        return $this->codigo_contabil;
    }

    /**
     * Returns the value of field descricao
     *
     * @return string
     */
    public function getdescricao()
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
     * @return int
     */
    public function getIdGrupoPai()
    {
        return $this->id_grupo_pai;
    }

    /**
     * @param int $id_grupo_pai
     */
    public function setIdGrupoPai($id_grupo_pai)
    {
        $this->id_grupo_pai = $id_grupo_pai;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("proposta_comercial_servico_grupo");
        $this->hasMany('id', 'Circuitos\Models\PropostaComercialServico', 'id_proposta_comercial_servico_grupo', ['alias' => 'PropostaComercialServico']);
        $this->hasMany('id', 'Circuitos\Models\PropostaComercialServico', 'id_grupo_pai', ['alias' => 'PropostaComercialServico']);
        $this->belongsTo('id_grupo_pai', 'Circuitos\Models\PropostaComercialServico', 'id', ['alias' => 'PropostaComercialServico']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'proposta_comercial_servico_grupo';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialServicoGrupo[]|PropostaComercialServicoGrupo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialServicoGrupo|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de PropostaComercialServicoGrupo, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return PropostaComercialServicoGrupo|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarPropostaComercialServicoGrupo($parameters = null)
    {
        $query = new Builder();
        $query->from(array("PropostaComercialServicoGrupo" => "Circuitos\Models\PropostaComercialServicoGrupo"));
        $query->columns("PropostaComercialServicoGrupo.*");
        $query->where("PropostaComercialServicoGrupo.excluido = 0 AND (CONVERT(PropostaComercialServicoGrupo.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PropostaComercialServicoGrupo.codigo_legado USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PropostaComercialServicoGrupo.codigo_contabil USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PropostaComercialServicoGrupo.descricao USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("PropostaComercialServicoGrupo.id");
        $query->orderBy("PropostaComercialServicoGrupo.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
