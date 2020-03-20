var URLImagensSistema = "public/images";

//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos
function inicializar()
{

}

var table = $("#tb_controleacesso").DataTable({
    buttons: [
        {//Botão Novo Registro
            className: "bt_novo",
            text: "Novo",
            name: "novo", // do not change name
            titleAttr: "Novo registro",
            action: function (e, dt, node, config) {
            }

        },
        {//Botão Permissões
            className: "bt_permissoes",
            text: "Permissões",
            name: "permissoes", // do not change name
            titleAttr: "Permissões do Perfil",
            action: function (e, dt, node, config) {
            },
            enabled: false

        },
        // {//Botão Visualizar Registro
        //     className: "bt_visual",
        //     text: "Visualizar",
        //     name: "visualizar", // do not change name
        //     titleAttr: "Visualizar Perfil",
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

        }
    ]
});

table.buttons().container().appendTo("#tb_controleacesso_wrapper .col-md-6:eq(0)");

table.on( 'select deselect', function () {
    "use strict";
    var selectedRows = table.rows( { selected: true } ).count();

    table.button( 1 ).enable( selectedRows === 1 );
    table.button( 2 ).enable( selectedRows === 1 );
    table.button( 3 ).enable( selectedRows > 0 );
});

//Limpar Linhas da Tabela
(function ($) {
    "use strict";
    var RemoveTableRow = function (handler) {
        var tr = $(handler).closest("tr");
        tr.fadeOut(400, function () {
            tr.remove();
        });
        return false;
    };
})(jQuery);

$(".bt_novo").on("click", function(){
    "use strict";
    $("#modalcontroleacesso").modal();
    $("#salvarControleAcesso").removeClass("editar_controleacesso").addClass("criar_controleacesso");
});

$(document).on("click", ".criar_controleacesso", function(){
    "use strict";
    //Validação de formulário
    $("#formControleAcesso").validate({
        rules : {
            nome_perfil:{
                required: true,
                maxlength: 50
            }
        },
        messages:{
            nome_perfil:{
                required:"É necessário informar um nome para o perfil",
                maxlength: jQuery.validator.format("O campo tem limite de {0} caracteres!")
            }
        },
        submitHandler: function(form) {
            var dados = $("#formControleAcesso").serialize();
            var action = actionCorreta(window.location.href.toString(), "controle_acesso/criarControleAcesso");
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
                            title: "Cadastro de Perfil",
                            text: "Cadastro do perfil concluído!",
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
                            title: "Cadastro de Perfil",
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
$("#tb_controleacesso").on("click", "tr", function () {
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
    "use strict";
    var id_controleacesso = ids[0];
    var action = actionCorreta(window.location.href.toString(), "controle_acesso/formControleAcesso");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_controleacesso: id_controleacesso},
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
            $("#formControleAcesso input").removeAttr('readonly', 'readonly');
            $("#formControleAcesso select").removeAttr('readonly', 'readonly');
            $("#formControleAcesso textarea").removeAttr('readonly', 'readonly');
            $(".tr_remove").remove();
            $("#id").val(data.dados.id);
            $("#id_cidade").val(data.dados.id_cidade).selected = "true";
            $("#descricao").val(data.dados.descricao);
            if (data.conectividades) {
                $.each(data.conectividades, function (key, value) {
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trcn" + value.Conectividade.id + "'>";
                    linhas += "<td>"+ value.descricao +"<input name='res_tipo_conectividade[]' type='hidden' value='"+ value.Conectividade.id_tipo +"' /></td>";
                    linhas += "<td>"+ value.Conectividade.descricao +"<input name='res_conectividade[]' type='hidden' value='"+ value.Conectividade.descricao +"' /></td>";
                    linhas += "<td>"+ value.Conectividade.endereco +"<input name='res_endereco[]' type='hidden' value='"+ value.Conectividade.endereco +"' /></td>";
                    linhas += "<td><a href='#' id='" + value.Conectividade.id + "' class='del_conec'><i class='fi-circle-cross'></i></a></td>";
                    linhas += "</tr class='remove'>";
                    $("#tb_conectividade").append(linhas);
                    $("#tb_conectividade").show();
                });
            }
            $("#modalcontroleacesso").modal();
        }
    });
    $("#salvarControleAcesso").removeClass("criar_controleacesso").addClass("editar_controleacesso");
});

$(".bt_visual").on("click", function(){
    "use strict";
    var id_controleacesso = ids[0];
    var action = actionCorreta(window.location.href.toString(), "controle_acesso/formControleAcesso");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {id_controleacesso: id_controleacesso},
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
            $("#formControleAcesso input").attr('readonly', 'readonly');
            $("#formControleAcesso select").attr('readonly', 'readonly');
            $("#formControleAcesso textarea").attr('readonly', 'readonly');
            $(".tr_remove").remove();
            $("#id").val(data.dados.id);
            $("#id_cidade").val(data.dados.id_cidade).selected = "true";
            $("#descricao").val(data.dados.descricao);
            if (data.conectividades) {
                $.each(data.conectividades, function (key, value) {
                    var linhas;
                    linhas += "<tr class='tr_remove' id='trcn" + value.Conectividade.id + "'>";
                    linhas += "<td>"+ value.descricao +"<input name='res_tipo_conectividade[]' type='hidden' value='"+ value.Conectividade.id_tipo +"' /></td>";
                    linhas += "<td>"+ value.Conectividade.descricao +"<input name='res_conectividade[]' type='hidden' value='"+ value.Conectividade.descricao +"' /></td>";
                    linhas += "<td>"+ value.Conectividade.endereco +"<input name='res_endereco[]' type='hidden' value='"+ value.Conectividade.endereco +"' /></td>";
                    linhas += "<td><i class='fi-circle-cross'></i></td>";
                    linhas += "</tr class='remove'>";
                    $("#tb_conectividade").append(linhas);
                    $("#tb_conectividade").show();
                });
            }
            $("#modalcontroleacesso").modal();
        }
    });
    $("#salvarControleAcesso").removeClass("criar_controleacesso").addClass("editar_controleacesso");
});

$(document).on("click", ".editar_controleacesso", function(){
    "use strict";
    //Validação de formulário
    $("#formControleAcesso").validate({
        rules : {
            nome_perfil:{
                required: true
            }
        },
        messages:{
            nome_perfil:{
                required:"É necessário informar um nome para o perfil"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formControleAcesso").serialize();
            var action = actionCorreta(window.location.href.toString(), "controle_acesso/editarControleAcesso");
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
                            title: "Cadastro de Cidade Digital",
                            text: "Edição de cidade digital concluída!",
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
                            title: "Cadastro de Cidade Digital",
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
    "use strict";
    var nm_rows = ids.length;
    if(nm_rows > 1){
        swal({
            title: "Tem certeza que deseja deletar múltipos registros?",
            text: "O sistema irá deletar um total de " + nm_rows + " registros com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "controle_acesso/deletarControleAcesso");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
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
                            title: "Deletados!",
                            text: "Os registros selecionados foram deletados com sucesso.",
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
    } else {
        swal({
            title: "Tem certeza que deseja deletar este registro?",
            text: "O sistema irá deletar o registro selecionado com essa ação. ATENÇÃO: Esta é uma ação irreversível!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, apagar!"
        }).then((result) => {
            var action = actionCorreta(window.location.href.toString(), "controle_acesso/deletarControleAcesso");
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    ids: ids
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
                            title: "Deletado!",
                            text: "O registro selecionado foi deletado com sucesso.",
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

//Permissões de Acesso
//Buscando permissões por Perfil
function buscarPermissoes(role)
{
    "use strict";
    var action = actionCorreta(window.location.href.toString(), "controle_acesso/buscarPermissoes");
    var dados = [];
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {role: role},
        async: false,
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
            dados.push(data.controleacesso);
        }
    });
    return dados;
}
//Funções que controlam o checkbox do total já iniciar corretamente
function checkAcessoUnitario(resources)
{
    "use strict";
    var verdadeiro_modulo = 0;
    var falso_modulo = 0;
    var seletor_unitario;
    var seletor_total;
    switch (resources)
    {
        case "index":
            seletor_unitario = ".permissao_unitaria_dashboard";
            seletor_total = ".check_total_dashboard";
            break;
        case "relatorios_gestao":
            seletor_unitario = ".permissao_unitaria_relatorios_gestao";
            seletor_total = ".check_total_relatorios_gestao";
            break;
        case "cidade_digital":
            seletor_unitario = ".permissao_unitaria_cidade_digital";
            seletor_total = ".check_total_cidade_digital";
            break;
        case "circuitos":
            seletor_unitario = ".permissao_unitaria_circuitos";
            seletor_total = ".check_total_circuitos";
            break;
        case "cliente":
            seletor_unitario = ".permissao_unitaria_cliente";
            seletor_total = ".check_total_cliente";
            break;
        case "cliente_unidade":
            seletor_unitario = ".permissao_unitaria_cliente_unidade";
            seletor_total = ".check_total_cliente_unidade";
            break;
        case "controle_acesso":
            seletor_unitario = ".permissao_unitaria_controle_acesso";
            seletor_total = ".check_total_controle_acesso";
            break;
        case "empresa":
            seletor_unitario = ".permissao_unitaria_empresa";
            seletor_total = ".check_total_empresa";
            break;
        case "empresa_departamento":
            seletor_unitario = ".permissao_unitaria_empresa_departamento";
            seletor_total = ".check_total_empresa_departamento";
            break;
        case "equipamento":
            seletor_unitario = ".permissao_unitaria_equipamento";
            seletor_total = ".check_total_equipamento";
            break;
        case "fabricante":
            seletor_unitario = ".permissao_unitaria_fabricante";
            seletor_total = ".check_total_fabricante";
            break;
        case "lov":
            seletor_unitario = ".permissao_unitaria_logs_sistema";
            seletor_total = ".check_total_logs_sistema";
            break;
        case "lov":
            seletor_unitario = ".permissao_unitaria_lov";
            seletor_total = ".check_total_lov";
            break;
        case "modelo":
            seletor_unitario = ".permissao_unitaria_modelo";
            seletor_total = ".check_total_modelo";
            break;
        case "usuario":
            seletor_unitario = ".permissao_unitaria_usuario";
            seletor_total = ".check_total_usuario";
            break;
        case "conectividade":
            seletor_unitario = ".permissao_unitaria_conectividade";
            seletor_total = ".check_total_conectividade";
            break;
        case "contrato":
            seletor_unitario = ".permissao_unitaria_contrato";
            seletor_total = ".check_total_contrato";
            break;
        case "contrato_fiscal":
            seletor_unitario = ".permissao_unitaria_contrato_fiscal";
            seletor_total = ".check_total_contrato_fiscal";
            break;
        case "contrato_financeiro":
            seletor_unitario = ".permissao_unitaria_contrato_financeiro";
            seletor_total = ".check_total_contrato_financeiro";
            break;
        case "contrato_penalidade":
            seletor_unitario = ".permissao_unitaria_contrato_penalidade";
            seletor_total = ".check_total_contrato_penalidade";
            break;
        case "estacao_telecon":
            seletor_unitario = ".permissao_unitaria_estacao_telecon";
            seletor_total = ".check_total_estacao_telecon";
            break;
        case "proposta_comercial":
            seletor_unitario = ".permissao_unitaria_proposta_comercial";
            seletor_total = ".check_total_proposta_comercial";
            break;
        case "proposta_comercial_servico":
            seletor_unitario = ".permissao_unitaria_proposta_comercial_servico";
            seletor_total = ".check_total_proposta_comercial_servico";
            break;
        case "proposta_comercial_servico_grupo":
            seletor_unitario = ".permissao_unitaria_proposta_comercial_servico_grupo";
            seletor_total = ".check_total_proposta_comercial_servico_grupo";
            break;
        case "proposta_comercial_servico_unidade":
            seletor_unitario = ".permissao_unitaria_proposta_comercial_servico_unidade";
            seletor_total = ".check_total_proposta_comercial_servico_unidade";
            break;
        case "set_seguranca":
            seletor_unitario = ".permissao_unitaria_set_seguranca";
            seletor_total = ".check_total_set_seguranca";
            break;
        case "set_equipamento":
            seletor_unitario = ".permissao_unitaria_set_equipamento";
            seletor_total = ".check_total_set_equipamento";
            break;
        case "terreno":
            seletor_unitario = ".permissao_unitaria_terreno";
            seletor_total = ".check_total_terreno";
            break;
        case "torre":
            seletor_unitario = ".permissao_unitaria_torre";
            seletor_total = ".check_total_torre";
            break;
        case "unidade_consumidora":
            seletor_unitario = ".permissao_unitaria_unidade_consumidora";
            seletor_total = ".check_total_unidade_consumidora";
            break;
    }
    //verifica o check total
    $(seletor_unitario).each(function () {
        if ($(this).prop("checked"))
        {
            verdadeiro_modulo++;
        }
        else
        {
            falso_modulo++;
        }
    });
    var total_modulo = verdadeiro_modulo + falso_modulo;
    if (total_modulo === verdadeiro_modulo)
    {
        $(seletor_total).prop("checked", true);
    }
    else
    {
        $(seletor_total).prop("checked", false);
    }

}
//Funções que controlam o checkbox do total já iniciar corretamente
function checkAcessoTotal()
{
    "use strict";
    var verdadeiro_global = 0;
    var falso_global = 0;
    //verifica o check total
    $(".permissao_unitaria").each(function () {
        if ($(this).prop("checked"))
        {
            verdadeiro_global++;
        }
        else
        {
            falso_global++;
        }
    });
    var total_global = verdadeiro_global + falso_global;
    if (total_global === verdadeiro_global)
    {
        $(".permissao_total_global").prop("checked", true);
        $(".permissao_total_modulo").prop("checked", true);
    }
    else
    {
        $(".permissao_total_global").prop("checked", false);
    }

}
//Carregando permissões já atribuídas
$(".bt_permissoes").on("click", function(){
    "use strict";
    var dados = buscarPermissoes(ids[0]);
    //Popula os dados
    if (dados)
    {
        $.each(dados[0], function (key, value) {
            $("#" + value.resources_name + value.access_name).prop("checked", true);
            checkAcessoUnitario(value.resources_name);
        });
    }
    $("#roles_name").val(ids[0]);
    $("#titulo_nome_perfil").html(ids[0]);
    //Verifica checkTotal
    checkAcessoTotal();
    //Abre o modal
    $("#modalpermissoes").modal();
});
//Permissão de Super User
$(".permissao_total_global").on("change", function(){
    "use strict";
    var role = $("#roles_name").val();
    var parent = $(this);
    var arrayRoles = [];
    var arrayResources = [];
    var arrayAccessNames = [];
    $.blockUI({
        message: "<img src='" + URLImagensSistema + "/loader_gears.gif' width='50' height='50'/>      Aguarde um momento, estamos processando suas permissões...",
        baseZ: 2000,
        onBlock: function(){
            if ($(parent).prop("checked")) {//Verifica se check total já está checked
                let prom = $(".permissao_unitaria_global").each(function (item) {//Faz o laço entre todos que possuem a classe selecionada
                    var elem = $(".permissao_unitaria_global")[item];
                    var valor = $(elem).val();
                    var resources_access = valor.split(".");
                    var resource = resources_access[0];
                    var access_name = resources_access[1];
                    if (!$(elem).prop("checked"))//Verifica se ele não está checked
                    {
                        arrayRoles.push(role);
                        arrayResources.push(resource);
                        arrayAccessNames.push(access_name);
                        $(elem).prop("checked", true);
                    }
                });
                Promise.all(prom).then(function() {
                    var action = actionCorreta(window.location.href.toString(), "controle_acesso/adicionarPermissao");
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: action,
                        data: {
                            tokenKey: $("#token").attr("name"),
                            tokenValue: $("#token").attr("value"),
                            arrayRoles: arrayRoles,
                            arrayResources: arrayResources,
                            arrayAccessNames: arrayAccessNames
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
                        complete: function () {
                            //Verifica checkTotal
                            checkAcessoTotal();
                            $.unblockUI();
                        }
                    });
                });
            }
            else{
                let prom = $(".permissao_unitaria_global").each(function (item) {//Faz o laço entre todos que possuem a classe selecionada
                    var elem = $(".permissao_unitaria_global")[item];
                    var valor = $(elem).val();
                    var resources_access = valor.split(".");
                    var resource = resources_access[0];
                    var access_name = resources_access[1];
                    if ($(elem).prop("checked"))//Verifica se ele não está checked
                    {
                        arrayRoles.push(role);
                        arrayResources.push(resource);
                        arrayAccessNames.push(access_name);
                        $(elem).prop("checked", false);
                    }
                });
                Promise.all(prom).then(function() {
                    var action = actionCorreta(window.location.href.toString(), "controle_acesso/removerPermissao");
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: action,
                        data: {
                            tokenKey: $("#token").attr("name"),
                            tokenValue: $("#token").attr("value"),
                            arrayRoles: arrayRoles,
                            arrayResources: arrayResources,
                            arrayAccessNames: arrayAccessNames
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
                        complete: function () {
                            //Verifica checkTotal
                            $(".permissao_total_modulo").prop("checked", false);
                            checkAcessoTotal();
                            $.unblockUI();
                        }
                    });
                });
            }

        }
    });
});
//Faz o check automático de todos por módulo
$(".permissao_total_modulo").on("change", function(){
    "use strict";
    var role = $("#roles_name").val();
    var attrname = $(this).attr("name");
    var modulo = attrname.split(".");
    var seletor;
    var parent = $(this);

    var arrayRoles = [];
    var arrayResources = [];
    var arrayAccessNames = [];

    switch (modulo[0])
    {
        case "index":
            seletor = ".permissao_unitaria_dashboard";
            break;
        case "relatorios_gestao":
            seletor = ".permissao_unitaria_relatorios_gestao";
            break;
        case "cidade_digital":
            seletor = ".permissao_unitaria_cidade_digital";
            break;
        case "circuitos":
            seletor = ".permissao_unitaria_circuitos";
            break;
        case "cliente":
            seletor = ".permissao_unitaria_cliente";
            break;
        case "cliente_unidade":
            seletor = ".permissao_unitaria_cliente_unidade";
            break;
        case "controle_acesso":
            seletor = ".permissao_unitaria_controle_acesso";
            break;
        case "empresa":
            seletor = ".permissao_unitaria_empresa";
            break;
        case "empresa_departamento":
            seletor = ".permissao_unitaria_empresa_departamento";
            break;
        case "equipamento":
            seletor = ".permissao_unitaria_equipamento";
            break;
        case "fabricante":
            seletor = ".permissao_unitaria_fabricante";
            break;
        case "logs_sistema":
            seletor = ".permissao_unitaria_logs_sistema";
            break;
        case "lov":
            seletor = ".permissao_unitaria_lov";
            break;
        case "modelo":
            seletor = ".permissao_unitaria_modelo";
            break;
        case "usuario":
            seletor = ".permissao_unitaria_usuario";
            break;
        case "conectividade":
            seletor = ".permissao_unitaria_conectividade";
            break;
        case "contrato":
            seletor = ".permissao_unitaria_contrato";
            break;
        case "contrato_fiscal":
            seletor = ".permissao_unitaria_contrato_fiscal";
            break;
        case "contrato_financeiro":
            seletor = ".permissao_unitaria_contrato_financeiro";
            break;
        case "contrato_penalidade":
            seletor = ".permissao_unitaria_contrato_penalidade";
            break;
        case "estacao_telecon":
            seletor = ".permissao_unitaria_estacao_telecon";
            break;
        case "proposta_comercial":
            seletor = ".permissao_unitaria_proposta_comercial";
            break;
        case "proposta_comercial_servico":
            seletor = ".permissao_unitaria_proposta_comercial_servico";
            break;
        case "proposta_comercial_servico_grupo":
            seletor = ".permissao_unitaria_proposta_comercial_servico_grupo";
            break;
        case "proposta_comercial_servico_unidade":
            seletor = ".permissao_unitaria_proposta_comercial_servico_unidade";
            break;
        case "set_seguranca":
            seletor = ".permissao_unitaria_set_seguranca";
            break;
        case "set_esquipamento":
            seletor = ".permissao_unitaria_set_equipamento";
            break;
        case "terreno":
            seletor = ".permissao_unitaria_terreno";
            break;
        case "torre":
            seletor = ".permissao_unitaria_torre";
            break;
        case "unidade_consumidora":
            seletor = ".permissao_unitaria_unidade_consumidora";
            break;
    }
    $.blockUI({
        message: "<img src='" + URLImagensSistema + "/loader_gears.gif' width='50' height='50'/>      Aguarde um momento, estamos processando suas permissões...",
        baseZ: 2000,
        onBlock: function(){
            if ($(parent).prop("checked")) {//Verifica se check total já está checked
                let prom = $(seletor).each(function (item) {//Faz o laço entre todos que possuem a classe selecionada
                    var elem = $(seletor)[item];
                    var valor = $(elem).val();
                    var resources_access = valor.split(".");
                    var resource = resources_access[0];
                    var access_name = resources_access[1];
                    if (!$(elem).prop("checked"))//Verifica se ele não está checked
                    {
                        arrayRoles.push(role);
                        arrayResources.push(resource);
                        arrayAccessNames.push(access_name);
                        $(elem).prop("checked", true);
                    }
                });
                Promise.all(prom).then(function() {
                    var action = actionCorreta(window.location.href.toString(), "controle_acesso/adicionarPermissao");
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: action,
                        data: {
                            tokenKey: $("#token").attr("name"),
                            tokenValue: $("#token").attr("value"),
                            arrayRoles: arrayRoles,
                            arrayResources: arrayResources,
                            arrayAccessNames: arrayAccessNames
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
                        complete: function () {
                            //Verifica checkTotal
                            checkAcessoTotal();
                            $.unblockUI();
                        }
                    });
                });
            }
            else{
                let prom = $(seletor).each(function (item) {//Faz o laço entre todos que possuem a classe selecionada
                    var elem = $(seletor)[item];
                    var valor = $(elem).val();
                    var resources_access = valor.split(".");
                    var resource = resources_access[0];
                    var access_name = resources_access[1];
                    if ($(elem).prop("checked"))//Verifica se ele não está checked
                    {
                        arrayRoles.push(role);
                        arrayResources.push(resource);
                        arrayAccessNames.push(access_name);
                        $(elem).prop("checked", false);
                    }
                });
                Promise.all(prom).then(function() {
                    var action = actionCorreta(window.location.href.toString(), "controle_acesso/removerPermissao");
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: action,
                        data: {
                            tokenKey: $("#token").attr("name"),
                            tokenValue: $("#token").attr("value"),
                            arrayRoles: arrayRoles,
                            arrayResources: arrayResources,
                            arrayAccessNames: arrayAccessNames
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
                        complete: function () {
                            //Verifica checkTotal
                            checkAcessoTotal();
                            $.unblockUI();
                        }
                    });
                });
            }

        }
    });
});
//Faz a concessão de permissões unitárias
$(".permissao_unitaria").on("change", function(){
    "use strict";
    var valor = $(this).val();
    var resources_access = valor.split(".");
    var resource = resources_access[0];
    var access_name = resources_access[1];
    var role = $("#roles_name").val();
    var arrayRoles = [];
    var arrayResources = [];
    var arrayAccessNames = [];
    //Verifica o check clicado
    if ($(this).prop("checked"))
    {
        arrayRoles.push(role);
        arrayResources.push(resource);
        arrayAccessNames.push(access_name);
        var action = actionCorreta(window.location.href.toString(), "controle_acesso/adicionarPermissao");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                arrayRoles: arrayRoles,
                arrayResources: arrayResources,
                arrayAccessNames: arrayAccessNames
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
            }
        });
    }
    else
    {
        arrayRoles.push(role);
        arrayResources.push(resource);
        arrayAccessNames.push(access_name);
        var action = actionCorreta(window.location.href.toString(), "controle_acesso/removerPermissao");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                arrayRoles: arrayRoles,
                arrayResources: arrayResources,
                arrayAccessNames: arrayAccessNames
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
            }
        });
    }
    //verifica o check total
    checkAcessoUnitario(resource);
    checkAcessoTotal();
});

function reload() {
    window.location.reload(true);
}