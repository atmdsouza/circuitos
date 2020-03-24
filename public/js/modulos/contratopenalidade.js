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
        order: [[0, "desc"]]//Ordenação default
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
            mudou = false;
        }).catch(swal.noop);
    }
    limparModalBootstrap(modal);
    limparValidacao();
    $("#"+modal).modal('hide');
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
    $('#primeira-aba').trigger('click');
    $("#formCadastro input").removeAttr('readonly', 'readonly');
    $("#formCadastro select").removeAttr('readonly', 'readonly');
    $("#formCadastro textarea").removeAttr('readonly', 'readonly');
    $('#id_servico').selectpicker('val', null);
    $('.selectpicker').prop('disabled', false);
    $('.selectpicker').selectpicker('refresh');
    ocultarInputs();
    bloquearAbas();
    $("#salvarCadastro").val('criar');
    $("#salvarCadastro").show();
    $("#modalCadastro").modal();
}

function ocultarInputs()
{
    'use strict';
    $('.inputs_ocultos').hide();
}

function exibirInputs()
{
    'use strict';
    $('.inputs_ocultos').show();
}

function bloquearAbas()
{
    'use strict';
    $('#tab-movimento').addClass('disabled');
    $('#tab-anexo').addClass('disabled');
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
            id_servico:{
                required: true
            },
            numero_processo:{
                required: true
            },
            numero_notificacao:{
                required: true
            },
            numero_rt:{
                required: true
            },
            numero_oficio:{
                required: true
            },
            motivo_penalidade:{
                required: true
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "contrato_penalidade/" + acao);
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
        var action = actionCorreta(window.location.href.toString(), "contrato_penalidade/ativar");
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
        var action = actionCorreta(window.location.href.toString(), "contrato_penalidade/inativar");
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
        var action = actionCorreta(window.location.href.toString(), "contrato_penalidade/excluir");
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
        data: {metodo: 'visualizarContratoPenalidade', id: id},
        complete: function (data) {
            if (ocultar) {
                $("#formCadastro input").attr('readonly', 'readonly');
                $("#formCadastro select").attr('readonly', 'readonly');
                $("#formCadastro textarea").attr('readonly', 'readonly');
                $('.selectpicker').prop('disabled', true);
                exibirInputs();
                $("#salvarCadastro").hide();
            } else {
                $("#formCadastro input").removeAttr('readonly', 'readonly');
                $("#formCadastro select").removeAttr('readonly', 'readonly');
                $("#formCadastro textarea").removeAttr('readonly', 'readonly');
                $('.selectpicker').prop('disabled', false);
                exibirInputs();
                $("#salvarCadastro").val('editar');
                $("#salvarCadastro").show();
                $('.hide_buttons').show();
            }
            $('.selectpicker').selectpicker('refresh');
            $('#primeira-aba').trigger('click');
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
            $('#lid_contrato').val(data.descricoes.ds_contrato);
            $('#id_contrato').val(data.dados.id_contrato);
            $('#id_servico').selectpicker('val', data.dados.id_servico);
            $('#statusv').val(data.descricoes.ds_status);
            $('#data_criacaov').val(data.descricoes.data_criacao_formatada);
            $('#numero_processo').val(data.dados.numero_processo);
            $('#numero_notificacao').val(data.dados.numero_notificacao);
            $('#numero_rt').val(data.dados.numero_rt);
            $('#numero_oficio').val(data.dados.numero_oficio);
            $('#data_recebimento_oficio_notificacaov').val(data.descricoes.data_recebimento_oficio_notificacao_formatada);
            $('#data_prazo_respostav').val(data.descricoes.data_prazo_resposta_formatada);
            $('#data_apresentacao_defesav').val(data.descricoes.data_apresentacao_defesa_formatada);
            $('#motivo_penalidade').val(data.dados.motivo_penalidade);
            $('#numero_oficio_multa').val(data.dados.numero_oficio_multa);
            $('#valor_multa').val(data.descricoes.valor_multa_formatado);
            $('#data_recebimento_oficio_multav').val(data.descricoes.data_recebimento_oficio_multa_formatada);
            $('#parecerv').val(data.dados.parecer);
            $('#observacao').val(data.dados.observacao);
            montarTabelaMovimentacao(data.movimentos);
            montarTabelaAnexosv(data.anexos);
        }
    });
}

function montarTabelaAnexosv(array_anexo)
{
    'use strict';
    $('.tr_remove_anexov').remove();
    var linhas =null;
    if (array_anexo.length > 0){
        $.each(array_anexo, function(key, value) {
            linhas += '<tr class="tr_remove_anexov">';
            linhas += '<td>'+ value.ds_tipo_anexo +'</td>';
            linhas += '<td>'+ value.descricao +'</td>';
            linhas += '<td>'+ value.data_criacao +'</td>';
            linhas += '<td><a href="'+ value.url +'" class="botoes_acao" download><img src="public/images/sistema/download.png" title="Baixar" alt="Baixar" height="25" width="25"></a></td>';
            linhas += '</tr>';
        });
    } else {
        linhas += "<tr class='tr_remove_anexov'>";
        linhas += "<td colspan='4' style='text-align: center;'>Não existem anexos para serem exibidos!</td>";
        linhas += "</tr>";
    }
    $("#tabela_lista_anexosv").append(linhas);
    $('#tab-anexo').removeClass('disabled');
}

function montarTabelaMovimentacao(array_movimento)
{
    'use strict';
    $('.tr_remove_movimento').remove();
    var linhas =null;
    if (array_movimento.length > 0){
        $.each(array_movimento, function(key, value) {
            var valor_atual = (value.valor_atual !== null) ? value.valor_atual : '';
            var valor_anterior = (value.valor_anterior !== null) ? value.valor_anterior : '';
            var observacao = (value.observacao !== null) ? value.observacao : '';
            linhas += '<tr class="tr_remove_movimento">';
            linhas += '<td>'+ value.data_movimento +'</td>';
            linhas += '<td>'+ value.tipo_movimento +'</td>';
            linhas += '<td>'+ value.usuario_nome +'</td>';
            linhas += '<td>'+ valor_atual +'</td>';
            linhas += '<td>'+ valor_anterior +'</td>';
            linhas += '<td>'+ observacao +'</td>';
            linhas += '</tr>';
        });
    } else {
        linhas += "<tr class='tr_remove_movimento'>";
        linhas += "<td colspan='6' style='text-align: center;'>Não existem movimentos para serem exibidos!</td>";
        linhas += "</tr>";
    }
    $("#tabela_lista_movimento").append(linhas);
    $('#tab-movimento').removeClass('disabled');
}

function limpar()
{
    'use strict';
    $('#fieldPesquisa').val('');
    $('#formPesquisa').submit();
}

/**
 * Sessão de Anexos
 * **/
function criarAnexo(id_contrato_penalidade)
{
    'use strict';
    $('#id_contrato_penalidade').val(id_contrato_penalidade);
    getIdentificador(id_contrato_penalidade);
    montarTabelaAnexos(id_contrato_penalidade, false);
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
        data: {metodo: 'visualizarContratoPenalidadeIdentificador', id: id},
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

function montarTabelaAnexos(id_contrato_penalidade, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoPenalidadeAnexos', id: id_contrato_penalidade },
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
            data: {metodo: 'excluirContratoPenalidadeAnexo', id: id_anexo},
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

/**
 * Sessão de Movimento
 * **/
function criarMovimento(id_penalidade)
{
    'use strict';
    ocultarBlocos();
    $('.selectpicker').prop('disabled', false);
    $('.selectpicker').selectpicker('refresh');
    $('#id_penalidade').val(id_penalidade);
    $('#modalMovimento').modal();

}

function ocultarBlocos()
{
    'use strict';
    $('.bloco_oculto').hide();
}

function exibirBloco()
{
    'use strict';
    var seq_tipo_movimento = $('#tipo_movimento').val();
    switch (seq_tipo_movimento) {
        case '4':
            ocultarBlocos();
            $('#bloco-receber-oficio-notificacao').show();
            $('#bloco-observacao').show();
            break;
        case '5':
            ocultarBlocos();
            $('#bloco-apresentacao-defesa').show();
            $('#bloco-observacao').show();
            break;
        case '6':
            ocultarBlocos();
            $('#bloco-receber-oficio-multa').show();
            $('#bloco-observacao').show();
            break;
        case '7':
            ocultarBlocos();
            $('#bloco-parecer').show();
            $('#bloco-observacao').show();
            break;
        case '8':
            ocultarBlocos();
            $('#bloco-observacao').show();
            swal({
                title: 'Executar a Penalidade',
                text: 'Essa ação executará a penalidade selecionada ao clicar no botão "Salvar"!',
                type: 'info'
            });
            break;
        case '9':
            ocultarBlocos();
            $('#bloco-observacao').show();
            swal({
                title: 'Cancelamento de Penalidade',
                text: 'Essa ação cancelará a penalidade selecionada ao clicar no botão "Salvar"!',
                type: 'info'
            });
            break;
        case '10':
            ocultarBlocos();
            $('#bloco-observacao').show();
            swal({
                title: 'Estorno de Status',
                text: '"Essa ação retornará esta penalidade para o status "Aberta" ao clicar no botão "Salvar"!"',
                type: 'info'
            });
            break;
        default:
            ocultarBlocos();
            break;
    }
}

function salvarMovimento()
{
    'use strict';
    $("#formMovimento").validate({
        rules : {
            tipo_movimento:{
                required: true
            }
        },
        submitHandler: function(form) {
            var dados = $("#formMovimento").serialize();
            var action = actionCorreta(window.location.href.toString(), "contrato_penalidade/movimentar");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token_movimento").attr("name"),
                    tokenValue: $("#token_movimento").attr("value"),
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