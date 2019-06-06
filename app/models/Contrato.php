<?php

namespace Circuitos\Models;

class Contrato extends \Phalcon\Mvc\Model
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
    protected $id_contrato_principal;

    /**
     *
     * @var integer
     */
    protected $ordem;

    /**
     *
     * @var integer
     */
    protected $id_tipo_contrato;

    /**
     *
     * @var integer
     */
    protected $id_processo_contratacao;

    /**
     *
     * @var integer
     */
    protected $id_fornecedor;

    /**
     *
     * @var integer
     */
    protected $id_cliente;

    /**
     *
     * @var integer
     */
    protected $id_status;

    /**
     *
     * @var string
     */
    protected $data_criacao;

    /**
     *
     * @var string
     */
    protected $data_assinatura;

    /**
     *
     * @var string
     */
    protected $data_publicacao;

    /**
     *
     * @var string
     */
    protected $num_diario_oficial;

    /**
     *
     * @var string
     */
    protected $data_encerramento;

    /**
     *
     * @var integer
     */
    protected $vigencia_tipo;

    /**
     *
     * @var integer
     */
    protected $vigencia_prazo;

    /**
     *
     * @var integer
     */
    protected $numero;

    /**
     *
     * @var integer
     */
    protected $ano;

    /**
     *
     * @var integer
     */
    protected $exercicio;

    /**
     *
     * @var string
     */
    protected $objeto;

    /**
     *
     * @var double
     */
    protected $valor_global;

    /**
     *
     * @var double
     */
    protected $valor_exercicio;

    /**
     *
     * @var double
     */
    protected $valor_mensal;

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
     * Method to set the value of field id_contrato_principal
     *
     * @param integer $id_contrato_principal
     * @return $this
     */
    public function setIdContratoPrincipal($id_contrato_principal)
    {
        $this->id_contrato_principal = $id_contrato_principal;

        return $this;
    }

    /**
     * Method to set the value of field ordem
     *
     * @param integer $ordem
     * @return $this
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;

        return $this;
    }

    /**
     * Method to set the value of field id_tipo_contrato
     *
     * @param integer $id_tipo_contrato
     * @return $this
     */
    public function setIdTipoContrato($id_tipo_contrato)
    {
        $this->id_tipo_contrato = $id_tipo_contrato;

        return $this;
    }

    /**
     * Method to set the value of field id_processo_contratacao
     *
     * @param integer $id_processo_contratacao
     * @return $this
     */
    public function setIdProcessoContratacao($id_processo_contratacao)
    {
        $this->id_processo_contratacao = $id_processo_contratacao;

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
     * Method to set the value of field data_assinatura
     *
     * @param string $data_assinatura
     * @return $this
     */
    public function setDataAssinatura($data_assinatura)
    {
        $this->data_assinatura = $data_assinatura;

        return $this;
    }

    /**
     * Method to set the value of field data_publicacao
     *
     * @param string $data_publicacao
     * @return $this
     */
    public function setDataPublicacao($data_publicacao)
    {
        $this->data_publicacao = $data_publicacao;

        return $this;
    }

    /**
     * Method to set the value of field num_diario_oficial
     *
     * @param string $num_diario_oficial
     * @return $this
     */
    public function setNumDiarioOficial($num_diario_oficial)
    {
        $this->num_diario_oficial = $num_diario_oficial;

        return $this;
    }

    /**
     * Method to set the value of field data_encerramento
     *
     * @param string $data_encerramento
     * @return $this
     */
    public function setDataEncerramento($data_encerramento)
    {
        $this->data_encerramento = $data_encerramento;

        return $this;
    }

    /**
     * Method to set the value of field vigencia_tipo
     *
     * @param integer $vigencia_tipo
     * @return $this
     */
    public function setVigenciaTipo($vigencia_tipo)
    {
        $this->vigencia_tipo = $vigencia_tipo;

        return $this;
    }

    /**
     * Method to set the value of field vigencia_prazo
     *
     * @param integer $vigencia_prazo
     * @return $this
     */
    public function setVigenciaPrazo($vigencia_prazo)
    {
        $this->vigencia_prazo = $vigencia_prazo;

        return $this;
    }

    /**
     * Method to set the value of field numero
     *
     * @param integer $numero
     * @return $this
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Method to set the value of field ano
     *
     * @param integer $ano
     * @return $this
     */
    public function setAno($ano)
    {
        $this->ano = $ano;

        return $this;
    }

    /**
     * Method to set the value of field exercicio
     *
     * @param integer $exercicio
     * @return $this
     */
    public function setExercicio($exercicio)
    {
        $this->exercicio = $exercicio;

        return $this;
    }

    /**
     * Method to set the value of field objeto
     *
     * @param string $objeto
     * @return $this
     */
    public function setObjeto($objeto)
    {
        $this->objeto = $objeto;

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
     * Method to set the value of field valor_exercicio
     *
     * @param double $valor_exercicio
     * @return $this
     */
    public function setValorExercicio($valor_exercicio)
    {
        $this->valor_exercicio = $valor_exercicio;

        return $this;
    }

    /**
     * Method to set the value of field valor_mensal
     *
     * @param double $valor_mensal
     * @return $this
     */
    public function setValorMensal($valor_mensal)
    {
        $this->valor_mensal = $valor_mensal;

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
     * Returns the value of field id_contrato_principal
     *
     * @return integer
     */
    public function getIdContratoPrincipal()
    {
        return $this->id_contrato_principal;
    }

    /**
     * Returns the value of field ordem
     *
     * @return integer
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * Returns the value of field id_tipo_contrato
     *
     * @return integer
     */
    public function getIdTipoContrato()
    {
        return $this->id_tipo_contrato;
    }

    /**
     * Returns the value of field id_processo_contratacao
     *
     * @return integer
     */
    public function getIdProcessoContratacao()
    {
        return $this->id_processo_contratacao;
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
     * Returns the value of field id_cliente
     *
     * @return integer
     */
    public function getIdCliente()
    {
        return $this->id_cliente;
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
     * Returns the value of field data_criacao
     *
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    /**
     * Returns the value of field data_assinatura
     *
     * @return string
     */
    public function getDataAssinatura()
    {
        return $this->data_assinatura;
    }

    /**
     * Returns the value of field data_publicacao
     *
     * @return string
     */
    public function getDataPublicacao()
    {
        return $this->data_publicacao;
    }

    /**
     * Returns the value of field num_diario_oficial
     *
     * @return string
     */
    public function getNumDiarioOficial()
    {
        return $this->num_diario_oficial;
    }

    /**
     * Returns the value of field data_encerramento
     *
     * @return string
     */
    public function getDataEncerramento()
    {
        return $this->data_encerramento;
    }

    /**
     * Returns the value of field vigencia_tipo
     *
     * @return integer
     */
    public function getVigenciaTipo()
    {
        return $this->vigencia_tipo;
    }

    /**
     * Returns the value of field vigencia_prazo
     *
     * @return integer
     */
    public function getVigenciaPrazo()
    {
        return $this->vigencia_prazo;
    }

    /**
     * Returns the value of field numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Returns the value of field ano
     *
     * @return integer
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * Returns the value of field exercicio
     *
     * @return integer
     */
    public function getExercicio()
    {
        return $this->exercicio;
    }

    /**
     * Returns the value of field objeto
     *
     * @return string
     */
    public function getObjeto()
    {
        return $this->objeto;
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
     * Returns the value of field valor_exercicio
     *
     * @return double
     */
    public function getValorExercicio()
    {
        return $this->valor_exercicio;
    }

    /**
     * Returns the value of field valor_mensal
     *
     * @return double
     */
    public function getValorMensal()
    {
        return $this->valor_mensal;
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
        $this->setSource("contrato");
        $this->hasMany('id', 'CircuitosModels\Contrato', 'id_contrato_principal', ['alias' => 'Contrato']);
        $this->hasMany('id', 'CircuitosModels\ContratoAnexo', 'id_contrato', ['alias' => 'ContratoAnexo']);
        $this->hasMany('id', 'CircuitosModels\ContratoArquivoFisico', 'id_contrato', ['alias' => 'ContratoArquivoFisico']);
        $this->hasMany('id', 'CircuitosModels\ContratoExercicio', 'id_contrato', ['alias' => 'ContratoExercicio']);
        $this->hasMany('id', 'CircuitosModels\ContratoFiscalHasContrato', 'id_contrato', ['alias' => 'ContratoFiscalHasContrato']);
        $this->hasMany('id', 'CircuitosModels\ContratoHasContratoGarantia', 'id_contrato', ['alias' => 'ContratoHasContratoGarantia']);
        $this->hasMany('id', 'CircuitosModels\ContratoMovimento', 'id_contrato', ['alias' => 'ContratoMovimento']);
        $this->hasMany('id', 'CircuitosModels\ContratoOrcamento', 'id_contrato', ['alias' => 'ContratoOrcamento']);
        $this->hasMany('id', 'CircuitosModels\EstacaoTelecon', 'id_contrato', ['alias' => 'EstacaoTelecon']);
        $this->hasMany('id', 'CircuitosModels\SetEquipamento', 'id_contrato', ['alias' => 'SetEquipamento']);
        $this->hasMany('id', 'CircuitosModels\SetSegurancaComponentes', 'id_contrato', ['alias' => 'SetSegurancaComponentes']);
        $this->hasMany('id', 'CircuitosModels\Terreno', 'id_contrato', ['alias' => 'Terreno']);
        $this->hasMany('id', 'CircuitosModels\Torre', 'id_contrato', ['alias' => 'Torre']);
        $this->belongsTo('id_cliente', 'CircuitosModels\Cliente', 'id', ['alias' => 'Cliente']);
        $this->belongsTo('id_contrato_principal', 'CircuitosModels\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_fornecedor', 'CircuitosModels\Fornecedor', 'id', ['alias' => 'Fornecedor']);
        $this->belongsTo('id_tipo_contrato', 'CircuitosModels\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_status', 'CircuitosModels\Lov', 'id', ['alias' => 'Lov']);
        $this->belongsTo('id_processo_contratacao', 'CircuitosModels\ContratoProcesso', 'id', ['alias' => 'ContratoProcesso']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contrato[]|Contrato|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contrato|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
