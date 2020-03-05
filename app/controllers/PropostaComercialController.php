<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Circuitos\Models\Anexos;
use Circuitos\Models\EmpresaDepartamento;
use Circuitos\Models\Lov;
use Circuitos\Models\Operations\AnexosOP;
use Circuitos\Models\Operations\CoreOP;
use Circuitos\Models\Operations\PropostaComercialOP;
use Circuitos\Models\PropostaComercial;
use Circuitos\Models\PropostaComercialAnexo;
use Circuitos\Models\PropostaComercialItem;
use Circuitos\Models\PropostaComercialServicoGrupo;
use Circuitos\Models\PropostaComercialValorMensal;
use Phalcon\Http\Response as Response;
use Util\TokenManager;

class PropostaComercialController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle("Gestão Comercial");
        parent::initialize();
        //Voltando o usuário não autenticado para a página de login
        $auth = new Autentica();
        $identity = $auth->getIdentity();
        if (!is_array($identity)) {
            return $this->response->redirect('session/login');
        }
        $this->view->user = $identity["nome"];
        //CSRFToken
        $this->tokenManager = new TokenManager();
        if (!$this->tokenManager->doesUserHaveToken('User')) {
            $this->tokenManager->generateToken('User');
        }
        $this->view->token = $this->tokenManager->getToken('User');
    }

    public function indexAction()
    {
        $dados = filter_input_array(INPUT_POST);
        $propostacomercialOP = new PropostaComercialOP();
        $propostacomercial = $propostacomercialOP->listar($dados['pesquisa']);
        $tipos = Lov::find(array(
            "tipo = 23 AND excluido = 0 AND ativo = 1",
            "order" => "descricao"
        ));
        $status = Lov::find(array(
            "tipo = 25 AND excluido = 0 AND ativo = 1",
            "order" => "descricao"
        ));
        $tipos_anexos = Lov::find(array(
            "tipo = 20 AND excluido = 0 AND ativo = 1",
            "order" => "descricao"
        ));
        $grupos = PropostaComercialServicoGrupo::find(array(
            "id_grupo_pai IS NULL AND excluido=0 AND ativo=1",
            "order" => "descricao"
        ));
        $departamentos = EmpresaDepartamento::find(array(
            "excluido=0 AND ativo=1",
            "order" => "descricao"
        ));
        $this->view->tipos = $tipos;
        $this->view->status = $status;
        $this->view->grupos = $grupos;
        $this->view->tipos_anexos = $tipos_anexos;
        $this->view->departamentos = $departamentos;
        $this->view->page = $propostacomercial;
    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Proposta Comercial';
        $msg = 'Proposta Comercial cadastrada com sucesso!';
        $error_msg = 'Erro ao cadastrar uma Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercial = new PropostaComercial($params);
            $propostacomercialvalormensal = new PropostaComercialValorMensal();
            $propostacomercialvalormensal->setJan($params['jan']);
            $propostacomercialvalormensal->setFev($params['fev']);
            $propostacomercialvalormensal->setMar($params['mar']);
            $propostacomercialvalormensal->setAbr($params['abr']);
            $propostacomercialvalormensal->setMai($params['mai']);
            $propostacomercialvalormensal->setJun($params['jun']);
            $propostacomercialvalormensal->setJul($params['jul']);
            $propostacomercialvalormensal->setAgo($params['ago']);
            $propostacomercialvalormensal->setSet($params['set']);
            $propostacomercialvalormensal->setOut($params['out']);
            $propostacomercialvalormensal->setNov($params['nov']);
            $propostacomercialvalormensal->setDez($params['dez']);
            $arrPropostacomercialitens = [];
            if (isset($params['id_proposta_comercial_servicos_item']) && count($params['id_proposta_comercial_servicos_item']) > 0){
                foreach ($params['id_proposta_comercial_servicos_item'] as $key => $item)
                {
                    $propostacomercialitens = new PropostaComercialItem();
                    $propostacomercialitens->setIdPropostaComercialServicos($item);
                    $propostacomercialitens->setImposto($params['imposto_item'][$key]);
                    $propostacomercialitens->setReajuste($params['reajuste_item'][$key]);
                    $propostacomercialitens->setQuantidade($params['quantidade_item'][$key]);
                    $propostacomercialitens->setMesInicial($params['mes_inicial_item'][$key]);
                    $propostacomercialitens->setVigencia($params['vigencia_item'][$key]);
                    $propostacomercialitens->setValorUnitario($params['valor_unitario_item'][$key]);
                    $propostacomercialitens->setValorTotal($params['valor_total_item'][$key]);
                    $propostacomercialitens->setValorTotalReajuste($params['valor_total_reajuste_item'][$key]);
                    $propostacomercialitens->setValorImpostos($params['valor_impostos_item'][$key]);
                    $propostacomercialitens->setValorTotalImpostos($params['valor_total_impostos_item'][$key]);
                    array_push($arrPropostacomercialitens, $propostacomercialitens);
                }
            }
            $propostacomercialOP = new PropostaComercialOP();
            if($propostacomercialOP->cadastrar($propostacomercial, $propostacomercialvalormensal, $arrPropostacomercialitens)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function editarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Alteração de Proposta Comercial';
        $msg = 'Proposta Comercial alterada com sucesso!';
        $error_msg = 'Erro ao alterar uma Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercial = new PropostaComercial($params);
            $propostacomercialvalormensal = new PropostaComercialValorMensal();
            $propostacomercialvalormensal->setJan($params['jan']);
            $propostacomercialvalormensal->setFev($params['fev']);
            $propostacomercialvalormensal->setMar($params['mar']);
            $propostacomercialvalormensal->setAbr($params['abr']);
            $propostacomercialvalormensal->setMai($params['mai']);
            $propostacomercialvalormensal->setJun($params['jun']);
            $propostacomercialvalormensal->setJul($params['jul']);
            $propostacomercialvalormensal->setAgo($params['ago']);
            $propostacomercialvalormensal->setSet($params['set']);
            $propostacomercialvalormensal->setOut($params['out']);
            $propostacomercialvalormensal->setNov($params['nov']);
            $propostacomercialvalormensal->setDez($params['dez']);
            $arrPropostacomercialitens = [];
            if (isset($params['id_proposta_comercial_servicos_item']) && count($params['id_proposta_comercial_servicos_item']) > 0){
                foreach ($params['id_proposta_comercial_servicos_item'] as $key => $item)
                {
                    $propostacomercialitens = new PropostaComercialItem();
                    $propostacomercialitens->setIdPropostaComercialServicos($item);
                    $propostacomercialitens->setImposto($params['imposto_item'][$key]);
                    $propostacomercialitens->setReajuste($params['reajuste_item'][$key]);
                    $propostacomercialitens->setQuantidade($params['quantidade_item'][$key]);
                    $propostacomercialitens->setMesInicial($params['mes_inicial_item'][$key]);
                    $propostacomercialitens->setVigencia($params['vigencia_item'][$key]);
                    $propostacomercialitens->setValorUnitario($params['valor_unitario_item'][$key]);
                    $propostacomercialitens->setValorTotal($params['valor_total_item'][$key]);
                    $propostacomercialitens->setValorTotalReajuste($params['valor_total_reajuste_item'][$key]);
                    $propostacomercialitens->setValorImpostos($params['valor_impostos_item'][$key]);
                    $propostacomercialitens->setValorTotalImpostos($params['valor_total_impostos_item'][$key]);
                    array_push($arrPropostacomercialitens, $propostacomercialitens);
                }
            }
            $propostacomercialOP = new PropostaComercialOP();
            if($propostacomercialOP->alterar($propostacomercial, $propostacomercialvalormensal, $arrPropostacomercialitens)){//Altera com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function ativarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $titulo = 'Reativação de Proposta Comercial';
        $msg = 'Proposta Comercial reativada com sucesso!';
        $error_msg = 'Erro ao reativar uma Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercialOP = new PropostaComercialOP();
            $propostacomercial = new PropostaComercial($dados['dados']);
            if($propostacomercialOP->ativar($propostacomercial)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function inativarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $titulo = 'Desativação de Proposta Comercial';
        $msg = 'Proposta Comercial desativada com sucesso!';
        $error_msg = 'Erro ao desativar uma Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercialOP = new PropostaComercialOP();
            $propostacomercial = new PropostaComercial($dados['dados']);
            if($propostacomercialOP->inativar($propostacomercial)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function excluirAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $titulo = 'Exclusão de Proposta Comercial';
        $msg = 'Proposta Comercial excluída com sucesso!';
        $error_msg = 'Erro ao excluir a Proposta Comercial!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $propostacomercialOP = new PropostaComercialOP();
            $propostacomercial = new PropostaComercial($dados['dados']);
            if($propostacomercialOP->excluir($propostacomercial)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    public function subirAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $anexosOP = new AnexosOP();
        $modulo = $this->router->getControllerName();
        $action = $this->router->getActionName();
        $request = $this->request;
        $id_proposta = $request->get('id_proposta');
        $id_tipo_anexo = $request->get('id_tipo_anexo');
        $descricao = $request->get('descricao');
        $coreOP = new CoreOP();
        $files = $coreOP->servicoUpload($request, $modulo, $action, $id_proposta, null);
        foreach ($files as $key => $file)
        {
            $anexos = new Anexos();
            $anexos->setDescricao($descricao[$key]);
            $anexos->setIdTipoAnexo($id_tipo_anexo[$key]);
            $anexos->setUrl($file['path']);
            $anexo_cadastrado = $anexosOP->cadastrar($anexos);
            $propostaanexos = new PropostaComercialAnexo();
            $propostaanexos->setIdAnexo($anexo_cadastrado->getId());
            $propostaanexos->setIdPropostaComercial($id_proposta);
            $anexosOP->cadastrarPropostaComercialAnexo($propostaanexos);
        }
        $this->response->redirect('proposta_comercial');
    }
}
