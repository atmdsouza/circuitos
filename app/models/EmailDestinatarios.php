<?php

namespace Circuitos\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class EmailDestinatarios extends \Phalcon\Mvc\Model
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
    protected $id_tipo_destinatario;

    /**
     *
     * @var integer
     */
    protected $id_usuario;

    /**
     *
     * @var string
     */
    protected $nome;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var integer
     */
    protected $cad_circuito;

    /**
     *
     * @var integer
     */
    protected $edt_circuito;

    /**
     *
     * @var integer
     */
    protected $del_circuito;

    /**
     *
     * @var integer
     */
    protected $mov_circuito;

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
     * Method to set the value of field id_tipo_destinatario
     *
     * @param integer $id_tipo_destinatario
     * @return $this
     */
    public function setIdTipoDestinatario($id_tipo_destinatario)
    {
        $this->id_tipo_destinatario = $id_tipo_destinatario;

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
     * Method to set the value of field nome
     *
     * @param string $nome
     * @return $this
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field cad_circuito
     *
     * @param integer $cad_circuito
     * @return $this
     */
    public function setCadCircuito($cad_circuito)
    {
        $this->cad_circuito = $cad_circuito;

        return $this;
    }

    /**
     * Method to set the value of field edt_circuito
     *
     * @param integer $edt_circuito
     * @return $this
     */
    public function setEdtCircuito($edt_circuito)
    {
        $this->edt_circuito = $edt_circuito;

        return $this;
    }

    /**
     * Method to set the value of field del_circuito
     *
     * @param integer $del_circuito
     * @return $this
     */
    public function setDelCircuito($del_circuito)
    {
        $this->del_circuito = $del_circuito;

        return $this;
    }

    /**
     * Method to set the value of field mov_circuito
     *
     * @param integer $mov_circuito
     * @return $this
     */
    public function setMovCircuito($mov_circuito)
    {
        $this->mov_circuito = $mov_circuito;

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
     * Returns the value of field id_tipo_destinatario
     *
     * @return integer
     */
    public function getIdTipoDestinatario()
    {
        return $this->id_tipo_destinatario;
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
     * Returns the value of field nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field cad_circuito
     *
     * @return integer
     */
    public function getCadCircuito()
    {
        return $this->cad_circuito;
    }

    /**
     * Returns the value of field edt_circuito
     *
     * @return integer
     */
    public function getEdtCircuito()
    {
        return $this->edt_circuito;
    }

    /**
     * Returns the value of field del_circuito
     *
     * @return integer
     */
    public function getDelCircuito()
    {
        return $this->del_circuito;
    }

    /**
     * Returns the value of field mov_circuito
     *
     * @return integer
     */
    public function getMovCircuito()
    {
        return $this->mov_circuito;
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
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("email_destinatarios");
        $this->belongsTo('id_tipo_destinatario', 'CircuitosModels\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_usuario', 'CircuitosModels\Usuario', 'id', ['alias' => 'Usuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'email_destinatarios';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmailDestinatarios[]|EmailDestinatarios|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmailDestinatarios|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
