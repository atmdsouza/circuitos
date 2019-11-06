//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Variáveis Globais
var mudou = false;

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
    autocompletarContratoPrincipal('lid_contrato_principal','id_contrato_principal');
    autocompletarPropostaComercial('lid_proposta_comercial','id_proposta_comercial');
    autocompletarClienteFornecedorParceiro('lid_cliente','id_cliente');
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
        }).catch(swal.noop);
    }
    else
    {
        $("#"+modal).modal('hide');
        limparModalBootstrap(modal);
    }
}

function criar()
{
    'use strict';
    $('#primeira_aba').trigger('click');
    $('.hide_buttons').show();
    $('#bt_inserir_garantia').text("Inserir");
    $('#bt_inserir_garantia').removeAttr('onclick');
    $('#bt_inserir_garantia').attr('onclick', 'inserirGarantia();');
    $('#bt_inserir_exercicio').text("Inserir");
    $('#bt_inserir_exercicio').removeAttr('onclick');
    $('#bt_inserir_exercicio').attr('onclick', 'inserirExercicio();');
    $('#bt_inserir_orcamento').text("Inserir");
    $('#bt_inserir_orcamento').removeAttr('onclick');
    $('#bt_inserir_orcamento').attr('onclick', 'inserirOrcamento();');
    limparDadosFormGarantia();
    limparDadosFormExercicio();
    limparDadosFormOrcamento();
    $('.tr_remove_prc').remove();
    $('.tr_remove_exe').remove();
    $('.tr_remove_gar').remove();
    $('#tabela_orcamentos').removeAttr('style', 'display: table;');
    $('#tabela_orcamentos').attr('style', 'display: none;');
    $('#tabela_exercicios').removeAttr('style', 'display: table;');
    $('#tabela_exercicios').attr('style', 'display: none;');
    $('#tabela_garantias').removeAttr('style', 'display: table;');
    $('#tabela_garantias').attr('style', 'display: none;');
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
            numero_processo:{
                required: true
            },
            id_tipo_processo:{
                required: true
            },
            id_tipo_contrato:{
                required: true
            },
            id_status:{
                required: true
            },
            lid_cliente:{
                required: true
            },
            vincular_instrumento:{
                required: true
            },
            data_assinatura:{
                required: true
            },
            data_encerramento:{
                required: true
            },
            vigencia_tipo:{
                required: true,
                maiorQueZero: true
            },
            vigencia_prazo:{
                required: true
            },
            numero:{
                required: true
            },
            ano:{
                required: true
            },
            valor_global:{
                required: true
            },
            valor_mensal:{
                required: true
            }
        },
        messages:{
            numero_processo:{
                required: "É necessário informar o Número do Processo"
            },
            id_tipo_processo:{
                required: "É necessário informar o Tipo do Processo"
            },
            id_status:{
                required: "É necessário informar o Status"
            },
            lid_cliente:{
                required: "É necessário informar um Cliente, Fornecedor ou Parceiro"
            },
            id_tipo_contrato:{
                required: "É necessário informar o Tipo do Contrato"
            },
            vincular_instrumento:{
                required: "É necessário informar se existe instrumento principal"
            },
            data_assinatura:{
                required: "É necessário informar a Data de Assinatura"
            },
            data_encerramento:{
                required: "É necessário informar a Data de Encerramento"
            },
            vigencia_tipo:{
                required: "É necessário informar o Tipo de Vigência"
            },
            vigencia_prazo:{
                required: "É necessário informar o Prazo de Vigência"
            },
            numero:{
                required: "É necessário informar o Número"
            },
            ano:{
                required: "É necessário informar o Ano"
            },
            valor_global:{
                required: "É necessário informar um Valor Global"
            },
            valor_mensal:{
                required: "É necessário informar um Valor Mensal"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "contrato/" + acao);
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
        var action = actionCorreta(window.location.href.toString(), "contrato/ativar");
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
        var action = actionCorreta(window.location.href.toString(), "contrato/inativar");
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
        var action = actionCorreta(window.location.href.toString(), "contrato/excluir");
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
        data: {metodo: 'visualizarContrato', id: id},
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
            montarTabelaOrcamento(data.dados.id, ocultar);
            montarTabelaGarantia(data.dados.id, ocultar);
            montarTabelaExercicio(data.dados.id, ocultar);
        }
    });
}

function movimentar(id)
{

}

function atribuir(id)
{

}

function fiscalizar(id)
{

}

function acompanhar(id)
{

}

function limpar()
{
    'use strict';
    $('#fieldPesquisa').val('');
    $('#formPesquisa').submit();
}

function vincularContrato()
{
    'use strict';
    var vincular_instrumento = $('#vincular_instrumento').val();
    if (vincular_instrumento === '1'){
        $('#lid_contrato_principal').removeAttr('disabled');
    } else {
        $('#lid_contrato_principal').val('');
        $('#id_contrato_principal').val('');
        $('#lid_contrato_principal').attr('disabled', 'disabled');
    }
}

function preencherDadosPropostaComercial()
{
    'use strict';
}

/*
* Orçamento
* */
function montarTabelaOrcamento(id_contrato, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoOrcamento', id: id_contrato },
        complete: function() {
            $('#tabela_orcamentos').removeAttr('style', 'display: none;');
            $('#tabela_orcamentos').attr('style', 'display: table;');
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
            $('.tr_remove_orc_vis').remove();
            var linhas = null;
            $.each(data.dados, function(key, value) {
                linhas += '<tr class="tr_remove_orc_vis">';
                linhas += '<td>'+ value.unidade_orcamentaria +'<input name="res_unidade_orcamentaria[]" type="hidden" value="'+ value.unidade_orcamentaria +'" /></td>';
                linhas += '<td>'+ value.fonte_orcamentaria +'<input name="res_fonte_orcamentaria[]" type="hidden" value="'+ value.fonte_orcamentaria +'" /></td>';
                linhas += '<td>'+ value.programa_trabalho +'<input name="res_programa_trabalho[]" type="hidden" value="'+ value.programa_trabalho +'" /></td>';
                linhas += '<td>'+ value.elemento_despesa +'<input name="res_elemento_despesa[]" type="hidden" value="'+ value.elemento_despesa +'" /></td>';
                linhas += '<td>'+ value.pi +'<input name="res_pi[]" type="hidden" value="'+ value.pi +'" /></td>';
                if (visualizar) {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesOrcamento(' + value.id_contrato_orcamento + ', ' + true + ');" class="botoes_acao"><img src="public/images/sistema/visualizar.png" title="Visualizar" alt="Visualizar" height="25" width="25"></a></td>';
                } else {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesOrcamento(' + value.id_contrato_orcamento + ', ' + false + ');" class="botoes_acao"><img src="public/images/sistema/editar.png" title="Editar" alt="Editar" height="25" width="25"></a>' +
                        '<a href="javascript:void(0)" onclick="excluirOrcamento(' + value.id_contrato_orcamento + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
                }
                linhas += '</tr>';
            });
            $("#tabela_orcamentos").append(linhas);
        }
    });
}

function criarOrcamento()
{
    'use strict';
    limparDadosFormOrcamento();
    $('.hide_buttons').show();
    $('#bt_inserir_orcamento').text("Inserir");
    $('#bt_inserir_orcamento').removeAttr('onclick');
    $('#bt_inserir_orcamento').attr('onclick', 'inserirOrcamento();');
    $('#dados_orcamento').removeAttr('style', 'display: none;');
    $('#dados_orcamento').attr('style', 'display: block;');
    $('#grupo').focus();
}

function inserirOrcamento()
{
    'use strict';
    //Dados
    var unidade_orcamentaria = $('#v_unidade_orcamentaria').val();
    var fonte_orcamentaria = $('#v_fonte_orcamentaria').val();
    var programa_trabalho = $('#v_programa_trabalho').val();
    var elemento_despesa = $('#v_elemento_despesa').val();
    var pi = $('#v_pi').val();
    if(unidade_orcamentaria !== '' && fonte_orcamentaria !== '' && programa_trabalho !== '' && elemento_despesa !== ''){//Campos Obrigatórios
        var linhas = null;
        linhas += '<tr class="tr_remove_orc">';
        linhas += '<td>'+ unidade_orcamentaria +'<input name="unidade_orcamentaria[]" type="hidden" value="'+ unidade_orcamentaria +'" /></td>';
        linhas += '<td>'+ fonte_orcamentaria +'<input name="fonte_orcamentaria[]" type="hidden" value="'+ fonte_orcamentaria +'" /></td>';
        linhas += '<td>'+ programa_trabalho +'<input name="programa_trabalho[]" type="hidden" value="'+ programa_trabalho +'" /></td>';
        linhas += '<td>'+ elemento_despesa +'<input name="elemento_despesa[]" type="hidden" value="'+ elemento_despesa +'" /></td>';
        linhas += '<td>'+ pi +'<input name="pi[]" type="hidden" value="'+ pi +'" /></td>';
        linhas += '<td><a href="javascript:void(0)" onclick="RemoveTableRow(this);" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
        linhas += '</tr>';
        $("#tabela_orcamentos").append(linhas);
        $('#tabela_orcamentos').removeAttr('style','display: none;');
        $('#tabela_orcamentos').attr('style', 'display: table;');
        limparDadosFormOrcamento();
    } else {
        swal({
            title: "Campos Obrigatórios!",
            text: "Você precisa preencher todos os campos obrigatórios no formulário!",
            type: "warning"
        });
    }
}

function cancelarOrcamento()
{
    'use strict';
    $('#bt_inserir_orcamento').text("Inserir");
    $('#bt_inserir_orcamento').removeAttr('onclick');
    $('#bt_inserir_orcamento').attr('onclick', 'inserirOrcamento();');
    verificarAlteracao();
    limparDadosFormOrcamento();
}

function limparDadosFormOrcamento()
{
    'use strict';
    $('#v_unidade_orcamentaria').val('');
    $('#v_fonte_orcamentaria').val('');
    $('#v_programa_trabalho').val('');
    $('#v_elemento_despesa').val('');
    $('#v_pi').val('');
    $('#dados_orcamento').removeAttr('style', 'display: block;');
    $('#dados_orcamento').attr('style', 'display: none;');
    $('#grupo').focus();
}

function exibirDetalhesOrcamento(id, ocultar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoOrcamento', id: id },
        complete: function() {
            if (ocultar) {
                $("#formCadastro input").attr('readonly', 'readonly');
                $("#formCadastro select").attr('readonly', 'readonly');
                $("#formCadastro textarea").attr('readonly', 'readonly');
                $('.hide_buttons').hide();
                $("#salvarCadastro").hide();
            } else {
                $('#bt_inserir_orcamento').text("Alterar");
                $('#bt_inserir_orcamento').removeAttr('onclick');
                $('#bt_inserir_orcamento').attr('onclick', 'editarOrcamento(' + id + ');');
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
            $('#id_codigo_servico').val(data.dados.id_contrato_servicos);
            $('#descricao_servico').val(data.dados.ds_contrato_servicos);
            $('#id_servico').val(data.dados.id_contrato_servicos);
            $('#grandeza').val(data.dados.ds_contrato_servicos_unidade);
            $('#quantidade_unitaria_servico').val(data.dados.quantidade);
            $('#valor_unitario_servico').val(accounting.formatMoney(data.dados.valor_unitario, "R$ ", 2, ".", ","));
        }
    });
}

function editarOrcamento(id)
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
        data: { metodo: 'alterarContratoOrcamento', array_dados: array_dados },
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
            $('.tr_remove_orc').remove();
            limparDadosFormOrcamento();
            montarTabelaOrcamento(data.dados.id_contrato, false);
            swal({
                title: "Alteração de Orçamento",
                text: 'Orçamento alterado com sucesso!',
                type: "success"
            });
        }
    });
}

function excluirOrcamento(id)
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
            data: { metodo: 'deletarContratoOrçamento', id: id },
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
                $('.tr_remove_orc').remove();
                limparDadosFormOrcamento();
                montarTabelaOrcamento(data.dados, false);
                swal({
                    title: "Exclusão de Orçamento",
                    text: 'Orçamento excluído com sucesso!',
                    type: "success"
                });
            }
        });
    });
}

/*
* Exercício
* */
function montarTabelaExercicio(id_contrato, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoExercicio', id: id_contrato },
        complete: function() {
            $('#tabela_exercicios').removeAttr('style', 'display: none;');
            $('#tabela_exercicios').attr('style', 'display: table;');
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
            $('.tr_remove_exe_vis').remove();
            var linhas = null;
            $.each(data.dados, function(key, value) {
                linhas += '<tr class="tr_remove_exe_vis">';
                linhas += '<td>'+ value.exercicio +'<input name="res_exercicio[]" type="hidden" value="'+ value.exercicio +'" /></td>';
                linhas += '<td>'+ value.competencia_inicial +'<input name="res_competencia_inicial[]" type="hidden" value="'+ value.competencia_inicial +'" /></td>';
                linhas += '<td>'+ value.competencia_final +'<input name="res_competencia_final[]" type="hidden" value="'+ value.competencia_final +'" /></td>';
                linhas += '<td>'+ value.valor_previsto +'<input name="res_valor_previsto[]" type="hidden" value="'+ value.valor_previsto +'" /></td>';
                if (visualizar) {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesExercicio(' + value.id_contrato_item + ', ' + true + ');" class="botoes_acao"><img src="public/images/sistema/visualizar.png" title="Visualizar" alt="Visualizar" height="25" width="25"></a></td>';
                } else {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesExercicio(' + value.id_contrato_item + ', ' + false + ');" class="botoes_acao"><img src="public/images/sistema/editar.png" title="Editar" alt="Editar" height="25" width="25"></a>' +
                        '<a href="javascript:void(0)" onclick="excluirExercicio(' + value.id_contrato_item + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
                }
                linhas += '</tr>';
            });
            $("#tabela_exercicios").append(linhas);
        }
    });
}

function criarExercicio()
{
    'use strict';
    limparDadosFormExercicio();
    $('.hide_buttons').show();
    $('#bt_inserir_exercicio').text("Inserir");
    $('#bt_inserir_exercicio').removeAttr('onclick');
    $('#bt_inserir_exercicio').attr('onclick', 'inserirExercicio();');
    $('#dados_exercicio').removeAttr('style', 'display: none;');
    $('#dados_exercicio').attr('style', 'display: block;');
    $('#grupo').focus();
}

function inserirExercicio()
{
    'use strict';
    //Dados
    var exercicio = $('#v_exercicio').val();
    var competencia_inicial = $('#v_competencia_inicial').val();
    var competencia_final = $('#v_competencia_final').val();
    var valor_previsto = $('#v_valor_previsto').val();
    if(exercicio !== '' && competencia_inicial !== '' && competencia_final !== '' && valor_previsto !== ''){//Campos Obrigatórios
        var linhas = null;
        linhas += '<tr class="tr_remove_exe">';
        linhas += '<td>'+ exercicio +'<input name="exercicio[]" type="hidden" value="'+ exercicio +'" /></td>';
        linhas += '<td>'+ competencia_inicial +'<input name="competencia_inicial[]" type="hidden" value="'+ competencia_inicial +'" /></td>';
        linhas += '<td>'+ competencia_final +'<input name="competencia_final[]" type="hidden" value="'+ competencia_final +'" /></td>';
        linhas += '<td>'+ valor_previsto +'<input name="valor_previsto[]" type="hidden" value="'+ valor_previsto +'" /></td>';
        linhas += '<td><a href="javascript:void(0)" onclick="RemoveTableRow(this);" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
        linhas += '</tr>';
        $("#tabela_exercicios").append(linhas);
        $('#tabela_exercicios').removeAttr('style','display: none;');
        $('#tabela_exercicios').attr('style', 'display: table;');
        limparDadosFormExercicio();
    } else {
        swal({
            title: "Campos Obrigatórios!",
            text: "Você precisa preencher todos os campos obrigatórios no formulário!",
            type: "warning"
        });
    }
}

function cancelarExercicio()
{
    'use strict';
    $('#bt_inserir_exercicio').text("Inserir");
    $('#bt_inserir_exercicio').removeAttr('onclick');
    $('#bt_inserir_exercicio').attr('onclick', 'inserirExercicio();');
    verificarAlteracao();
    limparDadosFormExercicio();
}

function limparDadosFormExercicio()
{
    'use strict';
    $('#v_exercicio').val('');
    $('#v_competencia_inicial').val('');
    $('#v_competencia_final').val('');
    $('#v_valor_previsto').val('');
    $('#dados_exercicio').removeAttr('style', 'display: block;');
    $('#dados_exercicio').attr('style', 'display: none;');
    $('#grupo').focus();
}

function exibirDetalhesExercicio(id, ocultar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoExercicio', id: id },
        complete: function() {
            if (ocultar) {
                $("#formCadastro input").attr('readonly', 'readonly');
                $("#formCadastro select").attr('readonly', 'readonly');
                $("#formCadastro textarea").attr('readonly', 'readonly');
                $('.hide_buttons').hide();
                $("#salvarCadastro").hide();
            } else {
                $('#bt_inserir_exercicio').text("Alterar");
                $('#bt_inserir_exercicio').removeAttr('onclick');
                $('#bt_inserir_exercicio').attr('onclick', 'editarExercicio(' + id + ');');
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
            $('#id_codigo_servico').val(data.dados.id_contrato_servicos);
            $('#descricao_servico').val(data.dados.ds_contrato_servicos);
            $('#id_servico').val(data.dados.id_contrato_servicos);
            $('#grandeza').val(data.dados.ds_contrato_servicos_unidade);
            $('#quantidade_unitaria_servico').val(data.dados.quantidade);
            $('#valor_unitario_servico').val(accounting.formatMoney(data.dados.valor_unitario, "R$ ", 2, ".", ","));
        }
    });
}

function editarExercicio(id)
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
        data: { metodo: 'alterarContratoExercicio', array_dados: array_dados },
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
            $('.tr_remove_exe').remove();
            limparDadosFormExercicio();
            montarTabelaExercicio(data.dados.id_contrato, false);
            swal({
                title: "Alteração de Exercício",
                text: 'Exercício alterado com sucesso!',
                type: "success"
            });
        }
    });
}

function excluirExercicio(id)
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
            data: { metodo: 'deletarContratoExercicio', id: id },
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
                $('.tr_remove_exe').remove();
                limparDadosFormExercicio();
                montarTabelaExercicio(data.dados, false);
                swal({
                    title: "Exclusão de Exercício",
                    text: 'Exercício excluído com sucesso!',
                    type: "success"
                });
            }
        });
    });
}

/*
* Garantia
* */
function montarTabelaGarantia(id_contrato, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoGarantia', id: id_contrato },
        complete: function() {
            $('#tabela_garantias').removeAttr('style', 'display: none;');
            $('#tabela_garantias').attr('style', 'display: table;');
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
            $('.tr_remove_gar_vis').remove();
            var linhas = null;
            $.each(data.dados, function(key, value) {
                var ds_garantia_concretizada = (value.garantia_concretizada === '1') ? 'Sim' : 'Não';
                linhas += '<tr class="tr_remove_exe_vis">';
                linhas += '<td>'+ value.ds_modalidade +'<input name="res_id_modalidade[]" type="hidden" value="'+ value.id_modalidade +'" /></td>';
                linhas += '<td>'+ ds_garantia_concretizada +'<input name="res_garantia_concretizada[]" type="hidden" value="'+ value.garantia_concretizada +'" /></td>';
                linhas += '<td>'+ value.percentual +'<input name="res_percentual[]" type="hidden" value="'+ value.percentual +'" /></td>';
                linhas += '<td>'+ value.valor_garantia +'<input name="res_valor_garantia[]" type="hidden" value="'+ value.valor_garantia +'" /></td>';
                if (visualizar) {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesGarantia(' + value.id_contrato_item + ', ' + true + ');" class="botoes_acao"><img src="public/images/sistema/visualizar.png" title="Visualizar" alt="Visualizar" height="25" width="25"></a></td>';
                } else {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesGarantia(' + value.id_contrato_item + ', ' + false + ');" class="botoes_acao"><img src="public/images/sistema/editar.png" title="Editar" alt="Editar" height="25" width="25"></a>' +
                        '<a href="javascript:void(0)" onclick="excluirGarantia(' + value.id_contrato_item + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
                }
                linhas += '</tr>';
            });
            $("#tabela_garantias").append(linhas);
        }
    });
}

function criarGarantia()
{
    'use strict';
    limparDadosFormGarantia();
    $('.hide_buttons').show();
    $('#bt_inserir_garantia').text("Inserir");
    $('#bt_inserir_garantia').removeAttr('onclick');
    $('#bt_inserir_garantia').attr('onclick', 'inserirGarantia();');
    $('#dados_garantia').removeAttr('style', 'display: none;');
    $('#dados_garantia').attr('style', 'display: block;');
    $('#grupo').focus();
}

function inserirGarantia()
{
    'use strict';
    //Dados
    var id_modalidade = $('#v_id_modalidade').val();
    var ds_modalidade = document.getElementById("v_id_modalidade").options[document.getElementById("v_id_modalidade").selectedIndex].text;
    var garantia_concretizada = $('#v_garantia_concretizada').val();
    var ds_garantia_concretizada = (garantia_concretizada === '1') ? 'Sim' : 'Não';
    var percentual = $('#v_percentual').val();
    var valor_garantia = $('#v_valor_garantia').val();
    if(id_modalidade !== '' && percentual !== '' && valor_garantia !== ''){//Campos Obrigatórios
        var linhas = null;
        linhas += '<tr class="tr_remove_exe">';
        linhas += '<td>'+ ds_modalidade +'<input name="id_modalidade[]" type="hidden" value="'+ id_modalidade +'" /></td>';
        linhas += '<td>'+ ds_garantia_concretizada +'<input name="garantia_concretizada[]" type="hidden" value="'+ garantia_concretizada +'" /></td>';
        linhas += '<td>'+ percentual +'<input name="percentual[]" type="hidden" value="'+ percentual +'" /></td>';
        linhas += '<td>'+ valor_garantia +'<input name="valor_garantia[]" type="hidden" value="'+ valor_garantia +'" /></td>';
        linhas += '<td><a href="javascript:void(0)" onclick="RemoveTableRow(this);" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
        linhas += '</tr>';
        $("#tabela_garantias").append(linhas);
        $('#tabela_garantias').removeAttr('style','display: none;');
        $('#tabela_garantias').attr('style', 'display: table;');
        limparDadosFormGarantia();
    } else {
        swal({
            title: "Campos Obrigatórios!",
            text: "Você precisa preencher todos os campos obrigatórios no formulário!",
            type: "warning"
        });
    }
}

function cancelarGarantia()
{
    'use strict';
    $('#bt_inserir_garantia').text("Inserir");
    $('#bt_inserir_garantia').removeAttr('onclick');
    $('#bt_inserir_garantia').attr('onclick', 'inserirGarantia();');
    verificarAlteracao();
    limparDadosFormGarantia();
}

function limparDadosFormGarantia()
{
    'use strict';
    $('#v_percentual').val('');
    $('#v_valor_garantia').val('');
    $('#v_id_modalidade').val(null).selected = 'true';
    $('#v_garantia_concretizada').val('0').selected = 'true';
    $('#dados_garantia').removeAttr('style', 'display: block;');
    $('#dados_garantia').attr('style', 'display: none;');
    $('#grupo').focus();
}

function exibirDetalhesGarantia(id, ocultar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoGarantia', id: id },
        complete: function() {
            if (ocultar) {
                $("#formCadastro input").attr('readonly', 'readonly');
                $("#formCadastro select").attr('readonly', 'readonly');
                $("#formCadastro textarea").attr('readonly', 'readonly');
                $('.hide_buttons').hide();
                $("#salvarCadastro").hide();
            } else {
                $('#bt_inserir_garantia').text("Alterar");
                $('#bt_inserir_garantia').removeAttr('onclick');
                $('#bt_inserir_garantia').attr('onclick', 'editarGarantia(' + id + ');');
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
            $('#id_codigo_servico').val(data.dados.id_contrato_servicos);
            $('#descricao_servico').val(data.dados.ds_contrato_servicos);
            $('#id_servico').val(data.dados.id_contrato_servicos);
            $('#grandeza').val(data.dados.ds_contrato_servicos_unidade);
            $('#quantidade_unitaria_servico').val(data.dados.quantidade);
            $('#valor_unitario_servico').val(accounting.formatMoney(data.dados.valor_unitario, "R$ ", 2, ".", ","));
        }
    });
}

function editarGarantia(id)
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
        data: { metodo: 'alterarContratoGarantia', array_dados: array_dados },
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
            $('.tr_remove_gar_vis').remove();
            limparDadosFormGarantia();
            montarTabelaGarantia(data.dados.id_contrato, false);
            swal({
                title: "Alteração de Item",
                text: 'Item alterado com sucesso!',
                type: "success"
            });
        }
    });
}

function excluirGarantia(id)
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
            data: { metodo: 'deletarContratoGarantia', id: id },
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
                $('.tr_remove_gar_vis').remove();
                limparDadosFormGarantia();
                montarTabelaGarantia(data.dados, false);
                swal({
                    title: "Exclusão de Item",
                    text: 'Item excluído com sucesso!',
                    type: "success"
                });
            }
        });
    });
}