var table = $('#tb_clienteunidades').DataTable({
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

table.buttons().container().appendTo('#tb_clienteunidades_wrapper .col-md-6:eq(0)');

table.on( 'select deselect', function () {
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows > 0 );
    table.button( 3 ).enable( selectedRows > 0 );
    table.button( 4 ).enable( selectedRows > 0 );
});

function limpaEndereco()
{
    $("#bairro").val(null);
    $("#cidade").val(null);
    $("#endereco").val(null);
    $("#sigla_uf").val(null);
    $("#estado").val(null);
    $("#numero").val(null);
    $("#complemento").val(null);
    $("#sigla_uf").val(null);
    $("#tipoendereco").val(null).selected = "true";
    $("#principal").val(null).selected = "true";
    $("#cep").val(null);
    $("#cep").focus();
}

function limparModal()
{
    $(".tr_remove").remove();
    $("#tb_endereco").hide();
    $("#tb_telefone").hide();
    $("#tb_email").hide();
    valCEP = [];
    valTEL = [];
    valEML = [];
    $(".form_fis").hide();
    $(".form_jur").hide();
    $("#id").val(null);
}

$("#cep").on("change", function(){
    var cep_t = $("#cep").val();
    if (cep_t) {
        var cep = formata_cep(cep_t);
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: 'core/completaEndereco',
            data: {cep: cep},
            beforeSend: function () {
                $("#bairro").val(null);
                $("#cidade").val(null);
                $("#endereco").val(null);
                $("#sigla_uf").val(null);
                $("#estado").val(null);
            },
            complete: function () {
            },
            error: function () {
            },
            success: function (data) {
                if (data.operacao){
                    $("#bairro").val(data.endereco["bairro"]);
                    $("#cidade").val(data.endereco["cidade"]);
                    $("#endereco").val(data.endereco["logradouro"]);
                    $("#sigla_uf").val(data.endereco["sigla_uf"]);
                    $("#estado").val(data.endereco["uf"]);
                    $("#numero").focus();
                } else {
                    swal({
                        title: 'Atenção',
                        text: 'O CEP digitado não retorno nenhum endereço válido! Por favor, insira o endereço de forma manual.',
                        type: 'warning'
                      });
                }
            }
        }); 
    }   
});

//Limpar Linhas da Tabela
(function ($) {
    RemoveTableRow = function (handler) {
        var tr = $(handler).closest('tr');
        tr.fadeOut(400, function () {
            tr.remove();
        });
        return false;
    };
})(jQuery);

var valCEP = [];
$("#insert_endereco").on("click", function(){
    var sigla_uf = $("#sigla_uf_t").val();
    var tipoendereco = $("#tipoendereco_t").val();
    var tipoendereco_desc = document.getElementById("tipoendereco_t").options[document.getElementById("tipoendereco_t").selectedIndex].text;
    var cep = $("#cep_t").val();
    var endereco = $("#endereco_t").val();
    var numero = $("#numero_t").val();
    var bairro = $("#bairro_t").val();
    var cidade = $("#cidade_t").val();
    var estado = $("#estado_t").val();
    var complemento = $("#complemento_t").val();
    var principal = $("#principal_t").val();
    var eprincipal = principal == "1" ? "Sim" : "Não";

    if ($.inArray(cep, valCEP) == -1) {
        valCEP.push(cep);
        if (tipoendereco != 0 && cep && endereco && numero && cidade && principal !== "") {
            var linhas = null;
            linhas += "<tr class='tr_remove'>";
            linhas += "<td style='display: none;'>" + sigla_uf + "<input name='sigla_uf[]' type='hidden' value='" + sigla_uf + "' /></td>";
            linhas += "<td>"+ eprincipal +"<input name='principal_end[]' type='hidden' value='"+ principal +"' /></td>";
            linhas += "<td>"+ tipoendereco_desc +"<input name='tipoendereco[]' type='hidden' value='"+ tipoendereco +"' /></td>";
            linhas += "<td>"+ cep +"<input name='cep[]' type='hidden' value='"+ cep +"' /></td>";
            linhas += "<td>"+ endereco +"<input name='endereco[]' type='hidden' value='"+ endereco +"' /></td>";
            linhas += "<td style='display: none;'>"+ numero +"<input name='numero[]' type='hidden' value='"+ numero +"' /></td>";
            linhas += "<td style='display: none;'>"+ bairro +"<input name='bairro[]' type='hidden' value='"+ bairro +"' /></td>";
            linhas += "<td>"+ cidade +"<input name='cidade[]' type='hidden' value='"+ cidade +"' /></td>";
            linhas += "<td>"+ estado +"<input name='estado[]' type='hidden' value='"+ estado +"' /></td>";
            linhas += "<td style='display: none;'>"+ complemento +"<input name='complemento[]' type='hidden' value='"+ complemento +"' /></td>";
            linhas += "<td><a href='#' onclick='RemoveTableRow(this)'><i class='fi-circle-cross'></i></a></td>";
            linhas += "</tr>";
            $("#tb_endereco").append(linhas);
            $('#tb_endereco').show();
            limpaEndereco();
        } else {
            swal({
                title: 'Endereço do Cliente',
                text: 'Você precisa preencher corretamente os campos do endereço!',
                type: 'warning'
              });
        }
    } else {
        swal({
            title: 'Endereço do Cliente',
            text: 'Esse endereço já existe na tabela abaixo!',
            type: 'warning'
        });
    }
});

var valTEL = [];
$("#insert_telefone").on("click", function(){
    var tipotelefone = $("#tipotelefone_t").val();
    var tipotelefone_desc = document.getElementById("tipotelefone_t").options[document.getElementById("tipotelefone_t").selectedIndex].text;
    var telefone = $("#telefone_t").val();
    var principal = $("#princtel_t").val();
    var eprincipal = principal == "1" ? "Sim" : "Não";

    if ($.inArray(telefone, valTEL) == -1) {
        valTEL.push(telefone);
        if (tipotelefone != 0 && telefone && principal !== "") {
            var linhas = null;
            linhas += "<tr class='tr_remove'>";
            linhas += "<td>"+ eprincipal +"<input name='principal_tel[]' type='hidden' value='"+ principal +"' /></td>";
            linhas += "<td>"+ tipotelefone_desc +"<input name='tipotelefone[]' type='hidden' value='"+ tipotelefone +"' /></td>";
            linhas += "<td>"+ telefone +"<input name='telefone[]' type='hidden' value='"+ telefone +"' /></td>";
            linhas += "<td><a href='#' onclick='RemoveTableRow(this)'><i class='fi-circle-cross'></i></a></td>";
            linhas += "</tr>";
            $("#tb_telefone").append(linhas);
            $('#tb_telefone').show();
            $("#tipotelefone_t").val(null).selected = "true";
            $("#telefone_t").val(null);
            $("#princtel_t").val(null).selected = "true";
        } else {
            swal({
                title: 'Contatos do Cliente',
                text: 'Você precisa preencher corretamente os campos do telefone!',
                type: 'warning'
              });
        }
    } else {
        swal({
            title: 'Endereço do Cliente',
            text: 'Esse telefone já existe na tabela abaixo!',
            type: 'warning'
        });
    }
});

var valEML = [];
$("#insert_email").on("click", function(){
    var tipoemail = $("#tipoemail_t").val();
    var tipoemail_desc = document.getElementById("tipoemail_t").options[document.getElementById("tipoemail_t").selectedIndex].text;
    var email = $("#email_t").val();
    var principal = $("#princemail_t").val();
    var eprincipal = principal == "1" ? "Sim" : "Não";

    if ($.inArray(email, valEML) == -1) {
        valEML.push(email);
        if (tipoemail != 0 && email && principal !== "") {
            var linhas = null;
            linhas += "<tr class='tr_remove'>";
            linhas += "<td>"+ eprincipal +"<input name='principal_email[]' type='hidden' value='"+ principal +"' /></td>";
            linhas += "<td>"+ tipoemail_desc +"<input name='tipoemailpf[]' type='hidden' value='"+ tipoemail +"' /></td>";
            linhas += "<td>"+ email +"<input name='email_pf[]' type='hidden' value='"+ email +"' /></td>";
            linhas += "<td><a href='#' onclick='RemoveTableRow(this)'><i class='fi-circle-cross'></i></a></td>";
            linhas += "</tr>";
            $("#tb_email").append(linhas);
            $('#tb_email').show();
            $("#tipoemail_t").val(null).selected = "true";
            $("#email_t").val(null);
            $("#princemail_t").val(null).selected = "true";
        } else {
            swal({
                title: 'Contatos do Cliente',
                text: 'Você precisa preencher corretamente os campos do e-mail!',
                type: 'warning'
            });
        }
    } else {
        swal({
            title: 'Endereço do Cliente',
            text: 'Esse e-mail já existe na tabela abaixo!',
            type: 'warning'
        });
    }
});

$("#email_t").on("change", function(){
    var email = $("#email_t").val();
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
                    text: data.message,
                    type: 'warning'
                  });
                $("#email_t").val(null);
            } 
        }
    });    
});

$("#cpf").on("change", function(){
    var cpf = $("#cpf").val();
    if (cpf) {
        var cpf_form = formataCPFCNPJ(cpf);
        var resultado = TestaCPF(cpf_form);
        if (resultado === false) {
            swal({
                title: 'Cadastro de Clientes',
                text: 'O CPF digitado está incorreto, por favor, verifique o número digitado!',
                type: 'warning'
              });
        }
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: 'core/validarCPF',
            data: {cpf: cpf_form},
            beforeSend: function () {
            },
            complete: function () {
            },
            error: function () {
            },
            success: function (data) {
                if (data.operacao){
                    swal({
                        title: 'Cadastro de Clientes',
                        text: 'O CPF digitado já existe, por favor, verifique o cadastro!',
                        type: 'warning'
                      });
                    $("#salvaClienteUnidade").attr("disabled", "true");
                } else {
                    $("#salvaClienteUnidade").removeAttr("disabled", "true");
                }
            }
        });   
    } 
});

$("#cnpj").on("change", function(){
    var cnpj = $("#cnpj").val();
    if (cnpj) {
        var cnpj_form = formataCPFCNPJ(cnpj);
        var resultado = TestaCNPJ(cnpj_form);
        if (resultado === false) {
            swal({
                title: 'Cadastro de Clientes',
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
                        title: 'Cadastro de Clientes',
                        text: 'O CNPJ digitado já existe, por favor, verifique o cadastro!',
                        type: 'warning'
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
    $("#salvaClienteUnidade").removeClass("editar_clienteunidades").addClass("criar_clienteunidades");
});

$(document).on("click", ".criar_clienteunidades", function(){
    var tipoclienteunidades = $("#tipoclienteunidades").val();
    if (tipoclienteunidades){
        switch (tipoclienteunidades)
        {
            case "45"://Pessoa Física
            //Validação de formulário
            $("#formCliente").validate({
                rules : {
                    nome_pessoa2:{
                        required: true
                    },
                    sexo:{
                        required: true
                    },
                    cpf:{
                        required: true
                    }
                },
                messages:{
                    nome_pessoa2:{
                        required:"É necessário informar o nome do clienteunidades"
                    },
                    sexo:{
                        required:"É necessário informar o sexo do clienteunidades"
                    },
                    cpf:{
                        required:"É necessário informar o CPF do clienteunidades"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCliente").serialize();
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        url: 'clienteunidades/criarCliente',
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
            break;
            case "44"://Pessoa Jurídica
            //Validação de formulário
            $("#formCliente").validate({
                rules : {
                    nome_pessoa:{
                        required: true
                    },
                    rzsocial:{
                        required: true
                    },
                    cnpj:{
                        required: true
                    },
                    esfera:{
                        required: true
                    },
                    setor:{
                        required: true
                    }
                },
                messages:{
                    nome_pessoa:{
                        required:"É necessário informar o nome fantasia do clienteunidades"
                    },
                    rzsocial:{
                        required:"É necessário informar a razão social do clienteunidades"
                    },
                    cnpj:{
                        required:"É necessário informar o CNPJ do clienteunidades"
                    },
                    esfera:{
                        required: "É necessário informar uma esfera"
                    },
                    setor:{
                        required: "É necessário informar um setor"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCliente").serialize();
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        url: 'clienteunidades/criarCliente',
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
            break;
        }
    } else {
        swal({
            title: 'Cadastro de Cliente',
            text: "Você precisa selecionar um tipo de clienteunidades!",
            type: 'error'
        });
    }
});

//Coletando os ids das linhas selecionadas na tabela
var ids = [];   
$("#tb_clienteunidades").on("click", "tr", function () {
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
            text: 'Você somente pode editar um único clienteunidades! Selecione apenas um e tente novamente!',
            type: 'warning'
          });
    } else if (nm_rows == 0) {
        swal({
            title: 'Edição de Cliente',
            text: 'Você precisa selecionar um clienteunidades para a edição!',
            type: 'warning'
          });
     } else {
        var id_clienteunidades = ids[0];
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: 'clienteunidades/formCliente',
            data: {id_clienteunidades: id_clienteunidades},
            beforeSend: function () {
            },
            complete: function () {
            },
            error: function () {
            },
            success: function (data) {
                switch (data.dados.tipo)
                {
                    case "45"://Pessoa Física
                        $("#id").val(data.dados.id);
                        $("#tipoclienteunidades").val(data.dados.tipo).selected = "true";
                        $("#nome_pessoa2").val(data.dados.nome);
                        $("#cpf").val(data.dados.cpf);
                        $("#rg").val(data.dados.rg);
                        $("#datanasc").val(data.dados.datanasc);
                        $("#sexo").val(data.dados.id_sexo).selected = "true";
                        if (data.dados.pessoaendereco) {
                            $.each(data.dados.pessoaendereco, function (key, value) {
                                var principal_desc = (value.PessoaEndereco.principal == "1") ? "Sim" : "Não";
                                var linhas;
                                linhas += "<tr class='tr_remove' id='tred" + value.PessoaEndereco.id + "'>";
                                linhas += "<td style='display: none;'>" + value.PessoaEndereco.sigla_estado + "<input name='res_sigla_uf[]' type='hidden' value='" + value.PessoaEndereco.sigla_estado + "' /></td>";
                                linhas += "<td>"+ principal_desc +"<input name='res_principal_end[]' type='hidden' value='"+ value.PessoaEndereco.principal +"' /></td>";
                                linhas += "<td>"+ value.descricao +"<input name='res_tipoendereco[]' type='hidden' value='"+ value.PessoaEndereco.id_tipoendereco +"' /></td>";
                                linhas += "<td>"+ value.PessoaEndereco.cep +"<input name='res_cep[]' type='hidden' value='"+ value.PessoaEndereco.cep +"' /></td>";
                                linhas += "<td>"+ value.PessoaEndereco.endereco +"<input name='res_endereco[]' type='hidden' value='"+ value.endereco +"' /></td>";
                                linhas += "<td style='display: none;'>"+ value.PessoaEndereco.numero +"<input name='res_numero[]' type='hidden' value='"+ value.PessoaEndereco.numero +"' /></td>";
                                linhas += "<td style='display: none;'>"+ value.PessoaEndereco.bairro +"<input name='res_bairro[]' type='hidden' value='"+ value.PessoaEndereco.bairro +"' /></td>";
                                linhas += "<td>"+ value.PessoaEndereco.cidade +"<input name='res_cidade[]' type='hidden' value='"+ value.PessoaEndereco.cidade +"' /></td>";
                                linhas += "<td>"+ value.PessoaEndereco.estado +"<input name='res_estado[]' type='hidden' value='"+ value.PessoaEndereco.estado +"' /></td>";
                                linhas += "<td style='display: none;'>"+ value.PessoaEndereco.complemento +"<input name='res_complemento[]' type='hidden' value='"+ value.PessoaEndereco.complemento +"' /></td>";
                                linhas += "<td><a href='#' id='" + value.PessoaEndereco.id + "' class='del_end'><i class='fi-circle-cross'></i></a></td>";
                                linhas += "</tr class='remove'>";
                                $("#tb_endereco").append(linhas);
                                $("#tb_endereco").show();
                            });
                        }
                        if (data.dados.pessoatelefone) {
                            $.each(data.dados.pessoatelefone, function (key, value) {
                                var principal_desc = (value.PessoaTelefone.principal == "1") ? "Sim" : "Não";
                                var linhas;
                                linhas += "<tr class='tr_remove' id='trte" + value.PessoaTelefone.id + "'>";
                                linhas += "<td>"+ principal_desc +"<input name='res_principal_tel[]' type='hidden' value='"+ value.PessoaTelefone.principal +"' /></td>";
                                linhas += "<td>"+ value.descricao +"<input name='res_tipotelefone[]' type='hidden' value='"+ value.PessoaTelefone.id_tipotelefone +"' /></td>";
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
                                linhas += "<td>"+ value.descricao +"<input name='res_tipoemailpf[]' type='hidden' value='"+ value.PessoaEmail.id_tipoemail +"' /></td>";
                                linhas += "<td>"+ value.PessoaEmail.email +"<input name='res_email_pf[]' type='hidden' value='"+ value.PessoaEmail.email +"' /></td>";
                                linhas += "<td><a href='#' id='" + value.PessoaEmail.id + "' class='del_eml'><i class='fi-circle-cross'></i></a></td>";
                                linhas += "</tr>";
                                $("#tb_email").append(linhas);
                                $("#tb_email").show();
                            });
                        }
                        $(".form_jur").hide();
                        $(".form_fis").show();
                    break;
                    case "44"://Pessoa Jurídica
                        $("#id").val(data.dados.id);
                        $("#tipoclienteunidades").val(data.dados.tipo).selected = "true";
                        $("#nome_pessoa").val(data.dados.nome);
                        $("#rzsocial").val(data.dados.razaosocial);
                        $("#sigla").val(data.dados.sigla);
                        $("#cnpj").val(data.dados.cnpj);
                        $("#inscricaoestadual").val(data.dados.inscricaoestadual);
                        $("#inscricaomunicipal").val(data.dados.inscricaomunicipal);
                        $("#esfera").val(data.dados.id_tipoesfera).selected = "true";
                        $("#setor").val(data.dados.id_setor).selected = "true";
                        $("#datafund").val(data.dados.datafund);
                        $(".form_fis").hide();
                        $(".form_jur").show();
                    break;
                }
                $("#modalclienteunidades").modal();
            }
        });
        $("#salvaClienteUnidade").removeClass("criar_clienteunidades").addClass("editar_clienteunidades");
    }
});

$(document).on("click", ".editar_clienteunidades", function(){
    var tipoclienteunidades = $("#tipoclienteunidades").val();
    if (tipoclienteunidades){
        switch (tipoclienteunidades)
        {
            case "45"://Pessoa Física
            //Validação de formulário
            $("#formCliente").validate({
                rules : {
                    nome_pessoa2:{
                        required: true
                    },
                    sexo:{
                        required: true
                    },
                    cpf:{
                        required: true
                    }
                },
                messages:{
                    nome_pessoa2:{
                        required:"É necessário informar o nome do clienteunidades"
                    },
                    sexo:{
                        required:"É necessário informar o sexo do clienteunidades"
                    },
                    cpf:{
                        required:"É necessário informar o CPF do clienteunidades"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCliente").serialize();
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        url: 'clienteunidades/editarCliente',
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
                                    text: 'Edição do Cliente concluída!',
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
            break;
            case "44"://Pessoa Jurídica
            //Validação de formulário
            $("#formCliente").validate({
                rules : {
                    nome_pessoa:{
                        required: true
                    },
                    rzsocial:{
                        required: true
                    },
                    cnpj:{
                        required: true
                    },
                    esfera:{
                        required: true
                    },
                    setor:{
                        required: true
                    }
                },
                messages:{
                    nome_pessoa:{
                        required:"É necessário informar o nome fantasia do clienteunidades"
                    },
                    rzsocial:{
                        required:"É necessário informar a razão social do clienteunidades"
                    },
                    cnpj:{
                        required:"É necessário informar o CNPJ do clienteunidades"
                    },
                    esfera:{
                        required: "É necessário informar uma esfera"
                    },
                    setor:{
                        required: "É necessário informar um setor"
                    }
                },
                submitHandler: function(form) {
                    var dados = $("#formCliente").serialize();
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        url: 'clienteunidades/editarCliente',
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
                                    text: 'Edição do Cliente concluída!',
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
            break;
        }
    } else {
        swal({
            title: 'Cadastro de Cliente',
            text: "Você precisa selecionar um tipo de clienteunidades!",
            type: 'error'
        });
    }
});

$("#tb_endereco").on("click", ".del_end", function(){
    var id = $(this).attr("id");
    swal({
        title: 'Tem certeza que deseja deletar este endereço?',
        text: "O sistema irá deletar o endereço selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, apagar!'
      }).then((result) => {
          $.ajax({
              type: 'POST',
              dataType: 'JSON',
              url: 'core/deletarPessoaEndereco',
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
                          text: 'O endereço selecionado foi deletado com sucesso.',
                          type: 'success',
                          showCancelButton: false,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Ok'
                        }).then((result) => {
                            $("#tred" + id).remove();
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

$(".bt_del").on("click", function(){
    var nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: 'Tem certeza que deseja deletar múltipos clienteunidadess?',
            text: "O sistema irá deletar um total de " + nm_rows + " clienteunidadess com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'clienteunidades/deletarCliente',
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
                              text: 'Os clienteunidades selecionados foram deletados com sucesso.',
                              type: 'success',
                              showCancelButton: false,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Ok'
                            }).then((result) => {
                                $(this).remove();
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
            text: 'Você precisa selecionar um clienteunidades ou mais clienteunidadess para serem deletados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja deletar este clienteunidades?',
            text: "O sistema irá deletar o clienteunidades selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, apagar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'clienteunidades/deletarCliente',
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
                              text: 'O clienteunidades selecionado foi deletado com sucesso.',
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
            title: 'Tem certeza que deseja ativar múltipos clienteunidadess?',
            text: "O sistema irá ativar um total de " + nm_rows + " clienteunidadess com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, ativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'clienteunidades/ativarCliente',
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
                              text: 'Os clienteunidades selecionados foram ativados com sucesso.',
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
            text: 'Você precisa selecionar um clienteunidades ou mais clienteunidadess para serem ativados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja ativar este clienteunidades?',
            text: "O sistema irá ativar o clienteunidades selecionado com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, ativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'clienteunidades/ativarCliente',
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
                              text: 'O clienteunidades selecionado foi ativado com sucesso.',
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
            title: 'Tem certeza que deseja inativar múltipos clienteunidadess?',
            text: "O sistema irá inativar um total de " + nm_rows + " clienteunidadess com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, inativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'clienteunidades/inativarCliente',
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
                              text: 'Os clienteunidades selecionados foram inativados com sucesso.',
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
            text: 'Você precisa selecionar um clienteunidades ou mais clienteunidadess para serem inativados!',
            type: 'warning'
          });
     } else {
        swal({
            title: 'Tem certeza que deseja inativar este clienteunidades?',
            text: "O sistema irá inativar o clienteunidades selecionado com essa ação.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, inativar!'
          }).then((result) => {
              $.ajax({
                  type: 'POST',
                  dataType: 'JSON',
                  url: 'clienteunidades/inativarCliente',
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
                              text: 'O clienteunidades selecionado foi inativado com sucesso.',
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