<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Util\Infra;

class Terreno extends \Phalcon\Mvc\Model
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
    protected $id_contrato;

    /**
     *
     * @var integer
     */
    protected $id_fornecedor;

    /**
     *
     * @var string
     */
    protected $descricao;

    /**
     *
     * @var double
     */
    protected $comprimento;

    /**
     *
     * @var double
     */
    protected $largura;

    /**
     *
     * @var double
     */
    protected $area;

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
     * @var string
     */
    protected $latitude;

    /**
     *
     * @var string
     */
    protected $longitude;

    /**
     *
     * @var integer
     */
    protected $propriedade_prodepa;

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
     * Method to set the value of field id_contrato
     *
     * @param integer $id_contrato
     * @return $this
     */
    public function setIdContrato($id_contrato)
    {
        $this->id_contrato = $id_contrato;

        return $this;
    }

    /**
     * Method to set the value of field id_fornecedor
     *
     * @param integer $id_fornecedor
     * @return $this
     */
    public function setIdFornecedor($id_fornecedor)
    {
        $this->id_fornecedor = $id_fornecedor;

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
     * Method to set the value of field comprimento
     *
     * @param double $comprimento
     * @return $this
     */
    public function setComprimento($comprimento)
    {
        $this->comprimento = $comprimento;

        return $this;
    }

    /**
     * Method to set the value of field largura
     *
     * @param double $largura
     * @return $this
     */
    public function setLargura($largura)
    {
        $this->largura = $largura;

        return $this;
    }

    /**
     * Method to set the value of field area
     *
     * @param double $area
     * @return $this
     */
    public function setArea($area)
    {
        $this->area = $area;

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
     * Method to set the value of field latitude
     *
     * @param string $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Method to set the value of field longitude
     *
     * @param string $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Method to set the value of field propriedade_prodepa
     *
     * @param integer $propriedade_prodepa
     * @return $this
     */
    public function setPropriedadeProdepa($propriedade_prodepa)
    {
        $this->propriedade_prodepa = $propriedade_prodepa;

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
     * Method to set the value of field excluido
     *
     * @param integer $excluido
     * @return $this
     */
    public function setExcluido($excluido)
    {
        $this->excluido = $excluido;

        return $this;
    }

    /**
     * Method to set the value of field data_update
     *
     * @param string $data_update
     * @return $this
     */
    public function setDataUpdate($data_update)
    {
        $this->data_update = $data_update;

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
     * Returns the value of field id_contrato
     *
     * @return integer
     */
    public function getIdContrato()
    {
        return $this->id_contrato;
    }

    /**
     * Returns the value of field id_fornecedor
     *
     * @return integer
     */
    public function getIdFornecedor()
    {
        return $this->id_fornecedor;
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
     * Returns the value of field comprimento
     *
     * @return double
     */
    public function getComprimento()
    {
        return $this->comprimento;
    }

    /**
     * Returns the value of field largura
     *
     * @return double
     */
    public function getLargura()
    {
        return $this->largura;
    }

    /**
     * Returns the value of field area
     *
     * @return double
     */
    public function getArea()
    {
        return $this->area;
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
     * Returns the value of field latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Returns the value of field longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Returns the value of field propriedade_prodepa
     *
     * @return integer
     */
    public function getPropriedadeProdepa()
    {
        return $this->propriedade_prodepa;
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
     * Returns the value of field excluido
     *
     * @return integer
     */
    public function getExcluido()
    {
        return $this->excluido;
    }

    /**
     * Returns the value of field data_update
     *
     * @return string
     */
    public function getDataUpdate()
    {
        return $this->data_update;
    }

    /**
     * Returns the value of field Nome do Fornecedor
     *
     * @return string
     */
    public function getFornecedor()
    {
        return $this->Fornecedor->Pessoa->nome;
    }

    /**
     * Returns the value of field Nome do Fornecedor
     *
     * @return string
     */
    public function getContrato()
    {
        return $this->Contrato->numero;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("terreno");
        $this->hasMany('id', 'Circuitos\Models\EstacaoTelecon', 'id_terreno', ['alias' => 'EstacaoTelecon']);
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_fornecedor', 'Circuitos\Models\Cliente', 'id', ['alias' => 'Fornecedor']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'terreno';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Terreno[]|Terreno|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Terreno|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de Terreno, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Terreno|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarTerreno($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Terreno" => "Circuitos\Models\Terreno"));
        $query->columns("Terreno.*");
        $query->leftJoin("Circuitos\Models\Cliente", "Fornecedor.id = Terreno.id_fornecedor", "Fornecedor");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa.id = Fornecedor.id_pessoa", "Pessoa");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "PessoaJuridica.id = Fornecedor.id_pessoa", "PessoaJuridica");
        $query->where("Terreno.excluido = 0 AND (CONVERT(Terreno.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Terreno.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica.razaosocial USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("Terreno.id");
        $query->orderBy("Terreno.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
