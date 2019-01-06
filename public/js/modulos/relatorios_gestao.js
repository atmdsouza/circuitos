//Gerar o relatório Customizado
$("#gerar_relatorio").on("click", function(){
    var eixo_x = $("#eixo_x").val();
    var eixo_y = $("#eixo_y").val();
    var ordenar_campo = $("#ordenar_campo").val();
    var ordenar_sentido = $("#ordenar_sentido").val();

    var action = actionCorreta(window.location.href.toString(), "relatorios_gestao/relatorioCustomizado");
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: action,
        data: {eixo_x: eixo_x, eixo_y: eixo_y, ordenar_campo: ordenar_campo, ordenar_sentido: ordenar_sentido},
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
            console.log(data);
        }
    });

});