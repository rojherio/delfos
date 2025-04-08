$(function () {
  "use strict";

  var qtdCols = $('#table_modelo_01 > thead > tr > th').not('.no-print').length;
  var colsExport = '0';
  for (var i = 1; i < qtdCols; i++) {
    colsExport += ', '+i;
  }
  var titleTable = $('#titulo_relatorio').val();
  // var user = $('input#zatu_nome').val();
  var user = 'usuario';

  $('#table_modelo_01').DataTable( {
    dom: '<"ps-3 pe-3"Blfrtip>',
    // dom: 'Bfrtip',
    //geral
    paging        : true,
    pagingType    : "simple_numbers",
    lengthChange  : true,
    searching     : true,
    ordering      : true,
    info          : true,
    autoWidth     : true,
    buttons: [
      { extend: 'copy',     text: 'COPIAR',   messageTop: titleTable, messageBottom: '', exportOptions: {columns: [ colsExport ]}},
      { extend: 'csv',      text: 'CVS',      messageTop: titleTable, messageBottom: '', exportOptions: {columns: [ colsExport ]}},
      { extend: 'excel',    text: 'EXCEL',    messageTop: titleTable, messageBottom: '', exportOptions: {columns: [ colsExport ]}},
      { extend: 'pdfHtml5', text: 'PDF',      messageTop: titleTable, messageBottom: '', exportOptions: {columns: [ colsExport ]}},
      { 
        extend: 'print',
        text: 'Imprimir',
        // messageTop: ('<h3>'+titleTable+'</h3>'),
        // messageBottom: '',
        exportOptions: {columns: [ colsExport ]}, 
        customize: function (win) {
          // alert(JSON.stringify(win.document.body));
          impressao(win, titleTable, user);
          // alert(JSON.stringify(win.document.body));
        }
      }
      ],
    //exibir
    lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"] ],
    //pesquisa
    "columnDefs": [
      { searchable: false, targets: 0 },
      { orderable:  false, targets: qtdCols-1 },
      {className: 'dt-body-left'}
      ],
    language: {
      decimal             : ",",
      emptyTable          : "Não existem registros para exibir",
      info                : "Exibindo _START_ a _END_ de _TOTAL_ registros",
      infoEmpty           : "Exibindo 0 a 0 de 0 registros",
      infoFiltered        : "(filtrado de um total de _MAX_ registros)",
      infoPostFix         : "",
      thousands           : ",",
      lengthMenu          : "Exibir _MENU_ registros",
      loadingRecords      : "Carregando...",
      processing          : "Processando...",
      search              : "Pesquisar: ",
      searchPlaceholder   : "Digite sua pesquisa",
      zeroRecords         : "Nenhum registro encontrado",
      paginate: {
        first             : "Primeiro",
        last              : "Último",
        next              : "Proximo",
        previous          : "Anterior"
      },
      aria: {
        sortAscending     : ": classificar em ordem ascendente",
        sortDescending    : ": classificar em ordem descendente"
      }
    }
  });
}); // End of use strict

function impressao(win, titleTable, user) {
  var dateNow = new Date();
  var dd = String(dateNow.getDate()).padStart(2, '0');
  var mm = String(dateNow.getMonth() + 1).padStart(2, '0');
  var yyyy = dateNow.getFullYear();
  var today = dd + '/' + mm + '/' + yyyy;
  var time = addZero(dateNow.getHours()) + ":" + addZero(dateNow.getMinutes()) + ":" + addZero(dateNow.getSeconds());
  var htmlBefore = '';
  htmlBefore += '<style type="text/css">';
  htmlBefore += '  *{';
  htmlBefore += '      margin: 0;';
  htmlBefore += '      padding: 0;';
  htmlBefore += '      box-sizing: border-box;';
  htmlBefore += '      font-family: Calibri, Helvetica, sans-serif;';
  htmlBefore += '  }';
  htmlBefore += '  header {';
  htmlBefore += '      display: flex;';
  htmlBefore += '      justify-content: flex-start;';
  htmlBefore += '      align-items: center;';
  htmlBefore += '      padding: 10px;';
  htmlBefore += '      border-bottom: 1px solid #c1c1c1;';
  htmlBefore += '      height:120px;';
  htmlBefore += '  }';
  htmlBefore += '  header .img{';
  htmlBefore += '      width: 100px;';
  htmlBefore += '  }';
  htmlBefore += '  header .img img{';
  htmlBefore += '      width: 100%;';
  htmlBefore += '  }';
  htmlBefore += '  header .info{';
  htmlBefore += '      display: flex;';
  htmlBefore += '      flex-direction: column;';
  htmlBefore += '      align-items: flex-start;';
  htmlBefore += '      width: calc(100% - 400px);';
  htmlBefore += '      padding: 0 10px;';
  htmlBefore += '  }';
  htmlBefore += '  header .info span {';
  htmlBefore += '      font-size:15px;';
  htmlBefore += '      font-weight: 400;';
  htmlBefore += '  }';
  htmlBefore += '  header .data{';
  htmlBefore += '      display: flex;';
  htmlBefore += '      flex-direction: column;';
  htmlBefore += '      align-items: flex-start;';
  htmlBefore += '      width: 300px;';
  htmlBefore += '  }';
  htmlBefore += '  header .data span{';
  htmlBefore += '      font-size:15px;';
  htmlBefore += '  }';
  htmlBefore += '  .content{';
  htmlBefore += '      padding: 20px;';
  htmlBefore += '      min-height: calc(100vh - 200px);';
  htmlBefore += '  }';
  htmlBefore += '  .content h1{';
  htmlBefore += '      text-align: center;';
  htmlBefore += '      font-size: 24px;';
  htmlBefore += '  }';
  htmlBefore += '  .content .list-info {';
  htmlBefore += '      display: flex;';
  htmlBefore += '  }';
  htmlBefore += '  .content .list-info ul{';
  htmlBefore += '      width:50%;';
  htmlBefore += '      min-width: 300px;';
  htmlBefore += '      list-style: none;';
  htmlBefore += '      padding: 0;';
  htmlBefore += '      margin: 20px 0;';
  htmlBefore += '      display: flex;';
  htmlBefore += '      flex-direction: column;';
  htmlBefore += '  }';
  htmlBefore += '  .content table {';
  htmlBefore += '      width: 100%;';
  htmlBefore += '      border-collapse: collapse;';
  htmlBefore += '  }';
  htmlBefore += '  .content table thead{';
  htmlBefore += '      border-bottom: 1px solid #c1c1c1;';
  htmlBefore += '  }';
  htmlBefore += '  .content table th {';
  htmlBefore += '      text-align:left;';
  htmlBefore += '      padding: 10px 5px;';
  htmlBefore += '      text-transform: uppercase;';
  htmlBefore += '      font-size: 12px;';
  htmlBefore += '  }';
  htmlBefore += '  .content table tbody{';
  htmlBefore += '      border-bottom: 1px solid #c1c1c1;';
  htmlBefore += '  }';
  htmlBefore += '  .content table td {';
  htmlBefore += '      text-align: left;';
  htmlBefore += '      padding: 10px 5px;';
  htmlBefore += '      font-size: 12px;';
  htmlBefore += '  }';
  htmlBefore += '  .content table tbody tr{';
  htmlBefore += '      border-bottom: 1px solid #f1f1f1;';
  htmlBefore += '  }';
  htmlBefore += '  .content table tbody tr:last-child{';
  htmlBefore += '      border-bottom: none;';
  htmlBefore += '  }';
  htmlBefore += '  .content table tbody tr:nth-child(even){';
  htmlBefore += '      background-color: #f1f1f1;';
  htmlBefore += '  }';
  htmlBefore += '  footer {';
  htmlBefore += '      border-top: 1px solid #c1c1c1;';
  htmlBefore += '      height:80px;';
  htmlBefore += '      display: flex;';
  htmlBefore += '      justify-content:center;';
  htmlBefore += '      align-items: center;';
  htmlBefore += '      flex-direction: column;';
  htmlBefore += '  }';
  htmlBefore += '  footer .logo img {';
  htmlBefore += '      width:90px;';
  htmlBefore += '  }';
  htmlBefore += '  footer .text {';
  htmlBefore += '      font-weight:bold;';
  htmlBefore += '      font-size:13px;';
  htmlBefore += '  }';
  htmlBefore += '</style>';
  var htmlHead = '';
  // htmlHead += '<head>';
  htmlHead += '  <meta charset="UTF-8">';
  htmlHead += '  <meta http-equiv="X-UA-Compatible" content="IE=edge">';
  htmlHead += '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
  htmlHead += '  <title>:: ZATU | Relatórios ::</title>';
  // htmlHead += '</head>';
  var htmlPrepend = '';
  htmlPrepend += '<header>';
  htmlPrepend += '      <div class="img">';
  htmlPrepend += '          <img src="'+PORTAL_URL+'/assets/images/brasao_tarauaca.png" alt="">';
  htmlPrepend += '      </div>';
  htmlPrepend += '      <div class="info">';
  htmlPrepend += '          <span>Estado do Acre</span>';
  htmlPrepend += '          <span>Prefeitura Municipal de Tarauacá</span>';
  htmlPrepend += '          <span>Secretaria Municipal de Administração</span>';
  htmlPrepend += '          <span>Coordenação de Recursos Humanos</span>';
  htmlPrepend += '      </div>';
  htmlPrepend += '      <div class="data">';
  htmlPrepend += '          <span><strong>Data:</strong> '+today+'</span>';
  htmlPrepend += '          <span><strong>Hora:</strong> '+time+'</span>';
  htmlPrepend += '          <span><strong>Usuário:</strong> '+user+'</span>';
  htmlPrepend += '      </div>';
  htmlPrepend += '</header>';
  htmlPrepend += '<div class="content">';
  htmlPrepend += '      <h1>'+titleTable+'</h1>';
  // htmlPrepend += '      <div class="list-info">';
  // htmlPrepend += '          <ul>';
  // htmlPrepend += '              <li><strong>Secretaria:</strong> Todos</li>';
  // htmlPrepend += '              <li><strong>Lotação:</strong> Todos</li>';
  // htmlPrepend += '              <li><strong>Atualização Cadastral:</strong> Todos</li>';
  // htmlPrepend += '          </ul>';
  // htmlPrepend += '          <ul>';
  // htmlPrepend += '              <li><strong>Mês de Aniversário:</strong> Janeiro</li>';
  // htmlPrepend += '              <li><strong>Tipo de Pessoa:</strong> Servidor</li>';
  // htmlPrepend += '              <li><strong></strong></li>';
  // htmlPrepend += '          </ul>';
  // htmlPrepend += '      </div>';
  htmlPrepend += '</div>';
  var htmlAppend = '';
  htmlAppend += '<footer>';
  htmlAppend += '      <div class="logo">';
  htmlAppend += '          <img src="'+PORTAL_URL+'/assets/images/zatu-logo.svg" alt="">';
  htmlAppend += '      </div>';
  htmlAppend += '      <div class="text">';
  htmlAppend += '          <p>Copyright © Prefeitura Municipal de Tarauacá - Desenvolvido por Wessix Tecnologia e Inovação</p>';
  htmlAppend += '      </div>';
  htmlAppend += '</footer>';

  // $(win.document.head).html(htmlHead);
  // .css( 'font-size', '10pt' )
  $(win.document.body).find('h1').remove();
  $(win.document.body).find('div:first-child').remove();
  $(win.document.body).find('div:last-child').remove();
  $(win.document.body).before(htmlBefore);
  $(win.document.body).prepend(htmlPrepend);
  $(win.document.body).append(htmlAppend);
  $(win.document.body).removeClass('dt-print-view');
  $(win.document.body).find('table').removeClass('table table-hover dataTable no-footer');
  $(win.document.body).find('table').appendTo($(win.document.body).find('.content'));
  // .css( 'font-size', '10pt' )
  
  // $(win.document.body).find( 'table' )
  // .addClass( 'compact' )
  // .css( 'font-size', 'inherit' );
}
function addZero(i) {
  if (i < 10) {i = "0" + i}
    return i;
}




//  **------Tabel 1**
// $(function() {
//     $('#example').DataTable(); 
//     $('#example1').DataTable(); 
// });
// $(function() {
//     $('#example2').DataTable( {
//         dom: 'Bfrtip',
//         buttons: [
//             'copy', 'csv', 'excel', 'pdf', 'print'
//         ]
//     } );
// } );
// $(function() {
//     $('#example3').DataTable({
//         createdRow: function (row, data, index) {
//             if (data[5].replace(/[\$,]/g, '') * 1 > 150000) {
//                 $('td', row).eq(5).addClass('highlight');
//             }
//         },
//     });
// });

//     /* Formatting function for row details - modify as you need */
// function format ( d ) {
//     // `d` is the original data object for the row
//     return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
//         '<tr>'+
//             '<td>Full name:</td>'+
//             '<td>'+d.name+'</td>'+
//         '</tr>'+
//         '<tr>'+
//             '<td>Extension number:</td>'+
//             '<td>'+d.extn+'</td>'+
//         '</tr>'+
//         '<tr>'+
//             '<td>Extra info:</td>'+
//             '<td>And any further details here (images etc)...</td>'+
//         '</tr>'+
//     '</table>';
// }

// $(function() {
//     // Add event listener for opening and closing details
//     $('#example4').on('click', 'tbody td.dt-control', function () {
//         var tr = $(this).closest('tr');
//         var row = table.row( tr );
 
//         if ( row.child.isShown() ) {
//             // This row is already open - close it
//             row.child.hide();
//         }
//         else {
//             // Open this row
//             row.child( format(row.data()) ).show();
//         }
//     } );
 
//     $('#example4').on('requestChild.dt', function(e, row) {
//         row.child(format(row.data())).show();
//     })
 
//     var table = $('#example4').DataTable( {
//         "ajax": "../assets/vendor/datatable/ajax/objects.txt",
//         "rowId": 'id',
//         "columns": [
//             {
//                 "className":      'dt-control',
//                 "orderable":      false,
//                 "data":           null,
//                 "defaultContent": ''
//             },
//             { "data": "name" },
//             { "data": "position" },
//             { "data": "office" },
//             { "data": "salary" }
//         ],
//         "order": [[1, 'asc']],
//         dom: 'Blfrtip',
//         buttons:['createState', 'savedStates']
//     } );
 
//     table.on('stateLoaded', (e, settings, data) => {
//         for(var i = 0; i < data.childRows.length; i++) {
//             var row = table.row(data.childRows[i]);
//             row.child(format(row.data())).show();
//         }
//     })
// } );

// // Delete btn js
// document.addEventListener('DOMContentLoaded', (event) => {
//     // Function to handle delete action
//     const handleDelete = (event) => {
//         const deleteButton = event.target;
//         if (deleteButton.classList.contains('delete-btn')) {
//             const row = deleteButton.closest('tr');
//             row.remove();
//         }
//     };
  
//     // Add event listener to all delete buttons
//     const deleteButtons = document.querySelectorAll('.delete-btn');
//     deleteButtons.forEach(button => {
//         button.addEventListener('click', handleDelete);
//     });
//   });