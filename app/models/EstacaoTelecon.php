<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Resultset;

class EstacaoTelecon extends \Phalcon\Mvc\Model
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
    protected $id_cidade_digital;

    /**
     *
     * @var integer
     */
    protected $id_contrato;

    /**
     *
     * @var integer
     */
    protected $id_terreno;

    /**
     *
     * @var integer
     */
    protected $id_torre;

    /**
     *
     * @var integer
     */
    protected $id_set_equipamento;

    /**
     *
     * @var integer
     */
    protected $id_set_seguranca;

    /**
     *
     * @var integer
     */
    protected $id_unidade_consumidora;

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
     * Method to set the value of field id_cidade_digital
     *
     * @param integer $id_cidade_digital
     * @return $this
     */
    public function setIdCidadeDigital($id_cidade_digital)
    {
        $this->id_cidade_digital = $id_cidade_digital;

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
     * Method to set the value of field id_terreno
     *
     * @param integer $id_terreno
     * @return $this
     */
    public function setIdTerreno($id_terreno)
    {
        $this->id_terreno = $id_terreno;

        return $this;
    }

    /**
     * Method to set the value of field id_torre
     *
     * @param integer $id_torre
     * @return $this
     */
    public function setIdTorre($id_torre)
    {
        $this->id_torre = $id_torre;

        return $this;
    }

    /**
     * Method to set the value of field id_set_equipamento
     *
     * @param integer $id_set_equipamento
     * @return $this
     */
    public function setIdSetEquipamento($id_set_equipamento)
    {
        $this->id_set_equipamento = $id_set_equipamento;

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
     * Method to set the value of field id_unidade_consumidora
     *
     * @param integer $id_unidade_consumidora
     * @return $this
     */
    public function setIdUnidadeConsumidora($id_unidade_consumidora)
    {
        $this->id_unidade_consumidora = $id_unidade_consumidora;

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
     * Returns the value of field id_cidade_digital
     *
     * @return integer
     */
    public function getIdCidadeDigital()
    {
        return $this->id_cidade_digital;
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
     * Returns the value of field id_terreno
     *
     * @return integer
     */
    public function getIdTerreno()
    {
        return $this->id_terreno;
    }

    /**
     * Returns the value of field id_torre
     *
     * @return integer
     */
    public function getIdTorre()
    {
        return $this->id_torre;
    }

    /**
     * Returns the value of field id_set_equipamento
     *
     * @return integer
     */
    public function getIdSetEquipamento()
    {
        return $this->id_set_equipamento;
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
     * Returns the value of field id_unidade_consumidora
     *
     * @return integer
     */
    public function getIdUnidadeConsumidora()
    {
        return $this->id_unidade_consumidora;
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
     * Returns the value of field cidade digital
     *
     * @return string
     */
    public function getCidadeDigital()
    {
        return $this->CidadeDigital->descricao;
    }

    /**
     * Returns the value of field set equipamento
     *
     * @return string
     */
    public function getSetEquipamento()
    {
        return $this->SetEquipamento->descricao;
    }

    /**
     * Returns the value of field Set Seguranca
     *
     * @return string
     */
    public function getSetSeguranca()
    {
        return $this->SetSeguranca->descricao;
    }

    /**
     * Returns the value of field Terreno
     *
     * @return string
     */
    public function getTerreno()
    {
        return $this->Terreno->descricao;
    }

    /**
     * Returns the value of field Torre
     *
     * @return string
     */
    public function getTorre()
    {
        return $this->Torre->descricao;
    }

    /**
     * Returns the value of field CEP
     *
     * @return string
     */
    public function getCep()
    {
        return $this->Terreno->cep;
    }

    /**
     * Returns the value of field Endereço
     *
     * @return string
     */
    public function getEndereco()
    {
        return $this->Terreno->endereco;
    }

    /**
     * Returns the value of field numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->Terreno->numero;
    }

    /**
     * Returns the value of field bairro
     *
     * @return string
     */
    public function getBairro()
    {
        return $this->Terreno->bairro;
    }

    /**
     * Returns the value of field cidade
     *
     * @return string
     */
    public function getCidade()
    {
        return $this->Terreno->cidade;
    }

    /**
     * Returns the value of field estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->Terreno->estado;
    }

    /**
     * Returns the value of field sigla_estado
     *
     * @return string
     */
    public function getSiglaEstado()
    {
        return $this->Terreno->sigla_estado;
    }

    /**
     * Returns the value of field latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->Terreno->latitude;
    }

    /**
     * Returns the value of field longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->Terreno->longitude;
    }

    /**
     * Returns the value of field sigla_estado
     *
     * @return string
     */
    public function getUnidadeConsumidora()
    {
        return $this->UnidadeConsumidora->codigo_conta_contrato;
    }

    /**
     * Returns the value of tipo de conectividade
     *
     * @return string
     */
    public function getNomeCidadeDigital()
    {
        return $this->CidadeDigital->descricao;
    }

    /**
     * Returns the value of field Contrato
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
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("estacao_telecon");
        $this->belongsTo('id_cidade_digital', 'Circuitos\Models\CidadeDigital', 'id', ['alias' => 'CidadeDigital']);
        $this->belongsTo('id_contrato', 'Circuitos\Models\Contrato', 'id', ['alias' => 'Contrato']);
        $this->belongsTo('id_set_equipamento', 'Circuitos\Models\SetEquipamento', 'id', ['alias' => 'SetEquipamento']);
        $this->belongsTo('id_set_seguranca', 'Circuitos\Models\SetSeguranca', 'id', ['alias' => 'SetSeguranca']);
        $this->belongsTo('id_terreno', 'Circuitos\Models\Terreno', 'id', ['alias' => 'Terreno']);
        $this->belongsTo('id_torre', 'Circuitos\Models\Torre', 'id', ['alias' => 'Torre']);
        $this->belongsTo('id_unidade_consumidora', 'Circuitos\Models\UnidadeConsumidora', 'id', ['alias' => 'UnidadeConsumidora']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'estacao_telecon';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EstacaoTelecon[]|EstacaoTelecon|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EstacaoTelecon|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de EstacaoTelecon, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return EstacaoTelecon|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarEstacaoTelecon($parameters = null)
    {
        $query = new Builder();
        $query->from(array("EstacaoTelecon" => "Circuitos\Models\EstacaoTelecon"));
        $query->columns("EstacaoTelecon.*");
        $query->leftJoin("Circuitos\Models\CidadeDigital", "CidadeDigital.id = EstacaoTelecon.id_cidade_digital", "CidadeDigital");
        $query->leftJoin("Circuitos\Models\Contrato", "Contrato.id = EstacaoTelecon.id_contrato", "Contrato");
        $query->leftJoin("Circuitos\Models\Terreno", "Terreno.id = EstacaoTelecon.id_terreno", "Terreno");
        $query->leftJoin("Circuitos\Models\Torre", "Torre.id = EstacaoTelecon.id_torre", "Torre");
        $query->leftJoin("Circuitos\Models\SetSeguranca", "SetSeguranca.id = EstacaoTelecon.id_set_seguranca", "SetSeguranca");
        $query->leftJoin("Circuitos\Models\SetEquipamento", "SetEquipamento.id = EstacaoTelecon.id_set_equipamento", "SetEquipamento");
        $query->where("EstacaoTelecon.excluido = 0 AND (CONVERT(EstacaoTelecon.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(EstacaoTelecon.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(CidadeDigital.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Terreno.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Torre.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(SetSeguranca.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(SetEquipamento.descricao USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("EstacaoTelecon.id");
        $query->orderBy("EstacaoTelecon.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
