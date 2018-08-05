<?php

namespace Circuitos\Models;

class Osocomon extends \Phalcon\Mvc\Model
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
    protected $id_circuitos;

    /**
     *
     * @var string
     */
    protected $osocomon;

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
     * Method to set the value of field id_circuitos
     *
     * @param integer $id_circuitos
     * @return $this
     */
    public function setIdCircuitos($id_circuitos)
    {
        $this->id_circuitos = $id_circuitos;

        return $this;
    }

    /**
     * Method to set the value of field osocomon
     *
     * @param string $osocomon
     * @return $this
     */
    public function setOsocomon($osocomon)
    {
        $this->osocomon = $osocomon;

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
     * Returns the value of field id_circuitos
     *
     * @return integer
     */
    public function getIdCircuitos()
    {
        return $this->id_circuitos;
    }

    /**
     * Returns the value of field osocomon
     *
     * @return string
     */
    public function getOsocomon()
    {
        return $this->osocomon;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("osocomon");
        $this->belongsTo('id_circuitos', 'Circuitos\Models\Circuitos', 'id', ['alias' => 'Circuitos']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'osocomon';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Osocomon[]|Osocomon|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Osocomon|\Phalcon\Mvc\Model\ResultInterface
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
            'id_circuitos' => 'id_circuitos',
            'osocomon' => 'osocomon'
        ];
    }

}
