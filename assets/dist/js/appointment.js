
var idcustomer;
var tabla_appointment;

$(function() {

     $('#date_start').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'es'
    });

    $('#date_end').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'es'
    });

    //timepker only time
    $('#time_start').datetimepicker({
        format: 'LT'
    });

    $('#time_end').datetimepicker({
        format: 'LT'
    });


idcustomer=$("#customer_id").val();
tabla_appointment = $('#appointment').DataTable({ 
        "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
        },
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url":base_url+"customers/ajax_list_appointment/"+idcustomer,
            "type": "POST"
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
 
 });

    $(".fechaDatepiker").datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });
});
function reload_table2()
{
    tabla_appointment.ajax.reload(null,false); //reload datatable ajax 
}

function edit_event(event_id=-1){
    $.ajax({
        url: base_url+'customers/get_event_by_id/'+event_id,
        type: 'POST',
        dataType: 'json',
        data: {},
    })
    .done(function(res) {
        $("#event_id").val(res.event_id);
        $("#search_patient").val(res.name_customer);
        $("#description").val(res.description);
        $("#start").val(res.start_date);
        $("#end").val(res.end_date);
        $("#start_time").val(moment(res.start_time).format('LT'));
        $("#end_time").val(moment(res.end_time).format('LT'));
        $("#lawyer_id").val(res.lawyer_id);
        $("#status").val(res.status);
        $("#modal_edit_cita").modal("show");
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}
function updateEvent() {
    $('#btnUpdate').text('Actualizando...'); //change button text
    $('#btnUpdate').attr('disabled',true); //set button disable 
    var url = base_url+'customers/ajax_update_event';

    var formData = new FormData($('#crud-form-event')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {
            if(data=='1') //if success close modal and reload ajax table
            {
                $('#modal_edit_cita').modal('hide');
                reload_table2();
            }
            $('#btnUpdate').text('Actualizar'); //change button text
            $('#btnUpdate').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnUpdate').text('Actualizar'); //change button text
            $('#btnUpdate').attr('disabled',false); //set button enable 
        }
    });
}


function delete_event(evetent_id) {

    if(confirm('Estas seguro que quieres eliminar?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+'customers/event_delete/'+ evetent_id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_edit_cita').modal('hide');
                reload_table2();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al eliminar');
            }
        });
    }    
}