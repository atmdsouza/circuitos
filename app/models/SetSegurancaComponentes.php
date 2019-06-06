<?php

namespace Circuitos\Models;

class SetSegurancaComponentes extends \Phalcon\Mvc\Model
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
    protected $id_set_seguranca;

    /**
     *
     * @var integer
     */
    protected $id_contrato;

    /**
     *
     * @var integer
     */
    protected $id_tipo;

    /**
     *
     * @var integer
     */
    protected $id_fornecedor;

    /**
     *
     * @var integer
     */
    protected $propriedade_prodepa;

    /**
     *
     * @var string
     */
    protected $senha;

    /**
     *
     * @var string
     */
    protected $validade;

    /**
     *
     * @var string
     */
    protected $endereco_chave;

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
     * Method to set the value of field id_set_seguranca
     *
     * @param integer $id_set_seguranca
     * @return $this
     */
    public function setIdSetSeguranca($id_set_seguranca)
    {
        $this->id_set_seguranca = $id_set_seguranca;

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
     * Method to set the value of field id_tipo
     *
     * @param integer $id_tipo
     * @return $this
     */
    public function setIdTipo($id_tipo)
    {
        $this->id_tipo = $id_tipo;

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
     * Method to set the value of field senha
     *
     * @param string $senha
     * @return $this
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }

    /**
     * Method to set the value of field validade
     *
     * @param string $validade
     * @return $this
     */
    public function setValidade($validade)
    {
        $this->validade = $validade;

        return $this;
    }

    /**
     * Method to set the value of field endereco_chave
     *
     * @param string $endereco_chave
     * @return $this
     */
    public function setEnderecoChave($endereco_chave)
    {
        $this->endereco_chave = $endereco_chave;

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
     * Returns the value of field id_set_seguranca
     *
     * @return integer
     */
    public function getIdSetSeguranca()
    {
        return $this->id_set_seguranca;
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
     * Returns the value of field id_tipo
     *
     * @return integer
     */
    public function getIdTipo()
    {
        return $this->id_tipo;
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
     * Returns the value of field propriedade_prodepa
     *
     * @return integer
     */
    public function getPropriedadeProdepa()
    {
        return $this->propriedade_prodepa;
    }

    /**
     * Returns the value of field senha
     *
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Returns the value of field validade
     *
     * @return string
     */
    public function getValidade()
    {
        return $this->validade;
    }

    /**
     * Returns the value of field endereco_chave
     *
     * @return string
     */
    public function getEnderecoChave()
    {
        return $this->endereco_chave;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("set_seguranca_componentes");
        $this->belongsTo('id_contrato', 'CircuitosModels\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_fornecedor', 'CircuitosModels\Fornecedor', 'id', ['alias' => 'Fornecedor']);
        $this->belongsTo('id_tipo', 'CircuitosModels\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_set_seguranca', 'CircuitosModels\SetSeguranca', 'id', ['alias' => 'SetSeguranca']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'set_seguranca_componentes';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SetSegurancaComponentes[]|SetSegurancaComponentes|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SetSegurancaComponentes|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
