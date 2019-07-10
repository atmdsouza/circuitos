//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos
function inicializar()
{

}

/**
 Template Name: Abstack - Bootstrap 4 Web App kit
 Author: CoderThemes
 Email: coderthemes@gmail.com
 File: Chartjs
 */

//Randomizador de Cores
function getRandomColor() {
    "use strict";
    var colors = ["#63b598", "#ce7d78", "#ea9e70", "#a48a9e", "#c6e1e8", "#648177" ,"#0d5ac1" ,
        "#f205e6" ,"#1c0365" ,"#14a9ad" ,"#4ca2f9" ,"#a4e43f" ,"#d298e2" ,"#6119d0",
        "#d2737d" ,"#c0a43c" ,"#f2510e" ,"#651be6" ,"#79806e" ,"#61da5e" ,"#cd2f00" ,
        "#9348af" ,"#01ac53" ,"#c5a4fb" ,"#996635","#b11573" ,"#4bb473" ,"#75d89e" ,
        "#2f3f94" ,"#2f7b99" ,"#da967d" ,"#34891f" ,"#b0d87b" ,"#ca4751" ,"#7e50a8" ,
        "#c4d647" ,"#e0eeb8" ,"#11dec1" ,"#289812" ,"#566ca0" ,"#ffdbe1" ,"#2f1179" ,
        "#935b6d" ,"#916988" ,"#513d98" ,"#aead3a", "#9e6d71", "#4b5bdc", "#0cd36d",
        "#250662", "#cb5bea", "#228916", "#ac3e1b", "#df514a", "#539397", "#880977",
        "#f697c1", "#ba96ce", "#679c9d", "#c6c42c", "#5d2c52", "#48b41b", "#e1cf3b",
        "#5be4f0", "#57c4d8", "#a4d17a", "#225b8", "#be608b", "#96b00c", "#088baf",
        "#f158bf", "#e145ba", "#ee91e3", "#05d371", "#5426e0", "#4834d0", "#802234",
        "#6749e8", "#0971f0", "#8fb413", "#b2b4f0", "#c3c89d", "#c9a941", "#41d158",
        "#fb21a3", "#51aed9", "#5bb32d", "#807fb", "#21538e", "#89d534", "#d36647",
        "#7fb411", "#0023b8", "#3b8c2a", "#986b53", "#f50422", "#983f7a", "#ea24a3",
        "#79352c", "#521250", "#c79ed2", "#d6dd92", "#e33e52", "#b2be57", "#fa06ec",
        "#1bb699", "#6b2e5f", "#64820f", "#1c271", "#21538e", "#89d534", "#d36647",
        "#7fb411", "#0023b8", "#3b8c2a", "#986b53", "#f50422", "#983f7a", "#ea24a3",
        "#79352c", "#521250", "#c79ed2", "#d6dd92", "#e33e52", "#b2be57", "#fa06ec",
        "#1bb699", "#6b2e5f", "#64820f", "#1c271", "#9cb64a", "#996c48", "#9ab9b7",
        "#06e052", "#e3a481", "#0eb621", "#fc458e", "#b2db15", "#aa226d", "#792ed8",
        "#73872a", "#520d3a", "#cefcb8", "#a5b3d9", "#7d1d85", "#c4fd57", "#f1ae16",
        "#8fe22a", "#ef6e3c", "#243eeb", "#1dc18", "#dd93fd", "#3f8473", "#e7dbce",
        "#421f79", "#7a3d93", "#635f6d", "#93f2d7", "#9b5c2a", "#15b9ee", "#0f5997",
        "#409188", "#911e20", "#1350ce", "#10e5b1", "#fff4d7", "#cb2582", "#ce00be",
        "#32d5d6", "#17232", "#608572", "#c79bc2", "#00f87c", "#77772a", "#6995ba",
        "#fc6b57", "#f07815", "#8fd883", "#060e27", "#96e591", "#21d52e", "#d00043",
        "#b47162", "#1ec227", "#4f0f6f", "#1d1d58", "#947002", "#bde052", "#e08c56",
        "#28fcfd", "#bb09b", "#36486a", "#d02e29", "#1ae6db", "#3e464c", "#a84a8f",
        "#911e7e", "#3f16d9", "#0f525f", "#ac7c0a", "#b4c086", "#c9d730", "#30cc49",
        "#3d6751", "#fb4c03", "#640fc1", "#62c03e", "#d3493a", "#88aa0b", "#406df9",
        "#615af0", "#4be47", "#2a3434", "#4a543f", "#79bca0", "#a8b8d4", "#00efd4",
        "#7ad236", "#7260d8", "#1deaa7", "#06f43a", "#823c59", "#e3d94c", "#dc1c06",
        "#f53b2a", "#b46238", "#2dfff6", "#a82b89", "#1a8011", "#436a9f", "#1a806a",
        "#4cf09d", "#c188a2", "#67eb4b", "#b308d3", "#fc7e41", "#af3101", "#ff065",
        "#71b1f4", "#a2f8a5", "#e23dd0", "#d3486d", "#00f7f9", "#474893", "#3cec35",
        "#1c65cb", "#5d1d0c", "#2d7d2a", "#ff3420", "#5cdd87", "#a259a4", "#e4ac44",
        "#1bede6", "#8798a4", "#d7790f", "#b2c24f", "#de73c2", "#d70a9c", "#25b67",
        "#88e9b8", "#c2b0e2", "#86e98f", "#ae90e2", "#1a806b", "#436a9e", "#0ec0ff",
        "#f812b3", "#b17fc9", "#8d6c2f", "#d3277a", "#2ca1ae", "#9685eb", "#8a96c6",
        "#dba2e6", "#76fc1b", "#608fa4", "#20f6ba", "#07d7f6", "#dce77a", "#77ecca"];
    var rand = Math.floor(Math.random()*colors.length);
    return colors[rand];
}

var pieOptions = {
    
};

!function($) {
    "use strict";

    var ChartJs = function() {};

    ChartJs.prototype.respChart = function(selector,type,data, options) {
        // get selector by context
        var ctx = selector.get(0).getContext("2d");
        // pointing parent container to make chart js inherit its width
        var container = $(selector).parent();

        // enable resizing matter
        $(window).resize( generateChart );

        // this function produce the responsive Chart JS
        function generateChart(){
            // make chart width fit with its container
            var ww = selector.attr("width", $(container).width() );
            switch(type){
                case "Line":
                    new Chart(ctx, {type: "line", data: data, options: options});
                    break;
                case "Doughnut":
                    new Chart(ctx, {type: "doughnut", data: data, options: options});
                    break;
                case "Pie":
                    new Chart(ctx, {type: "pie", data: data, options: pieOptions});
                    break;
                case "Bar":
                    new Chart(ctx, {type: "bar", data: data, options: options});
                    break;
                case "Radar":
                    new Chart(ctx, {type: "radar", data: data, options: options});
                    break;
                case "PolarArea":
                    new Chart(ctx, {data: data, type: "polarArea", options: options});
                    break;
            }
            // Initiate new chart or Redraw

        };
        // run function - render chart at first load
        generateChart();
    },

        //init
        ChartJs.prototype.init = function() {
            //creating lineChart
            // var lineChart = {
            //     labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September"],
            //     datasets: [
            //         {
            //             label: "Sales Analytics",
            //             fill: false,
            //             lineTension: 0.05,
            //             backgroundColor: "#fff",
            //             borderColor: "#3ec396",
            //             borderCapStyle: "butt",
            //             borderDash: [],
            //             borderDashOffset: 0.0,
            //             borderJoinStyle: "miter",
            //             pointBorderColor: "#3ec396",
            //             pointBackgroundColor: "#fff",
            //             pointBorderWidth: 8,
            //             pointHoverRadius: 6,
            //             pointHoverBackgroundColor: "#fff",
            //             pointHoverBorderColor: "#3ec396",
            //             pointHoverBorderWidth: 3,
            //             pointRadius: 1,
            //             pointHitRadius: 10,
            //             data: [65, 59, 80, 81, 56, 55, 40, 35, 30]
            //         }
            //     ]
            // };
            //
            // var lineOpts = {
            //     scales: {
            //         yAxes: [{
            //             ticks: {
            //                 max: 100,
            //                 min: 20,
            //                 stepSize: 10
            //             }
            //         }]
            //     }
            // };
            //
            // this.respChart($("#lineChart"),"Line",lineChart, lineOpts);

            //donut chart
            // var donutChart = {
            //     labels: [
            //         "Desktops",
            //         "Tablets",
            //         "Mobiles",
            //         "Mobiles",
            //         "Tablets"
            //     ],
            //     datasets: [
            //         {
            //             data: [80, 50, 100,121,77],
            //             backgroundColor: [
            //                 "#5553ce",
            //                 "#297ef6",
            //                 "#e52b4c",
            //                 "#ffa91c",
            //                 "#32c861"
            //             ],
            //             hoverBackgroundColor: [
            //                 "#5553ce",
            //                 "#297ef6",
            //                 "#e52b4c",
            //                 "#ffa91c",
            //                 "#32c861"
            //             ],
            //             hoverBorderColor: "#fff"
            //         }]
            // };
            // this.respChart($("#doughnut"),"Doughnut",donutChart);


            //Circuitos por Status
            // var pieChart = {
            //     labels: [
            //         "Desktops",
            //         "Tablets",
            //         "Mobiles",
            //         "Mobiles",
            //         "Tablets"
            //     ],
            //     datasets: [
            //         {
            //             data: [80, 50, 100,121,77],
            //             backgroundColor: [
            //                 "#5d6dc3",
            //                 "#3ec396",
            //                 "#f9bc0b",
            //                 "#4fbde9",
            //                 "#313a46"
            //             ],
            //             hoverBackgroundColor: [
            //                 "#5d6dc3",
            //                 "#3ec396",
            //                 "#f9bc0b",
            //                 "#4fbde9",
            //                 "#313a46"
            //             ],
            //             hoverBorderColor: "#fff"
            //         }]
            // };
            // this.respChart($("#circuitos_status"),"Pie",pieChart);

            //barchart
            // var barChart = {
            //     labels: ["January", "February", "March", "April", "May", "June", "July"],
            //     datasets: [
            //         {
            //             label: "Sales Analytics",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [65, 59, 80, 81, 56, 55, 40,20]
            //         },
            //         {
            //             label: "Outros Dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [31, 60, 12, 45, 80, 20, 84,1]
            //         },
            //         {
            //             label: "Mais dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [84, 41, 79, 80, 12, 46, 13,90]
            //         }
            //     ]
            // };
            // this.respChart($("#bar"),"Bar",barChart);


            //radar chart
            // var radarChart = {
            //     labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
            //     datasets: [
            //         {
            //             label: "Desktops",
            //             backgroundColor: "rgba(179,181,198,0.2)",
            //             borderColor: "rgba(179,181,198,1)",
            //             pointBackgroundColor: "rgba(179,181,198,1)",
            //             pointBorderColor: "#fff",
            //             pointHoverBackgroundColor: "#fff",
            //             pointHoverBorderColor: "rgba(179,181,198,1)",
            //             data: [65, 59, 90, 81, 56, 55, 40]
            //         },
            //         {
            //             label: "Tablets",
            //             backgroundColor: "rgba(255,99,132,0.2)",
            //             borderColor: "rgba(255,99,132,1)",
            //             pointBackgroundColor: "rgba(255,99,132,1)",
            //             pointBorderColor: "#fff",
            //             pointHoverBackgroundColor: "#fff",
            //             pointHoverBorderColor: "rgba(255,99,132,1)",
            //             data: [28, 48, 40, 19, 96, 27, 100]
            //         }
            //     ]
            // };
            // this.respChart($("#radar"),"Radar",radarChart);

            //Polar area chart
            // var polarChart = {
            //     datasets: [{
            //         data: [
            //             11,
            //             16,
            //             7,
            //             18
            //         ],
            //         backgroundColor: [
            //             "#297ef6",
            //             "#45bbe0",
            //             "#ebeff2",
            //             "#1ea69a"
            //         ],
            //         label: "My dataset", // for legend
            //         hoverBorderColor: "#fff"
            //     }],
            //     labels: [
            //         "Series 1",
            //         "Series 2",
            //         "Series 3",
            //         "Series 4"
            //     ]
            // };
            // this.respChart($("#polarArea"),"PolarArea",polarChart);

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
                    var labels = [];
                    var values = [];
                    var colour = [];
                    $.each(data.dados, function (key, value) {
                        labels.push(value.status);
                        values.push(value.total);
                        colour.push(getRandomColor());
                    });
                    var pieChart = {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colour,
                                hoverBackgroundColor: colour,
                                hoverBorderColor: "#fff"
                            }]
                    };
                    ChartJs.prototype.respChart($("#pie_circuitos_status"),"Pie",pieChart);
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
                    var labels = [];
                    var values = [];
                    var colour = [];
                    $.each(data.dados, function (key, value) {
                        labels.push(value.status);
                        values.push(value.total);
                        colour.push(getRandomColor());
                    });
                    var pieChart = {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colour,
                                hoverBackgroundColor: colour,
                                hoverBorderColor: "#fff"
                            }]
                    };
                    ChartJs.prototype.respChart($("#pie_cidade_digital_status"),"Pie",pieChart);
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
                    var labels = [];
                    var values = [];
                    var colour = [];
                    $.each(data.dados, function (key, value) {
                        labels.push(value.link);
                        values.push(value.total);
                        colour.push(getRandomColor());
                    });
                    var pieChart = {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colour,
                                hoverBackgroundColor: colour,
                                hoverBorderColor: "#fff"
                            }]
                    };
                    ChartJs.prototype.respChart($("#pie_circuitos_link"),"Pie",pieChart);
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
                    var labels = [];
                    var values = [];
                    var colour = [];
                    $.each(data.dados, function (key, value) {
                        labels.push(value.cliente_esfera);
                        values.push(value.total);
                        colour.push(getRandomColor());
                    });
                    var pieChart = {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colour,
                                hoverBackgroundColor: colour,
                                hoverBorderColor: "#fff"
                            }]
                    };
                    ChartJs.prototype.respChart($("#pie_clientes_esfera"),"Pie",pieChart);
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
                    var labels = [];
                    var values = [];
                    var colour = [];
                    $.each(data.dados, function (key, value) {
                        labels.push(value.link);
                        values.push(value.total);
                        colour.push(getRandomColor());
                    });
                    var pieChart = {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colour,
                                hoverBackgroundColor: colour,
                                hoverBorderColor: "#fff"
                            }]
                    };
                    ChartJs.prototype.respChart($("#pie_ativacao_link_mes"),"Pie",pieChart);
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
                    var labels = [];
                    var values = [];
                    var colour = [];
                    $.each(data.dados, function (key, value) {
                        labels.push(value.status);
                        values.push(value.total);
                        colour.push(getRandomColor());
                    });
                    var pieChart = {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colour,
                                hoverBackgroundColor: colour,
                                hoverBorderColor: "#fff"
                            }]
                    };
                    ChartJs.prototype.respChart($("#pie_ativacoes_desativacoes_mes"),"Pie",pieChart);
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
                    var labels = [];
                    var values = [];
                    var colour = [];
                    $.each(data.dados, function (key, value) {
                        labels.push(value.funcao);
                        values.push(value.total);
                        colour.push(getRandomColor());
                    });
                    var pieChart = {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colour,
                                hoverBackgroundColor: colour,
                                hoverBorderColor: "#fff"
                            }]
                    };
                    ChartJs.prototype.respChart($("#pie_circuitos_funcao"),"Pie",pieChart);
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
                    var labels = [];
                    var values = [];
                    var colour = [];
                    $.each(data.dados, function (key, value) {
                        labels.push(value.conectividade);
                        values.push(value.total);
                        colour.push(getRandomColor());
                    });
                    var pieChart = {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colour,
                                hoverBackgroundColor: colour,
                                hoverBorderColor: "#fff"
                            }]
                    };
                    ChartJs.prototype.respChart($("#pie_circuitos_conectividade"),"Pie",pieChart);
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
                    var labels = [];
                    var values = [];
                    var colour = [];
                    $.each(data.dados, function (key, value) {
                        labels.push(value.acesso);
                        values.push(value.total);
                        colour.push(getRandomColor());
                    });
                    var pieChart = {
                        labels: labels,
                        datasets: [
                            {
                                data: values,
                                backgroundColor: colour,
                                hoverBackgroundColor: colour,
                                hoverBorderColor: "#fff"
                            }]
                    };
                    ChartJs.prototype.respChart($("#pie_circuitos_acesso"),"Pie",pieChart);
                }
            });

            //bar_hotzones_cidade
            // var action_circuito_hotzone_cidade = actionCorreta(window.location.href.toString(), "index/circuitoHotzoneCidade");
            // $.ajax({
            //     type: "GET",
            //     dataType: "JSON",
            //     url: action_circuito_hotzone_cidade,
            //     beforeSend: function () {
            //     },
            //     complete: function () {
            //     },
            //     error: function (data) {
            //         if (data.status && data.status === 401)
            //         {
            //             swal({
            //                 title: "Erro de Permissão",
            //                 text: "Seu usuário não possui privilégios para executar esta ação! Por favor, procure o administrador do sistema!",
            //                 type: "warning"
            //             });
            //         }
            //     },
            //     success: function (data) {
            //         var labels = [];
            //         labels.push("Quantidade");
            //         var values = [];
            //         var colour;
            //         $.each(data.dados, function (key, value) {
            //             colour = getRandomColor();
            //             values.push({
            //                 label: value.descricao,
            //                 backgroundColor: colour,
            //                 borderColor: colour,
            //                 borderWidth: 2,
            //                 hoverBackgroundColor: colour,
            //                 hoverBorderColor: colour,
            //                 data: value.total
            //             });
            //         });
            //         var barChart = {
            //             labels: labels,
            //             datasets: values
            //         };
            //         var Options = {
            //
            //         }
            //         ChartJs.prototype.respChart($("#bar_hotzones_cidade"),"Bar",barChart, Options);
            //     }
            // });
            //
            // //bar_cluster_cidade
            // var barChart = {
            //     labels: ["January", "February", "March", "April", "May", "June", "July", "August"],
            //     datasets: [
            //         {
            //             label: "Sales Analytics",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [65, 59, 80, 81, 56, 55, 40,20]
            //         },
            //         {
            //             label: "Outros Dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [31, 60, 12, 45, 80, 20, 84,1]
            //         },
            //         {
            //             label: "Mais dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [84, 41, 79, 80, 12, 46, 13,90]
            //         }
            //     ]
            // };
            // this.respChart($("#bar_cluster_cidade"),"Bar",barChart);
            //
            // //bar_equipamentos_fabricante
            // var barChart = {
            //     labels: ["January", "February", "March", "April", "May", "June", "July"],
            //     datasets: [
            //         {
            //             label: "Sales Analytics",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [65, 59, 80, 81, 56, 55, 40,20]
            //         },
            //         {
            //             label: "Outros Dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [31, 60, 12, 45, 80, 20, 84,1]
            //         },
            //         {
            //             label: "Mais dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [84, 41, 79, 80, 12, 46, 13,90]
            //         }
            //     ]
            // };
            // this.respChart($("#bar_equipamentos_fabricante"),"Bar",barChart);
            //
            // //bar_equipamentos_tipo
            // var barChart = {
            //     labels: ["January", "February", "March", "April", "May", "June", "July"],
            //     datasets: [
            //         {
            //             label: "Sales Analytics",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [65, 59, 80, 81, 56, 55, 40,20]
            //         },
            //         {
            //             label: "Outros Dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [31, 60, 12, 45, 80, 20, 84,1]
            //         },
            //         {
            //             label: "Mais dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [84, 41, 79, 80, 12, 46, 13,90]
            //         }
            //     ]
            // };
            // this.respChart($("#bar_equipamentos_tipo"),"Bar",barChart);
            //
            // //bar_circuitos_cidade_digital
            // var barChart = {
            //     labels: ["January", "February", "March", "April", "May", "June", "July"],
            //     datasets: [
            //         {
            //             label: "Sales Analytics",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [65, 59, 80, 81, 56, 55, 40,20]
            //         },
            //         {
            //             label: "Outros Dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [31, 60, 12, 45, 80, 20, 84,1]
            //         },
            //         {
            //             label: "Mais dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [84, 41, 79, 80, 12, 46, 13,90]
            //         }
            //     ]
            // };
            // this.respChart($("#bar_circuitos_cidade_digital"),"Bar",barChart);
            //
            // //bar_top20_clientes
            // var barChart = {
            //     labels: ["January", "February", "March", "April", "May", "June", "July"],
            //     datasets: [
            //         {
            //             label: "Sales Analytics",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [65, 59, 80, 81, 56, 55, 40,20]
            //         },
            //         {
            //             label: "Outros Dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [31, 60, 12, 45, 80, 20, 84,1]
            //         },
            //         {
            //             label: "Mais dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [84, 41, 79, 80, 12, 46, 13,90]
            //         }
            //     ]
            // };
            // this.respChart($("#bar_top20_clientes"),"Bar",barChart);
            //
            // //bar_circuitos_conectividade
            // var barChart = {
            //     labels: ["January", "February", "March", "April", "May", "June", "July"],
            //     datasets: [
            //         {
            //             label: "Sales Analytics",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [65, 59, 80, 81, 56, 55, 40,20]
            //         },
            //         {
            //             label: "Outros Dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [31, 60, 12, 45, 80, 20, 84,1]
            //         },
            //         {
            //             label: "Mais dados",
            //             backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
            //             borderColor: "#3c86d8",
            //             borderWidth: 2,
            //             hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
            //             hoverBorderColor: "#3c86d8",
            //             data: [84, 41, 79, 80, 12, 46, 13,90]
            //         }
            //     ]
            // };
            // this.respChart($("#bar_circuitos_conectividade"),"Bar",barChart);
        },
        $.ChartJs = new ChartJs, $.ChartJs.Constructor = ChartJs

}(window.jQuery),

//initializing
    function($) {
        "use strict";
        $.ChartJs.init()
    }(window.jQuery);

