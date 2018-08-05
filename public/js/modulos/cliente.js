var table = $('#tb_cliente').DataTable({
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

table.buttons().container().appendTo('#tb_cliente_wrapper .col-md-6:eq(0)');

table.on( 'select deselect', function () {
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows > 0 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
});

$("#login").on("change", function(){
    var login = $("#login").val();
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'cliente/validarLogin',
        data: {login: login},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
        },
        success: function (data) {
            if (data.operacao){
                swal({
                    title: 'Login de Cliente',
                    text: 'O login digitado já existe, por favor, escolha um novo!',
                    type: 'warning'
                  });
                $("#salvaCliente").attr("disabled", "true");
            } else {
                $("#salvaCliente").removeAttr("disabled", "true");
            }
        }
    });    
});

$("#email").on("change", function(){
    var email = $("#email").val();
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'core/validarEmail',
        data: {email: email},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
        },
        success: function (data) {
            if (data.operacao){
                swal({
                    title: 'E-mail de Cliente',
                    text: 'O e-mail digitado já existe, por favor, escolha um novo!',
                    type: 'warning'
                  });
                $("#salvaCliente").attr("disabled", "true");
            } else {
                $("#salvaCliente").removeAttr("disabled", "true");
            }
        }
    });    
});

$(".bt_novo").on("click", function(){
    $("#modalcliente").modal();
    $("#salvaCliente").removeClass("editar_cliente").addClass("criar_cliente");
});

$(document).on("click", ".criar_cliente", function(){
    //Validação de formulário
    $("#formCliente").validate({
        rules : {
            nome_pessoa:{
                required: true
            },
            email:{
                required: true
            },
            login:{
                required: true
            },
            senha:{
                required: true,
                minlength: 6
            },
            roles_name:{
                required: true
            }
        },
        messages:{
            nome_pessoa:{
                required:"É necessário informar seu nome"
            },
            email:{
                required:"É necessário informar um email"
            },
            login:{
                required:"É necessário informar seu login"
            },
            roles_name:{
                required: "É necessário informar um perfil"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCliente").serialize();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: 'cliente/criarCliente',
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
                            title: 'Cadastro de Cliente',
                            text: 'Cadastro de Cliente concluído!',
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
                            title: 'Cadastro de Cliente',
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
$("#tb_cliente").on("click", "tr", function () {
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
            title: 'Edição de Cliente',
            text: 'Você somente pode editar um único cliente! Selecione apenas um e tente novamente!',
            type: 'warning'
          });
    } else if (nm_rows == 0) {
        swal({
            title: 'Edição de Cliente',
            text: 'Você precisa selecionar um cliente para a edição!',
            type: 'warning'
          });
     } else {
        var id_cliente = ids[0];
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: 'cliente/formCliente',
            data: {id_cliente: id_cliente},
            beforeSend: function () {
            },
            complete: function () {
            },
            error: function () {
            },
            success: function (data) {
                $("#id").val(data.dados.id);
                $("#nome_pessoa").val(data.dados.nome);
                $("#email").val(data.dados.email);
                $("#login").val(data.dados.login);
                $("#roles_name").val(data.dados.perfil).selected = "true";
                $("#senhas").hide();
                $("#reset_senha").show();
                $("#modalcliente").modal();
            }
        });
        $("#salvaCliente").removeClass("criar_cliente").addClass("editar_cliente");
    }

});

$(document).on("click", ".editar_cliente", function(){
    //Validação de formulário
    $("#formCliente").validate({
        rules : {
            nome_pessoa:{
                required: true
            },
            email:{
                required: true
            },
            login:{
                required: true
            },
            roles_name:{
                required: true
            }
        },
        messages:{
            nome_pessoa:{
                required:"É necessário informar seu nome"
            },
            email:{
                required:"É necessário informar um email"
            },
            login:{
                required:"É necessário informar seu login"
            },
            roles_name:{
                required: "É necessário informar um perfil"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCliente").serialize();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: 'cliente/editarCliente',
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
                            title: 'Cadastro de Cliente',
                            text: 'Edição de Cliente concluída!',
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
                            title: 'Cadastro de Cliente',
                            text: data.mensagem,
                            type: 'error'
                        });
                    }
                }
            });
        }
    });
});

$("#senha_reset").on("click", function(){
    swal({
        title: 'Tem certeza que deseja resetar a senha deste cliente?',
        text: "O sistema irá gerar uma senha aleatória e enviá-la para o cliente através de seu endereço de e-mail!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, resete!'
      }).then((result) => {
          var id = $("#id").val();
          $.ajax({
              type: 'POST',
              dataType: 'JSON',
              url: 'cliente/resetarSenha',
              data: {
                tokenKey: $('#token').attr('name'),
                tokenValue: $('#token').attr('value'),
                id: id
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
                          title: 'Resetada!',
                          text: 'A senha deste cliente foi resetada com sucesso.',
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
                          title: 'Reset de Senha',
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
            title: 'Tem certeza que deseja deletar múltipos clientes?',
            text: "O sistema irá deletar um total de " + nm_rows + " clientes com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'cliente/deletarCliente',
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
                              text: 'Os cliente selecionados foram deletados com sucesso.',
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
            title: 'Deletar Cliente',
            text: 'Você precisa selecionar um cliente ou mais clientes para serem deletados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja deletar este cliente?',
            text: "O sistema irá deletar o cliente selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'cliente/deletarCliente',
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
                              text: 'O cliente selecionado foi deletado com sucesso.',
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
            title: 'Tem certeza que deseja ativar múltipos clientes?',
            text: "O sistema irá ativar um total de " + nm_rows + " clientes com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, ativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'cliente/ativarCliente',
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
                              title: 'Ativados!',
                              text: 'Os cliente selecionados foram ativados com sucesso.',
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
            title: 'Ativar Cliente',
            text: 'Você precisa selecionar um cliente ou mais clientes para serem ativados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja ativar este cliente?',
            text: "O sistema irá ativar o cliente selecionado com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, ativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'cliente/ativarCliente',
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
                              title: 'Ativado!',
                              text: 'O cliente selecionado foi ativado com sucesso.',
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
            title: 'Tem certeza que deseja inativar múltipos clientes?',
            text: "O sistema irá inativar um total de " + nm_rows + " clientes com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, inativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'cliente/inativarCliente',
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
                              title: 'Inativados!',
                              text: 'Os cliente selecionados foram inativados com sucesso.',
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
            title: 'Inativar Cliente',
            text: 'Você precisa selecionar um cliente ou mais clientes para serem inativados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja inativar este cliente?',
            text: "O sistema irá inativar o cliente selecionado com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, inativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'cliente/inativarCliente',
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
                              title: 'Inativado!',
                              text: 'O cliente selecionado foi inativado com sucesso.',
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