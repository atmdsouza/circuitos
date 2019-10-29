//Variáveis Globais
var listCidade = [];
var ci = 0;
var listTerrenos = [];
var t = 0;
var listTorres = [];
var r = 0;
var listSetEquipamentos = [];
var se = 0;
var listSetSegurancas = [];
var s = 0;
var listUnidadeConsumidora = [];
var u = 0;
var listCidadeDigital = [];
var i = 0;
var listGrupo = [];
var g = 0;
var listUnidade = [];
var ug = 0;
var listFabricante = [];
var fb = 0;
var listModelo = [];
var m = 0;
var listEquipamento = [];
var e = 0;
var listFornecedor = [];
var o = 0;
var listAgrupadora = [];
var f = 0;
var listCliente = [];
var c = 0;
var listCodigoServico = [];
var cs = 0;
var listServico = [];
var z = 0;

/*
* Sessão de Campos Autocomplete
* */
function autocompletarCodigoServico(id_label, id_valor, id_grupo, id_subgrupo)
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_codigo_servico = $("#"+id_label);
    var vl_codigo_servico = $("#"+id_valor);
    var vl_grupo = $("#"+id_grupo).val();
    var vl_subgrupo = $("#"+id_subgrupo).val();
    var string = ac_codigo_servico.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'codigoServicosAtivos', string: string, id_grupo: vl_grupo, id_subgrupo: vl_subgrupo},
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
            if (data.operacao) {
                listCodigoServico = [];
                $.each(data.dados, function (key, value) {
                    listCodigoServico.push({value: value.codigo_legado, data: value.id});
                });
                if(cs === 0) {
                    //Autocomplete
                    ac_codigo_servico.autocomplete({
                        lookup: listCodigoServico,
                        onSelect: function (suggestion) {
                            vl_codigo_servico.val(suggestion.data);
                        }
                    });
                    cs++;
                } else {
                    //Autocomplete
                    ac_codigo_servico.autocomplete().setOptions( {
                        lookup: listCodigoServico
                    });
                }
            } else {
                vl_codigo_servico.val("");
                ac_codigo_servico.val("");
            }
        }
    });

}

function autocompletarServico(id_label, id_valor, id_grupo, id_subgrupo)
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_servico = $("#"+id_label);
    var vl_servico = $("#"+id_valor);
    var vl_grupo = $("#"+id_grupo).val();
    var vl_subgrupo = $("#"+id_subgrupo).val();
    var string = ac_servico.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'servicosAtivos', string: string, id_grupo: vl_grupo, id_subgrupo: vl_subgrupo},
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
            if (data.operacao) {
                listServico = [];
                $.each(data.dados, function (key, value) {
                    listServico.push({value: value.descricao, data: value.id});
                });
                if(z === 0) {
                    //Autocomplete
                    ac_servico.autocomplete({
                        lookup: listServico,
                        onSelect: function (suggestion) {
                            vl_servico.val(suggestion.data);
                        }
                    });
                    z++;
                } else {
                    //Autocomplete
                    ac_servico.autocomplete().setOptions( {
                        lookup: listServico
                    });
                }
            } else {
                vl_servico.val("");
                ac_servico.val("");
            }
        }
    });
}

function autocompletarContratoVinculado(id_label, id_valor)
{

}

function autocompletarCliente(id_label, id_valor)
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_cliente = $("#"+id_label);
    var vl_cliente = $("#"+id_valor);
    var string = ac_cliente.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'clientesAtivos', string: string},
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
            if (data.operacao) {
                listCliente = [];
                $.each(data.dados, function (key, value) {
                    listCliente.push({value: value.nome, data: value.id});
                });
                if(c === 0) {
                    //Autocomplete
                    ac_cliente.autocomplete({
                        lookup: listCliente,
                        onSelect: function (suggestion) {
                            vl_cliente.val(suggestion.data);
                        }
                    });
                    c++;
                } else {
                    //Autocomplete
                    ac_cliente.autocomplete().setOptions( {
                        lookup: listCliente
                    });
                }
            } else {
                vl_cliente.val("");
                ac_cliente.val("");
            }
        }
    });
}

function autocompletarContrato(id_label, id_valor)
{

}

function autocompletarGrupo(id_label, id_valor)
{
    "use strict";
    //Autocomplete
    var ac_servico_grupo = $("#"+id_label);
    var vl_servico_grupo = $("#"+id_valor);
    var string = ac_servico_grupo.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'servicoGruposAtivos', string: string},
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
            if (data.operacao) {
                listGrupo = [];
                $.each(data.dados, function (key, value) {
                    listGrupo.push({value: value.descricao, data: value.id});
                });
                if(g === 0) {
                    //Autocomplete
                    ac_servico_grupo.autocomplete({
                        lookup: listGrupo,
                        onSelect: function (suggestion) {
                            vl_servico_grupo.val(suggestion.data);
                        }
                    });
                    g++;
                } else {
                    //Autocomplete
                    ac_servico_grupo.autocomplete().setOptions( {
                        lookup: listGrupo
                    });
                }
            } else {
                vl_servico_grupo.val("");
                ac_servico_grupo.val("");
            }
        }
    });
}

function autocompletarUnidade(id_label, id_valor)
{
    "use strict";
    //Autocomplete
    var ac_servico_unidade = $("#"+id_label);
    var vl_servico_unidade = $("#"+id_valor);
    var string = ac_servico_unidade.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'servicoUnidadesAtivos', string: string},
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
            if (data.operacao) {
                listUnidade = [];
                $.each(data.dados, function (key, value) {
                    listUnidade.push({value: value.descricao, data: value.id});
                });
                if(ug === 0) {
                    //Autocomplete
                    ac_servico_unidade.autocomplete({
                        lookup: listUnidade,
                        onSelect: function (suggestion) {
                            vl_servico_unidade.val(suggestion.data);
                        }
                    });
                    ug++;
                } else {
                    //Autocomplete
                    ac_servico_unidade.autocomplete().setOptions( {
                        lookup: listUnidade
                    });
                }
            } else {
                vl_servico_unidade.val("");
                ac_servico_unidade.val("");
            }
        }
    });
}

function autocompletarCidadeDigital(id_label, id_valor)
{
    "use strict";
    var ac_cidadedigital = $("#"+id_label);
    var vl_cidadedigital = $("#"+id_valor);
    var string = ac_cidadedigital.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'cidadesDigitaisAtivas', string: string},
        success: function (data) {
            if (data.operacao) {
                listCidadeDigital = [];
                $.each(data.dados, function (key, value) {
                    listCidadeDigital.push({value: value.descricao, data: value.id});
                });
                if(i === 0) {
                    //Autocomplete
                    ac_cidadedigital.autocomplete({
                        lookup: listCidadeDigital,
                        onSelect: function (suggestion) {
                            vl_cidadedigital.val(suggestion.data);
                        }
                    });
                    i++;
                } else {
                    //Autocomplete
                    ac_cidadedigital.autocomplete().setOptions( {
                        lookup: listCidadeDigital
                    });
                }
            } else {
                vl_cidadedigital.val("");
                ac_cidadedigital.val("");
            }
        }
    });
}

function autocompletarCidade(id_chave, id_label, id_valor)
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_cidade = $("#"+id_label);
    var vl_cidade = $("#"+id_valor);
    var id_estado = $("#"+id_chave).val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'completarCidades', id_estado: id_estado},
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
            if (data.operacao) {
                listCidade = [];
                $.each(data.dados, function (key, value) {
                    listCidade.push({value: value.cidade, data: value.id});
                });
                if(ci === 0) {
                    //Autocomplete
                    ac_cidade.autocomplete({
                        lookup: listCidade,
                        onSelect: function (suggestion) {
                            vl_cidade.val(suggestion.data);
                        }
                    });
                    ci++;
                } else {
                    //Autocomplete
                    ac_cidade.autocomplete().setOptions( {
                        lookup: listCidade
                    });
                }
            } else {
                vl_cidade.val("");
                ac_cidade.val("");
            }
        }
    });
}

function autocompletarTerreno(id_label, id_valor)
{
    "use strict";
    var input_autocomplete = $("#"+id_label);
    var input_valor = $("#"+id_valor);
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'terrenosAtivos', string: string},
        success: function (data) {
            if (data.operacao) {
                listTerrenos = [];
                $.each(data.dados, function (key, value) {
                    listTerrenos.push({value: value.descricao, data: value.id});
                });
                if(t === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listTerrenos,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    t++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listTerrenos
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}

function autocompletarTorre(id_label, id_valor)
{
    "use strict";
    var input_autocomplete = $("#"+id_label);
    var input_valor = $("#"+id_valor);
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'torresAtivas', string: string},
        success: function (data) {
            if (data.operacao) {
                listTorres = [];
                $.each(data.dados, function (key, value) {
                    listTorres.push({value: value.descricao, data: value.id});
                });
                if(r === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listTorres,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    r++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listTorres
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}

function autocompletarSetEquipamento(id_label, id_valor)
{
    "use strict";
    var input_autocomplete = $("#"+id_label);
    var input_valor = $("#"+id_valor);
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'setsEquipamentosAtivos', string: string},
        success: function (data) {
            if (data.operacao) {
                listSetEquipamentos = [];
                $.each(data.dados, function (key, value) {
                    listSetEquipamentos.push({value: value.descricao, data: value.id});
                });
                if(se === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listSetEquipamentos,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    se++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listSetEquipamentos
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}

function autocompletarSetSeguranca(id_label, id_valor)
{
    "use strict";
    var input_autocomplete = $("#"+id_label);
    var input_valor = $("#"+id_valor);
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'setsSegurancaAtivos', string: string},
        success: function (data) {
            if (data.operacao) {
                listSetSegurancas = [];
                $.each(data.dados, function (key, value) {
                    listSetSegurancas.push({value: value.descricao, data: value.id});
                });
                if(s === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listSetSegurancas,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    s++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listSetSegurancas
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}

function autocompletarUnidadeConsumidora(id_label, id_valor)
{
    "use strict";
    var input_autocomplete = $("#"+id_label);
    var input_valor = $("#"+id_valor);
    var string = input_autocomplete.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'unidadeConsumidorasAtivas', string: string},
        success: function (data) {
            if (data.operacao) {
                listUnidadeConsumidora = [];
                $.each(data.dados, function (key, value) {
                    listUnidadeConsumidora.push({value: value.codigo_conta_contrato, data: value.id});
                });
                if(u === 0) {
                    //Autocomplete
                    input_autocomplete.autocomplete({
                        lookup: listUnidadeConsumidora,
                        onSelect: function (suggestion) {
                            input_valor.val(suggestion.data);
                        }
                    });
                    u++;
                } else {
                    //Autocomplete
                    input_autocomplete.autocomplete().setOptions( {
                        lookup: listUnidadeConsumidora
                    });
                }
            } else {
                input_valor.val("");
                input_autocomplete.val("");
            }
        }
    });
}

function autocompletarNumeroSeriePatrimonio()
{
    "use strict";
    var ac_serie_patrimonio = $("#i_lnumero_serie");
    var vl_serie_patrimonio = $("#i_numero_serie");
    var listSeriePatrimonio = [];
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'equipamentoSeriePatrimonioAtivos'},
        beforeSend: function () {
            vl_serie_patrimonio.val("");
            ac_serie_patrimonio.val("");
            listSeriePatrimonio = [];
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
                $.each(data.dados, function (key, value) {
                    listSeriePatrimonio.push({value: value, data: value});
                });
            } else {
                vl_serie_patrimonio.val("");
                ac_serie_patrimonio.val("");
            }
            //Autocomplete
            ac_serie_patrimonio.autocomplete({
                lookup: listSeriePatrimonio,
                onSelect: function (suggestion) {
                    vl_serie_patrimonio.val(suggestion.data);
                    var numero_serie = suggestion.data;
                    if (numero_serie !== ""){
                        var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: action,
                            data: {metodo: 'equipamentoNumeroSerie', numero_serie: numero_serie},
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
                                    $("#i_lid_fabricante").val(data.nome_fabricante);
                                    $("#i_lid_modelo").val(data.nome_modelo);
                                    $("#i_lid_equipamento").val(data.nome_equipamento + " (" + numero_serie + " / " + data.numero_patrimonio + ")");
                                    $("#i_id_fabricante").val(data.id_fabricante);
                                    $("#i_id_modelo").val(data.id_modelo);
                                    $("#i_id_equipamento").val(data.id_equipamento);
                                    $("#i_lid_modelo").removeAttr("disabled");
                                    $("#i_lid_equipamento").removeAttr("disabled");
                                }
                            }
                        });
                    }
                }
            });
        }
    });
}

function autocompletarFabricante()
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_fabricante = $("#i_lid_fabricante");
    var vl_fabricante = $("#i_id_fabricante");
    var ac_model = $("#i_lid_modelo");
    var vl_model = $("#i_id_modelo");
    var ac_equip = $("#i_lid_equipamento");
    var vl_equip = $("#i_id_equipamento");
    var string = ac_fabricante.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'fabricantesAtivos', string: string},
        beforeSend: function () {
            vl_model.val("");
            ac_model.val("");
            ac_model.attr("disabled", "true");
            ac_equip.val("");
            vl_equip.val("");
            ac_equip.attr("disabled", "true");
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
            if (data.operacao) {
                listFabricante = [];
                $.each(data.dados, function (key, value) {
                    listFabricante.push({value: value.nome, data: value.id});
                });
                if(fb === 0) {
                    //Autocomplete
                    ac_fabricante.autocomplete({
                        lookup: listFabricante,
                        onSelect: function (suggestion) {
                            vl_fabricante.val(suggestion.data);
                            ac_model.removeAttr("disabled");
                            vl_equip.val("");
                            ac_equip.val("");
                            ac_equip.attr("disabled", "true");
                        }
                    });
                    fb++;
                } else {
                    //Autocomplete
                    ac_fabricante.autocomplete().setOptions( {
                        lookup: listFabricante
                    });
                }
            } else {
                vl_fabricante.val("");
                ac_fabricante.val("");
            }
        }
    });
}

function autocompletarModelo()
{
    "use strict";
    //Autocomplete de Fabricante
    var vl_fabricante = $("#i_id_fabricante");
    var ac_model = $("#i_lid_modelo");
    var vl_model = $("#i_id_modelo");
    var ac_equip = $("#i_lid_equipamento");
    var vl_equip = $("#i_id_equipamento");
    var string = ac_model.val();
    var id = vl_fabricante.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'modelosAtivos', id: id, string: string},
        beforeSend: function () {
            ac_equip.val("");
            vl_equip.val("");
            ac_equip.attr("disabled", "true");
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
            if (data.operacao) {
                listModelo = [];
                $.each(data.dados, function (key, value) {
                    listModelo.push({value: value.modelo, data: value.id});
                });
                if(m === 0) {
                    //Autocomplete
                    ac_model.autocomplete({
                        lookup: listModelo,
                        onSelect: function (suggestion) {
                            vl_model.val(suggestion.data);
                            ac_equip.removeAttr("disabled");
                        }
                    });
                    m++;
                } else {
                    //Autocomplete
                    ac_model.autocomplete().setOptions( {
                        lookup: listModelo
                    });
                }
            } else {
                vl_model.val("");
                ac_model.val("");
            }
        }
    });
}

function autocompletarEquipamento()
{
    "use strict";
    //Autocomplete de Fabricante
    var vl_model = $("#i_id_modelo");
    var ac_equip = $("#i_lid_equipamento");
    var vl_equip = $("#i_id_equipamento");
    var id = vl_model.val();
    var string = ac_equip.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'equipamentosAtivos', string: string, id: id},
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
            if (data.operacao) {
                listEquipamento = [];
                $.each(data.dados, function (key, value) {
                    listEquipamento.push({value: value.nome, data: value.id});
                });
                if(e === 0) {
                    //Autocomplete
                    ac_equip.autocomplete({
                        lookup: listEquipamento,
                        onSelect: function (suggestion) {
                            vl_equip.val(suggestion.data);
                        }
                    });
                    e++;
                } else {
                    //Autocomplete
                    ac_equip.autocomplete().setOptions( {
                        lookup: listEquipamento
                    });
                }
            } else {
                vl_equip.val("");
                ac_equip.val("");
            }
        }
    });
}

function autocompletarFornecedor(id_label, id_valor)
{
    "use strict";
    //Autocomplete de Fabricante
    var ac_fornecedor = $("#"+id_label);
    var vl_fornecedor = $("#"+id_valor);
    var string = ac_fornecedor.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'fornecedoresAtivos', string: string},
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
            if (data.operacao) {
                listFornecedor = [];
                $.each(data.dados, function (key, value) {
                    listFornecedor.push({value: value.nome, data: value.id});
                });
                if(o === 0) {
                    //Autocomplete
                    ac_fornecedor.autocomplete({
                        lookup: listFornecedor,
                        onSelect: function (suggestion) {
                            vl_fornecedor.val(suggestion.data);
                        }
                    });
                    o++;
                } else {
                    //Autocomplete
                    ac_fornecedor.autocomplete().setOptions( {
                        lookup: listFornecedor
                    });
                }
            } else {
                vl_fornecedor.val("");
                ac_fornecedor.val("");
            }
        }
    });
}

function autocompletarAgrupadora(id_label, id_valor)
{
    "use strict";
    //Autocomplete
    var ac_agrupadora = $("#"+id_label);
    var vl_agrupadora = $("#"+id_valor);
    var string = ac_agrupadora.val();
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {metodo: 'contasAgrupadorasAtivas', string: string},
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
            if (data.operacao) {
                listAgrupadora = [];
                $.each(data.dados, function (key, value) {
                    listAgrupadora.push({value: value.codigo_conta_contrato, data: value.id});
                });
                if(f === 0) {
                    //Autocomplete
                    ac_agrupadora.autocomplete({
                        lookup: listAgrupadora,
                        onSelect: function (suggestion) {
                            vl_agrupadora.val(suggestion.data);
                        }
                    });
                    f++;
                } else {
                    //Autocomplete
                    ac_agrupadora.autocomplete().setOptions( {
                        lookup: listAgrupadora
                    });
                }
            } else {
                vl_agrupadora.val("");
                ac_agrupadora.val("");
            }
        }
    });
}

/*
* Preenchimento automático de endereço
* */
function preencherEndereco(id_cep, id_endereco, id_numero, id_complemento, id_bairro, id_cidade, id_estado, id_sigla_estado, id_latitude, id_longitude)
{
    "use strict";
    var bairro = $("#"+id_bairro);
    var cidade = $("#"+id_cidade);
    var endereco = $("#"+id_endereco);
    var sigla_estado = $("#"+id_sigla_estado);
    var estado = $("#"+id_estado);
    var latitude = $("#"+id_latitude);
    var longitude = $("#"+id_longitude);
    var numero = $("#"+id_numero);
    var cep_t = $("#"+id_cep).val();
    if (cep_t) {
        var cep = formata_cep(cep_t);
        var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: action,
            data: {metodo: 'completarEndereco', cep: cep},
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
                    var logradouro = endereco.val();
                    if (logradouro){
                        swal({
                            title: "Deseja substituir o endereço existente?",
                            text: "O sistema pode substituir o endereço atual pelo resultando que ele encontrou com base no CEP. O endereço é: " + data.endereco.logradouro + ", " + data.endereco.bairro + ", " + data.endereco.cidade + ".",
                            type: "info",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sim, vou substituir!",
                            cancelButtonText: "Cancelar"
                        }).then((result) => {
                            //Limpa
                            bairro.val(null);
                            cidade.val(null);
                            endereco.val(null);
                            sigla_estado.val(null);
                            estado.val(null);
                            latitude.val(null);
                            longitude.val(null);
                            //Preenche novamente
                            bairro.val(data.endereco.bairro);
                            cidade.val(data.endereco.cidade);
                            endereco.val(data.endereco.logradouro);
                            sigla_estado.val(data.endereco.sigla_estado);
                            estado.val(data.endereco.uf);
                            latitude.val(data.endereco.latitude);
                            longitude.val(data.endereco.longitude);
                            numero.focus();
                        });
                    }else{
                        bairro.val(data.endereco.bairro);
                        cidade.val(data.endereco.cidade);
                        endereco.val(data.endereco.logradouro);
                        sigla_estado.val(data.endereco.sigla_estado);
                        estado.val(data.endereco.uf);
                        latitude.val(data.endereco.latitude);
                        longitude.val(data.endereco.longitude);
                        numero.focus();
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
}