<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;

class PhalconRoles extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     *
     * @var int
     */
    protected $ativo;

    /**
     *
     * @var int
     */
    protected $excluido;

    /**
     *
     * @var string
     */
    protected $data_update;

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param int $excluido
     */
    public function setExcluido($excluido)
    {
        $this->excluido = $excluido;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getExcluido()
    {
        return $this->excluido;
    }

    /**
     * @return int
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param int $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * @return string
     */
    public function getDataUpdate()
    {
        return $this->data_update;
    }

    /**
     * @param string $data_update
     */
    public function setDataUpdate($data_update)
    {
        $this->data_update = $data_update;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("phalcon_roles");
        $this->hasMany('name', 'Circuitos\Models\PhalconAccessList', 'roles_name', ['alias' => 'PhalconAccessList']);
        $this->hasMany('name', 'Circuitos\Models\PhalconRolesInherits', 'roles_name', ['alias' => 'PhalconRolesInherits']);
        $this->hasMany('name', 'Circuitos\Models\Usuario', 'roles_name', ['alias' => 'Usuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'phalcon_roles';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PhalconRoles[]|PhalconRoles|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PhalconRoles|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de Controle de Acesso, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return PhalconRoles|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarControleAcesso($parameters = null)
    {
        $query = new Builder();
        $query->from(array("PhalconRoles" => "Circuitos\Models\PhalconRoles"));
        $query->columns("PhalconRoles.*");

        $query->leftJoin("Circuitos\Models\PhalconAccessList", "PhalconRoles.name = PhalconAccessList.roles_name", "PhalconAccessList");

        $query->where("PhalconRoles.excluido = 0 AND (CONVERT(PhalconRoles.name USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PhalconAccessList.resources_name USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PhalconAccessList.access_name USING utf8) LIKE '%{$parameters}%')");

        $query->groupBy("PhalconRoles.name");

        $query->orderBy("PhalconRoles.name DESC");

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
