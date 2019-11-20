//Função do que deve ser carregado no Onload (Obrigatória para todas os arquivos)
function inicializar()
{

}
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var geocoder = new google.maps.Geocoder;
//Sessão de declaração das variáveis
var mapa_terreno;
var marcacoes = [];
var markerClustererPositivacao;
var infowindowPositivacao;
var oms;
var caminho = [];
var mcClusterIconFolder = "http://127.0.0.1/circuitos/public/images/sistema/cluster_icons";
var URLImagensSistema = "http://127.0.0.1/circuitos/public/images/sistema";
//Funções de configuração do Mapa
function init()
{
    "use strict";
    //Carga incial do Mapa - Todos Marcadores
    var action = actionCorreta(window.location.href.toString(), "core/processarAjaxAutocomplete");
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: action,
        data: {metodo: 'estacoesTeleconAtivas'},
        beforeSend: function () {
            var loader = "<img id='loader_map' class='rem_loader' src='" + URLImagensSistema + "/loader_gears.gif' alt='Carregando' title='Carregando'/>";
            loader += "<p class='rem_loader'>Aguarde... Carregando o Mapa!</p>";
            $("#loader_mapa").append(loader);
        },
        complete: function () {
            $(".rem_loader").remove();
        },
        error: function () {
            alert("Erro na action: " + action);
        },
        success: function (data) {
            $("#mapa").append("<div id='mapa-terreno' style='height: 650px;'></div>");
            //Rederização do Mapa
            var mapOptions = {
                zoom: 15,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.DEFAULT,
                },
                disableDoubleClickZoom: true,
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                },
                scaleControl: true,
                scrollwheel: true,
                panControl: true,
                streetViewControl: true,
                draggable: true,
                overviewMapControl: true,
                overviewMapControlOptions: {
                    opened: false,
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            }
            //Mapa
            var mapElement = document.getElementById('mapa-terreno');
            mapa_terreno = new google.maps.Map(mapElement, mapOptions);
            //Criando as janelas de Identificação
            infowindowPositivacao = new google.maps.InfoWindow();
            // Abrindo a janela de Identificação com o clique no marcador
            google.maps.event.addListener(mapa_terreno, 'click', function () {
                infowindowPositivacao.close();
            });
            // Create OverlappingMarkerSpiderfier instsance
            oms = new OverlappingMarkerSpiderfier(mapa_terreno, {markersWontMove: true, markersWontHide: true});
            mostrarMarcacoes(data.dados);
        }
    });
}

function mostrarMarcacoes(array_pontos)
{
    "use strict";
    var bounds = new google.maps.LatLngBounds();
    for (var a = 0; a < array_pontos.length; a++) {
        var latlng = new google.maps.LatLng(array_pontos[a].latitude, array_pontos[a].longitude);
        var id = array_pontos[a].id;
        var cidade_digital = array_pontos[a].cidade_digital;
        var descricao = array_pontos[a].descricao;
        var endereco = array_pontos[a].endereco;
        var bairro = array_pontos[a].bairro;
        var numero = array_pontos[a].numero;
        var cidade = array_pontos[a].cidade;
        var estado = array_pontos[a].estado;
        var sigla_estado = array_pontos[a].sigla_estado;
        var cep = array_pontos[a].cep;
        createMarker(latlng, cidade_digital, id, descricao, sigla_estado, endereco, bairro, numero, cidade, estado, cep);
        bounds.extend(latlng);
    }
    var mcOptions = {
        gridSize: 80,
        maxZoom: 15,
        styles: [
            {
                height: 53,
                url: mcClusterIconFolder + "/m1.png",
                width: 53
            },
            {
                height: 56,
                url: mcClusterIconFolder + "/m2.png",
                width: 56
            },
            {
                height: 66,
                url: mcClusterIconFolder + "/m3.png",
                width: 66
            },
            {
                height: 78,
                url: mcClusterIconFolder + "/m4.png",
                width: 78
            },
            {
                height: 90,
                url: mcClusterIconFolder + "/m5.png",
                width: 90
            }
        ]
    };
    markerClustererPositivacao = new MarkerClusterer(mapa_terreno, marcacoes, mcOptions);
    mapa_terreno.fitBounds(bounds);
}

function createMarker(latlng, cidade_digital, id, descricao, sigla_estado, endereco, bairro, numero, cidade, estado, cep)
{
    "use strict";
    var icone_mapa = null;
    // switch (tipo) {
    //     case '0'://Pedido de Serviço
    //         icone_mapa = URLImagensSistema + "/map_icon_servico_40.png";
    //         break;
    //     case '1'://Pedido de Produto
    //         icone_mapa = URLImagensSistema + "/map_icon_produto_40.png";
    //         break;
    //     case '2'://Folha Semáforo
    //         icone_mapa = URLImagensSistema + "/map_icon_semaforo_40.png";
    //         break;
    //     case '3'://Positivado
    //         if (positivado === 's') {
    //             icone_mapa = URLImagensSistema + "/map_icon_servico_40A.png";
    //         } else {
    //             icone_mapa = URLImagensSistema + "/map_icon_produto_40A.png";
    //         }
    //         break;
    //     case '5'://Visita
    //         switch (tipo_visita) {
    //             case '1':
    //                 icone_mapa = URLImagensSistema + "/map_icon_visita_40A.png";
    //                 break;
    //             case '2':
    //                 icone_mapa = URLImagensSistema + "/map_icon_entrega_40D.png";
    //                 break;
    //         }
    //         break;
    //     default:
    //         icone_mapa = null;
    //         break;
    // }
    var marker = new google.maps.Marker({
        map: mapa_terreno,
        position: latlng,
        title: 'Estação Telecon',
        animation: google.maps.Animation.DROP,
        icon: icone_mapa
    });
    google.maps.event.addListener(marker, 'click', function () {
        var iwContent = "<div id='iw_container'>";
        iwContent += "<div class='iw-subTitle'>Dados da Estação Telecon</div>";
        iwContent += "<div class='iw_content'><strong>" + descricao + "</strong></div>";
        if (numero !== null) {
            iwContent += "<div class='iw_content'><strong>Endereço:</strong> " + endereco + "&emsp;Nº: " + numero + "<br />";
        } else {
            iwContent += "<div class='iw_content'><strong>Endereço:</strong> " + endereco + "<br />";
        }
        iwContent += "<strong>Bairro:</strong> " + bairro + "&emsp;&emsp;<strong>Cidade:</strong> " + cidade + "<br />";
        iwContent += "<strong>UF:</strong> " + estado + "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<strong>CEP:</strong> " + exibeCep(cep) + "<br />";
        iwContent += "</div></div>";
        oms.addListener('click', function (marker) {
            infowindowPositivacao.setContent(iwContent);
            infowindowPositivacao.open(mapa_terreno, marker);
        });
    });
    oms.addMarker(marker);
    marcacoes.push(marker);
}

function setMapOnAll(mapa_terreno) {
    "use strict";
    for (var i = 0; i < marcacoes.length; i++) {
        marcacoes[i].setMap(mapa_terreno);
    }
}

function clearMarkers() {
    "use strict";
    setMapOnAll(null);
}

function deleteMarkers() {
    "use strict";
    clearMarkers();
    marcacoes = [];
}

function clearMarkerCluster() {
    "use strict";
    // Unset all marcacoes
    var i = 0,
        l = marcacoes.length;
    for (i; i < l; i++) {
        marcacoes[i].setMap(null)
    }
    marcacoes = [];
    // Clears all clusters and marcacoes from the clusterer.
    markerClustererPositivacao.clearMarkers();
}

function clearOverlays() {
    "use strict";
    for (var i = 0; i < marcacoes.length; i++) {
        marcacoes[i].setMap(null);
    }
    marcacoes.length = 0;
}

function criarCaminho(array_pontos) {
    "use strict";
    for (var a = 0; a < array_pontos.length; a++) {
        latlon = new google.maps.LatLng(parseFloat(array_pontos[a].latitude), parseFloat(array_pontos[a].longitude));
        caminho.push(latlon);
    }
    var flightPath = new google.maps.Polyline({
        path: caminho,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });
    flightPath.setMap(mapa_terreno);
}
//Traçando a rota
function calcRoute(array_pontos) {
    "use strict";
    var w = (array_pontos.length) - 1;
    var start = new google.maps.LatLng(parseFloat(array_pontos[0].latitude), parseFloat(array_pontos[0].longitude));
    var end = new google.maps.LatLng(parseFloat(array_pontos[w].latitude), parseFloat(array_pontos[w].longitude));
    var waypts = [];
    for (var a = 0; a < array_pontos.length; a++) {
        if (array_pontos[a].latitude != '0.0000000000' && array_pontos[a].longitude != '0.0000000000') {
            latlon = new google.maps.LatLng(parseFloat(array_pontos[a].latitude), parseFloat(array_pontos[a].longitude));
            waypts.push({
                location: latlon,
                stopover: true
            });
        }
    }
    var request = {
        origin: start,
        destination: end,
        waypoints: waypts,
        optimizeWaypoints: true,
        unitSystem: google.maps.UnitSystem.METRIC,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
        } else {
            //Alertas de erro
            switch (true) {
                case status == 'ZERO_RESULTS':
                    swal("Atenção!", "Nenhuma rota pode ser encontrada entre a origem eo destino!", "warning");
                    break;
                case status == 'UNKNOWN_ERROR':
                    swal("Atenção!", "Uma solicitação de direções não pôde ser processada devido a um erro no servidor. O pedido pode ser bem sucedido se você tentar novamente!", "warning");
                    break;
                case status == 'REQUEST_DENIED':
                    swal("Atenção!", "Esta página não tem permissão para usar o serviço de instruções!", "warning");
                    break;
                case status == 'OVER_QUERY_LIMIT':
                    swal("Atenção!", "A página web passou pelo limite de solicitações em um período de tempo muito curto!", "warning");
                    break;
                case status == 'NOT_FOUND':
                    swal("Atenção!", "Pelo menos um dos destinos (waypoints) não pode ser geocodificado!", "warning");
                    break;
                case status == 'INVALID_REQUEST':
                    swal("Atenção!", "DirectionsRequest fornecido foi inválido!", "warning");
                    break;
                default:
                    swal("Atenção!", "Houve um erro desconhecido em seu pedido. Requeststatus: " + status, "warning");
                    break;
            }
        }
    });
}

//Coletando cidade pela latitude e longitude
function geocodeLatlng(geocoder, lat, lng) {
    "use strict";
    var latlng = {lat: lat, lng: lng};
    geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === 'OK') {
            if (results[0]) {
                map.setZoom(11);
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
            } else {
                swal("Atenção!", "Não foram localizados resultados", "warning");
            }
        } else {
            swal("Atenção!", "O serviço geocode falhou. Status: " + status, "warning");
        }
    });
}

//Inicialização do Mapa
google.maps.event.addDomListener(window, 'load', init);
