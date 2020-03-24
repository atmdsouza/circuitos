<?php

namespace Circuitos\Models;

use Util\Infra;
use Util\Util;

class ContratoPenalidadeMovimento extends \Phalcon\Mvc\Model
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
    protected $id_contrato_penalidade;

    /**
     *
     * @var integer
     */
    protected $id_tipo_movimento;

    /**
     *
     * @var integer
     */
    protected $id_usuario;

    /**
     *
     * @var string
     */
    protected $data_movimento;

    /**
     *
     * @var string
     */
    protected $valor_anterior;

    /**
     *
     * @var string
     */
    protected $valor_atualizado;

    /**
     *
     * @var string
     */
    protected $observacao;

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
     * Method to set the value of field id_contrato_penalidade
     *
     * @param integer $id_contrato_penalidade
     * @return $this
     */
    public function setIdContratoPenalidade($id_contrato_penalidade)
    {
        $this->id_contrato_penalidade = $id_contrato_penalidade;

        return $this;
    }

    /**
     * Method to set the value of field id_tipo_movimento
     *
     * @param integer $id_tipo_movimento
     * @return $this
     */
    public function setIdTipoMovimento($id_tipo_movimento)
    {
        $this->id_tipo_movimento = $id_tipo_movimento;

        return $this;
    }

    /**
     * Method to set the value of field id_usuario
     *
     * @param integer $id_usuario
     * @return $this
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    /**
     * Method to set the value of field data_movimento
     *
     * @param string $data_movimento
     * @return $this
     */
    public function setDataMovimento($data_movimento)
    {
        $this->data_movimento = $data_movimento;

        return $this;
    }

    /**
     * Method to set the value of field observacao
     *
     * @param string $observacao
     * @return $this
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

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
     * Returns the value of field id_contrato_penalidade
     *
     * @return integer
     */
    public function getIdContratoPenalidade()
    {
        return $this->id_contrato_penalidade;
    }

    /**
     * Returns the value of field id_tipo_movimento
     *
     * @return integer
     */
    public function getIdTipoMovimento()
    {
        return $this->id_tipo_movimento;
    }

    /**
     * Returns the value of field id_tipo_movimento descrição
     *
     * @return integer
     */
    public function getTipoMovimentoDescricao()
    {
        return $this->Lov->descricao;
    }

    /**
     * Returns the value of field id_usuario
     *
     * @return integer
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * Returns the value of field id_usuario nome
     *
     * @return integer
     */
    public function getUsuarioNome()
    {
        return $this->Usuario->Pessoa->nome;
    }

    /**
     * Returns the value of field data_movimento
     *
     * @return string
     */
    public function getDataMovimento()
    {
        return $this->data_movimento;
    }

    /**
     * Returns the value of field data_movimento formatada
     *
     * @return string
     */
    public function getDataMovimentoFormatada()
    {
        $util = new Util();
        return ($this->data_movimento) ? $util->converterDataHoraParaBr($this->data_movimento) : null;
    }

    /**
     * Returns the value of field observacao
     *
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
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
     * @return string
     */
    public function getValorAnterior()
    {
        return $this->valor_anterior;
    }

    /**
     * @param string $valor_anterior
     */
    public function setValorAnterior($valor_anterior)
    {
        $this->valor_anterior = $valor_anterior;

        return $this;
    }

    /**
     * @return string
     */
    public function getValorAtualizado()
    {
        return $this->valor_atualizado;
    }

    /**
     * @param string $valor_atualizado
     */
    public function setValorAtualizado($valor_atualizado)
    {
        $this->valor_atualizado = $valor_atualizado;

        return $this;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_penalidade_movimento");
        $this->belongsTo('id_contrato_penalidade', 'Circuitos\Models\ContratoPenalidade', 'id', ['alias' => 'ContratoPenalidade']);
        $this->belongsTo('id_tipo_movimento', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_usuario', 'Circuitos\Models\Usuario', 'id', ['alias' => 'Usuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_penalidade_movimento';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoPenalidadeMovimento[]|ContratoPenalidadeMovimento|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoPenalidadeMovimento|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Buscar o di do tipo de movimento específico para gravar no banco de dados
     *
     * @param int tipo
     * @param int valor
     * @return int id_tipo_movimento
     */
    public function buscarIdTipoMovimento($tipo, $valor)
    {
        $objeto = Lov::findFirst('tipo='.$tipo.' AND valor="'.$valor.'"');
        return $objeto->getId();
    }
}
