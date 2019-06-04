<?php

namespace Circuitos\Models;

class PhalconRolesInherits extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    protected $roles_inherit;

    /**
     *
     * @var string
     */
    protected $roles_name;

    /**
     * Method to set the value of field roles_inherit
     *
     * @param string $roles_inherit
     * @return $this
     */
    public function setRolesInherit($roles_inherit)
    {
        $this->roles_inherit = $roles_inherit;

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
     * Returns the value of field roles_inherit
     *
     * @return string
     */
    public function getRolesInherit()
    {
        return $this->roles_inherit;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("phalcon_roles_inherits");
        $this->belongsTo('roles_name', 'Circuitos\Models\PhalconRoles', 'name', ['alias' => 'PhalconRoles']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'phalcon_roles_inherits';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PhalconRolesInherits[]|PhalconRolesInherits|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PhalconRolesInherits|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
