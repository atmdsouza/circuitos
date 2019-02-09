var table = $("#tb_equipamento").DataTable({
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

table.buttons().container().appendTo("#tb_equipamento_wrapper .col-md-6:eq(0)");

table.on( "select deselect", function () {
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows === 1 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
    table.button( 5 ).enable( selectedRows > 0 );
});

$(".bt_novo").on("click", function(){
    $("#modalequipamento").modal();
    $("#salvarEquipamento").removeClass("editar_equipamento").addClass("criar_equipamento");
});

$("#id_fabricante").on("change", function(){
    var id_fabricante = $(this).val();
    var action = actionCorreta(window.location.href.toString(), "equipamento/carregaModelos");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_fabricante: id_fabricante},
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
                $(".remove").remove();
                $.each(data.dados, function (key, value) {
                    var linhas = "<option class='remove' value='" + value.id + "'>" + value.modelo + "</option>";
                    $("#id_modelo").append(linhas);
                    $("#id_modelo").removeAttr("disabled");
                });
            } else {
                $(".remove").remove();
                $("#id_modelo").val(null).selected = "true";
                $("#id_modelo").attr("disabled", "true");
                swal({
                    title: "Cadastro de Equipamentos",
                    text: data.mensagem,
                    type: "error"
                });
            }
        }
    });
});

$(document).on("click", ".criar_equipamento", function(){
    //Validação de formulário
    $("#formEquipamento").validate({
        rules : {
            id_fabricante:{
                required: true
            },
            id_modelo:{
                required: true
            },
            id_tipoequipamento:{
                required: true
            },
            numserie:{
                required: true
            },
            numpatrimonio:{
                required: true
            }
        },
        messages:{
            id_fabricante:{
                required:"É necessário informar um fabricante"
            },
            id_modelo:{
                required:"É necessário informar um modelo"
            },
            id_tipoequipamento:{
                required:"É necessário informar um tipo de equipamento"
            },
            numserie:{
                required:"É necessário informar um número de série"
            },
            numpatrimonio:{
                required:"É necessário informar um número de patrimônio"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formEquipamento").serialize();
            var action = actionCorreta(window.location.href.toString(), "equipamento/criarEquipamento");
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
                            title: "Cadastro de Equipamentos",
                            text: "Cadastro do equipamento concluído!",
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
                            title: "Cadastro de Equipamentos",
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
$("#tb_equipamento").on("click", "tr", function () {
    var valr = $(this)[0].cells[0].innerText;
    if (!ids.includes(valr)) {
        ids.push(valr);
    } else {
        var index = ids.indexOf(valr);
        ids.splice(index, 1);
    }
});

$(".bt_edit").on("click", function(){
    var id_equipamento = ids[0];
    var action = actionCorreta(window.location.href.toString(), "equipamento/formEquipamento");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_equipamento: id_equipamento},
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
            $("#formEquipamento input").removeAttr('readonly', 'readonly');
            $("#formEquipamento select").removeAttr('readonly', 'readonly');
            $("#formEquipamento textarea").removeAttr('readonly', 'readonly');
            var action = actionCorreta(window.location.href.toString(), "equipamento/carregaModelos");
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: action,
                data: {id_fabricante: data.dados.id_fabricante},
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
                        $(".remove").remove();
                        $.each(data.dados, function (key, value) {
                            var selected = (value.id_modelo == data.dados.id_modelo) ? "selected" : null;
                            var linhas = "<option " + selected + " class='remove' value='" + value.id + "'>" + value.modelo + "</option>";
                            $("#id_modelo").append(linhas);
                            $("#id_modelo").removeAttr("disabled");
                        });
                    } else {
                        $(".remove").remove();
                        $("#id_modelo").val(null).selected = "true";
                        $("#id_modelo").attr("disabled", "true");
                        swal({
                            title: "Cadastro de Equipamentos",
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
            $("#id").val(data.dados.id);
            $("#id_fabricante").val(data.dados.id_fabricante).selected = "true";
            $("#id_tipoequipamento").val(data.dados.id_tipoequipamento).selected = "true";
            $("#nome").val(data.dados.nome);
            $("#numserie").val(data.dados.numserie);
            $("#numpatrimonio").val(data.dados.numpatrimonio);
            $("#descricao").val(data.dados.descricao);
            $("#modalequipamento").modal();
        }
    });
    $("#salvarEquipamento").removeClass("criar_equipamento").addClass("editar_equipamento");
});

$(".bt_visual").on("click", function(){
    var id_equipamento = ids[0];
    var action = actionCorreta(window.location.href.toString(), "equipamento/formEquipamento");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_equipamento: id_equipamento},
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
            $("#formEquipamento input").attr('readonly', 'readonly');
            $("#formEquipamento select").attr('readonly', 'readonly');
            $("#formEquipamento textarea").attr('readonly', 'readonly');
            var action = actionCorreta(window.location.href.toString(), "equipamento/carregaModelos");
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: action,
                data: {id_fabricante: data.dados.id_fabricante},
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
                        $(".remove").remove();
                        $.each(data.dados, function (key, value) {
                            var selected = (value.id_modelo == data.dados.id_modelo) ? "selected" : null;
                            var linhas = "<option " + selected + " class='remove' value='" + value.id + "'>" + value.modelo + "</option>";
                            $("#id_modelo").append(linhas);
                            $("#id_modelo").removeAttr("disabled");
                        });
                    } else {
                        $(".remove").remove();
                        $("#id_modelo").val(null).selected = "true";
                        $("#id_modelo").attr("disabled", "true");
                        swal({
                            title: "Cadastro de Equipamentos",
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
            $("#id").val(data.dados.id);
            $("#id_fabricante").val(data.dados.id_fabricante).selected = "true";
            $("#id_tipoequipamento").val(data.dados.id_tipoequipamento).selected = "true";
            $("#nome").val(data.dados.nome);
            $("#numserie").val(data.dados.numserie);
            $("#numpatrimonio").val(data.dados.numpatrimonio);
            $("#descricao").val(data.dados.descricao);
            $("#modalequipamento").modal();
        }
    });
    $("#salvarEquipamento").removeClass("criar_equipamento").addClass("editar_equipamento");
});

$(document).on("click", ".editar_equipamento", function(){
    //Validação de formulário
    $("#formEquipamento").validate({
        rules : {
            id_fabricante:{
                required: true
            },
            id_modelo:{
                required: true
            },
            id_tipoequipamento:{
                required: true
            },
            numserie:{
                required: true
            },
            numpatrimonio:{
                required: true
            }
        },
        messages:{
            id_fabricante:{
                required:"É necessário informar um fabricante"
            },
            id_modelo:{
                required:"É necessário informar um modelo"
            },
            id_tipoequipamento:{
                required:"É necessário informar um tipo de equipamento"
            },
            numserie:{
                required:"É necessário informar um número de série"
            },
            numpatrimonio:{
                required:"É necessário informar um número de patrimônio"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formEquipamento").serialize();
            var action = actionCorreta(window.location.href.toString(), "equipamento/editarEquipamento");
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
                            title: "Cadastro de Equipamentos",
                            text: "Edição de equipamento concluída!",
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
                            title: "Cadastro de Equipamentos",
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
            title: "Tem certeza que deseja deletar múltipos equipamentos?",
            text: "O sistema irá deletar um total de " + nm_rows + " equipamentos com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "equipamento/deletarEquipamento");
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
                            text: "Os equipamentos selecionados foram deletados com sucesso.",
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
            text: "Você precisa selecionar um valor ou mais equipamentos para serem deletados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja deletar este equipamento?",
            text: "O sistema irá deletar o equipamento selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "equipamento/deletarEquipamento");
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
                            text: "O equipamento selecionado foi deletado com sucesso.",
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
            title: "Tem certeza que deseja ativar múltipos equipamentos?",
            text: "O sistema irá ativar um total de " + nm_rows + " equipamentos com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, ativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "equipamento/ativarEquipamento");
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
                            text: "Os equipamentos selecionados foram ativados com sucesso.",
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
            title: "Ativar Equipamentos",
            text: "Você precisa selecionar um ou mais equipamentos para serem ativados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja ativar este equipamento?",
            text: "O sistema irá ativar o equipamento selecionado com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, ativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "equipamento/ativarEquipamento");
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
                            text: "O equipamento selecionado foi ativado com sucesso.",
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
            title: "Tem certeza que deseja inativar múltipos equipamentos?",
            text: "O sistema irá inativar um total de " + nm_rows + " equipamentos com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, inativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "equipamento/inativarEquipamento");
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
                            text: "As equipamentos selecionados foram inativadas com sucesso.",
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
            title: "Inativar Equipamento",
            text: "Você precisa selecionar um ou mais equipamentos para serem inativados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja inativar este equipamento?",
            text: "O sistema irá inativar o equipamento selecionado com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, inativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "equipamento/inativarEquipamento");
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
                            text: "O equipamento selecionado foi inativado com sucesso.",
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