var table = $('#tb_equipamento').DataTable({
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
            titleAttr: 'Novo registro',
            action: function (e, dt, node, config) {
            }

        },
        {//Botão Editar Registro
            className: 'bt_edit',
            text: 'Editar',
            name: 'edit', // do not change name
            titleAttr: 'Editar registro',
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
        {//Botão Ativar Registro
            className: 'bt_ativo',
            text: 'Ativar',
            name: 'ativo', // do not change name
            titleAttr: 'Ativar registro',
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
        {//Botão Inativar Registro
            className: 'bt_inativo',
            text: 'Inativar',
            name: 'inativo', // do not change name
            titleAttr: 'Inativar registro',
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
        {//Botão Deletar Registro
            className: 'bt_del',
            text: 'Deletar',
            name: 'del', // do not change name
            titleAttr: 'Deletar registro',
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
        // {//Botão exportar excel
        //     extend: 'excelHtml5',
        //     text: 'XLSX',
        //     titleAttr: 'Exportar para Excel'
        // },
        // {//Botão exportar pdf
        //     extend: 'pdfHtml5',
        //     text: 'PDF',
        //     titleAttr: 'Exportar para PDF'
        // }
    ]
});

table.buttons().container().appendTo('#tb_equipamento_wrapper .col-md-6:eq(0)');

table.on( 'select deselect', function () {
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows > 0 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
});

$(".bt_novo").on("click", function(){
    $("#modalequipamento").modal();
    $("#salvarEquipamento").removeClass("editar_equipamento").addClass("criar_equipamento");
});

$("#id_fabricante").on("change", function(){
    var id_fabricante = $(this).val();
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'equipamento/carregaModelos',
        data: {id_fabricante: id_fabricante},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
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
                    title: 'Cadastro de Equipamentos',
                    text: data.mensagem,
                    type: 'error'
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
            equipamento:{
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
            equipamento:{
                required:"É necessário informar um equipamento"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formEquipamento").serialize();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: 'equipamento/criarEquipamento',
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
                            title: 'Cadastro de Equipamentos',
                            text: 'Cadastro do equipamento concluído!',
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
                            title: 'Cadastro de Equipamentos',
                            text: data.mensagem,
                            type: 'error'
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
    nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: 'Edição de Equipamentos',
            text: 'Você somente pode editar um único valor! Selecione apenas um e tente novamente!',
            type: 'warning'
          });
    } else if (nm_rows == 0) {
        swal({
            title: 'Edição de Equipamentos',
            text: 'Você precisa selecionar um registro para a edição!',
            type: 'warning'
          });
     } else {
        var id_equipamento = ids[0];
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: 'equipamento/formEquipamento',
            data: {id_equipamento: id_equipamento},
            beforeSend: function () {
            },
            complete: function () {
            },
            error: function () {
            },
            success: function (data) {
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: 'equipamento/carregaModelos',
                    data: {id_fabricante: data.dados.id_fabricante},
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    error: function () {
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
                                title: 'Cadastro de Equipamentos',
                                text: data.mensagem,
                                type: 'error'
                            });
                        }
                    }
                });
                $("#id").val(data.dados.id);
                $("#id_fabricante").val(data.dados.id_fabricante).selected = "true";
                $("#nome").val(data.dados.nome);
                $("#descricao").val(data.dados.descricao);
                $("#modalequipamento").modal();
            }
        });
        $("#salvarEquipamento").removeClass("criar_equipamento").addClass("editar_equipamento");
    }

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
            equipamento:{
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
            equipamento:{
                required:"É necessário informar um equipamento"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formEquipamento").serialize();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: 'equipamento/editarEquipamento',
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
                            title: 'Cadastro de Equipamentos',
                            text: 'Edição de equipamento concluída!',
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
                            title: 'Cadastro de Equipamentos',
                            text: data.mensagem,
                            type: 'error'
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
            title: 'Tem certeza que deseja deletar múltipos equipamentos?',
            text: "O sistema irá deletar um total de " + nm_rows + " equipamentos com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'equipamento/deletarEquipamento',
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
                              text: 'Os equipamentos selecionados foram deletados com sucesso.',
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
            title: 'Deletar Valor',
            text: 'Você precisa selecionar um valor ou mais equipamentos para serem deletados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja deletar este equipamento?',
            text: "O sistema irá deletar o equipamento selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'equipamento/deletarEquipamento',
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
                              text: 'O equipamento selecionado foi deletado com sucesso.',
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
            title: 'Tem certeza que deseja ativar múltipos equipamentos?',
            text: "O sistema irá ativar um total de " + nm_rows + " equipamentos com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, ativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'equipamento/ativarEquipamento',
                  data: {
                    tokenKey: $('#token').attr('name'),
                    tokenValue: $('#token').attr('value'),
                    ids: ids
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
                              title: 'Ativados!',
                              text: 'Os equipamentos selecionados foram ativados com sucesso.',
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
                              title: 'Ativar',
                              text: data.mensagem,
                              type: 'error'
                          });
                      }
                  }
              });
        });
    } else if (nm_rows == 0) {
        swal({
            title: 'Ativar Equipamentos',
            text: 'Você precisa selecionar um ou mais equipamentos para serem ativados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja ativar este equipamento?',
            text: "O sistema irá ativar o equipamento selecionado com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, ativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'equipamento/ativarEquipamento',
                  data: {
                    tokenKey: $('#token').attr('name'),
                    tokenValue: $('#token').attr('value'),
                    ids: ids
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
                              title: 'Ativado!',
                              text: 'O equipamento selecionado foi ativado com sucesso.',
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
                              title: 'Ativar',
                              text: data.mensagem,
                              type: 'error'
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
            title: 'Tem certeza que deseja inativar múltipos equipamentos?',
            text: "O sistema irá inativar um total de " + nm_rows + " equipamentos com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, inativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'equipamento/inativarEquipamento',
                  data: {
                    tokenKey: $('#token').attr('name'),
                    tokenValue: $('#token').attr('value'),
                    ids: ids
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
                              title: 'Inativadas!',
                              text: 'As equipamentos selecionados foram inativadas com sucesso.',
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
                              title: 'Inativar',
                              text: data.mensagem,
                              type: 'error'
                          });
                      }
                  }
              });
        });
    } else if (nm_rows == 0) {
        swal({
            title: 'Inativar Equipamento',
            text: 'Você precisa selecionar um ou mais equipamentos para serem inativados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja inativar este equipamento?',
            text: "O sistema irá inativar o equipamento selecionado com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, inativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'equipamento/inativarEquipamento',
                  data: {
                    tokenKey: $('#token').attr('name'),
                    tokenValue: $('#token').attr('value'),
                    ids: ids
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
                              title: 'Inativado!',
                              text: 'O equipamento selecionado foi inativado com sucesso.',
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
                              title: 'Inativar',
                              text: data.mensagem,
                              type: 'error'
                          });
                      }
                  }
              });
        });
    }
});