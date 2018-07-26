<?php

class EndEstado extends \Phalcon\Mvc\Model
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
    protected $uf;

    /**
     *
     * @var string
     */
    protected $estado;

    /**
     *
     * @var string
     */
    protected $cod_ibge;

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
     * Method to set the value of field uf
     *
     * @param string $uf
     * @return $this
     */
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Method to set the value of field estado
     *
     * @param string $estado
     * @return $this
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Method to set the value of field cod_ibge
     *
     * @param string $cod_ibge
     * @return $this
     */
    public function setCodIbge($cod_ibge)
    {
        $this->cod_ibge = $cod_ibge;

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
     * Returns the value of field uf
     *
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * Returns the value of field estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Returns the value of field cod_ibge
     *
     * @return string
     */
    public function getCodIbge()
    {
        return $this->cod_ibge;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("end_estado");
        $this->hasMany('uf', 'connecta\EndCidade', 'uf', ['alias' => 'EndCidade']);
        $this->hasMany('uf', 'connecta\EndFaixaBairros', 'uf', ['alias' => 'EndFaixaBairros']);
        $this->hasMany('uf', 'connecta\EndFaixaCidades', 'uf', ['alias' => 'EndFaixaCidades']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'end_estado';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndEstado[]|EndEstado|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EndEstado|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
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
            'uf' => 'uf',
            'estado' => 'estado',
            'cod_ibge' => 'cod_ibge'
        ];
    }

}
