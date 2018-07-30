<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class PessoaFisica extends \Phalcon\Mvc\Model
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
    protected $id_sexo;

    /**
     *
     * @var string
     */
    protected $cpf;

    /**
     *
     * @var string
     */
    protected $rg;

    /**
     *
     * @var string
     */
    protected $datanasc;

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
     * Method to set the value of field id_sexo
     *
     * @param integer $id_sexo
     * @return $this
     */
    public function setIdSexo($id_sexo)
    {
        $this->id_sexo = $id_sexo;

        return $this;
    }

    /**
     * Method to set the value of field cpf
     *
     * @param string $cpf
     * @return $this
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Method to set the value of field rg
     *
     * @param string $rg
     * @return $this
     */
    public function setRg($rg)
    {
        $this->rg = $rg;

        return $this;
    }

    /**
     * Method to set the value of field datanasc
     *
     * @param string $datanasc
     * @return $this
     */
    public function setDatanasc($datanasc)
    {
        $this->datanasc = $datanasc;

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
     * Returns the value of field id_sexo
     *
     * @return integer
     */
    public function getIdSexo()
    {
        return $this->id_sexo;
    }

    /**
     * Returns the value of field cpf
     *
     * @return string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Returns the value of field rg
     *
     * @return string
     */
    public function getRg()
    {
        return $this->rg;
    }

    /**
     * Returns the value of field datanasc
     *
     * @return string
     */
    public function getDatanasc()
    {
        return $this->datanasc;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("pessoa_fisica");
        $this->belongsTo('id_sexo', 'connecta\Lov', 'id', ['alias' => 'Lov']);
        $this->hasOne('id', 'connecta\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pessoa_fisica';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaFisica[]|PessoaFisica|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaFisica|\Phalcon\Mvc\Model\ResultInterface
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
            'id_sexo' => 'id_sexo',
            'cpf' => 'cpf',
            'rg' => 'rg',
            'datanasc' => 'datanasc'
        ];
    }

}
