//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Variáveis Globais
var mudou = false;
var listAgrupadora = [];
var f = 0;

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
        order: [[4, "asc"],[0, "desc"]]//Ordenação passando a lista de ativos primeiro
    });
    autocompletarAgrupadora();
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
    var action = actionCorreta(window.location.href.toString(), "core/processarAjax");
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
            },
            codigo_conta_contrato:{
                required: true
            }
        },
        messages:{
            descricao:{
                required:"É necessário informar uma Descrição"
            },
            codigo_conta_contrato:{
                required:"É necessário informar um Código de Conta Contrato"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "unidade_consumidora/" + acao);
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
        var action = actionCorreta(window.location.href.toString(), "unidade_consumidora/ativar");
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
        var action = actionCorreta(window.location.href.toString(), "unidade_consumidora/inativar");
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
        var action = actionCorreta(window.location.href.toString(), "unidade_consumidora/excluir");
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
    var action = actionCorreta(window.location.href.toString(), "core/processarAjax");
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

function criarComponente()
{
    'use strict';
    limparDadosFormComponente();
    $('#bt_inserir_componente').val('Inserir');
    $('#dados_componente').removeAttr('style','display: none;');
    $('#dados_componente').attr('style', 'display: block;');
    $('#i_lid_fornecedor').focus();
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
    var desc_propriedade_prodepa = (propriedade_prodepa === '1') ? 'Sim' : 'Não';
    var senha = $('#i_senha').val();
    var validade = $('#i_validade').val();
    var nome = $('#i_nome').val();
    var email = $('#i_email').val();
    var telefone = $('#i_telefone').val();
    if(id_tipo !== '' && lid_fornecedor !== ''){//Campos Obrigatórios
        var linhas = null;
        linhas += '<tr class="tr_remove">';
        linhas += '<td style="display: none;">' + lid_contrato + '<input name="id_contrato[]" type="hidden" value="' + id_contrato + '" /></td>';
        linhas += '<td style="display: none;">'+ endereco +'<input name="endereco_chave[]" type="hidden" value="'+ endereco +'" /></td>';
        linhas += '<td style="display: none;">'+ senha +'<input name="senha[]" type="hidden" value="'+ senha +'" /></td>';
        linhas += '<td style="display: none;">'+ validade +'<input name="validade[]" type="hidden" value="'+ validade +'" /></td>';
        linhas += '<td style="display: none;">'+ email +'<input name="email[]" type="hidden" value="'+ email +'" /></td>';
        linhas += '<td>'+ lid_fornecedor +'<input name="id_fornecedor[]" type="hidden" value="'+ id_fornecedor +'" /></td>';
        linhas += '<td>'+ desc_id_tipo +'<input name="id_tipo[]" type="hidden" value="'+ id_tipo +'" /></td>';
        linhas += '<td>'+ desc_propriedade_prodepa +'<input name="propriedade_prodepa[]" type="hidden" value="'+ propriedade_prodepa +'" /></td>';
        linhas += '<td>'+ nome +'<input name="nome[]" type="hidden" value="'+ nome +'" /></td>';
        linhas += '<td>'+ telefone +'<input name="telefone[]" type="hidden" value="'+ telefone +'" /></td>';
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
    $('#i_senha').val('');
    $('#i_validade').val('');
    $('#i_endereco_chave').val('');
    $('#i_nome').val('');
    $('#i_telefone').val('');
    $('#i_email').val('');
    $('#i_id_tipo').val(null).selected = 'true';
    $('#i_propriedade_prodepa').val('1').selected = 'true';
    $('#dados_componente').removeAttr('style', 'display: block;');
    $('#dados_componente').attr('style','display: none;');
}

function autocompletarAgrupadora()
{
    "use strict";
    //Autocomplete
    var ac_agrupadora = $("#lid_conta_agrupadora");
    var vl_agrupadora = $("#id_conta_agrupadora");
    var string = ac_agrupadora.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjax");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'contasAgrupadorasAtivas', string: string},
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
            console.log(data.dados);
            if (data.operacao) {
                listAgrupadora = [];
                $.each(data.dados, function (key, value) {
                    listAgrupadora.push({value: value.codigo_conta_contrato, data: value.id});
                });
                if(f === 0) {
                    //Autocomplete
                    ac_agrupadora.autocomplete({
                        lookup: listAgrupadora,
                        onSelect: function (suggestion) {
                            vl_agrupadora.val(suggestion.data);
                        }
                    });
                    f++;
                } else {
                    //Autocomplete
                    ac_agrupadora.autocomplete().setOptions( {
                        lookup: listAgrupadora
                    });
                }
            } else {
                vl_agrupadora.val("");
                ac_agrupadora.val("");
            }
        }
    });
}