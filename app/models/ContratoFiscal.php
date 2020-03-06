<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Util\Infra;

class ContratoFiscal extends \Phalcon\Mvc\Model
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
    protected $id_usuario;

    /**
     *
     * @var integer
     */
    protected $id_fiscal_suplente;

    /**
     *
     * @var integer
     */
    protected $tipo_fiscal;

    /**
     *
     * @var string
     */
    protected $data_criacao;

    /**
     *
     * @var string
     */
    protected $data_nomeacao;

    /**
     *
     * @var string
     */
    protected $documento_nomeacao;

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
     * Method to set the value of field id_fiscal_suplente
     *
     * @param integer $id_fiscal_suplente
     * @return $this
     */
    public function setIdFiscalSuplente($id_fiscal_suplente)
    {
        $this->id_fiscal_suplente = $id_fiscal_suplente;

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
     * Method to set the value of field documento_nomeacao
     *
     * @param string $documento_nomeacao
     * @return $this
     */
    public function setDocumentoNomeacao($documento_nomeacao)
    {
        $this->documento_nomeacao = $documento_nomeacao;

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
     * Returns the value of field id_usuario
     *
     * @return integer
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * Returns the value of field id_fiscal_suplente
     *
     * @return integer
     */
    public function getIdFiscalSuplente()
    {
        return $this->id_fiscal_suplente;
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
     * Returns the value of field documento_nomeacao
     *
     * @return string
     */
    public function getDocumentoNomeacao()
    {
        return $this->documento_nomeacao;
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
     * @return int
     */
    public function getTipoFiscal()
    {
        return $this->tipo_fiscal;
    }

    /**
     * @param int $tipo_fiscal
     */
    public function setTipoFiscal($tipo_fiscal)
    {
        $this->tipo_fiscal = $tipo_fiscal;

        return $this;
    }

    /**
     * @return string
     */
    public function getDataNomeacao()
    {
        return $this->data_nomeacao;
    }

    /**
     * @param string $data_nomeacao
     */
    public function setDataNomeacao($data_nomeacao)
    {
        $this->data_nomeacao = $data_nomeacao;

        return $this;
    }

    /**
     * @return string
     */
    public function getNomeFiscal()
    {
        return $this->Usuario->Pessoa->nome;
    }

    /**
     * @return string
     */
    public function getNomeFiscalSuplente()
    {
        return $this->UsuarioSuplente->Pessoa->nome;
    }

    /**
     * @return integer
     */
    public function getIdContrato()
    {
        return $this->ContratoFiscalHasContratoUnico->id_contrato;
    }

    /**
     * @return string
     */
    public function getNumeroContrato()
    {
        return $this->ContratoFiscalHasContratoUnico->Contrato->numero;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("contrato_fiscal");
        $this->hasMany('id', 'Circuitos\Models\ContratoFiscalHasContrato', 'id_contrato_fiscal', ['alias' => 'ContratoFiscalHasContrato']);
        $this->belongsTo('id_fiscal_suplente', 'Circuitos\Models\Usuario', 'id', ['alias' => 'UsuarioSuplente']);
        $this->belongsTo('id_usuario', 'Circuitos\Models\Usuario', 'id', ['alias' => 'Usuario']);
        $this->belongsTo('id', 'Circuitos\Models\ContratoFiscalHasContrato', 'id_contrato_fiscal', ['alias' => 'ContratoFiscalHasContratoUnico']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'contrato_fiscal';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFiscal[]|ContratoFiscal|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ContratoFiscal|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de ContratoFiscal, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return ContratoFiscal|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarContratoFiscal($parameters = null)
    {
        $query = new Builder();
        $query->from(array("ContratoFiscal" => "Circuitos\Models\ContratoFiscal"));
        $query->columns("ContratoFiscal.*");
        $query->leftJoin("Circuitos\Models\ContratoFiscalHasContrato", "ContratoFiscalHasContrato.id_contrato_fiscal = ContratoFiscal.id", "ContratoFiscalHasContrato");
        $query->leftJoin("Circuitos\Models\Contrato", "Contrato.id = ContratoFiscalHasContrato.id_contrato", "Contrato");
        $query->leftJoin("Circuitos\Models\Usuario", "Usuario.id = ContratoFiscal.id_usuario", "Usuario");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa.id = Usuario.id_pessoa", "Pessoa");
        $query->where("ContratoFiscal.excluido = 0 AND (CONVERT(ContratoFiscal.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa.nome USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("ContratoFiscal.id");
        $query->orderBy("ContratoFiscal.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
