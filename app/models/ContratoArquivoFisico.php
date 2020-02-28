<?php

namespace Circuitos\Models;

use Util\Infra;

class ContratoArquivoFisico extends \Phalcon\Mvc\Model
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
    protected $corredor;

    /**
     *
     * @var string
     */
    protected $armario;

    /**
     *
     * @var string
     */
    protected $prateleira;

    /**
     *
     * @var string
     */
    protected $codigo;

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
     * Method to set the value of field corredor
     *
     * @param string $corredor
     * @return $this
     */
    public function setCorredor($corredor)
    {
        $this->corredor = $corredor;

        return $this;
    }

    /**
     * Method to set the value of field armario
     *
     * @param string $armario
     * @return $this
     */
    public function setArmario($armario)
    {
        $this->armario = $armario;

        return $this;
    }

    /**
     * Method to set the value of field prateleira
     *
     * @param string $prateleira
     * @return $this
     */
    public function setPrateleira($prateleira)
    {
        $this->prateleira = $prateleira;

        return $this;
    }

    /**
     * Method to set the value of field codigo
     *
     * @param string $codigo
     * @return $this
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

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
     * Returns the value of field corredor
     *
     * @return string
     */
    public function getCorredor()
    {
        return $this->corredor;
    }

    /**
     * Returns the value of field armario
     *
     * @return string
     */
    public function getArmario()
    {
        return $this->armario;
    }

    /**
     * Returns the value of field prateleira
     *
     * @return string
     */
    public function getPrateleira()
    {
        return $this->prateleira;
    }

    /**
     * Returns the value of field codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
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
        $this->setSource("contrato_arquivo_fisico");
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_arquivo_fisico';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoArquivoFisico[]|ContratoArquivoFisico|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoArquivoFisico|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
