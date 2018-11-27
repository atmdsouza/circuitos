<?php

namespace Circuitos\Models;

class Lov extends \Phalcon\Mvc\Model
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
    protected $tipo;

    /**
     *
     * @var string
     */
    protected $descricao;

    /**
     *
     * @var string
     */
    protected $codigoespecifico;

    /**
     *
     * @var string
     */
    protected $valor;

    /**
     *
     * @var string
     */
    protected $duracao;

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
     * Method to set the value of field tipo
     *
     * @param integer $tipo
     * @return $this
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Method to set the value of field descricao
     *
     * @param string $descricao
     * @return $this
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Method to set the value of field codigoespecifico
     *
     * @param string $codigoespecifico
     * @return $this
     */
    public function setCodigoespecifico($codigoespecifico)
    {
        $this->codigoespecifico = $codigoespecifico;

        return $this;
    }

    /**
     * Method to set the value of field valor
     *
     * @param string $valor
     * @return $this
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Method to set the value of field duracao
     *
     * @param string $duracao
     * @return $this
     */
    public function setDuracao($duracao)
    {
        $this->duracao = $duracao;

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
     * Returns the value of field tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Returns the value of field descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Returns the value of field codigoespecifico
     *
     * @return string
     */
    public function getCodigoespecifico()
    {
        return $this->codigoespecifico;
    }

    /**
     * Returns the value of field valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Returns the value of field duracao
     *
     * @return string
     */
    public function getDuracao()
    {
        return $this->duracao;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("lov");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_contrato', ['alias' => 'Circuitos']);
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_status', ['alias' => 'Circuitos']);
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_cluster', ['alias' => 'Circuitos']);
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_tipounidade', ['alias' => 'Circuitos']);
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_funcao', ['alias' => 'Circuitos']);
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_enlace', ['alias' => 'Circuitos']);
        $this->hasMany('id', 'Circuitos\Models\Cliente', 'id_tipocliente', ['alias' => 'Cliente']);
        $this->hasMany('id', 'Circuitos\Models\Movimentos', 'id_tipomovimento', ['alias' => 'Movimentos']);
        $this->hasMany('id', 'Circuitos\Models\Movimentos', 'id_statusmovimento', ['alias' => 'Movimentos']);
        $this->hasMany('id', 'Circuitos\Models\PessoaContato', 'id_tipocontato', ['alias' => 'PessoaContato']);
        $this->hasMany('id', 'Circuitos\Models\PessoaEmail', 'id_tipoemail', ['alias' => 'PessoaEmail']);
        $this->hasMany('id', 'Circuitos\Models\PessoaEndereco', 'id_tipoendereco', ['alias' => 'PessoaEndereco']);
        $this->hasMany('id', 'Circuitos\Models\PessoaFisica', 'id_sexo', ['alias' => 'PessoaFisica']);
        $this->hasMany('id', 'Circuitos\Models\PessoaJuridica', 'id_tipoesfera', ['alias' => 'PessoaJuridica']);
        $this->hasMany('id', 'Circuitos\Models\PessoaJuridica', 'id_setor', ['alias' => 'PessoaJuridica']);
        $this->hasMany('id', 'Circuitos\Models\PessoaTelefone', 'id_tipotelefone', ['alias' => 'PessoaTelefone']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'lov';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lov[]|Lov|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lov|\Phalcon\Mvc\Model\ResultInterface
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
            'tipo' => 'tipo',
            'descricao' => 'descricao',
            'codigoespecifico' => 'codigoespecifico',
            'valor' => 'valor',
            'duracao' => 'duracao'
        ];
    }

}
