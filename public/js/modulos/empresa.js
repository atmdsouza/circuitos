var table = $('#tb_empresa').DataTable({
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

table.buttons().container().appendTo('#tb_empresa_wrapper .col-md-6:eq(0)');

table.on( 'select deselect', function () {
    var selectedRows = table.rows( { selected: true } ).count();
    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows > 0 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
});

$("#cnpj").on("change", function(){
    var cnpj = $("#cnpj").val();
    var cnpj_form = formataCPFCNPJ(cnpj);
    var resultado = TestaCNPJ(cnpj_form);
    if (resultado === false) {
        swal({
            title: 'Cadastro de Empresas',
            text: 'O CNPJ digitado está incorreto, por favor, verifique o número digitado!',
            type: 'warning'
          });
    }
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'core/validarCNPJ',
        data: {cnpj: cnpj_form},
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
        },
        success: function (data) {
            if (data.operacao){
                swal({
                    title: 'Cadastro de Empresas',
                    text: 'O CNPJ digitado já existe, por favor, verifique o cadastro!',
                    type: 'warning'
                  });
                $("#salvaEmpresa").attr("disabled", "true");
            } else {
                $("#salvaEmpresa").removeAttr("disabled", "true");
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
                    title: 'E-mail de Usuários',
                    text: 'O e-mail digitado já existe, por favor, escolha um novo!',
                    type: 'warning'
                  });
                $("#salvaUser").attr("disabled", "true");
            } else {
                $("#salvaUser").removeAttr("disabled", "true");
            }
        }
    });    
});

$(".bt_novo").on("click", function(){
    $("#modalempresa").modal();
    $("#salvaEmpresa").removeClass("editar_empresa").addClass("criar_empresa");
});

$(document).on("click", ".criar_empresa", function(){
    //Validação de formulário
    $("#formEmpresa").validate({
        rules : {
            nome_pessoa:{
                required: true
            },
            razaosocial:{
                required: true
            },
            email:{
                required: true
            },
            mail_host:{
                required: true
            },
            mail_port:{
                required: true
            },
            mail_smtpssl:{
                required: true
            },
            mail_user:{
                required: true
            },
            mail_passwrd:{
                required: true
            }
        },
        messages:{
            nome_pessoa:{
                required:"É necessário informar um nome fantasia"
            },
            razaosocial:{
                required:"É necessário informar uma razão social"
            },
            email:{
                required:"É necessário informar um e-mail"
            },
            mail_host:{
                required:"É necessário informar o host de envio"
            },
            mail_port:{
                required: "É necessário informar a porta"
            },
            mail_smtpssl:{
                required: "É necessário informar o protocolo SSL"
            },
            mail_user:{
                required: "É necessário informar o usuário"
            },
            mail_passwrd:{
                required: "É necessário informar uma senha"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formEmpresa").serialize();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: 'empresa/criarEmpresa',
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
                            title: 'Cadastro de Empresas',
                            text: 'Cadastro de Empresas concluído!',
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
                            title: 'Cadastro de Empresas',
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
$("#tb_empresa").on("click", "tr", function () {
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
            title: 'Edição de Empresas',
            text: 'Você somente pode editar uma única empresa! Selecione apenas uma e tente novamente!',
            type: 'warning'
          });
    } else if (nm_rows == 0) {
        swal({
            title: 'Edição de Empresas',
            text: 'Você precisa selecionar uma empresa para a edição!',
            type: 'warning'
          });
     } else {
        var id_empresa = ids[0];
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: 'empresa/formEmpresa',
            data: {id_empresa: id_empresa},
            beforeSend: function () {
            },
            complete: function () {
            },
            error: function () {
            },
            success: function (data) {
                $("#id").val(data.dados.id);
                $("#setor").val(data.dados.setor).selected = "true";
                $("#esfera").val(data.dados.esfera).selected = "true";
                $("#nome_pessoa").val(data.dados.nome_pessoa);
                $("#razaosocial").val(data.dados.razaosocial);
                $("#cnpj").val(data.dados.cnpj);
                $("#inscestadual").val(data.dados.inscestadual);
                $("#inscmunicipal").val(data.dados.inscmunicipal);
                $("#fundacao").val(data.dados.fundacao);
                $("#email").val(data.dados.email);
                $("#mail_host").val(data.dados.mail_host);
                $("#mail_port").val(data.dados.mail_port);
                $("#mail_smtpssl").val(data.dados.mail_smtpssl);
                $("#mail_user").val(data.dados.mail_user);
                $("#mail_passwrd").val(data.dados.mail_passwrd);
                $("#modalempresa").modal();
            }
        });
        $("#salvaEmpresa").removeClass("criar_empresa").addClass("editar_empresa");
    }

});

$(document).on("click", ".editar_empresa", function(){
    //Validação de formulário
    $("#formEmpresa").validate({
        rules : {
            nome_pessoa:{
                required: true
            },
            razaosocial:{
                required: true
            },
            email:{
                required: true
            },
            mail_host:{
                required: true
            },
            mail_port:{
                required: true
            },
            mail_smtpssl:{
                required: true
            },
            mail_user:{
                required: true
            },
            mail_passwrd:{
                required: true
            }
        },
        messages:{
            nome_pessoa:{
                required:"É necessário informar um nome fantasia"
            },
            razaosocial:{
                required:"É necessário informar uma razão social"
            },
            email:{
                required:"É necessário informar um e-mail"
            },
            mail_host:{
                required:"É necessário informar o host de envio"
            },
            mail_port:{
                required: "É necessário informar a porta"
            },
            mail_smtpssl:{
                required: "É necessário informar o protocolo SSL"
            },
            mail_user:{
                required: "É necessário informar o usuário"
            },
            mail_passwrd:{
                required: "É necessário informar uma senha"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formEmpresa").serialize();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: 'empresa/editarEmpresa',
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
                            title: 'Cadastro de Empresas',
                            text: 'Edição de Empresas concluída!',
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
                            title: 'Cadastro de Empresas',
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
            title: 'Tem certeza que deseja deletar múltipos empresas?',
            text: "O sistema irá deletar um total de " + nm_rows + " empresas com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'empresa/deletarEmpresa',
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
                              text: 'Os empresa selecionados foram deletados com sucesso.',
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
            title: 'Deletar Empresas',
            text: 'Você precisa selecionar um empresa ou mais empresas para serem deletados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja deletar este empresa?',
            text: "O sistema irá deletar o empresa selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'empresa/deletarEmpresa',
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
                              text: 'O empresa selecionado foi deletado com sucesso.',
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