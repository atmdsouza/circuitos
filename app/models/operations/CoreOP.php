<?php

namespace Circuitos\Models\Operations;

use Phalcon\Http\Response as Response;

use Circuitos\Models\CidadeDigital;
use Circuitos\Models\Cliente;
use Circuitos\Models\EndEndereco;
use Circuitos\Models\EndCidade;
use Circuitos\Models\Equipamento;
use Circuitos\Models\EstacaoTelecon;
use Circuitos\Models\Fabricante;
use Circuitos\Models\Lov;
use Circuitos\Models\Modelo;
use Circuitos\Models\Terreno;
use Circuitos\Models\Torre;
use Circuitos\Models\SetEquipamento;
use Circuitos\Models\SetSeguranca;

/**
 * Class CoreOP
 * @package Circuitos\Models\Operations
 * Created by PhpStorm.
 * User: andre
 * Date: 17/08/2019
 * Time: 08:10
 * Responsável por realizar todas as operações de consulta reutilizáveis do sistema, sendo, em sua maioria, consultas para retorno em operações ajax.
 */
class CoreOP
{
    public function completarEndereco()
    {
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        $endereco = EndEndereco::findFirst("cep='{$dados["cep"]}'");
        $end = [
            "cep" => $endereco->getCep(),
            "logradouro" => $endereco->getTipoLogradouro() . " " . $endereco->getLogradouro(),
            "bairro" => $endereco->getNomeBairro(),
            "cidade" => $endereco->getNomeCidade(),
            "uf" => $endereco->getNomeEstado(),
            "sigla_estado" => $endereco->getSiglaEstado(),
            "latitude" => $endereco->getLatitude(),
            "longitude" => $endereco->getLongitude()
        ];
        if ($endereco) {
            $response->setContent(json_encode(array("endereco" => $end,"operacao" => True)));
        } else {
            $response->setContent(json_encode(array("operacao" => False)));
        }
        return $response;
    }

    public function completarCidades()
    {
        $dados = filter_input_array(INPUT_GET);
        $array_dados = EndCidade::find('uf = "'.$dados["id_estado"] . '"');
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $array_dados)));
        return $response;
    }

    public function fornecedoresAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $fornecedores = Cliente::pesquisarFornecedoresAtivos($dados['string']);
        $array_dados = array();
        foreach ($fornecedores as $fornecedor){
            array_push($array_dados, ['id' => $fornecedor->getId(), 'nome' => $fornecedor->getClienteNome()]);
        }
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $array_dados)));
        return $response;
    }

    public function cidadesDigitaisAtivas()
    {
        $dados = filter_input_array(INPUT_GET);
        $cidadedigital = CidadeDigital::find("excluido=0 AND ativo=1 AND descricao LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $cidadedigital)));
        return $response;
    }

    public function estacoesTeleconAtivas()
    {
        $estacoestelecon = EstacaoTelecon::find("excluido=0 AND ativo=1");
        $dados_estacoes = array();
        foreach ($estacoestelecon as $estacaotelecon){
            $dados_estacao = array(
                'id' => $estacaotelecon->getId(),
                'descricao' => $estacaotelecon->getDescricao(),
                'cidade_digital' => $estacaotelecon->getCidadeDigital(),
                'cep' => $estacaotelecon->getCep(),
                'endereco' => $estacaotelecon->getEndereco(),
                'numero' => $estacaotelecon->getNumero(),
                'bairro' => $estacaotelecon->getBairro(),
                'cidade' => $estacaotelecon->getCidade(),
                'estado' => $estacaotelecon->getEstado(),
                'sigla_estado' => $estacaotelecon->getSiglaEstado(),
                'latitude' => $estacaotelecon->getLatitude(),
                'longitude' => $estacaotelecon->getLongitude()
            );
            array_push($dados_estacoes,$dados_estacao);
        }
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $dados_estacoes)));
        return $response;
    }

    public function terrenosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $terreno = Terreno::find("excluido=0 AND ativo=1 AND descricao LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $terreno)));
        return $response;
    }

    public function torresAtivas()
    {
        $dados = filter_input_array(INPUT_GET);
        $torre = Torre::find("excluido=0 AND ativo=1 AND descricao LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $torre)));
        return $response;
    }

    public function setsSegurancaAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $setseguranca = SetSeguranca::find("excluido=0 AND ativo=1 AND descricao LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $setseguranca)));
        return $response;
    }

    public function setsEquipamentosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $setequipamento = SetEquipamento::find("excluido=0 AND ativo=1 AND descricao LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $setequipamento)));
        return $response;
    }

    public function tiposCidadesDigitaisAtivas()
    {
        $tipos = Lov::find("tipo=18 AND excluido=0 AND ativo=1");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $tipos)));
        return $response;
    }

    public function equipamentoSeriePatrimonioAtivos()
    {
        $equipamentos = Equipamento::find("excluido=0 AND ativo=1");
        $dados_serie_patrimonio = array();
        foreach($equipamentos as $equipamento)
        {
            array_push($dados_serie_patrimonio, $equipamento->getNumserie());
            array_push($dados_serie_patrimonio, $equipamento->getNumpatrimonio());
        }
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $dados_serie_patrimonio)));
        return $response;
    }

    public function equipamentoNumeroSerie()
    {
        $response = new Response();
        $dados = filter_input_array(INPUT_GET);
        if ($dados["numero_serie"]) {
            $equipamentos = Equipamento::findFirst("numserie='{$dados['numero_serie']}' OR numpatrimonio='{$dados['numero_serie']}'");
            if ($equipamentos) {
                $response->setContent(json_encode(array(
                    "operacao" => True,
                    "id_equipamento" => $equipamentos->getId(),
                    "nome_equipamento" => $equipamentos->getNome(),
                    "numero_patrimonio" => $equipamentos->getNumpatrimonio(),
                    "id_fabricante" => $equipamentos->getIdFabricante(),
                    "nome_fabricante" => $equipamentos->getNomeFabricante(),
                    "id_modelo" => $equipamentos->getIdModelo(),
                    "nome_modelo" => $equipamentos->getNomeModelo()
                )));
            } else {
                $response->setContent(json_encode(array("operacao" => False)));
            }
        } else {
            $response->setContent(json_encode(array("operacao" => False)));
        }
        return $response;
    }

    public function fabricantesAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $fabricantes = Fabricante::pesquisarFabricantesAtivos($dados['string']);
        $array_dados = array();
        foreach ($fabricantes as $fabricante){
            array_push($array_dados, ['id' => $fabricante->getId(), 'nome' => $fabricante->getNomeFabricante()]);
        }
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $array_dados)));
        return $response;
    }

    public function modelosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $array_dados = Modelo::find("excluido=0 AND ativo=1 AND id_fabricante = {$dados['id']} AND modelo LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $array_dados)));
        return $response;
    }

    public function equipamentosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $array_dados = Equipamento::find("excluido=0 AND ativo=1 AND id_modelo = {$dados['id']} AND nome LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $array_dados)));
        return $response;
    }
}