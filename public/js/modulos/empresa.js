var table = $("#tb_empresa").DataTable({
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
        // {//Botão Ativar Registro
        //     className: "bt_ativo",
        //     text: "Ativar",
        //     name: "ativo", // do not change name
        //     titleAttr: "Ativar registro",
        //     action: function (e, dt, node, config) {
        //     },
        //     enabled: false

        // },
        // {//Botão Inativar Registro
        //     className: "bt_inativo",
        //     text: "Inativar",
        //     name: "inativo", // do not change name
        //     titleAttr: "Inativar registro",
        //     action: function (e, dt, node, config) {
        //     },
        //     enabled: false

        // },
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

table.buttons().container().appendTo("#tb_empresa_wrapper .col-md-6:eq(0)");

table.on( "select deselect", function () {
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows === 1 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
    table.button( 5 ).enable( selectedRows > 0 );
});

$("#cnpj").on("change", function(){
    var cnpj = $("#cnpj").val();
    var cnpj_form = formataCPFCNPJ(cnpj);
    var resultado = TestaCNPJ(cnpj_form);
    if (resultado === false) {
        swal({
            title: "Cadastro de Empresas",
            text: "O CNPJ digitado está incorreto, por favor, verifique o número digitado!",
            type: "warning"
        });
    }
    var action = actionCorreta(window.location.href.toString(), "core/validarCNPJ");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {cnpj: cnpj_form},
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
                    title: "Cadastro de Empresas",
                    text: "O CNPJ digitado já existe, por favor, verifique o cadastro!",
                    type: "warning"
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
    var action = actionCorreta(window.location.href.toString(), "core/validarEmail");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {email: email},
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
                    title: "E-mail de Usuários",
                    text: "O e-mail digitado já existe, por favor, escolha um novo!",
                    type: "warning"
                });
                $("#salvaUser").attr("disabled", "true");
            } else {
                $("#salvaUser").removeAttr("disabled", "true");
            }
        }
    });
});

$("#cep").on("change", function(){
    var cep_t = $("#cep").val();
    if (cep_t) {
        var cep = formata_cep(cep_t);
        var action = actionCorreta(window.location.href.toString(), "core/completaEndereco");
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: action,
            data: {cep: cep},
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
                    var logradouro = $("#endereco").val();
                    if (logradouro){
                        swal({
                            title: "Deseja substituir o endereço existente?",
                            text: "O sistema pode substituir o endereço atual pelo resultando que ele encontrou com base no CEP. O endereço é: " + data.endereco["logradouro"] + ", " + data.endereco["bairro"] + ", " + data.endereco["cidade"] + ".",
                            type: "info",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sim, vou substituir!",
                            cancelButtonText: "Cancelar"
                        }).then((result) => {
                            $("#bairro").val(null);
                            $("#cidade").val(null);
                            $("#endereco").val(null);
                            $("#sigla_uf").val(null);
                            $("#estado").val(null);
                            $("#bairro").val(data.endereco["bairro"]);
                            $("#cidade").val(data.endereco["cidade"]);
                            $("#endereco").val(data.endereco["logradouro"]);
                            $("#sigla_uf").val(data.endereco["sigla_uf"]);
                            $("#estado").val(data.endereco["uf"]);
                            $("#numero").focus();
                        });
                    }else{
                        $("#bairro").val(data.endereco["bairro"]);
                        $("#cidade").val(data.endereco["cidade"]);
                        $("#endereco").val(data.endereco["logradouro"]);
                        $("#sigla_uf").val(data.endereco["sigla_uf"]);
                        $("#estado").val(data.endereco["uf"]);
                        $("#numero").focus();
                    }
                } else {
                    swal({
                        title: "Atenção",
                        text: "O CEP digitado não retorno nenhum endereço válido! Por favor, insira o endereço de forma manual.",
                        type: "warning"
                    });
                }
            }
        });
    }
});

$(".bt_novo").on("click", function(){
    $("#modalempresa").modal();
    $("#formEmpresa input").removeAttr('readonly', 'readonly');
    $("#formEmpresa select").removeAttr('readonly', 'readonly');
    $("#formEmpresa textarea").removeAttr('readonly', 'readonly');
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
            setor:{
                required: true
            },
            esfera:{
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
            },
            cep:{
                required: true
            },
            endereco:{
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
            setor:{
                required: "É necessário informar um setor"
            },
            esfera:{
                required: "É necessário informar uma esfera"
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
            },
            cep:{
                required: "É necessário informar um cep"
            },
            endereco:{
                required: "É necessário informar um endereço"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formEmpresa").serialize();
            var action = actionCorreta(window.location.href.toString(), "empresa/criarEmpresa");
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
                            title: "Cadastro de Empresas",
                            text: "Cadastro de Empresas concluído!",
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
                            title: "Cadastro de Empresas",
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
$("#tb_empresa").on("click", "tr", function () {
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
    var id_empresa = ids[0];
    var action = actionCorreta(window.location.href.toString(), "empresa/formEmpresa");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_empresa: id_empresa},
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
            $("#formEmpresa input").removeAttr('readonly', 'readonly');
            $("#formEmpresa select").removeAttr('readonly', 'readonly');
            $("#formEmpresa textarea").removeAttr('readonly', 'readonly');
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
            $("#sigla_uf").val(data.dados.pessoaendereco.sigla_uf);
            $("#cep").val(data.dados.pessoaendereco.cep);
            $("#endereco").val(data.dados.pessoaendereco.endereco);
            $("#numero").val(data.dados.pessoaendereco.numero);
            $("#bairro").val(data.dados.pessoaendereco.bairro);
            $("#cidade").val(data.dados.pessoaendereco.cidade);
            $("#estado").val(data.dados.pessoaendereco.estado);
            $("#complemento").val(data.dados.pessoaendereco.complemento);
            $("#modalempresa").modal();
        }
    });
    $("#salvaEmpresa").removeClass("criar_empresa").addClass("editar_empresa");
});

$(".bt_visual").on("click", function(){
    var id_empresa = ids[0];
    var action = actionCorreta(window.location.href.toString(), "empresa/formEmpresa");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_empresa: id_empresa},
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
            $("#formEmpresa input").attr('readonly', 'readonly');
            $("#formEmpresa select").attr('readonly', 'readonly');
            $("#formEmpresa textarea").attr('readonly', 'readonly');
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
            $("#sigla_uf").val(data.dados.pessoaendereco.sigla_uf);
            $("#cep").val(data.dados.pessoaendereco.cep);
            $("#endereco").val(data.dados.pessoaendereco.endereco);
            $("#numero").val(data.dados.pessoaendereco.numero);
            $("#bairro").val(data.dados.pessoaendereco.bairro);
            $("#cidade").val(data.dados.pessoaendereco.cidade);
            $("#estado").val(data.dados.pessoaendereco.estado);
            $("#complemento").val(data.dados.pessoaendereco.complemento);
            $("#modalempresa").modal();
        }
    });
    $("#salvaEmpresa").removeClass("criar_empresa").addClass("editar_empresa");
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
            },
            cep:{
                required: true
            },
            endereco:{
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
            },
            cep:{
                required: "É necessário informar um cep"
            },
            endereco:{
                required: "É necessário informar um endereço"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formEmpresa").serialize();
            var action = actionCorreta(window.location.href.toString(), "empresa/editarEmpresa");
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
                            title: "Cadastro de Empresas",
                            text: "Edição de Empresas concluída!",
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
                            title: "Cadastro de Empresas",
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
            title: "Tem certeza que deseja deletar múltipos empresas?",
            text: "O sistema irá deletar um total de " + nm_rows + " empresas com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "empresa/deletarEmpresa");
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
                            text: "Os empresa selecionados foram deletados com sucesso.",
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
            title: "Deletar Empresas",
            text: "Você precisa selecionar um empresa ou mais empresas para serem deletados!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja deletar este empresa?",
            text: "O sistema irá deletar o empresa selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "empresa/deletarEmpresa");
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
                            text: "O empresa selecionado foi deletado com sucesso.",
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