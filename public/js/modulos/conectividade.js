//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Variáveis Globais
var mudou = false;
var RemoveTableRow;

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
    $("#salvarCadastro").removeClass("acao_editar").addClass("acao_criar");
    $("#salvarCadastro").show();
    $("#modalCadastro").modal();
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

function editar(id)
{
    'use strict';
    $("#formCadastro input").removeAttr('readonly', 'readonly');
    $("#formCadastro select").removeAttr('readonly', 'readonly');
    $("#formCadastro textarea").removeAttr('readonly', 'readonly');
    $("#salvarCadastro").removeClass("acao_criar").addClass("acao_editar");
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
                ids: id
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
                ids: id
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
                ids: id
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

function limpar()
{
    'use strict';
    $('#fieldPesquisa').val('');
    $('#formPesquisa').submit();
}

function autocompletarCidadeDigital()
{
    //Autocomplete de Conectividade
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
    //Autocomplete de Conectividade
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

//Limpar Linhas da Tabela
(function ($) {
    'use strict';
    RemoveTableRow = function (handler) {
        var tr = $(handler).closest("tr");
        tr.fadeOut(400, function () {
            tr.remove();
        });
        return false;
    };
})(jQuery);

$(document).on("click", ".acao_criar", function(){
    'use strict';
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
            var action = actionCorreta(window.location.href.toString(), "conectividade/criar");
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
});

$(".bt_edit").on("click", function(){
    var id_conectividade = ids[0];
    var action = actionCorreta(window.location.href.toString(), "conectividade/formCadastro");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_conectividade: id_conectividade},
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
            $("#formCadastro input").removeAttr('readonly', 'readonly');
            $("#formCadastro select").removeAttr('readonly', 'readonly');
            $("#formCadastro textarea").removeAttr('readonly', 'readonly');
            $(".tr_remove").remove();
            $("#tb_conectividade").hide();
            $("#id").val(data.dados.id);
            $("#id_cidade").val(data.dados.id_cidade).selected = "true";
            $("#descricao").val(data.dados.descricao);
            if (data.conectividades) {
                $.each(data.conectividades, function (key, value) {
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trcn" + value.Conectividade.id + "'>";
                    linhas += "<td>"+ value.descricao +"<input name='res_tipo_conectividade[]' type='hidden' value='"+ value.Conectividade.id_tipo +"' /></td>";
                    linhas += "<td>"+ value.Conectividade.descricao +"<input name='res_conectividade[]' type='hidden' value='"+ value.Conectividade.descricao +"' /></td>";
                    linhas += "<td>"+ value.Conectividade.endereco +"<input name='res_endereco[]' type='hidden' value='"+ value.Conectividade.endereco +"' /></td>";
                    linhas += "<td><a href='#' id='" + value.Conectividade.id + "' class='del_conec'><i class='fi-circle-cross'></i></a></td>";
                    linhas += "</tr class='remove'>";
                    $("#tb_conectividade").append(linhas);
                    $("#tb_conectividade").show();
                });
            }
            $("#modalCadastro").modal();
        }
    });
    $("#salvarCidadeDigital").removeClass("criar_conectividade").addClass("editar_conectividade");
});

$(".bt_visual").on("click", function(){
    var id_conectividade = ids[0];
    var action = actionCorreta(window.location.href.toString(), "conectividade/formCadastro");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_conectividade: id_conectividade},
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
            $("#formCadastro input").attr('readonly', 'readonly');
            $("#formCadastro select").attr('readonly', 'readonly');
            $("#formCadastro textarea").attr('readonly', 'readonly');
            $(".tr_remove").remove();
            $("#id").val(data.dados.id);
            $("#id_cidade").val(data.dados.id_cidade).selected = "true";
            $("#descricao").val(data.dados.descricao);
            if (data.conectividades) {
                $.each(data.conectividades, function (key, value) {
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trcn" + value.Conectividade.id + "'>";
                    linhas += "<td>"+ value.descricao +"<input name='res_tipo_conectividade[]' type='hidden' value='"+ value.Conectividade.id_tipo +"' /></td>";
                    linhas += "<td>"+ value.Conectividade.descricao +"<input name='res_conectividade[]' type='hidden' value='"+ value.Conectividade.descricao +"' /></td>";
                    linhas += "<td>"+ value.Conectividade.endereco +"<input name='res_endereco[]' type='hidden' value='"+ value.Conectividade.endereco +"' /></td>";
                    linhas += "<td><i class='fi-circle-cross'></i></td>";
                    linhas += "</tr class='remove'>";
                    $("#tb_conectividade").append(linhas);
                    $("#tb_conectividade").show();
                });
            }
            $("#modalCadastro").modal();
        }
    });
    $("#salvarCidadeDigital").removeClass("criar_conectividade").addClass("editar_conectividade");
});

$(document).on("click", ".editar_conectividade", function(){
    //Validação de formulário
    $("#formCadastro").validate({
        rules : {
            id_cidade:{
                required: true
            },
            id_tipo:{
                required: true
            },
            descricao:{
                required: true
            }
        },
        messages:{
            id_cidade:{
                required:"É necessário informar uma cidade"
            },
            id_tipo:{
                required:"É necessário informar um tipo"
            },
            descricao:{
                required:"É necessário informar uma descrição"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "conectividade/editarCidadeDigital");
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
                            text: "Edição de cidade digital concluída!",
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
});

$(".bt_del").on("click", function(){
    var nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: "Tem certeza que deseja deletar múltipos registros?",
            text: "O sistema irá deletar um total de " + nm_rows + " registros com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "conectividade/editarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
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
                            title: "Deletados!",
                            text: "Os registros selecionados foram deletados com sucesso.",
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
                            title: "Deletar",
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
        });
    } else if (nm_rows == 0) {
        swal({
            title: "Deletar Valor",
            text: "Você precisa selecionar um valor ou mais registros para serem deletados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja deletar este registro?",
            text: "O sistema irá deletar o registro selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "conectividade/deletarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
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
                            title: "Deletado!",
                            text: "O registro selecionado foi deletado com sucesso.",
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
                            title: "Deletar",
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
        });
    }
});

$(".bt_ativo").on("click", function(){
    var nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: "Tem certeza que deseja ativar múltipos registros?",
            text: "O sistema irá ativar um total de " + nm_rows + " registros com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, ativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "conectividade/ativarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
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
                            title: "Ativados!",
                            text: "Os registros selecionados foram ativados com sucesso.",
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
    } else if (nm_rows == 0) {
        swal({
            title: "Ativar Conectividade",
            text: "Você precisa selecionar um ou mais registros para serem ativados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja ativar este registro?",
            text: "O sistema irá ativar o registro selecionado com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, ativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "conectividade/ativarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
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
                            text: "O registro selecionado foi ativado com sucesso.",
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
});

$(".bt_inativo").on("click", function(){
    var nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: "Tem certeza que deseja inativar múltipos registros?",
            text: "O sistema irá inativar um total de " + nm_rows + " registros com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, inativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "conectividade/inativarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
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
                            title: "Inativadas!",
                            text: "Os registros selecionados foram inativadas com sucesso.",
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
    } else if (nm_rows == 0) {
        swal({
            title: "Inativar Conectividade",
            text: "Você precisa selecionar um ou mais registros para serem inativados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja inativar este registro?",
            text: "O sistema irá inativar o registro selecionado com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, inativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "conectividade/inativarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
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
                            text: "O registro selecionado foi inativado com sucesso.",
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
});

$("#tb_conectividade").on("click", ".del_conec", function(){
    var id = $(this).attr("id");
    swal({
        title: "Tem certeza que deseja deletar esta conectividade?",
        text: "O sistema irá deletar a conectividade selecionada com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, apagar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "conectividade/deletarConectividade");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {id: id},
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
                        title: "Deletado!",
                        text: "O contato selecionado foi deletado com sucesso.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        $("#trcn" + id).remove();
                    });
                } else {
                    swal({
                        title: "Deletar",
                        text: data.mensagem,
                        type: "error"
                    });
                }
            }
        });
    });
});