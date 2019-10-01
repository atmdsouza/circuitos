//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Variáveis Globais
var mudou = false;
var listFabricante = [];
var f = 0;
var listModelo = [];
var m = 0;
var listEquipamento = [];
var e = 0;

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
        order: [[2, "asc"],[0, "desc"]]//Ordenação passando a lista de ativos primeiro
    });
    autocompletarNumeroSeriePatrimonio();
    autocompletarFabricante();
    autocompletarModelo();
    autocompletarEquipamento();
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
            var action = actionCorreta(window.location.href.toString(), "set_equipamento/" + acao);
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
        var action = actionCorreta(window.location.href.toString(), "set_equipamento/ativar");
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
        var action = actionCorreta(window.location.href.toString(), "set_equipamento/inativar");
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
        var action = actionCorreta(window.location.href.toString(), "set_equipamento/excluir");
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

function autocompletarContrato()
{

}

function autocompletarFornecedor()
{

}

function changeEquipamento()
{
    "use strict";
    var numero_serie = $('#i_lnumero_serie').val();
    if (numero_serie === ''){
        $("#i_lid_fabricante").val(null);
        $("#i_lid_modelo").val(null);
        $("#i_lid_equipamento").val(null);
        $("#i_id_fabricante").val(null);
        $("#i_id_modelo").val(null);
        $("#i_id_equipamento").val(null);
        $("#i_lid_modelo").attr("disabled", "true");
        $("#i_lid_equipamento").attr("disabled", "true");
        swal("Atenção","Não existem equipamentos para esse número de série!","info");
    }
}

function autocompletarNumeroSeriePatrimonio()
{
    "use strict";
    var ac_serie_patrimonio = $("#i_lnumero_serie");
    var vl_serie_patrimonio = $("#i_numero_serie");
    var listSeriePatrimonio = [];
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'equipamentoSeriePatrimonioAtivos'},
        beforeSend: function () {
            vl_serie_patrimonio.val("");
            ac_serie_patrimonio.val("");
            listSeriePatrimonio = [];
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
                $.each(data.dados, function (key, value) {
                    listSeriePatrimonio.push({value: value, data: value});
                });
            } else {
                vl_serie_patrimonio.val("");
                ac_serie_patrimonio.val("");
            }
            //Autocomplete
            ac_serie_patrimonio.autocomplete({
                lookup: listSeriePatrimonio,
                onSelect: function (suggestion) {
                    vl_serie_patrimonio.val(suggestion.data);
                    var numero_serie = suggestion.data;
                    if (numero_serie !== ""){
                        var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {metodo: 'equipamentoNumeroSerie', numero_serie: numero_serie},
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
                                    $("#i_lid_fabricante").val(data.nome_fabricante);
                                    $("#i_lid_modelo").val(data.nome_modelo);
                                    $("#i_lid_equipamento").val(data.nome_equipamento + " (" + numero_serie + " / " + data.numero_patrimonio + ")");
                                    $("#i_id_fabricante").val(data.id_fabricante);
                                    $("#i_id_modelo").val(data.id_modelo);
                                    $("#i_id_equipamento").val(data.id_equipamento);
                                    $("#i_lid_modelo").removeAttr("disabled");
                                    $("#i_lid_equipamento").removeAttr("disabled");
                                }
                            }
                        });
                    }
                }
            });
        }
    });
}

function autocompletarFabricante()
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_fabricante = $("#i_lid_fabricante");
    var vl_fabricante = $("#i_id_fabricante");
    var ac_model = $("#i_lid_modelo");
    var vl_model = $("#i_id_modelo");
    var ac_equip = $("#i_lid_equipamento");
    var vl_equip = $("#i_id_equipamento");
    var string = ac_fabricante.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'fabricantesAtivos', string: string},
        beforeSend: function () {
            vl_model.val("");
            ac_model.val("");
            ac_model.attr("disabled", "true");
            ac_equip.val("");
            vl_equip.val("");
            ac_equip.attr("disabled", "true");
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
            if (data.operacao) {
                listFabricante = [];
                $.each(data.dados, function (key, value) {
                    listFabricante.push({value: value.nome, data: value.id});
                });
                if(f === 0) {
                    //Autocomplete
                    ac_fabricante.autocomplete({
                        lookup: listFabricante,
                        onSelect: function (suggestion) {
                            vl_fabricante.val(suggestion.data);
                            ac_model.removeAttr("disabled");
                            vl_equip.val("");
                            ac_equip.val("");
                            ac_equip.attr("disabled", "true");
                        }
                    });
                    f++;
                } else {
                    //Autocomplete
                    ac_fabricante.autocomplete().setOptions( {
                        lookup: listFabricante
                    });
                }
            } else {
                vl_fabricante.val("");
                ac_fabricante.val("");
            }
        }
    });
}

function autocompletarModelo()
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_fabricante = $("#i_lid_fabricante");
    var vl_fabricante = $("#i_id_fabricante");
    var ac_model = $("#i_lid_modelo");
    var vl_model = $("#i_id_modelo");
    var ac_equip = $("#i_lid_equipamento");
    var vl_equip = $("#i_id_equipamento");
    var string = ac_model.val();
    var id = vl_fabricante.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'modelosAtivos', id: id, string: string},
        beforeSend: function () {
            ac_equip.val("");
            vl_equip.val("");
            ac_equip.attr("disabled", "true");
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
            if (data.operacao) {
                listModelo = [];
                $.each(data.dados, function (key, value) {
                    listModelo.push({value: value.modelo, data: value.id});
                });
                if(m === 0) {
                    //Autocomplete
                    ac_model.autocomplete({
                        lookup: listModelo,
                        onSelect: function (suggestion) {
                            vl_model.val(suggestion.data);
                            ac_equip.removeAttr("disabled");
                        }
                    });
                    m++;
                } else {
                    //Autocomplete
                    ac_model.autocomplete().setOptions( {
                        lookup: listModelo
                    });
                }
            } else {
                vl_model.val("");
                ac_model.val("");
            }
        }
    });
}

function autocompletarEquipamento()
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_model = $("#i_lid_modelo");
    var vl_model = $("#i_id_modelo");
    var ac_equip = $("#i_lid_equipamento");
    var vl_equip = $("#i_id_equipamento");
    var id = vl_model.val();
    var string = ac_equip.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'equipamentosAtivos', string: string, id: id},
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
                listEquipamento = [];
                $.each(data.dados, function (key, value) {
                    listEquipamento.push({value: value.nome, data: value.id});
                });
                if(e === 0) {
                    //Autocomplete
                    ac_equip.autocomplete({
                        lookup: listEquipamento,
                        onSelect: function (suggestion) {
                            vl_equip.val(suggestion.data);
                        }
                    });
                    e++;
                } else {
                    //Autocomplete
                    ac_equip.autocomplete().setOptions( {
                        lookup: listEquipamento
                    });
                }
            } else {
                vl_equip.val("");
                ac_equip.val("");
            }
        }
    });
}

function criarComponente()
{
    'use strict';
    limparDadosFormComponente();
    $('#bt_inserir_componente').val('Inserir');
    $('#dados_componente').removeAttr('style','display: none;');
    $('#dados_componente').attr('style', 'display: block;');
    $('#i_lnumero_serie').focus();
}

function inserirComponente()
{
    'use strict';
    //Dados
    var id_contrato = $('#i_id_contrato').val();
    var id_fornecedor = $('#i_id_fornecedor').val();
    var lid_fornecedor = $('#i_lid_fornecedor').val();
    var lnumero_serie = $('#i_lnumero_serie').val();
    var lid_modelo = $('#i_lid_modelo').val();
    var lid_equipamento = $('#i_lid_equipamento').val();
    var id_equipamento = $('#i_id_equipamento').val();
    var lid_fabricante = $('#i_lid_fabricante').val();
    if(lid_fornecedor !== '' && lid_equipamento !== ''){//Campos Obrigatórios
        var linhas = null;
        linhas += '<tr class="tr_remove">';
        linhas += '<td style="display: none;"><input name="id_contrato[]" type="hidden" value="'+ id_contrato +'" /></td>';
        linhas += '<td>'+ lid_fornecedor +'<input name="id_fornecedor[]" type="hidden" value="'+ id_fornecedor +'" /></td>';
        linhas += '<td>'+ lnumero_serie +'</td>';
        linhas += '<td>'+ lid_fabricante +'</td>';
        linhas += '<td>'+ lid_modelo +'</td>';
        linhas += '<td>'+ lid_equipamento +'<input name="id_equipamento[]" type="hidden" value="'+ id_equipamento +'" /></td>';
        linhas += '<td><a href="javascript:void(0)" onclick="RemoveTableRow(this)" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
        linhas += '</tr>';
        $("#tabela_componentes").append(linhas);
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
    $('#i_lnumero_serie').val('');
    $('#i_numero_serie').val('');
    $('#i_lid_modelo').val('');
    $('#i_id_modelo').val('');
    $('#i_lid_equipamento').val('');
    $('#i_id_equipamento').val('');
    $('#i_lid_fabricante').val('');
    $('#i_id_fabricante').val('');
    $('#dados_componente').removeAttr('style', 'display: block;');
    $('#dados_componente').attr('style','display: none;');
}