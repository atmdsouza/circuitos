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
        order: [
                [2, "asc"],
                [0, "desc"]
            ] //Ordenação passando a lista de ativos primeiro
    });
    autocompletarFornecedor('i_lid_fornecedor','i_id_fornecedor');
    autocompletarContrato('i_lid_contrato','i_id_contrato');
}

function verificarAlteracao()
{
    'use strict';
    $('form').on('change paste', 'input, select, textarea', function() {
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
    $('.tr_res_remove').remove();
    $('.tr_remove').remove();
    $('#tabela_componentes').removeAttr('style', 'display: table;');
    $('#tabela_componentes').attr('style', 'display: none;');
    $("#formCadastro input").removeAttr('readonly', 'readonly');
    $("#formCadastro select").removeAttr('readonly', 'readonly');
    $("#formCadastro textarea").removeAttr('readonly', 'readonly');
    $("#salvarCadastro").val('criar');
    $("#salvarCadastro").show();
    $('.hide_buttons').show();
    $("#modalCadastro").modal();
}

function salvar()
{
    'use strict';
    var acao = $('#salvarCadastro').val();
    $("#formCadastro").validate({
        rules: {
            descricao: {
                required: true
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "set_seguranca/" + acao);
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    dados: dados
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
                    if (data.operacao) {
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
        title: "Tem certeza que deseja ativar o registro \"" + descr + "\"?",
        text: "O sistema irá ativar o registro \"" + descr + "\" com essa ação.",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, ativar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "set_seguranca/ativar");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                dados: { id: id }
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
                if (data.operacao) {
                    swal({
                        title: "Ativado!",
                        text: "O registro \"" + descr + "\" foi ativado com sucesso.",
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
        title: "Tem certeza que deseja inativar o registro \"" + descr + "\"?",
        text: "O sistema irá inativar o registro \"" + descr + "\" com essa ação.",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, inativar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "set_seguranca/inativar");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                dados: { id: id }
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
                if (data.operacao) {
                    swal({
                        title: "Inativado!",
                        text: "O registro \"" + descr + "\" foi inativado com sucesso.",
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
        title: "Tem certeza que deseja excluir o registro \"" + descr + "\"?",
        text: "O sistema irá excluir o registro \"" + descr + "\" com essa ação. Essa é uma ação irreversível!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "set_seguranca/excluir");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                dados: { id: id }
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
                if (data.operacao) {
                    swal({
                        title: "Excluído!",
                        text: "O registro \"" + descr + "\" foi excluído com sucesso.",
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
        data: { metodo: 'visualizarSetSeguranca', id: id },
        complete: function() {
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
            $('#id').val(data.dados.id);
            $('#descricao').val(data.dados.descricao);
            montarTabelaComponente(data.dados.id, ocultar);
        }
    });
}

function exibirDetalhesComponente(id, ocultar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarComponenteSetSeguranca', id: id },
        complete: function() {
            if (ocultar) {
                $("#formCadastro input").attr('readonly', 'readonly');
                $("#formCadastro select").attr('readonly', 'readonly');
                $("#formCadastro textarea").attr('readonly', 'readonly');
                $('.hide_buttons').hide();
                $("#salvarCadastro").hide();
            } else {
                $('#bt_inserir_componente').text("Alterar");
                $('#bt_inserir_componente').removeAttr('onclick');
                $('#bt_inserir_componente').attr('onclick', 'editarComponente(' + id + ');');
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
            $('#i_propriedade_prodepa').val(data.dados.propriedade_prodepa);
            $('#i_lid_fornecedor').val(data.dados.desc_fornecedor);
            $('#i_id_fornecedor').val(data.dados.id_fornecedor);
            $('#i_lid_contrato').val(data.dados.desc_contrato);
            $('#i_id_contrato').val(data.dados.id_contrato);
            $('#i_id_tipo').val(data.dados.id_tipo).selected = "true";
            $('#i_endereco_chave').val(data.dados.endereco);
            $('#i_senha').val(data.dados.senha);
            $('#i_validade').val(data.dados.validade);
            $('#cont_id').val(data.dados.cont_id);
            $('#i_nome').val(data.dados.cont_nome);
            $('#i_email').val(data.dados.cont_email);
            $('#i_telefone').val(data.dados.cont_telefone);
            $('#dados_componente').removeAttr('style', 'display: none;');
            $('#dados_componente').attr('style', 'display: block;');
            if ($('#i_propriedade_prodepa').val() === '-1') {
                $('#i_lid_fornecedor').val('PRODEPA');
                $('#i_id_fornecedor').val(-1);
            }
        }
    });
}

function editarComponente(id)
{
    'use strict';
    var array_dados = {
        id: id,
        cont_id: $('#cont_id').val(),
        propriedade_prodepa: $('#i_propriedade_prodepa').val(),
        id_fornecedor: $('#i_id_fornecedor').val(),
        id_contrato: $('#i_id_contrato').val(),
        id_tipo: $('#i_id_tipo').val(),
        endereco_chave: $('#i_endereco_chave').val(),
        senha: $('#i_senha').val(),
        validade: $('#i_validade').val(),
        nome: $('#i_nome').val(),
        email: $('#i_email').val(),
        telefone: $('#i_telefone').val()
    };
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAcao");
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: action,
        data: { metodo: 'alterarComponenteSeguranca', array_dados: array_dados },
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
            $('.tr_res_remove').remove();
            limparDadosFormComponente();
            montarTabelaComponente(data.dados.id_set_seguranca, false);
            swal({
                title: "Alteração de Componente",
                text: 'Componente alterado com sucesso!',
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
            data: { metodo: 'deletarComponenteSeguranca', id: id },
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
                $('.tr_res_remove').remove();
                limparDadosFormComponente();
                montarTabelaComponente(data.dados, false);
                swal({
                    title: "Exclusão de Componente",
                    text: 'Componente excluído com sucesso!',
                    type: "success"
                });
            }
        });
    });
}

function montarTabelaComponente(id_set_seguranca, visualizar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: { metodo: 'visualizarComponentesSetSeguranca', id: id_set_seguranca },
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
            $('.tr_res_remove').remove();
            var linhas = null;
            $.each(data.dados, function(key, value) {
                var desc_propriedade_prodepa = (value.propriedade_prodepa == '1') ? 'Sim' : 'Não';
                linhas += '<tr class="tr_res_remove">';
                linhas += '<td style="display: none;">' + value.desc_contrato + '<input name="res_id_contrato[]" type="hidden" value="' + value.id_contrato + '" /></td>';
                linhas += '<td style="display: none;">' + value.endereco + '<input name="res_endereco_chave[]" type="hidden" value="' + value.endereco + '" /></td>';
                linhas += '<td style="display: none;">' + value.senha + '<input name="res_senha[]" type="hidden" value="' + value.senha + '" /></td>';
                linhas += '<td style="display: none;">' + value.validade + '<input name="res_validade[]" type="hidden" value="' + value.validade + '" /></td>';
                linhas += '<td style="display: none;">' + value.cont_email + '<input name="res_email[]" type="hidden" value="' + value.cont_email + '" /></td>';
                linhas += '<td>' + value.desc_fornecedor + '<input name="res_id_fornecedor[]" type="hidden" value="' + value.id_fornecedor + '" /></td>';
                linhas += '<td>' + value.desc_tipo + '<input name="res_id_tipo[]" type="hidden" value="' + value.id_tipo + '" /></td>';
                linhas += '<td>' + desc_propriedade_prodepa + '<input name="res_propriedade_prodepa[]" type="hidden" value="' + value.propriedade_prodepa + '" /></td>';
                linhas += '<td>' + value.cont_nome + '<input name="res_nome[]" type="hidden" value="' + value.cont_nome + '" /></td>';
                linhas += '<td>' + value.cont_telefone + '<input name="res_telefone[]" type="hidden" value="' + value.cont_telefone + '" /></td>';
                if (visualizar) {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesComponente(' + value.id_componente + ', ' + true + ');" class="botoes_acao"><img src="public/images/sistema/visualizar.png" title="Visualizar" alt="Visualizar" height="25" width="25"></a></td>';
                } else {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesComponente(' + value.id_componente + ', ' + false + ');" class="botoes_acao"><img src="public/images/sistema/editar.png" title="Editar" alt="Editar" height="25" width="25"></a>' +
                        '<a href="javascript:void(0)" onclick="excluirComponente(' + value.id_componente + ');" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
                }
                linhas += '</tr>';
            });
            $("#tabela_componentes").append(linhas);
        }
    });
}

function limpar()
{
    'use strict';
    $('#fieldPesquisa').val('');
    $('#formPesquisa').submit();
}

function criarComponente()
{
    'use strict';
    limparDadosFormComponente();
    $('#bt_inserir_componente').text("Inserir");
    $('#bt_inserir_componente').removeAttr('onclick');
    $('#bt_inserir_componente').attr('onclick', 'inserirComponente();');
    $('#dados_componente').removeAttr('style', 'display: none;');
    $('#dados_componente').attr('style', 'display: block;');
    if ($('#i_propriedade_prodepa').val() === '-1') {
        $('#i_lid_fornecedor').val('PRODEPA');
        $('#i_id_fornecedor').val(-1);
    }
    $('#i_lid_fornecedor').focus();
}

function habilitarFornecedor()
{
    'use strict';
    var propriedade_prodepa = $('#i_propriedade_prodepa').val();
    if (propriedade_prodepa !== '-1') {
        $('#i_lid_fornecedor').removeAttr('disabled');
        $('#i_lid_fornecedor').val('');
        $('#i_id_fornecedor').val('');
    } else {
        $('#i_lid_fornecedor').attr('disabled', 'true');
        $('#i_lid_fornecedor').val('PRODEPA');
        $('#i_id_fornecedor').val(-1);
    }
}

function inserirComponente()
{
    'use strict';
    //Dados
    var lid_fornecedor = $('#i_lid_fornecedor').val();
    var id_fornecedor = $('#i_id_fornecedor').val();
    var lid_contrato = $('#i_lid_contrato').val();
    var id_contrato = $('#i_id_contrato').val();
    var endereco = $('#i_endereco_chave').val();
    var desc_id_tipo = document.getElementById("i_id_tipo").options[document.getElementById("i_id_tipo").selectedIndex].text;
    var id_tipo = $('#i_id_tipo').val();
    var propriedade_prodepa = $('#i_propriedade_prodepa').val();
    var desc_propriedade_prodepa = (propriedade_prodepa === '-1') ? 'Sim' : 'Não';
    var senha = $('#i_senha').val();
    var validade = $('#i_validade').val();
    var nome = $('#i_nome').val();
    var email = $('#i_email').val();
    var telefone = $('#i_telefone').val();
    if (id_tipo !== '' && lid_fornecedor !== '') { //Campos Obrigatórios
        var linhas = null;
        linhas += '<tr class="tr_remove">';
        linhas += '<td style="display: none;">' + lid_contrato + '<input name="id_contrato[]" type="hidden" value="' + id_contrato + '" /></td>';
        linhas += '<td style="display: none;">' + endereco + '<input name="endereco_chave[]" type="hidden" value="' + endereco + '" /></td>';
        linhas += '<td style="display: none;">' + senha + '<input name="senha[]" type="hidden" value="' + senha + '" /></td>';
        linhas += '<td style="display: none;">' + validade + '<input name="validade[]" type="hidden" value="' + validade + '" /></td>';
        linhas += '<td style="display: none;">' + email + '<input name="email[]" type="hidden" value="' + email + '" /></td>';
        linhas += '<td>' + lid_fornecedor + '<input name="id_fornecedor[]" type="hidden" value="' + id_fornecedor + '" /></td>';
        linhas += '<td>' + desc_id_tipo + '<input name="id_tipo[]" type="hidden" value="' + id_tipo + '" /></td>';
        linhas += '<td>' + desc_propriedade_prodepa + '<input name="propriedade_prodepa[]" type="hidden" value="' + propriedade_prodepa + '" /></td>';
        linhas += '<td>' + nome + '<input name="nome[]" type="hidden" value="' + nome + '" /></td>';
        linhas += '<td>' + telefone + '<input name="telefone[]" type="hidden" value="' + telefone + '" /></td>';
        linhas += '<td><a href="javascript:void(0)" onclick="RemoveTableRow(this)" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
        linhas += '</tr>';
        $("#tabela_componentes").append(linhas);
        $('#tabela_componentes').removeAttr('style', 'display: none;');
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

function cancelarComponente()
{
    'use strict';
    verificarAlteracao();
    limparDadosFormComponente();
}

function limparDadosFormComponente()
{
    'use strict';
    $('#i_id_contrato').val('');
    $('#i_lid_contrato').val('');
    $('#i_id_fornecedor').val('');
    $('#i_lid_fornecedor').val('');
    $('#i_senha').val('');
    $('#i_validade').val('');
    $('#i_endereco_chave').val('');
    $('#i_nome').val('');
    $('#i_telefone').val('');
    $('#i_email').val('');
    $('#i_id_tipo').val(null).selected = 'true';
    $('#i_propriedade_prodepa').val('-1').selected = 'true';
    $('#dados_componente').removeAttr('style', 'display: block;');
    $('#dados_componente').attr('style', 'display: none;');
}