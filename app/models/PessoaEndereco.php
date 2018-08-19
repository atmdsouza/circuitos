<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class PessoaEndereco extends \Phalcon\Mvc\Model
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
    protected $id_tipoendereco;

    /**
     *
     * @var integer
     */
    protected $principal;

    /**
     *
     * @var string
     */
    protected $cep;

    /**
     *
     * @var string
     */
    protected $endereco;

    /**
     *
     * @var string
     */
    protected $numero;

    /**
     *
     * @var string
     */
    protected $bairro;

    /**
     *
     * @var string
     */
    protected $complemento;

    /**
     *
     * @var string
     */
    protected $cidade;

    /**
     *
     * @var string
     */
    protected $estado;

    /**
     *
     * @var string
     */
    protected $sigla_estado;

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
     * Method to set the value of field id_tipoendereco
     *
     * @param integer $id_tipoendereco
     * @return $this
     */
    public function setIdTipoendereco($id_tipoendereco)
    {
        $this->id_tipoendereco = $id_tipoendereco;

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
     * Method to set the value of field cep
     *
     * @param string $cep
     * @return $this
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Method to set the value of field endereco
     *
     * @param string $endereco
     * @return $this
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Method to set the value of field numero
     *
     * @param string $numero
     * @return $this
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Method to set the value of field bairro
     *
     * @param string $bairro
     * @return $this
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;

        return $this;
    }

    /**
     * Method to set the value of field complemento
     *
     * @param string $complemento
     * @return $this
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;

        return $this;
    }

    /**
     * Method to set the value of field cidade
     *
     * @param string $cidade
     * @return $this
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    /**
     * Method to set the value of field estado
     *
     * @param string $estado
     * @return $this
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Method to set the value of field sigla_estado
     *
     * @param string $sigla_estado
     * @return $this
     */
    public function setSiglaEstado($sigla_estado)
    {
        $this->sigla_estado = $sigla_estado;

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
     * Returns the value of field id_tipoendereco
     *
     * @return integer
     */
    public function getIdTipoendereco()
    {
        return $this->id_tipoendereco;
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
     * Returns the value of field cep
     *
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Returns the value of field endereco
     *
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Returns the value of field numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Returns the value of field bairro
     *
     * @return string
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * Returns the value of field complemento
     *
     * @return string
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Returns the value of field cidade
     *
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * Returns the value of field estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Returns the value of field sigla_estado
     *
     * @return string
     */
    public function getSiglaEstado()
    {
        return $this->sigla_estado;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("pessoa_endereco");
        $this->belongsTo('id_tipoendereco', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_pessoa', 'Circuitos\Models\Pessoa', 'id', ['alias' => 'Pessoa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pessoa_endereco';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaEndereco[]|PessoaEndereco|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PessoaEndereco|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta com o join na tabela lov 
     *
     * @param int $id_pessoa
     * @return PessoaEndereco|\Phalcon\Mvc\Model\Resultset
     */
    public static function buscaCompletaLov($id_pessoa)
    {
        $query = new Builder();
        $query->from(array("PessoaEndereco" => "Circuitos\Models\PessoaEndereco"));
        $query->columns("PessoaEndereco.*, Lov.descricao");
        $query->join("Circuitos\Models\Lov", "Lov.id = PessoaEndereco.id_tipoendereco", "Lov");
        $query->where("PessoaEndereco.id_pessoa = :id:", array("id" => $id_pessoa));
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
            'id_tipoendereco' => 'id_tipoendereco',
            'principal' => 'principal',
            'cep' => 'cep',
            'endereco' => 'endereco',
            'numero' => 'numero',
            'bairro' => 'bairro',
            'complemento' => 'complemento',
            'cidade' => 'cidade',
            'estado' => 'estado',
            'sigla_estado' => 'sigla_estado',
            'ativo' => 'ativo'
        ];
    }

}
