var table = $('#tb_lov').DataTable({
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
        // {//Botão Ativar Registro
        //     className: 'bt_ativo',
        //     text: 'Ativar',
        //     name: 'ativo', // do not change name
        //     titleAttr: 'Ativar registro',
        //     action: function (e, dt, node, config) {
        //     },
        //     enabled: false

        // },
        // {//Botão Inativar Registro
        //     className: 'bt_inativo',
        //     text: 'Inativar',
        //     name: 'inativo', // do not change name
        //     titleAttr: 'Inativar registro',
        //     action: function (e, dt, node, config) {
        //     },
        //     enabled: false

        // },
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

table.buttons().container().appendTo('#tb_lov_wrapper .col-md-6:eq(0)');

table.on( 'select deselect', function () {
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows > 0 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
});

$(".bt_novo").on("click", function(){
    $("#modallov").modal();
    $("#salvarLov").removeClass("editar_lov").addClass("criar_lov");
});

$(document).on("click", ".criar_lov", function(){
    //Validação de formulário
    $("#formLov").validate({
        rules : {
            tipo:{
                required: true
            },
            descricao:{
                required: true
            }
        },
        messages:{
            tipo:{
                required:"É necessário informar um tipo"
            },
            descricao:{
                required:"É necessário informar uma descrição"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formLov").serialize();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: 'lov/criarLov',
                data: {
                    tokenKey: $('#token').attr('name'),
                    tokenValue: $('#token').attr('value'),
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
                            title: 'Cadastro de Valores',
                            text: 'Cadastro dos dados concluído!',
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
                            title: 'Cadastro de Valores',
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
$("#tb_lov").on("click", "tr", function () {
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
            title: 'Edição de Valores',
            text: 'Você somente pode editar um único valor! Selecione apenas um e tente novamente!',
            type: 'warning'
          });
    } else if (nm_rows == 0) {
        swal({
            title: 'Edição de Valores',
            text: 'Você precisa selecionar um registro para a edição!',
            type: 'warning'
          });
     } else {
        var id_lov = ids[0];
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: 'lov/formLov',
            data: {id_lov: id_lov},
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
                $("#tipo").val(data.dados.tipo).selected = "true";
                $("#descricao").val(data.dados.descricao);
                $("#codigoespecifico").val(data.dados.codigoespecifico);
                $("#duracao").val(data.dados.duracao);
                $("#valor").val(data.dados.valor);
                $("#modallov").modal();
            }
        });
        $("#salvarLov").removeClass("criar_lov").addClass("editar_lov");
    }

});

$(document).on("click", ".editar_lov", function(){
    //Validação de formulário
    $("#formLov").validate({
        rules : {
            tipo:{
                required: true
            },
            descricao:{
                required: true
            }
        },
        messages:{
            tipo:{
                required:"É necessário informar um tipo"
            },
            descricao:{
                required:"É necessário informar uma descrição"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formLov").serialize();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: 'lov/editarLov',
                data: {
                    tokenKey: $('#token').attr('name'),
                    tokenValue: $('#token').attr('value'),
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
                            title: 'Cadastro de Valores',
                            text: 'Edição de Valor concluída!',
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
                            title: 'Cadastro de Valores',
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
            title: 'Tem certeza que deseja deletar múltipos valores?',
            text: "O sistema irá deletar um total de " + nm_rows + " valores com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'lov/deletarLov',
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
                              title: 'Deletados!',
                              text: 'Os valores selecionados foram deletados com sucesso.',
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
            text: 'Você precisa selecionar um valor ou mais valores para serem deletados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja deletar este valor?',
            text: "O sistema irá deletar o valor selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'lov/deletarLov',
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
                              title: 'Deletado!',
                              text: 'O valor selecionado foi deletado com sucesso.',
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