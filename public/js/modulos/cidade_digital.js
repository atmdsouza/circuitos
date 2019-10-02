//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Variáveis Globais
var mudou = false;
var listCidade = [];
var f = 0;
var listTerrenos = [];
var t = 0;
var listTorres = [];
var r = 0;
var listSetEquipamentos = [];
var e = 0;
var listSetSegurancas = [];
var s = 0;
var listUnidadeConsumidora = [];
var u = 0;


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
        order: [[3, "asc"],[0, "desc"]]//Ordenação passando a lista de ativos primeiro
    });
    autocompletarCidade();
    autocompletarContrato();
    autocompletarSetEquipamento();
    autocompletarSetSeguranca();
    autocompletarTerreno();
    autocompletarTorre();
    autocompletarUnidadeConsumidora();
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
    $("#formCadastro input").removeAttr('readonly', 'readonly');
    $("#formCadastro select").removeAttr('readonly', 'readonly');
    $("#formCadastro textarea").removeAttr('readonly', 'readonly');
    $("#salvarCadastro").val('criar');
    $("#salvarCadastro").show();
    $("#modalCadastro").modal();
}

function editar(id)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'visualizarCidadeDigital', id: id},
        complete: function () {
            $("#formCadastro input").removeAttr('readonly', 'readonly');
            $("#formCadastro select").removeAttr('readonly', 'readonly');
            $("#formCadastro textarea").removeAttr('readonly', 'readonly');
            $("#salvarCadastro").val('editar');
            $("#salvarCadastro").show();
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
            $('#lid_cidade_digital').val(data.dados.desc_cidade_digital);
            $('#id_cidade_digital').val(data.dados.id_cidade_digital);
            $('#lid_tipo').val(data.dados.desc_tipo);
            $('#id_tipo').val(data.dados.id_tipo);
            $('#descricao').val(data.dados.descricao);
            $('#endereco').val(data.dados.endereco);
        }
    });
}

function salvar()
{
    'use strict';
    var acao = $('#salvarCadastro').val();
    $("#formCadastro").validate({
        rules : {
            descricao:{
                required: true
            },
            lid_cidade:{
                required: true
            }
        },
        messages:{
            descricao:{
                required:"É necessário informar uma Descrição"
            },
            lid_cidade:{
                required:"É necessário informar uma Cidade"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "cidade_digital/" + acao);
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
        var action = actionCorreta(window.location.href.toString(), "cidade_digital/ativar");
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
        var action = actionCorreta(window.location.href.toString(), "cidade_digital/inativar");
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
        var action = actionCorreta(window.location.href.toString(), "cidade_digital/excluir");
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

function visualizar(id)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'visualizarCidadeDigital', id: id},
        complete: function () {
            $("#formCadastro input").attr('readonly', 'readonly');
            $("#formCadastro select").attr('readonly', 'readonly');
            $("#formCadastro textarea").attr('readonly', 'readonly');
            $("#salvarCadastro").hide();
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
            $('#lid_cidade_digital').val(data.dados.desc_cidade_digital);
            $('#id_cidade_digital').val(data.dados.id_cidade_digital);
            $('#id_tipo').val(data.dados.id_tipo).selected = "true";
            $('#descricao').val(data.dados.descricao);
            $('#endereco').val(data.dados.endereco);
        }
    });
}

function limpar()
{
    'use strict';
    $('#fieldPesquisa').val('');
    $('#formPesquisa').submit();
}

/*
* Criação de Conectividades
* */

function criarComponenteConectividade()
{
    'use strict';
    limparDadosFormConectividade();
    $('#bt_inserir_conectividade').val('Inserir');
    $('.hidden_conectividade').removeAttr('style','display: none;');
    $('.hidden_conectividade').attr('style', 'display: block;');
    $('#i_id_tipo').focus();
}

function inserirComponenteConectividade()
{
    'use strict';
    //Dados
    var id_tipo = $("#i_id_tipo").val();
    var id_tipo_desc = document.getElementById("i_id_tipo").options[document.getElementById("i_id_tipo").selectedIndex].text;
    var conectividade = $("#i_descricao").val();
    var endereco = $("#i_endereco").val();

    if (id_tipo && conectividade) {
        var linhas = null;
        linhas += "<tr class='tr_remove'>";
        linhas += "<td>"+ id_tipo_desc +"<input name='tipo_conectividade[]' type='hidden' value='"+ id_tipo +"' /></td>";
        linhas += "<td>"+ conectividade +"<input name='conectividade[]' type='hidden' value='"+ conectividade +"' /></td>";
        linhas += "<td>"+ endereco +"<input name='endereco[]' type='hidden' value='"+ endereco +"' /></td>";
        linhas += "<td><a href='#' onclick='RemoveTableRow(this)'><i class='fi-circle-cross'></i></a></td>";
        linhas += "</tr>";
        $("#tabela_conectividade").append(linhas);
        $('#tabela_conectividade').removeAttr('style','display: none;');
        $('#tabela_conectividade').attr('style', 'display: table;');
        limparDadosFormConectividade();
    } else {
        swal({
            title: "Campos Obrigatórios!",
            text: "Você precisa preencher todos os campos obrigatórios no formulário!",
            type: "warning"
        });
    }
}

function cancelarComponenteConectividade()
{
    'use strict';
    verificarAlteracao();
    limparDadosFormConectividade();
    $('.hidden_conectividade').removeAttr('style', 'display: block;');
    $('.hidden_conectividade').attr('style','display: none;');
}

function limparDadosFormConectividade()
{
    'use strict';
    $('#i_endereco').val('');
    $('#i_descricao').val('');
    $('#i_id_tipo').val(null).selected = 'true';
    $('#i_id_tipo').focus();
}

/*
* Criação de Estações Telecon
* */

function criarComponenteETelecon()
{
    'use strict';
    limparDadosFormETelecon();
    $('#bt_inserir_estacaotelecon').val('Inserir');
    $('.hidden_estacaotelecon').removeAttr('style', 'display: none;');
    $('.hidden_estacaotelecon').attr('style','display: block;');
    $('#i_descricao').focus();
}

function inserirComponenteETelecon()
{
    'use strict';
    //Dados
    var descricao = $('#i_descricao_et').val();
    var id_contrato = $('#i_id_contrato').val();
    var contrato = $('#lid_contrato').val();
    var id_terreno = $('#i_id_terreno').val();
    var terreno = $('#lid_terreno').val();
    var id_torre = $('#i_id_torre').val();
    var torre = $('#lid_torre').val();
    var id_set_equipamento = $('#i_id_set_equipamento').val();
    var set_equipamento = $('#lid_set_equipamento').val();
    var id_set_seguranca = $('#i_id_set_seguranca').val();
    var set_seguranca = $('#lid_set_seguranca').val();
    var id_unidade_consumidora = $('#i_id_unidade_consumidora').val();
    var unidade_consumidora = $('#lid_unidade_consumidora').val();

    if (descricao && id_terreno && id_torre && id_set_equipamento && id_set_seguranca) {
        var linhas = null;
        linhas += "<tr class='tr_remove'>";
        linhas += "<td>"+ descricao +"<input name='estelecon[]' type='hidden' value='"+ descricao +"' /></td>";
        linhas += "<td style='display: none;'>"+ contrato +"<input name='id_contrato[]' type='hidden' value='"+ id_contrato +"' /></td>";
        linhas += "<td>"+ terreno +"<input name='id_terreno[]' type='hidden' value='"+ id_terreno +"' /></td>";
        linhas += "<td>"+ torre +"<input name='id_torre[]' type='hidden' value='"+ id_torre +"' /></td>";
        linhas += "<td>"+ set_equipamento +"<input name='id_set_equipamento[]' type='hidden' value='"+ id_set_equipamento +"' /></td>";
        linhas += "<td style='display: none;'>"+ set_seguranca +"<input name='id_set_seguranca[]' type='hidden' value='"+ id_set_seguranca +"' /></td>";
        linhas += "<td style='display: none;'>"+ unidade_consumidora +"<input name='id_unidade_consumidora[]' type='hidden' value='"+ id_unidade_consumidora +"' /></td>";
        linhas += "<td><a href='#' onclick='RemoveTableRow(this)'><i class='fi-circle-cross'></i></a></td>";
        linhas += "</tr>";
        $("#tabela_estacaotelecon").append(linhas);
        $('#tabela_estacaotelecon').removeAttr('style','display: none;');
        $('#tabela_estacaotelecon').attr('style', 'display: table;');
        limparDadosFormETelecon();
    } else {
        swal({
            title: "Campos Obrigatórios!",
            text: "Você precisa preencher todos os campos obrigatórios no formulário!",
            type: "warning"
        });
    }
}

function cancelarComponenteETelecon()
{
    'use strict';
    verificarAlteracao();
    limparDadosFormETelecon();
    $('.hidden_estacaotelecon').removeAttr('style', 'display: block;');
    $('.hidden_estacaotelecon').attr('style','display: none;');
}

function limparDadosFormETelecon()
{
    'use strict';
    $('#i_descricao_et').val('');
    $('#i_id_contrato').val('');
    $('#lid_contrato').val('');
    $('#i_id_terreno').val('');
    $('#lid_terreno').val('');
    $('#i_id_torre').val('');
    $('#lid_torre').val('');
    $('#i_id_set_equipamento').val('');
    $('#lid_set_equipamento').val('');
    $('#i_id_set_seguranca').val('');
    $('#lid_set_seguranca').val('');
    $('#i_id_unidade_consumidora').val('');
    $('#lid_unidade_consumidora').val('');
    $('#i_descricao_et').focus();
}

/*
* Sessão de Campos Autocomplete
* */

function autocompletarCidade()
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_cidade = $("#lid_cidade");
    var vl_cidade = $("#id_cidade");
    var id_estado = $("#uf_estado").val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'completarCidades', id_estado: id_estado},
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
            if (data.operacao) {
                listCidade = [];
                $.each(data.dados, function (key, value) {
                    listCidade.push({value: value.cidade, data: value.id});
                });
                if(f === 0) {
                    //Autocomplete
                    ac_cidade.autocomplete({
                        lookup: listCidade,
                        onSelect: function (suggestion) {
                            vl_cidade.val(suggestion.data);
                        }
                    });
                    f++;
                } else {
                    //Autocomplete
                    ac_cidade.autocomplete().setOptions( {
                        lookup: listCidade
                    });
                }
            } else {
                vl_cidade.val("");
                ac_cidade.val("");
            }
        }
    });
}

function autocompletarContrato()
{

}

function autocompletarTerreno()
{
    "use strict";
    var input_autocomplete = $("#lid_terreno");
    var input_valor = $("#i_id_terreno");
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'terrenosAtivos', string: string},
        success: function (data) {
            if (data.operacao) {
                listTerrenos = [];
                $.each(data.dados, function (key, value) {
                    listTerrenos.push({value: value.descricao, data: value.id});
                });
                if(t === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listTerrenos,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    t++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listTerrenos
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}

function autocompletarTorre()
{
    "use strict";
    var input_autocomplete = $("#lid_torre");
    var input_valor = $("#i_id_torre");
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'torresAtivas', string: string},
        success: function (data) {
            if (data.operacao) {
                listTorres = [];
                $.each(data.dados, function (key, value) {
                    listTorres.push({value: value.descricao, data: value.id});
                });
                if(r === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listTorres,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    r++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listTorres
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}

function autocompletarSetEquipamento()
{
    "use strict";
    var input_autocomplete = $("#lid_set_equipamento");
    var input_valor = $("#i_id_set_equipamento");
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'setsEquipamentosAtivos', string: string},
        success: function (data) {
            if (data.operacao) {
                listSetEquipamentos = [];
                $.each(data.dados, function (key, value) {
                    listSetEquipamentos.push({value: value.descricao, data: value.id});
                });
                if(e === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listSetEquipamentos,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    e++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listSetEquipamentos
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}

function autocompletarSetSeguranca()
{
    "use strict";
    var input_autocomplete = $("#lid_set_seguranca");
    var input_valor = $("#i_id_set_seguranca");
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'setsSegurancaAtivos', string: string},
        success: function (data) {
            if (data.operacao) {
                listSetSegurancas = [];
                $.each(data.dados, function (key, value) {
                    listSetSegurancas.push({value: value.descricao, data: value.id});
                });
                if(s === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listSetSegurancas,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    s++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listSetSegurancas
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}

function autocompletarUnidadeConsumidora()
{
    "use strict";
    var input_autocomplete = $("#lid_unidade_consumidora");
    var input_valor = $("#id_unidade_consumidora");
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'unidadeConsumidorasAtivas', string: string},
        success: function (data) {
            if (data.operacao) {
                listUnidadeConsumidora = [];
                $.each(data.dados, function (key, value) {
                    listUnidadeConsumidora.push({value: value.codigo_conta_contrato, data: value.id});
                });
                if(u === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listUnidadeConsumidora,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    u++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listUnidadeConsumidora
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}