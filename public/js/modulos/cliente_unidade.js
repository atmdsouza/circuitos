//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos
function inicializar()
{

}

var table = $("#tb_clienteunidades").DataTable({
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
        {//Botão Ativar Registro
            className: "bt_ativo",
            text: "Ativar",
            name: "ativo", // do not change name
            titleAttr: "Ativar registro",
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
        {//Botão Inativar Registro
            className: "bt_inativo",
            text: "Inativar",
            name: "inativo", // do not change name
            titleAttr: "Inativar registro",
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
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

table.buttons().container().appendTo("#tb_clienteunidades_wrapper .col-md-6:eq(0)");

table.on( "select deselect", function () {
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows === 1 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
    table.button( 5 ).enable( selectedRows > 0 );
});

function limpaContato()
{
    $("#principal_contato_t").val(null).selected = "true";
    $("#id_tipocontato_t").val(null).selected = "true";
    $("#nome_contato_t").val(null);
    $("#telefone_contato_t").val(null);
    $("#email_contato_t").val(null);
    $("#nome_contato_t").focus();
}

function limparModal()
{
    $(".tr_remove").remove();
    $("#tb_contato").hide();
    $("#tb_telefone").hide();
    $("#tb_email").hide();
    valCON = [];
    valTEL = [];
    valEML = [];
    $("#id").val(null);
    $("#cliente").removeAttr("disabled", "true");
}

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

//Limpar Linhas da Tabela
(function ($) {
    RemoveTableRow = function (handler) {
        var tr = $(handler).closest("tr");
        tr.fadeOut(400, function () {
            tr.remove();
        });
        return false;
    };
})(jQuery);

// var valCON = [];
$("#add_contato").on("click", function(){
    var id_tipocontato = $("#id_tipocontato_t").val();
    var id_tipocontato_desc = document.getElementById("id_tipocontato_t").options[document.getElementById("id_tipocontato_t").selectedIndex].text;
    var nome_contato = $("#nome_contato_t").val();
    var telefone_contato = $("#telefone_contato_t").val();
    var email_contato = $("#email_contato_t").val();
    var principal = $("#principal_contato_t").val();
    var eprincipal = principal == "1" ? "Sim" : "Não";

    // if ($.inArray(nome_contato, valCON) == -1) {
    //     valCON.push(nome_contato);
    if (id_tipocontato && nome_contato && principal !== "") {
        var linhas = null;
        linhas += "<tr class='tr_remove'>";
        linhas += "<td>"+ eprincipal +"<input name='principal_contato[]' type='hidden' value='"+ principal +"' /></td>";
        linhas += "<td>"+ id_tipocontato_desc +"<input name='tipo_contato[]' type='hidden' value='"+ id_tipocontato +"' /></td>";
        linhas += "<td>"+ nome_contato +"<input name='nome_contato[]' type='hidden' value='"+ nome_contato +"' /></td>";
        linhas += "<td>"+ telefone_contato +"<input name='telefone_contato[]' type='hidden' value='"+ telefone_contato +"' /></td>";
        linhas += "<td>"+ email_contato +"<input name='email_contato[]' type='hidden' value='"+ email_contato +"' /></td>";
        linhas += "<td><a href='#' onclick='RemoveTableRow(this)'><i class='fi-circle-cross'></i></a></td>";
        linhas += "</tr>";
        $("#tb_contato").append(linhas);
        $('#tb_contato').show();
        limpaContato();
    } else {
        swal({
            title: "Contato da Unidade",
            text: "Você precisa preencher corretamente os campos do contato!",
            type: "warning"
        });
    }
    // } else {
    //     swal({
    //         title: "Contato da Unidade",
    //         text: "Esse contato já existe na tabela abaixo!",
    //         type: "warning"
    //     });
    // }
});

// var valTEL = [];
$("#insert_telefone").on("click", function(){
    var telefone = $("#telefone_t").val();
    var principal = $("#princtel_t").val();
    var eprincipal = principal == "1" ? "Sim" : "Não";

    // if ($.inArray(telefone, valTEL) == -1) {
    //     valTEL.push(telefone);
    if (telefone && principal !== "") {
        var linhas = null;
        linhas += "<tr class='tr_remove'>";
        linhas += "<td>"+ eprincipal +"<input name='principal_tel[]' type='hidden' value='"+ principal +"' /></td>";
        linhas += "<td>"+ telefone +"<input name='telefone[]' type='hidden' value='"+ telefone +"' /></td>";
        linhas += "<td><a href='#' onclick='RemoveTableRow(this)'><i class='fi-circle-cross'></i></a></td>";
        linhas += "</tr>";
        $("#tb_telefone").append(linhas);
        $('#tb_telefone').show();
        $("#telefone_t").val(null);
        $("#princtel_t").val(null).selected = "true";
    } else {
        swal({
            title: "Contatos do Cliente",
            text: "Você precisa preencher corretamente os campos do telefone!",
            type: "warning"
        });
    }
    // } else {
    //     swal({
    //         title: "Endereço do Cliente",
    //         text: "Esse telefone já existe na tabela abaixo!",
    //         type: "warning"
    //     });
    // }
});

// var valEML = [];
$("#insert_email").on("click", function(){
    var email = $("#email_t").val();
    var principal = $("#princemail_t").val();
    var eprincipal = principal == "1" ? "Sim" : "Não";

    // if ($.inArray(email, valEML) == -1) {
    //     valEML.push(email);
    if (email && principal !== "") {
        var linhas = null;
        linhas += "<tr class='tr_remove'>";
        linhas += "<td>"+ eprincipal +"<input name='principal_email[]' type='hidden' value='"+ principal +"' /></td>";
        linhas += "<td>"+ email +"<input name='email_pf[]' type='hidden' value='"+ email +"' /></td>";
        linhas += "<td><a href='#' onclick='RemoveTableRow(this)'><i class='fi-circle-cross'></i></a></td>";
        linhas += "</tr>";
        $("#tb_email").append(linhas);
        $('#tb_email').show();
        $("#email_t").val(null);
        $("#princemail_t").val(null).selected = "true";
    } else {
        swal({
            title: "Contatos do Cliente",
            text: "Você precisa preencher corretamente os campos do e-mail!",
            type: "warning"
        });
    }
    // } else {
    //     swal({
    //         title: "Endereço do Cliente",
    //         text: "Esse e-mail já existe na tabela abaixo!",
    //         type: "warning"
    //     });
    // }
});

$(".email_val").on("change", function(){
    var email = $(this).val();
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
                    title: "E-mail de Cliente",
                    text: data.message,
                    type: "warning"
                });
                $(".email_val").val(null);
            }
        }
    });
});

$("#cnpj").on("change", function(){
    var cnpj = $("#cnpj").val();
    if (cnpj) {
        var cnpj_form = formataCPFCNPJ(cnpj);
        var resultado = TestaCNPJ(cnpj_form);
        if (resultado === false) {
            swal({
                title: "Cadastro de Clientes",
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
                        title: "Cadastro de Clientes",
                        text: "O CNPJ digitado já existe, por favor, verifique o cadastro!",
                        type: "warning"
                    });
                    $("#salvaClienteUnidade").attr("disabled", "true");
                } else {
                    $("#salvaClienteUnidade").removeAttr("disabled", "true");
                }
            }
        });
    }
});

$(".bt_novo").on("click", function(){
    $("#modalclienteunidades").modal();
    $("#formClienteUnidade input").removeAttr('readonly', 'readonly');
    $("#formClienteUnidade select").removeAttr('readonly', 'readonly');
    $("#formClienteUnidade textarea").removeAttr('readonly', 'readonly');
    $(".tr_remove").remove();
    $("#tb_contato").hide();
    $("#tb_telefone").hide();
    $("#tb_email").hide();
    $("#salvaClienteUnidade").removeClass("editar_clienteunidades").addClass("criar_clienteunidades");
});

$(document).on("click", ".criar_clienteunidades", function(){
    //Validação de formulário
    $("#formClienteUnidade").validate({
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
            var dados = $("#formClienteUnidade").serialize();
            var action = actionCorreta(window.location.href.toString(), "cliente_unidade/criarClienteUnidade");
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
                            title: "Cadastro de Unidade",
                            text: "Cadastro da Unidade concluído!",
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
                            title: "Cadastro de Unidade",
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
$("#tb_clienteunidades").on("click", "tr", function () {
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
    var id_clienteunidade = ids[0];
    var action = actionCorreta(window.location.href.toString(), "cliente_unidade/formClienteUnidade");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_clienteunidade: id_clienteunidade},
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
            $("#formClienteUnidade input").removeAttr('readonly', 'readonly');
            $("#formClienteUnidade select").removeAttr('readonly', 'readonly');
            $("#formClienteUnidade textarea").removeAttr('readonly', 'readonly');
            $(".tr_remove").remove();
            $("#tb_contato").hide();
            $("#tb_telefone").hide();
            $("#tb_email").hide();
            $("#id").val(data.dados.id);
            $("#cliente").val(data.dados.id_cliente).selected = "true";
            $("#nome_pessoa").val(data.dados.nome);
            $("#sigla").val(data.dados.sigla);
            $("#rzsocial").val(data.dados.razaosocial);
            $("#cnpj").val(data.dados.cnpj);
            $("#inscricaoestadual").val(data.dados.inscricaoestadual);
            $("#inscricaomunicipal").val(data.dados.inscricaomunicipal);
            $("#sigla_uf").val(data.dados.pessoaendereco.sigla_uf);
            $("#cep").val(data.dados.pessoaendereco.cep);
            $("#endereco").val(data.dados.pessoaendereco.endereco);
            $("#numero").val(data.dados.pessoaendereco.numero);
            $("#bairro").val(data.dados.pessoaendereco.bairro);
            $("#cidade").val(data.dados.pessoaendereco.cidade);
            $("#estado").val(data.dados.pessoaendereco.estado);
            $("#complemento").val(data.dados.pessoaendereco.complemento);
            if (data.dados.pessoacontato) {
                $.each(data.dados.pessoacontato, function (key, value) {
                    var principal_desc = (value.PessoaContato.principal == "1") ? "Sim" : "Não";
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trct" + value.PessoaContato.id + "'>";
                    linhas += "<td>"+ principal_desc +"<input name='res_principal_contato[]' type='hidden' value='"+ value.PessoaContato.principal +"' /></td>";
                    linhas += "<td>"+ value.descricao +"<input name='res_tipo_contato[]' type='hidden' value='"+ value.PessoaContato.id_tipocontato +"' /></td>";
                    linhas += "<td>"+ value.PessoaContato.nome +"<input name='res_nome_contato[]' type='hidden' value='"+ value.PessoaContato.nome +"' /></td>";
                    linhas += "<td>"+ value.PessoaContato.telefone +"<input name='res_telefone_contato[]' type='hidden' value='"+ value.PessoaContato.telefone +"' /></td>";
                    linhas += "<td>"+ value.PessoaContato.email +"<input name='res_email_contato[]' type='hidden' value='"+ value.PessoaContato.email +"' /></td>";
                    linhas += "<td><a href='#' id='" + value.PessoaContato.id + "' class='del_cto'><i class='fi-circle-cross'></i></a></td>";
                    linhas += "</tr class='remove'>";
                    $("#tb_contato").append(linhas);
                    $("#tb_contato").show();
                });
            }
            if (data.dados.pessoatelefone) {
                $.each(data.dados.pessoatelefone, function (key, value) {
                    var principal_desc = (value.PessoaTelefone.principal == "1") ? "Sim" : "Não";
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trte" + value.PessoaTelefone.id + "'>";
                    linhas += "<td>"+ principal_desc +"<input name='res_principal_tel[]' type='hidden' value='"+ value.PessoaTelefone.principal +"' /></td>";
                    linhas += "<td>"+ "(" + value.PessoaTelefone.ddd +") " + value.PessoaTelefone.telefone +"<input name='res_telefone[]' type='hidden' value='"+ value.PessoaTelefone.ddd + value.PessoaTelefone.telefone +"' /></td>";
                    linhas += "<td><a href='#' id='" + value.PessoaTelefone.id + "' class='del_tel'><i class='fi-circle-cross'></i></a></td>";
                    linhas += "</tr>";
                    $("#tb_telefone").append(linhas);
                    $("#tb_telefone").show();
                });
            }
            if (data.dados.pessoaemail) {
                $.each(data.dados.pessoaemail, function (key, value) {
                    var principal_desc = (value.PessoaEmail.principal == "1") ? "Sim" : "Não";
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trem" + value.PessoaEmail.id + "'>";
                    linhas += "<td>"+ principal_desc +"<input name='res_principal_email[]' type='hidden' value='"+ value.PessoaEmail.principal +"' /></td>";
                    linhas += "<td>"+ value.PessoaEmail.email +"<input name='res_email_pf[]' type='hidden' value='"+ value.PessoaEmail.email +"' /></td>";
                    linhas += "<td><a href='#' id='" + value.PessoaEmail.id + "' class='del_eml'><i class='fi-circle-cross'></i></a></td>";
                    linhas += "</tr>";
                    $("#tb_email").append(linhas);
                    $("#tb_email").show();
                });
            }
            $("#modalclienteunidades").modal();
        }
    });
    $("#salvaClienteUnidade").removeClass("criar_clienteunidades").addClass("editar_clienteunidades");
});

$(".bt_visual").on("click", function(){
    var id_clienteunidade = ids[0];
    var action = actionCorreta(window.location.href.toString(), "cliente_unidade/formClienteUnidade");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_clienteunidade: id_clienteunidade},
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
            $("#formClienteUnidade input").attr('readonly', 'readonly');
            $("#formClienteUnidade select").attr('readonly', 'readonly');
            $("#formClienteUnidade textarea").attr('readonly', 'readonly');
            $("#id").val(data.dados.id);
            $("#cliente").val(data.dados.id_cliente).selected = "true";
            $("#nome_pessoa").val(data.dados.nome);
            $("#sigla").val(data.dados.sigla);
            $("#rzsocial").val(data.dados.razaosocial);
            $("#cnpj").val(data.dados.cnpj);
            $("#inscricaoestadual").val(data.dados.inscricaoestadual);
            $("#inscricaomunicipal").val(data.dados.inscricaomunicipal);
            $("#sigla_uf").val(data.dados.pessoaendereco.sigla_uf);
            $("#cep").val(data.dados.pessoaendereco.cep);
            $("#endereco").val(data.dados.pessoaendereco.endereco);
            $("#numero").val(data.dados.pessoaendereco.numero);
            $("#bairro").val(data.dados.pessoaendereco.bairro);
            $("#cidade").val(data.dados.pessoaendereco.cidade);
            $("#estado").val(data.dados.pessoaendereco.estado);
            $("#complemento").val(data.dados.pessoaendereco.complemento);
            if (data.dados.pessoacontato) {
                $.each(data.dados.pessoacontato, function (key, value) {
                    var principal_desc = (value.PessoaContato.principal == "1") ? "Sim" : "Não";
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trct" + value.PessoaContato.id + "'>";
                    linhas += "<td>"+ principal_desc +"<input name='res_principal_contato[]' type='hidden' value='"+ value.PessoaContato.principal +"' /></td>";
                    linhas += "<td>"+ value.descricao +"<input name='res_tipo_contato[]' type='hidden' value='"+ value.PessoaContato.id_tipocontato +"' /></td>";
                    linhas += "<td>"+ value.PessoaContato.nome +"<input name='res_nome_contato[]' type='hidden' value='"+ value.PessoaContato.nome +"' /></td>";
                    linhas += "<td>"+ value.PessoaContato.telefone +"<input name='res_telefone_contato[]' type='hidden' value='"+ value.PessoaContato.telefone +"' /></td>";
                    linhas += "<td>"+ value.PessoaContato.email +"<input name='res_email_contato[]' type='hidden' value='"+ value.PessoaContato.email +"' /></td>";
                    linhas += "<td><i class='fi-circle-cross'></i></td>";
                    linhas += "</tr class='remove'>";
                    $("#tb_contato").append(linhas);
                    $("#tb_contato").show();
                });
            }
            if (data.dados.pessoatelefone) {
                $.each(data.dados.pessoatelefone, function (key, value) {
                    var principal_desc = (value.PessoaTelefone.principal == "1") ? "Sim" : "Não";
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trte" + value.PessoaTelefone.id + "'>";
                    linhas += "<td>"+ principal_desc +"<input name='res_principal_tel[]' type='hidden' value='"+ value.PessoaTelefone.principal +"' /></td>";
                    linhas += "<td>"+ "(" + value.PessoaTelefone.ddd +") " + value.PessoaTelefone.telefone +"<input name='res_telefone[]' type='hidden' value='"+ value.PessoaTelefone.ddd + value.PessoaTelefone.telefone +"' /></td>";
                    linhas += "<td><i class='fi-circle-cross'></i></td>";
                    linhas += "</tr>";
                    $("#tb_telefone").append(linhas);
                    $("#tb_telefone").show();
                });
            }
            if (data.dados.pessoaemail) {
                $.each(data.dados.pessoaemail, function (key, value) {
                    var principal_desc = (value.PessoaEmail.principal == "1") ? "Sim" : "Não";
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trem" + value.PessoaEmail.id + "'>";
                    linhas += "<td>"+ principal_desc +"<input name='res_principal_email[]' type='hidden' value='"+ value.PessoaEmail.principal +"' /></td>";
                    linhas += "<td>"+ value.PessoaEmail.email +"<input name='res_email_pf[]' type='hidden' value='"+ value.PessoaEmail.email +"' /></td>";
                    linhas += "<td><i class='fi-circle-cross'></i></td>";
                    linhas += "</tr>";
                    $("#tb_email").append(linhas);
                    $("#tb_email").show();
                });
            }
            $("#cliente").attr("disabled", "true");
            $("#modalclienteunidades").modal();
        }
    });
    $("#salvaClienteUnidade").removeClass("criar_clienteunidades").addClass("editar_clienteunidades");
});

$(document).on("click", ".editar_clienteunidades", function(){
    //Validação de formulário
    $("#formClienteUnidade").validate({
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
            var dados = $("#formClienteUnidade").serialize();
            var action = actionCorreta(window.location.href.toString(), "cliente_unidade/editarClienteUnidade");
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
                            title: "Cadastro de Unidade",
                            text: "Cadastro da Unidade concluído!",
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
                            title: "Cadastro de Unidade",
                            text: data.mensagem,
                            type: "error"
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
        title: "Tem certeza que deseja deletar este telefone?",
        text: "O sistema irá deletar o telefone selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, apagar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "core/deletarPessoaTelefone");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {id: id},
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
                        text: "O telefone selecionado foi deletado com sucesso.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        $("#trte" + id).remove();
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
});

$("#tb_email").on("click", ".del_eml", function(){
    var id = $(this).attr("id");
    swal({
        title: "Tem certeza que deseja deletar este e-mail?",
        text: "O sistema irá deletar o e-mail selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, apagar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "core/deletarPessoaEmail");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {id: id},
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
                        text: "O e-mail selecionado foi deletado com sucesso.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        $("#trem" + id).remove();
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
});

$("#tb_contato").on("click", ".del_cto", function(){
    var id = $(this).attr("id");
    swal({
        title: "Tem certeza que deseja deletar este contato?",
        text: "O sistema irá deletar o contato selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, apagar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "core/deletarPessoaContato");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {id: id},
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
                        text: "O contato selecionado foi deletado com sucesso.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        $("#trct" + id).remove();
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
            var action = actionCorreta(window.location.href.toString(), "cliente_unidade/deletarClienteUnidade");
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
            title: "Deletar Unidade",
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
            var action = actionCorreta(window.location.href.toString(), "cliente_unidade/deletarClienteUnidade");
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

$(".bt_ativo").on("click", function(){
    var nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: "Tem certeza que deseja ativar múltipas unidades?",
            text: "O sistema irá ativar um total de " + nm_rows + " unidades com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, ativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "cliente_unidade/ativarClienteUnidade");
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
                            title: "Ativadas!",
                            text: "As unidades selecionadas foram ativadas com sucesso.",
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
                            title: "Ativar",
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
        });
    } else if (nm_rows == 0) {
        swal({
            title: "Ativar Unidades",
            text: "Você precisa selecionar uma ou mais unidades para serem ativadas!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja ativar esta unidade?",
            text: "O sistema irá ativar a unidade selecionada com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, ativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "cliente_unidade/ativarClienteUnidade");
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
                            title: "Ativada!",
                            text: "A unidade selecionada foi ativada com sucesso.",
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
                            title: "Ativar",
                            text: data.mensagem,
                            type: "error"
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
            title: "Tem certeza que deseja inativar múltipos unidades?",
            text: "O sistema irá inativar um total de " + nm_rows + " unidades com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, inativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "cliente_unidade/inativarClienteUnidade");
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
                            title: "Inativadas!",
                            text: "As unidades selecionados foram inativadas com sucesso.",
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
                            title: "Inativar",
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
        });
    } else if (nm_rows == 0) {
        swal({
            title: "Inativar Unidade",
            text: "Você precisa selecionar uma ou mais unidades para serem inativadas!",
            type: "warning"
        });
    } else {
        swal({
            title: "Tem certeza que deseja inativar esta unidade?",
            text: "O sistema irá inativar a unidade selecionada com essa ação.",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, inativar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "cliente_unidade/inativarClienteUnidade");
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
                            title: "Inativada!",
                            text: "A unidade selecionada foi inativada com sucesso.",
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
                            title: "Inativar",
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
        });
    }
});