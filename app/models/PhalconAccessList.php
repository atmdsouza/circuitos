<?php

namespace Circuitos\Models;

class PhalconAccessList extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    protected $access_name;

    /**
     *
     * @var string
     */
    protected $roles_name;

    /**
     *
     * @var string
     */
    protected $resources_name;

    /**
     * Method to set the value of field access_name
     *
     * @param string $access_name
     * @return $this
     */
    public function setAccessName($access_name)
    {
        $this->access_name = $access_name;

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
     * Method to set the value of field resources_name
     *
     * @param string $resources_name
     * @return $this
     */
    public function setResourcesName($resources_name)
    {
        $this->resources_name = $resources_name;

        return $this;
    }

    /**
     * Returns the value of field access_name
     *
     * @return string
     */
    public function getAccessName()
    {
        return $this->access_name;
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
     * Returns the value of field resources_name
     *
     * @return string
     */
    public function getResourcesName()
    {
        return $this->resources_name;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("phalcon_access_list");
        $this->hasMany('access_name', 'Circuitos\Models\PhalconResourcesAccesses', 'access_name', ['alias' => 'PhalconResourcesAccesses']);
        $this->belongsTo('resources_name', 'Circuitos\Models\PhalconResources', 'name', ['alias' => 'PhalconResources']);
        $this->belongsTo('roles_name', 'Circuitos\Models\PhalconRoles', 'name', ['alias' => 'PhalconRoles']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'phalcon_access_list';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PhalconAccessList[]|PhalconAccessList|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PhalconAccessList|\Phalcon\Mvc\Model\ResultInterface
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
            'access_name' => 'access_name',
            'roles_name' => 'roles_name',
            'resources_name' => 'resources_name'
        ];
    }

}
