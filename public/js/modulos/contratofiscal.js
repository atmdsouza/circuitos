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
        order: [[5, "asc"],[0, "desc"]]//Ordenação passando a lista de ativos primeiro
    });
    autocompletarContrato('lid_contrato','id_contrato');
    autocompletarUsuario('lid_usuario', 'id_usuario');
    autocompletarUsuarioSuplente('lid_fiscal_suplente','id_fiscal_suplente');
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
    $('#tabela_lista_anexosv').removeAttr('style', 'display: table;');
    $('#tabela_lista_anexosv').attr('style', 'display: none;');
    $("#formCadastro input").removeAttr('readonly', 'readonly');
    $("#formCadastro select").removeAttr('readonly', 'readonly');
    $("#formCadastro textarea").removeAttr('readonly', 'readonly');
    $("#salvarCadastro").val('criar');
    habilitaSuplente();
    $("#salvarCadastro").show();
    $("#modalCadastro").modal();
}

function habilitaSuplente()
{
    'use strict';
    var tipo_fiscal = $('#tipo_fiscal').val();
    $('#tipo_fiscal').removeAttr('disabled');
    if (tipo_fiscal === '1'){
        $('#lid_fiscal_suplente').removeAttr('disabled');
    } else {
        $('#lid_fiscal_suplente').val('');
        $('#id_fiscal_suplente').val('');
        $('#lid_fiscal_suplente').attr('disabled', 'disabled');
    }
}

function validacaoFiscalTitular()
{
    'use strict';
    var id = $('#id_contrato').val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'validacaoFiscalTitular', id: id},
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
            if (data.temTitular){
                swal({
                    title: 'Verificação de Fiscal Titular',
                    text: 'Este contrato já possui um fiscal cadastrado como titular. Você só pode cadastrar fiscais suplentes agora ou então, alterar a titularidade.',
                    type: "info",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok"
                }).then((result) => {
                    $('#tipo_fiscal').val(0).selected='true';
                    $('#tipo_fiscal').attr('disabled', 'disabled');
                    $('#lid_fiscal_suplente').val('');
                    $('#id_fiscal_suplente').val('');
                    $('#lid_fiscal_suplente').attr('disabled', 'disabled');
                    // $('#lid_usuario').val(data.id_usuario);
                    // $('#id_usuario').val(data.nome_usuario);
                });
            } else {
                $('#tipo_fiscal').removeAttr('disabled');
                $('#lid_fiscal_suplente').removeAttr('disabled');
                // $('#lid_usuario').val('');
                // $('#id_usuario').val('');
            }
        }
    });

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
            lid_usuario:{
                required: true
            },
            tipo_fiscal:{
                required: true
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "contrato_fiscal/" + acao);
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
        var action = actionCorreta(window.location.href.toString(), "contrato_fiscal/ativar");
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
        var action = actionCorreta(window.location.href.toString(), "contrato_fiscal/inativar");
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
        var action = actionCorreta(window.location.href.toString(), "contrato_fiscal/excluir");
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
        data: {metodo: 'visualizarContratoFiscal', id: id},
        complete: function (data) {
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
                habilitaSuplente();
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
            $('#lid_contrato').val(data.descricoes.ds_contrato);
            $('#id_contrato').val(data.descricoes.id_contrato);
            $('#lid_usuario').val(data.descricoes.nome_fiscal);
            $('#id_usuario').val(data.dados.id_usuario);
            $('#lid_fiscal_suplente').val(data.descricoes.nome_fiscal_suplente);
            $('#id_fiscal_suplente').val(data.dados.id_fiscal_suplente);
            $('#tipo_fiscal').val(data.dados.tipo_fiscal).selected = "true";
            $('#data_nomeacao').val(data.descricoes.ds_data_nomeacao);
            $('#documento_nomeacao').val(data.dados.documento_nomeacao);
            montarTabelaAnexosv(data.dados.id, ocultar);
        }
    });
}

function montarTabelaAnexosv(id_contrato_fiscal, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoFiscalAnexos', id: id_contrato_fiscal },
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
function criarAnexo(id_contrato_fiscal)
{
    'use strict';
    $('#id_contrato_fiscal').val(id_contrato_fiscal);
    getIdentificador(id_contrato_fiscal);
    montarTabelaAnexos(id_contrato_fiscal, false);
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
        data: {metodo: 'visualizarContratoFiscalNome', id: id},
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

function montarTabelaAnexos(id_contrato_fiscal, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarContratoFiscalAnexos', id: id_contrato_fiscal },
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
            data: {metodo: 'excluirContratoFiscalAnexo', id: id_anexo},
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