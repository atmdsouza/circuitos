var table = $("#tb_modelo").DataTable({
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

table.buttons().container().appendTo("#tb_modelo_wrapper .col-md-6:eq(0)");

table.on( "select deselect", function () {
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows === 1 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
    table.button( 5 ).enable( selectedRows > 0 );
});

$(".bt_novo").on("click", function(){
    $("#modalmodelo").modal();
    $("#salvarModelo").removeClass("editar_modelo").addClass("criar_modelo");
});

$(document).on("click", ".criar_modelo", function(){
    //Validação de formulário
    $("#formModelo").validate({
        rules : {
            id_fabricante:{
                required: true
            },
            modelo:{
                required: true
            }
        },
        messages:{
            id_fabricante:{
                required:"É necessário informar um fabricante"
            },
            modelo:{
                required:"É necessário informar um modelo"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formModelo").serialize();
            var action = actionCorreta(window.location.href.toString(), "modelo/criarModelo");
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
                            title: "Cadastro de Modelos",
                            text: "Cadastro do modelo concluído!",
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
                            title: "Cadastro de Modelos",
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
$("#tb_modelo").on("click", "tr", function () {
    "use strict";
    var valr = $(this)[0].cells[0].innerText;
    if (valr !== "Código")
    {
        if (!ids.includes(valr)) {
            ids.push(valr);
        } else {
            var index = ids.indexOf(valr);
            ids.splice(index, 1);
        }
    }
});

$(".bt_edit").on("click", function(){
    var id_modelo = ids[0];
    var action = actionCorreta(window.location.href.toString(), "modelo/formModelo");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_modelo: id_modelo},
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
            $("#formModelo input").removeAttr('readonly', 'readonly');
            $("#formModelo select").removeAttr('readonly', 'readonly');
            $("#formModelo textarea").removeAttr('readonly', 'readonly');
            $("#id").val(data.dados.id);
            $("#id_fabricante").val(data.dados.id_fabricante).selected = "true";
            $("#modelo").val(data.dados.modelo);
            $("#descricao").val(data.dados.descricao);
            $("#modalmodelo").modal();
        }
    });
    $("#salvarModelo").removeClass("criar_modelo").addClass("editar_modelo");
});

$(".bt_visual").on("click", function(){
    var id_modelo = ids[0];
    var action = actionCorreta(window.location.href.toString(), "modelo/formModelo");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_modelo: id_modelo},
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
            $("#formModelo input").attr('readonly', 'readonly');
            $("#formModelo select").attr('readonly', 'readonly');
            $("#formModelo textarea").attr('readonly', 'readonly');
            $("#id").val(data.dados.id);
            $("#id_fabricante").val(data.dados.id_fabricante).selected = "true";
            $("#modelo").val(data.dados.modelo);
            $("#descricao").val(data.dados.descricao);
            $("#modalmodelo").modal();
        }
    });
    $("#salvarModelo").removeClass("criar_modelo").addClass("editar_modelo");
});

$(document).on("click", ".editar_modelo", function(){
    //Validação de formulário
    $("#formModelo").validate({
        rules : {
            id_fabricante:{
                required: true
            },
            modelo:{
                required: true
            }
        },
        messages:{
            id_fabricante:{
                required:"É necessário informar um fabricante"
            },
            modelo:{
                required:"É necessário informar um modelo"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formModelo").serialize();
            var action = actionCorreta(window.location.href.toString(), "modelo/editarModelo");
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
                            title: "Cadastro de Modelos",
                            text: "Edição de modelo concluída!",
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
                            title: "Cadastro de Modelos",
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
            title: "Tem certeza que deseja deletar múltipos modelos?",
            text: "O sistema irá deletar um total de " + nm_rows + " modelos com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "modelo/deletarModelo");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {ids: ids},
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
                            text: "Os modelos selecionados foram deletados com sucesso.",
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
            text: "Você precisa selecionar um valor ou mais modelos para serem deletados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja deletar este modelo?",
            text: "O sistema irá deletar o modelo selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "modelo/deletarModelo");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {ids: ids},
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
                            text: "O modelo selecionado foi deletado com sucesso.",
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
            title: "Tem certeza que deseja ativar múltipos modelos?",
            text: "O sistema irá ativar um total de " + nm_rows + " modelos com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, ativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "modelo/ativarModelo");
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
                            text: "Os modelos selecionados foram ativados com sucesso.",
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
            title: "Ativar Modelos",
            text: "Você precisa selecionar um ou mais modelos para serem ativados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja ativar este modelo?",
            text: "O sistema irá ativar o modelo selecionado com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, ativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "modelo/ativarModelo");
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
                            text: "O modelo selecionado foi ativado com sucesso.",
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
            title: "Tem certeza que deseja inativar múltipos modelos?",
            text: "O sistema irá inativar um total de " + nm_rows + " modelos com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, inativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "modelo/inativarModelo");
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
                            text: "As modelos selecionados foram inativadas com sucesso.",
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
            title: "Inativar Modelo",
            text: "Você precisa selecionar um ou mais modelos para serem inativados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja inativar este modelo?",
            text: "O sistema irá inativar o modelo selecionado com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, inativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "modelo/inativarModelo");
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
                            text: "O modelo selecionado foi inativado com sucesso.",
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