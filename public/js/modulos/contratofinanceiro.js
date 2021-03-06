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
        order: [[0, "desc"]]//Ordenação passando a lista de ativos primeiro
    });
    autocompletarContrato('lid_contrato','id_contrato');
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
            limparValidacao('#formCadastro');
            limparCompetencias();
        }).catch(swal.noop);
    }
    else
    {
        $("#"+modal).modal('hide');
        limparModalBootstrap(modal);
        limparValidacao('#formCadastro');
        limparCompetencias();
    }
}

function limparValidacao(id_html_form)
{
    'use strict';
    var validator = $(id_html_form).validate();
    validator.resetForm();
}

function carregarContratoExercicio()
{
    'use strict';
    autocompletarContratoExercicio('lid_exercicio','id_exercicio','id_contrato');
    $('#lid_exercicio').val('');
    $('#id_exercicio').val('');
    $('#valor-previsto-exercicio').val('0,00');
    $('#valor-realizado-exercicio').val('0,00');
    $('#valor-disponivel-exercicio').val('0,00');
    limparCompetencias();
    $('#lid_exercicio').removeAttr('disabled', 'disabled');
    $('#lid_exercicio').focus();
}

function carregarValoresExercicio()
{
    'use strict';
    var id_exercicio = $('#id_exercicio').val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'carregarValoresExercicio', id_exercicio: id_exercicio},
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
            var valor_previsto = accounting.formatMoney(data.valor_previsto, "", 2, ".", ",");
            var valor_realizado = (data.valor_realizado) ? accounting.formatMoney(data.valor_realizado, "", 2, ".", ",") : '0,00';
            var subtracao = parseFloat(data.valor_previsto) - parseFloat(data.valor_realizado);
            var valor_disponivel = (subtracao) ? accounting.formatMoney(subtracao, "", 2, ".", ",") : '0,00';
            $('#valor-previsto-exercicio').val(valor_previsto);
            $('#valor-realizado-exercicio').val(valor_realizado);
            $('#valor-disponivel-exercicio').val(valor_disponivel);
        }
    });
}

function carregarCompetenciasExercicio(mes_competencia)
{
    'use strict';
    var id_exercicio = $('#id_exercicio').val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxSelect");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'carregarCompetenciasExercicio', id_exercicio: id_exercicio},
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
            if (data.dados.length > 0){
                $('.remove_competencias').remove();
                $.each(data.dados, function(key, value) {
                    var linhas = '<option class="remove_competencias" value="'+("00" + value).slice(-2)+'">'+ ("00" + value).slice(-2) +'</option>';
                    $('#mes_competencia').append(linhas);
                });
                $('#mes_competencia').prop('disabled', false);
                if (mes_competencia !== '' && mes_competencia !== null)
                {
                    $('#mes_competencia').selectpicker('val', mes_competencia);
                }
                $('#mes_competencia').selectpicker('refresh');
            }
        }
    });
    $('#mes_competencia').removeAttr('disabled', 'disabled');
    $('#mes_competencia').focus();
}

function limparCompetencias()
{
    'use strict';
    $('.remove_competencias').remove();
    $('#mes_competencia').prop('disabled', true);
    $('#mes_competencia').selectpicker('refresh');
}

function validarCompetenciaExercicio()
{
    'use strict';
    var id_exercicio = $('#id_exercicio').val();
    var mes_competencia = $('#mes_competencia').val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'validarCompetenciaExercicio', id_exercicio: id_exercicio, mes_competencia: mes_competencia},
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
            if (data.competenciaUtilizada){
                swal({
                    title: 'Verificação de Competência',
                    text: 'A competência selecionada já teve um pagamento cadastrado para esse Contrato/Exercício. Por favor, utilize outra competência para continuar o cadastro!',
                    type: "info",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok"
                }).then((result) => {
                    $('#mes_competencia').selectpicker('val', '');
                    $('#mes_competencia').selectpicker('render');
                    $('#salvarCadastro').attr('disabled', 'disabled');
                    $('#mes_competencia').focus();
                });
            } else {
                $('#salvarCadastro').removeAttr('disabled', 'disabled');
            }
        }
    });
}

function validarValorPagamentoPendente()
{
    'use strict';
    var valor_pendente = $('#valor-disponivel-exercicio').val();
    var valor_pagamento = $('#valor_pagamento').val();
    var saldo = accounting.unformat(valor_pendente) - accounting.unformat(valor_pagamento);
    if (saldo < 0) {
        swal({
            title: 'Valor do Pagamento Incorreto',
            text: 'Você informou um valor de pagamento acima do saldo restante para o exercício. O valor precisa ser menor ou igual ao valor pendente no exercício!',
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok"
        }).then((result) => {
            $('#valor_pagamento').val('');
            $('#valor_pagamento').focus();
        });
    }
}

function criar()
{
    'use strict';
    $("#formCadastro input").removeAttr('readonly', 'readonly');
    $("#formCadastro select").removeAttr('readonly', 'readonly');
    $("#formCadastro textarea").removeAttr('readonly', 'readonly');
    exibirTabela('tabela_lista_pagamentosv');
    ocultarTabela('tabela_lista_pagamentosv','tr_remove_notasv');
    $('#div-valor-pagov').hide();
    $('#div-status-pagamento').hide();
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
            lid_contrato:{
                required: true
            },
            lid_exercicio:{
                required: true
            },
            mes_competencia:{
                required: true
            },
            valor_pagamento:{
                required: true,
                maiorQueZero: true
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "contrato_financeiro/" + acao);
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
        var action = actionCorreta(window.location.href.toString(), "contrato_financeiro/ativar");
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
        var action = actionCorreta(window.location.href.toString(), "contrato_financeiro/inativar");
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
        var action = actionCorreta(window.location.href.toString(), "contrato_financeiro/excluir");
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
        data: {metodo: 'visualizarContratoFinanceiro', id: id},
        complete: function () {
            if (ocultar) {
                $("#formCadastro input").attr('readonly', 'readonly');
                $("#formCadastro select").attr('readonly', 'readonly');
                $("#formCadastro textarea").attr('readonly', 'readonly');
                $('#div-valor-pagov').show();
                $('#div-status-pagamento').show();
                $("#salvarCadastro").hide();
            } else {
                $("#formCadastro input").removeAttr('readonly', 'readonly');
                $("#formCadastro select").removeAttr('readonly', 'readonly');
                $("#formCadastro textarea").removeAttr('readonly', 'readonly');
                $("#lid_exercicio").removeAttr('disabled', 'disabled');
                $('#div-valor-pagov').hide();
                $('#div-status-pagamento').hide();
                $("#salvarCadastro").val('editar');
                $("#salvarCadastro").show();
                $('.hide_buttons').show();
            }
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
            $('#id').val(data.dados_objeto.id);
            $('#lid_contrato').val(data.dados_descricoes.ds_contrato);
            $('#id_contrato').val(data.dados_descricoes.id_contrato);
            $('#lid_exercicio').val(data.dados_descricoes.ds_exercicio);
            $('#id_exercicio').val(data.dados_objeto.id_exercicio);
            carregarValoresExercicio();
            carregarCompetenciasExercicio(data.dados_objeto.mes_competencia);
            $('#valor_pagamento').val(data.dados_descricoes.valor_pagamento_formatado);
            $('#valor-pagov').val(data.dados_descricoes.valor_pagamento_realizado_formatado);
            $('#status-pagamento').val(data.dados_descricoes.ds_status_pagamento);
            montarTabelaPagamentos(data.dados_objeto.id, ocultar, 'tabela_lista_pagamentosv', 'notasv');
        }
    });
}

function ocultarTabela(id_html_tabela, class_tr)
{
    'use strict';
    $('.'+class_tr).remove();
    $('#'+id_html_tabela).removeAttr('style', 'display: table;');
    $('#'+id_html_tabela).attr('style', 'display: none;');
}

function exibirTabela(id_html_tabela)
{
    'use strict';
    $('#'+id_html_tabela).removeAttr('style', 'display: none;');
    $('#'+id_html_tabela).attr('style', 'display: table;');
}

function montarTabelaPagamentos(id_contrato_financeiro, visualizar, id_html_tabela, remove)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoFinanceiroNotas', id: id_contrato_financeiro },
        complete: function() {
            exibirTabela(id_html_tabela);
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
            $('.tr_remove_'+remove).remove();
            var linhas=null;
            if (!isEmpty(data.dados_objeto)){
                $.each(data.dados_objeto, function(key, value) {
                    linhas += '<tr class="tr_remove_'+remove+'">';
                    linhas += '<td>'+ value.id +'</td>';
                    linhas += '<td>'+ data.dados_descricoes[key].data_pagamento_formatada +'</td>';
                    linhas += '<td>'+ value.numero_nota_fiscal +'</td>';
                    linhas += '<td>'+ data.dados_descricoes[key].valor_pagamento_formatado +'</td>';
                    linhas += '<td>'+ value.observacao +'</td>';
                    if (visualizar) {
                        if (data.dados_descricoes[key].url_anexo) {
                            linhas += '<td><a href="'+ data.dados_descricoes[key].url_anexo_formatado +'" class="botoes_acao" download><img src="public/images/sistema/download.png" title="Baixar" alt="Baixar" height="25" width="25"></a></td>';
                        } else {
                            linhas += '<td>Sem Anexo</td>';
                        }
                    } else {
                        linhas += '<td>';
                        if (data.dados_descricoes[key].url_anexo) {
                            linhas += '<a href="'+ data.dados_descricoes[key].url_anexo_formatado +'" class="botoes_acao" download><img src="public/images/sistema/download.png" title="Baixar" alt="Baixar" height="25" width="25"></a>';
                        } else {
                            linhas += 'Sem Anexo';
                        }
                        linhas += '<a href="javascript:void(0)" onclick="excluirBaixarPagamento(' + value.id + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a>';
                        linhas += '</td>';
                    }
                    linhas += '</tr>';
                });
            } else {
                linhas += '<tr class="tr_remove_'+remove+'">';
                linhas += '<td colspan="6" style="text-align: center;">Não existem notas fiscais para serem exibidas! Favor Cadastrar!</td>';
                linhas += '</tr>';
            }
            $("#"+id_html_tabela).append(linhas);
        }
    });
}

function limpar()
{
    'use strict';
    $('#fieldPesquisa').val('');
    $('#formPesquisa').submit();
}

/**
 * Sessão de Anexos
 **/
function criarAnexo(id_contrato_financeiro, contrato, exercicio, competencia)
{
    'use strict';
    $('#id_contrato_financeiro').val(id_contrato_financeiro);
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'visualizarContratoFinanceiroNotas', id: id_contrato_financeiro},
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
            if (!isEmpty(data.dados_objeto)) {
                $('#id_contrato_financeiro').val(id_contrato_financeiro);
                $('#identificador-anexado').html(contrato +'/'+exercicio +'/'+competencia);
                $.each(data.dados_objeto, function(key, value) {
                    inserirAnexo(value.id, value.numero_nota_fiscal, value.id_anexo, tipos_anexos);
                });
                montarTabelaAnexos(data.dados_objeto, data.dados_descricoes);
                $('#modalAnexoArquivo').modal();
            } else {
                swal({
                    title: 'Anexo de Nota Fiscal',
                    text: 'O pagamento já teve todas os anexos de notas lançados ou não possui baixa de pagamentos!',
                    type: "info",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok"
                }).then((result) => {

                });
            }
        }
    });
    $('.selectpicker').prop('disabled', false);
    $('.selectpicker').selectpicker('refresh');
}

function montarTabelaAnexos(objeto, objeto_descricoes)
{
    'use strict';
    $('.tr_remove_anexo').remove();
    var linhas =null;
    if (!isEmpty(objeto)){
        $.each(objeto, function(key, value) {
            if (value.id_anexo) {
                linhas += '<tr class="tr_remove_anexo">';
                linhas += '<td>'+ objeto_descricoes[key].ds_tipo_anexo +'</td>';
                linhas += '<td>'+ objeto_descricoes[key].descricao +'</td>';
                linhas += '<td>'+ objeto_descricoes[key].data_criacao +'</td>';
                linhas += '<td><a href="'+ objeto_descricoes[key].url_anexo_formatado +'" class="botoes_acao nova-aba" download><img src="public/images/sistema/download.png" title="Baixar" alt="Baixar" height="25" width="25"></a>' +
                    '<a href="javascript:void(0)" onclick="excluirContratoFinanceiroNotaAnexo(' + value.id + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
                linhas += '</tr>';
            }
        });
    } else {
        linhas += "<tr class='tr_remove_anexo'>";
        linhas += "<td colspan='5' style='text-align: center;'>Não existem anexos para serem exibidos! Favor Cadastrar!</td>";
        linhas += "</tr>";
    }
    if (linhas) {
        $("#tabela_lista_anexos").append(linhas);
        exibirTabela('tabela_lista_anexos');
    } else {
        ocultarTabela('tabela_lista_anexos','tr_remove_anexo');
    }
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

function inserirAnexo(id_contrato_financeiro_nota, descricao, id_anexo, tipos_de_anexo)
{
    'use strict';
    if (id_anexo === '' || id_anexo === null){
        contador++;
        var elemento = '<div id="div-anexo-'+ contador +'" class="form-row anexos-complementares">';
        elemento += '<input type="hidden" id="id_contrato_financeiro_nota_'+ contador +'" name="id_contrato_financeiro_nota[]" value="'+id_contrato_financeiro_nota+'">';
        elemento += '<div class="form-group col-md-3">';
        elemento += '<label for="id_tipo_anexo-'+ contador +'">Tipo de Anexo <span class="required">*</span></label>';
        elemento += '<select id="id_tipo_anexo-'+ contador +'" name="id_tipo_anexo[]" class="form-control selectpicker" data-live-search="true" data-style="btn-light">';
        elemento += '<option value="">Selecione o Tipo de Anexo</option>';
        $.each(tipos_de_anexo, function(key, value) {
            elemento += '<option value="'+value.id+'">'+value.descricao+'</option>';
        });
        elemento += '</select>';
        elemento += '</div>';
        elemento += '<div class="form-group col-md-3 botoes_forms_alinhamento">';
        elemento += '<label for="descricao-'+ contador +'">Descrição </label>';
        elemento += '<input type="text" id="descricao-'+ contador +'" name="descricao[]" class="form-control" size="200" value="NF-e Nº '+descricao+'">';
        elemento += '</div>';
        elemento += '<div class="form-group col-md-6">';
        elemento += '<label for="anexo-'+ contador +'">Anexo <span class="required">*</span></label>';
        elemento += '<input type="file" id="anexo-'+ contador +'" name="anexo-'+ contador +'" class="filestyle">';
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
}

function excluirContratoFinanceiroNotaAnexo(id_contrato_financeiro_nota)
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
            data: {metodo: 'excluirContratoFinanceiroNotaAnexo', id: id_contrato_financeiro_nota},
            error: function (data) {
                if (data.status && data.status === 401) {
                    swal({
                        title: "Erro de Permissão",
                        text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                        type: "warning"
                    });
                }
            },
            complete: function (data) {
                window.location.reload(true);
            }
        });
    });
}

function limparModalAnexos()
{
    'use strict';
    contador = 0;
    $('.anexos-complementares').remove();
    $('#modalAnexoArquivo').find('form')[0].reset();
}

/**
 * Bloco de Controle de Baixa de Pagamento
 **/
function criarBaixaPagamento(id_contrato_financeiro)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'visualizarContratoFinanceiro', id: id_contrato_financeiro},
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
            if (!data.quitado) {
                $('#id_contrato_financeiro_da_nota').val(data.dados_objeto.id);
                $('#valor-pagamento').val(data.dados_descricoes.valor_pagamento_formatado);
                $('#valor-pago').val(data.dados_descricoes.valor_pagamento_realizado_formatado);
                $('#valor-pendente').val(data.dados_descricoes.valor_pagamento_pendente_formatado);
                $('#id-pagamento-baixa').html(data.dados_descricoes.ds_contrato +'/'+data.dados_descricoes.ds_exercicio +'/'+data.dados_objeto.mes_competencia);
                montarTabelaPagamentos(data.dados_objeto.id, true, 'tabela_lista_pagamentos', 'notas');
                $("#modalBaixarPagamento").modal();
            } else {
                swal({
                    title: 'Pagamento Finalizado',
                    text: 'O pagamento selecionado já encontra-se finalizado no sistema!',
                    type: "info",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok"
                }).then((result) => {

                });
            }
        }
    });
}

function confirmaCancelarBaixarPagamento(modal)
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
            limparValidacao('#formBaixarPagamento');
        }).catch(swal.noop);
    }
    else
    {
        $("#"+modal).modal('hide');
        limparModalBootstrap(modal);
        limparValidacao('#formBaixarPagamento');
    }
}

function validarValorNotaPendente()
{
    'use strict';
    var valor_pendente = $('#valor-pendente').val();
    var valor_nota = $('#valor_nota').val();
    var saldo = accounting.unformat(valor_pendente) - accounting.unformat(valor_nota);
    if (saldo < 0) {
        swal({
            title: 'Valor do Pagamento Incorreto',
            text: 'Você informou um valor de pagamento acima do saldo restante do pagamento. O valor precisa ser menor ou igual ao valor pendente de recebimento!',
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok"
        }).then((result) => {
            $('#valor_nota').val('');
            $('#valor_nota').focus();
        });
    }
}

function salvarBaixarPagamento()
{
    'use strict';
    $("#formBaixarPagamento").validate({
        rules : {
            data_pagamento:{
                required: true
            },
            numero_nota_fiscal:{
                required: true
            },
            valor_nota:{
                required: true
            }
        },
        submitHandler: function(form) {
            var dados = $("#formBaixarPagamento").serialize();
            var action = actionCorreta(window.location.href.toString(), "contrato_financeiro/baixar");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token_baixar").attr("name"),
                    tokenValue: $("#token_baixar").attr("value"),
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

function excluirBaixarPagamento(id)
{
    'use strict';
    swal({
        title: "Tem certeza que deseja excluir o pagamento?",
        text: "O sistema irá excluir o pagamento selecionado com essa ação.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "contrato_financeiro/baixarExcluir");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token_baixar").attr("name"),
                tokenValue: $("#token_baixar").attr("value"),
                dados: {id: id}
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
                if (data.operacao){
                    swal({
                        title: "Excluído!",
                        text: "O pagamento selecionado foi excluído com sucesso.",
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