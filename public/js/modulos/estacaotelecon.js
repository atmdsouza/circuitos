//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Variáveis Globais
var mudou = false;
var listCidadeDigital = [];
var c = 0;
var listTerrenos = [];
var t = 0;
var listTorres = [];
var r = 0;
var listSetEquipamentos = [];
var e = 0;
var listSetSegurancas = [];
var s = 0;

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
    autocompletarCidadeDigital();
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
        data: {metodo: 'visualizarConectividade', id: id},
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
            }
        },
        messages:{
            descricao:{
                required:"É necessário informar uma Descrição"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "estacao_telecon/" + acao);
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
        var action = actionCorreta(window.location.href.toString(), "estacao_telecon/ativar");
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
        var action = actionCorreta(window.location.href.toString(), "estacao_telecon/inativar");
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
        var action = actionCorreta(window.location.href.toString(), "estacao_telecon/excluir");
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
        data: {metodo: 'visualizarConectividade', id: id},
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

function autocompletarCidadeDigital()
{
    "use strict";
    var ac_cidadedigital = $("#lid_cidade_digital");
    var string = ac_cidadedigital.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'cidadesDigitaisAtivas', string: string},
        success: function (data) {
            if (data.operacao) {
                listCidadeDigital = [];
                $.each(data.dados, function (key, value) {
                    listCidadeDigital.push({value: value.descricao, data: value.id});
                });
                if(c === 0) {
                    //Autocomplete
                    ac_cidadedigital.autocomplete({
                        lookup: listCidadeDigital,
                        onSelect: function (suggestion) {
                            $("#id_cidade_digital").val(suggestion.data);
                        }
                    });
                    c++;
                } else {
                    //Autocomplete
                    ac_cidadedigital.autocomplete().setOptions( {
                        lookup: listCidadeDigital
                    });
                }
            } else {
                $("#id_cidade_digital").val("");
                $("#lid_cidade_digital").val("");
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
    var input_valor = $("#id_terreno");
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
    var input_valor = $("#id_torre");
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
    var input_valor = $("#id_set_equipamento");
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
    var input_valor = $("#id_set_seguranca");
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

}