//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Variáveis Globais
var mudou = false;

//Inicializar datatable
$("#tb_conectividade").DataTable({
    select: false,
    language: {
        select: false
    }
});

//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos)
function inicializar()
{
    'use strict';
    autocompletarCidadeDigital();
    autocompletarTipoCidadeDigital();
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
    $("#formCadastro input").removeAttr('readonly', 'readonly');
    $("#formCadastro select").removeAttr('readonly', 'readonly');
    $("#formCadastro textarea").removeAttr('readonly', 'readonly');
    $("#salvarCadastro").val('editar');
    $("#salvarCadastro").show();
    $("#modalCadastro").modal();

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
        var action = actionCorreta(window.location.href.toString(), "conectividade/ativar");
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
        var action = actionCorreta(window.location.href.toString(), "conectividade/inativar");
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
        var action = actionCorreta(window.location.href.toString(), "conectividade/excluir");
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
    $("#formCadastro input").attr('readonly', 'readonly');
    $("#formCadastro select").attr('readonly', 'readonly');
    $("#formCadastro textarea").attr('readonly', 'readonly');
    $("#salvarCadastro").hide();
    $("#modalCadastro").modal();

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
    var listCidadeDigital = [];
    var action = actionCorreta(window.location.href.toString(), "core/processarAjax");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'cidadesDigitaisAtivas'},
        beforeSend: function () {
            $("#id_cidade_digital").val("");
            $("#lid_cidade_digital").val("");
            listCidadeDigital = [];
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
            if (data.operacao) {
                $.each(data.dados, function (key, value) {
                    listCidadeDigital.push({value: value.descricao, data: value.id});
                });
            } else {
                $("#id_cidade_digital").val("");
                $("#lid_cidade_digital").val("");
            }
            //Autocomplete de Equipamento
            ac_cidadedigital.autocomplete({
                lookup: listCidadeDigital,
                noCache: true,
                minChars: 1,
                triggerSelectOnValidInput: false,
                showNoSuggestionNotice: true,
                noSuggestionNotice: "Não existem resultados para essa consulta!",
                onSelect: function (suggestion) {
                    $("#id_cidade_digital").val(suggestion.data);
                }
            });
        }
    });
}

function autocompletarTipoCidadeDigital()
{
    "use strict";
    var ac_tipo_cidade = $("#lid_tipo");
    var listTipoCidade = [];
    var action = actionCorreta(window.location.href.toString(), "core/processarAjax");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'tiposCidadesDigitaisAtivas'},
        beforeSend: function () {
            $("#id_tipo").val("");
            $("#lid_tipo").val("");
            listTipoCidade = [];
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
            if (data.operacao) {
                $.each(data.dados, function (key, value) {
                    listTipoCidade.push({value: value.descricao, data: value.id});
                });
            } else {
                $("#id_tipo").val("");
                $("#lid_tipo").val("");
            }
            //Autocomplete de Equipamento
            ac_tipo_cidade.autocomplete({
                lookup: listTipoCidade,
                noCache: true,
                minChars: 1,
                triggerSelectOnValidInput: false,
                showNoSuggestionNotice: true,
                noSuggestionNotice: "Não existem resultados para essa consulta!",
                onSelect: function (suggestion) {
                    $("#id_tipo").val(suggestion.data);
                }
            });
        }
    });
}

function salvar()
{
    'use strict';
    var acao = $('#salvarCadastro').val();
    //Validação de formulário
    $("#formCadastro").validate({
        rules : {
            lid_cidade_digital:{
                required: true
            },
            lid_tipo:{
                required: true
            },
            descricao:{
                required: true
            }
        },
        messages:{
            lid_cidade_digital:{
                required:"É necessário informar uma Cidade Digital"
            },
            lid_tipo:{
                required:"É necessário informar um Tipo de Conectividade"
            },
            descricao:{
                required:"É necessário informar uma Descrição"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "conectividade/" + acao);
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
                            title: "Cadastro de Conectividade",
                            text: "Cadastro da conectividade concluído!",
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
                            title: "Cadastro de Conectividade",
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
        }
    });
}