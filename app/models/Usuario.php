<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Util\Infra;

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
     * Returns the value of field Nome Pessoa
     *
     * @return string
     */
    public function getNomePessoaUsuario()
    {
        return $this->Pessoa->nome;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("usuario");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_usuario_criacao', ['alias' => 'Circuitos1']);
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_usuario_atualizacao', ['alias' => 'Circuitos2']);
        $this->hasMany('id', 'Circuitos\Models\Movimentos', 'id_usuario', ['alias' => 'Movimentos']);
        $this->hasOne('id_pessoa', 'Circuitos\Models\Pessoa', 'id', ['alias' => 'Pessoa']);
        $this->belongsTo('roles_name', 'Circuitos\Models\PhalconRoles', 'name', ['alias' => 'PhalconRoles']);
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
     * Consulta completa de usuarios, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Usuario|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarUsuarios($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Usuario" => "Circuitos\Models\Usuario"));
        $query->columns("Usuario.*");

        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = Usuario.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaEmail", "PessoaEmail.id_pessoa = Pessoa2.id", "PessoaEmail");

        $query->where("(CONVERT(Usuario.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Usuario.roles_name USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaEmail.email USING utf8) LIKE '%{$parameters}%')");

        $query->groupBy("Usuario.id");

        $query->orderBy("Usuario.id DESC");

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta completa de usuarios, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Usuario|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarUsuariosAtivos($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Usuario" => "Circuitos\Models\Usuario"));
        $query->columns("Usuario.*");

        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = Usuario.id_pessoa", "Pessoa2");
        $query->where("Pessoa2.excluido=0 AND Pessoa2.ativo=1 AND (CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("Usuario.id");
        $query->orderBy("Usuario.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
