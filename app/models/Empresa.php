<?php

namespace Circuitos\Models;

class Empresa extends \Phalcon\Mvc\Model
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
    protected $id_pessoa;

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
     * Method to set the value of field id_pessoa
     *
     * @param integer $id_pessoa
     * @return $this
     */
    public function setIdPessoa($id_pessoa)
    {
        $this->id_pessoa = $id_pessoa;

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
     * Returns the value of field id_pessoa
     *
     * @return integer
     */
    public function getIdPessoa()
    {
        return $this->id_pessoa;
    }

    /**
     * Returns the value of Nome Empresa
     *
     * @return string
     */
    public function getNomeEmpresa()
    {
        return $this->Pessoa->nome;
    }

    /**
     * Returns the value of Razão Social Empresa
     *
     * @return string
     */
    public function getRazaoEmpresa()
    {
        return $this->Pessoa->PessoaJuridica->razaosocial;
    }

    /**
     * Returns the value of Host Empresa
     *
     * @return string
     */
    public function getHostEmpresa()
    {
        return $this->EmpresaParametros->mail_host;
    }

    /**
     * Returns the value of Mail User Empresa
     *
     * @return string
     */
    public function getMailUserEmpresa()
    {
        return $this->EmpresaParametros->mail_user;
    }

    /**
     * Returns the value of Mail User Empresa
     *
     * @return string
     */
    public function getMailPswEmpresa()
    {
        return $this->EmpresaParametros->mail_passwrd;
    }

    /**
     * Returns the value of Mail SMTP Empresa
     *
     * @return string
     */
    public function getMailSmtpEmpresa()
    {
        return $this->EmpresaParametros->mail_smtpssl;
    }

    /**
     * Returns the value of Mail SMTP Empresa
     *
     * @return string
     */
    public function getMailPortEmpresa()
    {
        return $this->EmpresaParametros->mail_port;
    }

    /**
     * Returns the value of EMail Empresa
     *
     * @return string
     */
    public function getEMailEmpresa()
    {
        return $this->Pessoa->PessoaEmail[0]->email;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("empresa");
        $this->hasOne('id', 'Circuitos\Models\EmpresaParametros', 'id_empresa', ['alias' => 'EmpresaParametros']);
        $this->hasOne('id_pessoa', 'Circuitos\Models\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'empresa';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Empresa[]|Empresa|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Empresa|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
