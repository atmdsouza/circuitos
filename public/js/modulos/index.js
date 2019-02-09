/**
 Template Name: Abstack - Bootstrap 4 Web App kit
 Author: CoderThemes
 Email: coderthemes@gmail.com
 File: Chartjs
 */

//Randomizador de Cores
function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

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
                    new Chart(ctx, {type: "pie", data: data, options: options});
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
            var pieChart = {
                labels: [
                    "Desktops",
                    "Tablets",
                    "Mobiles",
                    "Mobiles",
                    "Tablets"
                ],
                datasets: [
                    {
                        data: [80, 50, 100,121,77],
                        backgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBackgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBorderColor: "#fff"
                    }]
            };
            this.respChart($("#pie_ativacao_link_mes"),"Pie",pieChart);

            //pie_ativacoes_desativacoes_mes
            var pieChart = {
                labels: [
                    "Desktops",
                    "Tablets",
                    "Mobiles",
                    "Mobiles",
                    "Tablets"
                ],
                datasets: [
                    {
                        data: [80, 50, 100,121,77],
                        backgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBackgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBorderColor: "#fff"
                    }]
            };
            this.respChart($("#pie_ativacoes_desativacoes_mes"),"Pie",pieChart);

            //pie_circuitos_funcao
            var pieChart = {
                labels: [
                    "Desktops",
                    "Tablets",
                    "Mobiles",
                    "Mobiles",
                    "Tablets"
                ],
                datasets: [
                    {
                        data: [80, 50, 100,121,77],
                        backgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBackgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBorderColor: "#fff"
                    }]
            };
            this.respChart($("#pie_circuitos_funcao"),"Pie",pieChart);

            //pie_circuitos_conectividade
            var pieChart = {
                labels: [
                    "Desktops",
                    "Tablets",
                    "Mobiles",
                    "Mobiles",
                    "Tablets"
                ],
                datasets: [
                    {
                        data: [80, 50, 100,121,77],
                        backgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBackgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBorderColor: "#fff"
                    }]
            };
            this.respChart($("#pie_circuitos_conectividade"),"Pie",pieChart);

            //pie_circuitos_acesso
            var pieChart = {
                labels: [
                    "Desktops",
                    "Tablets",
                    "Mobiles",
                    "Mobiles",
                    "Tablets"
                ],
                datasets: [
                    {
                        data: [80, 50, 100,121,77],
                        backgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBackgroundColor: [
                            "#5d6dc3",
                            "#3ec396",
                            "#f9bc0b",
                            "#4fbde9",
                            "#313a46"
                        ],
                        hoverBorderColor: "#fff"
                    }]
            };
            this.respChart($("#pie_circuitos_acesso"),"Pie",pieChart);

            //bar_hotzones_cidade
            var barChart = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Sales Analytics",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [65, 59, 80, 81, 56, 55, 40,20]
                    },
                    {
                        label: "Outros Dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [31, 60, 12, 45, 80, 20, 84,1]
                    },
                    {
                        label: "Mais dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [84, 41, 79, 80, 12, 46, 13,90]
                    }
                ]
            };
            this.respChart($("#bar_hotzones_cidade"),"Bar",barChart);

            //bar_cluster_cidade
            var barChart = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Sales Analytics",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [65, 59, 80, 81, 56, 55, 40,20]
                    },
                    {
                        label: "Outros Dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [31, 60, 12, 45, 80, 20, 84,1]
                    },
                    {
                        label: "Mais dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [84, 41, 79, 80, 12, 46, 13,90]
                    }
                ]
            };
            this.respChart($("#bar_cluster_cidade"),"Bar",barChart);

            //bar_equipamentos_fabricante
            var barChart = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Sales Analytics",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [65, 59, 80, 81, 56, 55, 40,20]
                    },
                    {
                        label: "Outros Dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [31, 60, 12, 45, 80, 20, 84,1]
                    },
                    {
                        label: "Mais dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [84, 41, 79, 80, 12, 46, 13,90]
                    }
                ]
            };
            this.respChart($("#bar_equipamentos_fabricante"),"Bar",barChart);

            //bar_equipamentos_tipo
            var barChart = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Sales Analytics",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [65, 59, 80, 81, 56, 55, 40,20]
                    },
                    {
                        label: "Outros Dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [31, 60, 12, 45, 80, 20, 84,1]
                    },
                    {
                        label: "Mais dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [84, 41, 79, 80, 12, 46, 13,90]
                    }
                ]
            };
            this.respChart($("#bar_equipamentos_tipo"),"Bar",barChart);

            //bar_circuitos_cidade_digital
            var barChart = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Sales Analytics",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [65, 59, 80, 81, 56, 55, 40,20]
                    },
                    {
                        label: "Outros Dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [31, 60, 12, 45, 80, 20, 84,1]
                    },
                    {
                        label: "Mais dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [84, 41, 79, 80, 12, 46, 13,90]
                    }
                ]
            };
            this.respChart($("#bar_circuitos_cidade_digital"),"Bar",barChart);

            //bar_top20_clientes
            var barChart = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Sales Analytics",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [65, 59, 80, 81, 56, 55, 40,20]
                    },
                    {
                        label: "Outros Dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [31, 60, 12, 45, 80, 20, 84,1]
                    },
                    {
                        label: "Mais dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [84, 41, 79, 80, 12, 46, 13,90]
                    }
                ]
            };
            this.respChart($("#bar_top20_clientes"),"Bar",barChart);

            //bar_circuitos_conectividade
            var barChart = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Sales Analytics",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [65, 59, 80, 81, 56, 55, 40,20]
                    },
                    {
                        label: "Outros Dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [31, 60, 12, 45, 80, 20, 84,1]
                    },
                    {
                        label: "Mais dados",
                        backgroundColor: "rgba(" + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) + "," + (Math.floor(Math.random() * 256)) +  ", 0.3)",
                        borderColor: "#3c86d8",
                        borderWidth: 2,
                        hoverBackgroundColor: "rgba(60, 134, 216, 0.7)",
                        hoverBorderColor: "#3c86d8",
                        data: [84, 41, 79, 80, 12, 46, 13,90]
                    }
                ]
            };
            this.respChart($("#bar_circuitos_conectividade"),"Bar",barChart);
        },
        $.ChartJs = new ChartJs, $.ChartJs.Constructor = ChartJs

}(window.jQuery),

//initializing
    function($) {
        "use strict";
        $.ChartJs.init()
    }(window.jQuery);

