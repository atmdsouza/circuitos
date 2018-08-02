<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Pessoa;

class Usuario extends \Phalcon\Mvc\Model
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
     *
     * @var string
     */
    protected $roles_name;

    /**
     *
     * @var string
     */
    protected $login;

    /**
     *
     * @var string
     */
    protected $senha;

    /**
     *
     * @var string
     */
    protected $data_ultimoacesso;

    /**
     *
     * @var string
     */
    protected $primeiroacesso;

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
     * Method to set the value of field roles_name
     *
     * @param string $roles_name
     * @return $this
     */
    public function setRolesName($roles_name)
    {
        $this->roles_name = $roles_name;

        return $this;
    }

    /**
     * Method to set the value of field login
     *
     * @param string $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Method to set the value of field senha
     *
     * @param string $senha
     * @return $this
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }

    /**
     * Method to set the value of field data_ultimoacesso
     *
     * @param string $data_ultimoacesso
     * @return $this
     */
    public function setDataUltimoacesso($data_ultimoacesso)
    {
        $this->data_ultimoacesso = $data_ultimoacesso;

        return $this;
    }

    /**
     * Method to set the value of field primeiroacesso
     *
     * @param string $primeiroacesso
     * @return $this
     */
    public function setPrimeiroacesso($primeiroacesso)
    {
        $this->primeiroacesso = $primeiroacesso;

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
     * Returns the value of field roles_name
     *
     * @return string
     */
    public function getRolesName()
    {
        return $this->roles_name;
    }

    /**
     * Returns the value of field login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Returns the value of field senha
     *
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Returns the value of field data_ultimoacesso
     *
     * @return string
     */
    public function getDataUltimoacesso()
    {
        return $this->data_ultimoacesso;
    }

    /**
     * Returns the value of field primeiroacesso
     *
     * @return string
     */
    public function getPrimeiroacesso()
    {
        return $this->primeiroacesso;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("usuario");
        $this->hasMany('id', Circuitos, 'id_usuario_criacao', ['alias' => 'Circuitos1']);
        $this->hasMany('id', Circuitos, 'id_usuario_atualizacao', ['alias' => 'Circuitos2']);
        $this->hasMany('id', Movimentos, 'id_usuario', ['alias' => 'Movimentos']);
        $this->hasOne('id_pessoa', Pessoa, 'id', ['alias' => 'Pessoa']);
        $this->belongsTo('roles_name', PhalconRoles, 'name', ['alias' => 'PhalconRoles']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuario';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuario[]|Usuario|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuario|\Phalcon\Mvc\Model\ResultInterface
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
            'id_pessoa' => 'id_pessoa',
            'roles_name' => 'roles_name',
            'login' => 'login',
            'senha' => 'senha',
            'data_ultimoacesso' => 'data_ultimoacesso',
            'primeiroacesso' => 'primeiroacesso'
        ];
    }

}
