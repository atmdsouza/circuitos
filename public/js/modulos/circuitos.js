var table = $('#tb_circuitos').DataTable({
    language: {
        sEmptyTable: "Nenhum registro encontrado",
        sInfo: "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        sInfoEmpty: "Mostrando 0 até 0 de 0 registros",
        sInfoFiltered: "(Filtrados de _MAX_ registros)",
        sInfoPostFix: "",
        sInfoThousands: ".",
        sLengthMenu: "Exibindo _MENU_ registros por página",
        sLoadingRecords: "Carregando...",
        sProcessing: "Processando...",
        sZeroRecords: "Nenhum registro encontrado",
        sSearch: "Pesquisar",
        oPaginate: {
            sNext: "Próximo",
            sPrevious: "Anterior",
            sFirst: "Primeiro",
            sLast: "Último"
        },
        oAria: {
            sSortAscending: ": Ordenar colunas de forma ascendente",
            sSortDescending: ": Ordenar colunas de forma descendente"
        },
        select: {
            rows: {
                _: "%d linhas selecionadas.",
                0: "Clique em uma ou mais linhas para selecioná-las.",
                1: "1 linha selecionada."
            }
        }
    },
    select: {
        style: 'multi'
    },
    responsive: false,
    search: {
        caseInsensitive: false
    },
    ordering: true,
    orderMulti: true,
    lengthChange: false,
    buttons: [
        {//Botão Novo Registro
            className: 'bt_novo',
            text: 'Novo',
            name: 'novo', // do not change name
            titleAttr: 'Novo Circuito',
            action: function (e, dt, node, config) {
            }
        },
        {//Botão Visualizar Registro
            className: 'bt_visual',
            text: 'Visualizar',
            name: 'visualizar', // do not change name
            titleAttr: 'Visualizar Circuito',
            action: function (e, dt, node, config) {
            },
            enabled: false
        },
        {//Botão Editar Registro
            className: 'bt_edit',
            text: 'Editar',
            name: 'edit', // do not change name
            titleAttr: 'Editar Circuito',
            action: function (e, dt, node, config) {
            },
            enabled: false
        },
        // {//Botão Inativar Registro
        //     className: 'bt_inativo',
        //     text: 'Inativar',
        //     name: 'inativo', // do not change name
        //     titleAttr: 'Inativar registro',
        //     action: function (e, dt, node, config) {
        //     },
        //     enabled: false
        // },
        {//Botão Movimentar Registro (Inativo)
            className: 'bt_mov',
            text: 'Movimentar',
            name: 'mov', // do not change name
            titleAttr: 'Movimentar Circuito',
            action: function (e, dt, node, config) {
            },
            enabled: false
        },
        {//Botão Deletar Registro (Inativo)
            className: 'bt_del',
            text: 'Deletar',
            name: 'del', // do not change name
            titleAttr: 'Deletar Circuito',
            action: function (e, dt, node, config) {
            },
            enabled: false
        },
        // {//Botão Selecionar
        //     extend: 'selectAll',
        //     text: 'Selecionar',
        //     titleAttr: 'Selecionar Todos os Registros'
        // },
        // {//Botão Limpar Seleção
        //     extend: 'selectNone',
        //     text: 'Limpar',
        //     titleAttr: 'Limpar Seleção dos Registros'
        // },
        // {//Botão imprimir
        //     extend: 'print',
        //     text: 'Imprimir',
        //     titleAttr: 'Imprimir'
        // },
        {//Botão exportar excel
            extend: 'excelHtml5',
            text: 'Excel',
            titleAttr: 'Exportar para Excel'
        },
        {//Botão exportar pdf
            extend: 'pdfHtml5',
            text: 'PDF',
            titleAttr: 'Exportar para PDF'
        }
    ]
});

table.buttons().container().appendTo('#tb_circuitos_wrapper .col-md-6:eq(0)');

table.on( 'select deselect', function () {
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
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'circuitos/unidadeCliente',
        data: {id_cliente: id_cliente},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
        },
        success: function (data) {
            $("#tipocliente").val(data.tipocliente);
            if (data.operacao){
                $(".remove_cliente").remove();
                $.each(data.dados, function (key, value) {
                    var linhas = "<option class='remove_cliente' value='" + value.id + "'>" + value.nome + "</option>";
                    $("#id_cliente_unidade").append(linhas);
                    $("#id_cliente_unidade").removeAttr("disabled");
                });
            } else {
                $(".remove_cliente").remove();
                $("#id_cliente_unidade").val(null).selected = "true";
                $("#id_cliente_unidade").attr("disabled", "true");
            }
        }
    });
});

$("#id_fabricante").on("change", function(){
    var id_fabricante = $(this).val();
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'circuitos/modeloFabricante',
        data: {id_fabricante: id_fabricante},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
        },
        success: function (data) {
            if (data.operacao){
                $(".remove_modelo").remove();
                $.each(data.dados, function (key, value) {
                    var linhas = "<option class='remove_modelo' value='" + value.id + "'>" + value.modelo + "</option>";
                    $("#id_modelo").append(linhas);
                    $("#id_modelo").removeAttr("disabled");
                    $(".remove_equip").remove();
                    $("#id_equipamento").val(null).selected = "true";
                    $("#id_equipamento").attr("disabled", "true");
                });
            } else {
                $(".remove_modelo").remove();
                $("#id_modelo").val(null).selected = "true";
                $("#id_modelo").attr("disabled", "true");
                $(".remove_equip").remove();
                $("#id_equipamento").val(null).selected = "true";
                $("#id_equipamento").attr("disabled", "true");
            }
        }
    });
});

$("#id_modelo").on("change", function(){
    var id_modelo = $(this).val();
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'circuitos/equipamentoModelo',
        data: {id_modelo: id_modelo},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
        },
        success: function (data) {
            if (data.operacao){
                $(".remove_equip").remove();
                $.each(data.dados, function (key, value) {
                    var numserie = (value.numserie) ? value.numserie : "Sem Nº Série";
                    var numpatrimonio = (value.numpatrimonio) ? value.numpatrimonio : "Sem Nº Patrimônio";
                    var linhas = "<option class='remove_equip' value='" + value.id + "'>" + value.nome + " (" + numserie + " / " + numpatrimonio + ")</option>";
                    $("#id_equipamento").append(linhas);
                    $("#id_equipamento").removeAttr("disabled");
                });
            } else {
                $(".remove_equip").remove();
                $("#id_equipamento").val(null).selected = "true";
                $("#id_equipamento").attr("disabled", "true");
            }
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
        case "44"://Pessoa Jurídica
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
                vlan:{
                    required: true
                },
                id_contrato:{
                    required: true
                },
                id_cluster:{
                    required: true
                },
                id_tipounidade:{
                    required: true
                },
                id_funcao:{
                    required: true
                },
                id_enlace:{
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
                vlan:{
                    required: "É necessário informar a VLAN"
                },
                id_contrato:{
                    required: "É necessário informar o tipo de Contrato"
                },
                id_cluster:{
                    required: "É necessário informar um Cluster"
                },
                id_tipounidade:{
                    required: "É necessário informar um tipo de Circuitos"
                },
                id_funcao:{
                    required: "É necessário informar uma Função"
                },
                id_enlace:{
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
                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: 'circuitos/criarCircuitos',
                    data: {
                        tokenKey: $('#token').attr('name'),
                        tokenValue: $('#token').attr('value'),
                        dados: dados
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.operacao){
                            swal({
                                title: 'Cadastro de Circuitos',
                                text: 'Cadastro da Circuitos concluído!',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                window.location.reload(true);
                              });
                        } else {
                            swal({
                                title: 'Cadastro de Circuitos',
                                text: data.mensagem,
                                type: 'error'
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
                vlan:{
                    required: true
                },
                id_contrato:{
                    required: true
                },
                id_cluster:{
                    required: true
                },
                id_tipounidade:{
                    required: true
                },
                id_funcao:{
                    required: true
                },
                id_enlace:{
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
                vlan:{
                    required: "É necessário informar a VLAN"
                },
                id_contrato:{
                    required: "É necessário informar o tipo de Contrato"
                },
                id_cluster:{
                    required: "É necessário informar um Cluster"
                },
                id_tipounidade:{
                    required: "É necessário informar um tipo de Circuitos"
                },
                id_funcao:{
                    required: "É necessário informar uma Função"
                },
                id_enlace:{
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
                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: 'circuitos/criarCircuitos',
                    data: {
                        tokenKey: $('#token').attr('name'),
                        tokenValue: $('#token').attr('value'),
                        dados: dados
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.operacao){
                            swal({
                                title: 'Cadastro de Circuitos',
                                text: 'Cadastro da Circuitos concluído!',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                window.location.reload(true);
                              });
                        } else {
                            swal({
                                title: 'Cadastro de Circuitos',
                                text: data.mensagem,
                                type: 'error'
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
    nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: 'Edição de Circuitos',
            text: 'Você somente pode editar um único circuitos! Selecione apenas um e tente novamente!',
            type: 'warning'
          });
    } else if (nm_rows == 0) {
        swal({
            title: 'Edição de Circuitos',
            text: 'Você precisa selecionar um circuitos para a edição!',
            type: 'warning'
          });
     } else {
        var id_circuitos = ids[0];
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: 'circuitos/formCircuitos',
            data: {id_circuitos: id_circuitos},
            beforeSend: function () {
            },
            complete: function () {
            },
            error: function () {
            },
            success: function (data) {
                $(".remove_unidade").remove();
                $.each(data.unidadescli, function (key, value) {
                    var linhas = "<option class='remove_unidade' value='" + value.id + "'>" + value.nome + "</option>";
                    $("#id_cliente_unidade").append(linhas);
                    $("#id_cliente_unidade").removeAttr("disabled");
                });
                $(".remove_modelo").remove();
                $.each(data.modelos, function (key, value) {
                    var linhas = "<option class='remove_modelo' value='" + value.id + "'>" + value.modelo + "</option>";
                    $("#id_modelo").append(linhas);
                });
                $(".remove_equipamento").remove();
                $.each(data.equipamentos, function (key, value) {
                    var linhas = "<option class='remove_equipamento' value='" + value.id + "'>" + value.nome + "</option>";
                    $("#id_equipamento").append(linhas);
                });
                $("#id").val(data.dados.id);
                $("#tipocliente").val(data.cliente.id_tipocliente);
                $("#id_cliente").val(data.dados.id_cliente).selected = "true";
                $("#id_cliente_unidade").val(data.dados.id_cliente_unidade).selected = "true";
                $("#id_fabricante").attr("disabled", "true");
                $("#id_fabricante").val(data.equip.id_fabricante).selected = "true";
                $("#id_modelo").attr("disabled", "true");
                $("#id_modelo").val(data.equip.id_modelo).selected = "true";
                $("#id_equipamento").attr("disabled", "true");
                $("#id_equipamento").val(data.dados.id_equipamento).selected = "true";
                $("#id_contrato").val(data.dados.id_contrato).selected = "true";
                $("#id_cluster").val(data.dados.id_cluster).selected = "true";
                $("#id_tipounidade").val(data.dados.id_tipounidade).selected = "true";
                $("#id_funcao").val(data.dados.id_funcao).selected = "true";
                $("#id_enlace").val(data.dados.id_enlace).selected = "true";
                $("#banda").attr("disabled", "true");
                $("#banda").val(data.dados.id_banda).selected = "true";
                $("#designacao").val(data.dados.designacao);
                $("#uf").val(data.dados.uf);
                $("#cidade").val(data.dados.cidade);
                $("#vlan").val(data.dados.vlan);
                $("#ccode").val(data.dados.ccode);
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
            title: 'Visualização de Circuitos',
            text: 'Você somente pode editar um único circuitos! Selecione apenas um e tente novamente!',
            type: 'warning'
          });
    } else if (nm_rows == 0) {
        swal({
            title: 'Visualização de Circuitos',
            text: 'Você precisa selecionar um circuitos para a edição!',
            type: 'warning'
          });
     } else {
        var id_circuitos = ids[0];
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: 'circuitos/visualizaCircuitos',
            data: {id_circuitos: id_circuitos},
            beforeSend: function () {
            },
            complete: function () {
            },
            error: function () {
            },
            success: function (data) {
                $("#idv").val(data.dados.id);
                $("#id_clientev").val(data.dados.id_cliente).selected = "true";
                $("#id_cliente_unidadev").val(data.dados.id_cliente_unidade).selected = "true";
                $("#id_fabricantev").val(data.equip.id_fabricante).selected = "true";
                $("#id_modelov").val(data.equip.id_modelo).selected = "true";
                $("#id_equipamentov").val(data.dados.id_equipamento).selected = "true";
                $("#id_contratov").val(data.dados.id_contrato).selected = "true";
                $("#id_statusv").val(data.dados.id_status).selected = "true";
                $("#id_clusterv").val(data.dados.id_cluster).selected = "true";
                $("#id_tipounidadev").val(data.dados.id_tipounidade).selected = "true";
                $("#id_funcaov").val(data.dados.id_funcao).selected = "true";
                $("#id_enlacev").val(data.dados.id_enlace).selected = "true";
                $("#bandav").val(data.dados.id_banda).selected = "true";
                $("#designacaov").val(data.dados.designacao);
                $("#ufv").val(data.dados.uf);
                $("#cidadev").val(data.dados.cidade);
                $("#vlanv").val(data.dados.vlan);
                $("#ccodev").val(data.dados.ccode);
                $("#ip_redelocalv").val(data.dados.ip_redelocal);
                $("#ip_gerenciav").val(data.dados.ip_gerencia);
                $("#tagv").val(data.dados.tag);
                $("#observacaov").val(data.dados.observacao);
                $("#dtativacaov").val(data.dados.data_ativacao);
                $("#dtatualizacaov").val(data.dados.data_atualizacao);
                $("#numpatserv").val(data.dados.numpatrimonio + " / " + data.dados.numserie);
                var linhas;
                if(data.mov)
                {
                    $(".rem_mov").remove();
                    $.each(data.mov, function (key, value) {
                        var os = value.osocomon ? value.osocomon : '';
                        var ant = value.valoranterior ? value.valoranterior : '';
                        var atu = value.valoratualizado ? value.valoratualizado : '';
                        var obs = value.observacao ? value.observacao : '';
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
                    linhas = "<tr>";
                    linhas = "<td colspan='7' style='text-align: center;'>Não existem dados para serem exibidos! Dados Importados!</td>";
                    linhas += "</tr>";
                    $("#tb_movimento").append(linhas);
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
        case "44"://Pessoa Jurídica
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
                vlan:{
                    required: true
                },
                id_contrato:{
                    required: true
                },
                id_cluster:{
                    required: true
                },
                id_tipounidade:{
                    required: true
                },
                id_funcao:{
                    required: true
                },
                id_enlace:{
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
                vlan:{
                    required: "É necessário informar a VLAN"
                },
                id_contrato:{
                    required: "É necessário informar o tipo de Contrato"
                },
                id_cluster:{
                    required: "É necessário informar um Cluster"
                },
                id_tipounidade:{
                    required: "É necessário informar um tipo de Circuitos"
                },
                id_funcao:{
                    required: "É necessário informar uma Função"
                },
                id_enlace:{
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
                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: 'circuitos/editarCircuitos',
                    data: {
                        tokenKey: $('#token').attr('name'),
                        tokenValue: $('#token').attr('value'),
                        dados: dados
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.operacao){
                            swal({
                                title: 'Cadastro de Circuitos',
                                text: 'Cadastro da Circuitos concluído!',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                window.location.reload(true);
                              });
                        } else {
                            swal({
                                title: 'Cadastro de Circuitos',
                                text: data.mensagem,
                                type: 'error'
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
                vlan:{
                    required: true
                },
                id_contrato:{
                    required: true
                },
                id_cluster:{
                    required: true
                },
                id_tipounidade:{
                    required: true
                },
                id_funcao:{
                    required: true
                },
                id_enlace:{
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
                vlan:{
                    required: "É necessário informar a VLAN"
                },
                id_contrato:{
                    required: "É necessário informar o tipo de Contrato"
                },
                id_cluster:{
                    required: "É necessário informar um Cluster"
                },
                id_tipounidade:{
                    required: "É necessário informar um tipo de Circuitos"
                },
                id_funcao:{
                    required: "É necessário informar uma Função"
                },
                id_enlace:{
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
                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: 'circuitos/editarCircuitos',
                    data: {
                        tokenKey: $('#token').attr('name'),
                        tokenValue: $('#token').attr('value'),
                        dados: dados
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.operacao){
                            swal({
                                title: 'Cadastro de Circuitos',
                                text: 'Cadastro da Circuitos concluído!',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                window.location.reload(true);
                              });
                        } else {
                            swal({
                                title: 'Cadastro de Circuitos',
                                text: data.mensagem,
                                type: 'error'
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
        case "61"://Alteração de Banda
            $("#bandamovdiv").show();
            $("#redelocalmovdiv").hide();
            $("#gerenciamovdiv").hide();
            $("#statusmovdiv").hide();
            $(".equip").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
        break;
        case "62"://Mudança de Status do Circuito
            $("#statusmovdiv").show();
            $("#gerenciamovdiv").hide();
            $("#redelocalmovdiv").hide();
            $("#bandamovdiv").hide();
            $(".equip").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
        break;
        case "63"://Alteração de IP Gerencial
            $("#gerenciamovdiv").show();
            $("#bandamovdiv").hide();
            $("#redelocalmovdiv").hide();
            $("#statusmovdiv").hide();
            $(".equip").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
        break;
        case "64"://Alteração de IP Local
            $("#redelocalmovdiv").show();
            $("#statusmovdiv").hide();
            $("#bandamovdiv").hide();
            $("#gerenciamovdiv").hide();
            $(".equip").hide();
            $("#salvaCircuitosmov").removeAttr("disabled");
        break;
        case "89"://Alteração de Equipamento
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

$("#id_fabricantemov").on("change", function(){
    var id_fabricante = $(this).val();
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'circuitos/modeloFabricante',
        data: {id_fabricante: id_fabricante},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
        },
        success: function (data) {
            if (data.operacao){
                $(".remove_modelo").remove();
                $.each(data.dados, function (key, value) {
                    var linhas = "<option class='remove_modelo' value='" + value.id + "'>" + value.modelo + "</option>";
                    $("#id_modelomov").append(linhas);
                    $("#id_modelomov").removeAttr("disabled");
                    $(".remove_equip").remove();
                    $("#id_equipamentomov").val(null).selected = "true";
                    $("#id_equipamentomov").attr("disabled", "true");
                });
            } else {
                $(".remove_modelo").remove();
                $("#id_modelomov").val(null).selected = "true";
                $("#id_modelomov").attr("disabled", "true");
                $(".remove_equip").remove();
                $("#id_equipamentomov").val(null).selected = "true";
                $("#id_equipamentomov").attr("disabled", "true");
            }
        }
    });
});

$("#id_modelomov").on("change", function(){
    var id_modelo = $(this).val();
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'circuitos/equipamentoModelo',
        data: {id_modelo: id_modelo},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
        },
        success: function (data) {
            if (data.operacao){
                $(".remove_equip").remove();
                $.each(data.dados, function (key, value) {
                    var numserie = (value.numserie) ? value.numserie : "Sem Nº Série";
                    var numpatrimonio = (value.numpatrimonio) ? value.numpatrimonio : "Sem Nº Patrimônio";
                    var linhas = "<option class='remove_equip' value='" + value.id + "'>" + value.nome + " (" + numserie + " / " + numpatrimonio + ")</option>";
                    $("#id_equipamentomov").append(linhas);
                    $("#id_equipamentomov").removeAttr("disabled");
                });
            } else {
                $(".remove_equip").remove();
                $("#id_equipamentomov").val(null).selected = "true";
                $("#id_equipamentomov").attr("disabled", "true");
            }
        }
    });
});

$(document).on("click", ".criar_mov", function(){
    var id_tipomovimento = $("#id_tipomovimento").val();
    switch (id_tipomovimento)
    {
        case "61"://Alteração de Banda
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
                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: 'circuitos/movCircuitos',
                    data: {
                        tokenKey: $('#token').attr('name'),
                        tokenValue: $('#token').attr('value'),
                        dados: dados
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.operacao){
                            swal({
                                title: 'Movimento de Circuito',
                                text: 'Movimento de Circuito concluído!',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                window.location.reload(true);
                              });
                        } else {
                            swal({
                                title: 'Movimento de Circuito',
                                text: data.mensagem,
                                type: 'error'
                            });
                        }
                    }
                });
            }
        });
        break;
        case "62"://Mudança de Status do Circuito
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
                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: 'circuitos/movCircuitos',
                    data: {
                        tokenKey: $('#token').attr('name'),
                        tokenValue: $('#token').attr('value'),
                        dados: dados
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.operacao){
                            swal({
                                title: 'Movimento de Circuito',
                                text: 'Movimento de Circuito concluído!',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                window.location.reload(true);
                              });
                        } else {
                            swal({
                                title: 'Movimento de Circuito',
                                text: data.mensagem,
                                type: 'error'
                            });
                        }
                    }
                });
            }
        });
        break;
        case "63"://Alteração de IP Gerencial
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
                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: 'circuitos/movCircuitos',
                    data: {
                        tokenKey: $('#token').attr('name'),
                        tokenValue: $('#token').attr('value'),
                        dados: dados
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.operacao){
                            swal({
                                title: 'Movimento de Circuito',
                                text: 'Movimento de Circuito concluído!',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                window.location.reload(true);
                              });
                        } else {
                            swal({
                                title: 'Movimento de Circuito',
                                text: data.mensagem,
                                type: 'error'
                            });
                        }
                    }
                });
            }
        });
        break;
        case "64"://Alteração de IP Local
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
                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: 'circuitos/movCircuitos',
                    data: {
                        tokenKey: $('#token').attr('name'),
                        tokenValue: $('#token').attr('value'),
                        dados: dados
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.operacao){
                            swal({
                                title: 'Movimento de Circuito',
                                text: 'Movimento de Circuito concluído!',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                window.location.reload(true);
                              });
                        } else {
                            swal({
                                title: 'Movimento de Circuito',
                                text: data.mensagem,
                                type: 'error'
                            });
                        }
                    }
                });
            }
        });
        break;
        case "89"://Alteração de Equipamento
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
                $.ajax({
                    type: 'POST',
                    dataType: 'JSON',
                    url: 'circuitos/movCircuitos',
                    data: {
                        tokenKey: $('#token').attr('name'),
                        tokenValue: $('#token').attr('value'),
                        dados: dados
                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.operacao){
                            swal({
                                title: 'Movimento de Circuito',
                                text: 'Movimento de Circuito concluído!',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                              }).then((result) => {
                                window.location.reload(true);
                              });
                        } else {
                            swal({
                                title: 'Movimento de Circuito',
                                text: data.mensagem,
                                type: 'error'
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
            title: 'Tem certeza que deseja deletar múltipas unidades?',
            text: "O sistema irá deletar um total de " + nm_rows + " unidades com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'circuitos/deletarCircuitos',
                  data: {ids: ids},
                  beforeSend: function () {
                  },
                  complete: function () {
                  },
                  error: function () {
                  },
                  success: function (data) {
                      if (data.operacao){
                          swal({
                              title: 'Deletados!',
                              text: 'As unidades selecionadas foram deletadas com sucesso.',
                              type: 'success',
                              showCancelButton: false,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Ok'
                            }).then((result) => {
                                window.location.reload(true);
                            });
                      } else {
                          swal({
                              title: 'Deletar',
                              text: data.mensagem,
                              type: 'error'
                          });
                      }
                  }
              });
        });
    } else if (nm_rows == 0) {
        swal({
            title: 'Deletar Circuitos',
            text: 'Você precisa selecionar uma ou mais unidades para serem deletadas!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja deletar esta unidade?',
            text: "O sistema irá deletar a unidade selecionada com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'circuitos/deletarCircuitos',
                  data: {ids: ids},
                  beforeSend: function () {
                  },
                  complete: function () {
                  },
                  error: function () {
                  },
                  success: function (data) {
                      if (data.operacao){
                          swal({
                              title: 'Deletado!',
                              text: 'A unidade selecionada foi deletada com sucesso.',
                              type: 'success',
                              showCancelButton: false,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Ok'
                            }).then((result) => {
                              window.location.reload(true);
                            });
                      } else {
                          swal({
                              title: 'Deletar',
                              text: data.mensagem,
                              type: 'error'
                          });
                      }
                  }
              });
        });
    }
});

$("#pdfCircuito").on("click", function () {
    var id_circuito = $("#idv").val();
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'circuitos/pdfCircuito',
        data: {id_circuito: id_circuito},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
            swal("Erro!", "Erro ao gerar o PDF com os dados do circuito!", "error");
        },
        success: function (data) {
            window.open(data.url);
        }
    });
});