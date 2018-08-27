<?php

namespace Circuitos\Models;

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
    protected $id_tipounidade;

    /**
     *
     * @var integer
     */
    protected $id_funcao;

    /**
     *
     * @var integer
     */
    protected $id_enlace;

    /**
     *
     * @var integer
     */
    protected $id_usuario_criacao;

    /**
     *
     * @var integer
     */
    protected $id_banda;

    /**
     *
     * @var integer
     */
    protected $id_usuario_atualizacao;

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
    protected $vlan;

    /**
     *
     * @var string
     */
    protected $ccode;

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
     * @var integer
     */
    protected $ativo;

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
     * Method to set the value of field id_tipounidade
     *
     * @param integer $id_tipounidade
     * @return $this
     */
    public function setIdTipounidade($id_tipounidade)
    {
        $this->id_tipounidade = $id_tipounidade;

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
     * Method to set the value of field id_enlace
     *
     * @param integer $id_enlace
     * @return $this
     */
    public function setIdEnlace($id_enlace)
    {
        $this->id_enlace = $id_enlace;

        return $this;
    }

    /**
     * Method to set the value of field id_usuario_criacao
     *
     * @param integer $id_usuario_criacao
     * @return $this
     */
    public function setIdUsuarioCriacao($id_usuario_criacao)
    {
        $this->id_usuario_criacao = $id_usuario_criacao;

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
     * Method to set the value of field id_usuario_atualizacao
     *
     * @param integer $id_usuario_atualizacao
     * @return $this
     */
    public function setIdUsuarioAtualizacao($id_usuario_atualizacao)
    {
        $this->id_usuario_atualizacao = $id_usuario_atualizacao;

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
     * Method to set the value of field vlan
     *
     * @param string $vlan
     * @return $this
     */
    public function setVlan($vlan)
    {
        $this->vlan = $vlan;

        return $this;
    }

    /**
     * Method to set the value of field ccode
     *
     * @param string $ccode
     * @return $this
     */
    public function setCcode($ccode)
    {
        $this->ccode = $ccode;

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
     * Returns the value of field id_tipounidade
     *
     * @return integer
     */
    public function getIdTipounidade()
    {
        return $this->id_tipounidade;
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
     * Returns the value of field id_enlace
     *
     * @return integer
     */
    public function getIdEnlace()
    {
        return $this->id_enlace;
    }

    /**
     * Returns the value of field id_usuario_criacao
     *
     * @return integer
     */
    public function getIdUsuarioCriacao()
    {
        return $this->id_usuario_criacao;
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
     * Returns the value of field id_usuario_atualizacao
     *
     * @return integer
     */
    public function getIdUsuarioAtualizacao()
    {
        return $this->id_usuario_atualizacao;
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
     * Returns the value of field vlan
     *
     * @return string
     */
    public function getVlan()
    {
        return $this->vlan;
    }

    /**
     * Returns the value of field ccode
     *
     * @return string
     */
    public function getCcode()
    {
        return $this->ccode;
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
     * Returns the value of field ativo
     *
     * @return integer
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_circuitosnavega");
        $this->setSource("circuitos");
        $this->hasMany('id', 'Circuitos\Models\Movimentos', 'id_circuitos', ['alias' => 'Movimentos']);
        $this->belongsTo('id_banda', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov7']);
        $this->belongsTo('id_cliente', 'Circuitos\Models\Cliente', 'id', ['alias' => 'Cliente']);
        $this->belongsTo('id_cliente_unidade', 'Circuitos\Models\ClienteUnidade', 'id', ['alias' => 'ClienteUnidade']);
        $this->belongsTo('id_equipamento', 'Circuitos\Models\Equipamento', 'id', ['alias' => 'Equipamento']);
        $this->belongsTo('id_contrato', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov1']);
        $this->belongsTo('id_status', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov2']);
        $this->belongsTo('id_cluster', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov3']);
        $this->belongsTo('id_tipounidade', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov4']);
        $this->belongsTo('id_funcao', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov5']);
        $this->belongsTo('id_enlace', 'Circuitos\Models\Lov', 'id', ['alias' => 'Lov6']);
        $this->belongsTo('id_usuario_criacao', 'Circuitos\Models\Usuario', 'id', ['alias' => 'Usuario1']);
        $this->belongsTo('id_usuario_atualizacao', 'Circuitos\Models\Usuario', 'id', ['alias' => 'Usuario2']);
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
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'id_cliente' => 'id_cliente',
            'id_cliente_unidade' => 'id_cliente_unidade',
            'id_equipamento' => 'id_equipamento',
            'id_contrato' => 'id_contrato',
            'id_status' => 'id_status',
            'id_cluster' => 'id_cluster',
            'id_tipounidade' => 'id_tipounidade',
            'id_funcao' => 'id_funcao',
            'id_enlace' => 'id_enlace',
            'id_usuario_criacao' => 'id_usuario_criacao',
            'id_banda' => 'id_banda',
            'id_usuario_atualizacao' => 'id_usuario_atualizacao',
            'designacao' => 'designacao',
            'uf' => 'uf',
            'cidade' => 'cidade',
            'vlan' => 'vlan',
            'ccode' => 'ccode',
            'ip_redelocal' => 'ip_redelocal',
            'ip_gerencia' => 'ip_gerencia',
            'tag' => 'tag',
            'observacao' => 'observacao',
            'data_ativacao' => 'data_ativacao',
            'data_atualizacao' => 'data_atualizacao',
            'excluido' => 'excluido'
        ];
    }

}
