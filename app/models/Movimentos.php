<?php

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
    protected $id_statusmovimento;

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
    protected $data_atualizacao;

    /**
     *
     * @var string
     */
    protected $observacao;

    /**
     *
     * @var string
     */
    protected $anexo;

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
     * Method to set the value of field id_statusmovimento
     *
     * @param integer $id_statusmovimento
     * @return $this
     */
    public function setIdStatusmovimento($id_statusmovimento)
    {
        $this->id_statusmovimento = $id_statusmovimento;

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
     * Method to set the value of field data_atualizacao
     *
     * @param string $data_atualizacao
     * @return $this
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;

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
     * Method to set the value of field anexo
     *
     * @param string $anexo
     * @return $this
     */
    public function setAnexo($anexo)
    {
        $this->anexo = $anexo;

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
     * Returns the value of field id_statusmovimento
     *
     * @return integer
     */
    public function getIdStatusmovimento()
    {
        return $this->id_statusmovimento;
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
     * Returns the value of field data_atualizacao
     *
     * @return string
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
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
     * Returns the value of field anexo
     *
     * @return string
     */
    public function getAnexo()
    {
        return $this->anexo;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("movimentos");
        $this->belongsTo('id_circuitos', 'connecta\Circuitos', 'id', ['alias' => 'Circuitos']);
        $this->belongsTo('id_tipomovimento', 'connecta\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_statusmovimento', 'connecta\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_usuario', 'connecta\Usuario', 'id', ['alias' => 'Usuario']);
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
            'id_tipomovimento' => 'id_tipomovimento',
            'id_statusmovimento' => 'id_statusmovimento',
            'id_usuario' => 'id_usuario',
            'data_movimento' => 'data_movimento',
            'data_atualizacao' => 'data_atualizacao',
            'observacao' => 'observacao',
            'anexo' => 'anexo'
        ];
    }

}
