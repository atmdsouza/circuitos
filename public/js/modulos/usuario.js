var table = $('#tb_usuario').DataTable({
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
            }

        },
        {//Botão Deletar Registro
            className: 'bt_del',
            text: 'Deletar',
            name: 'del', // do not change name
            titleAttr: 'Deletar registro',
            action: function (e, dt, node, config) {
            }

        },
        {//Botão Ativar Registro
            className: 'bt_ativo',
            text: 'Ativar',
            name: 'ativo', // do not change name
            titleAttr: 'Ativar registro',
            action: function (e, dt, node, config) {
            }

        },
        {//Botão Inativar Registro
            className: 'bt_inativo',
            text: 'Inativar',
            name: 'inativo', // do not change name
            titleAttr: 'Inativar registro',
            action: function (e, dt, node, config) {
            }

        },
        {//Botão Selecionar
            extend: 'selectAll',
            text: 'Selecionar',
            titleAttr: 'Selecionar Todos os Registros'
        },
        {//Botão Limpar Seleção
            extend: 'selectNone',
            text: 'Limpar',
            titleAttr: 'Limpar Seleção dos Registros'
        },
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

table.buttons().container().appendTo('#tb_usuario_wrapper .col-md-6:eq(0)');

$(".bt_novo").on("click", function(){
    $("#modalusuario").modal();
    $("#salvaUser").removeClass("editar_usuario").addClass("criar_usuario");
});

$(document).on("click", ".criar_usuario", function(){
    var dados = $("#formUser").serialize();
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'usuario/criarUsuario',
        data: dados,
        beforeSend: function () {
        },
        complete: function () {
        },
        error: function () {
        },
        success: function (data) {
        }
    });
});

$(".bt_edit").on("click", function(){
    alert("Editar");
});
$(".bt_del").on("click", function(){
    alert("Deletar");
});
$(".bt_ativo").on("click", function(){
    alert("Ativar");
});
$(".bt_inativo").on("click", function(){
    alert("Inativar");
});