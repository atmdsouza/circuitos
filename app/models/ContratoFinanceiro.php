<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Util\Infra;
use Util\Util;

class ContratoFinanceiro extends \Phalcon\Mvc\Model
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
    protected $id_exercicio;

    /**
     *
     * @var integer
     */
    protected $mes_competencia;

    /**
     *
     * @var integer
     */
    protected $status_pagamento;

    /**
     *
     * @var double
     */
    protected $valor_pagamento;

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
    protected $data_criacao;

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
     * Method to set the value of field id_exercicio
     *
     * @param integer $id_exercicio
     * @return $this
     */
    public function setIdExercicio($id_exercicio)
    {
        $this->id_exercicio = $id_exercicio;

        return $this;
    }

    /**
     * Method to set the value of field mes_competencia
     *
     * @param integer $mes_competencia
     * @return $this
     */
    public function setMesCompetencia($mes_competencia)
    {
        $this->mes_competencia = $mes_competencia;

        return $this;
    }

    /**
     * Method to set the value of field status_pagamento
     *
     * @param integer $status_pagamento
     * @return $this
     */
    public function setStatusPagamento($status_pagamento)
    {
        $this->status_pagamento = $status_pagamento;

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
     * Returns the value of field id_exercicio
     *
     * @return integer
     */
    public function getIdExercicio()
    {
        return $this->id_exercicio;
    }

    /**
     * Returns the value of field mes_competencia
     *
     * @return integer
     */
    public function getMesCompetencia()
    {
        return $this->mes_competencia;
    }

    /**
     * Returns the value of field status_pagamento
     *
     * @return integer
     */
    public function getStatusPagamento()
    {
        return $this->status_pagamento;
    }

    /**
     * @return int
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param int $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
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

        return $this;
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

        return $this;
    }

    /**
     * @return string
     */
    public function getExercicio()
    {
        return $this->ContratoExercicio->exercicio;
    }

    /**
     * @return string
     */
    public function getNumeroAnoContrato()
    {
        return $this->ContratoExercicio->Contrato->numero .'/'.$this->ContratoExercicio->Contrato->ano;
    }

    /**
     * @return integer
     */
    public function getIdContrato()
    {
        return $this->ContratoExercicio->Contrato->id;
    }

    /**
     * @return integer
     */
    public function getValorPrevistoExercicio()
    {
        return $this->ContratoExercicio->valor_previsto;
    }

    /**
     * @return string
     */
    public function getStatusDescricao()
    {
        $status = "Pagamento Pendente";
        switch($this->status_pagamento)
        {
            case '0':
                $status = "Sem Pagamento";
                break;
            case '2':
                $status = "Pagamento Efetuado";
                break;
            case '3':
                $status = "Pagamento Parcial";
                break;
        }
        return $status;
    }

    /**
     * @return float
     */
    public function getValorPagamento()
    {
        return $this->valor_pagamento;
    }

    /**
     * @param float $valor_pagamento
     */
    public function setValorPagamento($valor_pagamento)
    {
        $this->valor_pagamento = $valor_pagamento;

        return $this;
    }

    /**
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    /**
     * @param string $data_criacao
     */
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;

        return $this;
    }

    /**
     * @return string
     */
    public function getAtivoDescricao()
    {
        $status = ($this->ativo == 1) ? 'Ativo' : 'Inativo';
        return $status;
    }

    /**
     * @return float
     */
    public function getValorPagamentoFormatado()
    {
        $util = new Util();
        return $util->formataMoedaReal($this->valor_pagamento);
    }

    /**
     * @return float
     */
    public function getValorPago()
    {
        $objetosFilhos = ContratoFinanceiroNota::find('ativo=1 AND excluido=0 AND id_contrato_financeiro='.$this->id);
        $valor_pago = 0;
        foreach ($objetosFilhos as $objetoFilho)
        {
            $valor_pago += $objetoFilho->getValorNota();
        }
        return $valor_pago;
    }

    /**
     * @return float
     */
    public function getValorPagoFormatado()
    {
        $objetosFilhos = ContratoFinanceiroNota::find('ativo=1 AND excluido=0 AND id_contrato_financeiro='.$this->id);
        $valor_pago = 0;
        foreach ($objetosFilhos as $objetoFilho)
        {
            $valor_pago += $objetoFilho->getValorNota();
        }
        $util = new Util();
        return $util->formataMoedaReal($valor_pago);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_financeiro");
        $this->hasMany('id', 'Circuitos\Models\ContratoFinanceiroNota', 'id_contrato_financeiro', ['alias' => 'ContratoFinanceiroNota']);
        $this->belongsTo('id_exercicio', 'Circuitos\Models\ContratoExercicio', 'id', ['alias' => 'ContratoExercicio']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_financeiro';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFinanceiro[]|ContratoFinanceiro|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFinanceiro|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de ContratoFinanceiro, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return ContratoFinanceiro|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarContratoFinanceiro($parameters = null)
    {
        $query = new Builder();
        $query->from(array("ContratoFinanceiro" => "Circuitos\Models\ContratoFinanceiro"));
        $query->columns("ContratoFinanceiro.*");
        $query->leftJoin("Circuitos\Models\ContratoExercicio", "ContratoExercicio.id = ContratoFinanceiro.id_exercicio", "ContratoExercicio");
        $query->leftJoin("Circuitos\Models\Contrato", "Contrato.id = ContratoExercicio.id_contrato", "Contrato");
        $query->where("ContratoFinanceiro.excluido = 0 AND (CONVERT(ContratoFinanceiro.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(ContratoExercicio.exercicio USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(ContratoExercicio.valor_previsto USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Contrato.ano USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Contrato.numero USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("ContratoFinanceiro.id");
        $query->orderBy("ContratoFinanceiro.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
