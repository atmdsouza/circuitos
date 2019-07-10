//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos
function inicializar()
{

}

//Datatable
var table = $("#tb_circuitos").DataTable({
    buttons: [
        {//Botão Novo Registro
            className: "bt_novo auto_serie_patrimonio auto_cidadedigital auto_cliente auto_fabricante",
            text: "Novo",
            name: "novo", // do not change name
            titleAttr: "Novo Circuito",
            action: function (e, dt, node, config) {
            }
        },
        // {//Botão Anexos
        //     className: "bt_anexo",
        //     text: "Anexos",
        //     name: "anexo", // do not change name
        //     titleAttr: "Anexos do Circuito",
        //     action: function (e, dt, node, config) {
        //     },
        //     enabled: false
        // },
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
            className: "bt_edit auto_cidadedigital auto_cliente",
            text: "Editar",
            name: "edit", // do not change name
            titleAttr: "Editar Circuito",
            action: function (e, dt, node, config) {
            },
            enabled: false
        },
        {//Botão Movimentar Registro (Inativo)
            className: "bt_mov auto_fabricantemov auto_serie_patrimoniomov",
            text: "Movimentar",
            name: "mov", // do not change name
            titleAttr: "Movimentar Circuito",
            action: function (e, dt, node, config) {
            },
            enabled: false
        },
        {//Botão Deletar Registro (Inativo)
            className: "bt_del",
            text: "Deletar",
            name: "del", // do not change name
            titleAttr: "Deletar Circuito",
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
        {//Botão exportar excel
            extend: "excelHtml5",
            text: "Excel",
            titleAttr: "Exportar para Excel"
        },
        {//Botão exportar pdf
            extend: "pdfHtml5",
            text: "PDF",
            titleAttr: "Exportar para PDF"
        }
    ],
    order: [[ 1, "desc" ]]
});

table.buttons().container().appendTo("#tb_circuitos_wrapper .col-md-6:eq(0)");

table.on( "select deselect", function () {
    "use strict";
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows === 1 );
    table.button( 3 ).enable( selectedRows === 1 );
    table.button( 4 ).enable( selectedRows > 0 );
    // table.button( 5 ).enable( selectedRows > 0 );
});

function limparModal()
{
    "use strict";
    $("#id").val(null);
}

function limparModalAnexos()
{
    "use strict";
    $("#id_anexocircuito").val(null);
}
//Cliente e suas unidades
// $("#id_cliente").on("change", function(){
//     "use strict";
//     var id_cliente = $(this).val();
//     var action = actionCorreta(window.location.href.toString(), "circuitos/unidadeCliente");
//     $.ajax({
//         type: "GET",
//         dataType: "JSON",
//         url: action,
//         data: {id_cliente: id_cliente},
//         beforeSend: function () {
//         },
//         complete: function () {
//         },
//         error: function (data) {
//             if (data.status && data.status === 401)
//             {
//                 swal({
//                     title: "Erro de Permissão",
//                     text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                     type: "warning"
//                 });
//             }
//         },
//         success: function (data) {
//             $("#tipocliente").val(data.tipocliente);
//             if (data.operacao){
//                 $(".remove_cliente_unidade").remove();
//                 $.each(data.dados, function (key, value) {
//                     var linhas = "<option class='remove_cliente_unidade' value='" + value.id + "'>" + value.nome + "</option>";
//                     $("#id_cliente_unidade").append(linhas);
//                 });
//                 $("#id_cliente_unidade").removeAttr("disabled");
//             } else {
//                 $(".remove_cliente_unidade").remove();
//                 $("#id_cliente_unidade").val(null).selected = "true";
//                 $("#id_cliente_unidade").attr("disabled", "true");
//             }
//         }
//     });
// });

//Cidade Digital e suas conectividades
// $("#id_cidadedigital").on("change", function(){
//     "use strict";
//     var id_cidadedigital = $(this).val();
//     var action = actionCorreta(window.location.href.toString(), "circuitos/cidadedigitalConectividade");
//     $.ajax({
//         type: "GET",
//         dataType: "JSON",
//         url: action,
//         data: {id_cidadedigital: id_cidadedigital},
//         beforeSend: function () {
//         },
//         complete: function () {
//         },
//         error: function (data) {
//             if (data.status && data.status === 401)
//             {
//                 swal({
//                     title: "Erro de Permissão",
//                     text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                     type: "warning"
//                 });
//             }
//         },
//         success: function (data) {
//             if (data.operacao){
//                 $(".remove_conectividade").remove();
//                 $.each(data.dados, function (key, value) {
//                     var linhas = "<option class='remove_conectividade' value='" + value.id + "'>" + value.tipo + " " + value.descricao + "</option>";
//                     $("#id_conectividade").append(linhas);
//                 });
//                 $("#id_conectividade").removeAttr("disabled");
//             } else {
//                 $(".remove_conectividade").remove();
//                 $("#id_conectividade").val(null).selected = "true";
//                 $("#id_conectividade").attr("disabled", "true");
//                 swal("Atenção","Não existem conectividades para esse cidade digital!","info");
//             }
//         }
//     });
// });

$(".auto_cliente").on("click", function(){
    "use strict";
    //Autocomplete de Cidade Digital
    var ac_cliente = $("#lid_cliente");
    var listCliente = [];
    var ac_cliente_unidade = $("#lid_cliente_unidade");
    var listUnidadeCliente = [];
    var action = actionCorreta(window.location.href.toString(), "circuitos/clienteAll");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        beforeSend: function () {
            $("#id_cliente").val("");
            $("#lid_cliente").val("");
            listCliente = [];
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
                $.each(data.dados, function (key, value) {
                    listCliente.push({value: value.nome, data: value.id});
                });
            } else {
                $("#id_cliente").val("");
                $("#lid_cliente").val("");
            }
            //Autocomplete de Equipamento
            ac_cliente.autocomplete({
                lookup: listCliente,
                noCache: true,
                minChars: 1,
                triggerSelectOnValidInput: false,
                showNoSuggestionNotice: true,
                noSuggestionNotice: "Não existem resultados para essa consulta!",
                onSelect: function (suggestion) {
                    $("#id_cliente").val(suggestion.data);
                    var vl_cliente = suggestion.data;
                    if (vl_cliente) {
                        var id_cliente = $("#id_cliente").val();
                        var action = actionCorreta(window.location.href.toString(), "circuitos/unidadeCliente");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {id_cliente: id_cliente},
                            beforeSend: function () {
                                $("#id_cliente_unidade").val("");
                                $("#lid_cliente_unidade").val("");
                                $("#lid_cliente_unidade").attr("disabled", "true");
                                listUnidadeCliente = [];
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
                                    $.each(data.dados, function (key, value) {
                                        listUnidadeCliente.push({value: value.nome, data: value.id});
                                    });
                                    $("#lid_cliente_unidade").removeAttr("disabled");
                                } else {
                                    $("#id_cliente_unidade").val("");
                                    $("#lid_cliente_unidade").val("");
                                    $("#lid_cliente_unidade").attr("disabled", "true");
                                    swal("Atenção","Não existem unidade para esse cliente!","info");
                                }
                                //Autocomplete de Cliente Unidade
                                ac_cliente_unidade.autocomplete({
                                    lookup: listUnidadeCliente,
                                    noCache: true,
                                    minChars: 1,
                                    triggerSelectOnValidInput: false,
                                    showNoSuggestionNotice: true,
                                    noSuggestionNotice: "Não existem resultados para essa consulta!",
                                    onSelect: function (suggestion) {
                                        $("#id_cliente_unidade").val(suggestion.data);
                                    }
                                });
                            }
                        });
                    } else {
                        $("#id_cliente_unidade").val("");
                        $("#lid_cliente_unidade").val("");
                        $("#lid_cliente_unidade").attr("disabled", "true");
                    }
                }
            });
        }
    });
});

$(".auto_cidadedigital").on("click", function(){
    //Autocomplete de Cidade Digital
    "use strict";
    var ac_cidadedigital = $("#lid_cidadedigital");
    var listCidadeDigital = [];
    var ac_conectividade = $("#lid_conectividade");
    var listConectividade = [];
    var action = actionCorreta(window.location.href.toString(), "circuitos/cidadedigitalAll");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        beforeSend: function () {
            $("#id_cidadedigital").val("");
            $("#lid_cidadedigital").val("");
            listCidadeDigital = [];
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
                $.each(data.dados, function (key, value) {
                    listCidadeDigital.push({value: value.descricao, data: value.id});
                });
            } else {
                $("#id_cidadedigital").val("");
                $("#lid_cidadedigital").val("");
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
                    $("#id_cidadedigital").val(suggestion.data);
                    var vl_cidadedigital = $("#lid_cidadedigital").val();
                    if (vl_cidadedigital) {
                        var id_cidadedigital = suggestion.data;
                        var action = actionCorreta(window.location.href.toString(), "circuitos/cidadedigitalConectividade");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {id_cidadedigital: id_cidadedigital},
                            beforeSend: function () {
                                $("#id_conectividade").val("");
                                $("#lid_conectividade").val("");
                                $("#lid_conectividade").attr("disabled", "true");
                                listConectividade = [];
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
                                    $.each(data.dados, function (key, value) {
                                        listConectividade.push({value: value.tipo + " " + value.descricao, data: value.id});
                                    });
                                    $("#lid_conectividade").removeAttr("disabled");
                                } else {
                                    $("#id_conectividade").val("");
                                    $("#lid_conectividade").val("");
                                    $("#lid_conectividade").attr("disabled", "true");
                                    swal("Atenção","Não existem conectividades para essa cidade digital!","info");
                                }
                                //Autocomplete de Equipamento
                                ac_conectividade.autocomplete({
                                    lookup: listConectividade,
                                    noCache: true,
                                    minChars: 1,
                                    triggerSelectOnValidInput: false,
                                    showNoSuggestionNotice: true,
                                    noSuggestionNotice: "Não existem resultados para essa consulta!",
                                    onSelect: function (suggestion) {
                                        $("#id_conectividade").val(suggestion.data);
                                    }
                                });
                            }
                        });
                    } else {
                        $("#id_conectividade").val("");
                        $("#lid_conectividade").val("");
                        $("#lid_conectividade").attr("disabled", "true");
                    }
                }
            });
        }
    });
});

$(".auto_fabricante").on("click", function(){
    "use strict";
    //Autocomplete de Fabricante
    var ac_fabricante = $("#lid_fabricante");
    var listFabricante = [];
    var ac_model = $("#lid_modelo");
    var ac_equip = $("#lid_equipamento");
    var listEquip = [];
    var listModel = [];
    var action = actionCorreta(window.location.href.toString(), "circuitos/fabricanteAll");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        beforeSend: function () {
            $("#id_fabricante").val("");
            $("#lid_fabricante").val("");
            $("#id_modelo").val("");
            $("#lid_modelo").val("");
            $("#lid_modelo").attr("disabled", "true");
            $("#lid_equipamento").val("");
            $("#id_equipamento").val("");
            $("#lid_equipamento").attr("disabled", "true");
            listFabricante = [];
            listModel = [];
            listEquip = [];
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
                $.each(data.dados, function (key, value) {
                    listFabricante.push({value: value.nome, data: value.id});
                });
            } else {
                $("#id_fabricante").val("");
                $("#lid_fabricante").val("");
            }
            //Autocomplete de Equipamento
            ac_fabricante.autocomplete({
                lookup: listFabricante,
                noCache: true,
                minChars: 1,
                triggerSelectOnValidInput: false,
                showNoSuggestionNotice: true,
                noSuggestionNotice: "Não existem resultados para essa consulta!",
                onSelect: function (suggestion) {
                    $("#id_fabricante").val(suggestion.data);
                    var vl_fabricante = $("#lid_fabricante").val();
                    if (vl_fabricante) {
                        var id_fabricante = suggestion.data;
                        var action = actionCorreta(window.location.href.toString(), "circuitos/modeloFabricante");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {id_fabricante: id_fabricante},
                            beforeSend: function () {
                                $("#id_modelo").val("");
                                $("#lid_modelo").val("");
                                $("#lid_modelo").attr("disabled", "true");
                                $("#lid_equipamento").val("");
                                $("#id_equipamento").val("");
                                $("#lid_equipamento").attr("disabled", "true");
                                listModel = [];
                                listEquip = [];
                            },
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
                                if (data.operacao) {
                                    $.each(data.dados, function (key, value) {
                                        listModel.push({value: value.modelo, data: value.id});
                                    });
                                    $("#lid_modelo").removeAttr("disabled");
                                    $("#id_equipamento").val("");
                                    $("#lid_equipamento").val("");
                                    $("#lid_equipamento").attr("disabled", "true");
                                } else {
                                    $("#id_modelo").val("");
                                    $("#lid_modelo").val("");
                                    $("#lid_modelo").attr("disabled", "true");
                                    $("#id_equipamento").val("");
                                    $("#lid_equipamento").val("");
                                    $("#lid_equipamento").attr("disabled", "true");
                                    swal("Atenção", "Não existem modelos para esse fabricante!", "info");
                                }

                                //Autocomplete de Modelo
                                ac_model.autocomplete({
                                    lookup: listModel,
                                    noCache: true,
                                    minChars: 1,
                                    triggerSelectOnValidInput: false,
                                    showNoSuggestionNotice: true,
                                    noSuggestionNotice: "Não existem resultados para essa consulta!",
                                    onSelect: function (suggestion) {
                                        $("#id_modelo").val(suggestion.data);
                                        var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoModelo");
                                        $.ajax({
                                            type: "GET",
                                            dataType: "JSON",
                                            url: action,
                                            data: {id_modelo: suggestion.data},
                                            beforeSend: function () {
                                                $("#lid_equipamento").val("");
                                                $("#id_equipamento").val("");
                                                $("#lid_equipamento").attr("disabled", "true");
                                                listEquip = [];
                                            },
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
                                                if (data.operacao) {
                                                    $.each(data.dados, function (key, value) {
                                                        var numserie = (value.numserie) ? value.numserie : "Sem Nº Série";
                                                        var numpatrimonio = (value.numpatrimonio) ? value.numpatrimonio : "Sem Nº Patrimônio";
                                                        listEquip.push({
                                                            value: value.nome + " (" + numserie + " / " + numpatrimonio + ")",
                                                            data: value.id
                                                        });
                                                    });
                                                    $("#lid_equipamento").removeAttr("disabled");
                                                } else {
                                                    $("#lid_equipamento").val("");
                                                    $("#id_equipamento").val("");
                                                    $("#lid_equipamento").attr("disabled", "true");
                                                    swal("Atenção", "Não existem equipamentos para este modelo!", "info");
                                                }

                                                //Autocomplete de Equipamento
                                                ac_equip.autocomplete({
                                                    lookup: listEquip,
                                                    noCache: true,
                                                    minChars: 1,
                                                    triggerSelectOnValidInput: false,
                                                    showNoSuggestionNotice: true,
                                                    noSuggestionNotice: "Não existem resultados para essa consulta!",
                                                    onSelect: function (suggestion) {
                                                        $("#id_equipamento").val(suggestion.data);
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    } else {
                        $("#id_modelo").val("");
                        $("#lid_modelo").val("");
                        $("#lid_modelo").attr("disabled", "true");
                        $("#lid_equipamento").val("");
                        $("#id_equipamento").val("");
                        $("#lid_equipamento").attr("disabled", "true");
                    }
                }
            });
        }
    });
});

//Fabricante, seus modelos e equipamentos
// $("#id_fabricante").on("change", function(){
//     "use strict";
//     var id_fabricante = $(this).val();
//     var action = actionCorreta(window.location.href.toString(), "circuitos/modeloFabricante");
//     $.ajax({
//         type: "GET",
//         dataType: "JSON",
//         url: action,
//         data: {id_fabricante: id_fabricante},
//         beforeSend: function () {
//             $(".remove_equipamento").remove();
//             $("#id_equipamento").val(null).selected = "true";
//             $("#id_equipamento").attr("disabled", "true");
//             $(".remove_modelo").remove();
//             $("#id_modelo").val(null).selected = "true";
//             $("#id_modelo").attr("disabled", "true");
//         },
//         complete: function () {
//         },
//         error: function (data) {
//             if (data.status && data.status === 401)
//             {
//                 swal({
//                     title: "Erro de Permissão",
//                     text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                     type: "warning"
//                 });
//             }
//         },
//         success: function (data) {
//             if (data.operacao){
//                 $(".remove_modelo").remove();
//                 $.each(data.dados, function (key, value) {
//                     var linhas = "<option class='remove_modelo' value='" + value.id + "'>" + value.modelo + "</option>";
//                     $("#id_modelo").append(linhas);
//                 });
//                 $("#id_modelo").removeAttr("disabled");
//             } else {
//                 $(".remove_modelo").remove();
//                 $("#id_modelo").val(null).selected = "true";
//                 $("#id_modelo").attr("disabled", "true");
//                 swal("Atenção","Não existem modelos para esse fabricante!","info");
//             }
//         }
//     });
// });
// $("#id_modelo").on("change", function(){
//     "use strict";
//     var id_modelo = $(this).val();
//     var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoModelo");
//     $.ajax({
//         type: "GET",
//         dataType: "JSON",
//         url: action,
//         data: {id_modelo: id_modelo},
//         beforeSend: function () {
//         },
//         complete: function () {
//         },
//         error: function (data) {
//             if (data.status && data.status === 401)
//             {
//                 swal({
//                     title: "Erro de Permissão",
//                     text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                     type: "warning"
//                 });
//             }
//         },
//         success: function (data) {
//             if (data.operacao){
//                 $(".remove_equipamento").remove();
//                 $.each(data.dados, function (key, value) {
//                     var numserie = (value.numserie) ? value.numserie : "Sem Nº Série";
//                     var numpatrimonio = (value.numpatrimonio) ? value.numpatrimonio : "Sem Nº Patrimônio";
//                     var linhas = "<option class='remove_equipamento' value='" + value.id + "'>" + value.nome + " (" + numserie + " / " + numpatrimonio + ") </option>";
//                     $("#id_equipamento").append(linhas);
//                 });
//                 $("#id_equipamento").removeAttr("disabled");
//             } else {
//                 $(".remove_equipamento").remove();
//                 $("#id_equipamento").val(null).selected = "true";
//                 $("#id_equipamento").attr("disabled", "true");
//                 swal("Atenção","Não existem equipamentos para esse modelo!","info");
//             }
//         }
//     });
// });
//Validando o equipamento selecionados
$("#lid_equipamento").on("change", function(){
    "use strict";
    var id_equipamento = $("#id_equipamento").val();
    validaEquipamentoCircuito(id_equipamento).done(function(valida){
        if (valida) {
            $("#lid_fabricante").val(null);
            $("#lid_modelo").val(null);
            $("#lid_equipamento").val(null);
            $("#id_fabricante").val(null);
            $("#id_modelo").val(null);
            $("#id_equipamento").val(null);
            $("#lid_modelo").attr("disabled", "true");
            $("#lid_equipamento").attr("disabled", "true");
            swal("Atenção","Esse equipamento já foi cadastrado para outro circuito!","info");
        }
    });
});
//Campo Número de Série
// $("#lnumero_serie").on("change", function () {
//     "use strict";
//     var numero_serie = $(this).val();
//     if (numero_serie !== ""){
//         var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoNumeroSerie");
//         $.ajax({
//             type: "GET",
//             dataType: "JSON",
//             url: action,
//             data: {numero_serie: numero_serie},
//             error: function (data) {
//                 if (data.status && data.status === 401)
//                 {
//                     swal({
//                         title: "Erro de Permissão",
//                         text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                         type: "warning"
//                     });
//                 }
//             },
//             success: function (data) {
//                 if (data.operacao){
//                     validaEquipamentoCircuito(data.id_equipamento).done(function(valida){
//                         if (valida) {
//                             $("#lid_fabricante").val(null);
//                             $("#lid_modelo").val(null);
//                             $("#lid_equipamento").val(null);
//                             $("#id_fabricante").val(null);
//                             $("#id_modelo").val(null);
//                             $("#id_equipamento").val(null);
//                             $("#lid_modelo").attr("disabled", "true");
//                             $("#lid_equipamento").attr("disabled", "true");
//                             swal("Atenção","Esse equipamento já foi cadastrado para outro circuito!","info");
//                         } else {
//                             $("#lid_fabricante").val(data.nome_fabricante);
//                             $("#lid_modelo").val(data.nome_modelo);
//                             $("#lid_equipamento").val(data.nome_equipamento + " (" + numero_serie + " / " + data.numero_patrimonio + ")");
//                             $("#id_fabricante").val(data.id_fabricante);
//                             $("#id_modelo").val(data.id_modelo);
//                             $("#id_equipamento").val(data.id_equipamento);
//                             $("#lid_modelo").removeAttr("disabled");
//                             $("#lid_equipamento").removeAttr("disabled");
//                         }
//                     });
//                 } else {
//                     $("#lid_fabricante").val(null);
//                     $("#lid_modelo").val(null);
//                     $("#lid_equipamento").val(null);
//                     $("#id_fabricante").val(null);
//                     $("#id_modelo").val(null);
//                     $("#id_equipamento").val(null);
//                     $("#lid_modelo").attr("disabled", "true");
//                     $("#lid_equipamento").attr("disabled", "true");
//                     swal("Atenção","Não existem equipamentos para esse número de série!","info");
//                 }
//             }
//         });
//     }
// });
//Validar vinculo de equipamento e circuito
function validaEquipamentoCircuito(id_equipamento)
{
    "use strict";
    var action = actionCorreta(window.location.href.toString(), "circuitos/validarEquipamentoCircuito");
    return $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_equipamento: id_equipamento},
        async: false,
        error: function (data) {
            if (data.status && data.status === 401)
            {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        }
    });
}

$(".auto_serie_patrimonio").on("click", function(){
    "use strict";
    //Autocomplete de Cidade Digital
    var ac_serie_patrimonio = $("#lnumero_serie");
    var listSeriePatrimonio = [];
    var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoSeriePatrimonio");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        beforeSend: function () {
            $("#numero_serie").val("");
            $("#lnumero_serie").val("");
            listSeriePatrimonio = [];
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
                $.each(data.dados, function (key, value) {
                    listSeriePatrimonio.push({value: value, data: value});
                });
            } else {
                $("#numero_serie").val("");
                $("#lnumero_serie").val("");
            }
            //Autocomplete de Equipamento
            ac_serie_patrimonio.autocomplete({
                lookup: listSeriePatrimonio,
                noCache: true,
                minChars: 1,
                triggerSelectOnValidInput: false,
                showNoSuggestionNotice: true,
                noSuggestionNotice: "Não existem resultados para essa consulta!",
                onSelect: function (suggestion) {
                    $("#numero_serie").val(suggestion.data);
                    var numero_serie = suggestion.data;
                    if (numero_serie !== ""){
                        var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoNumeroSerie");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {numero_serie: numero_serie},
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
                                    validaEquipamentoCircuito(data.id_equipamento).done(function(valida){
                                        if (valida) {
                                            $("#lid_fabricante").val(null);
                                            $("#lid_modelo").val(null);
                                            $("#lid_equipamento").val(null);
                                            $("#id_fabricante").val(null);
                                            $("#id_modelo").val(null);
                                            $("#id_equipamento").val(null);
                                            $("#lid_modelo").attr("disabled", "true");
                                            $("#lid_equipamento").attr("disabled", "true");
                                            swal("Atenção","Esse equipamento já foi cadastrado para outro circuito!","info");
                                        } else {
                                            $("#lid_fabricante").val(data.nome_fabricante);
                                            $("#lid_modelo").val(data.nome_modelo);
                                            $("#lid_equipamento").val(data.nome_equipamento + " (" + numero_serie + " / " + data.numero_patrimonio + ")");
                                            $("#id_fabricante").val(data.id_fabricante);
                                            $("#id_modelo").val(data.id_modelo);
                                            $("#id_equipamento").val(data.id_equipamento);
                                            $("#lid_modelo").removeAttr("disabled");
                                            $("#lid_equipamento").removeAttr("disabled");
                                        }
                                    });
                                } else {
                                    $("#lid_fabricante").val(null);
                                    $("#lid_modelo").val(null);
                                    $("#lid_equipamento").val(null);
                                    $("#id_fabricante").val(null);
                                    $("#id_modelo").val(null);
                                    $("#id_equipamento").val(null);
                                    $("#lid_modelo").attr("disabled", "true");
                                    $("#lid_equipamento").attr("disabled", "true");
                                    swal("Atenção","Não existem equipamentos para esse número de série!","info");
                                }
                            }
                        });
                    }
                }
            });
        }
    });
});

$(".bt_novo").on("click", function(){
    "use strict";
    $("#modalcircuitos").modal();
    $("#salvaCircuitos").removeClass("editar_circuitos").addClass("criar_circuitos");
});

$(document).on("click", ".criar_circuitos", function(){
    "use strict";
    var tipocliente = $("#tipocliente").val();
    switch (tipocliente)
    {
        case "43"://Pessoa Jurídica
            //Validação de formulário
            $("#formCircuitos").validate({
                rules : {
                    lid_cliente:{
                        required: true
                    },
                    lid_cliente_unidade:{
                        required: true
                    },
                    id_circuitos:{
                        required: true
                    },
                    designacao:{
                        required: true
                    },
                    chamado:{
                        required: true
                    },
                    banda:{
                        required: true
                    },
                    tag:{
                        required: true
                    },
                    lid_cidadedigital:{
                        required: true
                    },
                    lid_conectividade:{
                        required: true
                    },
                    id_contrato:{
                        required: true
                    },
                    id_cluster:{
                        required: true
                    },
                    id_tipolink:{
                        required: true
                    },
                    id_funcao:{
                        required: true
                    },
                    id_tipoacesso:{
                        required: true
                    },
                    lid_fabricante:{
                        required: true
                    },
                    lid_modelo:{
                        required: true
                    },
                    lid_equipamento:{
                        required: true
                    },
                    ip_redelocal:{
                        required: true
                    },
                    ip_gerencia:{
                        required: true
                    }
                },
                messages:{
                    lid_cliente:{
                        required:"É necessário informar um Cliente"
                    },
                    lid_cliente_unidade:{
                        required:"É necessário informar uma Uunidade de Cliente"
                    },
                    id_circuitos:{
                        required:"É necessário selecionar uma Circuitos"
                    },
                    designacao:{
                        required:"É necessário informar a Designação"
                    },
                    chamado:{
                        required: "É necessário informar um códido de Chamado"
                    },
                    banda:{
                        required: "É necessário informar uma Banda"
                    },
                    tag:{
                        required: "É necessário informar uma TAG"
                    },
                    lid_cidadedigital:{
                        required: "É necessário informar a Cidade Digital"
                    },
                    lid_conectividade:{
                        required: "É necessário informar a Conectividade"
                    },
                    id_contrato:{
                        required: "É necessário informar o tipo de Contrato"
                    },
                    id_cluster:{
                        required: "É necessário informar um Cluster"
                    },
                    id_tipolink:{
                        required: "É necessário informar um tipo de Link"
                    },
                    id_funcao:{
                        required: "É necessário informar uma Função"
                    },
                    id_tipoacesso:{
                        required: "É necessário informar um Enlce"
                    },
                    lid_fabricante:{
                        required: "É necessário informar um Fabricante"
                    },
                    lid_modelo:{
                        required: "É necessário informar um Modelo"
                    },
                    lid_equipamento:{
                        required: "É necessário informar um Equipamento"
                    },
                    ip_redelocal:{
                        required: "É necessário informar um IP de Rede Local"
                    },
                    ip_gerencia:{
                        required: "É necessário informar um IP de Rede Gerencial"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitos").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/criarCircuitos");
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
                                    title: "Cadastro de Circuitos",
                                    text: "Cadastro da Circuitos concluído!",
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
                                    title: "Cadastro de Circuitos",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        default://Pessoa Física
            //Validação de formulário
            $("#formCircuitos").validate({
                rules : {
                    lid_cliente:{
                        required: true
                    },
                    designacao:{
                        required: true
                    },
                    chamado:{
                        required: true
                    },
                    lid_cidadedigital:{
                        required: true
                    },
                    lid_conectividade:{
                        required: true
                    },
                    banda:{
                        required: true
                    },
                    tag:{
                        required: true
                    },
                    id_contrato:{
                        required: true
                    },
                    id_cluster:{
                        required: true
                    },
                    id_tipolink:{
                        required: true
                    },
                    id_funcao:{
                        required: true
                    },
                    id_tipoacesso:{
                        required: true
                    },
                    lid_fabricante:{
                        required: true
                    },
                    lid_modelo:{
                        required: true
                    },
                    lid_equipamento:{
                        required: true
                    },
                    ip_redelocal:{
                        required: true
                    },
                    ip_gerencia:{
                        required: true
                    }
                },
                messages:{
                    lid_cliente:{
                        required:"É necessário informar um Cliente"
                    },
                    designacao:{
                        required:"É necessário informar a Designação"
                    },
                    chamado:{
                        required: "É necessário informar um código de Chamado"
                    },
                    banda:{
                        required: "É necessário informar uma Banda"
                    },
                    tag:{
                        required: "É necessário informar uma TAG"
                    },
                    lid_cidadedigital:{
                        required: "É necessário informar a Cidade Digital"
                    },
                    lid_conectividade:{
                        required: "É necessário informar a Conectividade"
                    },
                    id_contrato:{
                        required: "É necessário informar o tipo de Contrato"
                    },
                    id_cluster:{
                        required: "É necessário informar um Cluster"
                    },
                    id_tipolink:{
                        required: "É necessário informar um tipo de Link"
                    },
                    id_funcao:{
                        required: "É necessário informar uma Função"
                    },
                    id_tipoacesso:{
                        required: "É necessário informar um Enlce"
                    },
                    lid_fabricante:{
                        required: "É necessário informar um Fabricante"
                    },
                    lid_modelo:{
                        required: "É necessário informar um Modelo"
                    },
                    lid_equipamento:{
                        required: "É necessário informar um Equipamento"
                    },
                    ip_redelocal:{
                        required: "É necessário informar um IP de Rede Local"
                    },
                    ip_gerencia:{
                        required: "É necessário informar um IP de Rede Gerencial"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitos").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/criarCircuitos");
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
                                    title: "Cadastro de Circuitos",
                                    text: "Cadastro da Circuitos concluído!",
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
                                    title: "Cadastro de Circuitos",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
    }
});

//Coletando os ids das linhas selecionadas na tabela
var ids = [];
$("#tb_circuitos").on("click", "tr", function () {
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
    "use strict";
    var id_circuitos = ids[0];
    var action = actionCorreta(window.location.href.toString(), "circuitos/formCircuitos");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_circuitos: id_circuitos},
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
            $("#id").val(data.dados.id);
            $("#tipocliente").val(data.dados.id_tipocliente);
            $("#lid_cliente").attr("disabled", "true");
            $("#id_cliente").val(data.dados.id_cliente);
            $("#id_cliente_unidade").val(data.dados.id_cliente_unidade);
            $("#lid_cliente").val(data.dados.lid_cliente);
            $("#lid_cliente_unidade").val(data.dados.lid_cliente_unidade);
            $("#numero_serie").attr("disabled", "true");
            $("#id_fabricante").val(data.dados.id_fabricante);
            $("#lid_fabricante").val(data.dados.lid_fabricante);
            $("#lid_fabricante").attr("disabled", "true");
            $("#lid_modelo").val(data.dados.lid_modelo);
            $("#id_modelo").val(data.dados.id_modelo);
            $("#lid_modelo").attr("disabled", "true");
            $("#lid_equipamento").val(data.dados.lid_equipamento + " ("+ data.dados.nums_equip +" / "+ data.dados.patr_equip +")");
            $("#id_equipamento").val(data.dados.id_equipamento);
            $("#lid_equipamento").attr("disabled", "true");
            $("#id_contrato").val(data.dados.id_contrato).selected = "true";
            $("#id_cluster").val(data.dados.id_cluster).selected = "true";
            $("#id_tipolink").val(data.dados.id_tipolink).selected = "true";
            $("#id_cidadedigital").val(data.dados.id_cidadedigital);
            $("#id_conectividade").val(data.dados.id_conectividade);
            $("#lid_cidadedigital").val(data.dados.lid_cidadedigital);
            $("#lid_cidadedigital").attr("disabled", "true");
            $("#lid_conectividade").val(data.dados.lid_conectividade);
            $("#lid_conectividade").attr("disabled", "true");
            $("#id_funcao").val(data.dados.id_funcao).selected = "true";
            $("#id_tipoacesso").val(data.dados.id_tipoacesso).selected = "true";
            $("#banda").val(data.dados.id_banda).selected = "true";
            $("#banda").attr("disabled", "true");
            $("#designacao").val(data.dados.designacao);
            $("#designacao_anterior").val(data.dados.designacao_anterior);
            $("#chamado").val(data.dados.chamado);
            $("#uf").val(data.dados.uf);
            $("#cidade").val(data.dados.cidade);
            $("#ssid").val(data.dados.ssid);
            $("#ip_redelocal").val(data.dados.ip_redelocal);
            $("#ip_redelocal").attr("disabled", "true");
            $("#ip_gerencia").val(data.dados.ip_gerencia);
            $("#ip_gerencia").attr("disabled", "true");
            $("#tag").val(data.dados.tag);
            $("#observacao").val(data.dados.observacao);
            $("#modalcircuitos").modal();
        }
    });
    $("#salvaCircuitos").removeClass("criar_circuitos").addClass("editar_circuitos");
});

$(".bt_visual").on("click", function(){
    "use strict";
    var id_circuitos = ids[0];
    var action = actionCorreta(window.location.href.toString(), "circuitos/visualizaCircuitos");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_circuitos: id_circuitos},
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
            $("#idv").val(data.dados.id);
            $("#lid_clientev").val(data.dados.lid_cliente);
            $("#lid_cliente_unidadev").val(data.dados.lid_cliente_unidade);
            $("#id_clientev").val(data.dados.id_cliente);
            $("#id_cliente_unidadev").val(data.dados.id_cliente_unidade);
            $("#lid_fabricantev").val(data.dados.lid_fabricante);
            $("#lid_modelov").val(data.dados.lid_modelo);
            $("#lid_equipamentov").val(data.dados.lid_equipamento);
            $("#id_fabricantev").val(data.dados.id_fabricante);
            $("#id_modelov").val(data.dados.id_modelo);
            $("#id_equipamentov").val(data.dados.id_equipamento);
            $("#id_contratov").val(data.dados.id_contrato).selected = "true";
            $("#id_statusv").val(data.dados.id_status).selected = "true";
            $("#id_clusterv").val(data.dados.id_cluster).selected = "true";
            $("#id_tipolinkv").val(data.dados.id_tipolink).selected = "true";
            $("#lid_cidadedigitalv").val(data.dados.lid_cidadedigital);
            $("#lid_conectividadev").val(data.dados.lid_conectividade);
            $("#id_cidadedigitalv").val(data.dados.id_cidadedigital);
            $("#id_conectividadev").val(data.dados.id_conectividade);
            $("#id_funcaov").val(data.dados.id_funcao).selected = "true";
            $("#id_tipoacessov").val(data.dados.id_tipoacesso).selected = "true";
            $("#bandav").val(data.dados.id_banda).selected = "true";
            $("#designacaov").val(data.dados.designacao);
            $("#designacao_anteriorv").val(data.dados.designacao_anterior);
            $("#ufv").val(data.dados.uf);
            $("#cidadev").val(data.dados.cidade);
            $("#chamadov").val(data.dados.chamado);
            $("#ssidv").val(data.dados.ssid);
            $("#ip_redelocalv").val(data.dados.ip_redelocal);
            $("#ip_gerenciav").val(data.dados.ip_gerencia);
            $("#tagv").val(data.dados.tag);
            $("#observacaov").val(data.dados.observacao);
            $("#dtativacaov").val(data.dados.data_ativacao);
            $("#dtatualizacaov").val(data.dados.data_atualizacao);
            $("#numpatserv").val(data.dados.numpatrimonio + " / " + data.dados.numserie);
            //Bloco de movimmentos
            var linhas;
            if(data.mov.length > 0)
            {
                $(".rem_mov").remove();
                $.each(data.mov, function (key, value) {
                    var os = value.osocomon ? value.osocomon : "";
                    var ant = value.valoranterior ? value.valoranterior : "";
                    var atu = value.valoratualizado ? value.valoratualizado : "";
                    var obs = value.observacao ? value.observacao : "";
                    linhas = "<tr class='rem_mov'>";
                    linhas += "<td>" + os + "</td>";
                    linhas += "<td>" + value.data_movimento + "</td>";
                    linhas += "<td>" + value.id_tipomovimento + "</td>";
                    linhas += "<td>" + value.id_usuario + "</td>";
                    linhas += "<td>" + ant + "</td>";
                    linhas += "<td>" + atu + "</td>";
                    linhas += "<td>" + obs + "</td>";
                    linhas += "</tr>";
                    $("#tb_movimento").append(linhas);
                });
            }
            else
            {
                $(".rem_mov").remove();
                linhas = "<tr class='rem_mov'>";
                linhas = "<td colspan='7' style='text-align: center;'>Não existem dados para serem exibidos! Dados Importados!</td>";
                linhas += "</tr>";
                $("#tb_movimento").append(linhas);
            }
            //Bloco de endereço
            var linhas_end;
            if(data.endereco.length > 0)
            {
                $(".rem_end").remove();
                $.each(data.endereco, function (key, value) {
                    var numero = value.numero ? " Nº "+ value.numero : "";
                    linhas_end = "<tr class='rem_end'>";
                    linhas_end += "<td>" + value.endereco + numero + "</td>";
                    linhas_end += "<td>" + value.complemento + "</td>";
                    linhas_end += "<td>" + value.bairro + "</td>";
                    linhas_end += "<td>" + value.cep + "</td>";
                    linhas_end += "</tr>";
                    $("#tb_endereco").append(linhas_end);
                });
            }
            else
            {
                $(".rem_end").remove();
                linhas_end = "<tr class='rem_end'>";
                linhas_end += "<td colspan='4' style='text-align: center;'>Não existe endereço para ser exibido! Favor Cadastrar!</td>";
                linhas_end += "</tr>";
                $("#tb_endereco").append(linhas_end);
            }
            //Bloco de contatos
            var linhas_cont;
            if(data.cont.length > 0)
            {
                $(".rem_cont").remove();
                $.each(data.cont, function (key, value) {
                    var fone;
                    if (value.telefone || value.telefone.length == 11)
                    {
                        fone = value.telefone ? value.telefone.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3') : "";
                    }
                    else
                    {
                        fone = value.telefone ? value.telefone.replace(/^(\d{2})(\d{4})(\d{4}).*/, '($1) $2-$3') : "";
                    }
                    var mail = value.email ? value.email : "";
                    linhas_cont = "<tr class='rem_cont'>";
                    linhas_cont += "<td>" + value.principal + "</td>";
                    linhas_cont += "<td>" + value.id_tipocontato + "</td>";
                    linhas_cont += "<td>" + value.nome + "</td>";
                    linhas_cont += "<td>" + fone + "</td>";
                    linhas_cont += "<td>" + mail + "</td>";
                    linhas_cont += "</tr>";
                    $("#tb_contatos").append(linhas_cont);
                });
            }
            else
            {
                $(".rem_cont").remove();
                linhas_cont = "<tr class='rem_cont'>";
                linhas_cont += "<td colspan='5' style='text-align: center;'>Não existem contatos para serem exibidos! Favor Cadastrar!</td>";
                linhas_cont += "</tr>";
                $("#tb_contatos").append(linhas_cont);
            }
            $("#modalvisualizar").modal();
        }
    });
});

$(document).on("click", ".editar_circuitos", function(){
    "use strict";
    var tipocliente = $("#tipocliente").val();
    switch (tipocliente)
    {
        case "43"://Pessoa Jurídica
            //Validação de formulário
            $("#formCircuitos").validate({
                rules : {
                    lid_cliente:{
                        required: true
                    },
                    lid_cliente_unidade:{
                        required: true
                    },
                    id_circuitos:{
                        required: true
                    },
                    designacao:{
                        required: true
                    },
                    chamado:{
                        required: true
                    },
                    banda:{
                        required: true
                    },
                    tag:{
                        required: true
                    },
                    lid_cidadedigital:{
                        required: true
                    },
                    lid_conectividade:{
                        required: true
                    },
                    id_contrato:{
                        required: true
                    },
                    id_cluster:{
                        required: true
                    },
                    id_tipolink:{
                        required: true
                    },
                    id_funcao:{
                        required: true
                    },
                    id_tipoacesso:{
                        required: true
                    },
                    lid_fabricante:{
                        required: true
                    },
                    lid_modelo:{
                        required: true
                    },
                    lid_equipamento:{
                        required: true
                    },
                    ip_redelocal:{
                        required: true
                    },
                    ip_gerencia:{
                        required: true
                    }
                },
                messages:{
                    lid_cliente:{
                        required:"É necessário informar um Cliente"
                    },
                    lid_cliente_unidade:{
                        required:"É necessário informar uma Unidade de Cliente"
                    },
                    id_circuitos:{
                        required:"É necessário selecionar uma Circuitos"
                    },
                    designacao:{
                        required:"É necessário informar a Designação"
                    },
                    chamado:{
                        required: "É necessário informar um códido de Chamado"
                    },
                    banda:{
                        required: "É necessário informar uma Banda"
                    },
                    tag:{
                        required: "É necessário informar uma TAG"
                    },
                    lid_cidadedigital:{
                        required: "É necessário informar a Cidade Digital"
                    },
                    lid_conectividade:{
                        required: "É necessário informar a Conectividade"
                    },
                    id_contrato:{
                        required: "É necessário informar o tipo de Contrato"
                    },
                    id_cluster:{
                        required: "É necessário informar um Cluster"
                    },
                    id_tipolink:{
                        required: "É necessário informar um tipo de Link"
                    },
                    id_funcao:{
                        required: "É necessário informar uma Função"
                    },
                    id_tipoacesso:{
                        required: "É necessário informar um Enlce"
                    },
                    lid_fabricante:{
                        required: "É necessário informar um Fabricante"
                    },
                    lid_modelo:{
                        required: "É necessário informar um Modelo"
                    },
                    lid_equipamento:{
                        required: "É necessário informar um Equipamento"
                    },
                    ip_redelocal:{
                        required: "É necessário informar um IP de Rede Local"
                    },
                    ip_gerencia:{
                        required: "É necessário informar um IP de Rede Gerencial"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitos").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/editarCircuitos");
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
                                    title: "Cadastro de Circuitos",
                                    text: "Cadastro da Circuitos concluído!",
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
                                    title: "Cadastro de Circuitos",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        default://Pessoa Física
            //Validação de formulário
            $("#formCircuitos").validate({
                rules : {
                    lid_cliente:{
                        required: true
                    },
                    designacao:{
                        required: true
                    },
                    chamado:{
                        required: true
                    },
                    lid_cidadedigital:{
                        required: true
                    },
                    lid_conectividade:{
                        required: true
                    },
                    banda:{
                        required: true
                    },
                    tag:{
                        required: true
                    },
                    id_contrato:{
                        required: true
                    },
                    id_cluster:{
                        required: true
                    },
                    id_tipolink:{
                        required: true
                    },
                    id_funcao:{
                        required: true
                    },
                    id_tipoacesso:{
                        required: true
                    },
                    lid_fabricante:{
                        required: true
                    },
                    lid_modelo:{
                        required: true
                    },
                    lid_equipamento:{
                        required: true
                    },
                    ip_redelocal:{
                        required: true
                    },
                    ip_gerencia:{
                        required: true
                    }
                },
                messages:{
                    lid_cliente:{
                        required:"É necessário informar um Cliente"
                    },
                    designacao:{
                        required:"É necessário informar a Designação"
                    },
                    chamado:{
                        required: "É necessário informar um código de Chamado"
                    },
                    banda:{
                        required: "É necessário informar uma Banda"
                    },
                    tag:{
                        required: "É necessário informar uma TAG"
                    },
                    lid_cidadedigital:{
                        required: "É necessário informar a Cidade Digital"
                    },
                    lid_conectividade:{
                        required: "É necessário informar a Conectividade"
                    },
                    id_contrato:{
                        required: "É necessário informar o tipo de Contrato"
                    },
                    id_cluster:{
                        required: "É necessário informar um Cluster"
                    },
                    id_tipolink:{
                        required: "É necessário informar um tipo de Link"
                    },
                    id_funcao:{
                        required: "É necessário informar uma Função"
                    },
                    id_tipoacesso:{
                        required: "É necessário informar um Enlce"
                    },
                    lid_fabricante:{
                        required: "É necessário informar um Fabricante"
                    },
                    lid_modelo:{
                        required: "É necessário informar um Modelo"
                    },
                    lid_equipamento:{
                        required: "É necessário informar um Equipamento"
                    },
                    ip_redelocal:{
                        required: "É necessário informar um IP de Rede Local"
                    },
                    ip_gerencia:{
                        required: "É necessário informar um IP de Rede Gerencial"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitos").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/editarCircuitos");
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
                                    title: "Cadastro de Circuitos",
                                    text: "Cadastro da Circuitos concluído!",
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
                                    title: "Cadastro de Circuitos",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
    }
});

$(".bt_mov").on("click", function(){
    "use strict";
    $("#id_circuito").val(ids[0]);
    $("#modalcircuitosmov").modal();
    $("#salvaCircuitosmov").addClass("criar_mov");
});

$("#id_tipomovimento").on("change", function(){
    "use strict";
    var id_tipomovimento = $("#id_tipomovimento").val();
    var id_circuito = $("#id_circuito").val();
    switch(id_tipomovimento)
    {
        case "4"://Alteração de Banda
            $("#bandamovdiv").show();
            $("#redelocalmovdiv").hide();
            $("#gerenciamovdiv").hide();
            $("#statusmovdiv").hide();
            $(".equip").hide();
            $(".cidade_digital").hide();
            $(".conectividade").hide();
            $(".cliente").hide();
            $(".ucliente").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
            break;
        case "5"://Mudança de Status do Circuito
            $("#statusmovdiv").show();
            $("#gerenciamovdiv").hide();
            $("#redelocalmovdiv").hide();
            $("#bandamovdiv").hide();
            $(".equip").hide();
            $(".cidade_digital").hide();
            $(".conectividade").hide();
            $(".cliente").hide();
            $(".ucliente").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
            break;
        case "6"://Alteração de IP Gerencial
            $("#gerenciamovdiv").show();
            $("#bandamovdiv").hide();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $(".equip").hide();
            $(".cidade_digital").hide();
            $(".conectividade").hide();
            $(".cliente").hide();
            $(".ucliente").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
            break;
        case "7"://Alteração de IP Local
            $("#redelocalmovdiv").show();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $(".equip").hide();
            $(".cidade_digital").hide();
            $(".conectividade").hide();
            $(".cliente").hide();
            $(".ucliente").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
            break;
        case "8"://Alteração de Equipamento
            $(".equip").show();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $(".cidade_digital").hide();
            $(".conectividade").hide();
            $(".cliente").hide();
            $(".ucliente").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
            break;
        case "9"://Alteração de Cliente

            var ac_cliente = $("#lid_clientemov");
            var listCliente = [];
            var ac_cliente_unidade = $("#lid_cliente_unidademov");
            var listUnidadeCliente = [];
            var action = actionCorreta(window.location.href.toString(), "circuitos/clienteAll");
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: action,
                beforeSend: function () {
                    $("#id_clientemov").val("");
                    $("#lid_clientemov").val("");
                    listCliente = [];
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
                            listCliente.push({value: value.nome, data: value.id});
                        });
                    } else {
                        $("#id_clientemov").val("");
                        $("#lid_clientemov").val("");
                    }
                    //Autocomplete de Equipamento
                    ac_cliente.autocomplete({
                        lookup: listCliente,
                        noCache: true,
                        minChars: 1,
                        triggerSelectOnValidInput: false,
                        showNoSuggestionNotice: true,
                        noSuggestionNotice: "Não existem resultados para essa consulta!",
                        onSelect: function (suggestion) {
                            $("#id_clientemov").val(suggestion.data);
                            var vl_cliente = suggestion.data;
                            if (vl_cliente) {
                                var id_cliente = $("#id_clientemov").val();
                                var action = actionCorreta(window.location.href.toString(), "circuitos/unidadeCliente");
                                $.ajax({
                                    type: "GET",
                                    dataType: "JSON",
                                    url: action,
                                    data: {id_cliente: id_cliente},
                                    beforeSend: function () {
                                        $("#id_cliente_unidademov").val("");
                                        $("#lid_cliente_unidademov").val("");
                                        $("#lid_cliente_unidademov").attr("disabled", "true");
                                        listUnidadeCliente = [];
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
                                                listUnidadeCliente.push({value: value.nome, data: value.id});
                                            });
                                            $("#lid_cliente_unidademov").removeAttr("disabled");
                                        } else {
                                            $("#id_cliente_unidademov").val("");
                                            $("#lid_cliente_unidademov").val("");
                                            $("#lid_cliente_unidademov").attr("disabled", "true");
                                            swal("Atenção","Não existem unidade para esse cliente!","info");
                                        }
                                        //Autocomplete de Cliente Unidade
                                        ac_cliente_unidade.autocomplete({
                                            lookup: listUnidadeCliente,
                                            noCache: true,
                                            minChars: 1,
                                            triggerSelectOnValidInput: false,
                                            showNoSuggestionNotice: true,
                                            noSuggestionNotice: "Não existem resultados para essa consulta!",
                                            onSelect: function (suggestion) {
                                                $("#id_cliente_unidademov").val(suggestion.data);
                                            }
                                        });
                                    }
                                });
                            } else {
                                $("#id_cliente_unidademov").val("");
                                $("#lid_cliente_unidademov").val("");
                                $("#lid_cliente_unidademov").attr("disabled", "true");
                            }
                        }
                    });
                }
            });

            $(".cliente").show();
            $(".ucliente").show();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $(".cidade_digital").hide();
            $(".conectividade").hide();
            $("#lid_cliente_unidademov").attr("disabled", "true");
            $("#lid_clientemov").removeAttr("disabled");
            $("#salvaCircuitosmov").removeAttr("disabled");
            break;
        case "10"://Alteração de Unidade Cliente

            ac_cliente_unidade = $("#lid_cliente_unidademov");
            listUnidadeCliente = [];
            action = actionCorreta(window.location.href.toString(), "circuitos/getClienteCircuito");
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: action,
                data: {id_circuito: id_circuito},
                beforeSend: function () {
                    $("#id_clientemov").val("");
                    $("#lid_clientemov").val("");
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
                    $("#id_clientemov").val(data.cliente_id);
                    $("#lid_clientemov").val(data.cliente_nome);
                    var vl_cliente = data.cliente_id;
                    if (vl_cliente) {
                        var action = actionCorreta(window.location.href.toString(), "circuitos/unidadeCliente");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {id_cliente: vl_cliente},
                            beforeSend: function () {
                                $("#id_cliente_unidademov").val("");
                                $("#lid_cliente_unidademov").val("");
                                $("#lid_cliente_unidademov").attr("disabled", "true");
                                listUnidadeCliente = [];
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
                                        listUnidadeCliente.push({value: value.nome, data: value.id});
                                    });
                                    $("#lid_cliente_unidademov").removeAttr("disabled");
                                } else {
                                    $("#id_cliente_unidademov").val("");
                                    $("#lid_cliente_unidademov").val("");
                                    $("#lid_cliente_unidademov").attr("disabled", "true");
                                    swal("Atenção","Não existem unidade para esse cliente!","info");
                                }
                                //Autocomplete de Cliente Unidade
                                ac_cliente_unidade.autocomplete({
                                    lookup: listUnidadeCliente,
                                    noCache: true,
                                    minChars: 1,
                                    triggerSelectOnValidInput: false,
                                    showNoSuggestionNotice: true,
                                    noSuggestionNotice: "Não existem resultados para essa consulta!",
                                    onSelect: function (suggestion) {
                                        $("#id_cliente_unidademov").val(suggestion.data);
                                    }
                                });
                            }
                        });
                    } else {
                        $("#id_cliente_unidademov").val("");
                        $("#lid_cliente_unidademov").val("");
                        $("#lid_cliente_unidademov").attr("disabled", "true");
                    }
                }
            });

            $(".cliente").show();
            $(".ucliente").show();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $(".cidade_digital").hide();
            $(".conectividade").hide();
            $("#lid_clientemov").attr("disabled", "true");
            $("#lid_cliente_unidademov").removeAttr("disabled");
            $("#salvaCircuitosmov").removeAttr("disabled");
            break;
        case "11"://Alteração de Cidade Digital

            var ac_cidadedigital = $("#lid_cidadedigitalmov");
            var listCidadeDigital = [];
            var ac_conectividade = $("#lid_conectividademov");
            var listConectividade = [];
            var action = actionCorreta(window.location.href.toString(), "circuitos/cidadedigitalAll");
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: action,
                beforeSend: function () {
                    $("#id_cidadedigitalmov").val("");
                    $("#lid_cidadedigitalmov").val("");
                    listCidadeDigital = [];
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
                            listCidadeDigital.push({value: value.descricao, data: value.id});
                        });
                    } else {
                        $("#id_cidadedigitalmov").val("");
                        $("#lid_cidadedigitalmov").val("");
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
                            $("#id_cidadedigitalmov").val(suggestion.data);
                            var vl_cidadedigital = $("#lid_cidadedigitalmov").val();
                            if (vl_cidadedigital) {
                                var id_cidadedigital = suggestion.data;
                                var action = actionCorreta(window.location.href.toString(), "circuitos/cidadedigitalConectividade");
                                $.ajax({
                                    type: "GET",
                                    dataType: "JSON",
                                    url: action,
                                    data: {id_cidadedigital: id_cidadedigital},
                                    beforeSend: function () {
                                        $("#id_conectividademov").val("");
                                        $("#lid_conectividademov").val("");
                                        $("#lid_conectividademov").attr("disabled", "true");
                                        listConectividade = [];
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
                                                listConectividade.push({value: value.tipo + " " + value.descricao, data: value.id});
                                            });
                                            $("#lid_conectividademov").removeAttr("disabled");
                                        } else {
                                            $("#id_conectividademov").val("");
                                            $("#lid_conectividademov").val("");
                                            $("#lid_conectividademov").attr("disabled", "true");
                                            swal("Atenção","Não existem conectividades para essa cidade digital!","info");
                                        }
                                        //Autocomplete de Equipamento
                                        ac_conectividade.autocomplete({
                                            lookup: listConectividade,
                                            noCache: true,
                                            minChars: 1,
                                            triggerSelectOnValidInput: false,
                                            showNoSuggestionNotice: true,
                                            noSuggestionNotice: "Não existem resultados para essa consulta!",
                                            onSelect: function (suggestion) {
                                                $("#id_conectividademov").val(suggestion.data);
                                            }
                                        });
                                    }
                                });
                            } else {
                                $("#id_conectividademov").val("");
                                $("#lid_conectividademov").val("");
                                $("#lid_conectividademov").attr("disabled", "true");
                            }
                        }
                    });
                }
            });

            $(".cidade_digital").show();
            $(".conectividade").show();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $(".cliente").hide();
            $(".ucliente").hide();
            $("#lid_conectividademov").attr("disabled", "true");
            $("#lid_cidadedigitalmov").removeAttr("disabled");
            $("#salvaCircuitosmov").removeAttr("disabled");
            break;
        case "12"://Alteração de Conectividade

            ac_conectividade = $("#lid_conectividademov");
            listConectividade = [];
            action = actionCorreta(window.location.href.toString(), "circuitos/getCidadeDigitalCircuito");
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: action,
                data: {id_circuito: id_circuito},
                beforeSend: function () {
                    $("#id_cidadedigitalmov").val("");
                    $("#lid_cidadedigitalmov").val("");
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
                    $("#lid_cidadedigitalmov").val(data.cidade_digital_nome);
                    $("#id_cidadedigitalmov").val(data.cidade_digital_id);
                    var vl_cidadedigital = data.cidade_digital_id;
                    if (vl_cidadedigital) {
                        var action = actionCorreta(window.location.href.toString(), "circuitos/cidadedigitalConectividade");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {id_cidadedigital: vl_cidadedigital},
                            beforeSend: function () {
                                $("#id_conectividademov").val("");
                                $("#lid_conectividademov").val("");
                                $("#lid_conectividademov").attr("disabled", "true");
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
                                        listConectividade.push({value: value.tipo + " " + value.descricao, data: value.id});
                                    });
                                    $("#lid_conectividademov").removeAttr("disabled");
                                } else {
                                    $("#id_conectividademov").val("");
                                    $("#lid_conectividademov").val("");
                                    $("#lid_conectividademov").attr("disabled", "true");
                                    swal("Atenção","Não existem conectividades para essa cidade digital!","info");
                                }
                                //Autocomplete de Equipamento
                                ac_conectividade.autocomplete({
                                    lookup: listConectividade,
                                    noCache: true,
                                    minChars: 1,
                                    triggerSelectOnValidInput: false,
                                    showNoSuggestionNotice: true,
                                    noSuggestionNotice: "Não existem resultados para essa consulta!",
                                    onSelect: function (suggestion) {
                                        $("#id_conectividademov").val(suggestion.data);
                                    }
                                });
                            }
                        });
                    } else {
                        $("#id_conectividademov").val("");
                        $("#lid_conectividademov").val("");
                        $("#lid_conectividademov").attr("disabled", "true");
                    }
                }
            });

            $(".cidade_digital").show();
            $(".conectividade").show();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $(".cliente").hide();
            $(".ucliente").hide();
            $("#lid_cidadedigitalmov").attr("disabled", "true");
            $("#lid_conectividademov").removeAttr("disabled");
            $("#salvaCircuitosmov").removeAttr("disabled");
            break;
        default:
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $(".cidade_digital").hide();
            $(".conectividade").hide();
            $(".cliente").hide();
            $(".ucliente").hide();
            $(".equip").hide();
            $("#salvaCircuitosmov").attr("disabled", "true");
            break;
    }
});

//Fabricante, Modelo e equipamento para o movimento de circuitos
$(".auto_fabricantemov").on("click", function(){
    "use strict";
    //Autocomplete de Fabricante
    var ac_fabricante = $("#lid_fabricantemov");
    var listFabricante = [];
    var ac_model = $("#lid_modelomov");
    var ac_equip = $("#lid_equipamentomov");
    var listEquip = [];
    var listModel = [];
    var action = actionCorreta(window.location.href.toString(), "circuitos/fabricanteAll");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        beforeSend: function () {
            $("#id_fabricantemov").val("");
            $("#lid_fabricantemov").val("");
            $("#id_modelomov").val("");
            $("#lid_modelomov").val("");
            $("#lid_modelomov").attr("disabled", "true");
            $("#lid_equipamentomov").val("");
            $("#id_equipamentomov").val("");
            $("#lid_equipamentomov").attr("disabled", "true");
            listFabricante = [];
            listModel = [];
            listEquip = [];
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
                $.each(data.dados, function (key, value) {
                    listFabricante.push({value: value.nome, data: value.id});
                });
            } else {
                $("#id_fabricantemov").val("");
                $("#lid_fabricantemov").val("");
            }
            //Autocomplete de Equipamento
            ac_fabricante.autocomplete({
                lookup: listFabricante,
                noCache: true,
                minChars: 1,
                triggerSelectOnValidInput: false,
                showNoSuggestionNotice: true,
                noSuggestionNotice: "Não existem resultados para essa consulta!",
                onSelect: function (suggestion) {
                    $("#id_fabricantemov").val(suggestion.data);
                    var vl_fabricante = $("#lid_fabricantemov").val();
                    if (vl_fabricante) {
                        var id_fabricante = suggestion.data;
                        var action = actionCorreta(window.location.href.toString(), "circuitos/modeloFabricante");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {id_fabricante: id_fabricante},
                            beforeSend: function () {
                                $("#id_modelomov").val("");
                                $("#lid_modelomov").val("");
                                $("#lid_modelomov").attr("disabled", "true");
                                $("#lid_equipamentomov").val("");
                                $("#id_equipamentomov").val("");
                                $("#lid_equipamentomov").attr("disabled", "true");
                                listModel = [];
                                listEquip = [];
                            },
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
                                if (data.operacao) {
                                    $.each(data.dados, function (key, value) {
                                        listModel.push({value: value.modelo, data: value.id});
                                    });
                                    $("#lid_modelomov").removeAttr("disabled");
                                    $("#id_equipamentomov").val("");
                                    $("#lid_equipamentomov").val("");
                                    $("#lid_equipamentomov").attr("disabled", "true");
                                } else {
                                    $("#id_modelomov").val("");
                                    $("#lid_modelomov").val("");
                                    $("#lid_modelomov").attr("disabled", "true");
                                    $("#id_equipamentomov").val("");
                                    $("#lid_equipamentomov").val("");
                                    $("#lid_equipamentomov").attr("disabled", "true");
                                    swal("Atenção", "Não existem modelos para esse fabricante!", "info");
                                }

                                //Autocomplete de Modelo
                                ac_model.autocomplete({
                                    lookup: listModel,
                                    noCache: true,
                                    minChars: 1,
                                    triggerSelectOnValidInput: false,
                                    showNoSuggestionNotice: true,
                                    noSuggestionNotice: "Não existem resultados para essa consulta!",
                                    onSelect: function (suggestion) {
                                        $("#id_modelomov").val(suggestion.data);
                                        var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoModelo");
                                        $.ajax({
                                            type: "GET",
                                            dataType: "JSON",
                                            url: action,
                                            data: {id_modelo: suggestion.data},
                                            beforeSend: function () {
                                                $("#lid_equipamentomov").val("");
                                                $("#id_equipamentomov").val("");
                                                $("#lid_equipamentomov").attr("disabled", "true");
                                                listEquip = [];
                                            },
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
                                                if (data.operacao) {
                                                    $.each(data.dados, function (key, value) {
                                                        var numserie = (value.numserie) ? value.numserie : "Sem Nº Série";
                                                        var numpatrimonio = (value.numpatrimonio) ? value.numpatrimonio : "Sem Nº Patrimônio";
                                                        listEquip.push({
                                                            value: value.nome + " (" + numserie + " / " + numpatrimonio + ")",
                                                            data: value.id
                                                        });
                                                    });
                                                    $("#lid_equipamentomov").removeAttr("disabled");
                                                } else {
                                                    $("#lid_equipamentomov").val("");
                                                    $("#id_equipamentomov").val("");
                                                    $("#lid_equipamentomov").attr("disabled", "true");
                                                    swal("Atenção", "Não existem equipamentos para este modelo!", "info");
                                                }

                                                //Autocomplete de Equipamento
                                                ac_equip.autocomplete({
                                                    lookup: listEquip,
                                                    noCache: true,
                                                    minChars: 1,
                                                    triggerSelectOnValidInput: false,
                                                    showNoSuggestionNotice: true,
                                                    noSuggestionNotice: "Não existem resultados para essa consulta!",
                                                    onSelect: function (suggestion) {
                                                        $("#id_equipamentomov").val(suggestion.data);
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    } else {
                        $("#id_modelomov").val("");
                        $("#lid_modelomov").val("");
                        $("#lid_modelomov").attr("disabled", "true");
                        $("#lid_equipamentomov").val("");
                        $("#id_equipamentomov").val("");
                        $("#lid_equipamentomov").attr("disabled", "true");
                    }
                }
            });
        }
    });
});

// $("#id_fabricantemov").on("change", function(){
//     "use strict";
//     var id_fabricante = $(this).val();
//     var action = actionCorreta(window.location.href.toString(), "circuitos/modeloFabricante");
//     $.ajax({
//         type: "GET",
//         dataType: "JSON",
//         url: action,
//         data: {id_fabricante: id_fabricante},
//         beforeSend: function () {
//             $(".remove_equipamentomov").remove();
//             $("#id_equipamentomov").val(null).selected = "true";
//             $("#id_equipamentomov").attr("disabled", "true");
//             $(".remove_modelomov").remove();
//             $("#id_modelomov").val(null).selected = "true";
//             $("#id_modelomov").attr("disabled", "true");
//         },
//         complete: function () {
//         },
//         error: function (data) {
//             if (data.status && data.status === 401)
//             {
//                 swal({
//                     title: "Erro de Permissão",
//                     text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                     type: "warning"
//                 });
//             }
//         },
//         success: function (data) {
//             if (data.operacao){
//                 $(".remove_modelomov").remove();
//                 $.each(data.dados, function (key, value) {
//                     var linhas = "<option class='remove_modelomov' value='" + value.id + "'>" + value.modelo + "</option>";
//                     $("#id_modelomov").append(linhas);
//                 });
//                 $("#id_modelomov").removeAttr("disabled");
//             } else {
//                 $(".remove_modelomov").remove();
//                 $("#id_modelomov").val(null).selected = "true";
//                 $("#id_modelomov").attr("disabled", "true");
//                 swal("Atenção","Não existem modelos para esse fabricante!","info");
//             }
//         }
//     });
// });
// $("#id_modelomov").on("change", function(){
//     "use strict";
//     var id_modelo = $(this).val();
//     var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoModelo");
//     $.ajax({
//         type: "GET",
//         dataType: "JSON",
//         url: action,
//         data: {id_modelo: id_modelo},
//         beforeSend: function () {
//         },
//         complete: function () {
//         },
//         error: function (data) {
//             if (data.status && data.status === 401)
//             {
//                 swal({
//                     title: "Erro de Permissão",
//                     text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                     type: "warning"
//                 });
//             }
//         },
//         success: function (data) {
//             if (data.operacao){
//                 $(".remove_equipamentomov").remove();
//                 $.each(data.dados, function (key, value) {
//                     var numserie = (value.numserie) ? value.numserie : "Sem Nº Série";
//                     var numpatrimonio = (value.numpatrimonio) ? value.numpatrimonio : "Sem Nº Patrimônio";
//                     var linhas = "<option class='remove_equipamento' value='" + value.id + "'>" + value.nome + " (" + numserie + " / " + numpatrimonio + ") </option>";
//                     $("#id_equipamentomov").append(linhas);
//                 });
//                 $("#id_equipamentomov").removeAttr("disabled");
//             } else {
//                 $(".remove_equipamentomov").remove();
//                 $("#id_equipamentomov").val(null).selected = "true";
//                 $("#id_equipamentomov").attr("disabled", "true");
//                 swal("Atenção","Não existem equipamentos para esse modelo!","info");
//             }
//         }
//     });
// });
//Validando o equipamento selecionados
$("#lid_equipamentomov").on("change", function(){
    "use strict";
    var id_equipamento = $("#id_equipamentomov").val();
    validaEquipamentoCircuito(id_equipamento).done(function(valida){
        if (valida) {
            $("#lid_fabricantemov").val(null);
            $("#lid_modelomov").val(null);
            $("#lid_equipamentomov").val(null);
            $("#id_fabricantemov").val(null);
            $("#id_modelomov").val(null);
            $("#id_equipamentomov").val(null);
            $("#lid_modelomov").attr("disabled", "true");
            $("#lid_equipamentomov").attr("disabled", "true");
            swal("Atenção","Esse equipamento já foi cadastrado para outro circuito!","info");
        }
    });
});
//Campo Número de Série
// $("#numero_seriemov").on("change", function () {
//     "use strict";
//     var numero_serie = $(this).val();
//     if (numero_serie !== ""){
//         var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoNumeroSerie");
//         $.ajax({
//             type: "GET",
//             dataType: "JSON",
//             url: action,
//             data: {numero_serie: numero_serie},
//             error: function (data) {
//                 if (data.status && data.status === 401)
//                 {
//                     swal({
//                         title: "Erro de Permissão",
//                         text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                         type: "warning"
//                     });
//                 }
//             },
//             success: function (data) {
//                 if (data.operacao){
//                     validaEquipamentoCircuito(data.id_equipamento).done(function(valida){
//                         if (valida) {
//                             $("#lid_fabricantemov").val(null);
//                             $("#lid_modelomov").val(null);
//                             $("#lid_equipamentomov").val(null);
//                             $("#id_fabricantemov").val(null);
//                             $("#id_modelomov").val(null);
//                             $("#id_equipamentomov").val(null);
//                             $("#lid_modelomov").attr("disabled", "true");
//                             $("#lid_equipamentomov").attr("disabled", "true");
//                             swal("Atenção","Esse equipamento já foi cadastrado para outro circuito!","info");
//                         } else {
//                             $("#lid_fabricantemov").val(data.nome_fabricante);
//                             $("#lid_modelomov").val(data.nome_modelo);
//                             $("#lid_equipamentomov").val(data.nome_equipamento + " (" + numero_serie + " / " + data.numero_patrimonio + ")");
//                             $("#id_fabricantemov").val(data.id_fabricante);
//                             $("#id_modelomov").val(data.id_modelo);
//                             $("#id_equipamentomov").val(data.id_equipamento);
//                             $("#lid_modelomov").removeAttr("disabled");
//                             $("#lid_equipamentomov").removeAttr("disabled");
//                         }
//                     });
//                 } else {
//                     $("#lid_fabricantemov").val(null);
//                     $("#lid_modelomov").val(null);
//                     $("#lid_equipamentomov").val(null);
//                     $("#id_fabricantemov").val(null);
//                     $("#id_modelomov").val(null);
//                     $("#id_equipamentomov").val(null);
//                     $("#lid_modelomov").attr("disabled", "true");
//                     $("#lid_equipamentomov").attr("disabled", "true");
//                     swal("Atenção","Não existem equipamentos para esse número de série!","info");
//                 }
//             }
//         });
//     }
// });

$(".auto_serie_patrimoniomov").on("click", function(){
    "use strict";
    //Autocomplete de Cidade Digital
    var ac_serie_patrimoniomov = $("#lnumero_seriemov");
    var listSeriePatrimoniomov = [];
    var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoSeriePatrimonio");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        beforeSend: function () {
            $("#numero_seriemov").val("");
            $("#lnumero_seriemov").val("");
            listSeriePatrimoniomov = [];
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
                $.each(data.dados, function (key, value) {
                    listSeriePatrimoniomov.push({value: value, data: value});
                });
            } else {
                $("#numero_seriemov").val("");
                $("#lnumero_seriemov").val("");
            }
            //Autocomplete de Equipamento
            ac_serie_patrimoniomov.autocomplete({
                lookup: listSeriePatrimoniomov,
                noCache: true,
                minChars: 1,
                triggerSelectOnValidInput: false,
                showNoSuggestionNotice: true,
                noSuggestionNotice: "Não existem resultados para essa consulta!",
                onSelect: function (suggestion) {
                    $("#numero_seriemov").val(suggestion.data);
                    var numero_serie = suggestion.data;
                    if (numero_serie !== ""){
                        var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoNumeroSerie");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {numero_serie: numero_serie},
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
                                    validaEquipamentoCircuito(data.id_equipamento).done(function(valida){
                                        if (valida) {
                                            $("#lid_fabricantemov").val(null);
                                            $("#lid_modelomov").val(null);
                                            $("#lid_equipamentomov").val(null);
                                            $("#id_fabricantemov").val(null);
                                            $("#id_modelomov").val(null);
                                            $("#id_equipamentomov").val(null);
                                            $("#lid_modelomov").attr("disabled", "true");
                                            $("#lid_equipamentomov").attr("disabled", "true");
                                            swal("Atenção","Esse equipamento já foi cadastrado para outro circuito!","info");
                                        } else {
                                            $("#lid_fabricantemov").val(data.nome_fabricante);
                                            $("#lid_modelomov").val(data.nome_modelo);
                                            $("#lid_equipamentomov").val(data.nome_equipamento + " (" + numero_serie + " / " + data.numero_patrimonio + ")");
                                            $("#id_fabricantemov").val(data.id_fabricante);
                                            $("#id_modelomov").val(data.id_modelo);
                                            $("#id_equipamentomov").val(data.id_equipamento);
                                            $("#lid_modelomov").removeAttr("disabled");
                                            $("#lid_equipamentomov").removeAttr("disabled");
                                        }
                                    });
                                } else {
                                    $("#lid_fabricantemov").val(null);
                                    $("#lid_modelomov").val(null);
                                    $("#lid_equipamentomov").val(null);
                                    $("#id_fabricantemov").val(null);
                                    $("#id_modelomov").val(null);
                                    $("#id_equipamentomov").val(null);
                                    $("#lid_modelomov").attr("disabled", "true");
                                    $("#lid_equipamentomov").attr("disabled", "true");
                                    swal("Atenção","Não existem equipamentos para esse número de série!","info");
                                }
                            }
                        });
                    }
                }
            });
        }
    });
});

// $(function () {
//     "use strict";
//     var listEquip2 = [];
//     var listModel2 = [];
//     $("#id_fabricantemov").on("change", function(){
//         var id_fabricante = $(this).val();
//         var action = actionCorreta(window.location.href.toString(), "circuitos/modeloFabricante");
//         $.ajax({
//             type: "GET",
//             dataType: "JSON",
//             url: action,
//             data: {id_fabricante: id_fabricante},
//             beforeSend: function () {
//             },
//             complete: function () {
//             },
//             error: function (data) {
//                 if (data.status && data.status === 401)
//                 {
//                     swal({
//                         title: "Erro de Permissão",
//                         text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                         type: "warning"
//                     });
//                 }
//             },
//             success: function (data) {
//                 if (data.operacao){
//                     $(".remove_modelo").remove();
//                     $.each(data.dados, function (key, value) {
//                         listModel2.push({ value: value.modelo, data: value.id });
//                     });
//                     $("#lid_modelomov").removeAttr("disabled");
//                     $("#lid_equipamentomov").val("");
//                     $("#id_equipamentomov").val("");
//                     $("#lid_equipamentomov").attr("disabled", "true");
//                 } else {
//                     $("#lid_modelomov").val("");
//                     $("#id_modelomov").val("");
//                     $("#lid_modelomov").attr("disabled", "true");
//                     $("#lid_equipamentomov").val("");
//                     $("#id_equipamentomov").val("");
//                     $("#lid_equipamentomov").attr("disabled", "true");
//                 }
//             }
//         });
//     });
//
//     //Autocomplete de Modelo
//     $("#lid_modelomov").autocomplete({
//         lookup: listModel2,
//         noCache: true,
//         minChars: 1,
//         showNoSuggestionNotice: true,
//         noSuggestionNotice: "Não existem resultados para essa consulta!",
//         onSelect: function (suggestion) {
//             $("#id_modelomov").val(suggestion.data);
//             var action = actionCorreta(window.location.href.toString(), "circuitos/equipamentoModelo");
//             $.ajax({
//                 type: "GET",
//                 dataType: "JSON",
//                 url: action,
//                 data: {id_modelo: suggestion.data},
//                 beforeSend: function () {
//                     $("#lid_equipamentomov").val("");
//                     $("#id_equipamentomov").val("");
//                     $("#lid_equipamentomov").attr("disabled", "true");
//                 },
//                 complete: function () {
//                 },
//                 error: function (data) {
//                     if (data.status && data.status === 401)
//                     {
//                         swal({
//                             title: "Erro de Permissão",
//                             text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
//                             type: "warning"
//                         });
//                     }
//                 },
//                 success: function (data) {
//                     if (data.operacao){
//                         $.each(data.dados, function (key, value) {
//                             var numserie = (value.numserie) ? value.numserie : "Sem Nº Série";
//                             var numpatrimonio = (value.numpatrimonio) ? value.numpatrimonio : "Sem Nº Patrimônio";
//                             listEquip2.push({value: value.nome + " (" + numserie + " / " + numpatrimonio + ")", data: value.id});
//                         });
//                         $("#lid_equipamentomov").removeAttr("disabled");
//                     } else {
//                         $("#lid_equipamentomov").val("");
//                         $("#id_equipamentomov").val("");
//                         $("#lid_equipamentomov").attr("disabled", "true");
//                         swal("Atenção","Não existem equipamentos para este modelo!","info");
//                     }
//                 }
//             });
//         }
//     });
//
//     //Autocomplete de Equipamento
//     $("#lid_equipamentomov").autocomplete({
//         lookup: listEquip2,
//         noCache: true,
//         minChars: 1,
//         showNoSuggestionNotice: true,
//         noSuggestionNotice: "Não existem resultados para essa consulta!",
//         onSelect: function (suggestion) {
//             $("#id_equipamentomov").val(suggestion.data);
//         }
//     });
// });

$(document).on("click", ".criar_mov", function(){
    "use strict";
    var id_tipomovimento = $("#id_tipomovimento").val();
    switch (id_tipomovimento)
    {
        case "4"://Alteração de Banda
            //Validação de formulário
            $("#formCircuitosmov").validate({
                rules : {
                    id_tipomovimento:{
                        required: true
                    },
                    bandamov:{
                        required: true
                    }
                },
                messages:{
                    id_tipomovimento:{
                        required:"É necessário informar um tipo de movimento"
                    },
                    bandamov:{
                        required:"É necessário informar a banda"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitosmov").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/movCircuitos");
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
                                    title: "Movimento de Circuito",
                                    text: "Movimento de Circuito concluído!",
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
                                    title: "Movimento de Circuito",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        case "5"://Mudança de Status do Circuito
            //Validação de formulário
            $("#formCircuitosmov").validate({
                rules : {
                    id_tipomovimento:{
                        required: true
                    },
                    id_statusmov:{
                        required: true
                    }
                },
                messages:{
                    id_tipomovimento:{
                        required:"É necessário informar um tipo de movimento"
                    },
                    id_statusmov:{
                        required:"É necessário informar um status"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitosmov").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/movCircuitos");
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
                                    title: "Movimento de Circuito",
                                    text: "Movimento de Circuito concluído!",
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
                                    title: "Movimento de Circuito",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        case "6"://Alteração de IP Gerencial
            //Validação de formulário
            $("#formCircuitosmov").validate({
                rules : {
                    id_tipomovimento:{
                        required: true
                    },
                    ip_gerenciamov:{
                        required: true
                    }
                },
                messages:{
                    id_tipomovimento:{
                        required:"É necessário informar um tipo de movimento"
                    },
                    ip_gerenciamov:{
                        required:"É necessário informar um IP Gerencial"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitosmov").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/movCircuitos");
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
                                    title: "Movimento de Circuito",
                                    text: "Movimento de Circuito concluído!",
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
                                    title: "Movimento de Circuito",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        case "7"://Alteração de IP Rede Cliente
            //Validação de formulário
            $("#formCircuitosmov").validate({
                rules : {
                    id_tipomovimento:{
                        required: true
                    },
                    ip_redelocalmov:{
                        required: true
                    }
                },
                messages:{
                    id_tipomovimento:{
                        required:"É necessário informar um tipo de movimento"
                    },
                    ip_redelocalmov:{
                        required:"É necessário informar um IP Local"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitosmov").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/movCircuitos");
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
                                    title: "Movimento de Circuito",
                                    text: "Movimento de Circuito concluído!",
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
                                    title: "Movimento de Circuito",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        case "8"://Alteração de Equipamento
            //Validação de formulário
            $("#formCircuitosmov").validate({
                rules : {
                    id_tipomovimento:{
                        required: true
                    },
                    id_fabricantemov:{
                        required: true
                    },
                    id_modelomov:{
                        required: true
                    },
                    id_equipamentomov:{
                        required: true
                    }
                },
                messages:{
                    id_tipomovimento:{
                        required:"É necessário informar um tipo de movimento"
                    },
                    id_fabricantemov:{
                        required:"É necessário informar um fabricante"
                    },
                    id_modelomov:{
                        required:"É necessário informar um modelo"
                    },
                    id_equipamentomov:{
                        required:"É necessário informar um equipamento"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitosmov").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/movCircuitos");
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
                                    title: "Movimento de Circuito",
                                    text: "Movimento de Circuito concluído!",
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
                                    title: "Movimento de Circuito",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        case "9"://Alteração de Cliente / Unidade
            //Validação de formulário
            $("#formCircuitosmov").validate({
                rules : {
                    id_tipomovimento:{
                        required: true
                    },
                    lid_clientemov:{
                        required: true
                    },
                    lid_cliente_unidademov:{
                        required: true
                    }
                },
                messages:{
                    id_tipomovimento:{
                        required:"É necessário informar um tipo de movimento"
                    },
                    lid_clientemov:{
                        required:"É necessário informar um cliente"
                    },
                    lid_cliente_unidademov:{
                        required:"É necessário informar uma unidade de cliente"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitosmov").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/movCircuitos");
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
                                    title: "Movimento de Circuito",
                                    text: "Movimento de Circuito concluído!",
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
                                    title: "Movimento de Circuito",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        case "10"://Alteração de Unidade de Cliente
            //Validação de formulário
            $("#formCircuitosmov").validate({
                rules : {
                    id_tipomovimento:{
                        required: true
                    },
                    lid_cliente_unidademov:{
                        required: true
                    }
                },
                messages:{
                    id_tipomovimento:{
                        required:"É necessário informar um tipo de movimento"
                    },
                    lid_cliente_unidademov:{
                        required:"É necessário informar uma unidade de cliente"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitosmov").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/movCircuitos");
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
                                    title: "Movimento de Circuito",
                                    text: "Movimento de Circuito concluído!",
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
                                    title: "Movimento de Circuito",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        case "11"://Alteração de Cidade Digital / Conectividade
            //Validação de formulário
            $("#formCircuitosmov").validate({
                rules : {
                    id_tipomovimento:{
                        required: true
                    },
                    lid_cidadedigitalmov:{
                        required: true
                    },
                    lid_conectividademov:{
                        required: true
                    }
                },
                messages:{
                    id_tipomovimento:{
                        required:"É necessário informar um tipo de movimento"
                    },
                    lid_cidadedigitalmov:{
                        required:"É necessário informar uma cidade digital"
                    },
                    lid_conectividademov:{
                        required:"É necessário informar uma conectividade"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitosmov").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/movCircuitos");
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
                                    title: "Movimento de Circuito",
                                    text: "Movimento de Circuito concluído!",
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
                                    title: "Movimento de Circuito",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
        case "12"://Alteração de Conectividade
            //Validação de formulário
            $("#formCircuitosmov").validate({
                rules : {
                    id_tipomovimento:{
                        required: true
                    },
                    lid_conectividademov:{
                        required: true
                    }
                },
                messages:{
                    id_tipomovimento:{
                        required:"É necessário informar um tipo de movimento"
                    },
                    lid_conectividademov:{
                        required:"É necessário informar uma conectividade"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCircuitosmov").serialize();
                    var action = actionCorreta(window.location.href.toString(), "circuitos/movCircuitos");
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
                                    title: "Movimento de Circuito",
                                    text: "Movimento de Circuito concluído!",
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
                                    title: "Movimento de Circuito",
                                    text: data.mensagem,
                                    type: "error"
                                });
                            }
                        }
                    });
                }
            });
            break;
    }
});

$(".bt_del").on("click", function(){
    "use strict";
    var nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: "Tem certeza que deseja deletar múltipas unidades?",
            text: "O sistema irá deletar um total de " + nm_rows + " unidades com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "circuitos/deletarCircuitos");
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
                            text: "As unidades selecionadas foram deletadas com sucesso.",
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
    } else if (nm_rows === 0) {
        swal({
            title: "Deletar Circuitos",
            text: "Você precisa selecionar uma ou mais unidades para serem deletadas!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja deletar esta unidade?",
            text: "O sistema irá deletar a unidade selecionada com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "circuitos/deletarCircuitos");
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
                            text: "A unidade selecionada foi deletada com sucesso.",
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

$("#pdfCircuito").on("click", function () {
    "use strict";
    var id_circuito = $("#idv").val();
    var action = actionCorreta(window.location.href.toString(), "circuitos/pdfCircuito");
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: action,
        data: {id_circuito: id_circuito},
        beforeSend: function () {
            $.blockUI({ message: "<img src='" + URLImagensSistema + "/loader_gears.gif' width='50' height='50'/>      Aguarde um momento, estamos processando seu pedido...", baseZ: 2000 });
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
            window.open(data.url);
        }
    });
});


$(".bt_anexo").on("click", function(){
    "use strict";
    $("#modalanexoscircuitos").modal();
});
//jquery file upload example jsfiddle
// (https://github.com/stanislav-web/phalcon-uploader and https://forum.phalconphp.com/discussion/14401/how-to-use-blueimp-file-upload-with-phalcon and phalcon with jQuery File Upload)
$(".fileupload").fileupload({
    dataType: "JSON",
    dropZone: $("#dropzone"),
    add: function (e, data) {
        "use strict";
        var table= $("#fileTable");
        table.show();
        var tpl = $("<tr class='file'>" +
            "<td class='fname'></td>" +
            "<td class='fsize'></td>" +
            //Select de tipo
            "<td class='ftipo'>" +
            "<div class='form-group'>" +
            "<select name='id_tipo_anexo[]' id='id_tipo_anexo' class='form-control'>" +
            "<option value=''>Selecione</option>"+
            "</select>"+
            "</div>"+
            //Input de descrição
            "</td>" +
            "<td class='fdescricao'>" +
            "<div class='form-group'>" +
            "<input type='text' class='form-control' id='file_descricao' name='file_descricao[]'/>" +
            "</div>"+
            "</td>" +

            "<td class='fact'>" +
            "<a href='#' class='btn btn-warning rmvBtn'><i class='fi-ban'></i> Cancelar</a>" +
            // "<a href='#' class='btn btn-primary uplBtn'><i class='fi-cloud-upload'></i> Upload</a>" +
            "</td></tr>");
        tpl.find(".fname").text(data.files[0].name);
        tpl.find(".fsize").text(formatFileSize(data.files[0].size));
        data.context = tpl.appendTo("#fileList");

        $("#salvaAnexosCircuitos").click(function () {
            //fix this?
            data.submit();
        });
        $("#cancel").click(function () {
            data.submit().abort();
            tpl.fadeOut(function(){
                tpl.remove();
            });
            table.hide();
            $(".inputfile").val(null);
            $("label > span").html("Escolher Arquivos");
        });
        tpl.find(".rmvBtn").click(function(){
            if(tpl.hasClass("file")){
                data.submit().abort();
            }
            tpl.fadeOut(function(){
                tpl.remove();
            });
        });
        tpl.find(".uplBtn").click(function(){
            if(tpl.hasClass("file")){
                data.submit();
            }
            $(this).replaceWith("<p>Finalizado!</p>");
            tpl.find(".rmvBtn").hide();
            tpl.fadeOut(function(){
                tpl.remove();
            });
        });
        //var jqXHR = data.submit();
        //return false;
    },
    done: function (e, data) {
        "use strict";
        $("#result").val("Upload finalizado.");
    },
    error: function (jqXHR, textStatus, errorThrown) {
        "use strict";
        if (errorThrown === "abort") {
            $("#result").val("Upload de arquivo cancelado.");
        }
    }
});