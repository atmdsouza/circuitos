<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;

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
     * Returns the value of field cliente
     *
     * @return string
     */
    public function getCliente()
    {
        return $this->Cliente->Pessoa->nome;
    }

    /**
     * Returns the value of field Fornecedor
     *
     * @return string
     */
    public function getFornecedor()
    {
        return $this->Fornecedor->Pessoa->nome;
    }

    /**
     * Returns the value of field Tipo Contrato
     *
     * @return string
     */
    public function getTipoContrato()
    {
        return $this->Lov1->descricao;
    }

    /**
     * Returns the value of field Status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->Lov2->descricao;
    }

    /**
     * Returns the value of field Número Processo Contratação
     *
     * @return string
     */
    public function getNumeroProcessoContratacao()
    {
        return $this->ContratoProcesso->numero_processo;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("contrato");
        $this->hasMany('id', 'Circuitos\Models\Contrato', 'id_contrato_principal', ['alias' => 'Contrato']);
        $this->hasMany('id', 'Circuitos\Models\ContratoAnexo', 'id_contrato', ['alias' => 'ContratoAnexo']);
        $this->hasMany('id', 'Circuitos\Models\ContratoArquivoFisico', 'id_contrato', ['alias' => 'ContratoArquivoFisico']);
        $this->hasMany('id', 'Circuitos\Models\ContratoExercicio', 'id_contrato', ['alias' => 'ContratoExercicio']);
        $this->hasMany('id', 'Circuitos\Models\ContratoFiscalHasContrato', 'id_contrato', ['alias' => 'ContratoFiscalHasContrato']);
        $this->hasMany('id', 'Circuitos\Models\ContratoHasContratoGarantia', 'id_contrato', ['alias' => 'ContratoHasContratoGarantia']);
        $this->hasMany('id', 'Circuitos\Models\ContratoMovimento', 'id_contrato', ['alias' => 'ContratoMovimento']);
        $this->hasMany('id', 'Circuitos\Models\ContratoOrcamento', 'id_contrato', ['alias' => 'ContratoOrcamento']);
        $this->hasMany('id', 'Circuitos\Models\EstacaoTelecon', 'id_contrato', ['alias' => 'EstacaoTelecon']);
        $this->hasMany('id', 'Circuitos\Models\SetEquipamento', 'id_contrato', ['alias' => 'SetEquipamento']);
        $this->hasMany('id', 'Circuitos\Models\ContratoComponentes', 'id_contrato', ['alias' => 'ContratoComponentes']);
        $this->hasMany('id', 'Circuitos\Models\Terreno', 'id_contrato', ['alias' => 'Terreno']);
        $this->hasMany('id', 'Circuitos\Models\Torre', 'id_contrato', ['alias' => 'Torre']);
        $this->belongsTo('id_cliente', 'Circuitos\Models\Cliente', 'id', ['alias' => 'Cliente']);
        $this->belongsTo('id_contrato_principal', 'Circuitos\Models\Contrato', 'id', ['alias' => 'ContratoVinculado']);
        $this->belongsTo('id_fornecedor', 'Circuitos\Models\Cliente', 'id', ['alias' => 'Fornecedor']);
        $this->belongsTo('id_tipo_contrato', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov1']);
        $this->belongsTo('id_status', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov2']);
        $this->belongsTo('id_processo_contratacao', 'Circuitos\Models\ContratoProcesso', 'id', ['alias' => 'ContratoProcesso']);
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

    /**
     * Consulta completa de Contrato, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Contrato|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarContrato($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Contrato" => "Circuitos\Models\Contrato"));
        $query->columns("Contrato.*");
        $query->leftJoin("Circuitos\Models\Lov", "Lov1.id = Contrato.id_tipo_contrato", "Lov1");
        $query->leftJoin("Circuitos\Models\Lov", "Lov2.id = Contrato.id_status", "Lov2");
        $query->leftJoin("Circuitos\Models\ContratoProcesso", "ContratoProcesso.id = Contrato.id_processo_contratacao", "ContratoProcesso");
        $query->leftJoin("Circuitos\Models\Cliente", "Fornecedor.id = Contrato.id_fornecedor", "Fornecedor");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa1.id = Fornecedor.id_pessoa", "Pessoa1");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "PessoaJuridica1.id = Pessoa1.id", "PessoaJuridica1");
        $query->leftJoin("Circuitos\Models\Cliente", "Cliente.id = Contrato.id_Cliente", "Cliente");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = Cliente.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "PessoaJuridica2.id = Pessoa2.id", "PessoaJuridica2");
        $query->where("Contrato.excluido = 0 AND (CONVERT(Contrato.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Contrato.numero USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Contrato.ano USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(ContratoProcesso.codigo_processo USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(ContratoProcesso.numero_processo USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa1.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica1.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov1.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov2.descricao USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("Contrato.id");
        $query->orderBy("Contrato.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
