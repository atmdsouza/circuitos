/**
 * Trabalhando com o ajustse da Action Correta em chamadas Ajax
 * @url string URL que origina a chamada e será modificada
 * @action string Action que será usada na chamada, semrpe deve estar formatada como 'controller/action'
 * */
function actionCorreta(url, action) {
    'use strict';
    var novaAction;
    var urls = url.split("/");
    //Pasta raiz sendo "/circuitos"
    // novaAction = urls[0] + "//" + urls[2] + "/" + urls[3] + "/" + action;
    //Pasta raiz sendo "/"
    novaAction = urls[0] + "//" + urls[2] + "/" + action;
    return novaAction;
}

Object.size = function(obj) {
    'use strict';
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) {
            size++;
        }
    }
    return size;
};

//Processo para fazer funcionar o CSRF_Token
function getCookie(c_name) {
    'use strict';
    var c_start;
    var c_end;
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start !== -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end === -1){
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}
$(function () {
    'use strict';
    $.ajaxSetup({
        headers: { "X-CSRFToken": getCookie("csrftoken") }
    });
});
//Fim

var RemoveTableRow;

//Limpar Linhas da Tabela
(function ($) {
    'use strict';
    RemoveTableRow = function (handler) {
        var tr = $(handler).closest("tr");
        tr.fadeOut(400, function () {
            var el = tr.closest('table');
            tr.remove();
            if($('.tr_remove').length === 0) {
                el.attr('style','display: none;');
            }
        });

        return false;
    };
})(jQuery);

//Testar se objetos são vazios
function isEmpty(obj) {
    'use strict';
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop)){
            return false;
        }
    }
    return true;
}

// Select2 Configuração Padrão
$(".select2").select2({
    language: "pt-BR"
});

// Account Setting Default
accounting.settings = {
    currency: {
        symbol : "R$",   // default currency symbol is '$'
        format: "%s %v", // controls output: %s = symbol, %v = value/number (can be object: see below)
        decimal : ",",  // decimal point separator
        thousand: ".",  // thousands separator
        precision : 2   // decimal places
    },
    number: {
        precision : 0,  // default precision on numbers is 0
        thousand: ".",
        decimal : ","
    }
};

// These can be changed externally to edit the library's defaults:
accounting.settings.currency.format = "%s %v";

// Format can be an object, with `pos`, `neg` and `zero`:
accounting.settings.currency.format = {
    pos : "%s %v",   // for positive values, eg. "$ 1.00" (required)
    neg : "%s -%v", // for negative values, eg. "$ (1.00)" [optional]
    zero: "%s  0 "  // for zero values, eg. "$  --" [optional]
};

//Bootstrap Filestyle Configuração Padrão
$(':file').filestyle({
    dragdrop: true,
    input: true,
    htmlIcon: '<span class="fi-paper-clip"></span>',
    text: 'Localize o Arquivo',
    btnClass: 'btn-primary',
    disabled: false,
    buttonBefore: true,
    badge: true,
    badgeName: 'badge-danger',
    placeholder: 'Sem arquivo',

});

//Date range picker Configuração Padrão
$(".input-daterange-datepicker").daterangepicker({
    autoApply: true,
    autoUpdateInput: false,
    linkedCalendars: false,
    locale: {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "De",
        "toLabel": "Para",
        "customRangeLabel": "Customizado",
        "daysOfWeek": [
            "Do",
            "Se",
            "Te",
            "Qa",
            "Qi",
            "Se",
            "Sa"
        ],
        "monthNames": [
            "Janeiro",
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junhu",
            "Julhu",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro"
        ],
        "firstDay": 1
    }
});

$(".input-daterange-datepicker").on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
});

$(".input-daterange-datepicker").on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

//Configuração padrão do autocomplete
$.extend($.Autocomplete.defaults, {
    autoSelectFirst: true,
    deferRequestBy: 500,
    noCache: true,
    minChars: 1,
    triggerSelectOnValidInput: false,
    showNoSuggestionNotice: true,
    noSuggestionNotice: "Não existem resultados para essa consulta!"
});

//Configuração padrão de datatables
$.extend($.fn.dataTable.defaults, {
    fixedHeader: true,
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
        sSearch: "Pesquisar no resultado:",
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
    lengthChange: true,
    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tudo"]],
    select: {
        style: 'multi'
    },
    responsive: true,
    search: {
        caseInsensitive: true
    },
    ordering: true,
    orderMulti: true,
    order: [[ 0, "desc" ]]
});
//Header do datatable
function floatHeader()
{
    $(window).scroll(function() {
        var floatingHeader = $('table.table.table-striped.table-bordered.table-hover.dataTable.no-footer.dtr-inline.fixedHeader-floating');

        if(floatingHeader[0] != null) {
            floatingHeader.css('top', '65px');
            floatingHeader.css('left', ($('.left.side-menu').width() + 60 )+ 'px');
            floatingHeader.css('width', ($('.container-fluid').width() - 120)+ 'px');
        }
    });
}
//Opções Globais Highcharts
Highcharts.setOptions({
    global: {
        Date: undefined,
        VMLRadialGradientURL: "http://code.highcharts.com/{version}/gfx/vml-radial-gradient.png",
        canvasToolsURL: "http://code.highcharts.com/{version}/modules/canvas-tools.js",
        getTimezoneOffset: null,
        timezoneOffset: 0,
        useUTC: true
    },
    lang: {
        contextButtonTitle: "Opções",
        decimalPoint: ",",
        downloadJPEG: "Download Imagem JPEG",
        downloadPDF: "Download Imagem PDF",
        downloadPNG: "Download Imagem PNG",
        downloadSVG: "Download Imagem Vetor SVG",
        drillUpText: "Voltar para {series.name}",
        invalidDate: null,
        loading: "Carregando...",
        months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        noData: "Sem dados para visualizar",
        numericSymbols: ["k", "M", "G", "T", "P", "E"],
        printChart: "Imprimir Gráfico",
        resetZoom: "Resetar Zoom",
        resetZoomTitle: "Resetar Zoom para Nível 1:1",
        shortMonths: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        shortWeekdays: undefined,
        thousandsSep: ".",
        weekdays: ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"]
    }
});
//Limpar modal bootstrap
function limparModalBootstrap(modal)
{
    'use strict';
    $('#'+modal).find('form')[0].reset();
}

/**
 * Trabalhando com Datas e Horas
 * Exibe a Data atual no formato Americano
 * Exemplo:  2016-05-01
 */
function Hoje() {
    var data = new Date();
    var dia = data.getDate();
    if (dia.toString().length == 1)
        dia = "0" + dia;
    var mes = data.getMonth() + 1;
    if (mes.toString().length == 1)
        mes = "0" + mes;
    var ano = data.getFullYear();
    var hoje = ano + "-" + mes + "-" + dia;
    return hoje;
}

/**
 * Soma duas horas.
 * Exemplo:  12:35 + 07:20 = 19:55.
 */
function somaHora(horaInicio, horaSomada) {

    var horaIni = horaInicio.split(':');
    var horaSom = horaSomada.split(':');

    var horasTotal = parseInt(horaIni[0], 10) + parseInt(horaSom[0], 10);
    var minutosTotal = parseInt(horaIni[1], 10) + parseInt(horaSom[1], 10);

    if (minutosTotal >= 60) {
        minutosTotal -= 60;
        horasTotal += 1;
    }

    var horaFinal = completaZeroEsquerda(horasTotal, 2) + ":" + completaZeroEsquerda(minutosTotal, 2);
    return horaFinal;
}

/**
 * Verifica se a hora inicial é menor que a final.
 */
function isHoraInicialMenorHoraFinal(horaInicial, horaFinal) {
    var horaIni = horaInicial.split(':');
    var horaFim = horaFinal.split(':');

    // Verifica as horas. Se forem diferentes, é só ver se a inicial 
    // é menor que a final.
    var hIni = parseInt(horaIni[0], 10);
    var hFim = parseInt(horaFim[0], 10);
    if (hIni != hFim)
        return hIni < hFim;

    // Se as horas são iguais, verifica os minutos então.
    var mIni = parseInt(horaIni[1], 10);
    var mFim = parseInt(horaFim[1], 10);
    if (mIni != mFim)
        return mIni < mFim;
}
function completaZeroEsquerda(num, size) {
    var s = num + "";
    while (s.length < size)
        s = "0" + s;
    return s;
}

/**
 * Retona a diferença entre duas horas.
 * Exemplo: 14:35 a 17:21 = 02:46
 * Adaptada de http://stackoverflow.com/questions/2053057/doing-time-subtraction-with-jquery
 */
function diferencaHoras(horaInicial, horaFinal) {

    // Tratamento se a hora inicial é menor que a final 
    if (!isHoraInicialMenorHoraFinal(horaInicial, horaFinal)) {
        var aux = horaFinal;
        horaFinal = horaInicial;
        horaInicial = aux;
    }

    var hIni = horaInicial.split(':');
    var hFim = horaFinal.split(':');

    var horasTotal = parseInt(hFim[0], 10) - parseInt(hIni[0], 10);
    var minutosTotal = parseInt(hFim[1], 10) - parseInt(hIni[1], 10);

    if (minutosTotal < 0) {
        minutosTotal += 60;
        horasTotal -= 1;
    }

    horaFinal = completaZeroEsquerda(horasTotal, 2) + ":" + completaZeroEsquerda(minutosTotal, 2);
    return horaFinal;
}

/**
 * Calcular os intervalos entre duas horas.
 * Exemplo:  entre 09:00 e 10:30 com intervalo de 30 min = 09:00, 09:30, 10:00 e 10:30.
 */
function calculaIntervalos(horaInicial, horaFinal, intervalo) {
    // Tratamento se a hora inicial é menor que a final 
    if (!isHoraInicialMenorHoraFinal(horaInicial, horaFinal)) {
        var aux = horaFinal;
        horaFinal = horaInicial;
        horaInicial = aux;
    }

    var tempoIntervalo = diferencaHoras(horaInicial, horaFinal);
    var horaInt = tempoIntervalo.split(':');

    var hora_horaInt = horaInt[0];
    var minu_horaInt = horaInt[1];

    var totalMinutes = intervalo;
    var horas = 0;
    var hours = totalMinutes / 60;
    if (hours < 1) {
        horas = 0;
    } else {
        horas = hours;
    }
    var minutes = totalMinutes % 60;

    var tempo_intervalo = completaZeroEsquerda(horas, 2) + ":" + completaZeroEsquerda(minutes, 2);
    var conv_hora_minu_intervalo = (parseInt(hora_horaInt) * 60) + parseInt(minu_horaInt);
    var qtd_horarios = parseInt(conv_hora_minu_intervalo) / intervalo;

    var todos_horarios = [];
    var horario_atendimento;
    todos_horarios.push(horaInicial);
    for (var i = 0; i < qtd_horarios; i++) {
        horario_atendimento = somaHora(horaInicial, tempo_intervalo);
        todos_horarios.push(horario_atendimento);
        horaInicial = horario_atendimento;
    }
    return todos_horarios;
}

//Valida CPF
function TestaCPF(strCPF) {
    var Soma;
    var Resto;
    Soma = 0;
    //strCPF  = RetiraCaracteresInvalidos(strCPF,11);
    if (strCPF == "00000000000" ||
        strCPF == "11111111111" ||
        strCPF == "22222222222" ||
        strCPF == "33333333333" ||
        strCPF == "44444444444" ||
        strCPF == "55555555555" ||
        strCPF == "66666666666" ||
        strCPF == "77777777777" ||
        strCPF == "88888888888" ||
        strCPF == "99999999999")
        return false;
    for (i = 1; i <= 9; i++)
        Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
    Resto = (Soma * 10) % 11;
    if ((Resto == 10) || (Resto == 11))
        Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10)))
        return false;
    Soma = 0;
    for (i = 1; i <= 10; i++)
        Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;
    if ((Resto == 10) || (Resto == 11))
        Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11)))
        return false;
    return true;
}

//Valida CNPJ
function TestaCNPJ(cnpj) {
    //    cnpj = cnpj.replace(/[^\d]+/g, '');
    if (cnpj == '')
        return false;
    if (cnpj.length != 14)
        return false;
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;
    // Valida DVs
    var tamanho = cnpj.length - 2
    var numeros = cnpj.substring(0, tamanho);
    var digitos = cnpj.substring(tamanho);
    var soma = 0;
    var pos = tamanho - 7;
    for (var i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
        return false;
    return true;
}

//Funções para Formatação de Números
function number_format(number, decimals, dec_point, thousands_sep) {
    // %     nota 1: Para 1000.55 retorna com precisão 1 no FF/Opera é 1,000.5, mas no IE é 1,000.6
    // *     exemplo 1: number_format(1234.56);
    // *     retorno 1: '1,235'
    // *     exemplo 2: number_format(1234.56, 2, ',', ' ');
    // *     retorno 2: '1 234,56'
    // *     exemplo 3: number_format(1234.5678, 2, '.', '');
    // *     retorno 3: '1234.57'
    // *     exemplo 4: number_format(67, 2, ',', '.');
    // *     retorno 4: '67,00'
    // *     exemplo 5: number_format(1000);
    // *     retorno 5: '1,000'
    // *     exemplo 6: number_format(67.311, 2);
    // *     retorno 6: '67.31'

    var n = number, prec = decimals;
    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep == "undefined") ? ',' : thousands_sep;
    var dec = (typeof dec_point == "undefined") ? '.' : dec_point;

    var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;

    var abs = Math.abs(n).toFixed(prec);
    var _, i;

    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;

        _[0] = s.slice(0, i + (n < 0)) +
            _[0].slice(i).replace(/(\d{3})/g, sep + '$1');

        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }

    return s;
}

function moedaParaNumero(valor) {
    return isNaN(valor) == false ? parseFloat(valor) : parseFloat(valor.replace("R$", "").replace(".", "").replace(",", "."));
}

/*
 * Detalhe dos parametros
 * @param {type} n = numero a converter
 * @param {type} c = numero de casas decimais
 * @param {type} d = separador decimal
 * @param {type} t = separador milhar
 */

function numeroParaMoeda(n, c, d, t) {
    c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

/*
 * Detalhe dos parametros
 * @param {int} cep = cep a converter para modelo formatado
 */
function exibeCep(cep) {
    var cep1 = cep.substr(0, 2);
    var cep2 = cep.substr(2, 3);
    var cep3 = cep.substr(5, 3);
    return cep1 + "." + cep2 + "-" + cep3;
}

/*
 * Formata data americana para padrão PtBR com hora
 * Detalhe dos parametros
 * @param {str} data_hora = data_hora a converter para modelo formatado
 */
function formataDataHoraBR(data_hora) {
    var dt_hr = data_hora.split(" ");
    var data = dt_hr[0].split("-");
    var hora = dt_hr[1];
    return data[2] + "/" + data[1] + "/" + data[0] + " " + hora;
}

/*
 * Formata data americana whith timezone para padrão PtBR com hora
 * Detalhe dos parametros
 * @param {str} data_hora = data_hora a converter para modelo formatado
 */
function formataDataHoraTZBR(data_hora) {
    var dt_hr = data_hora.split("T");
    var data = dt_hr[0].split("-");
    var hora = dt_hr[1].split(".");
    return data[2] + "/" + data[1] + "/" + data[0] + " " + hora[0];
}

/*
 * Formata data americana para padrão PtBR
 * Detalhe dos parametros
 * @param {str} data = data a converter para modelo formatado
 */
function formataDataBR(data) {
    var data = data.split("-");
    return data[2] + "/" + data[1] + "/" + data[0];
}

/*
 * Formata data americana para padrão USA com hora
 * Detalhe dos parametros
 * @param {str} data_hora = data_hora a converter para modelo formatado
 */
function formataDataHoraUSA(data_hora) {
    var dt_hr = data_hora.split(" ");
    var data = dt_hr[0].split("/");
    var hora = dt_hr[1];
    return data[2] + "/" + data[1] + "/" + data[0] + " " + hora;
}

/*
 * Formata data americana para padrão USA
 * Detalhe dos parametros
 * @param {str} data = data a converter para modelo formatado
 */
function formataDataUSA(data) {
    var data = data.split("/");
    return data[2] + "-" + data[1] + "-" + data[0];
}

/*
 * Formata os números de CPF e CNPJ removendo os carateres especiais
 * Detalhe dos parametros
 * @param {str} cpf_cnpj = data a converter para modelo formatado
 */
function formataCPFCNPJ(cpf_cnpj) {
    var cpf1 = cpf_cnpj.replace(".", "");
    var cpf2 = cpf1.replace(".", "");
    var cpf3 = cpf2.replace("-", "");
    var cpf4 = cpf3.replace("/", "");
    return cpf4;
}

/*
 * Formata os números de TELEFONE removendo os carateres especiais
 * Detalhe dos parametros
 * @param {str} cpf_cnpj = data a converter para modelo formatado
 * @return {arr} = retorna um array com o ddd e o num
 */
function TelefoneDDD(telefone) { //(91) 3244-9464
    var tel = telefone.replace("-", "");
    var tel2 = tel.replace("(", "");
    var tel3 = tel2.replace(")", "");
    var tel4 = tel3.split(" ");
    return { ddd: tel4[0], num: tel4[1] };
}

/*
 * Formata os números de CEP removendo os carateres especiais
 * Detalhe dos parametros
 * @param {str} cep = data a converter para modelo formatado
 * @return {str} = retorna um string com o cep
 */
function formata_cep(cep) { //66.113-280
    var cep_limpo = cep
    cep_limpo = cep_limpo.replace('-', '');
    cep_limpo = cep_limpo.replace('.', '');
    return cep_limpo;
}

//Helper function for calculation of progress
function formatFileSize(bytes) {
    "use strict";
    if (typeof bytes !== "number") {
        return "";
    }
    if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + " GB";
    }
    if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + " MB";
    }
    return (bytes / 1000).toFixed(2) + " KB";
}

//Máscaras
$(document).ready(function () {
    $(".ct_credito").mask("0000 0000 0000 0000"); //Cartão de Crédito
    $(".valid_credito").mask("00/0000"); //Validade do Cartão de Crédito
    $(".cs_credito").mask("000"); //Código de Segurança do Cartão de Crédito
    $(".perc").mask("000,00 %"); //Limite do Percentual
    $(".competencia_ano").mask("00/0000"); //Competência/Ano
    $(".ano").mask("0000"); //Ano
    $(".cnpj").mask("00.000.000/0000-00"); //CNPJ
    $(".cpf").mask("000.000.000-00"); //CPF
    $(".data").mask("00/00/0000"); //Data
    $(".hora").mask("00:00"); //Hora
    $(".minutos").mask("00"); //Minutos
    $(".cep").mask("00.000-000"); //CEP
    $(".telefone").mask("(00) 0000-0000"); //Telefone Fixo ou Fax
    $(".celular").mask("(00) 0000-00009"); //Celular com até 9 digitos
    $(".valor_monetario").maskMoney({ symbol: 'R$ ', symbolStay: false, allowNegative: true, thousands: '.', decimal: ',', affixesStay: false }); //Valor Monetário R$
    $(".valor_monetario_limpo").maskMoney({ allowNegative: true, thousands: '.', decimal: ',', affixesStay: false }); //Valor Monetário sem R$
    $(".valor_percentual").maskMoney({ allowNegative: true, thousands: '.', decimal: ',', affixesStay: false }); //Valor Decimal
    $(".valor_inteiro").maskMoney({ allowNegative: true, thousands: '.', decimal: '', affixesStay: false, precision: 0 }); //Valor Decimal
    $(".mask_funcional").mask("00.000.0000"); //Funcional Programática
    $('.ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
    $('.ip2').mask('0ZZ.0ZZ.0ZZ.0ZZ/00', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
    $(".codigo_contabil").mask('0.ZZ.ZZ.ZZ.ZZ.ZZ.ZZ.ZZ.ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
    $(".numero_documento_oficial").mask('000ZZZZ/0000', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
});

//JQuery Validator para métodos customizados
jQuery.validator.addMethod("maiorQueZero", function(value, element) {
    return this.optional(element) || (parseFloat(value) > 0);
}, "O valor precisa ser maior que 0!");

jQuery.validator.addMethod("entre1e12", function(value, element) {
    return this.optional(element) || (parseFloat(value) > 0 && parseFloat(value) <= 12);
}, "O valor precisa ser entre 1 e 12!");