<?php

namespace Circuitos\Models;

class EmpresaParametros extends \Phalcon\Mvc\Model
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
    protected $id_empresa;

    /**
     *
     * @var string
     */
    protected $mail_host;

    /**
     *
     * @var string
     */
    protected $mail_smtp;

    /**
     *
     * @var string
     */
    protected $mail_user;

    /**
     *
     * @var string
     */
    protected $mail_passwrd;

    /**
     *
     * @var string
     */
    protected $mail_port;

    /**
     *
     * @var string
     */
    protected $mail_smtpssl;

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
     * Method to set the value of field id_empresa
     *
     * @param integer $id_empresa
     * @return $this
     */
    public function setIdEmpresa($id_empresa)
    {
        $this->id_empresa = $id_empresa;

        return $this;
    }

    /**
     * Method to set the value of field mail_host
     *
     * @param string $mail_host
     * @return $this
     */
    public function setMailHost($mail_host)
    {
        $this->mail_host = $mail_host;

        return $this;
    }

    /**
     * Method to set the value of field mail_smtp
     *
     * @param string $mail_smtp
     * @return $this
     */
    public function setMailSmtp($mail_smtp)
    {
        $this->mail_smtp = $mail_smtp;

        return $this;
    }

    /**
     * Method to set the value of field mail_user
     *
     * @param string $mail_user
     * @return $this
     */
    public function setMailUser($mail_user)
    {
        $this->mail_user = $mail_user;

        return $this;
    }

    /**
     * Method to set the value of field mail_passwrd
     *
     * @param string $mail_passwrd
     * @return $this
     */
    public function setMailPasswrd($mail_passwrd)
    {
        $this->mail_passwrd = $mail_passwrd;

        return $this;
    }

    /**
     * Method to set the value of field mail_port
     *
     * @param string $mail_port
     * @return $this
     */
    public function setMailPort($mail_port)
    {
        $this->mail_port = $mail_port;

        return $this;
    }

    /**
     * Method to set the value of field mail_smtpssl
     *
     * @param string $mail_smtpssl
     * @return $this
     */
    public function setMailSmtpssl($mail_smtpssl)
    {
        $this->mail_smtpssl = $mail_smtpssl;

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
     * Returns the value of field id_empresa
     *
     * @return integer
     */
    public function getIdEmpresa()
    {
        return $this->id_empresa;
    }

    /**
     * Returns the value of field mail_host
     *
     * @return string
     */
    public function getMailHost()
    {
        return $this->mail_host;
    }

    /**
     * Returns the value of field mail_smtp
     *
     * @return string
     */
    public function getMailSmtp()
    {
        return $this->mail_smtp;
    }

    /**
     * Returns the value of field mail_user
     *
     * @return string
     */
    public function getMailUser()
    {
        return $this->mail_user;
    }

    /**
     * Returns the value of field mail_passwrd
     *
     * @return string
     */
    public function getMailPasswrd()
    {
        return $this->mail_passwrd;
    }

    /**
     * Returns the value of field mail_port
     *
     * @return string
     */
    public function getMailPort()
    {
        return $this->mail_port;
    }

    /**
     * Returns the value of field mail_smtpssl
     *
     * @return string
     */
    public function getMailSmtpssl()
    {
        return $this->mail_smtpssl;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("empresa_parametros");
        $this->hasOne('id_empresa', 'Circuitos\Models\Empresa', 'id', ['alias' => 'Empresa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'empresa_parametros';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmpresaParametros[]|EmpresaParametros|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmpresaParametros|\Phalcon\Mvc\Model\ResultInterface
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
            'id_empresa' => 'id_empresa',
            'mail_host' => 'mail_host',
            'mail_smtp' => 'mail_smtp',
            'mail_user' => 'mail_user',
            'mail_passwrd' => 'mail_passwrd',
            'mail_port' => 'mail_port',
            'mail_smtpssl' => 'mail_smtpssl'
        ];
    }

}
