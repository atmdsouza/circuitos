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
            limparValidacao();
            limparCompetencias();
        }).catch(swal.noop);
    }
    else
    {
        $("#"+modal).modal('hide');
        limparModalBootstrap(modal);
        limparValidacao();
        limparCompetencias();
    }
}

function limparValidacao()
{
    'use strict';
    var validator = $("#formCadastro").validate();
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
            var valor_realizado = accounting.formatMoney(data.valor_realizado, "", 2, ".", ",");
            var valor_disponivel = accounting.formatMoney(parseFloat(data.valor_previsto) - parseFloat(data.valor_realizado), "", 2, ".", ",");
            $('#valor-previsto-exercicio').val(valor_previsto);
            $('#valor-realizado-exercicio').val(valor_realizado);
            $('#valor-disponivel-exercicio').val(valor_disponivel);
        }
    });
}

function carregarCompetenciasExercicio()
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
    $('#mes_competencia').attr('disabled', 'disabled');
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
                    $('#mes_competencia').val('').selected='true';
                    $('#salvarCadastro').attr('disabled', 'disabled');
                    $('#mes_competencia').focus();
                });
            } else {
                $('#salvarCadastro').removeAttr('disabled', 'disabled');
            }
        }
    });
}

function criar()
{
    'use strict';
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
            descricao:{
                required: true
            }
        },
        messages:{
            descricao:{
                required:"É necessário informar uma descrição"
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
                $("#salvarCadastro").hide();
            } else {
                $("#formCadastro input").removeAttr('readonly', 'readonly');
                $("#formCadastro select").removeAttr('readonly', 'readonly');
                $("#formCadastro textarea").removeAttr('readonly', 'readonly');
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
            $('#id').val(data.dados.id);
            $('#lid_departamento_pai').val(data.dados.ds_departamento_pai);
            $('#id_departamento_pai').val(data.dados.id_departamento_pai);
            $('#descricao').val(data.dados.descricao);
        }
    });
}

function montarTabelaAnexosv(id_contrato_financeiro, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoFinanceiroAnexos', id: id_contrato_financeiro },
        complete: function() {
            if (visualizar) {
                $('#tabela_lista_anexosv').removeAttr('style', 'display: none;');
                $('#tabela_lista_anexosv').attr('style', 'display: table;');
            } else {
                $('.tr_remove_anexov').remove();
                $('#tabela_lista_anexosv').removeAttr('style', 'display: table;');
                $('#tabela_lista_anexosv').attr('style', 'display: none;');
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
                    linhas += '<td><a href="'+ value.url +'" class="botoes_acao" download><img src="public/images/sistema/download.png" title="Baixar" alt="Baixar" height="25" width="25"></a></td>';
                    linhas += '</tr>';
                });
            } else {
                linhas += "<tr class='tr_remove_anexov'>";
                linhas += "<td colspan='5' style='text-align: center;'>Não existem anexos para serem exibidos! Favor Cadastrar!</td>";
                linhas += "</tr>";
            }
            $("#tabela_lista_anexosv").append(linhas);
        }
    });
}

function limpar()
{
    'use strict';
    $('#fieldPesquisa').val('');
    $('#formPesquisa').submit();
}

//Sessão de Anexos
function criarAnexo(id_contrato_financeiro)
{
    'use strict';
    $('#id_contrato_financeiro').val(id_contrato_financeiro);
    getIdentificador(id_contrato_financeiro);
    montarTabelaAnexos(id_contrato_financeiro, false);
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
        data: {metodo: 'visualizarContratoFinanceiroPagamento', id: id},
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

function montarTabelaAnexos(id_contrato_financeiro, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoFinanceiroAnexos', id: id_contrato_financeiro },
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
            data: {metodo: 'excluirContratoFinanceiroAnexo', id: id_anexo},
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