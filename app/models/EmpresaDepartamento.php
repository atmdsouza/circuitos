<?php

namespace Circuitos\Models;

use Phalcon\Mvc\Model\Query\Builder;
use Util\Infra;

class EmpresaDepartamento extends \Phalcon\Mvc\Model
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
    protected $id_departamento_pai;

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
     *
     * @var integer
     */
    protected $id_empresa;

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
     * Method to set the value of field id_departamento_pai
     *
     * @param integer $id_departamento_pai
     * @return $this
     */
    public function setIdDepartamentoPai($id_departamento_pai)
    {
        $this->id_departamento_pai = $id_departamento_pai;

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
     * Method to set the value of field id_empresa
     *
     * @param integer $id_empresa
     * @return $this
     */
    public function setIdEmpresa($id_empresa)
    {
        $this->id_empresa = $id_empresa;

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
     * Returns the value of field id_departamento_pai
     *
     * @return integer
     */
    public function getIdDepartamentoPai()
    {
        return $this->id_departamento_pai;
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
     * Returns the value of field id_empresa
     *
     * @return integer
     */
    public function getIdEmpresa()
    {
        return $this->id_empresa;
    }

    /**
     * Returns the value of field nome empresa
     *
     * @return string
     */
    public function getNomeEmpresa()
    {
        return (isset($this->Empresa->Pessoa->nome)) ? $this->Empresa->Pessoa->nome : null;
    }

    /**
     * Returns the value of field departamento pai
     *
     * @return string
     */
    public function getNomeDepartamentoPai()
    {
        return (isset($this->EmpresaDepartamentoPai->descricao)) ? $this->EmpresaDepartamentoPai->descricao : null;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $schema = new Infra();
        $this->setSchema($schema->getSchemaBanco());
        $this->setSource("empresa_departamento");
        $this->hasMany('id', 'Circuitos\Models\Circuitos', 'id_empresa_departamento', ['alias' => 'Circuitos']);
        $this->hasMany('id', 'Circuitos\Models\EmpresaDepartamento', 'id_departamento_pai', ['alias' => 'EmpresaDepartamento']);
        $this->hasMany('id', 'Circuitos\Models\PropostaComercial', 'id_localizacao', ['alias' => 'PropostaComercial']);
        $this->belongsTo('id_departamento_pai', 'Circuitos\Models\EmpresaDepartamento', 'id', ['alias' => 'EmpresaDepartamentoPai']);
        $this->belongsTo('id_empresa', 'Circuitos\Models\Empresa', 'id', ['alias' => 'Empresa']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'empresa_departamento';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmpresaDepartamento[]|EmpresaDepartamento|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmpresaDepartamento|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Consulta completa de EmpresaDepartamento, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return EmpresaDepartamento|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarEmpresaDepartamento($parameters = null)
    {
        $query = new Builder();
        $query->from(array("EmpresaDepartamento" => "Circuitos\Models\EmpresaDepartamento"));
        $query->columns("EmpresaDepartamento.*");
        $query->leftJoin("Circuitos\Models\Empresa", "Empresa.id = EmpresaDepartamento.id_empresa", "Empresa");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa.id = Empresa.id", "Pessoa");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "PessoaJuridica.id = Empresa.id", "PessoaJuridica");
        $query->where("EmpresaDepartamento.excluido = 0 AND (CONVERT(EmpresaDepartamento.id USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(EmpresaDepartamento.descricao USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(Pessoa.nome USING utf8) LIKE '%{$parameters}%'
                        OR CONVERT(PessoaJuridica.razaosocial USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("EmpresaDepartamento.id");
        $query->orderBy("EmpresaDepartamento.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

    /**
     * Consulta completa de departamentos, incluíndo os joins de tabelas
     *
     * @param string $parameters
     * @return EmpresaDepartamento|\Phalcon\Mvc\Model\Resultset
     */
    public static function pesquisarDepartamentosAtivos($parameters = null)
    {
        $query = new Builder();
        $query->from(array("EmpresaDepartamento" => "Circuitos\Models\EmpresaDepartamento"));
        $query->columns("EmpresaDepartamento.*");
        $query->leftJoin("Circuitos\Models\Empresa", "Empresa.id = EmpresaDepartamento.id_empresa", "Empresa");
        $query->leftJoin("Circuitos\Models\Pessoa", "Pessoa.id = Empresa.id", "Pessoa");
        $query->leftJoin("Circuitos\Models\PessoaJuridica", "PessoaJuridica.id = Empresa.id", "PessoaJuridica");
        $query->where("EmpresaDepartamento.excluido=0 AND EmpresaDepartamento.ativo=1 AND (CONVERT(EmpresaDepartamento.descricao USING utf8) LIKE '%{$parameters}%')");
        $query->groupBy("EmpresaDepartamento.id");
        $query->orderBy("EmpresaDepartamento.id DESC");
        $resultado = $query->getQuery()->execute();
        return $resultado;
    }

}
