//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Datatable
var table = $("#tb_circuitos").DataTable({
    buttons: [
        {//Botão Novo Registro
            className: "bt_novo auto_cidadedigital",
            text: "Novo",
            name: "novo", // do not change name
            titleAttr: "Novo Circuito",
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
            className: "bt_edit auto_cidadedigital",
            text: "Editar",
            name: "edit", // do not change name
            titleAttr: "Editar Circuito",
            action: function (e, dt, node, config) {
            },
            enabled: false
        },
        // {//Botão Inativar Registro
        //     className: "bt_inativo",
        //     text: "Inativar",
        //     name: "inativo", // do not change name
        //     titleAttr: "Inativar registro",
        //     action: function (e, dt, node, config) {
        //     },
        //     enabled: false
        // },
        {//Botão Movimentar Registro (Inativo)
            className: "bt_mov",
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
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows === 1 );
    table.button( 3 ).enable( selectedRows === 1 );
    table.button( 4 ).enable( selectedRows > 0 );
});

function limparModal()
{
    $("#id").val(null);
}

$("#id_cliente").on("change", function(){
    var id_cliente = $(this).val();
    var action = actionCorreta(window.location.href.toString(), "circuitos/unidadeCliente");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_cliente: id_cliente},
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
            $("#tipocliente").val(data.tipocliente);
            if (data.operacao){
                $(".remove_cliente_unidade").remove();
                $.each(data.dados, function (key, value) {
                    var linhas = "<option class='remove_cliente_unidade' value='" + value.id + "'>" + value.nome + "</option>";
                    $("#id_cliente_unidade").append(linhas);
                });
                $("#id_cliente_unidade").removeAttr("disabled");
            } else {
                $(".remove_cliente_unidade").remove();
                $("#id_cliente_unidade").val(null).selected = "true";
                $("#id_cliente_unidade").attr("disabled", "true");
            }
        }
    });
});

$(".auto_cidadedigital").on("click", function(){
    //Autocomplete de Cidade Digital
    var ac_cidadedigital = $("#lid_cidadedigital");
    var listCidadeDigital = [];
    var action = actionCorreta(window.location.href.toString(), "circuitos/cidadedigitalAll");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        beforeSend: function () {
            $("#id_cidadedigital").val("");
            $("#lid_cidadedigital").val("");
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
        }
    });
    //Autocomplete de Equipamento
    ac_cidadedigital.autocomplete({
        lookup: listCidadeDigital,
        noCache: true,
        minChars: 1,
        showNoSuggestionNotice: true,
        noSuggestionNotice: "Não existem resultados para essa consulta!",
        onSelect: function (suggestion) {
            $("#id_cidadedigital").val(suggestion.data);
        }
    });
});

$(function () {
    "use strict";
    var ac_conectividade = $("#lid_conectividade");
    var listConectividade = [];
    $("#lid_cidadedigital").on("change", function(){
        var vl_cidadedigital = $("#lid_cidadedigital").val();
        if (vl_cidadedigital) {
            var id_cidadedigital = $("#id_cidadedigital").val();
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
                        swal("Atenção","Não existem modelos para esse fabricante!","info");
                    }
                }
            });
        } else {
            $("#id_conectividade").val("");
            $("#lid_conectividade").val("");
            $("#lid_conectividade").attr("disabled", "true");
        }
    });
    //Autocomplete de Equipamento
    ac_conectividade.autocomplete({
        lookup: listConectividade,
        noCache: true,
        minChars: 1,
        showNoSuggestionNotice: true,
        noSuggestionNotice: "Não existem resultados para essa consulta!",
        onSelect: function (suggestion) {
            $("#id_conectividade").val(suggestion.data);
        }
    });
});

$(function () {
    "use strict";
    var ac_model = $("#lid_modelo");
    var ac_equip = $("#lid_equipamento");
    var listEquip = [];
    var listModel = [];
    $("#id_fabricante").on("change", function(){
        var id_fabricante = $(this).val();
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
                    swal("Atenção","Não existem modelos para esse fabricante!","info");
                }
            }
        });
    });

    //Autocomplete de Modelo
    ac_model.autocomplete({
        lookup: listModel,
        noCache: true,
        minChars: 1,
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
                            var numserie = (value.numserie) ? value.numserie : "Sem Nº Série";
                            var numpatrimonio = (value.numpatrimonio) ? value.numpatrimonio : "Sem Nº Patrimônio";
                            listEquip.push({value: value.nome + " (" + numserie + " / " + numpatrimonio + ")", data: value.id});
                        });
                        $("#lid_equipamento").removeAttr("disabled");
                    } else {
                        $("#lid_equipamento").val("");
                        $("#id_equipamento").val("");
                        $("#lid_equipamento").attr("disabled", "true");
                        swal("Atenção","Não existem equipamentos para este modelo!","info");
                    }
                }
            });
        }
    });

    //Autocomplete de Equipamento
    ac_equip.autocomplete({
        lookup: listEquip,
        noCache: true,
        minChars: 1,
        showNoSuggestionNotice: true,
        noSuggestionNotice: "Não existem resultados para essa consulta!",
        onSelect: function (suggestion) {
            $("#id_equipamento").val(suggestion.data);
        }
    });
});

$(".bt_novo").on("click", function(){
    $("#modalcircuitos").modal();
    $("#salvaCircuitos").removeClass("editar_circuitos").addClass("criar_circuitos");
});

$(document).on("click", ".criar_circuitos", function(){
    var tipocliente = $("#tipocliente").val();
    switch (tipocliente)
    {
        case "43"://Pessoa Jurídica
        //Validação de formulário
        $("#formCircuitos").validate({
            rules : {
                id_cliente:{
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
                id_cidadedigital:{
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
                id_fabricante:{
                    required: true
                },
                id_modelo:{
                    required: true
                },
                id_equipamento:{
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
                id_cliente:{
                    required:"É necessário informar um Circuitos"
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
                id_cidadedigital:{
                    required: "É necessário informar a Cidade Digital"
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
                id_fabricante:{
                    required: "É necessário informar um Fabricante"
                },
                id_modelo:{
                    required: "É necessário informar um Modelo"
                },
                id_equipamento:{
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
                id_cliente:{
                    required: true
                },
                designacao:{
                    required: true
                },
                chamado:{
                    required: true
                },
                id_cidadedigital:{
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
                id_fabricante:{
                    required: true
                },
                id_modelo:{
                    required: true
                },
                id_equipamento:{
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
                id_cliente:{
                    required:"É necessário informar um Circuitos"
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
                id_cidadedigital:{
                    required: "É necessário informar a Cidade Digital"
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
                id_fabricante:{
                    required: "É necessário informar um Fabricante"
                },
                id_modelo:{
                    required: "É necessário informar um Modelo"
                },
                id_equipamento:{
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
    var valr = $(this)[0].cells[0].innerText;
    if (!ids.includes(valr)) {
        ids.push(valr);
    } else {
        var index = ids.indexOf(valr);
        ids.splice(index, 1);
    }
});

$(".bt_edit").on("click", function(){
    var nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: "Edição de Circuitos",
            text: "Você somente pode editar um único circuitos! Selecione apenas um e tente novamente!",
            type: "warning"
          });
    } else if (nm_rows == 0) {
        swal({
            title: "Edição de Circuitos",
            text: "Você precisa selecionar um circuitos para a edição!",
            type: "warning"
          });
     } else {
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
                var id_fabricante = (data.equip) ? data.equip.id_fabricante : null;
                var id_modelo = (data.equip) ? data.equip.id_modelo : null;
                $(".remove_cliente_unidade").remove();
                $.each(data.unidadescli, function (key, value) {
                    var linhas = "<option class='remove_cliente_unidade' value='" + value.id + "'>" + value.nome + "</option>";
                    $("#id_cliente_unidade").append(linhas);
                    $("#id_cliente_unidade").removeAttr("disabled");
                });
                $(".remove_modelo").remove();
                $.each(data.modelos, function (key, value) {
                    var linhas = "<option class='remove_modelo' value='" + value.id + "'>" + value.modelo + "</option>";
                    $("#id_modelo").append(linhas);
                });
                $("#id").val(data.dados.id);
                $("#tipocliente").val(data.cliente.id_tipocliente);
                $("#id_cliente").val(data.dados.id_cliente).selected = "true";
                $("#id_cliente_unidade").val(data.dados.id_cliente_unidade).selected = "true";
                $("#id_fabricante").attr("disabled", "true");
                $("#id_fabricante").val(id_fabricante).selected = "true";
                $("#lid_modelo").val(data.dados.desc_modelo);
                $("#id_modelo").attr("disabled", "true");
                $("#id_equipamento").attr("disabled", "true");
                $("#lid_equipamento").val(data.dados.desc_equip + " ("+ data.dados.nums_equip +" / "+ data.dados.patr_equip +")");
                $("#id_equipamento").val(data.dados.id_equipamento);
                $("#id_contrato").val(data.dados.id_contrato).selected = "true";
                $("#id_cluster").val(data.dados.id_cluster).selected = "true";
                $("#id_tipolink").val(data.dados.id_tipolink).selected = "true";
                $("#id_cidadedigital").val(data.dados.id_cidadedigital);
                $("#lid_cidadedigital").val(data.dados.lid_cidadedigital);
                $("#id_conectividade").val(data.dados.id_conectividade);
                $("#lid_conectividade").val(data.dados.lid_conectividade);
                $("#id_funcao").val(data.dados.id_funcao).selected = "true";
                $("#id_tipoacesso").val(data.dados.id_tipoacesso).selected = "true";
                $("#banda").attr("disabled", "true");
                $("#banda").val(data.dados.id_banda).selected = "true";
                $("#designacao").val(data.dados.designacao);
                $("#designacao_anterior").val(data.dados.designacao_anterior);
                $("#chamado").val(data.dados.chamado);
                $("#uf").val(data.dados.uf);
                $("#cidade").val(data.dados.cidade);
                $("#ssid").val(data.dados.cssidcode);
                $("#ip_redelocal").attr("disabled", "true");
                $("#ip_redelocal").val(data.dados.ip_redelocal);
                $("#ip_gerencia").attr("disabled", "true");
                $("#ip_gerencia").val(data.dados.ip_gerencia);
                $("#tag").val(data.dados.tag);
                $("#observacao").val(data.dados.observacao);
                $("#modalcircuitos").modal();
            }
        });
        $("#salvaCircuitos").removeClass("criar_circuitos").addClass("editar_circuitos");
    }
});

$(".bt_visual").on("click", function(){
    nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: "Visualização de Circuitos",
            text: "Você somente pode editar um único circuitos! Selecione apenas um e tente novamente!",
            type: "warning"
          });
    } else if (nm_rows == 0) {
        swal({
            title: "Visualização de Circuitos",
            text: "Você precisa selecionar um circuitos para a edição!",
            type: "warning"
          });
     } else {
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
                var id_fabricante = (data.equip) ? data.equip.id_fabricante : null;
                var id_modelo = (data.equip) ? data.equip.id_modelo : null;
                $("#idv").val(data.dados.id);
                $("#id_clientev").val(data.dados.id_cliente).selected = "true";
                $("#id_cliente_unidadev").val(data.dados.id_cliente_unidade).selected = "true";
                $("#id_fabricantev").val(id_fabricante).selected = "true";
                $("#id_modelov").val(id_modelo).selected = "true";
                $("#id_equipamentov").val(data.dados.id_equipamento).selected = "true";
                $("#id_contratov").val(data.dados.id_contrato).selected = "true";
                $("#id_statusv").val(data.dados.id_status).selected = "true";
                $("#id_clusterv").val(data.dados.id_cluster).selected = "true";
                $("#id_tipolinkv").val(data.dados.id_tipolink).selected = "true";
                $("#id_cidadedigitalv").val(data.dados.id_cidadedigital);
                $("#lid_cidadedigitalv").val(data.dados.lid_cidadedigital);
                $("#id_conectividadev").val(data.dados.id_conectividade);
                $("#lid_conectividadev").val(data.dados.lid_conectividade);
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
    }
});

$(document).on("click", ".editar_circuitos", function(){
    var tipocliente = $("#tipocliente").val();
    switch (tipocliente)
    {
        case "43"://Pessoa Jurídica
        //Validação de formulário
        $("#formCircuitos").validate({
            rules : {
                id_cliente:{
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
                id_cidadedigital:{
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
                id_fabricante:{
                    required: true
                },
                id_modelo:{
                    required: true
                },
                id_equipamento:{
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
                id_cliente:{
                    required:"É necessário informar um Circuitos"
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
                id_cidadedigital:{
                    required: "É necessário informar a Cidade Digital"
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
                id_fabricante:{
                    required: "É necessário informar um Fabricante"
                },
                id_modelo:{
                    required: "É necessário informar um Modelo"
                },
                id_equipamento:{
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
                id_cliente:{
                    required: true
                },
                designacao:{
                    required: true
                },
                chamado:{
                    required: true
                },
                id_cidadedigital:{
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
                id_fabricante:{
                    required: true
                },
                id_modelo:{
                    required: true
                },
                id_equipamento:{
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
                id_cliente:{
                    required:"É necessário informar um Circuitos"
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
                id_cidadedigital:{
                    required: "É necessário informar a Cidade Digital"
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
                id_fabricante:{
                    required: "É necessário informar um Fabricante"
                },
                id_modelo:{
                    required: "É necessário informar um Modelo"
                },
                id_equipamento:{
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
    $("#id_circuito").val(ids[0]);
    $("#modalcircuitosmov").modal();
    $("#salvaCircuitosmov").addClass("criar_mov");
});

$("#id_tipomovimento").on("change", function(){
    var id_tipomovimento = $("#id_tipomovimento").val();
    switch(id_tipomovimento)
    {
        case "63"://Alteração de Banda
            $("#bandamovdiv").show();
            $("#redelocalmovdiv").hide();
            $("#gerenciamovdiv").hide();
            $("#statusmovdiv").hide();
            $(".equip").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
        break;
        case "64"://Mudança de Status do Circuito
            $("#statusmovdiv").show();
            $("#gerenciamovdiv").hide();
            $("#redelocalmovdiv").hide();
            $("#bandamovdiv").hide();
            $(".equip").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
        break;
        case "65"://Alteração de IP Gerencial
            $("#gerenciamovdiv").show();
            $("#bandamovdiv").hide();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $(".equip").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
        break;
        case "66"://Alteração de IP Local
            $("#redelocalmovdiv").show();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $(".equip").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
        break;
        case "67"://Alteração de Equipamento
            $(".equip").show();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
        break;
        default:
            $(".equip").hide();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $("#salvaCircuitosmov").attr("disabled", "true");
        break;
    }
});


$(function () {
    "use strict";
    var listEquip2 = [];
    var listModel2 = [];
    $("#id_fabricantemov").on("change", function(){
        var id_fabricante = $(this).val();
        var action = actionCorreta(window.location.href.toString(), "circuitos/modeloFabricante");
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
                    $(".remove_modelo").remove();
                    $.each(data.dados, function (key, value) {
                        listModel2.push({ value: value.modelo, data: value.id });
                    });
                    $("#lid_modelomov").removeAttr("disabled");
                    $("#lid_equipamentomov").val("");
                    $("#id_equipamentomov").val("");
                    $("#lid_equipamentomov").attr("disabled", "true");
                } else {
                    $("#lid_modelomov").val("");
                    $("#id_modelomov").val("");
                    $("#lid_modelomov").attr("disabled", "true");
                    $("#lid_equipamentomov").val("");
                    $("#id_equipamentomov").val("");
                    $("#lid_equipamentomov").attr("disabled", "true");
                }
            }
        });
    });

    //Autocomplete de Modelo
    $("#lid_modelomov").autocomplete({
        lookup: listModel2,
        noCache: true,
        minChars: 1,
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
                            var numserie = (value.numserie) ? value.numserie : "Sem Nº Série";
                            var numpatrimonio = (value.numpatrimonio) ? value.numpatrimonio : "Sem Nº Patrimônio";
                            listEquip2.push({value: value.nome + " (" + numserie + " / " + numpatrimonio + ")", data: value.id});
                        });
                        $("#lid_equipamentomov").removeAttr("disabled");
                    } else {
                        $("#lid_equipamentomov").val("");
                        $("#id_equipamentomov").val("");
                        $("#lid_equipamentomov").attr("disabled", "true");
                        swal("Atenção","Não existem equipamentos para este modelo!","info");
                    }
                }
            });
        }
    });

    //Autocomplete de Equipamento
    $("#lid_equipamentomov").autocomplete({
        lookup: listEquip2,
        noCache: true,
        minChars: 1,
        showNoSuggestionNotice: true,
        noSuggestionNotice: "Não existem resultados para essa consulta!",
        onSelect: function (suggestion) {
            $("#id_equipamentomov").val(suggestion.data);
        }
    });
});

$(document).on("click", ".criar_mov", function(){
    var id_tipomovimento = $("#id_tipomovimento").val();
    switch (id_tipomovimento)
    {
        case "63"://Alteração de Banda
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
        case "64"://Mudança de Status do Circuito
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
        case "65"://Alteração de IP Gerencial
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
        case "66"://Alteração de IP Local
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
        case "67"://Alteração de Equipamento
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
    }
});

$(".bt_del").on("click", function(){
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
    } else if (nm_rows == 0) {
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