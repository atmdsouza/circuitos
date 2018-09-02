<?php

namespace Circuitos\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class PessoaContato extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    protected $id_tipocontato;

    /**
     *
     * @var integer
     */
    protected $principal;

    /**
     *
     * @var string
     */
    protected $nome;

    /**
     *
     * @var string
     */
    protected $telefone;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var integer
     */
    protected $ativo;

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
     * Method to set the value of field id_tipocontato
     *
     * @param integer $id_tipocontato
     * @return $this
     */
    public function setIdTipocontato($id_tipocontato)
    {
        $this->id_tipocontato = $id_tipocontato;

        return $this;
    }

    /**
     * Method to set the value of field principal
     *
     * @param integer $principal
     * @return $this
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

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
     * Method to set the value of field telefone
     *
     * @param string $telefone
     * @return $this
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

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
     * Returns the value of field id_tipocontato
     *
     * @return integer
     */
    public function getIdTipocontato()
    {
        return $this->id_tipocontato;
    }

    /**
     * Returns the value of field principal
     *
     * @return integer
     */
    public function getPrincipal()
    {
        return $this->principal;
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
     * Returns the value of field telefone
     *
     * @return string
     */
    public function getTelefone()
    {
        return $this->telefone;
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
     * Returns the value of field ativo
     *
     * @return integer
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    // public function validation()
    // {
    //     $validator = new Validation();

    //     $validator->add(
    //         'email',
    //         new EmailValidator(
    //             [
    //                 'model'   => $this,
    //                 'message' => 'Please enter a correct email address',
    //             ]
    //         )
    //     );

    //     return $this->validate($validator);
    // }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("pessoa_contato");
        $this->belongsTo('id_tipocontato', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_pessoa', 'Circuitos\Models\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pessoa_contato';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaContato[]|PessoaContato|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaContato|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta com o join na tabela lov 
     *
     * @param int $id_pessoa
     * @return PessoaContato|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaCompletaLov($id_pessoa)
    {
        $query = new Builder();
        $query->from(array("PessoaContato" => "Circuitos\Models\PessoaContato"));
        $query->columns("PessoaContato.*, Lov.descricao");
        $query->join("Circuitos\Models\Lov", "Lov.id = PessoaContato.id_tipocontato", "Lov");
        $query->where("PessoaContato.id_pessoa = :id:", array("id" => $id_pessoa));
        $resultado = $query->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS);
        return $resultado;
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
            'id_tipocontato' => 'id_tipocontato',
            'principal' => 'principal',
            'nome' => 'nome',
            'telefone' => 'telefone',
            'email' => 'email',
            'ativo' => 'ativo'
        ];
    }

}
