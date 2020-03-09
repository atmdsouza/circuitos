<?php

namespace Circuitos\Models;

use Util\Infra;

class Anexos extends \Phalcon\Mvc\Model
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
    protected $id_tipo_anexo;

    /**
     *
     * @var string
     */
    protected $data_criacao;

    /**
     *
     * @var string
     */
    protected $descricao;

    /**
     *
     * @var string
     */
    protected $url;

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
     * Method to set the value of field id_tipo_anexo
     *
     * @param integer $id_tipo_anexo
     * @return $this
     */
    public function setIdTipoAnexo($id_tipo_anexo)
    {
        $this->id_tipo_anexo = $id_tipo_anexo;

        return $this;
    }

    /**
     * Method to set the value of field data_criacao
     *
     * @param string $data_criacao
     * @return $this
     */
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;

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
     * Method to set the value of field url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

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
     * Returns the value of field id_tipo_anexo
     *
     * @return integer
     */
    public function getIdTipoAnexo()
    {
        return $this->id_tipo_anexo;
    }

    /**
     * Returns the value of field data_criacao
     *
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
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
     * Returns the value of field url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("anexos");
        $this->hasOne('id', 'Circuitos\Models\ContratoFinanceiroNota', 'id_anexo', ['alias' => 'ContratoFinanceiroNota']);
        $this->hasMany('id', 'Circuitos\Models\CircuitosAnexo', 'id_anexo', ['alias' => 'CircuitosAnexo']);
        $this->hasMany('id', 'Circuitos\Models\ContratoFinanceiroNotaAnexo', 'id_anexo', ['alias' => 'ContratoAcompanhamentoFinanceiroNotaAnexo']);
        $this->hasMany('id', 'Circuitos\Models\ContratoAnexo', 'id_anexo', ['alias' => 'ContratoAnexo']);
        $this->hasMany('id', 'Circuitos\Models\ContratoNaoConformidadeAnexo', 'id_anexo', ['alias' => 'ContratoNaoConformidadeAnexo']);
        $this->hasMany('id', 'Circuitos\Models\PropostaComercialAnexo', 'id_anexo', ['alias' => 'PropostaComercialAnexo']);
        $this->belongsTo('id_tipo_anexo', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'anexos';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Anexos[]|Anexos|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Anexos|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
