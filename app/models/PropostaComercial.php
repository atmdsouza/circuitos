<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class PropostaComercial extends \Phalcon\Mvc\Model
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
    protected $id_cliente;

    /**
     *
     * @var integer
     */
    protected $id_tipo_proposta;

    /**
     *
     * @var integer
     */
    protected $id_localizacao;

    /**
     *
     * @var integer
     */
    protected $id_status;

    /**
     *
     * @var string
     */
    protected $data_proposta;

    /**
     *
     * @var string
     */
    protected $numero;

    /**
     *
     * @var string
     */
    protected $vencimento;

    /**
     *
     * @var double
     */
    protected $reajuste;

    /**
     *
     * @var double
     */
    protected $imposto;

    /**
     *
     * @var double
     */
    protected $desconto;

    /**
     *
     * @var double
     */
    protected $encargos;

    /**
     *
     * @var integer
     */
    protected $prorrogar_vigencia;

    /**
     *
     * @var double
     */
    protected $valor_global;

    /**
     *
     * @var string
     */
    protected $objetivo;

    /**
     *
     * @var string
     */
    protected $objetivo_especifico;

    /**
     *
     * @var string
     */
    protected $descritivo;

    /**
     *
     * @var string
     */
    protected $responsabilidade;

    /**
     *
     * @var string
     */
    protected $condicoes_pgto;

    /**
     *
     * @var string
     */
    protected $prazo_execucao;

    /**
     *
     * @var string
     */
    protected $consideracoes;

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
     * Method to set the value of field id_cliente
     *
     * @param integer $id_cliente
     * @return $this
     */
    public function setIdCliente($id_cliente)
    {
        $this->id_cliente = $id_cliente;

        return $this;
    }

    /**
     * Method to set the value of field id_tipo_proposta
     *
     * @param integer $id_tipo_proposta
     * @return $this
     */
    public function setIdTipoProposta($id_tipo_proposta)
    {
        $this->id_tipo_proposta = $id_tipo_proposta;

        return $this;
    }

    /**
     * Method to set the value of field id_localizacao
     *
     * @param integer $id_localizacao
     * @return $this
     */
    public function setIdLocalizacao($id_localizacao)
    {
        $this->id_localizacao = $id_localizacao;

        return $this;
    }

    /**
     * Method to set the value of field id_status
     *
     * @param integer $id_status
     * @return $this
     */
    public function setIdStatus($id_status)
    {
        $this->id_status = $id_status;

        return $this;
    }

    /**
     * Method to set the value of field data_proposta
     *
     * @param string $data_proposta
     * @return $this
     */
    public function setDataProposta($data_proposta)
    {
        $this->data_proposta = $data_proposta;

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
     * Method to set the value of field vencimento
     *
     * @param string $vencimento
     * @return $this
     */
    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;

        return $this;
    }

    /**
     * Method to set the value of field reajuste
     *
     * @param double $reajuste
     * @return $this
     */
    public function setReajuste($reajuste)
    {
        $this->reajuste = $reajuste;

        return $this;
    }

    /**
     * Method to set the value of field imposto
     *
     * @param double $imposto
     * @return $this
     */
    public function setImposto($imposto)
    {
        $this->imposto = $imposto;

        return $this;
    }

    /**
     * Method to set the value of field desconto
     *
     * @param double $desconto
     * @return $this
     */
    public function setDesconto($desconto)
    {
        $this->desconto = $desconto;

        return $this;
    }

    /**
     * Method to set the value of field encargos
     *
     * @param double $encargos
     * @return $this
     */
    public function setEncargos($encargos)
    {
        $this->encargos = $encargos;

        return $this;
    }

    /**
     * Method to set the value of field prorrogar_vigencia
     *
     * @param integer $prorrogar_vigencia
     * @return $this
     */
    public function setProrrogarVigencia($prorrogar_vigencia)
    {
        $this->prorrogar_vigencia = $prorrogar_vigencia;

        return $this;
    }

    /**
     * Method to set the value of field valor_global
     *
     * @param double $valor_global
     * @return $this
     */
    public function setValorGlobal($valor_global)
    {
        $this->valor_global = $valor_global;

        return $this;
    }

    /**
     * Method to set the value of field objetivo
     *
     * @param string $objetivo
     * @return $this
     */
    public function setObjetivo($objetivo)
    {
        $this->objetivo = $objetivo;

        return $this;
    }

    /**
     * Method to set the value of field objetivo_especifico
     *
     * @param string $objetivo_especifico
     * @return $this
     */
    public function setObjetivoEspecifico($objetivo_especifico)
    {
        $this->objetivo_especifico = $objetivo_especifico;

        return $this;
    }

    /**
     * Method to set the value of field descritivo
     *
     * @param string $descritivo
     * @return $this
     */
    public function setDescritivo($descritivo)
    {
        $this->descritivo = $descritivo;

        return $this;
    }

    /**
     * Method to set the value of field responsabilidade
     *
     * @param string $responsabilidade
     * @return $this
     */
    public function setResponsabilidade($responsabilidade)
    {
        $this->responsabilidade = $responsabilidade;

        return $this;
    }

    /**
     * Method to set the value of field condicoes_pgto
     *
     * @param string $condicoes_pgto
     * @return $this
     */
    public function setCondicoesPgto($condicoes_pgto)
    {
        $this->condicoes_pgto = $condicoes_pgto;

        return $this;
    }

    /**
     * Method to set the value of field prazo_execucao
     *
     * @param string $prazo_execucao
     * @return $this
     */
    public function setPrazoExecucao($prazo_execucao)
    {
        $this->prazo_execucao = $prazo_execucao;

        return $this;
    }

    /**
     * Method to set the value of field consideracoes
     *
     * @param string $consideracoes
     * @return $this
     */
    public function setConsideracoes($consideracoes)
    {
        $this->consideracoes = $consideracoes;

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
     * Returns the value of field id_cliente
     *
     * @return integer
     */
    public function getIdCliente()
    {
        return $this->id_cliente;
    }

    /**
     * Returns the value of field id_tipo_proposta
     *
     * @return integer
     */
    public function getIdTipoProposta()
    {
        return $this->id_tipo_proposta;
    }

    /**
     * Returns the value of field id_localizacao
     *
     * @return integer
     */
    public function getIdLocalizacao()
    {
        return $this->id_localizacao;
    }

    /**
     * Returns the value of field id_status
     *
     * @return integer
     */
    public function getIdStatus()
    {
        return $this->id_status;
    }

    /**
     * Returns the value of field data_proposta
     *
     * @return string
     */
    public function getDataProposta()
    {
        return $this->data_proposta;
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
     * Returns the value of field vencimento
     *
     * @return string
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * Returns the value of field reajuste
     *
     * @return double
     */
    public function getReajuste()
    {
        return $this->reajuste;
    }

    /**
     * Returns the value of field imposto
     *
     * @return double
     */
    public function getImposto()
    {
        return $this->imposto;
    }

    /**
     * Returns the value of field desconto
     *
     * @return double
     */
    public function getDesconto()
    {
        return $this->desconto;
    }

    /**
     * Returns the value of field encargos
     *
     * @return double
     */
    public function getEncargos()
    {
        return $this->encargos;
    }

    /**
     * Returns the value of field prorrogar_vigencia
     *
     * @return integer
     */
    public function getProrrogarVigencia()
    {
        return $this->prorrogar_vigencia;
    }

    /**
     * Returns the value of field valor_global
     *
     * @return double
     */
    public function getValorGlobal()
    {
        return $this->valor_global;
    }

    /**
     * Returns the value of field objetivo
     *
     * @return string
     */
    public function getObjetivo()
    {
        return $this->objetivo;
    }

    /**
     * Returns the value of field objetivo_especifico
     *
     * @return string
     */
    public function getObjetivoEspecifico()
    {
        return $this->objetivo_especifico;
    }

    /**
     * Returns the value of field descritivo
     *
     * @return string
     */
    public function getDescritivo()
    {
        return $this->descritivo;
    }

    /**
     * Returns the value of field responsabilidade
     *
     * @return string
     */
    public function getResponsabilidade()
    {
        return $this->responsabilidade;
    }

    /**
     * Returns the value of field condicoes_pgto
     *
     * @return string
     */
    public function getCondicoesPgto()
    {
        return $this->condicoes_pgto;
    }

    /**
     * Returns the value of field prazo_execucao
     *
     * @return string
     */
    public function getPrazoExecucao()
    {
        return $this->prazo_execucao;
    }

    /**
     * Returns the value of field consideracoes
     *
     * @return string
     */
    public function getConsideracoes()
    {
        return $this->consideracoes;
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
     * Returns the value of field Cliente
     *
     * @return string
     */
    public function getCliente()
    {
        return $this->Cliente->Pessoa->nome;
    }

    /**
     * Returns the value of field Tipo Proposta
     *
     * @return string
     */
    public function getTipoProposta()
    {
        return $this->Lov1->descricao;
    }

    /**
     * Returns the value of field Localizacao
     *
     * @return string
     */
    public function getLocalizacao()
    {
        return $this->Lov2->descricao;
    }

    /**
     * Returns the value of field Status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->Lov3->descricao;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("proposta_comercial");
        $this->hasMany('id', 'Circuitos\Models\ContratoProcesso', 'id_proposta_comercial', ['alias' => 'ContratoProcesso']);
        $this->hasMany('id', 'Circuitos\Models\PropostaComercialItem', 'id_proposta_comercial', ['alias' => 'PropostaComercialItem']);
        $this->hasMany('id', 'Circuitos\Models\PropostaComercialValorMensal', 'id_proposta_comercial', ['alias' => 'PropostaComercialValorMensal']);
        $this->belongsTo('id_cliente', 'Circuitos\Models\Cliente', 'id', ['alias' => 'Cliente']);
        $this->belongsTo('id_tipo_proposta', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov1']);
        $this->belongsTo('id_localizacao', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov2']);
        $this->belongsTo('id_status', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov3']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'proposta_comercial';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercial[]|PropostaComercial|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropostaComercial|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de PropostaComercial, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return PropostaComercial|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarPropostaComercial($parameters = null)
    {
        $query = new Builder();
        $query->from(array("PropostaComercial" => "Circuitos\Models\PropostaComercial"));
        $query->columns("PropostaComercial.*");
        $query->leftJoin("Circuitos\Models\Cliente", "Cliente.id = PropostaComercial.id_cliente", "Cliente");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa.id = Cliente.id_pessoa", "Pessoa");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "PessoaJuridica.id = Cliente.id_pessoa", "PessoaJuridica");
        $query->leftJoin("Circuitos\Models\Lov", "Lov1.id = PropostaComercial.id_tipo_proposta", "Lov1");
        $query->leftJoin("Circuitos\Models\Lov", "Lov2.id = PropostaComercial.id_localizacao", "Lov2");
        $query->leftJoin("Circuitos\Models\Lov", "Lov3.id = PropostaComercial.id_status", "Lov3");
        $query->where("PropostaComercial.excluido = 0 AND (CONVERT(PropostaComercial.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PropostaComercial.data_proposta USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PropostaComercial.numero USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PropostaComercial.vencimento USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov1.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov2.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov3.descricao USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("PropostaComercial.id");
        $query->orderBy("PropostaComercial.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
