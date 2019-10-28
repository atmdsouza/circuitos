//Load de tela
$(document).ajaxStop($.unblockUI);
var URLImagensSistema = "public/images";

//Variáveis Globais
var mudou = false;

//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos)
function inicializar()
{
    'use strict';
    //Configuração Específica do Datatable
    $("#datatable_listar").DataTable({
        select: false,
        language: {
            select: false
        },
        order: [[7, "asc"],[0, "desc"]]//Ordenação passando a lista de ativos primeiro
    });
    autocompletarCliente('lid_cliente','id_cliente');
    autocompletarCodigoServico('codigo_servico','id_codigo_servico','grupo','subgrupo');
    autocompletarServico('descricao_servico','id_servico','grupo','subgrupo');
}

function verificarAlteracao()
{
    'use strict';
    $('form').on('change paste', 'input, select, textarea', function(){
        mudou = true;
    });
}

function confirmaCancelar(modal)
{
    'use strict';
    verificarAlteracao();
    if (mudou)
    {
        swal({
            title: "Sair sem Salvar",
            text: "Deseja realmente sair sem salvar?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim",
            cancelButtonText: "Não"
        }).then(() => {
            $("#"+modal).modal('hide');
            limparModalBootstrap(modal);
            mudou = false;
        }).catch(swal.noop);
    }
    else
    {
        $("#"+modal).modal('hide');
        limparModalBootstrap(modal);
    }
}

function criar()
{
    'use strict';
    $("#formCadastro input").removeAttr('readonly', 'readonly');
    $("#formCadastro select").removeAttr('readonly', 'readonly');
    $("#formCadastro textarea").removeAttr('readonly', 'readonly');
    $("#salvarCadastro").val('criar');
    $("#salvarCadastro").show();
    $("#modalCadastro").modal();
}

function editar(id)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'visualizarConectividade', id: id},
        complete: function () {
            $("#formCadastro input").removeAttr('readonly', 'readonly');
            $("#formCadastro select").removeAttr('readonly', 'readonly');
            $("#formCadastro textarea").removeAttr('readonly', 'readonly');
            $("#salvarCadastro").val('editar');
            $("#salvarCadastro").show();
            $("#modalCadastro").modal();
        },
        error: function (data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function (data) {
            $('#id').val(data.dados.id);
            $('#lid_cidade_digital').val(data.dados.desc_cidade_digital);
            $('#id_cidade_digital').val(data.dados.id_cidade_digital);
            $('#lid_tipo').val(data.dados.desc_tipo);
            $('#id_tipo').val(data.dados.id_tipo);
            $('#descricao').val(data.dados.descricao);
            $('#endereco').val(data.dados.endereco);
        }
    });
}

function salvar()
{
    'use strict';
    var acao = $('#salvarCadastro').val();
    $("#formCadastro").validate({
        rules : {
            id_cliente:{
                required: true
            },
            id_tipo_proposta:{
                required: true
            },
            id_localizacao:{
                required: true
            },
            id_status:{
                required: true
            },
            data_proposta:{
                required: true
            },
            numero:{
                required: true
            },
            vencimento:{
                required: true
            },
            valor_global:{
                required: true,
                maiorQueZero: true
            },
            objetivo:{
                required: true
            },
            objetivo_especifico:{
                required: true
            },
            descritivo:{
                required: true
            },
            responsabilidade:{
                required: true
            },
            condicoes_pgto:{
                required: true
            },
            prazo_execucao:{
                required: true
            },
            consideracoes:{
                required: true
            }
        },
        messages:{
            id_cliente:{
                required:"É necessário informar um Cliente"
            },
            id_tipo_proposta:{
                required: "É necessário informar um tipo de proposta"
            },
            id_localizacao:{
                required: "É necessário informar um departamento"
            },
            id_status:{
                required: "É necessário informar um status"
            },
            data_proposta:{
                required: "É necessário informar a data da proposta"
            },
            numero:{
                required: "É necessário informar o número da proposta"
            },
            vencimento:{
                required: "É necessário informar a data de vencimento"
            },
            valor_global:{
                required: "É necessário informar o valor global"
            },
            objetivo:{
                required: "É necessário informar um objetivo"
            },
            objetivo_especifico:{
                required: "É necessário informar um objetivo específico"
            },
            descritivo:{
                required: "É necessário informar um descritivo"
            },
            responsabilidade:{
                required: "É necessário informar a responsabilidade"
            },
            condicoes_pgto:{
                required: "É necessário informar as condições de pagamento"
            },
            prazo_execucao:{
                required: "É necessário informar um prazo de execução"
            },
            consideracoes:{
                required: "É necessário informar as considerações"
            }
        },
        submitHandler: function(form) {
            var dados = $("#formCadastro").serialize();
            var action = actionCorreta(window.location.href.toString(), "proposta_comercial/" + acao);
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: action,
                data: {
                    tokenKey: $("#token").attr("name"),
                    tokenValue: $("#token").attr("value"),
                    dados: dados
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
                            title: data.titulo,
                            text: data.mensagem,
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
                            title: data.titulo,
                            text: data.mensagem,
                            type: "error"
                        });
                    }
                }
            });
        }
    });
}

function ativar(id, descr)
{
    'use strict';
    swal({
        title: "Tem certeza que deseja ativar o registro \""+ descr +"\"?",
        text: "O sistema irá ativar o registro \""+ descr +"\" com essa ação.",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, ativar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "conectividade/ativar");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                dados: {id: id}
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
                        title: "Ativado!",
                        text: "O registro \""+ descr +"\" foi ativado com sucesso.",
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

function inativar(id, descr)
{
    'use strict';
    swal({
        title: "Tem certeza que deseja inativar o registro \""+ descr +"\"?",
        text: "O sistema irá inativar o registro \""+ descr +"\" com essa ação.",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, inativar!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "conectividade/inativar");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                dados: {id: id}
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
                        title: "Inativado!",
                        text: "O registro \""+ descr +"\" foi inativado com sucesso.",
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

function excluir(id, descr)
{
    'use strict';
    swal({
        title: "Tem certeza que deseja excluir o registro \""+ descr +"\"?",
        text: "O sistema irá excluir o registro \""+ descr +"\" com essa ação. Essa é uma ação irreversível!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!"
    }).then((result) => {
        var action = actionCorreta(window.location.href.toString(), "conectividade/excluir");
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: action,
            data: {
                tokenKey: $("#token").attr("name"),
                tokenValue: $("#token").attr("value"),
                dados: {id: id}
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
                        title: "Excluído!",
                        text: "O registro \""+ descr +"\" foi excluído com sucesso.",
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
                        title: "Excluir",
                        text: data.mensagem,
                        type: "error"
                    });
                }
            }
        });
    });
}

function visualizar(id)
{
    'use strict';
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxVisualizar");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'visualizarConectividade', id: id},
        complete: function () {
            $("#formCadastro input").attr('readonly', 'readonly');
            $("#formCadastro select").attr('readonly', 'readonly');
            $("#formCadastro textarea").attr('readonly', 'readonly');
            $("#salvarCadastro").hide();
            $("#modalCadastro").modal();
        },
        error: function (data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function (data) {
            $('#id').val(data.dados.id);
            $('#lid_cidade_digital').val(data.dados.desc_cidade_digital);
            $('#id_cidade_digital').val(data.dados.id_cidade_digital);
            $('#id_tipo').val(data.dados.id_tipo).selected = "true";
            $('#descricao').val(data.dados.descricao);
            $('#endereco').val(data.dados.endereco);
        }
    });
}

function limpar()
{
    'use strict';
    $('#fieldPesquisa').val('');
    $('#formPesquisa').submit();
}

function criarComponente()
{
    'use strict';
    limparDadosFormComponente();
    $('#bt_inserir_componente').val('Inserir');
    $('#grupo').focus();
}

function inserirComponente()
{
    'use strict';
    //Dados
    var codigo_servico = $('#codigo_servico').val();
    var descricao_servico = $('#descricao_servico').val();
    var id_servico = $('#id_servico').val();
    var grandeza = $('#grandeza').val();
    var quantidade_unitaria = $('#quantidade_unitaria_servico').val();
    var ds_tem_imposto = document.getElementById("tem_imposto").options[document.getElementById("tem_imposto").selectedIndex].text;
    var tem_imposto = $('#tem_imposto').val();
    var ds_tem_reajuste = document.getElementById("tem_reajuste").options[document.getElementById("tem_reajuste").selectedIndex].text;
    var tem_reajuste = $('#tem_reajuste').val();
    var mes_inicial = $('#mes_inicial_servico').val();
    var vigencia = $('#vigencia_servico').val();
    var valor_unitario = accounting.unformat($('#valor_unitario_servico').val(), ",");
    var valor_total = valor_unitario * accounting.unformat(quantidade_unitaria, ",");
    var valor_impostos = accounting.unformat(valor_total, ",") * (accounting.unformat($('#imposto').val(), ",") * 0.01);
    var valor_reajuste = accounting.unformat(valor_total, ",") * (accounting.unformat($('#reajuste').val(), ",") * 0.01);
    var valor_total_reajuste = (tem_reajuste) ? accounting.unformat(valor_total, ",") + accounting.unformat(valor_reajuste, ",") : 0;
    var valor_total_imposto = (tem_imposto) ? accounting.unformat(valor_total, ",") + accounting.unformat(valor_impostos, ",") : 0;
    if(id_servico !== '' && quantidade_unitaria > 0 && tem_imposto !== '' && tem_reajuste !== '' && mes_inicial !== '' && vigencia !== ''){//Campos Obrigatórios
        var linhas = null;
        linhas += '<tr class="tr_remove">';
        linhas += '<td>'+ codigo_servico +'</td>';
        linhas += '<td>'+ descricao_servico +'<input name="id_proposta_comercial_servicos_item[]" type="hidden" value="'+ id_servico +'" /></td>';
        linhas += '<td>'+ grandeza +'</td>';
        linhas += '<td>'+ ds_tem_imposto +'<input name="imposto_item[]" type="hidden" value="'+ tem_imposto +'" /></td>';
        linhas += '<td>'+ ds_tem_reajuste +'<input name="reajuste_item[]" type="hidden" value="'+ tem_reajuste +'" /></td>';
        linhas += '<td>'+ quantidade_unitaria +'<input name="quantidade_item[]" type="hidden" value="'+ quantidade_unitaria +'" /></td>';
        linhas += '<td>'+ mes_inicial +'<input name="mes_inicial_item[]" type="hidden" value="'+ mes_inicial +'" /></td>';
        linhas += '<td>'+ vigencia +'<input name="vigencia_item[]" type="hidden" value="'+ vigencia +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_unitario, "R$ ", 2, ".", ",") +'<input name="valor_unitario_item[]" type="hidden" value="'+ valor_unitario +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_total, "R$ ", 2, ".", ",") +'<input name="valor_total_item[]" type="hidden" value="'+ valor_total +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_total_reajuste, "R$ ", 2, ".", ",") +'<input name="valor_total_reajuste_item[]" type="hidden" value="'+ valor_total_reajuste +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_impostos, "R$ ", 2, ".", ",") +'<input name="valor_impostos_item[]" type="hidden" value="'+ valor_impostos +'" /></td>';
        linhas += '<td>'+ accounting.formatMoney(valor_total_imposto, "R$ ", 2, ".", ",") +'<input name="valor_total_impostos_item[]" type="hidden" value="'+ valor_total_imposto +'" /></td>';
        linhas += '<td><a data-valor-total="'+ valor_total +'" href="javascript:void(0)" onclick="RemoveTableRow(this);removerValoresTotais(this)" class="botoes_acao"><img src="public/images/sistema/excluir.png" title="Excluir" alt="Excluir" height="25" width="25"></a></td>';
        linhas += '</tr>';
        $("#tabela_componentes").append(linhas);
        atualizaValorTotal(valor_total);
        atualizaValoresMensais(valor_total);
        $('#tabela_componentes').removeAttr('style','display: none;');
        $('#tabela_componentes').attr('style', 'display: table;');
        limparDadosFormComponente();
    } else {
        swal({
            title: "Campos Obrigatórios!",
            text: "Você precisa preencher todos os campos obrigatórios no formulário!",
            type: "warning"
        });
    }
}

function atualizaValorTotal(valor_total)
{
    'use strict';
    var valor_total_atual = accounting.unformat($('#demonstrativo_valor_total').html(), ",");
    var novo_valor_total = valor_total_atual + valor_total;
    $('#demonstrativo_valor_total').html('');
    $('#demonstrativo_valor_total').html(accounting.formatMoney(novo_valor_total, '', 2, '.', ','));
    $('#valor_global').val(accounting.formatMoney(novo_valor_total, '', 2, '.', ','));
}

function atualizaValoresMensais(valor_total)
{
    'use strict';
    var valor_total_atual = accounting.unformat($('#demonstrativo_valor_total').html(), ",");
    var novo_valor_total = valor_total_atual + valor_total;
    var valor_mes = accounting.unformat(novo_valor_total, ",") / 12;
    $('#mes_1').html('');
    $('#mes_1').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jan').val(valor_mes);
    $('#mes_2').html('');
    $('#mes_2').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#fev').val(valor_mes);
    $('#mes_3').html('');
    $('#mes_3').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#mar').val(valor_mes);
    $('#mes_4').html('');
    $('#mes_4').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#abr').val(valor_mes);
    $('#mes_5').html('');
    $('#mes_5').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#mai').val(valor_mes);
    $('#mes_6').html('');
    $('#mes_6').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jun').val(valor_mes);
    $('#mes_7').html('');
    $('#mes_7').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jul').val(valor_mes);
    $('#mes_8').html('');
    $('#mes_8').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#ago').val(valor_mes);
    $('#mes_9').html('');
    $('#mes_9').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#set').val(valor_mes);
    $('#mes_10').html('');
    $('#mes_10').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#out').val(valor_mes);
    $('#mes_11').html('');
    $('#mes_11').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#nov').val(valor_mes);
    $('#mes_12').html('');
    $('#mes_12').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#dez').val(valor_mes);
}

function removerValoresTotais(seletor)
{
    'use strict';
    var valor_total_removido = accounting.unformat($(seletor).attr('data-valor-total'), ",");
    // Redução do Valor Total
    var valor_total_atual = accounting.unformat($('#demonstrativo_valor_total').html(), ",");
    var novo_valor_total = valor_total_atual - valor_total_removido;
    $('#demonstrativo_valor_total').html('');
    $('#demonstrativo_valor_total').html(accounting.formatMoney(novo_valor_total, '', 2, '.', ','));
    $('#valor_global').val(accounting.formatMoney(novo_valor_total, '', 2, '.', ','));
    // Redução dos valores mensais
    var valor_mes = accounting.unformat(novo_valor_total, ",") / 12;
    $('#mes_1').html('');
    $('#mes_1').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jan').val(valor_mes);
    $('#mes_2').html('');
    $('#mes_2').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#fev').val(valor_mes);
    $('#mes_3').html('');
    $('#mes_3').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#mar').val(valor_mes);
    $('#mes_4').html('');
    $('#mes_4').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#abr').val(valor_mes);
    $('#mes_5').html('');
    $('#mes_5').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#mai').val(valor_mes);
    $('#mes_6').html('');
    $('#mes_6').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jun').val(valor_mes);
    $('#mes_7').html('');
    $('#mes_7').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#jul').val(valor_mes);
    $('#mes_8').html('');
    $('#mes_8').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#ago').val(valor_mes);
    $('#mes_9').html('');
    $('#mes_9').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#set').val(valor_mes);
    $('#mes_10').html('');
    $('#mes_10').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#out').val(valor_mes);
    $('#mes_11').html('');
    $('#mes_11').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#nov').val(valor_mes);
    $('#mes_12').html('');
    $('#mes_12').html(accounting.formatMoney(valor_mes, '', 2, '.', ','));
    $('#dez').val(valor_mes);
}

function cancelarComponente()
{
    'use strict';
    verificarAlteracao();
    limparDadosFormComponente();
}

function limparDadosFormComponente()
{
    'use strict';
    $('#valor_unitario').val('');
    $('#quantidade_unitaria').val('');
    $('#codigo_servico').val('');
    $('#id_codigo_servico').val('');
    $('#descricao_servico').val('');
    $('#id_servico').val('');
    $('#grupo').val(null).selected = 'true';
    $('#tem_imposto').val('0').selected = 'true';
    $('#tem_reajuste').val('0').selected = 'true';
    $('#mes_inicial').val('1').selected = 'true';
    $('#vigencia').val('12').selected = 'true';
    resetSelecaoSubgrupo();
    $('#grupo').focus();
}

function resetSelecaoSubgrupo() {
    'use strict';
    $('.remover_subgrupo').remove();
    $('#subgrupo').attr('disabled', 'disabled');
}

function mostrarSubgrupo()
{
    'use strict';
    var id_servico = $('#grupo').val();
    if (id_servico){//Fazer a busca e montar o outro select
        var action = actionCorreta(window.location.href.toString(), "core/processarAjaxSelect");
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: action,
            data: {metodo: 'selectSubGrupo', id: id_servico},
            complete: function () {
                $('#subgrupo').removeAttr('disabled');
            },
            error: function (data) {
                if (data.status && data.status === 401) {
                    swal({
                        title: "Erro de Permissão",
                        text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                        type: "warning"
                    });
                }
            },
            success: function (data) {
                $('.remover_subgrupo').remove();
                $.each(data.dados, function (key, value) {
                    var opcao = '<option class="remover_subgrupo" value="' + value.id + '">' + value.descricao + '</option>';
                    $('#subgrupo').append(opcao);
                });
            }
        });
    } else {//Mostrar a mensagem de elemento vazio para subgrupo
        $('.remover_subgrupo').remove();
        $('#subgrupo').attr('disabled', 'disabled');
    }
}

function selecaoDescricaoServico()
{
    'use strict';
    var id_servico = $('#id_codigo_servico').val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxSelect");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'selectIdServico', id: id_servico},
        error: function (data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function (data) {
            $('#descricao_servico').val(data.dados.descricao);
            $('#id_servico').val(data.dados.id);
            $('#grandeza').val(data.dados.grandeza);
        }
    });
}

function selectCodigoServico()
{
    'use strict';
    var id_servico = $('#id_servico').val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxSelect");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'selectIdServico', id: id_servico},
        error: function (data) {
            if (data.status && data.status === 401) {
                swal({
                    title: "Erro de Permissão",
                    text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
                    type: "warning"
                });
            }
        },
        success: function (data) {
            $('#codigo_servico').val(data.dados.codigo_legado);
            $('#id_codigo_servico').val(data.dados.id);
            $('#grandeza').val(data.dados.grandeza);
        }
    });
}