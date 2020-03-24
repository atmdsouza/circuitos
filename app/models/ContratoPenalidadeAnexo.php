<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoPenalidadeAnexo extends \Phalcon\Mvc\Model
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
    protected $id_contrato_penalidade;

    /**
     *
     * @var integer
     */
    protected $id_anexo;

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
     * Method to set the value of field id_contrato_penalidade
     *
     * @param integer $id_contrato_penalidade
     * @return $this
     */
    public function setIdContratoPenalidade($id_contrato_penalidade)
    {
        $this->id_contrato_penalidade = $id_contrato_penalidade;

        return $this;
    }

    /**
     * Method to set the value of field id_anexo
     *
     * @param integer $id_anexo
     * @return $this
     */
    public function setIdAnexo($id_anexo)
    {
        $this->id_anexo = $id_anexo;

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
     * Returns the value of field id_contrato_penalidade
     *
     * @return integer
     */
    public function getIdContratoPenalidade()
    {
        return $this->id_contrato_penalidade;
    }

    /**
     * Returns the value of field id_anexo
     *
     * @return integer
     */
    public function getIdAnexo()
    {
        return $this->id_anexo;
    }

    /**
     * Returns the value of field url
     *
     * @return string
     */
    public function getUrlAnexo()
    {
        return $this->Anexos->url;
    }

    /**
     * Returns the value of field descricao
     *
     * @return string
     */
    public function getDescricaoAnexo()
    {
        return $this->Anexos->descricao;
    }

    /**
     * Returns the value of field data criação
     *
     * @return string
     */
    public function getDataCriacaoAnexo()
    {
        return $this->Anexos->data_criacao;
    }

    /**
     * Returns the value of field descricao tipo anexo
     *
     * @return string
     */
    public function getDescricaoTipoAnexo()
    {
        return $this->Anexos->Lov->descricao;
    }

    /**
     * Returns the value of field id_tipo_anexo
     *
     * @return integer
     */
    public function getIdTipoAnexo()
    {
        return $this->Anexos->id_tipo_anexo;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource('contrato_penalidade_anexo');
        $this->belongsTo('id_anexo', 'Circuitos\Models\Anexos', 'id', ['alias' => 'Anexos']);
        $this->belongsTo('id_contrato_penalidade', 'Circuitos\Models\ContratoPenalidade', 'id', ['alias' => 'ContratoPenalidade']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_penalidade_anexo';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoPenalidadeAnexo[]|ContratoPenalidadeAnexo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoPenalidadeAnexo|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
