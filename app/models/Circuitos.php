<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Util\Infra;

class Circuitos extends \Phalcon\Mvc\Model
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
    protected $id_cliente_unidade;

    /**
     *
     * @var integer
     */
    protected $id_equipamento;

    /**
     *
     * @var integer
     */
    protected $id_contrato;

    /**
     *
     * @var integer
     */
    protected $id_status;

    /**
     *
     * @var integer
     */
    protected $id_cluster;

    /**
     *
     * @var integer
     */
    protected $id_tipoacesso;

    /**
     *
     * @var integer
     */
    protected $id_funcao;

    /**
     *
     * @var integer
     */
    protected $id_tipolink;

    /**
     *
     * @var integer
     */
    protected $excluido;

    /**
     *
     * @var integer
     */
    protected $id_banda;

    /**
     *
     * @var integer
     */
    protected $id_cidadedigital;

    /**
     *
     * @var integer
     */
    protected $id_conectividade;

    /**
     *
     * @var string
     */
    protected $chamado;

    /**
     *
     * @var string
     */
    protected $designacao;

    /**
     *
     * @var string
     */
    protected $uf;

    /**
     *
     * @var string
     */
    protected $cidade;

    /**
     *
     * @var string
     */
    protected $designacao_anterior;

    /**
     *
     * @var string
     */
    protected $ssid;

    /**
     *
     * @var string
     */
    protected $ip_redelocal;

    /**
     *
     * @var string
     */
    protected $ip_gerencia;

    /**
     *
     * @var string
     */
    protected $tag;

    /**
     *
     * @var string
     */
    protected $observacao;

    /**
     *
     * @var string
     */
    protected $data_ativacao;

    /**
     *
     * @var string
     */
    protected $data_atualizacao;

    /**
     *
     * @var string
     */
    protected $data_desinstalacao;

    /**
     *
     * @var integer
     */
    protected $id_empresa_departamento;

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
     * Method to set the value of field id_cliente_unidade
     *
     * @param integer $id_cliente_unidade
     * @return $this
     */
    public function setIdClienteUnidade($id_cliente_unidade)
    {
        $this->id_cliente_unidade = $id_cliente_unidade;

        return $this;
    }

    /**
     * Method to set the value of field id_equipamento
     *
     * @param integer $id_equipamento
     * @return $this
     */
    public function setIdEquipamento($id_equipamento)
    {
        $this->id_equipamento = $id_equipamento;

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
     * Method to set the value of field id_cluster
     *
     * @param integer $id_cluster
     * @return $this
     */
    public function setIdCluster($id_cluster)
    {
        $this->id_cluster = $id_cluster;

        return $this;
    }

    /**
     * Method to set the value of field id_tipoacesso
     *
     * @param integer $id_tipoacesso
     * @return $this
     */
    public function setIdTipoacesso($id_tipoacesso)
    {
        $this->id_tipoacesso = $id_tipoacesso;

        return $this;
    }

    /**
     * Method to set the value of field id_funcao
     *
     * @param integer $id_funcao
     * @return $this
     */
    public function setIdFuncao($id_funcao)
    {
        $this->id_funcao = $id_funcao;

        return $this;
    }

    /**
     * Method to set the value of field id_tipolink
     *
     * @param integer $id_tipolink
     * @return $this
     */
    public function setIdTipolink($id_tipolink)
    {
        $this->id_tipolink = $id_tipolink;

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
     * Method to set the value of field id_banda
     *
     * @param integer $id_banda
     * @return $this
     */
    public function setIdBanda($id_banda)
    {
        $this->id_banda = $id_banda;

        return $this;
    }

    /**
     * Method to set the value of field chamado
     *
     * @param string $chamado
     * @return $this
     */
    public function setChamado($chamado)
    {
        $this->chamado = $chamado;

        return $this;
    }

    /**
     * Method to set the value of field designacao
     *
     * @param string $designacao
     * @return $this
     */
    public function setDesignacao($designacao)
    {
        $this->designacao = $designacao;

        return $this;
    }

    /**
     * Method to set the value of field uf
     *
     * @param string $uf
     * @return $this
     */
    public function setUf($uf)
    {
        $this->uf = $uf;

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
     * Method to set the value of field designacao_anterior
     *
     * @param string $designacao_anterior
     * @return $this
     */
    public function setDesignacaoAnterior($designacao_anterior)
    {
        $this->designacao_anterior = $designacao_anterior;

        return $this;
    }

    /**
     * Method to set the value of field ssid
     *
     * @param string $ssid
     * @return $this
     */
    public function setSsid($ssid)
    {
        $this->ssid = $ssid;

        return $this;
    }

    /**
     * Method to set the value of field ip_redelocal
     *
     * @param string $ip_redelocal
     * @return $this
     */
    public function setIpRedelocal($ip_redelocal)
    {
        $this->ip_redelocal = $ip_redelocal;

        return $this;
    }

    /**
     * Method to set the value of field ip_gerencia
     *
     * @param string $ip_gerencia
     * @return $this
     */
    public function setIpGerencia($ip_gerencia)
    {
        $this->ip_gerencia = $ip_gerencia;

        return $this;
    }

    /**
     * Method to set the value of field tag
     *
     * @param string $tag
     * @return $this
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

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
     * Method to set the value of field data_ativacao
     *
     * @param string $data_ativacao
     * @return $this
     */
    public function setDataAtivacao($data_ativacao)
    {
        $this->data_ativacao = $data_ativacao;

        return $this;
    }

    /**
     * Method to set the value of field data_atualizacao
     *
     * @param string $data_atualizacao
     * @return $this
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;

        return $this;
    }

    /**
     * @param integer $id_cidadedigital
     * @return $this
     */
    public function setIdCidadedigital($id_cidadedigital)
    {
        $this->id_cidadedigital = $id_cidadedigital;

        return $this;
    }

    /**
     * @param int $id_conectividade
     */
    public function setIdConectividade($id_conectividade)
    {
        $this->id_conectividade = $id_conectividade;

        return $this;
    }

    /**
     * Returns the value of field id_conectividade
     *
     * @return int
     */
    public function getIdConectividade()
    {
        return $this->id_conectividade;
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
     * Returns the value of field id_cliente_unidade
     *
     * @return integer
     */
    public function getIdClienteUnidade()
    {
        return $this->id_cliente_unidade;
    }

    /**
     * Returns the value of field id_equipamento
     *
     * @return integer
     */
    public function getIdEquipamento()
    {
        return $this->id_equipamento;
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
     * Returns the value of field id_status
     *
     * @return integer
     */
    public function getIdStatus()
    {
        return $this->id_status;
    }

    /**
     * Returns the value of field id_cluster
     *
     * @return integer
     */
    public function getIdCluster()
    {
        return $this->id_cluster;
    }

    /**
     * Returns the value of field id_tipoacesso
     *
     * @return integer
     */
    public function getIdTipoacesso()
    {
        return $this->id_tipoacesso;
    }

    /**
     * Returns the value of field id_funcao
     *
     * @return integer
     */
    public function getIdFuncao()
    {
        return $this->id_funcao;
    }

    /**
     * Returns the value of field id_tipolink
     *
     * @return integer
     */
    public function getIdTipolink()
    {
        return $this->id_tipolink;
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
     * Returns the value of field id_banda
     *
     * @return integer
     */
    public function getIdBanda()
    {
        return $this->id_banda;
    }

    /**
     * Returns the value of field chamado
     *
     * @return string
     */
    public function getChamado()
    {
        return $this->chamado;
    }

    /**
     * Returns the value of field designacao
     *
     * @return string
     */
    public function getDesignacao()
    {
        return $this->designacao;
    }

    /**
     * Returns the value of field uf
     *
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
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
     * Returns the value of field designacao_anterior
     *
     * @return string
     */
    public function getDesignacaoAnterior()
    {
        return $this->designacao_anterior;
    }

    /**
     * Returns the value of field ssid
     *
     * @return string
     */
    public function getSsid()
    {
        return $this->ssid;
    }

    /**
     * Returns the value of field ip_redelocal
     *
     * @return string
     */
    public function getIpRedelocal()
    {
        return $this->ip_redelocal;
    }

    /**
     * Returns the value of field ip_gerencia
     *
     * @return string
     */
    public function getIpGerencia()
    {
        return $this->ip_gerencia;
    }

    /**
     * Returns the value of field tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
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
     * Returns the value of field data_ativacao
     *
     * @return string
     */
    public function getDataAtivacao()
    {
        return $this->data_ativacao;
    }

    /**
     * Returns the value of field data_atualizacao
     *
     * @return string
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * @return int
     */
    public function getIdCidadedigital()
    {
        return $this->id_cidadedigital;
    }


    /**
     * Returns the value of Cliente Nome
     *
     * @return string
     */
    public function getClienteNome()
    {
        return $this->Cliente->Pessoa->nome;
    }


    /**
     * Returns the value of Cliente Unidade Nome
     *
     * @return string
     */
    public function getClienteUnidadeNome()
    {
        return $this->ClienteUnidade->Pessoa->nome;
    }


    /**
     * Returns the value of Cidade Digital Nome
     *
     * @return string
     */
    public function getCidadeDigitalNome()
    {
        return $this->CidadeDigital->descricao;
    }


    /**
     * Returns the value of Conectividade Nome
     *
     * @return string
     */
    public function getConectividadeNome()
    {
        return $this->Conectividade->Lov->descricao . " " . $this->Conectividade->descricao;
    }


    /**
     * Returns the value of Fabricante Nome
     *
     * @return string
     */
    public function getFabricanteNome()
    {
        return $this->Equipamento->Fabricante->Pessoa->nome;
    }


    /**
     * Returns the value of Modelo Nome
     *
     * @return string
     */
    public function getModeloNome()
    {
        return $this->Equipamento->Modelo->modelo;
    }


    /**
     * Returns the value of Equipamento Nome
     *
     * @return string
     */
    public function getEquipamentoNome()
    {
        return $this->Equipamento->nome;
    }


    /**
     * Returns the value of Equipamento Patromonio
     *
     * @return string
     */
    public function getEquipamentoPatrimonio()
    {
        return $this->Equipamento->numpatrimonio;
    }


    /**
     * Returns the value of Equipamento Num Série
     *
     * @return string
     */
    public function getEquipamentoSerie()
    {
        return $this->Equipamento->numserie;
    }

    /**
     * Returns the value of id_tipocliente
     *
     * @return int
     */
    public function getIdTipoCliente()
    {
        return $this->Cliente->id_tipocliente;
    }

    /**
     * Returns the value of id_fabricante
     *
     * @return int
     */
    public function getIdFabricante()
    {
        return $this->Equipamento->Fabricante->id;
    }

    /**
     * Returns the value of id_modelo
     *
     * @return int
     */
    public function getIdModelo()
    {
        return $this->Equipamento->Modelo->id;
    }

    /**
     * Returns the value of Status do Circuito
     *
     * @return string
     */
    public function getStatusCircuito()
    {
        return $this->Lov2->descricao;
    }

    /**
     * Returns the value of Contrato do Circuito
     *
     * @return string
     */
    public function getContratoCircuito()
    {
        return $this->Lov1->descricao;
    }

    /**
     * Returns the value of Função do Circuito
     *
     * @return string
     */
    public function getFuncaoCircuito()
    {
        return $this->Lov5->descricao;
    }

    /**
     * Returns the value of Tipo de Link do Circuito
     *
     * @return string
     */
    public function getTipoLinkCircuito()
    {
        return $this->Lov6->descricao;
    }

    /**
     * Returns the value of Banda do Circuito
     *
     * @return string
     */
    public function getBandaCircuito()
    {
        return $this->Lov7->descricao;
    }

    /**
     * Returns the value of Id Tipo de Movimento Criação
     *
     * @return int
     */
    public function getIdMovimentoCriacaoCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=1");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Status do Circuito
     *
     * @return int
     */
    public function getIdStatusInicialCircuito()
    {
        $status = Lov::findFirst("tipo=6 AND valor=1");
        return $status->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento Exclusão
     *
     * @return int
     */
    public function getIdMovimentoExclusaoCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=2");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento Edição
     *
     * @return int
     */
    public function getIdMovimentoEdicaoCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=3");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento Banda
     *
     * @return int
     */
    public function getIdMovimentoBandaCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=4");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento Status
     *
     * @return int
     */
    public function getIdMovimentoStatusCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=5");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento IP Gerencial
     *
     * @return int
     */
    public function getIdMovimentoIpGerenciaCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=6");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento IP Local
     *
     * @return int
     */
    public function getIdMovimentoIpLocalCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=7");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento Equipamento
     *
     * @return int
     */
    public function getIdMovimentoEquipamentoCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=8");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento Cliente
     *
     * @return int
     */
    public function getIdMovimentoClienteCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=9");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento Cliente Unidade
     *
     * @return int
     */
    public function getIdMovimentoClienteUnidadeCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=10");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento Cidade Digital
     *
     * @return int
     */
    public function getIdMovimentoCidadeDigitalCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=11");
        return $movimento->getId();
    }

    /**
     * Returns the value of Id Tipo de Movimento Conectividade
     *
     * @return int
     */
    public function getIdMovimentoConectividadeCircuito()
    {
        $movimento = Lov::findFirst("tipo=16 AND valor=12");
        return $movimento->getId();
    }

    /**
     * @return string
     */
    public function getDataDesinstalacao()
    {
        return $this->data_desinstalacao;
    }

    /**
     * @param string $data_desinstalacao
     */
    public function setDataDesinstalacao($data_desinstalacao)
    {
        $this->data_desinstalacao = $data_desinstalacao;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdEmpresaDepartamento()
    {
        return $this->id_empresa_departamento;
    }

    /**
     * @param int $id_empresa_departamento
     */
    public function setIdEmpresaDepartamento($id_empresa_departamento)
    {
        $this->id_empresa_departamento = $id_empresa_departamento;

        return $this;
    }


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("circuitos");
        $this->hasMany("id", "Circuitos\Models\Movimentos", "id_circuitos", ["alias" => "Movimentos"]);
        $this->belongsTo("id_banda", "Circuitos\Models\Lov", "id", ["alias" => "Lov7"]);
        $this->belongsTo("id_cliente", "Circuitos\Models\Cliente", "id", ["alias" => "Cliente"]);
        $this->belongsTo("id_cliente_unidade", "Circuitos\Models\ClienteUnidade", "id", ["alias" => "ClienteUnidade"]);
        $this->belongsTo("id_equipamento", "Circuitos\Models\Equipamento", "id", ["alias" => "Equipamento"]);
        $this->belongsTo("id_contrato", "Circuitos\Models\Lov", "id", ["alias" => "Lov1"]);
        $this->belongsTo("id_status", "Circuitos\Models\Lov", "id", ["alias" => "Lov2"]);
        $this->belongsTo("id_cluster", "Circuitos\Models\Lov", "id", ["alias" => "Lov3"]);
        $this->belongsTo("id_tipoacesso", "Circuitos\Models\Lov", "id", ["alias" => "Lov4"]);
        $this->belongsTo("id_funcao", "Circuitos\Models\Lov", "id", ["alias" => "Lov5"]);
        $this->belongsTo("id_tipolink", "Circuitos\Models\Lov", "id", ["alias" => "Lov6"]);
        $this->belongsTo("id_cidadedigital", "Circuitos\Models\CidadeDigital", "id", ["alias" => "CidadeDigital"]);
        $this->belongsTo("id_conectividade", "Circuitos\Models\Conectividade", "id", ["alias" => "Conectividade"]);
        $this->belongsTo("id_empresa_departamento", "Circuitos\Models\EmpresaDepartamento", "id", ["alias" => "EmpresaDepartamento"]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'circuitos';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Circuitos[]|Circuitos|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Circuitos|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de circuitos, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarCircuitos($parameters = null)
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("Circuitos.*");

        $query->leftJoin("Circuitos\Models\Cliente", "Cliente.id = Circuitos.id_cliente", "Cliente");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa1.id = Cliente.id_pessoa", "Pessoa1");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa1.id = PessoaJuridica1.id", "PessoaJuridica1");
        $query->leftJoin("Circuitos\Models\ClienteUnidade", "ClienteUnidade.id = Circuitos.id_cliente_unidade", "ClienteUnidade");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = ClienteUnidade.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\Equipamento", "Circuitos.id_equipamento = Equipamento.id", "Equipamento");
        $query->leftJoin("Circuitos\Models\Modelo", "Equipamento.id_modelo = Modelo.id", "Modelo");
        $query->leftJoin("Circuitos\Models\Fabricante", "Modelo.id_fabricante = Fabricante.id", "Fabricante");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa3.id = Fabricante.id_pessoa", "Pessoa3");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa3.id = PessoaJuridica3.id", "PessoaJuridica3");
        $query->leftJoin("Circuitos\Models\CidadeDigital", "Circuitos.id_cidadedigital = CidadeDigital.id", "CidadeDigital");
        $query->leftJoin("Circuitos\Models\Conectividade", "Circuitos.id_conectividade = Conectividade.id", "Conectividade");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_contrato = Lov1.id", "Lov1");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_status = Lov2.id", "Lov2");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_cluster = Lov3.id", "Lov3");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_funcao = Lov4.id", "Lov4");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_tipoacesso = Lov5.id", "Lov5");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_banda = Lov6.id", "Lov6");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_tipolink = Lov7.id", "Lov7");

        $query->where("Circuitos.excluido = 0 AND (CONVERT(Circuitos.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa1.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa2.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica1.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica2.razaosocial USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Equipamento.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Equipamento.numserie USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Equipamento.numpatrimonio USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Modelo.modelo USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa3.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov1.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov2.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov3.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov4.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov5.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov6.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Lov7.descricao USING utf8) LIKE '%{$parameters}%'                        
                        OR CONVERT(CidadeDigital.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Conectividade.descricao USING utf8) LIKE '%{$parameters}%'                        
                        OR CONVERT(Circuitos.designacao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.designacao_anterior USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.uf USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.cidade USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.ssid USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.ip_gerencia USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.ip_redelocal USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.tag USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Circuitos.chamado USING utf8) LIKE '%{$parameters}%')");

        $query->groupBy("Circuitos.id");

        $query->orderBy("Circuitos.id DESC");

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta completa de circuitos para a geração de relatórios, incluíndo os joins de tabelas e filtros
     *
     * @param string $parameters
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarRelatorioCircuitos($colunas, $where, $orderby)
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns($colunas);

        $query->leftJoin("Circuitos\Models\Cliente", "Cliente.id = Circuitos.id_cliente", "Cliente");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa1.id = Cliente.id_pessoa", "Pessoa1");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa1.id = PessoaJuridica1.id", "PessoaJuridica1");
        $query->leftJoin("Circuitos\Models\Lov", "PessoaJuridica1.id_tipoesfera = Love.id", "Love");
        $query->leftJoin("Circuitos\Models\Lov", "PessoaJuridica1.id_setor = Lovs.id", "Lovs");
        $query->leftJoin("Circuitos\Models\ClienteUnidade", "ClienteUnidade.id = Circuitos.id_cliente_unidade", "ClienteUnidade");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = ClienteUnidade.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\Equipamento", "Circuitos.id_equipamento = Equipamento.id", "Equipamento");
        $query->leftJoin("Circuitos\Models\Modelo", "Equipamento.id_modelo = Modelo.id", "Modelo");
        $query->leftJoin("Circuitos\Models\Fabricante", "Modelo.id_fabricante = Fabricante.id", "Fabricante");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa3.id = Fabricante.id_pessoa", "Pessoa3");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa3.id = PessoaJuridica3.id", "PessoaJuridica3");
        $query->leftJoin("Circuitos\Models\CidadeDigital", "Circuitos.id_cidadedigital = CidadeDigital.id", "CidadeDigital");
        $query->leftJoin("Circuitos\Models\Conectividade", "Circuitos.id_conectividade = Conectividade.id", "Conectividade");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_contrato = Lov1.id", "Lov1");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_status = Lov2.id", "Lov2");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_funcao = Lov4.id", "Lov4");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_tipoacesso = Lov5.id", "Lov5");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_banda = Lov6.id", "Lov6");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_tipolink = Lov7.id", "Lov7");

        $query->where($where);
        $query->orderBy($orderby);

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta completa de circuitos para a geração de relatórios, incluíndo os joins de tabelas e sem filtros
     *
     * @param string $parameters
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarRelatorioCircuitosSF($colunas, $orderby)
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns($colunas);

        $query->leftJoin("Circuitos\Models\Cliente", "Cliente.id = Circuitos.id_cliente", "Cliente");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa1.id = Cliente.id_pessoa", "Pessoa1");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa1.id = PessoaJuridica1.id", "PessoaJuridica1");
        $query->leftJoin("Circuitos\Models\ClienteUnidade", "ClienteUnidade.id = Circuitos.id_cliente_unidade", "ClienteUnidade");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa2.id = ClienteUnidade.id_pessoa", "Pessoa2");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa2.id = PessoaJuridica2.id", "PessoaJuridica2");
        $query->leftJoin("Circuitos\Models\Equipamento", "Circuitos.id_equipamento = Equipamento.id", "Equipamento");
        $query->leftJoin("Circuitos\Models\Modelo", "Equipamento.id_modelo = Modelo.id", "Modelo");
        $query->leftJoin("Circuitos\Models\Fabricante", "Modelo.id_fabricante = Fabricante.id", "Fabricante");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa3.id = Fabricante.id_pessoa", "Pessoa3");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "Pessoa3.id = PessoaJuridica3.id", "PessoaJuridica3");
        $query->leftJoin("Circuitos\Models\CidadeDigital", "Circuitos.id_cidadedigital = CidadeDigital.id", "CidadeDigital");
        $query->leftJoin("Circuitos\Models\Conectividade", "Circuitos.id_conectividade = Conectividade.id", "Conectividade");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_contrato = Lov1.id", "Lov1");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_status = Lov2.id", "Lov2");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_funcao = Lov4.id", "Lov4");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_tipoacesso = Lov5.id", "Lov5");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_banda = Lov6.id", "Lov6");
        $query->leftJoin("Circuitos\Models\Lov", "Circuitos.id_tipolink = Lov7.id", "Lov7");

        $query->orderBy($orderby);

        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de circuitos por status
     *
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function circuitoStatus()
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("Lov.descricao AS status, COUNT(Circuitos.id_status) AS total");
        $query->innerJoin("Circuitos\Models\Lov", "Lov.id = Circuitos.id_status", "Lov");
        $query->where("Circuitos.excluido = 0");
        $query->groupBy("Lov.descricao");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de circuitos por status no mês
     *
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function circuitoStatusMes($dt_inicial, $dt_final)
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("Lov.descricao AS status, COUNT(Circuitos.id_status) AS total");
        $query->innerJoin("Circuitos\Models\Lov", "Lov.id = Circuitos.id_status", "Lov");
        $query->innerJoin("Circuitos\Models\Movimentos", "Circuitos.id = Movimentos.id_circuitos", "Movimentos");
        $query->where("Circuitos.excluido = 0 AND Movimentos.id_tipomovimento=64 AND Movimentos.data_movimento BETWEEN '{$dt_inicial} 00:00:00' AND '{$dt_final} 23:59:59'");
        $query->groupBy("Lov.descricao");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de circuitos por função
     *
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function circuitoFuncao()
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("Lov.descricao AS funcao, COUNT(Circuitos.id_funcao) AS total");
        $query->innerJoin("Circuitos\Models\Lov", "Lov.id = Circuitos.id_funcao", "Lov");
        $query->where("Circuitos.excluido = 0");
        $query->groupBy("Lov.descricao");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de circuitos por acesso
     *
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function circuitoAcesso()
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("Lov.descricao AS acesso, COUNT(Circuitos.id_tipoacesso) AS total");
        $query->innerJoin("Circuitos\Models\Lov", "Lov.id = Circuitos.id_tipoacesso", "Lov");
        $query->where("Circuitos.excluido = 0");
        $query->groupBy("Lov.descricao");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de circuitos por link
     *
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function circuitoLink()
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("Lov.descricao AS link, COUNT(Circuitos.id_tipolink) AS total");
        $query->innerJoin("Circuitos\Models\Lov", "Lov.id = Circuitos.id_tipolink", "Lov");
        $query->where("Circuitos.excluido = 0");
        $query->groupBy("Lov.descricao");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de circuitos por link no mês
     *
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function circuitoLinkMes($dt_inicial, $dt_final)
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("Lov.descricao AS link, COUNT(Circuitos.id_tipolink) AS total");
        $query->innerJoin("Circuitos\Models\Lov", "Lov.id = Circuitos.id_tipolink", "Lov");
        $query->where("Circuitos.excluido = 0 AND Circuitos.data_ativacao BETWEEN '{$dt_inicial} 00:00:00' AND '{$dt_final} 23:59:59'");
        $query->groupBy("Lov.descricao");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de circuitos por esfera
     *
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function circuitoEsfera()
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("Lov.descricao AS cliente_esfera, count(PessoaJuridica.id_tipoesfera) AS total");
        $query->innerJoin("Circuitos\Models\Cliente", "Cliente.id = Circuitos.id_cliente", "Cliente");
        $query->innerJoin("Circuitos\Models\Pessoa", "Pessoa.id = Cliente.id_pessoa", "Pessoa");
        $query->innerJoin("Circuitos\Models\PessoaJuridica", "PessoaJuridica.id = Pessoa.id", "PessoaJuridica");
        $query->innerJoin("Circuitos\Models\Lov", "Lov.id = PessoaJuridica.id_tipoesfera", "Lov");
        $query->where("Circuitos.excluido = 0");
        $query->groupBy("Lov.descricao");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de circuitos por conectividade
     *
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function circuitoConectividade()
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("Lov.descricao AS conectividade, COUNT(Circuitos.id_conectividade) AS total");
        $query->innerJoin("Circuitos\Models\Conectividade", "Conectividade.id = Circuitos.id_conectividade", "Conectividade");
        $query->innerJoin("Circuitos\Models\Lov", "Lov.id = Conectividade.id_tipo", "Lov");
        $query->where("Circuitos.excluido = 0");
        $query->groupBy("Lov.descricao");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta para gráfico de hotzones por cidade
     *
     * @return Circuitos|\Phalcon\Mvc\Model\Resultset
     */
    public static function circuitoHotzoneCidade()
    {
        $query = new Builder();
        $query->from(array("Circuitos" => "Circuitos\Models\Circuitos"));
        $query->columns("EndCidade.cidade AS descricao, COUNT(Circuitos.id) AS total");
        $query->innerJoin("Circuitos\Models\Lov", "Lov.id = Circuitos.id_funcao", "Lov");
        $query->innerJoin("Circuitos\Models\CidadeDigital", "Circuitos.id_cidadedigital = CidadeDigital.id", "CidadeDigital");
        $query->innerJoin("Circuitos\Models\EndCidade", "CidadeDigital.id_cidade = EndCidade.id", "EndCidade");
        $query->where("Circuitos.excluido = 0 AND Circuitos.id_funcao = 8");
        $query->groupBy("EndCidade.cidade");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
