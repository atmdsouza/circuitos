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
                $("#id").val(data.dados.id);
                $("#cliente").val(data.dados.id_cliente).selected = "true";
                $("#nome_pessoa").val(data.dados.nome);
                $("#sigla").val(data.dados.sigla);
                $("#rzsocial").val(data.dados.razaosocial);
                $("#cnpj").val(data.dados.cnpj);
                $("#inscricaoestadual").val(data.dados.inscricaoestadual);
                $("#inscricaomunicipal").val(data.dados.inscricaomunicipal);
                $("#datafund").val(data.dados.datafund);
                $("#sigla_uf").val(data.dados.pessoaendereco.sigla_uf);
                $("#cep").val(data.dados.pessoaendereco.cep);
                $("#endereco").val(data.dados.pessoaendereco.endereco);
                $("#numero").val(data.dados.pessoaendereco.numero);
                $("#bairro").val(data.dados.pessoaendereco.bairro);
                $("#cidade").val(data.dados.pessoaendereco.cidade);
                $("#estado").val(data.dados.pessoaendereco.estado);
                $("#complemento").val(data.dados.pessoaendereco.complemento);
                $("#cliente").attr("disabled", "true");
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
                $("#id").val(data.dados.id);
                $("#cliente").val(data.dados.id_cliente).selected = "true";
                $("#nome_pessoa").val(data.dados.nome);
                $("#sigla").val(data.dados.sigla);
                $("#rzsocial").val(data.dados.razaosocial);
                $("#cnpj").val(data.dados.cnpj);
                $("#inscricaoestadual").val(data.dados.inscricaoestadual);
                $("#inscricaomunicipal").val(data.dados.inscricaomunicipal);
                $("#datafund").val(data.dados.datafund);
                $("#sigla_uf").val(data.dados.pessoaendereco.sigla_uf);
                $("#cep").val(data.dados.pessoaendereco.cep);
                $("#endereco").val(data.dados.pessoaendereco.endereco);
                $("#numero").val(data.dados.pessoaendereco.numero);
                $("#bairro").val(data.dados.pessoaendereco.bairro);
                $("#cidade").val(data.dados.pessoaendereco.cidade);
                $("#estado").val(data.dados.pessoaendereco.estado);
                $("#complemento").val(data.dados.pessoaendereco.complemento);
                $("#cliente").attr("disabled", "true");
                $("#modalvisualizar").modal();
            }
        });
        $("#salvaCircuitos").removeClass("criar_circuitos").addClass("editar_circuitos");
    }
});

$(document).on("click", ".editar_circuitos", function(){
    //Validação de formulário
    $("#formCircuitos").validate({
        rules : {
            nome_pessoa:{
                required: true
            },
            cliente:{
                required: true
            },
            cep:{
                required: true
            },
            endereco:{
                required: true
            },
            numero:{
                required: true
            },
            bairro:{
                required: true
            },
            cidade:{
                required: true
            },
            estado:{
                required: true
            }
        },
        messages:{
            nome_pessoa:{
                required:"É necessário informar o nome fantasia da unidade"
            },
            cliente:{
                required:"É necessário selecionar um cliente para a unidade"
            },
            cep:{
                required:"É necessário informar o CEP do endereço"
            },
            endereco:{
                required: "É necessário informar uma endereço"
            },
            numero:{
                required: "É necessário informar um número"
            },
            bairro:{
                required: "É necessário informar um bairro"
            },
            cidade:{
                required: "É necessário informar uma cidade"
            },
            estado:{
                required: "É necessário informar um estado"
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
});

$("#tb_telefone").on("click", ".del_tel", function(){
    var id = $(this).attr("id");
    swal({
        title: 'Tem certeza que deseja deletar este telefone?',
        text: "O sistema irá deletar o telefone selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, apagar!'
      }).then((result) => {
          $.ajax({
              type: 'POST',
              dataType: 'JSON',
              url: 'core/deletarPessoaTelefone',
              data: {id: id},
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
                          text: 'O telefone selecionado foi deletado com sucesso.',
                          type: 'success',
                          showCancelButton: false,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Ok'
                        }).then((result) => {
                            $("#trte" + id).remove();
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
});

$("#tb_email").on("click", ".del_eml", function(){
    var id = $(this).attr("id");
    swal({
        title: 'Tem certeza que deseja deletar este e-mail?',
        text: "O sistema irá deletar o e-mail selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, apagar!'
      }).then((result) => {
          $.ajax({
              type: 'POST',
              dataType: 'JSON',
              url: 'core/deletarPessoaEmail',
              data: {id: id},
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
                          text: 'O e-mail selecionado foi deletado com sucesso.',
                          type: 'success',
                          showCancelButton: false,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Ok'
                        }).then((result) => {
                            $("#trem" + id).remove();
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
});

$("#tb_contato").on("click", ".del_cto", function(){
    var id = $(this).attr("id");
    swal({
        title: 'Tem certeza que deseja deletar este contato?',
        text: "O sistema irá deletar o contato selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, apagar!'
      }).then((result) => {
          $.ajax({
              type: 'POST',
              dataType: 'JSON',
              url: 'core/deletarPessoaContato',
              data: {id: id},
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
                          text: 'O contato selecionado foi deletado com sucesso.',
                          type: 'success',
                          showCancelButton: false,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Ok'
                        }).then((result) => {
                            $("#trct" + id).remove();
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

$(".bt_ativo").on("click", function(){
    var nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: 'Tem certeza que deseja ativar múltipas unidades?',
            text: "O sistema irá ativar um total de " + nm_rows + " unidades com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, ativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'circuitos/ativarCircuitos',
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
                              title: 'Ativadas!',
                              text: 'As unidades selecionadas foram ativadas com sucesso.',
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
            title: 'Ativar Circuitoss',
            text: 'Você precisa selecionar uma ou mais unidades para serem ativadas!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja ativar esta unidade?',
            text: "O sistema irá ativar a unidade selecionada com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, ativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'circuitos/ativarCircuitos',
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
                              title: 'Ativada!',
                              text: 'A unidade selecionada foi ativada com sucesso.',
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
            title: 'Tem certeza que deseja inativar múltipos unidades?',
            text: "O sistema irá inativar um total de " + nm_rows + " unidades com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, inativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'circuitos/inativarCircuitos',
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
                              title: 'Inativadas!',
                              text: 'As unidades selecionados foram inativadas com sucesso.',
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
            title: 'Inativar Circuitos',
            text: 'Você precisa selecionar uma ou mais unidades para serem inativadas!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja inativar esta unidade?',
            text: "O sistema irá inativar a unidade selecionada com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, inativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'circuitos/inativarCircuitos',
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
                              title: 'Inativada!',
                              text: 'A unidade selecionada foi inativada com sucesso.',
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