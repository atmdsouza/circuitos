<?php

namespace Circuitos\Models;

class PropostaComercialValorMensal extends \Phalcon\Mvc\Model
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
    protected $id_proposta_comercial;

    /**
     *
     * @var double
     */
    protected $jan;

    /**
     *
     * @var double
     */
    protected $fev;

    /**
     *
     * @var double
     */
    protected $mar;

    /**
     *
     * @var double
     */
    protected $abr;

    /**
     *
     * @var double
     */
    protected $mai;

    /**
     *
     * @var double
     */
    protected $jun;

    /**
     *
     * @var double
     */
    protected $jul;

    /**
     *
     * @var double
     */
    protected $ago;

    /**
     *
     * @var double
     */
    protected $set;

    /**
     *
     * @var double
     */
    protected $out;

    /**
     *
     * @var double
     */
    protected $nov;

    /**
     *
     * @var double
     */
    protected $dez;

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
     * Method to set the value of field id_proposta_comercial
     *
     * @param integer $id_proposta_comercial
     * @return $this
     */
    public function setIdPropostaComercial($id_proposta_comercial)
    {
        $this->id_proposta_comercial = $id_proposta_comercial;

        return $this;
    }

    /**
     * Method to set the value of field jan
     *
     * @param double $jan
     * @return $this
     */
    public function setJan($jan)
    {
        $this->jan = $jan;

        return $this;
    }

    /**
     * Method to set the value of field fev
     *
     * @param double $fev
     * @return $this
     */
    public function setFev($fev)
    {
        $this->fev = $fev;

        return $this;
    }

    /**
     * Method to set the value of field mar
     *
     * @param double $mar
     * @return $this
     */
    public function setMar($mar)
    {
        $this->mar = $mar;

        return $this;
    }

    /**
     * Method to set the value of field abr
     *
     * @param double $abr
     * @return $this
     */
    public function setAbr($abr)
    {
        $this->abr = $abr;

        return $this;
    }

    /**
     * Method to set the value of field mai
     *
     * @param double $mai
     * @return $this
     */
    public function setMai($mai)
    {
        $this->mai = $mai;

        return $this;
    }

    /**
     * Method to set the value of field jun
     *
     * @param double $jun
     * @return $this
     */
    public function setJun($jun)
    {
        $this->jun = $jun;

        return $this;
    }

    /**
     * Method to set the value of field jul
     *
     * @param double $jul
     * @return $this
     */
    public function setJul($jul)
    {
        $this->jul = $jul;

        return $this;
    }

    /**
     * Method to set the value of field ago
     *
     * @param double $ago
     * @return $this
     */
    public function setAgo($ago)
    {
        $this->ago = $ago;

        return $this;
    }

    /**
     * Method to set the value of field set
     *
     * @param double $set
     * @return $this
     */
    public function setSet($set)
    {
        $this->set = $set;

        return $this;
    }

    /**
     * Method to set the value of field out
     *
     * @param double $out
     * @return $this
     */
    public function setOut($out)
    {
        $this->out = $out;

        return $this;
    }

    /**
     * Method to set the value of field nov
     *
     * @param double $nov
     * @return $this
     */
    public function setNov($nov)
    {
        $this->nov = $nov;

        return $this;
    }

    /**
     * Method to set the value of field dez
     *
     * @param double $dez
     * @return $this
     */
    public function setDez($dez)
    {
        $this->dez = $dez;

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
     * Returns the value of field id_proposta_comercial
     *
     * @return integer
     */
    public function getIdPropostaComercial()
    {
        return $this->id_proposta_comercial;
    }

    /**
     * Returns the value of field jan
     *
     * @return double
     */
    public function getJan()
    {
        return $this->jan;
    }

    /**
     * Returns the value of field fev
     *
     * @return double
     */
    public function getFev()
    {
        return $this->fev;
    }

    /**
     * Returns the value of field mar
     *
     * @return double
     */
    public function getMar()
    {
        return $this->mar;
    }

    /**
     * Returns the value of field abr
     *
     * @return double
     */
    public function getAbr()
    {
        return $this->abr;
    }

    /**
     * Returns the value of field mai
     *
     * @return double
     */
    public function getMai()
    {
        return $this->mai;
    }

    /**
     * Returns the value of field jun
     *
     * @return double
     */
    public function getJun()
    {
        return $this->jun;
    }

    /**
     * Returns the value of field jul
     *
     * @return double
     */
    public function getJul()
    {
        return $this->jul;
    }

    /**
     * Returns the value of field ago
     *
     * @return double
     */
    public function getAgo()
    {
        return $this->ago;
    }

    /**
     * Returns the value of field set
     *
     * @return double
     */
    public function getSet()
    {
        return $this->set;
    }

    /**
     * Returns the value of field out
     *
     * @return double
     */
    public function getOut()
    {
        return $this->out;
    }

    /**
     * Returns the value of field nov
     *
     * @return double
     */
    public function getNov()
    {
        return $this->nov;
    }

    /**
     * Returns the value of field dez
     *
     * @return double
     */
    public function getDez()
    {
        return $this->dez;
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
        $this->setSource("proposta_comercial_valor_mensal");
        $this->belongsTo('id_proposta_comercial', 'Circuitos\Models\PropostaComercial', 'id', ['alias' => 'PropostaComercial']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'proposta_comercial_valor_mensal';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialValorMensal[]|PropostaComercialValorMensal|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercialValorMensal|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
