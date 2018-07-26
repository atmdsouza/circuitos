<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class PessoaJuridica extends \Phalcon\Mvc\Model
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
    protected $id_tipoesfera;

    /**
     *
     * @var integer
     */
    protected $id_setor;

    /**
     *
     * @var string
     */
    protected $cnpj;

    /**
     *
     * @var string
     */
    protected $razaosocial;

    /**
     *
     * @var string
     */
    protected $inscricaoestadual;

    /**
     *
     * @var string
     */
    protected $inscricaomunicipal;

    /**
     *
     * @var string
     */
    protected $datafund;

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
     * Method to set the value of field id_tipoesfera
     *
     * @param integer $id_tipoesfera
     * @return $this
     */
    public function setIdTipoesfera($id_tipoesfera)
    {
        $this->id_tipoesfera = $id_tipoesfera;

        return $this;
    }

    /**
     * Method to set the value of field id_setor
     *
     * @param integer $id_setor
     * @return $this
     */
    public function setIdSetor($id_setor)
    {
        $this->id_setor = $id_setor;

        return $this;
    }

    /**
     * Method to set the value of field cnpj
     *
     * @param string $cnpj
     * @return $this
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * Method to set the value of field razaosocial
     *
     * @param string $razaosocial
     * @return $this
     */
    public function setRazaosocial($razaosocial)
    {
        $this->razaosocial = $razaosocial;

        return $this;
    }

    /**
     * Method to set the value of field inscricaoestadual
     *
     * @param string $inscricaoestadual
     * @return $this
     */
    public function setInscricaoestadual($inscricaoestadual)
    {
        $this->inscricaoestadual = $inscricaoestadual;

        return $this;
    }

    /**
     * Method to set the value of field inscricaomunicipal
     *
     * @param string $inscricaomunicipal
     * @return $this
     */
    public function setInscricaomunicipal($inscricaomunicipal)
    {
        $this->inscricaomunicipal = $inscricaomunicipal;

        return $this;
    }

    /**
     * Method to set the value of field datafund
     *
     * @param string $datafund
     * @return $this
     */
    public function setDatafund($datafund)
    {
        $this->datafund = $datafund;

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
     * Returns the value of field id_tipoesfera
     *
     * @return integer
     */
    public function getIdTipoesfera()
    {
        return $this->id_tipoesfera;
    }

    /**
     * Returns the value of field id_setor
     *
     * @return integer
     */
    public function getIdSetor()
    {
        return $this->id_setor;
    }

    /**
     * Returns the value of field cnpj
     *
     * @return string
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * Returns the value of field razaosocial
     *
     * @return string
     */
    public function getRazaosocial()
    {
        return $this->razaosocial;
    }

    /**
     * Returns the value of field inscricaoestadual
     *
     * @return string
     */
    public function getInscricaoestadual()
    {
        return $this->inscricaoestadual;
    }

    /**
     * Returns the value of field inscricaomunicipal
     *
     * @return string
     */
    public function getInscricaomunicipal()
    {
        return $this->inscricaomunicipal;
    }

    /**
     * Returns the value of field datafund
     *
     * @return string
     */
    public function getDatafund()
    {
        return $this->datafund;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("pessoa_juridica");
        $this->belongsTo('id_tipoesfera', 'connecta\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_setor', 'connecta\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id', 'connecta\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pessoa_juridica';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaJuridica[]|PessoaJuridica|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaJuridica|\Phalcon\Mvc\Model\ResultInterface
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
            'id_tipoesfera' => 'id_tipoesfera',
            'id_setor' => 'id_setor',
            'cnpj' => 'cnpj',
            'razaosocial' => 'razaosocial',
            'inscricaoestadual' => 'inscricaoestadual',
            'inscricaomunicipal' => 'inscricaomunicipal',
            'datafund' => 'datafund'
        ];
    }

}
