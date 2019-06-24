<?php

namespace Circuitos\Models;

class Movimentos extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    protected $id_tipomovimento;

    /**
     *
     * @var integer
     */
    protected $id_usuario;

    /**
     *
     * @var string
     */
    protected $data_movimento;

    /**
     *
     * @var string
     */
    protected $osocomon;

    /**
     *
     * @var string
     */
    protected $valoranterior;

    /**
     *
     * @var string
     */
    protected $valoratualizado;

    /**
     *
     * @var string
     */
    protected $observacao;

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
     * Method to set the value of field id_tipomovimento
     *
     * @param integer $id_tipomovimento
     * @return $this
     */
    public function setIdTipomovimento($id_tipomovimento)
    {
        $this->id_tipomovimento = $id_tipomovimento;

        return $this;
    }

    /**
     * Method to set the value of field id_usuario
     *
     * @param integer $id_usuario
     * @return $this
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    /**
     * Method to set the value of field data_movimento
     *
     * @param string $data_movimento
     * @return $this
     */
    public function setDataMovimento($data_movimento)
    {
        $this->data_movimento = $data_movimento;

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
     * Method to set the value of field valoranterior
     *
     * @param string $valoranterior
     * @return $this
     */
    public function setValoranterior($valoranterior)
    {
        $this->valoranterior = $valoranterior;

        return $this;
    }

    /**
     * Method to set the value of field valoratualizado
     *
     * @param string $valoratualizado
     * @return $this
     */
    public function setValoratualizado($valoratualizado)
    {
        $this->valoratualizado = $valoratualizado;

        return $this;
    }

    /**
     * Method to set the value of field observacao
     *
     * @param string $observacao
     * @return $this
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

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
     * Returns the value of field id_tipomovimento
     *
     * @return integer
     */
    public function getIdTipomovimento()
    {
        return $this->id_tipomovimento;
    }

    /**
     * Returns the value of field id_usuario
     *
     * @return integer
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * Returns the value of field data_movimento
     *
     * @return string
     */
    public function getDataMovimento()
    {
        return $this->data_movimento;
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
     * Returns the value of field valoranterior
     *
     * @return string
     */
    public function getValoranterior()
    {
        return $this->valoranterior;
    }

    /**
     * Returns the value of field valoratualizado
     *
     * @return string
     */
    public function getValoratualizado()
    {
        return $this->valoratualizado;
    }

    /**
     * Returns the value of field observacao
     *
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * Returns the value of field Tipo Movimento
     *
     * @return string
     */
    public function getTipoMovimento()
    {
        return $this->Lov->descricao;
    }

    /**
     * Returns the value of field Usuario Movimento
     *
     * @return string
     */
    public function getUsuarioMovimento()
    {
        return $this->Usuario->Pessoa->nome;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("movimentos");
        $this->belongsTo('id_circuitos', 'Circuitos\Models\Circuitos', 'id', ['alias' => 'Circuitos']);
        $this->belongsTo('id_tipomovimento', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_usuario', 'Circuitos\Models\Usuario', 'id', ['alias' => 'Usuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'movimentos';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Movimentos[]|Movimentos|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Movimentos|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
