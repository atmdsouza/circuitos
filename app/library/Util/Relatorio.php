<?php

namespace Util;

use Circuitos\Models\Circuitos;
use Circuitos\Models\Movimentos;
use Circuitos\Models\Empresa;

use Util\Util;
use Spipu\Html2Pdf\Html2Pdf;

require APP_PATH . '/library/HTML2PDF/autoload.php';

class Relatorio
{
    /**
     * Função para gerar o relatório com os dados do circuito
     * @param integer $id_circuito
     * @return pdf Retorna o PDF para o usuário
     */
    public function gerarPDFCircuito($id_circuito) {
        $util = new Util();
        //Coleta de Dados
        $empresa = Empresa::findFirst();
        // var_dump($empresa->Pessoa->PessoaEndereco[0]->numero);
        // exit;
        $circuito = Circuitos::findFirst("id={$id_circuito}");
        $movimentos = Movimentos::find("id_circuitos={$id_circuito}");
        $hoje = date('d/m/Y H:i:s');
        //Corpo do relatório
        $corpo_html = "<style type='text/css'>";
        $corpo_html .= "p {";
        $corpo_html .= "font-size: 10px";
        $corpo_html .= "text-align: justify;";
        $corpo_html .= "padding: 0px;";
        $corpo_html .= "margin-top: 10px;";
        $corpo_html .= "margin-bottom: 10px;";
        $corpo_html .= "margin-left: 0px;";
        $corpo_html .= "margin-right: 0px;";
        $corpo_html .= "}";
        $corpo_html .= "ul,li {";
        $corpo_html .= "font-size: 10px;";
        $corpo_html .= "text-align: justify;";
        $corpo_html .= "}";
        $corpo_html .= "table {";
        $corpo_html .= "font-family: Helvetica, sans-serif;";
        $corpo_html .= "font-size: 9px;";
        $corpo_html .= "margin-left: 0px;";
        $corpo_html .= "}";
        $corpo_html .= "#logo {";
        $corpo_html .= "float: right;";
        $corpo_html .= "}";
        $corpo_html .= ".cabecalho {";
        $corpo_html .= "float: right;";
        $corpo_html .= "margin-top: 5px;";
        $corpo_html .= "font-family: Helvetica, sans-serif;";
        $corpo_html .= "font-size: 10px;";
        $corpo_html .= "}";
        $corpo_html .= "span {";
        $corpo_html .= "font-size: 12px;";
        $corpo_html .= "}";
        $corpo_html .= ".titulo {";
        $corpo_html .= "text-align: left;";
        $corpo_html .= "font-family: Helvetica, sans-serif;";
        $corpo_html .= "font-size: 12px;";
        $corpo_html .= "font-weight: bold;";
        $corpo_html .= "padding: 0px;";
        $corpo_html .= "margin-top: 10px;";
        $corpo_html .= "margin-bottom: 10px;";
        $corpo_html .= "margin-left: 0px;";
        $corpo_html .= "margin-right: 0px;";
        $corpo_html .= "}";
        $corpo_html .= ".corpo {";
        $corpo_html .= "font-family: Helvetica, sans-serif;";
        $corpo_html .= "}";
        $corpo_html .= "table.tabela td { ";
        $corpo_html .= "font-size: 10px; ";
        $corpo_html .= "}";
        $corpo_html .= "table { ";
        $corpo_html .= "width: 100%;";
        $corpo_html .= "top: 50%;";
        $corpo_html .= "left: 50%;";
        $corpo_html .= "padding: 0px;";
        $corpo_html .= "margin-top: 5px;";
        $corpo_html .= "margin-bottom: 5px;";
        $corpo_html .= "margin-left: 0px;";
        $corpo_html .= "margin-right: 0px;";
        $corpo_html .= "}";
        $corpo_html .= "</style>";
        $corpo_html .= "<page backtop='20mm' backbottom='10mm' backleft='0mm' backright='0mm'class='corpo'> ";
        $corpo_html .= "<!--Cabeçalho-->";
        $corpo_html .= "<page_header> ";
        $corpo_html .= "<img id='logo' src='http://www.2imagem.com.br/circuitos/public/images/logo_dark.png' height='45' alt='Início'/>";
        $corpo_html .= "<div class='cabecalho'>";
        $corpo_html .= "<strong>{$empresa->Pessoa->PessoaJuridica->razaosocial}</strong><br/>";
        if ($empresa->Pessoa->PessoaEndereco[0]->numero)
        {
            $corpo_html .= "{$empresa->Pessoa->PessoaEndereco[0]->endereco} {$empresa->Pessoa->PessoaEndereco[0]->numero}<br/>";
        } 
        else 
        {
            $corpo_html .= "{$empresa->Pessoa->PessoaEndereco[0]->endereco}<br/>";
        }
        $corpo_html .= "{$empresa->Pessoa->PessoaEndereco[0]->cidade} - {$empresa->Pessoa->PessoaEndereco[0]->estado} - CEP {$empresa->Pessoa->PessoaEndereco[0]->cep}<br/>";
        $corpo_html .= "<a href='www.prodepa.pa.gov.br'>www.prodepa.pa.gov.br</a> / <a href='mailto:prodepa@prodepa.pa.gov.br'>prodepa@prodepa.pa.gov.br</a>";
        $corpo_html .= "</div>";
        $corpo_html .= "<!--Divisor-->";
        $corpo_html .= "<hr size='1' width='100%'>";
        $corpo_html .= "</page_header>";
        $corpo_html .= "<!--Rodapé-->";
        $corpo_html .= "<page_footer>";
        $corpo_html .= "<!--Divisor-->";
        $corpo_html .= "<hr size='1' width='100%'>";
        $corpo_html .= "<span style='font-size: 10px;'>Detalhamento dos dados do Circuito. Emitido em {$hoje}. Sistema Circuitos® <i>Todos os Direitos Reservados</i>. Página [[page_cu]]/[[page_nb]].</span>";
        $corpo_html .= "</page_footer> ";
        $corpo_html .= "<!--Corpo do relatório-->";
        $corpo_html .= "<div class='titulo' id='titulo'>";
        $corpo_html .= "Detalhamento dos dados do Circuito<br/>";
        $corpo_html .= "Emitido em: {$hoje}";
        $corpo_html .= "</div>";
        //Conteúdo - Dados do Circuito
        $corpo_html .= "<table class='tabela'>";
        $corpo_html .= "<tbody>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td colspan='4' style='text-align: left; width: 100%;'>";
        $corpo_html .= "<h5>Dados do Circuito</h5>";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Designação</strong> <br/>{$circuito->designacao}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Status do Circuito</strong> <br/>{$circuito->Lov2->descricao}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $data_ativacao = $circuito->data_ativacao ? $util->converterDataHoraParaBr($circuito->data_ativacao) : '';
        $corpo_html .= "<strong>Data da Ativação</strong> <br/>{$data_ativacao}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $data_atualizacao = $circuito->data_atualizacao ? $util->converterDataHoraParaBr($circuito->data_atualizacao) : '';
        $corpo_html .= "<strong>Data da Última Atualização</strong> <br/>{$data_atualizacao}";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td colspan='2' style='text-align: left; width: 50%;'>";
        $corpo_html .= "<strong>Cliente</strong> <br/>{$circuito->Cliente->Pessoa->nome}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td colspan='2' style='text-align: left; width: 50%;'>";
        $corpo_html .= "<strong>Unidade do Cliente</strong> <br/>{$circuito->ClienteUnidade->Pessoa->nome}";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Contrato</strong> <br/>{$circuito->Lov1->descricao}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>VLAN</strong> <br/>{$circuito->vlan}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Cluster</strong> <br/>{$circuito->Lov3->descricao}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Tipo de Unidade</strong> <br/>{$circuito->Lov4->descricao}";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Função</strong> <br/>{$circuito->Lov5->descricao}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Enlace</strong> <br/>{$circuito->Lov6->descricao}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Fabricante</strong> <br/>{$circuito->Equipamento->Fabricante->Pessoa->nome}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Modelo</strong> <br/>{$circuito->Equipamento->Modelo->modelo}";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Equipamento</strong> <br/>{$circuito->Equipamento->nome}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Nº de Série</strong> <br/>{$circuito->Equipamento->numserie}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Nº de Patimônio</strong> <br/>{$circuito->Equipamento->numpatrimonio}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>TAG</strong> <br/>{$circuito->tag}";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>CCODE</strong> <br/>{$circuito->ccode}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Banda</strong> <br/>{$circuito->Lov7->descricao}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>IP Rede Local</strong> <br/>{$circuito->ip_redelocal}";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>IP Rede Gerencial</strong> <br/>{$circuito->ip_gerencia}";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td colspan='4' style='text-align: left; width: 100%;'>";
        $corpo_html .= "<strong>Observação</strong> <br/>{$circuito->observacao}";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        $corpo_html .= "</tbody>";
        $corpo_html .= "</table>";
        //Conteúdo - Histórico do Circuito
        $corpo_html .= "<table class='tabela'>";
        $corpo_html .= "<tbody>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td colspan='7' style='text-align: left; width: 100%;'>";
        $corpo_html .= "<h5>Histórico de Movimentos do Circuito</h5>";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        $corpo_html .= "<tr>";
        $corpo_html .= "<td style='text-align: left; width: 5%;'>";
        $corpo_html .= "<strong>OS</strong>";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 15%;'>";
        $corpo_html .= "<strong>Data Movimento</strong>";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 15%;'>";
        $corpo_html .= "<strong>Tipo Movimento</strong>";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 10%;'>";
        $corpo_html .= "<strong>Usuário</strong>";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 15%;'>";
        $corpo_html .= "<strong>Valor Anterior</strong>";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 15%;'>";
        $corpo_html .= "<strong>Valor Atual</strong>";
        $corpo_html .= "</td>";
        $corpo_html .= "<td style='text-align: left; width: 25%;'>";
        $corpo_html .= "<strong>Observação</strong>";
        $corpo_html .= "</td>";
        $corpo_html .= "</tr>";
        foreach($movimentos as $mov)
        {
            $corpo_html .= "<tr>";
            $corpo_html .= "<td style='text-align: left; width: 7%;'>";
            $corpo_html .= $mov->osocomon;
            $corpo_html .= "</td>";
            $corpo_html .= "<td style='text-align: left; width: 15%;'>";
            $data_mov = $mov->data_movimento ? $util->converterDataHoraParaBr($mov->data_movimento) : '';
            $corpo_html .= $data_mov;
            $corpo_html .= "</td>";
            $corpo_html .= "<td style='text-align: left; width: 15%;'>";
            $corpo_html .= $mov->Lov->descricao;
            $corpo_html .= "</td>";
            $corpo_html .= "<td style='text-align: left; width: 17%;'>";
            $corpo_html .= $mov->Usuario->Pessoa->nome;
            $corpo_html .= "</td>";
            $corpo_html .= "<td style='text-align: left; width: 10%;'>";
            $valoranterior = $mov->valoranterior ? $mov->valoranterior : '';
            $corpo_html .= $valoranterior;
            $corpo_html .= "</td>";
            $corpo_html .= "<td style='text-align: left; width: 10%;'>";
            $valoratualizado = $mov->valoratualizado ? $mov->valoratualizado : '';
            $corpo_html .= $valoratualizado;
            $corpo_html .= "</td>";
            $corpo_html .= "<td style='text-align: left; width: 26%;'>";
            $observacao = $mov->observacao ? $mov->observacao : '';
            $corpo_html .= $observacao;
            $corpo_html .= "</td>";
            $corpo_html .= "</tr>";
        }
        $corpo_html .= "</tbody>";
        $corpo_html .= "</table>";
        //Fim do Conteúdo
        $corpo_html .= "</page>";
        try {
            //Gerando PDF
            $html2pdf = new Html2Pdf('P', 'A4', 'pt', true, 'UTF-8', array(15, 10, 15, 10));
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($corpo_html);
            $nome_arquivo = "Circuito_" . date("HisYmd") . ".pdf";
            $nome_caminho = "/circuitos/public/relatorios/" . $nome_arquivo;
            $html2pdf->Output($nome_caminho, 'F');
            return "/circuitos/public/relatorios/" . $nome_arquivo;
        } catch (HTML2PDF_exception $e) {
            echo $e;
            var_dump($e);
            exit;
        }
    }
}