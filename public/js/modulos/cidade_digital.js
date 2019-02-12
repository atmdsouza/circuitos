var table = $("#tb_cidadedigital").DataTable({
    buttons: [
        {//Botão Novo Registro
            className: "bt_novo",
            text: "Novo",
            name: "novo", // do not change name
            titleAttr: "Novo registro",
            action: function (e, dt, node, config) {
            }

        },
        {//Botão Visualizar Registro
            className: "bt_visual",
            text: "Visualizar",
            name: "visualizar", // do not change name
            titleAttr: "Visualizar Circuito",
            action: function (e, dt, node, config) {
            },
            enabled: false
        },
        {//Botão Editar Registro
            className: "bt_edit",
            text: "Editar",
            name: "edit", // do not change name
            titleAttr: "Editar registro",
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
        {//Botão Ativar Registro
            className: "bt_ativo",
            text: "Ativar",
            name: "ativo", // do not change name
            titleAttr: "Ativar registro",
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
        {//Botão Inativar Registro
            className: "bt_inativo",
            text: "Inativar",
            name: "inativo", // do not change name
            titleAttr: "Inativar registro",
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
        {//Botão Deletar Registro
            className: "bt_del",
            text: "Deletar",
            name: "del", // do not change name
            titleAttr: "Deletar registro",
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
        // {//Botão Selecionar
        //     extend: "selectAll",
        //     text: "Selecionar",
        //     titleAttr: "Selecionar Todos os Registros"
        // },
        // {//Botão Limpar Seleção
        //     extend: "selectNone",
        //     text: "Limpar",
        //     titleAttr: "Limpar Seleção dos Registros"
        // },
        // {//Botão imprimir
        //     extend: "print",
        //     text: "Imprimir",
        //     titleAttr: "Imprimir"
        // },
        // {//Botão exportar excel
        //     extend: "excelHtml5",
        //     text: "XLSX",
        //     titleAttr: "Exportar para Excel"
        // },
        // {//Botão exportar pdf
        //     extend: "pdfHtml5",
        //     text: "PDF",
        //     titleAttr: "Exportar para PDF"
        // }
    ]
});

table.buttons().container().appendTo("#tb_cidadedigital_wrapper .col-md-6:eq(0)");

table.on( 'select deselect', function () {
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows === 1 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
    table.button( 5 ).enable( selectedRows > 0 );
});

//Limpar Linhas da Tabela
(function ($) {
    RemoveTableRow = function (handler) {
        var tr = $(handler).closest("tr");
        tr.fadeOut(400, function () {
            tr.remove();
        });
        return false;
    };
})(jQuery);

function limpaConectividade()
{
    $("#id_tipo_t").val(null).selected = "true";
    $("#descricao_t").val(null);
    $("#endereco_t").val(null);
    $("#descricao_t").focus();
}

$(".bt_novo").on("click", function(){
    $("#modalcidadedigital").modal();
    $("#salvarCidadeDigital").removeClass("editar_cidadedigital").addClass("criar_cidadedigital");
});

$("#id_cidade").on("change", function(){
    var cidade_desc = document.getElementById("id_cidade").options[document.getElementById("id_cidade").selectedIndex].text;
    $("#descricao").val(null);
    $("#descricao").val("CIDADE DIGITAL " + cidade_desc);
});

// var valCID = [];
$("#add_conectividade").on("click", function(){
    var id_tipo = $("#id_tipo_t").val();
    var id_tipo_desc = document.getElementById("id_tipo_t").options[document.getElementById("id_tipo_t").selectedIndex].text;
    var conectividade = $("#descricao_t").val();
    var endereco = $("#endereco_t").val();

    // if ($.inArray(conectividade, valCID) == -1) {
    //     valCID.push(conectividade);
        if (id_tipo && conectividade) {
            var linhas = null;
            linhas += "<tr class='tr_remove'>";
            linhas += "<td>"+ id_tipo_desc +"<input name='tipo_conectividade[]' type='hidden' value='"+ id_tipo +"' /></td>";
            linhas += "<td>"+ conectividade +"<input name='conectividade[]' type='hidden' value='"+ conectividade +"' /></td>";
            linhas += "<td>"+ endereco +"<input name='endereco[]' type='hidden' value='"+ endereco +"' /></td>";
            linhas += "<td><a href='#' onclick='RemoveTableRow(this)'><i class='fi-circle-cross'></i></a></td>";
            linhas += "</tr>";
            $("#tb_conectividade").append(linhas);
            $('#tb_conectividade').show();
            limpaConectividade();
        } else {
            swal({
                title: "Conectividade",
                text: "Você precisa preencher corretamente os campos obrigatórios!",
                type: "warning"
            });
        }
    // } else {
    //     swal({
    //         title: "Conectividade",
    //         text: "Essa conectividade já existe na tabela abaixo!",
    //         type: "warning"
    //     });
    // }
});

// $("#id_tipo").on("change", function() {
//     $("#descricao").val("");
//     var tipocidade_desc = document.getElementById("id_tipo").options[document.getElementById("id_tipo").selectedIndex].text;
//     var cidade = null;
//     if ($("#id_cidade").val() != "") {
//         cidade = document.getElementById("id_cidade").options[document.getElementById("id_cidade").selectedIndex].text;
//     } else {
//         cidade = "";
//     }
//     $("#descricao").val(tipocidade_desc + " " + cidade);
// });
//
// $("#id_cidade").on("change", function(){
//     $("#descricao").val("");
//     var tipocidade_desc = null;
//     var cidade = document.getElementById("id_cidade").options[document.getElementById("id_cidade").selectedIndex].text;
//     if ($("#id_cidade").val() != "") {
//         tipocidade_desc = document.getElementById("id_tipo").options[document.getElementById("id_tipo").selectedIndex].text;
//     } else {
//         tipocidade_desc = "";
//     }
//     $("#descricao").val(tipocidade_desc + " " + cidade);
// });

$(document).on("click", ".criar_cidadedigital", function(){
    //Validação de formulário
    $("#formCidadeDigital").validate({
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
            var dados = $("#formCidadeDigital").serialize();
            var action = actionCorreta(window.location.href.toString(), "cidade_digital/criarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    dados: dados
                },
                beforeSend: function () {
                },
                complete: function () {
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
                            title: "Cadastro de Cidade Digital",
                            text: "Cadastro do cidade digital concluído!",
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
                            title: "Cadastro de Cidade Digital",
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
        }
    });
});

//Coletando os ids das linhas selecionadas na tabela
var ids = [];
$("#tb_cidadedigital").on("click", "tr", function () {
    var valr = $(this)[0].cells[0].innerText;
    if (!ids.includes(valr)) {
        ids.push(valr);
    } else {
        var index = ids.indexOf(valr);
        ids.splice(index, 1);
    }
});

$(".bt_edit").on("click", function(){
    var id_cidadedigital = ids[0];
    var action = actionCorreta(window.location.href.toString(), "cidade_digital/formCidadeDigital");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_cidadedigital: id_cidadedigital},
        beforeSend: function () {
        },
        complete: function () {
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
            $("#formCidadeDigital input").removeAttr('readonly', 'readonly');
            $("#formCidadeDigital select").removeAttr('readonly', 'readonly');
            $("#formCidadeDigital textarea").removeAttr('readonly', 'readonly');
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
                    linhas += "<td><a href='#' id='" + value.Conectividade.id + "' class='del_conec'><i class='fi-circle-cross'></i></a></td>";
                    linhas += "</tr class='remove'>";
                    $("#tb_conectividade").append(linhas);
                    $("#tb_conectividade").show();
                });
            }
            $("#modalcidadedigital").modal();
        }
    });
    $("#salvarCidadeDigital").removeClass("criar_cidadedigital").addClass("editar_cidadedigital");
});

$(".bt_visual").on("click", function(){
    var id_cidadedigital = ids[0];
    var action = actionCorreta(window.location.href.toString(), "cidade_digital/formCidadeDigital");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_cidadedigital: id_cidadedigital},
        beforeSend: function () {
        },
        complete: function () {
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
            $("#formCidadeDigital input").attr('readonly', 'readonly');
            $("#formCidadeDigital select").attr('readonly', 'readonly');
            $("#formCidadeDigital textarea").attr('readonly', 'readonly');
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
            $("#modalcidadedigital").modal();
        }
    });
    $("#salvarCidadeDigital").removeClass("criar_cidadedigital").addClass("editar_cidadedigital");
});

$(document).on("click", ".editar_cidadedigital", function(){
    //Validação de formulário
    $("#formCidadeDigital").validate({
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
            var dados = $("#formCidadeDigital").serialize();
            var action = actionCorreta(window.location.href.toString(), "cidade_digital/editarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    dados: dados
                },
                beforeSend: function () {
                },
                complete: function () {
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
                            title: "Cadastro de Cidade Digital",
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
                            title: "Cadastro de Cidade Digital",
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
            var action = actionCorreta(window.location.href.toString(), "cidade_digital/editarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
                },
                beforeSend: function () {
                },
                complete: function () {
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
            var action = actionCorreta(window.location.href.toString(), "cidade_digital/deletarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
                },
                beforeSend: function () {
                },
                complete: function () {
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

//Coletando os ids das linhas selecionadas na tabela
var ids = [];
$("#tb_fabricantes").on("click", "tr", function () {
    var valr = $(this)[0].cells[0].innerText;
    if (!ids.includes(valr)) {
        ids.push(valr);
    } else {
        var index = ids.indexOf(valr);
        ids.splice(index, 1);
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
            var action = actionCorreta(window.location.href.toString(), "cidade_digital/ativarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
                },
                beforeSend: function () {
                },
                complete: function () {
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
            title: "Ativar Cidade Digital",
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
            var action = actionCorreta(window.location.href.toString(), "cidade_digital/ativarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
                },
                beforeSend: function () {
                },
                complete: function () {
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
            var action = actionCorreta(window.location.href.toString(), "cidade_digital/inativarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
                },
                beforeSend: function () {
                },
                complete: function () {
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
            title: "Inativar Cidade Digital",
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
            var action = actionCorreta(window.location.href.toString(), "cidade_digital/inativarCidadeDigital");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
                },
                beforeSend: function () {
                },
                complete: function () {
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
        var action = actionCorreta(window.location.href.toString(), "cidade_digital/deletarConectividade");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {id: id},
            beforeSend: function () {
            },
            complete: function () {
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