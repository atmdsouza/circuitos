<?php

namespace Circuitos\Models;

use Util\Infra;
use Util\Util;

class ContratoFinanceiroNota extends \Phalcon\Mvc\Model
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
    protected $id_contrato_financeiro;

    /**
     *
     * @var string
     */
    protected $numero_nota_fiscal;

    /**
     *
     * @var string
     */
    protected $data_pagamento;

    /**
     *
     * @var double
     */
    protected $valor_nota;

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
     *
     * @var string
     */
    protected $data_criacao;

    /**
     *
     * @var integer
     */
    protected $id_anexo;

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
     * Method to set the value of field id_contrato_financeiro
     *
     * @param integer $id_contrato_financeiro
     * @return $this
     */
    public function setIdContratoFinanceiro($id_contrato_financeiro)
    {
        $this->id_contrato_financeiro = $id_contrato_financeiro;

        return $this;
    }

    /**
     * Method to set the value of field numero_nota_fiscal
     *
     * @param string $numero_nota_fiscal
     * @return $this
     */
    public function setNumeroNotaFiscal($numero_nota_fiscal)
    {
        $this->numero_nota_fiscal = $numero_nota_fiscal;

        return $this;
    }

    /**
     * Method to set the value of field data_pagamento
     *
     * @param string $data_pagamento
     * @return $this
     */
    public function setDataPagamento($data_pagamento)
    {
        $this->data_pagamento = $data_pagamento;

        return $this;
    }

    /**
     * Method to set the value of field valor_nota
     *
     * @param double $valor_nota
     * @return $this
     */
    public function setValorNota($valor_nota)
    {
        $this->valor_nota = $valor_nota;

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
     * Method to set the value of field data_criacao
     *
     * @param string $data_criacao
     * @return $this
     */
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;

        return $this;
    }

    /**
     * Method to set the value of field id_anexo
     *
     * @param integer $id_anexo
     * @return $this
     */
    public function setIdAnexo($id_anexo)
    {
        $this->id_anexo = $id_anexo;

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
     * Returns the value of field id_contrato_financeiro
     *
     * @return integer
     */
    public function getIdContratoFinanceiro()
    {
        return $this->id_contrato_financeiro;
    }

    /**
     * Returns the value of field numero_nota_fiscal
     *
     * @return string
     */
    public function getNumeroNotaFiscal()
    {
        return $this->numero_nota_fiscal;
    }

    /**
     * Returns the value of field data_pagamento
     *
     * @return string
     */
    public function getDataPagamento()
    {
        return $this->data_pagamento;
    }

    /**
     * Returns the value of field valor_nota
     *
     * @return double
     */
    public function getValorNota()
    {
        return $this->valor_nota;
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
     * Returns the value of field data_criacao
     *
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    /**
     * Returns the value of field id_anexo
     *
     * @return integer
     */
    public function getIdAnexo()
    {
        return $this->id_anexo;
    }

    /**
     * @return string
     */
    public function getUrlAnexo()
    {
        return $this->Anexos->url;
    }

    /**
     * @return string
     */
    public function getUrlFormatadaAnexo()
    {
        $url_base = explode("/", $this->Anexos->url);
        return $url_base[count($url_base)-5].'/'.$url_base[count($url_base)-4].'/'.$url_base[count($url_base)-3].'/'.$url_base[count($url_base)-2].'/'.$url_base[count($url_base)-1];
    }

    /**
     * Returns the value of field descricao
     *
     * @return string
     */
    public function getDescricaoAnexo()
    {
        return $this->Anexos->descricao;
    }

    /**
     * Returns the value of field data criação
     *
     * @return string
     */
    public function getDataCriacaoAnexo()
    {
        return $this->Anexos->data_criacao;
    }

    /**
     * Returns the value of field descricao tipo anexo
     *
     * @return string
     */
    public function getDescricaoTipoAnexo()
    {
        return $this->Anexos->Lov->descricao;
    }

    /**
     * Returns the value of field id_tipo_anexo
     *
     * @return integer
     */
    public function getIdTipoAnexo()
    {
        return $this->Anexos->id_tipo_anexo;
    }

    /**
     * Returns the value of field data_pagamento
     *
     * @return string
     */
    public function getDataPagamentoFormatada()
    {
        $util = new Util();
        return $util->converterDataParaBr($this->data_pagamento);
    }

    /**
     * Returns the value of field valor_nota
     *
     * @return double
     */
    public function getValorNotaFormatado()
    {
        $util = new Util();
        return $util->formataMoedaReal($this->valor_nota);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_financeiro_nota");
        $this->belongsTo('id_contrato_financeiro', 'Circuitos\Models\ContratoFinanceiro', 'id', ['alias' => 'ContratoFinanceiro']);
        $this->hasOne('id_anexo', 'Circuitos\Models\Anexos', 'id', ['alias' => 'Anexos']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFinanceiroNota[]|ContratoFinanceiroNota|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFinanceiroNota|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_financeiro_nota';
    }

}
