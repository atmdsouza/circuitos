<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Util\Infra;

class PessoaEmail extends \Phalcon\Mvc\Model
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
    protected $id_tipoemail;

    /**
     *
     * @var integer
     */
    protected $principal;

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
     *
     * @var integer
     */
    protected $excluido;

    /**
     *
     * @var string
     */
    protected $data_update;

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
     * Method to set the value of field id_tipoemail
     *
     * @param integer $id_tipoemail
     * @return $this
     */
    public function setIdTipoemail($id_tipoemail)
    {
        $this->id_tipoemail = $id_tipoemail;

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
     * Returns the value of field id_tipoemail
     *
     * @return integer
     */
    public function getIdTipoemail()
    {
        return $this->id_tipoemail;
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
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Por favor, informe um endereço de e-mail correto',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * @return int
     */
    public function getExcluido()
    {
        return $this->excluido;
    }

    /**
     * @param int $excluido
     */
    public function setExcluido($excluido)
    {
        $this->excluido = $excluido;
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
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("pessoa_email");
        $this->belongsTo('id_tipoemail', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_pessoa', 'Circuitos\Models\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pessoa_email';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaEmail[]|PessoaEmail|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaEmail|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta com o join na tabela lov 
     *
     * @param int $id_pessoa
     * @return PessoaEmail|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaCompletaLov($id_pessoa)
    {
        $query = new Builder();
        $query->from(array("PessoaEmail" => "Circuitos\Models\PessoaEmail"));
        $query->columns("PessoaEmail.*, Lov.descricao");
        $query->join("Circuitos\Models\Lov", "Lov.id = PessoaEmail.id_tipoemail", "Lov");
        $query->where("PessoaEmail.id_pessoa = :id:", array("id" => $id_pessoa));
        $resultado = $query->getQuery()->execute()->setHydrateMode(Resultset::HYDRATE_ARRAYS);
        return $resultado;
    }

}
