//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Variáveis Globais
var mudou = false;
var contador = 0;
var tipos_anexos;

//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos)
function inicializar()
{
    'use strict';
    //Configuração Específica do Datatable
    $("#datatable_listar").DataTable({
        select: false,
        language: {
            select: false
        },
        order: [[7, "asc"],[0, "desc"]]//Ordenação passando a lista de ativos primeiro
    });
    autocompletarCliente('lid_cliente','id_cliente');
    autocompletarCodigoServico('codigo_servico','id_codigo_servico','grupo','subgrupo');
    autocompletarServico('descricao_servico','id_servico','grupo','subgrupo');
    getTiposAnexo();
}

function verificarAlteracao()
{
    'use strict';
    $('form').on('change paste', 'input, select, textarea', function(){
        mudou = true;
    });
}

function confirmaCancelar(modal)
{
    'use strict';
    verificarAlteracao();
    if (mudou)
    {
        swal({
            title: "Sair sem Salvar",
            text: "Deseja realmente sair sem salvar?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim",
            cancelButtonText: "Não"
        }).then(() => {
            $("#"+modal).modal('hide');
            limparModalBootstrap(modal);
            mudou = false;
            limparValidacao();
        }).catch(swal.noop);
    }
    else
    {
        $("#"+modal).modal('hide');
        limparModalBootstrap(modal);
        limparValidacao();
    }
}

function limparValidacao()
{
    'use strict';
    var validator = $("#formCadastro").validate();
    validator.resetForm();

}

function criar()
{
    'use strict';
    $('#primeira_aba').trigger('click');
    $('#tab-anexos').addClass('disabled');
    $('.hide_buttons').show();
    $('#bt_inserir_servico').text("Inserir");
    $('#bt_inserir_servico').removeAttr('onclick');
    $('#bt_inserir_servico').attr('onclick', 'inserirComponente();');
    limparDadosFormComponente();
    atualizaValorTotalMensal(0, 0);
    $('.tr_remove').remove();
    $('#tabela_componentes').removeAttr('style', 'display: table;');
    $('#tabela_componentes').attr('style', 'display: none;');
    $("#formCadastro input").removeAttr('readonly', 'readonly');
    $("#formCadastro select").removeAttr('readonly', 'readonly');
    $("#formCadastro textarea").removeAttr('readonly', 'readonly');
    $("#salvarCadastro").val('criar');
    $("#salvarCadastro").show();
    $("#modalCadastro").modal();
}

function salvar()
{
    'use strict';
    var acao = $('#salvarCadastro').val();
    $("#formCadastro").validate({
        rules : {
            id_cliente:{
                required: true
            },
            lid_cliente:{
                required: true
            },
            id_tipo_proposta:{
                required: true
            },
            id_localizacao:{
                required: true
            },
            id_status:{
                required: true
            },
            data_proposta:{
                required: true
            },
            numero:{
                required: true
            },
            vencimento:{
                required: true
            },
            valor_global:{
                required: true,
                maiorQueZero: true
            },
            objetivo:{
                required: true
            },
            objetivo_especifico:{
                required: true
            },
            descritivo:{
                required: true
            },
            responsabilidade:{
                required: true
            },
            condicoes_pgto:{
                required: true
            },
            prazo_execucao:{
                required: true
            },
            consideracoes:{
                required: true
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "proposta_comercial/" + acao);
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    dados: dados
                },
                error: function (data) {
                    if (data.status && data.status === 401)
                    {
                        swal({
                            title: "Erro de Permissão",
                            text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                            type: "warning"
                        });
                    }
                },
                success: function (data) {
                    if (data.operacao){
                        swal({
                            title: data.titulo,
                            text: data.mensagem,
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Ok"
                        }).then((result) => {
                            window.location.reload(true);
                        });
                    } else {
                        swal({
                            title: data.titulo,
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
        }
    });
}

function ativar(id, descr)
{
    'use strict';
    swal({
        title: "Tem certeza que deseja ativar o registro \""+ descr +"\"?",
        text: "O sistema irá ativar o registro \""+ descr +"\" com essa ação.",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, ativar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "proposta_comercial/ativar");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                dados: {id: id}
            },
            error: function (data) {
                if (data.status && data.status === 401)
                {
                    swal({
                        title: "Erro de Permissão",
                        text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                        type: "warning"
                    });
                }
            },
            success: function (data) {
                if (data.operacao){
                    swal({
                        title: "Ativado!",
                        text: "O registro \""+ descr +"\" foi ativado com sucesso.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        window.location.reload(true);
                    });
                } else {
                    swal({
                        title: "Ativar",
                        text: data.mensagem,
                        type: "error"
                    });
                }
            }
        });
    });
}

function inativar(id, descr)
{
    'use strict';
    swal({
        title: "Tem certeza que deseja inativar o registro \""+ descr +"\"?",
        text: "O sistema irá inativar o registro \""+ descr +"\" com essa ação.",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, inativar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "proposta_comercial/inativar");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                dados: {id: id}
            },
            error: function (data) {
                if (data.status && data.status === 401)
                {
                    swal({
                        title: "Erro de Permissão",
                        text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                        type: "warning"
                    });
                }
            },
            success: function (data) {
                if (data.operacao){
                    swal({
                        title: "Inativado!",
                        text: "O registro \""+ descr +"\" foi inativado com sucesso.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        window.location.reload(true);
                    });
                } else {
                    swal({
                        title: "Inativar",
                        text: data.mensagem,
                        type: "error"
                    });
                }
            }
        });
    });
}

function excluir(id, descr)
{
    'use strict';
    swal({
        title: "Tem certeza que deseja excluir o registro \""+ descr +"\"?",
        text: "O sistema irá excluir o registro \""+ descr +"\" com essa ação. Essa é uma ação irreversível!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "proposta_comercial/excluir");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                dados: {id: id}
            },
            error: function (data) {
                if (data.status && data.status === 401)
                {
                    swal({
                        title: "Erro de Permissão",
                        text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                        type: "warning"
                    });
                }
            },
            success: function (data) {
                if (data.operacao){
                    swal({
                        title: "Excluído!",
                        text: "O registro \""+ descr +"\" foi excluído com sucesso.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        window.location.reload(true);
                    });
                } else {
                    swal({
                        title: "Excluir",
                        text: data.mensagem,
                        type: "error"
                    });
                }
            }
        });
    });
}

function visualizar(id, ocultar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'visualizarPropostaComercial', id: id},
        complete: function () {
            if (ocultar) {
                $("#formCadastro input").attr('readonly', 'readonly');
                $("#formCadastro select").attr('readonly', 'readonly');
                $("#formCadastro textarea").attr('readonly', 'readonly');
                $("#salvarCadastro").hide();
            } else {
                $("#formCadastro input").removeAttr('readonly', 'readonly');
                $("#formCadastro select").removeAttr('readonly', 'readonly');
                $("#formCadastro textarea").removeAttr('readonly', 'readonly');
                $("#salvarCadastro").val('editar');
                $("#salvarCadastro").show();
                $('.hide_buttons').show();
            }
            $('#primeira_aba').trigger('click');
            $("#modalCadastro").modal();
        },
        error: function (data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function (data) {
            $('#id').val(data.dados.id);
            $('#lid_cliente').val(data.dados.ds_cliente);
            $('#id_cliente').val(data.dados.id_cliente);
            $('#id_status').val(data.dados.id_status).selected = "true";
            $('#id_tipo_proposta').val(data.dados.id_tipo_proposta).selected = "true";
            $('#id_localizacao').val(data.dados.id_localizacao).selected = "true";
            $('#data_proposta').val(data.dados.data_proposta);
            $('#data_contrato').val(data.dados.data_contrato);
            $('#numero').val(data.dados.numero);
            $('#vencimento').val(data.dados.vencimento);
            $('#reajuste').val(data.dados.reajuste);
            $('#desconto').val(data.dados.desconto);
            $('#imposto').val(data.dados.imposto);
            $('#encargos').val(data.dados.encargos);
            $('#valor_global').val(data.dados.valor_global);
            $('#objetivo').val(data.dados.objetivo);
            $('#objetivo_especifico').val(data.dados.objetivo_especifico);
            $('#descritivo').val(data.dados.descritivo);
            $('#responsabilidade').val(data.dados.responsabilidade);
            $('#condicoes_pgto').val(data.dados.condicoes_pgto);
            $('#prazo_execucao').val(data.dados.prazo_execucao);
            $('#consideracoes').val(data.dados.consideracoes);
            montarTabelaComponente(data.dados.id, ocultar);
            montarTabelaAnexosv(data.dados.id, ocultar);
        }
    });
}

function limpar()
{
    'use strict';
    $('#fieldPesquisa').val('');
    $('#formPesquisa').submit();
}

function montarTabelaAnexosv(id_proposta, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarPropostaComercialAnexos', id: id_proposta },
        complete: function() {
            if (visualizar) {
                $('#tab-anexos').removeClass('disabled');
                $('.hide_buttons').hide();
            }
        },
        error: function(data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function(data) {
            $('.tr_remove_anexov').remove();
            var linhas =null;
            if (data.dados.length > 0){
                $.each(data.dados, function(key, value) {
                    linhas += '<tr class="tr_remove_anexov">';
                    linhas += '<td>'+ value.ds_tipo_anexo +'</td>';
                    linhas += '<td>'+ value.descricao +'</td>';
                    linhas += '<td>'+ value.data_criacao +'</td>';
                    linhas += '<td><a href="'+ value.url +'" class="botoes_acao nova-aba" download><img src="public/images/sistema/download.png" title="Baixar" alt="Baixar" height="25" width="25"></a></td>';
                    linhas += '</tr>';
                });
                $("#tabela_lista_anexosv").append(linhas);
            } else {
                linhas += "<tr class='tr_remove_anexov'>";
                linhas += "<td colspan='5' style='text-align: center;'>Não existem anexos para serem exibidos! Favor Cadastrar!</td>";
                linhas += "</tr>";
                $("#tabela_lista_anexosv").append(linhas);
            }
        }
    });
}

function montarTabelaComponente(id_proposta_comercial, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarPropostaItens', id: id_proposta_comercial },
        complete: function() {
            $('#tabela_componentes').removeAttr('style', 'display: none;');
            $('#tabela_componentes').attr('style', 'display: table;');
            if (visualizar) {
                $('.hide_buttons').hide();
            }
        },
        error: function(data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function(data) {
            $('.tr_remove_vis').remove();
            var valor_global = 0;
            var linhas = null;
            $.each(data.dados, function(key, value) {
                valor_global += accounting.unformat(value.valor_total);
                var ds_imposto = (value.imposto === '1') ? 'Sim' : 'Não';
                var ds_reajuste = (value.reajuste === '1') ? 'Sim' : 'Não';
                linhas += '<tr class="tr_remove_vis">';
                linhas += '<td>'+ value.ds_codigo_servico +'</td>';
                linhas += '<td>'+ value.ds_proposta_comercial_servicos +'<input name="res_id_proposta_comercial_servicos_item[]" type="hidden" value="'+ value.id_proposta_comercial_servicos +'" /></td>';
                linhas += '<td>'+ value.ds_proposta_comercial_servicos_unidade +'</td>';
                linhas += '<td>'+ ds_imposto +'<input name="res_imposto_item[]" type="hidden" value="'+ value.imposto +'" /></td>';
                linhas += '<td>'+ ds_reajuste +'<input name="res_reajuste_item[]" type="hidden" value="'+ value.reajuste +'" /></td>';
                linhas += '<td>'+ value.quantidade +'<input name="res_quantidade_item[]" type="hidden" value="'+ value.quantidade +'" /></td>';
                linhas += '<td>'+ value.mes_inicial +'<input name="res_mes_inicial_item[]" type="hidden" value="'+ value.mes_inicial +'" /></td>';
                linhas += '<td>'+ value.vigencia +'<input name="res_vigencia_item[]" type="hidden" value="'+ value.vigencia +'" /></td>';
                linhas += '<td>'+ accounting.formatMoney(value.valor_unitario, "R$ ", 2, ".", ",") +'<input name="res_valor_unitario_item[]" type="hidden" value="'+ value.valor_unitario +'" /></td>';
                linhas += '<td>'+ accounting.formatMoney(value.valor_total, "R$ ", 2, ".", ",") +'<input name="res_valor_total_item[]" type="hidden" value="'+ value.valor_total +'" /></td>';
                linhas += '<td>'+ accounting.formatMoney(value.valor_total_reajuste, "R$ ", 2, ".", ",") +'<input name="res_valor_total_reajuste_item[]" type="hidden" value="'+ value.valor_total_reajuste +'" /></td>';
                linhas += '<td>'+ accounting.formatMoney(value.valor_impostos, "R$ ", 2, ".", ",") +'<input name="res_valor_impostos_item[]" type="hidden" value="'+ value.valor_impostos +'" /></td>';
                linhas += '<td>'+ accounting.formatMoney(value.valor_total_impostos, "R$ ", 2, ".", ",") +'<input name="res_valor_total_impostos_item[]" type="hidden" value="'+ value.valor_total_impostos +'" /></td>';
                if (visualizar) {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesComponente(' + value.id_proposta_comercial_item + ', ' + true + ');" class="botoes_acao"><img src="public/images/sistema/visualizar.png" title="Visualizar" alt="Visualizar" height="25" width="25"></a></td>';
                } else {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesComponente(' + value.id_proposta_comercial_item + ', ' + false + ');" class="botoes_acao"><img src="public/images/sistema/editar.png" title="Editar" alt="Editar" height="25" width="25"></a>' +
                        '<a href="javascript:void(0)" onclick="excluirComponente(' + value.id_proposta_comercial_item + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
                }
                linhas += '</tr>';
            });
            $("#tabela_componentes").append(linhas);
            atualizaValorTotalMensal(valor_global, 0);
        }
    });
}

function criarComponente()
{
    'use strict';
    limparDadosFormComponente();
    $('.hide_buttons').show();
    $('#bt_inserir_servico').text("Inserir");
    $('#bt_inserir_servico').removeAttr('onclick');
    $('#bt_inserir_servico').attr('onclick', 'inserirComponente();');
    $('#grupo').focus();
}

function checkMesVigencia(mes)
{
    'use strict';
    if (parseFloat(mes) >= 1 && parseFloat(mes) <= 12) {
        return true;
    } else {
        return false;
    }
}

function checkAnoVigencia(ano)
{
    'use strict';
    if (parseFloat(ano) >= 1 && parseFloat(ano) <= 60) {
        return true;
    } else {
        return false;
    }
}

function inserirComponente()
{
    'use strict';
    //Dados
    var codigo_servico = $('#codigo_servico').val();
    var descricao_servico = $('#descricao_servico').val();
    var id_servico = $('#id_servico').val();
    var grandeza = $('#grandeza').val();
    var quantidade_unitaria = $('#quantidade_unitaria_servico').val();
    var ds_tem_imposto = document.getElementById("tem_imposto").options[document.getElementById("tem_imposto").selectedIndex].text;
    var tem_imposto = $('#tem_imposto').val();
    var ds_tem_reajuste = document.getElementById("tem_reajuste").options[document.getElementById("tem_reajuste").selectedIndex].text;
    var tem_reajuste = $('#tem_reajuste').val();
    var mes_inicial = $('#mes_inicial_servico').val();
    var vigencia = $('#vigencia_servico').val();
    var valor_unitario = accounting.unformat($('#valor_unitario_servico').val(), ",");
    var valor_total = valor_unitario * accounting.unformat(quantidade_unitaria, ",");
    var valor_impostos = (tem_imposto === '1') ? accounting.unformat(valor_total, ",") * (accounting.unformat($('#imposto').val(), ",") * 0.01) : 0;
    var valor_reajuste = (tem_reajuste === '1') ? accounting.unformat(valor_total, ",") * (accounting.unformat($('#reajuste').val(), ",") * 0.01) : 0;
    var valor_total_reajuste = (tem_reajuste === '1') ? accounting.unformat(valor_total, ",") + accounting.unformat(valor_reajuste, ",") : 0;
    var valor_total_imposto = (tem_imposto === '1') ? accounting.unformat(valor_total, ",") + accounting.unformat(valor_impostos, ",") : 0;
    if (!checkMesVigencia(mes_inicial)){
        swal({
            title: "Campos Obrigatórios!",
            text: "Você precisa preencher o mês de vigência com valores entre 1 e 12!",
            type: "warning"
        });
    } else if (!checkAnoVigencia(vigencia)){
        swal({
            title: "Campos Obrigatórios!",
            text: "Você precisa preencher o ano de vigência com valores entre 1 e 60!",
            type: "warning"
        });
    } else if (id_servico !== '' && quantidade_unitaria > 0 && tem_imposto !== '' && tem_reajuste !== '' && mes_inicial !== '' && vigencia !== ''){//Campos Obrigatórios
        var linhas = null;
        linhas += '<tr class="tr_remove">';
        linhas += '<td>'+ codigo_servico +'</td>';
        linhas += '<td>'+ descricao_servico +'<input name="id_proposta_comercial_servicos_item[]" type="hidden" value="'+ id_servico +'" /></td>';
        linhas += '<td>'+ grandeza +'</td>';
        linhas += '<td>'+ ds_tem_imposto +'<input name="imposto_item[]" type="hidden" value="'+ tem_imposto +'" /></td>';
        linhas += '<td>'+ ds_tem_reajuste +'<input name="reajuste_item[]" type="hidden" value="'+ tem_reajuste +'" /></td>';
        linhas += '<td>'+ quantidade_unitaria +'<input name="quantidade_item[]" type="hidden" value="'+ quantidade_unitaria +'" /></td>';
        linhas += '<td>'+ mes_inicial +'<input name="mes_inicial_item[]" type="hidden" value="'+ mes_inicial +'" /></td>';
        linhas += '<td>'+ vigencia +'<input name="vigencia_item[]" type="hidden" value="'+ vigencia +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_unitario, "R$ ", 2, ".", ",") +'<input name="valor_unitario_item[]" type="hidden" value="'+ valor_unitario +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_total, "R$ ", 2, ".", ",") +'<input name="valor_total_item[]" type="hidden" value="'+ valor_total +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_total_reajuste, "R$ ", 2, ".", ",") +'<input name="valor_total_reajuste_item[]" type="hidden" value="'+ valor_total_reajuste +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_impostos, "R$ ", 2, ".", ",") +'<input name="valor_impostos_item[]" type="hidden" value="'+ valor_impostos +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_total_imposto, "R$ ", 2, ".", ",") +'<input name="valor_total_impostos_item[]" type="hidden" value="'+ valor_total_imposto +'" /></td>';
        linhas += '<td><a data-valor-total="'+ valor_total +'" href="javascript:void(0)" onclick="RemoveTableRow(this);removerValoresTotais(this)" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
        linhas += '</tr>';
        $("#tabela_componentes").append(linhas);
        atualizaValorTotalMensal(valor_total, accounting.unformat($('#demonstrativo_valor_total').html(), ","), mes_inicial, vigencia);
        $('#tabela_componentes').removeAttr('style','display: none;');
        $('#tabela_componentes').attr('style', 'display: table;');
        limparDadosFormComponente();
    } else {
        swal({
            title: "Campos Obrigatórios!",
            text: "Você precisa preencher todos os campos obrigatórios no formulário!",
            type: "warning"
        });
    }
}

function atualizaValorTotalMensal(valor_total, valor_total_atual, mes_inicial, vigencia)
{
    'use strict';
    var novo_valor_total = valor_total_atual + valor_total;
    $('#demonstrativo_valor_total').html('');
    $('#demonstrativo_valor_total').html(accounting.formatMoney(novo_valor_total, '', 2, '.', ','));
    $('#valor_global').val(accounting.formatMoney(novo_valor_total, '', 2, '.', ','));
    //Valores Mensais
    var valor_mes = accounting.unformat(novo_valor_total, ",") / 12;
    $('#mes_1').html('');
    $('#mes_1').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jan').val(valor_mes);
    $('#mes_2').html('');
    $('#mes_2').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#fev').val(valor_mes);
    $('#mes_3').html('');
    $('#mes_3').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#mar').val(valor_mes);
    $('#mes_4').html('');
    $('#mes_4').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#abr').val(valor_mes);
    $('#mes_5').html('');
    $('#mes_5').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#mai').val(valor_mes);
    $('#mes_6').html('');
    $('#mes_6').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jun').val(valor_mes);
    $('#mes_7').html('');
    $('#mes_7').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jul').val(valor_mes);
    $('#mes_8').html('');
    $('#mes_8').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#ago').val(valor_mes);
    $('#mes_9').html('');
    $('#mes_9').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#set').val(valor_mes);
    $('#mes_10').html('');
    $('#mes_10').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#out').val(valor_mes);
    $('#mes_11').html('');
    $('#mes_11').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#nov').val(valor_mes);
    $('#mes_12').html('');
    $('#mes_12').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#dez').val(valor_mes);
}

function removerValoresTotais(seletor)
{
    'use strict';
    var valor_total_removido = accounting.unformat($(seletor).attr('data-valor-total'), ",");
    // Redução do Valor Total
    var valor_total_atual = accounting.unformat($('#demonstrativo_valor_total').html(), ",");
    var novo_valor_total = valor_total_atual - valor_total_removido;
    $('#demonstrativo_valor_total').html('');
    $('#demonstrativo_valor_total').html(accounting.formatMoney(novo_valor_total, '', 2, '.', ','));
    $('#valor_global').val(accounting.formatMoney(novo_valor_total, '', 2, '.', ','));
    // Redução dos valores mensais
    var valor_mes = accounting.unformat(novo_valor_total, ",") / 12;
    $('#mes_1').html('');
    $('#mes_1').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jan').val(valor_mes);
    $('#mes_2').html('');
    $('#mes_2').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#fev').val(valor_mes);
    $('#mes_3').html('');
    $('#mes_3').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#mar').val(valor_mes);
    $('#mes_4').html('');
    $('#mes_4').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#abr').val(valor_mes);
    $('#mes_5').html('');
    $('#mes_5').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#mai').val(valor_mes);
    $('#mes_6').html('');
    $('#mes_6').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jun').val(valor_mes);
    $('#mes_7').html('');
    $('#mes_7').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jul').val(valor_mes);
    $('#mes_8').html('');
    $('#mes_8').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#ago').val(valor_mes);
    $('#mes_9').html('');
    $('#mes_9').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#set').val(valor_mes);
    $('#mes_10').html('');
    $('#mes_10').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#out').val(valor_mes);
    $('#mes_11').html('');
    $('#mes_11').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#nov').val(valor_mes);
    $('#mes_12').html('');
    $('#mes_12').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#dez').val(valor_mes);
}

function cancelarComponente()
{
    'use strict';
    $('#bt_inserir_servico').text("Inserir");
    $('#bt_inserir_servico').removeAttr('onclick');
    $('#bt_inserir_servico').attr('onclick', 'inserirComponente();');
    verificarAlteracao();
    limparDadosFormComponente();
}

function limparDadosFormComponente()
{
    'use strict';
    $('#valor_unitario_servico').val('');
    $('#quantidade_unitaria_servico').val('');
    $('#codigo_servico').val('');
    $('#id_codigo_servico').val('');
    $('#descricao_servico').val('');
    $('#id_servico').val('');
    $('#grupo').val(null).selected = 'true';
    $('#tem_imposto').val('0').selected = 'true';
    $('#tem_reajuste').val('0').selected = 'true';
    $('#mes_inicial_servico').val('1').selected = 'true';
    $('#vigencia_servico').val('12').selected = 'true';
    resetSelecaoSubgrupo();
    $('#grupo').focus();
}

function exibirDetalhesComponente(id, ocultar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarPropostaItem', id: id },
        complete: function() {
            if (ocultar) {
                $("#formCadastro input").attr('readonly', 'readonly');
                $("#formCadastro select").attr('readonly', 'readonly');
                $("#formCadastro textarea").attr('readonly', 'readonly');
                $('.hide_buttons').hide();
                $("#salvarCadastro").hide();
            } else {
                $('#bt_inserir_servico').text("Alterar");
                $('#bt_inserir_servico').removeAttr('onclick');
                $('#bt_inserir_servico').attr('onclick', 'editarComponente(' + id + ');');
                $('.hide_buttons').show();
            }
        },
        error: function(data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function(data) {
            $('#tem_imposto').val(data.dados.imposto).selected = 'true';
            $('#tem_reajuste').val(data.dados.reajuste).selected = 'true';
            $('#mes_inicial_servico').val(data.dados.mes_inicial).selected = 'true';
            $('#vigencia_servico').val(data.dados.vigencia).selected = 'true';
            $('#codigo_servico').val(data.dados.ds_codigo_servico);
            $('#id_codigo_servico').val(data.dados.id_proposta_comercial_servicos);
            $('#descricao_servico').val(data.dados.ds_proposta_comercial_servicos);
            $('#id_servico').val(data.dados.id_proposta_comercial_servicos);
            $('#grandeza').val(data.dados.ds_proposta_comercial_servicos_unidade);
            $('#quantidade_unitaria_servico').val(data.dados.quantidade);
            $('#valor_unitario_servico').val(accounting.formatMoney(data.dados.valor_unitario, "R$ ", 2, ".", ","));
        }
    });
}

function editarComponente(id)
{
    'use strict';
    var valor_unitario = accounting.unformat($('#valor_unitario_servico').val(), ",");
    var valor_total = valor_unitario * accounting.unformat($('#quantidade_unitaria_servico').val(), ",");
    var valor_impostos = accounting.unformat(valor_total, ",") * (accounting.unformat($('#imposto').val(), ",") * 0.01);
    var valor_reajuste = accounting.unformat(valor_total, ",") * (accounting.unformat($('#reajuste').val(), ",") * 0.01);
    var valor_total_reajuste = (tem_reajuste === '1') ? accounting.unformat(valor_total, ",") + accounting.unformat(valor_reajuste, ",") : 0;
    var valor_total_imposto = (tem_imposto === '1') ? accounting.unformat(valor_total, ",") + accounting.unformat(valor_impostos, ",") : 0;
    var array_dados = {
        id: id,
        id_servico: $('#id_servico').val(),
        imposto: $('#tem_imposto').val(),
        reajuste: $('#tem_reajuste').val(),
        mes_inicial: $('#mes_inicial_servico').val(),
        vigencia: $('#vigencia_servico').val(),
        quantidade: $('#quantidade_unitaria_servico').val(),
        valor_unitario: valor_unitario,
        valor_total: valor_total,
        valor_total_reajuste: valor_total_reajuste,
        valor_impostos: valor_impostos,
        valor_total_impostos: valor_total_imposto
    };
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAcao");
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: action,
        data: { metodo: 'alterarPropostaItem', array_dados: array_dados },
        complete: function() {},
        error: function(data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function(data) {
            $('.tr_remove_vis').remove();
            limparDadosFormComponente();
            montarTabelaComponente(data.dados.id_proposta_comercial, false);
            swal({
                title: "Alteração de Item",
                text: 'Item alterado com sucesso!',
                type: "success"
            });
        }
    });
}

function excluirComponente(id)
{
    'use strict';
    swal({
        title: "Tem certeza que deseja excluir o registro?",
        text: "O sistema irá excluir o registro selecionado com essa ação.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAcao");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: { metodo: 'deletarPropostaItem', id: id },
            complete: function() {},
            error: function(data) {
                if (data.status && data.status === 401) {
                    swal({
                        title: "Erro de Permissão",
                        text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                        type: "warning"
                    });
                }
            },
            success: function(data) {
                $('.tr_remove_vis').remove();
                limparDadosFormComponente();
                montarTabelaComponente(data.dados, false);
                swal({
                    title: "Exclusão de Item",
                    text: 'Item excluído com sucesso!',
                    type: "success"
                });
            }
        });
    });
}

function resetSelecaoSubgrupo() {
    'use strict';
    $('.remover_subgrupo').remove();
    $('#subgrupo').attr('disabled', 'disabled');
}

function mostrarSubgrupo()
{
    'use strict';
    var id_servico = $('#grupo').val();
    if (id_servico){//Fazer a busca e montar o outro select
        var action = actionCorreta(window.location.href.toString(), "core/processarAjaxSelect");
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: action,
            data: {metodo: 'selectSubGrupo', id: id_servico},
            complete: function () {
                $('#subgrupo').removeAttr('disabled');
            },
            error: function (data) {
                if (data.status && data.status === 401) {
                    swal({
                        title: "Erro de Permissão",
                        text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                        type: "warning"
                    });
                }
            },
            success: function (data) {
                $('.remover_subgrupo').remove();
                $.each(data.dados, function (key, value) {
                    var opcao = '<option class="remover_subgrupo" value="' + value.id + '">' + value.descricao + '</option>';
                    $('#subgrupo').append(opcao);
                });
            }
        });
    } else {//Mostrar a mensagem de elemento vazio para subgrupo
        $('.remover_subgrupo').remove();
        $('#subgrupo').attr('disabled', 'disabled');
    }
}

function selecaoDescricaoServico()
{
    'use strict';
    var id_servico = $('#id_codigo_servico').val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxSelect");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'selectIdServico', id: id_servico},
        error: function (data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function (data) {
            $('#descricao_servico').val(data.dados.descricao);
            $('#id_servico').val(data.dados.id);
            $('#grandeza').val(data.dados.grandeza);
        }
    });
}

function selectCodigoServico()
{
    'use strict';
    var id_servico = $('#id_servico').val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxSelect");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'selectIdServico', id: id_servico},
        error: function (data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function (data) {
            $('#codigo_servico').val(data.dados.codigo_legado);
            $('#id_codigo_servico').val(data.dados.id);
            $('#grandeza').val(data.dados.grandeza);
        }
    });
}

function criarAnexo(id_proposta)
{
    'use strict';
    $('#id_proposta').val(id_proposta);
    getIdentificador(id_proposta);
    montarTabelaAnexos(id_proposta, false);
    $('#tabela_lista_anexos').removeAttr('style', 'display: table;');
    $('#tabela_lista_anexos').attr('style', 'display: none;');
    $('.selectpicker').prop('disabled', false);
    $('.selectpicker').selectpicker('refresh');
    $('#modalAnexoArquivo').modal();

}

function getIdentificador(id)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'visualizarPropostaComercialNumero', id: id},
        error: function (data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function (data) {
            $('#identificador-anexado').html(data.dados);
        }
    });
}

function montarTabelaAnexos(id_proposta, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarPropostaComercialAnexos', id: id_proposta },
        complete: function() {
            if (visualizar) {
                $('.hide_buttons').hide();
            }
        },
        error: function(data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function(data) {
            if (data.dados.length > 0){
                $('.tr_remove_anexo').remove();
                var linhas =null;
                $.each(data.dados, function(key, value) {
                    linhas += '<tr class="tr_remove_anexo">';
                    linhas += '<td>'+ value.ds_tipo_anexo +'</td>';
                    linhas += '<td>'+ value.descricao +'</td>';
                    linhas += '<td>'+ value.data_criacao +'</td>';
                    linhas += '<td><a href="'+ value.url +'" class="botoes_acao nova-aba" download><img src="public/images/sistema/download.png" title="Baixar" alt="Baixar" height="25" width="25"></a>' +
                        '<a href="javascript:void(0)" onclick="excluirAnexo(' + value.id_anexo + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
                    linhas += '</tr>';
                });
                $("#tabela_lista_anexos").append(linhas);
                $('#tabela_lista_anexos').removeAttr('style', 'display: none;');
                $('#tabela_lista_anexos').attr('style', 'display: table;');
            } else {
                $('#tabela_lista_anexos').removeAttr('style', 'display: table;');
                $('#tabela_lista_anexos').attr('style', 'display: none;');
            }
        }
    });
}

function getTiposAnexo()
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxSelect");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'selectTiposAnexos'},
        error: function (data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function (data) {
            tipos_anexos = data.dados;
        }
    });
    return tipos_anexos;
}

function inserirAnexo()
{
    'use strict';
    contador++;
    var elemento = '<div id="div-anexo-'+ contador +'" class="form-row anexos-complementares">';
    elemento += '<div class="form-group col-md-3">';
    elemento += '<label for="id_tipo_anexo-'+ contador +'">Tipo de Anexo <span class="required">*</span></label>';
    elemento += '<select id="id_tipo_anexo-'+ contador +'" name="id_tipo_anexo[]" class="form-control selectpicker" data-live-search="true" data-style="btn-light">';
    elemento += '<option value="">Selecione o Tipo de Anexo</option>';
    $.each(tipos_anexos, function(key, value) {
        elemento += '<option value="'+value.id+'">'+value.descricao+'</option>';
    });
    elemento += '</select>';
    elemento += '</div>';
    elemento += '<div class="form-group col-md-3 botoes_forms_alinhamento">';
    elemento += '<label for="descricao-'+ contador +'">Descrição </label>';
    elemento += '<input type="text" id="descricao-'+ contador +'" name="descricao[]" class="form-control" size="200" placeholder="Descrição">';
    elemento += '</div>';
    elemento += '<div class="form-group col-md-5">';
    elemento += '<label for="anexo-'+ contador +'">Anexo <span class="required">*</span></label>';
    elemento += '<input type="file" id="anexo-'+ contador +'" name="anexo-'+ contador +'" class="filestyle">';
    elemento += '</div>';
    elemento += '<div class="form-group col-md-1 botoes_forms_alinhamento">';
    elemento += '<a href="javascript:void(0)" onclick="removerAnexo('+ contador +');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Remover" alt="Remover" height="25" width="25" style="margin: 35px 0px 0px 10px;"></a>';
    elemento += '</div>';
    elemento += '</div>';
    $('#agrupamento-anexos').append(elemento);
    $('.selectpicker').selectpicker();
    $(':file').last().filestyle({
        dragdrop: true,
        input: true,
        htmlIcon: '<span class="fi-paper-clip"></span>',
        text: 'Localize o Arquivo',
        btnClass: 'btn-primary',
        disabled: false,
        buttonBefore: true,
        badge: true,
        badgeName: 'badge-danger',
        placeholder: 'Sem arquivo',

    });
}

function removerAnexo(contador)
{
    'use strict';
    $('#div-anexo-'+ contador).remove();
}

function limparModalAnexos()
{
    'use strict';
    contador = 0;
    $('.anexos-complementares').remove();
    $('#modalAnexoArquivo').find('form')[0].reset();
}

function excluirAnexo(id_anexo)
{
    'use strict';
    swal({
        title: "Tem certeza que deseja excluir o anexo?",
        text: "O sistema irá excluir o anexo selecionado com essa ação.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAcao");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {metodo: 'excluirPropostaComercialAnexo', id: id_anexo},
            error: function (data) {
                if (data.status && data.status === 401) {
                    swal({
                        title: "Erro de Permissão",
                        text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                        type: "warning"
                    });
                }
            },
            success: function (data) {
                $('.tr_remove_anexo').remove();
                montarTabelaAnexos(data, false);
                swal({
                    title: "Exclusão de Anexo",
                    text: 'Anexo excluído com sucesso!',
                    type: "success"
                });
            }
        });
        return true;
    });
}