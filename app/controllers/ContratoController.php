<?php

namespace Circuitos\Controllers;

use Auth\Autentica;
use Circuitos\Models\Anexos;
use Circuitos\Models\Contrato;
use Circuitos\Models\ContratoAnexo;
use Circuitos\Models\ContratoArquivoFisico;
use Circuitos\Models\ContratoExercicio;
use Circuitos\Models\ContratoGarantia;
use Circuitos\Models\ContratoOrcamento;
use Circuitos\Models\Lov;
use Circuitos\Models\Operations\AnexosOP;
use Circuitos\Models\Operations\ContratoOP;
use Circuitos\Models\Operations\CoreOP;
use Phalcon\Http\Response;
use Util\TokenManager;

class ContratoController extends ControllerBase
{
    public $tokenManager;

    public function initialize()
    {
        $this->tag->setTitle("Gestão de Contratos");
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
        $contratoOP = new ContratoOP();
        $contrato = $contratoOP->listar($dados['pesquisa']);
        $tipos = Lov::find(array(
            'tipo = 26 AND excluido = 0 AND ativo = 1',
            'order' => 'descricao'
        ));
        $tipos_processos = Lov::find(array(
            'tipo = 27 AND excluido = 0 AND ativo = 1',
            'order' => 'descricao'
        ));
        $tipos_movimentos = Lov::find(array(
            'tipo = 28 AND excluido = 0 AND ativo = 1 AND valor >= 4',
            'order' => 'CAST(valor AS SIGNED)'
        ));
        $tipos_modalidades = Lov::find(array(
            'tipo = 29 AND excluido = 0 AND ativo = 1',
            'order' => 'descricao'
        ));
        $status_contrato = Lov::find(array(
            'tipo = 30 AND excluido = 0 AND ativo = 1',
            'order' => 'descricao'
        ));
        $tipos_fiscais = Lov::find(array(
            'tipo = 31 AND excluido = 0 AND ativo = 1',
            'order' => 'descricao'
        ));
        $tipos_anexos = Lov::find(array(
            'tipo = 20 AND excluido = 0 AND ativo = 1',
            'order' => 'descricao'
        ));
        $this->view->tipos = $tipos;
        $this->view->tipos_processos = $tipos_processos;
        $this->view->tipos_movimentos = $tipos_movimentos;
        $this->view->tipos_modalidades = $tipos_modalidades;
        $this->view->status_contrato = $status_contrato;
        $this->view->tipos_fiscais = $tipos_fiscais;
        $this->view->tipos_anexos = $tipos_anexos;
        $this->view->page = $contrato;
    }

    public function criarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);
        $params = array();
        parse_str($dados['dados'], $params);
        $titulo = 'Cadastro de Contrato';
        $msg = 'Contrato cadastrada com sucesso!';
        $error_msg = 'Erro ao cadastrar uma Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratoOP = new ContratoOP();
            //Contrato
            $contrato = new Contrato($params);
            $contratoarquivo = new ContratoArquivoFisico($params);
            //Contrato Orçamento
            $arrayCtOrcamento = array();
            foreach ($params['funcional_programatica'] as $key => $funcional_programatica){
                $contrato_orcamento = new ContratoOrcamento();
                $contrato_orcamento->setFuncionalProgramatica($funcional_programatica);
                $contrato_orcamento->setFonteOrcamentaria($params['fonte_orcamentaria'][$key]);
                $contrato_orcamento->setProgramaTrabalho($params['programa_trabalho'][$key]);
                $contrato_orcamento->setElementoDespesa($params['elemento_despesa'][$key]);
                array_push($arrayCtOrcamento, $contrato_orcamento);
            }
            //Contrato Exercicio
            $arrayCtExercicio = array();
            foreach ($params['exercicio'] as $key => $exercicio){
                $contrato_exercicio = new ContratoExercicio();
                $contrato_exercicio->setExercicio($exercicio);
                $contrato_exercicio->setCompetenciaInicial($params['competencia_inicial'][$key]);
                $contrato_exercicio->setCompetenciaFinal($params['competencia_final'][$key]);
                $contrato_exercicio->setValorPrevisto($params['valor_previsto'][$key]);
                array_push($arrayCtExercicio, $contrato_exercicio);
            }
            //Contrato Garantia
            $arrayCtGarantia = array();
            foreach ($params['id_modalidade'] as $key => $id_modalidade){
                $contrato_garantia = new ContratoGarantia();
                $contrato_garantia->setIdModalidade($id_modalidade);
                $contrato_garantia->setGarantiaConcretizada($params['garantia_concretizada'][$key]);
                $contrato_garantia->setPercentual($params['percentual'][$key]);
                $contrato_garantia->setValor($params['valor_garantia'][$key]);
                array_push($arrayCtGarantia, $contrato_garantia);
            }
            if($contratoOP->cadastrar($contrato, $contratoarquivo, $arrayCtOrcamento, $arrayCtExercicio, $arrayCtGarantia)){//Cadastrou com sucesso
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
        $titulo = 'Alteração de Contrato';
        $msg = 'Contrato alterada com sucesso!';
        $error_msg = 'Erro ao alterar uma Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratoOP = new ContratoOP();
            //Contrato
            $contrato = new Contrato($params);
            $contratoarquivo = new ContratoArquivoFisico($params);
            //Contrato Orçamento
            $arrayCtOrcamento = array();
            if (isset($params['funcional_programatica']) && count($params['funcional_programatica']) > 0){
                foreach ($params['funcional_programatica'] as $key => $funcional_programatica){
                    $contrato_orcamento = new ContratoOrcamento();
                    $contrato_orcamento->setFuncionalProgramatica($funcional_programatica);
                    $contrato_orcamento->setFonteOrcamentaria($params['fonte_orcamentaria'][$key]);
                    $contrato_orcamento->setProgramaTrabalho($params['programa_trabalho'][$key]);
                    $contrato_orcamento->setElementoDespesa($params['elemento_despesa'][$key]);
                    array_push($arrayCtOrcamento, $contrato_orcamento);
                }
            }
            //Contrato Exercicio
            $arrayCtExercicio = array();
            if (isset($params['exercicio']) && count($params['exercicio']) > 0) {
                foreach ($params['exercicio'] as $key => $exercicio) {
                    $contrato_exercicio = new ContratoExercicio();
                    $contrato_exercicio->setExercicio($exercicio);
                    $contrato_exercicio->setCompetenciaInicial($params['competencia_inicial'][$key]);
                    $contrato_exercicio->setCompetenciaFinal($params['competencia_final'][$key]);
                    $contrato_exercicio->setValorPrevisto($params['valor_previsto'][$key]);
                    array_push($arrayCtExercicio, $contrato_exercicio);
                }
            }
            //Contrato Garantia
            $arrayCtGarantia = array();
            if (isset($params['id_modalidade']) && count($params['id_modalidade']) > 0) {
                foreach ($params['id_modalidade'] as $key => $id_modalidade){
                    $contrato_garantia = new ContratoGarantia();
                    $contrato_garantia->setIdModalidade($id_modalidade);
                    $contrato_garantia->setGarantiaConcretizada($params['garantia_concretizada'][$key]);
                    $contrato_garantia->setPercentual($params['percentual'][$key]);
                    $contrato_garantia->setValor($params['valor_garantia'][$key]);
                    array_push($arrayCtGarantia, $contrato_garantia);
                }
            }
            if($contratoOP->alterar($contrato, $contratoarquivo, $arrayCtOrcamento, $arrayCtExercicio, $arrayCtGarantia)){//Altera com sucesso
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
        $titulo = 'Reativação de Contrato';
        $msg = 'Contrato reativada com sucesso!';
        $error_msg = 'Erro ao reativar uma Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratoOP = new ContratoOP();
            $contrato = new Contrato($dados['dados']);
            if($contratoOP->ativar($contrato)){//Cadastrou com sucesso
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
        $titulo = 'Desativação de Contrato';
        $msg = 'Contrato desativada com sucesso!';
        $error_msg = 'Erro ao desativar uma Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratoOP = new ContratoOP();
            $contrato = new Contrato($dados['dados']);
            if($contratoOP->inativar($contrato)){//Cadastrou com sucesso
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
        $titulo = 'Exclusão de Contrato';
        $msg = 'Contrato excluída com sucesso!';
        $error_msg = 'Erro ao excluir a Contrato!';
        $error_chk = 'Check de token de formulário inválido!';
        //CSRF Token Check
        if ($this->tokenManager->checkToken('User', $dados['tokenKey'], $dados['tokenValue'])) {//Formulário Válido
            $contratoOP = new ContratoOP();
            $contrato = new Contrato($dados['dados']);
            if($contratoOP->excluir($contrato)){//Cadastrou com sucesso
                $response->setContent(json_encode(array('operacao' => True, 'titulo' => $titulo, 'mensagem' => $msg)));
            } else {//Erro no cadastro
                $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo,'mensagem' => $error_msg)));
            }
        } else {//Formulário Inválido
            $response->setContent(json_encode(array('operacao' => False, 'titulo' => $titulo, 'mensagem' => $error_chk)));
        }
        return $response;
    }

    /**
     * Created by PhpStorm.
     * User: andre
     * Date: 06/11/2019
     * Time: 19:00
     * Responsável por movimentações controladas de um contrato
     */
    public function movimentarAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $response = new Response();
        $dados = filter_input_array(INPUT_POST);

        return $response;
    }

    /**
     * Created by PhpStorm.
     * User: andre
     * Date: 06/11/2019
     * Time: 19:00
     * Responsável por vincular arquivos a um contrato
     */
    public function subirAction()
    {
        //Desabilita o layout para o ajax
        $this->view->disable();
        $anexosOP = new AnexosOP();
        $modulo = $this->router->getControllerName();
        $action = $this->router->getActionName();
        $request = $this->request;
        $id_contrato = $request->get('id_contrato');
        $id_tipo_anexo = $request->get('id_tipo_anexo');
        $descricao = $request->get('descricao');
        $coreOP = new CoreOP();
        $files = $coreOP->servicoUpload($request, $modulo, $action, $id_contrato, null);
        foreach ($files as $key => $file)
        {
            $anexos = new Anexos();
            $anexos->setDescricao($descricao[$key]);
            $anexos->setIdTipoAnexo($id_tipo_anexo[$key]);
            $anexos->setUrl($file['path']);
            $anexo_cadastrado = $anexosOP->cadastrar($anexos);
            $contratoanexos = new ContratoAnexo();
            $contratoanexos->setIdAnexo($anexo_cadastrado->getId());
            $contratoanexos->setIdContrato($id_contrato);
            $anexosOP->cadastrarContratoAnexo($contratoanexos);
        }
        $this->response->redirect('contrato');
    }

}
