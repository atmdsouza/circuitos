//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos
function inicializar()
{

}

/*
* Sessão dos Gráficos do Dashboard
* */
//Circuitos por Status
var action_status = actionCorreta(window.location.href.toString(), "index/circuitoStatus");
$.ajax({
    type: "GET",
    dataType: "JSON",
    url: action_status,
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
        var dataPoints = [];
        $.each(data.dados, function (key, value) {
            dataPoints.push({name: value.status, y: parseFloat(value.total)});
        });
        Highcharts.chart('pie_circuitos_status', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Circuitos por Status'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            legend: {
                layout: "vertical",
                verticalAlign: "bottom",
                align: "left"
            },
            series: [{
                name: 'Status',
                colorByPoint: true,
                data: dataPoints
            }]
        });
    }
});

//Cidades Digitais por Status
var action_cidade_digital_status = actionCorreta(window.location.href.toString(), "index/cidadedigitalStatus");
$.ajax({
    type: "GET",
    dataType: "JSON",
    url: action_cidade_digital_status,
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
        var dataPoints = [];
        $.each(data.dados, function (key, value) {
            dataPoints.push({name: value.status, y: parseFloat(value.total)});
        });
        Highcharts.chart('pie_cidade_digital_status', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Cidades Digitais por Status'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            legend: {
                layout: "vertical",
                verticalAlign: "bottom",
                align: "left"
            },
            series: [{
                name: 'Status',
                colorByPoint: true,
                data: dataPoints
            }]
        });
    }
});

//pie_circuitos_link
var action_circuitos_link = actionCorreta(window.location.href.toString(), "index/circuitoLink");
$.ajax({
    type: "GET",
    dataType: "JSON",
    url: action_circuitos_link,
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
        var dataPoints = [];
        $.each(data.dados, function (key, value) {
            dataPoints.push({name: value.link, y: parseFloat(value.total)});
        });
        Highcharts.chart('pie_circuitos_link', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Circuitos por Tipo de Link'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            legend: {
                layout: "vertical",
                verticalAlign: "bottom",
                align: "left"
            },
            series: [{
                name: 'Tipo de Link',
                colorByPoint: true,
                data: dataPoints
            }]
        });
    }
});

//pie_clientes_esfera
var action_cliente_esfera = actionCorreta(window.location.href.toString(), "index/circuitoEsfera");
$.ajax({
    type: "GET",
    dataType: "JSON",
    url: action_cliente_esfera,
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
        var dataPoints = [];
        $.each(data.dados, function (key, value) {
            dataPoints.push({name: value.cliente_esfera, y: parseFloat(value.total)});
        });
        Highcharts.chart('pie_clientes_esfera', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Clientes por Esfera'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            legend: {
                layout: "vertical",
                verticalAlign: "bottom",
                align: "left"
            },
            series: [{
                name: 'Esfera',
                colorByPoint: true,
                data: dataPoints
            }]
        });
    }
});

//pie_ativacao_link_mes
var action_circuitos_link_mes = actionCorreta(window.location.href.toString(), "index/circuitoLinkMes");
$.ajax({
    type: "GET",
    dataType: "JSON",
    url: action_circuitos_link_mes,
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
        var dataPoints = [];
        $.each(data.dados, function (key, value) {
            dataPoints.push({name: value.link, y: parseFloat(value.total)});
        });
        Highcharts.chart('pie_ativacao_link_mes', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Ativações por Tipo de Link no Mês'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            legend: {
                layout: "vertical",
                verticalAlign: "bottom",
                align: "left"
            },
            series: [{
                name: 'Tipo de Link',
                colorByPoint: true,
                data: dataPoints
            }]
        });
    }
});

//pie_ativacoes_desativacoes_mes
var action_circuito_status_mes = actionCorreta(window.location.href.toString(), "index/circuitoStatusMes");
$.ajax({
    type: "GET",
    dataType: "JSON",
    url: action_circuito_status_mes,
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
        var dataPoints = [];
        $.each(data.dados, function (key, value) {
            dataPoints.push({name: value.status, y: parseFloat(value.total)});
        });
        Highcharts.chart('pie_ativacoes_desativacoes_mes', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Circuitos movimentados no mês (ativações e desativações)'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            legend: {
                layout: "vertical",
                verticalAlign: "bottom",
                align: "left"
            },
            series: [{
                name: 'Status',
                colorByPoint: true,
                data: dataPoints
            }]
        });
    }
});

//pie_circuitos_funcao
var action_circuito_funcao = actionCorreta(window.location.href.toString(), "index/circuitoFuncao");
$.ajax({
    type: "GET",
    dataType: "JSON",
    url: action_circuito_funcao,
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
        var dataPoints = [];
        $.each(data.dados, function (key, value) {
            dataPoints.push({name: value.funcao, y: parseFloat(value.total)});
        });
        Highcharts.chart('pie_circuitos_funcao', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Circuitos por Função'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            legend: {
                layout: "vertical",
                verticalAlign: "bottom",
                align: "left"
            },
            series: [{
                name: 'Status',
                colorByPoint: true,
                data: dataPoints
            }]
        });
    }
});

//pie_circuitos_conectividade
var action_circuitos_conectividade = actionCorreta(window.location.href.toString(), "index/circuitoConectividade");
$.ajax({
    type: "GET",
    dataType: "JSON",
    url: action_circuitos_conectividade,
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
        var dataPoints = [];
        $.each(data.dados, function (key, value) {
            dataPoints.push({name: value.conectividade, y: parseFloat(value.total)});
        });
        Highcharts.chart('pie_circuitos_conectividade', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Circuitos por Conectividade'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            legend: {
                layout: "vertical",
                verticalAlign: "bottom",
                align: "left"
            },
            series: [{
                name: 'Status',
                colorByPoint: true,
                data: dataPoints
            }]
        });
    }
});

//pie_circuitos_acesso
var action_circuito_acesso = actionCorreta(window.location.href.toString(), "index/circuitoAcesso");
$.ajax({
    type: "GET",
    dataType: "JSON",
    url: action_circuito_acesso,
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
        var dataPoints = [];
        $.each(data.dados, function (key, value) {
            dataPoints.push({name: value.acesso, y: parseFloat(value.total)});
        });
        Highcharts.chart('pie_circuitos_acesso', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Circuitos por Tipo de Acesso'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            legend: {
                layout: "vertical",
                verticalAlign: "bottom",
                align: "left"
            },
            series: [{
                name: 'Status',
                colorByPoint: true,
                data: dataPoints
            }]
        });
    }
});