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
    autocompletarUsuario('lid_usuario','v_id_usuario');
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
            limparDadosFormOrcamento();
            limparDadosFormExercicio();
            limparDadosFormGarantia();
            limparValidacao();
            mudou = false;
        }).catch(swal.noop);
    }
    else
    {
        $("#"+modal).modal('hide');
        limparModalBootstrap(modal);
        limparDadosFormOrcamento();
        limparDadosFormExercicio();
        limparDadosFormGarantia();
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
    $("#formCadastro input").removeAttr('disabled', 'disabled');
    $("#formCadastro select").removeAttr('disabled', 'disabled');
    $("#formCadastro textarea").removeAttr('disabled', 'disabled');
    $("#salvarCadastro").val('criar');
    $('#lid_contrato_principal').attr('disabled', 'disabled');
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
                required: true
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
            },
            objeto:{
                required: true
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
                $("#formCadastro input").attr('disabled', 'disabled');
                $("#formCadastro select").attr('disabled', 'disabled');
                $("#formCadastro textarea").attr('disabled', 'disabled');
                $("#salvarCadastro").hide();
            } else {
                $("#formCadastro input").removeAttr('disabled', 'disabled');
                $("#formCadastro select").removeAttr('disabled', 'disabled');
                $("#formCadastro textarea").removeAttr('disabled', 'disabled');
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
            $('#lid_contrato_principal').val(data.descricao.ds_contrato_principal);
            $('#id_contrato_principal').val(data.dados.id_contrato_principal);
            $('#lid_cliente').val(data.descricao.ds_cliente);
            $('#id_cliente').val(data.dados.id_cliente);
            $('#lid_proposta_comercial').val(data.descricao.ds_proposta_comercial);
            $('#id_proposta_comercial').val(data.dados.id_proposta_comercial);
            $('#id_tipo_contrato').val(data.dados.id_tipo_contrato).selected = "true";
            $('#id_status').val(data.dados.id_status).selected = "true";
            $('#id_tipo_processo').val(data.dados.id_tipo_processo).selected = "true";
            $('#vigencia_tipo').val(data.dados.vigencia_tipo).selected = "true";
            $('#vincular_instrumento').val((data.dados.id_contrato_principal) ? 1 : 0).selected = "true";
            $('#numero').val(data.dados.numero);
            $('#numero_processo').val(data.dados.numero_processo);
            $('#ano').val(data.dados.ano);
            $('#data_assinatura').val(formataDataBR(data.dados.data_assinatura));
            $('#data_publicacao').val(formataDataBR(data.dados.data_publicacao));
            $('#num_diario_oficial').val(data.dados.num_diario_oficial);
            $('#data_encerramento').val(formataDataBR(data.dados.data_encerramento));
            $('#vigencia_prazo').val(data.dados.vigencia_prazo);
            $('#objeto').val(data.dados.objeto);
            $('#objetivo_especifico').val(data.dados.objetivo_especifico);
            $('#valor_global').val(accounting.formatMoney(data.dados.valor_global, '', 2, '.', ','));
            $('#valor_mensal').val(accounting.formatMoney(data.dados.valor_mensal, '', 2, '.', ','));
            montarTabelaOrcamento(data.dados.id, ocultar);
            montarTabelaGarantia(data.dados.id, ocultar);
            montarTabelaExercicio(data.dados.id, ocultar);
            montarTabelaAnexosv(data.dados.id, ocultar);
        }
    });
}

function movimentar(id)
{

}

function fiscalizar(id)
{

}

function gerirFinanceiro(id)
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

function montarTabelaAnexosv(id_contrato, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoAnexos', id: id_contrato },
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
            if (data.dados != ''){
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

/**
* Orçamento
**/
function montarTabelaOrcamento(id_contrato, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoOrcamentos', id: id_contrato },
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
                linhas += '<td>'+ value.funcional_programatica +'<input name="res_funcional_programatica[]" type="hidden" value="'+ value.funcional_programatica +'" /></td>';
                linhas += '<td>'+ value.fonte_orcamentaria +'<input name="res_fonte_orcamentaria[]" type="hidden" value="'+ value.fonte_orcamentaria +'" /></td>';
                linhas += '<td>'+ value.programa_trabalho +'<input name="res_programa_trabalho[]" type="hidden" value="'+ value.programa_trabalho +'" /></td>';
                linhas += '<td>'+ value.elemento_despesa +'<input name="res_elemento_despesa[]" type="hidden" value="'+ value.elemento_despesa +'" /></td>';
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
    var funcional_programatica = $('#v_funcional_programatica').val();
    var fonte_orcamentaria = $('#v_fonte_orcamentaria').val();
    var programa_trabalho = $('#v_programa_trabalho').val();
    var elemento_despesa = $('#v_elemento_despesa').val();
    if(funcional_programatica !== '' && fonte_orcamentaria !== '' && programa_trabalho !== '' && elemento_despesa !== ''){//Campos Obrigatórios
        var linhas = null;
        linhas += '<tr class="tr_remove_orc">';
        linhas += '<td>'+ funcional_programatica +'<input name="funcional_programatica[]" type="hidden" value="'+ funcional_programatica +'" /></td>';
        linhas += '<td>'+ fonte_orcamentaria +'<input name="fonte_orcamentaria[]" type="hidden" value="'+ fonte_orcamentaria +'" /></td>';
        linhas += '<td>'+ programa_trabalho +'<input name="programa_trabalho[]" type="hidden" value="'+ programa_trabalho +'" /></td>';
        linhas += '<td>'+ elemento_despesa +'<input name="elemento_despesa[]" type="hidden" value="'+ elemento_despesa +'" /></td>';
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
    $('#v_funcional_programatica').val('');
    $('#v_fonte_orcamentaria').val('');
    $('#v_programa_trabalho').val('');
    $('#v_elemento_despesa').val('');
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
            $('#dados_orcamento').removeAttr('style', 'display: none;');
            $('#dados_orcamento').attr('style', 'display: block;');
            if (ocultar) {
                $("#formCadastro input").attr('disabled', 'disabled');
                $("#formCadastro select").attr('disabled', 'disabled');
                $("#formCadastro textarea").attr('disabled', 'disabled');
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
            $('#v_funcional_programatica').val(data.dados.funcional_programatica);
            $('#v_fonte_orcamentaria').val(data.dados.fonte_orcamentaria);
            $('#v_programa_trabalho').val(data.dados.programa_trabalho);
            $('#v_elemento_despesa').val(data.dados.elemento_despesa);
        }
    });
}

function editarOrcamento(id)
{
    'use strict';
    var array_dados = {
        id: id,
        funcional_programatica: $('#v_funcional_programatica').val(),
        fonte_orcamentaria: $('#v_fonte_orcamentaria').val(),
        programa_trabalho: $('#v_programa_trabalho').val(),
        elemento_despesa: $('#v_elemento_despesa').val(),
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
            data: { metodo: 'deletarContratoOrcamento', id: id },
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

/**
* Exercício
**/
function montarTabelaExercicio(id_contrato, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoExercicios', id: id_contrato },
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
                linhas += '<td>'+ accounting.formatMoney(value.valor_previsto, '', 2, '.', ',') +'<input name="res_valor_previsto[]" type="hidden" value="'+ value.valor_previsto +'" /></td>';
                if (visualizar) {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesExercicio(' + value.id_contrato_exercicio + ', ' + true + ');" class="botoes_acao"><img src="public/images/sistema/visualizar.png" title="Visualizar" alt="Visualizar" height="25" width="25"></a></td>';
                } else {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesExercicio(' + value.id_contrato_exercicio + ', ' + false + ');" class="botoes_acao"><img src="public/images/sistema/editar.png" title="Editar" alt="Editar" height="25" width="25"></a>' +
                        '<a href="javascript:void(0)" onclick="excluirExercicio(' + value.id_contrato_exercicio + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
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
            $('#dados_exercicio').removeAttr('style', 'display: none;');
            $('#dados_exercicio').attr('style', 'display: block;');
            if (ocultar) {
                $("#formCadastro input").attr('disabled', 'disabled');
                $("#formCadastro select").attr('disabled', 'disabled');
                $("#formCadastro textarea").attr('disabled', 'disabled');
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
            $('#v_exercicio').val(data.dados.exercicio);
            $('#v_competencia_inicial').val(data.dados.competencia_inicial);
            $('#v_competencia_final').val(data.dados.competencia_final);
            $('#v_valor_previsto').val(accounting.formatMoney(data.dados.valor_previsto, '', 2, '.', ','));
        }
    });
}

function editarExercicio(id)
{
    'use strict';
    var array_dados = {
        id: id,
        exercicio: $('#v_exercicio').val(),
        competencia_inicial: $('#v_competencia_inicial').val(),
        competencia_final: $('#v_competencia_final').val(),
        valor_previsto: $('#v_valor_previsto').val()
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

/**
* Garantia
**/
function montarTabelaGarantia(id_contrato, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoGarantias', id: id_contrato },
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
                linhas += '<tr class="tr_remove_gar_vis">';
                linhas += '<td>'+ value.ds_modalidade +'<input name="res_id_modalidade[]" type="hidden" value="'+ value.id_modalidade +'" /></td>';
                linhas += '<td>'+ ds_garantia_concretizada +'<input name="res_garantia_concretizada[]" type="hidden" value="'+ value.garantia_concretizada +'" /></td>';
                linhas += '<td>'+ accounting.formatMoney(value.percentual, '', 2, '.', ',') +'<input name="res_percentual[]" type="hidden" value="'+ value.percentual +'" /></td>';
                linhas += '<td>'+ accounting.formatMoney(value.valor, '', 2, '.', ',') +'<input name="res_valor_garantia[]" type="hidden" value="'+ value.valor +'" /></td>';
                if (visualizar) {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesGarantia(' + value.id_contrato_garantia + ', ' + true + ');" class="botoes_acao"><img src="public/images/sistema/visualizar.png" title="Visualizar" alt="Visualizar" height="25" width="25"></a></td>';
                } else {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesGarantia(' + value.id_contrato_garantia + ', ' + false + ');" class="botoes_acao"><img src="public/images/sistema/editar.png" title="Editar" alt="Editar" height="25" width="25"></a>' +
                        '<a href="javascript:void(0)" onclick="excluirGarantia(' + value.id_contrato_garantia + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
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
        linhas += '<tr class="tr_remove_gar">';
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
            $('#dados_garantia').removeAttr('style', 'display: none;');
            $('#dados_garantia').attr('style', 'display: block;');
            if (ocultar) {
                $("#formCadastro input").attr('disabled', 'disabled');
                $("#formCadastro select").attr('disabled', 'disabled');
                $("#formCadastro textarea").attr('disabled', 'disabled');
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
            $('#v_id_modalidade').val(data.dados.id_modalidade).selected = 'true';
            $('#v_garantia_concretizada').val(data.dados.garantia_concretizada).selected = 'true';
            $('#v_percentual').val(accounting.formatMoney(data.dados.percentual, '', 2, '.', ','));
            $('#v_valor_garantia').val(accounting.formatMoney(data.dados.valor, '', 2, '.', ','));
        }
    });
}

function editarGarantia(id)
{
    'use strict';
    var array_dados = {
        id: id,
        id_modalidade: $('#v_id_modalidade').val(),
        garantia_concretizada: $('#v_garantia_concretizada').val(),
        percentual: $('#v_percentual').val(),
        valor_garantia: $('#v_valor_garantia').val()
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

/**
 * Controle dos Fiscais de Contrato
 **/
function atribuir(id)
{
    'use strict';
    $("#modalFiscalContrato").modal();
}

function confirmaCancelarFiscalContrato(seletor)
{
    'use strict';
    $("#"+seletor).modal('hide');

}

function salvarFiscalContrato()
{

}

function criarFiscal()
{
    'use strict';
    limparDadosFormFiscal();
    $('.hide_buttons').show();
    $('#bt_inserir_fiscal').text("Inserir");
    $('#bt_inserir_fiscal').removeAttr('onclick');
    $('#bt_inserir_fiscal').attr('onclick', 'inserirFiscal();');
    $('#dados_fiscal').removeAttr('style', 'display: none;');
    $('#dados_fiscal').attr('style', 'display: block;');
    $('#v_id_tipo_fiscal').focus();
}

function inserirFiscal()
{

}

function cancelarFiscal()
{

}

function verificarTiposExistentes()
{

}

function limparDadosFormFiscal()
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

/**
 * Controle das Movimentações de Contrato
 **/
function movimentar(id)
{
    'use strict';
    $("#modalMovimentoContrato").modal();
}

function confirmaCancelarMovimentoContrato(seletor)
{
    'use strict';
    $("#"+seletor).modal('hide');

}

function salvarMovimentoContrato()
{

}

//Sessão de Anexos
function criarAnexo(id_contrato)
{
    'use strict';
    $('#id_contrato').val(id_contrato);
    getIdentificador(id_contrato);
    montarTabelaAnexos(id_contrato, false);
    $('#tabela_lista_anexos').removeAttr('style', 'display: table;');
    $('#tabela_lista_anexos').attr('style', 'display: none;');
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
        data: {metodo: 'visualizarContratoNumero', id: id},
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

function montarTabelaAnexos(id_contrato, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoAnexos', id: id_contrato },
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
            if (data.dados != ''){
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

var tipos_anexos;
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

var contador = 0;
function inserirAnexo()
{
    'use strict';
    tipos_anexos = getTiposAnexo();
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
            data: {metodo: 'excluirContratoAnexo', id: id_anexo},
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
                console.log(data);
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