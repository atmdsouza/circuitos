<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class Equipamento extends \Phalcon\Mvc\Model
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
    protected $id_fabricante;

    /**
     *
     * @var integer
     */
    protected $id_modelo;

    /**
     *
     * @var integer
     */
    protected $id_tipoequipamento;

    /**
     *
     * @var string
     */
    protected $nome;

    /**
     *
     * @var string
     */
    protected $numserie;

    /**
     *
     * @var string
     */
    protected $numpatrimonio;

    /**
     *
     * @var string
     */
    protected $descricao;

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
     * Method to set the value of field id_fabricante
     *
     * @param integer $id_fabricante
     * @return $this
     */
    public function setIdFabricante($id_fabricante)
    {
        $this->id_fabricante = $id_fabricante;

        return $this;
    }

    /**
     * Method to set the value of field id_modelo
     *
     * @param integer $id_modelo
     * @return $this
     */
    public function setIdModelo($id_modelo)
    {
        $this->id_modelo = $id_modelo;

        return $this;
    }

    /**
     * Method to set the value of field id_tipoequipamento
     *
     * @param integer $id_tipoequipamento
     * @return $this
     */
    public function setIdTipoequipamento($id_tipoequipamento)
    {
        $this->id_tipoequipamento = $id_tipoequipamento;

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
     * Method to set the value of field numserie
     *
     * @param string $numserie
     * @return $this
     */
    public function setNumserie($numserie)
    {
        $this->numserie = $numserie;

        return $this;
    }

    /**
     * Method to set the value of field numpatrimonio
     *
     * @param string $numpatrimonio
     * @return $this
     */
    public function setNumpatrimonio($numpatrimonio)
    {
        $this->numpatrimonio = $numpatrimonio;

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
     * Returns the value of field id_fabricante
     *
     * @return integer
     */
    public function getIdFabricante()
    {
        return $this->id_fabricante;
    }

    /**
     * Returns the value of field id_modelo
     *
     * @return integer
     */
    public function getIdModelo()
    {
        return $this->id_modelo;
    }

    /**
     * Returns the value of field id_tipoequipamento
     *
     * @return integer
     */
    public function getIdTipoequipamento()
    {
        return $this->id_tipoequipamento;
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
     * Returns the value of field numserie
     *
     * @return string
     */
    public function getNumserie()
    {
        return $this->numserie;
    }

    /**
     * Returns the value of field numpatrimonio
     *
     * @return string
     */
    public function getNumpatrimonio()
    {
        return $this->numpatrimonio;
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
     * Returns the value of field ativo
     *
     * @return integer
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Returns the value of Nome Fabricante
     *
     * @return string
     */
    public function getNomeFabricante()
    {
        return $this->Fabricante->Pessoa->nome;
    }

    /**
     * Returns the value of Nome Modelo
     *
     * @return string
     */
    public function getNomeModelo()
    {
        return $this->Modelo->modelo;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("equipamento");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_equipamento', ['alias' => 'Circuitos']);
        $this->belongsTo('id_fabricante', 'Circuitos\Models\Fabricante', 'id', ['alias' => 'Fabricante']);
        $this->belongsTo('id_tipoequipamento', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_modelo', 'Circuitos\Models\Modelo', 'id', ['alias' => 'Modelo']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'equipamento';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Equipamento[]|Equipamento|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Equipamento|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de equipamentos, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Equipamentos|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarEquipamentos($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Equipamento" => "Circuitos\Models\Equipamento"));
        $query->columns("Equipamento.*");

        $query->leftJoin("Circuitos\Models\Fabricante", "Fabricante.id = Equipamento.id_fabricante", "Fabricante");
        $query->leftJoin("Circuitos\Models\Pessoa", "Fabricante.id_pessoa = Pessoa.id", "Pessoa");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa.id = PessoaJuridica.id", "PessoaJuridica");
        $query->leftJoin("Circuitos\Models\Modelo", "Modelo.id = Equipamento.id_modelo", "Modelo");
        $query->leftJoin("Circuitos\Models\Lov", "Equipamento.id_tipoequipamento = Lov.id", "Lov");

        $query->where("(CONVERT(Equipamento.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Equipamento.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Equipamento.numserie USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Equipamento.numpatrimonio USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Modelo.modelo USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov.descricao USING utf8) LIKE '%{$parameters}%')");

        $query->groupBy("Equipamento.id");

        $query->orderBy("Equipamento.id DESC");

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
