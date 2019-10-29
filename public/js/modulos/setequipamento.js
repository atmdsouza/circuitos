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
        order: [[2, "asc"],[0, "desc"]]//Ordenação passando a lista de ativos primeiro
    });
    autocompletarNumeroSeriePatrimonio();
    autocompletarFabricante();
    autocompletarModelo();
    autocompletarEquipamento();
    autocompletarFornecedor('i_lid_fornecedor','i_id_fornecedor');
    autocompletarContrato('i_lid_contrato','i_id_contrato');
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

function visualizar(id, ocultar)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'visualizarSetEquipamento', id: id},
        complete: function () {
            if (ocultar){
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
        data: {metodo: 'visualizarComponenteSetEquipamento', id: id},
        complete: function () {
            if (ocultar){
                $("#formCadastro input").attr('readonly', 'readonly');
                $("#formCadastro select").attr('readonly', 'readonly');
                $("#formCadastro textarea").attr('readonly', 'readonly');
                $('.hide_buttons').hide();
                $("#salvarCadastro").hide();
            } else {
                $('#bt_inserir_componente').text("Alterar");
                $('#bt_inserir_componente').removeAttr('onclick');
                $('#bt_inserir_componente').attr('onclick','editarComponente(' + id + ');');
                $('.hide_buttons').show();
            }
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
            $('#i_lnumero_serie').val(data.dados.numserie_patrimonio);
            $('#i_lid_fabricante').val(data.dados.ds_fabricante);
            $('#i_id_fabricante').val(data.dados.id_fabricante);
            $('#i_lid_modelo').val(data.dados.ds_modelo);
            $('#i_id_modelo').val(data.dados.id_modelo);
            $('#i_lid_equipamento').val(data.dados.ds_equipamento);
            $('#i_id_equipamento').val(data.dados.id_equipamento);
            $('#i_lid_fornecedor').val(data.dados.ds_fornecedor);
            $('#i_id_fornecedor').val(data.dados.id_fornecedor);
            $('#i_lid_contrato').val(data.dados.ds_contrato);
            $('#i_id_contrato').val(data.dados.id_contrato);
            $('#dados_componente').removeAttr('style', 'display: none;');
            $('#dados_componente').attr('style', 'display: block;');
        }
    });
}

function editarComponente(id)
{
    'use strict';
    var array_dados = {
        id: id,
        id_fornecedor: $('#i_id_fornecedor').val(),
        id_contrato: $('#i_id_contrato').val(),
        id_equipamento: $('#i_id_equipamento').val()
    };
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAcao");
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: action,
        data: {metodo: 'alterarComponenteEquipamento', array_dados: array_dados},
        complete: function () {
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
            $('.tr_res_remove').remove();
            limparDadosFormComponente();
            montarTabelaComponente(data.dados.id_set_equipamento, false);
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
            data: {metodo: 'deletarComponenteEquipamento', id: id},
            complete: function () {
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
        data: {metodo: 'visualizarComponentesSetEquipamento', id: id_set_seguranca},
        complete: function () {
            $('#tabela_componentes').removeAttr('style','display: none;');
            $('#tabela_componentes').attr('style', 'display: table;');
            if (visualizar){
                $('.hide_buttons').hide();
            }
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
            $('.tr_res_remove').remove();
            var linhas = null;
            $.each(data.dados, function (key, value) {
                linhas += '<tr class="tr_res_remove">';
                linhas += '<td style="display: none;"><input name="res_id_contrato[]" type="hidden" value="'+ value.id_contrato +'" /></td>';
                linhas += '<td>'+ value.ds_fornecedor +'<input name="res_id_fornecedor[]" type="hidden" value="'+ value.id_fornecedor +'" /></td>';
                linhas += '<td>'+ value.numserie_patrimonio +'</td>';
                linhas += '<td>'+ value.ds_fabricante +'</td>';
                linhas += '<td>'+ value.ds_modelo +'</td>';
                linhas += '<td>'+ value.ds_equipamento +'<input name="res_id_equipamento[]" type="hidden" value="'+ value.id_equipamento +'" /></td>';
                if (visualizar) {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesComponente(' + value.id_componente + ', '+ true +');" class="botoes_acao"><img src="public/images/sistema/visualizar.png" title="Visualizar" alt="Visualizar" height="25" width="25"></a></td>';
                } else {
                    linhas += '<td><a href="javascript:void(0)" onclick="exibirDetalhesComponente(' + value.id_componente + ', '+ false +');" class="botoes_acao"><img src="public/images/sistema/editar.png" title="Editar" alt="Editar" height="25" width="25"></a>' +
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
    $('#bt_inserir_componente').attr('onclick','inserirComponente();');
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
