<?php

namespace Circuitos\Models\Operations;

use Circuitos\Models\CidadeDigital;
use Circuitos\Models\Cliente;
use Circuitos\Models\Contrato;
use Circuitos\Models\EmpresaDepartamento;
use Circuitos\Models\EndCidade;
use Circuitos\Models\EndEndereco;
use Circuitos\Models\Equipamento;
use Circuitos\Models\EstacaoTelecon;
use Circuitos\Models\Fabricante;
use Circuitos\Models\Lov;
use Circuitos\Models\Modelo;
use Circuitos\Models\PropostaComercial;
use Circuitos\Models\PropostaComercialServico;
use Circuitos\Models\PropostaComercialServicoGrupo;
use Circuitos\Models\PropostaComercialServicoUnidade;
use Circuitos\Models\SetEquipamento;
use Circuitos\Models\SetSeguranca;
use Circuitos\Models\Terreno;
use Circuitos\Models\Torre;
use Circuitos\Models\UnidadeConsumidora;
use Circuitos\Models\Usuario;
use Phalcon\Exception;
use Phalcon\Http\Response as Response;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Uploader\Uploader;

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
    private $arqLog = BASE_PATH . "/logs/systemlog.log";

    public function servicoUpload($request, $modulo, $action, $id, $rules)
    {
        $logger = new FileAdapter($this->arqLog);
        $uploader = new Uploader();
        $diretorio = BASE_PATH . "/public/anexos/" . $modulo . "/" . $action . "/" . $id . "/";
        if(!file_exists($diretorio) && !is_dir($diretorio)){
            mkdir($diretorio, 0777, true);
        }
        chmod($diretorio, 0777);
        if (empty($rules))
        {
            $rules = [
                "directory"   =>  $diretorio,
//                "minsize"   =>  1000,   // bytes
//                "maxsize"   =>  1000000,// bytes
//                "mimes"     =>  [//Permitidos
//                    "text/plain",
//                    "image/gif", "image/png", "image/jpeg", "image/bmp", "image/webp", "image/svg+xml", "image/x-icon",
//                    "audio/midi", "audio/mpeg", "audio/webm", "audio/ogg", "audio/wav",
//                    "video/webm", "video/ogg", "video/avi","video/mpeg","video/mp4",
//                    "application/x-rar-compressed", "application/octet-stream", "application/pkcs12", "application/vnd.mspowerpoint", "application/xhtml+xml",
//                    "application/xml",  "application/pdf"
//                ],
//                "extensions"     =>  [//Permitidos
//                    "gif", "jpeg", "jpg", "png", "pdf", "txt", "bpm", "webp", "svg", "icon", "mpeg", "webm", "ogg", "wav", "mp3", "midi",
//                    "avi", "mp4", "rar", "pptx", "docx", "xlsx", "ppt", "doc", "xls", "xml"
//                ],
                "sanitize" => true,
                "hash"     => "md5"
            ];
        }
        //Processo de Upload
        try {
            if($request->hasFiles() !== false) {
                $uploader->setRules($rules);
                if($uploader->isValid() === true) {
                    $uploader->move();
                }
            }
            if (count($uploader->getErrors()) > 0){
                foreach ($uploader->getErrors() as $erros)
                {
                    $logger->error('['.$erros.']');
                }
            }
            return $uploader->getInfo();
        } catch (Exception $e) {
            $logger->error('['.$e->getMessage().'] ['.$uploader->getErrors().']');
            return '['.$e->getMessage().'] ['.$uploader->getErrors().']';
        }
    }

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

    public function contasAgrupadorasAtivas()
    {
        $dados = filter_input_array(INPUT_GET);
        $array_dados = UnidadeConsumidora::find('ativo = 1 AND excluido = 0 AND codigo_conta_contrato LIKE "' . $dados['string'] . '%"');
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

    public function clientesAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $clientes = Cliente::pesquisarClientesAtivos($dados['string']);
        $array_dados = array();
        foreach ($clientes as $cliente){
            array_push($array_dados, ['id' => $cliente->getId(), 'nome' => $cliente->getClienteNome()]);
        }
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $array_dados)));
        return $response;
    }

    public function departamentosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $departamentos = EmpresaDepartamento::pesquisarDepartamentosAtivos($dados['string']);
        $array_dados = array();
        foreach ($departamentos as $departamento){
            array_push($array_dados, ['id' => $departamento->getId(), 'nome' => $departamento->getDescricao()]);
        }
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $array_dados)));
        return $response;
    }

    public function listaClientesFornecedoresParceirosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $clientes = Cliente::pesquisarClientesFornecedoresParceirosAtivos($dados['string']);
        $array_dados = array();
        foreach ($clientes as $cliente){
            array_push($array_dados, ['id' => $cliente->getId(), 'nome' => $cliente->getClienteNome()]);
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

    public function unidadeConsumidorasAtivas()
    {
        $dados = filter_input_array(INPUT_GET);
        $torre = UnidadeConsumidora::find("excluido=0 AND ativo=1 AND id_conta_agrupadora IS NULL AND codigo_conta_contrato LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $torre)));
        return $response;
    }

    public function servicosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        if (!empty($dados['id_subgrupo'])){
            $servicos = PropostaComercialServico::find("excluido=0 AND ativo=1 AND id_proposta_comercial_servico_grupo IN ({$dados['id_subgrupo']}) AND descricao LIKE '%{$dados['string']}%'");
        } else if (!empty($dados['id_grupo'])) {
            $objSubgrupos = PropostaComercialServicoGrupo::find('excluido=0 AND ativo=1 AND id_grupo_pai = '. $dados['id_grupo']);
            $prefix = $subGrupoList = '';
            foreach ($objSubgrupos as $objSubgrupo)
            {
                $subGrupoList .= $prefix . $objSubgrupo->getId() . ' ';
                $prefix = ', ';
            }
            $servicos = PropostaComercialServico::find("excluido=0 AND ativo=1 AND id_proposta_comercial_servico_grupo IN ({$subGrupoList}) AND descricao LIKE '%{$dados['string']}%'");
        } else {
            $servicos = PropostaComercialServico::find("excluido=0 AND ativo=1 AND descricao LIKE '%{$dados['string']}%'");
        }
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $servicos)));
        return $response;
    }

    public function codigoServicosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        if (!empty($dados['id_subgrupo'])){
            $servicos = PropostaComercialServico::find("excluido=0 AND ativo=1 AND id_proposta_comercial_servico_grupo IN ({$dados['vl_id_subgrupo']}) AND codigo_legado LIKE '%{$dados['string']}%'");
        } else if (!empty($dados['id_grupo'])) {
            $objSubgrupos = PropostaComercialServicoGrupo::find('excluido=0 AND ativo=1 AND id_grupo_pai = '. $dados['vl_id_grupo']);
            $prefix = $subGrupoList = '';
            foreach ($objSubgrupos as $objSubgrupo)
            {
                $subGrupoList .= $prefix . $objSubgrupo->getId() . ' ';
                $prefix = ', ';
            }
            $servicos = PropostaComercialServico::find("excluido=0 AND ativo=1 AND id_proposta_comercial_servico_grupo IN ({$subGrupoList}) AND codigo_legado LIKE '%{$dados['string']}%'");
        } else {
            $servicos = PropostaComercialServico::find("excluido=0 AND ativo=1 AND codigo_legado LIKE '%{$dados['string']}%'");
        }
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $servicos)));
        return $response;
    }

    public function gruposServicoAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $torre = PropostaComercialServicoGrupo::find("excluido=0 AND ativo=1 AND descricao LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $torre)));
        return $response;
    }

    public function servicoGruposAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $torre = PropostaComercialServicoGrupo::find("excluido=0 AND ativo=1 AND descricao LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $torre)));
        return $response;
    }

    public function servicoUnidadesAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $torre = PropostaComercialServicoUnidade::find("excluido=0 AND ativo=1 AND descricao LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $torre)));
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

    public function contratosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $objeto = Contrato::find("excluido=0 AND ativo=1 AND numero LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $objeto)));
        return $response;
    }

    public function contratosPrincipaisAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $objeto = Contrato::find("excluido=0 AND ativo=1 AND id_contrato_principal IS NULL AND numero LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $objeto)));
        return $response;
    }

    public function propostasComerciaisAtivas()
    {
        $dados = filter_input_array(INPUT_GET);
        $objeto = PropostaComercial::find("excluido=0 AND ativo=1 AND numero LIKE '%{$dados['string']}%'");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $objeto)));
        return $response;
    }

    public function listaUsuariosAtivos()
    {
        $dados = filter_input_array(INPUT_GET);
        $usuarios = Usuario::pesquisarUsuariosAtivos($dados['string']);
        $array_dados = array();
        foreach ($usuarios as $usuario){
            array_push($array_dados, ['id' => $usuario->getId(), 'nome' => $usuario->getNomePessoaUsuario()]);
        }
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True, "dados" => $array_dados)));
        return $response;
    }

    public function selectTiposAnexos()
    {
        $objeto = Lov::find("tipo=20 AND excluido=0 AND ativo=1");
        $response = new Response();
        $response->setContent(json_encode(array("operacao" => True,"dados" => $objeto)));
        return $response;
    }
}