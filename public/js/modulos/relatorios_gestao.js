//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos
function inicializar()
{

}

//Gerar o relatório Customizado
$("#gerar_relatorio").on("click", function(){
    var eixo_x = $("#eixo_x").val();
    var ordenar_campo = $("#ordenar_campo").val();
    var ordenar_sentido = $("#ordenar_sentido").val();

    var dados = $("#formFiltros").serialize();

    if (eixo_x.length !== 0)//Validação do campo de colunas do relatório
    {
        var action = actionCorreta(window.location.href.toString(), "relatorios_gestao/relatorioCustomizado");
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: action,
            data: {dados: dados, eixo_x: eixo_x, ordenar_campo: ordenar_campo, ordenar_sentido: ordenar_sentido},
            beforeSend: function () {
                $.blockUI({ message: "<img src='" + URLImagensSistema + "/loader_gears.gif' width='50' height='50'/>      Aguarde um momento, estamos processando seu pedido...", baseZ: 2000 });
            },
            complete: function () {
                // limparFiltros();
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
    }
    else
    {
        swal({
            title: "Atenção!!!",
            text: "Você precisa selecionar pelo menos 01 (um) campo para gerar um relatório! Tente novamente!",
            type: "warning"
        });
    }

});
//Controlando a visualização dos campos de filtro
$("#filtrar_relatorio").on("click", function () {
    var flag = $("#esconder").val();
    switch (flag)
    {
        case '0':
            $("#div_filtros_1").show();
            $("#esconder").val(1);
        break;
        case '1':
            $("#div_filtros_1").hide();
            $("#esconder").val(0);
        break;
    }
});

//Limpar campos do filtro
function limparFiltros() {
    $("#id_cliente").val(null).selected = "true";
    $("#id_cliente_unidade").val(null).selected = "true";
    $("#fieldDesignacao").val(null);
    $("#fieldDesignacaoAnterior").val(null);
    $("#id_cidadedigital").val(null).selected = "true";
    $("#id_conectividade").val(null).selected = "true";
    $("#id_contrato").val(null).selected = "true";
    $("#id_tipolink").val(null).selected = "true";
    $("#id_funcao").val(null).selected = "true";
    $("#id_tipoacesso").val(null).selected = "true";
    $("#id_fabricante").val(null).selected = "true";
    $("#lid_modelo").val(null);
    $("#lid_equipamento").val(null);
    $("#id_modelo").val(null);
    $("#id_equipamento").val(null);
    $("#fieldIpRedelocal").val(null);
    $("#fieldIpGerencia").val(null);
    $("#banda").val(null).selected = "true";
    $("#fieldTag").val(null);
    $("#fieldSsid").val(null);
    $("#fieldChamado").val(null);
    $("#id_status").val(null).selected = "true";
    $("#fieldDataAtivacao").val(null);
    $("#fieldDataAtualizacao").val(null);
    $("#uf").val(null).selected = "true";
    $("#cidade").val(null).selected = "true";
    $("#id_tipoesfera").val(null).selected = "true";
    $("#id_setor").val(null).selected = "true";
    //Inabilitando campos
    $("#id_cliente_unidade").attr("disabled", "true");
    $("#id_conectividade").attr("disabled", "true");
    $("#lid_modelo").attr("disabled", "true");
    $("#lid_equipamento").attr("disabled", "true");
    $("#cidade").attr("disabled", "true");
}
$("#limpar_filtros").on("click", function () {
    limparFiltros();
});
//Cliente e suas unidades
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
//Cidade Digital e suas conectividades
$("#id_cidadedigital").on("change", function(){
    var id_cidadedigital = $(this).val();
    var action = actionCorreta(window.location.href.toString(), "circuitos/cidadedigitalConectividade");
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
            if (data.operacao){
                $(".remove_conectividade").remove();
                $.each(data.dados, function (key, value) {
                    var linhas = "<option class='remove_conectividade' value='" + value.id + "'>" + value.tipo + " " + value.descricao + "</option>";
                    $("#id_conectividade").append(linhas);
                });
                $("#id_conectividade").removeAttr("disabled");
            } else {
                $(".remove_conectividade").remove();
                $("#id_conectividade").val(null).selected = "true";
                $("#id_conectividade").attr("disabled", "true");
                swal("Atenção","Não existem conectividades para esse cidade digital!","info");
            }
        }
    });
});
//Fabricante - Modelo - Equipamento
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
//Estado e suas cidades
$("#uf").on("change", function(){
    var uf = $(this).val();
    var action = actionCorreta(window.location.href.toString(), "core/listaCidades");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {uf: uf},
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
                $(".remove_cidade").remove();
                $.each(data.cidade, function (key, value) {
                    var linhas = "<option class='remove_cidade' value='" + value.cidade + "'>" + value.cidade + "</option>";
                    $("#cidade").append(linhas);
                });
                $("#cidade").removeAttr("disabled");
            } else {
                $(".remove_cidade").remove();
                $("#cidade").val(null).selected = "true";
                $("#cidade").attr("disabled", "true");
            }
        }
    });
});


