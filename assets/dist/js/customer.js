
var save_method; //for save method string
var table;
var table_consulta;
var table_tramite;
var table_proceso;
var table_files;
var global_net=0;
var global_acuenta=0;
var custumerid;
$(function() {
    table = $('#customers').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "scrollX": true,

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+'customers/ajax_list',
            "type": "POST",
            "data":{
                
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
});

function add_customer()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Nuevo Cliente'); // Set Title to Bootstrap modal title
    $('.takephoto').hide();
    $('#photo-preview').hide(); // hide photo preview modal

    $('#label-photo').text('Subir photo'); // label photo upload   
}

function edit_customer(id_customer)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $('.takephoto').show();
    $.ajax({
        url : base_url+'customers/ajax_edit/' + id_customer,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="person_id"]').val(data.person_id);
            $('[name="hist_clinic"]').val(data.hist_clinic);
            $('[name="first_name"]').val(data.first_name);
            $('[name="last_name"]').val(data.last_name);
            $('[name="birth_date"]').datepicker('update',data.birth_date);
            $('[name="gender"]').val(data.gender);
            $('[name="email"]').val(data.email);
            $('[name="phone"]').val(data.phone);
            $('[name="cell_phone"]').val(data.cell_phone);
            $('[name="address"]').val(data.address);
            $('[name="dni"]').val(data.dni);
            $('[name="date_reg"]').val(data.date_reg);
            $('[name="comment"]').val(data.comment);
 
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Cliente'); // Set title to Bootstrap modal title

            $('#photo-preview').show(); // show photo preview modal

            if(data.photo)
            {
                $('#label-photo').text('Cambiar photo'); // label photo upload
                $('#photo-preview div').html('<img src="'+base_url+'upload/'+data.photo+'" class="img-responsive">'); // show photo
                $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="'+data.photo+'"/> Borrar foto cuando guarda'); // remove photo

            }
            else
            {
                $('#label-photo').text('Subir Foto'); // label photo upload
                $('#photo-preview div').text('(No tiene Foto)');
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = base_url+'customers/ajax_add';
    } else {
        url = base_url+'customers/ajax_update';
    }

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Guardar'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('Guardar'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        }
    });
}

function delete_customer(id)
{
    if(confirm('Estas seguro que quieres eliminar?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+'customers/ajax_delete/'+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
    }
}

$(function() {
        custumerid=$("#customer_id").val();
        table_consulta = $('#table_consulta').DataTable({
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
            "url": base_url+'customers/lista_consulta/'+custumerid,
            "type": "POST",
            "data":{
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
});

function add_consulta() {
    save_method = 'add';
    $('#crud_consulta')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_consulta').modal('show'); // show bootstrap modal
    $('.modal-title').text('Nuevo Consulta'); // Set Title to Bootstrap modal title
}

function edit_consulta(consulta_id)
{
    save_method = 'update';
    $('#crud_consulta')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : base_url+'customers/edit_consulta/' + consulta_id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="consulta_id"]').val(data.consulta_id);
            $('[name="customer_id"]').val(data.customer_id);
            $('[name="lawyer_id"]').val(data.lawyer_id);
            $('[name="fecha"]').val(data.fecha);
            $('[name="hora"]').val(data.hora);
            $('[name="tipo_consulta"]').val(data.tipo_consulta);
            $('[name="a_cuenta"]').val(data.a_cuenta);
            $('[name="saldo"]').val(data.saldo);
            $('[name="total"]').val(data.total);
            $('[name="estado"]').val(data.estado);
            //$('[name="dob"]').datepicker('update',data.dob);
            $('#modal_consulta').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Consulta'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table_consulta()
{
    table_consulta.ajax.reload(null,false); //reload datatable ajax 
}

function save_consulta()
{
    $('#btnconsulta').text('Guardando...'); //change button text
    $('#btnconsulta').attr('disabled',true); //set button disable 
    var url;
    if(save_method == 'add') {
        url = base_url+'customers/save_consulta';
    } else if (save_method == 'pagar') {
        url = base_url+'customers/pagar_consulta';
    } else {
        url = base_url+'customers/update_consulta';
    }
    // var formData = new FormData($('#form')[0]);
    // console.log(formData);
    // return;
    if (save_method == 'pagar') {
        var formData = $('#pagar_consulta').serialize();
    } else {
        var formData = $('#crud_consulta').serialize();
    }
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        //contentType: false,
        //processData: false,
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                if (save_method == 'pagar') {
                    $('#modal_pagar_consulta').modal('hide');
                    reload_table_consulta();  
                } else {
                    $('#modal_consulta').modal('hide');
                    reload_table_consulta(); 
                }
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnconsulta').text('Guardar'); //change button text
            $('#btnconsulta').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnconsulta').text('Guardar'); //change button text
            $('#btnconsulta').attr('disabled',false); //set button enable 

        }
    });
}

function delete_consulta(id)
{
    if(confirm('Estas seguro que quiere eliminar?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+'customers/delete_consulta/'+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_consulta').modal('hide');
                reload_table_consulta();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al eliminar data');
            }
        });

    }
}

function pagar_consulta(consulta_id)
{
    save_method = 'pagar';
    $('#pagar_consulta')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : base_url+'customers/edit_consulta/' + consulta_id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="consulta_pag_id"]').val(data.consulta_id);
            $('[name="lawyer_pag_id"]').val(data.lawyer_id);
            $('[name="tipo_consulta_pag"]').val(data.tipo_consulta);
            $('[name="a_cuenta1"]').val(data.a_cuenta);
            $('[name="saldo1"]').val(data.saldo);
            global_net = data.saldo;
            global_acuenta = data.a_cuenta;
            $('[name="total1"]').val(data.total);
            $('[name="estado_pag"]').val(data.estado);
            //$('[name="dob"]').datepicker('update',data.dob);
            $('#modal_pagar_consulta').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Consulta'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

$("#a_cuenta").keyup(function(){
    var total = $('#total').val();
    var acuenta = $('#a_cuenta').val();
    operacion = total - acuenta;
    $('#saldo').val(operacion);
});

$("#total").keyup(function(){
    var total = $('#total').val();
    var acuenta = $('#a_cuenta').val();
    operacion = total - acuenta;
    if (acuenta != 0) {
        $('#saldo').val(operacion);
    } else {
        $('#saldo').val(0);
    }
});

$("#pagar").keyup(function(){
    var temp = global_net;
    var temp_acuenta = global_acuenta;
    var pagar1 = $('#pagar').val();
    var total = $('#total1').val();
    pagar1 = pagar1!=''?pagar1:0;
    var acuenta_total = parseFloat(temp_acuenta) + parseFloat(pagar1);
    if (acuenta_total>=parseFloat(total)) {
        $("#a_cuenta1").val(0);
    }else{
        $("#a_cuenta1").val(acuenta_total);
    }
    operacion1 = parseFloat(temp) - parseFloat(pagar1);
    $('#saldo1').val(operacion1);
});

function imprimir_consulta(id) {
    $.ajax({
        url : base_url+'customers/print_documento/'+id,
        type: "POST",
        dataType: "html",
        success: function(data)
        {
            $('#ipdf').html(data);
            $('#modal_print').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al imprimir');
        }
    });
}

//Seccion Tramites

$(function() {
    custumertramid=$("#customer_tram_id").val();
    table_tramite = $('#table_tramite').DataTable({
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
            "url": base_url+'customers/lista_tramite/'+custumertramid,
            "type": "POST",
            "data":{
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
});

function add_tramite() {
    save_method = 'add';
    $('#crud_tramite')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_tramite').modal('show'); // show bootstrap modal
    $('.modal-title').text('Nuevo Tramite'); // Set Title to Bootstrap modal title
}

function edit_tramite(tramite_id)
{
    save_method = 'update';
    $('#crud_tramite')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : base_url+'customers/edit_tramite/' + tramite_id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="tramite_id"]').val(data.tramite_id);
            $('[name="customer_tram_id"]').val(data.customer_id);
            $('[name="lawyer_tram_id"]').val(data.lawyer_id);
            $('[name="fecha_tram"]').val(data.fecha_tram);
            $('[name="hora_tram"]').val(data.hora_tram);
            $('[name="tipo_tramite"]').val(data.tipo_tramite);
            $('[name="a_cuenta_tram"]').val(data.a_cuenta_tram);
            $('[name="saldo_tram"]').val(data.saldo_tram);
            $('[name="total_tram"]').val(data.total_tram);
            $('[name="estado_tram"]').val(data.estado_tram);
            //$('[name="dob"]').datepicker('update',data.dob);
            $('#modal_tramite').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Tramite'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table_tramite()
{
    table_tramite.ajax.reload(null,false); //reload datatable ajax 
}

function save_tramite()
{
    $('#btntramite').text('Guardando...'); //change button text
    $('#btntramite').attr('disabled',true); //set button disable 
    var url;
    if(save_method == 'add') {
        url = base_url+'customers/save_tramite';
    } else if (save_method == 'pagar') {
        url = base_url+'customers/pagar_tramite';
    } else {
        url = base_url+'customers/update_tramite';
    }
    // var formData = new FormData($('#form')[0]);
    // console.log(formData);
    // return;
    if (save_method == 'pagar') {
        var formData = $('#pagar_tramite').serialize();
    } else {
        var formData = $('#crud_tramite').serialize();
    }
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        //contentType: false,
        //processData: false,
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                if (save_method == 'pagar') {
                    $('#modal_pagar_tramite').modal('hide');
                    reload_table_tramite();  
                } else {
                    $('#modal_tramite').modal('hide');
                    reload_table_tramite(); 
                }
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btntramite').text('Guardar'); //change button text
            $('#btntramite').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btntramite').text('Guardar'); //change button text
            $('#btntramite').attr('disabled',false); //set button enable 

        }
    });
}


function pagar_tramite(tramite_id)
{
    save_method = 'pagar';
    $('#pagar_tramite')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : base_url+'customers/edit_tramite/' + tramite_id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="tramite_pag_id"]').val(data.tramite_id);
            $('[name="lawyer_pag_tram_id"]').val(data.lawyer_id);
            $('[name="tipo_pag_tramite"]').val(data.tipo_tramite);
            $('[name="a_cuenta_tram1"]').val(data.a_cuenta_tram);
            $('[name="saldo_tram1"]').val(data.saldo_tram);
            global_net = data.saldo_tram;
            global_acuenta = data.a_cuenta_tram;
            $('[name="total_tram1"]').val(data.total_tram);
            $('[name="estado_pag_tram"]').val(data.estado_tram);
            //$('[name="dob"]').datepicker('update',data.dob);
            $('#modal_pagar_tramite').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Tramite'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

$("#a_cuenta_tram").keyup(function(){
    var total_tram = $('#total_tram').val();
    var acuenta_tram = $('#a_cuenta_tram').val();
    operacion = total_tram - acuenta_tram;
    $('#saldo_tram').val(operacion);
});

$("#total_tram").keyup(function(){
    var total_tram = $('#total_tram').val();
    var acuenta_tram = $('#a_cuenta_tram').val();
    operacion = total_tram - acuenta_tram;
    if (acuenta_tram != 0) {
        $('#saldo_tram').val(operacion);
    } else {
        $('#saldo_tram').val(0);
    }
});

$("#pagar_tram").keyup(function(){
    var temp = global_net;
    var temp_acuenta = global_acuenta;
    var pagar1 = $('#pagar_tram').val();
    var total_tram = $('#total_tram1').val();
    pagar1 = pagar1!=''?pagar1:0;
    var acuenta_total = parseFloat(temp_acuenta) + parseFloat(pagar1);
    if (acuenta_total>=parseFloat(total_tram)) {
        $("#a_cuenta_tram1").val(0);
    }else{
        $("#a_cuenta_tram1").val(acuenta_total);
    }
    operacion1 = parseFloat(temp) - parseFloat(pagar1);
    $('#saldo_tram1').val(operacion1);
});

function imprimir_tramite(id) {
    $.ajax({
        url : base_url+'customers/print_documento_tramite/'+id,
        type: "POST",
        dataType: "html",
        success: function(data)
        {
            $('#ipdf').html(data);
            $('#modal_print').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al imprimir');
        }
    });
}

function delete_tramite(id)
{
    if(confirm('Estas seguro que quiere eliminar?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+'customers/delete_tramite/'+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_tramite').modal('hide');
                reload_table_tramite();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al eliminar data');
            }
        });

    }
}

//Seccion de Procesos

$(function() {
    custumerid=$("#customer_id").val();
    table_proceso = $('#table_proceso').DataTable({
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
            "url": base_url+'customers/lista_proceso/'+custumerid,
            "type": "POST",
            "data":{
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });


    //datepicker
    $('.datepicker_proceso').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});


function add_proceso() {
    save_method = 'add';
    $('#crud_proceso')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_proceso').modal('show'); // show bootstrap modal
    $('.modal-title').text('Nuevo Proceso'); // Set Title to Bootstrap modal title
}

function edit_proceso(id)
{
    save_method = 'update';
    $('#crud_proceso')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : base_url+'customers/ajax_edit_proceso/' + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="proceso_id"]').val(data.proceso_id);
            $('[name="lawyer_proc_id"]').val(data.lawyer_proc_id);
            $('[name="customer_proc_id"]').val(data.customer_proc_id);
            $('[name="tipo_proceso"]').val(data.tipo_proceso);
            $('[name="expediente_proc"]').val(data.expediente_proc);
            $('[name="total_proc"]').val(data.total_proc);
            $('[name="estado_proc"]').val(data.estado_proc);
            $('#modal_proceso').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Proceso'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table_proceso()
{
    table_proceso.ajax.reload(null,false); //reload datatable ajax 
}

function save_proceso()
{
    $('#btnproceso').text('Guardando...'); //change button text
    $('#btnproceso').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = base_url+'customers/ajax_add_proceso';
    } else {
        url = base_url+'customers/ajax_update_proceso';
    }
    // var formData = new FormData($('#form')[0]);
    // console.log(formData);
    // return;
    var formData = $('#crud_proceso').serialize();

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        //contentType: false,
        //processData: false,
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_proceso').modal('hide');
                reload_table_proceso();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnproceso').text('Guardar'); //change button text
            $('#btnproceso').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnproceso').text('Guardar'); //change button text
            $('#btnproceso').attr('disabled',false); //set button enable 

        }
    });
}

function delete_proceso(id)
{
    if(confirm('Estas seguro que deseas eliminar este proceso?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+'customers/ajax_delete_proceso/'+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_proceso').modal('hide');
                reload_table_proceso();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}


function add_cronograma(id) {
    //save_method = 'add';
    $('#crud_cronograma')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_cronograma').modal('show'); // show bootstrap modal
    $('.modal-title').text('Lista de Cronograma'); // Set Title to Bootstrap modal title
}


function contrato_proceso(id) {
    $.ajax({
        url : base_url+'customers/print_contrato_proceso/'+id,
        type: "POST",
        dataType: "html",
        success: function(data)
        {
            $('#ipdf_contraro_proceso').html(data);
            $('#modal_contrato_print').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al imprimir');
        }
    });
}

function imprimir_cronograma(id,customer_id) {
    //save_method = 'add';
    $("#procesoID").val(id);
    $.ajax({
        url: base_url+'customers/print_cronograma_proceso',
        type: 'POST',
        dataType: 'html',
        data: {id: id,customer_id:customer_id},
        success: function(data)
        {
            $('#ipdf_proceso').html(data);
            $('#modal_print_proceso').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al imprimir');
        }
    })
}

function add_cronograma_pagos(id,customer_id) {
    //save_method = 'add';
    $("#procesoID").val(id);
    $.ajax({
        url: base_url+'customers/get_id_proceso',
        type: 'POST',
        dataType: 'json',
        data: {id: id,customer_id:customer_id},
    })
    .done(function(result) {
        var html = '';
        $.each(result, function(index, val) {
            let condicion = '';
            let condicionstatus = 'true';
            if (val.saldo==0) {
                condicion = 'bg-success';
                condicionstatus = 'false';
            }
             html += '<tr class="table-row '+condicion+'" id="table-row-'+val.payment_id+'">'+
                  '<td contenteditable="false" onBlur="saveToDatabase(this,\'payment_id\',\''+val.payment_id+'\')" onClick="editRow(this);">'+val.payment_id+'</td>'+
                  '<td contenteditable="'+condicionstatus+'" class="fecha_date" onBlur="saveToDatabase(this,\'fecha\',\''+val.payment_id+'\')" onClick="editRow(this);">'+val.fecha+'</td>'+
                  '<td contenteditable="'+condicionstatus+'" onBlur="saveToDatabase(this,\'pagar\',\''+val.payment_id+'\')" onClick="editRow(this);">'+val.pagar+'</td>'+     
                  '<td contenteditable="false" onBlur="saveToDatabase(this,\'acuenta\',\''+val.payment_id+'\')" onClick="editRow(this);">'+val.acuenta+'</td>'+
                  '<td contenteditable="false" onBlur="saveToDatabase(this,\'saldo\',\''+val.payment_id+'\')" onClick="editRow(this);">'+val.saldo+'</td>'+
                  '<td contenteditable="false" onBlur="saveToDatabase(this,\'total\',\''+val.payment_id+'\')" onClick="editRow(this);">'+val.total+'</td>'+
                  '<td><a class="btn btn-danger btn-xs" href="javascript:void(0)" title="Borrar" onclick="deleteRecord('+val.payment_id+');">Borrar</a>/<a class="btn btn-default btn-xs" href="javascript:void(0)" title="Imprimir" onclick="printRecord('+val.payment_id+');">Imprimir</a></td>'+
                '</tr>';

        });
        $('#table-body').html(html);
        console.log("success");
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
    $('#crud_cronograma_pagos')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_cronograma_pagos').modal('show'); // show bootstrap modal
    $('.modal-title').text('Lista de Cronograma'); // Set Title to Bootstrap modal title
}

var minLengthDni = 8;
var maxLengthDni = 8;
$(function(){
    $('#dni').on('keydown keyup change', function(){
        var char = $(this).val();
        var charLength = $(this).val().length;
        if(charLength < minLengthDni){
            $('span.dni').text('Debe tener '+minLengthDni+' números.');
        }else if(charLength > maxLengthDni){
            $('span.dni').text('DNI solo tiene '+maxLengthDni+' números como máximo.');
            $(this).val(char.substring(0, maxLengthDni));
        }else{
            $('span.dni').text('DNI Correcto');
        }
    });
});

$(function() {
    $('#email').on('keydown keyup change',function() {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if (re.test($(this).val())) {
            $('span.email').text('Email correcto');

        } else {
            $('span.email').text('Tu email debe incluir @ y debe ser válida');
        }
    });
});

var minLengthPhone = 7;
var maxLengthPhone = 7;
$(function(){
    $('#phone').on('keydown keyup change', function(){
        var char = $(this).val();
        var charLength = $(this).val().length;
        if (isNaN($("#phone").val())) {
            $('span.phone').text('Telf. debe tener un número válido');
        }else if(charLength < minLengthPhone){
            $('span.phone').text('Telf. debe tener '+minLengthPhone+' caracteres como minimo.');
        }else if(charLength > maxLengthPhone){
            $('span.phone').text('Numero de Teléfono es correcto.');
            $(this).val(char.substring(0, maxLengthPhone));
        }else{
            $('span.phone').text('Numero de Teléfono es Correcto');
        }
    });
});

var minLengthCellPhone = 9;
var maxLengthCellPhone = 9;
$(function(){
    $('#cell_phone').on('keydown keyup change', function(){
        var char = $(this).val();
        var charLength = $(this).val().length;
        if (isNaN($("#cell_phone").val())) {
            $('span.cell_phone').text('Celular debe tener un número válido');
        }else if(charLength < minLengthCellPhone){
            $('span.cell_phone').text('Celular debe tener '+minLengthCellPhone+' caracteres como minimo.');
        }else if(charLength > maxLengthCellPhone){
            $('span.cell_phone').text('Numero de Celular es correcto.');
            $(this).val(char.substring(0, maxLengthCellPhone));
        }else{
            $('span.cell_phone').text('Numero de Celular es Correcto');
        }
    });
});


var INDEX_ROW=1;
$(function() {
    $('#add_item').click(function(e) {
        var articulo = $('input[name="articulo0"]').val();
        var cantidad = $('input[name="cantidad0"]').val();
        var precio = $('input[name="precio0"]').val();
        var total = $('input[name="total0"]').val();
        var fila = '<tr class="fila'+INDEX_ROW+'">'+
                        '<td></td>'+
                        '<td width="50%"><input type="text" style="display:none" id="articulo'+INDEX_ROW+'" name="articulo[]" class="form-control show_input'+INDEX_ROW+'" value="'+(articulo!=''?articulo:'Default')+'"><span id="articulo_s'+INDEX_ROW+'" class="hide_span'+INDEX_ROW+'">'+(articulo!=''?articulo:'Default')+'</span></td>'+
                        '<td><input type="text" style="display:none" id="cantidad'+INDEX_ROW+'" onkeyup="change_qty('+INDEX_ROW+')" name="cantidad[]" class="form-control show_input'+INDEX_ROW+'" value="'+(cantidad!=''?cantidad:1)+'"><span id="cantidad_s'+INDEX_ROW+'" class="hide_span'+INDEX_ROW+'">'+(cantidad!=''?cantidad:1)+'</span></td>'+
                        '<td><input type="text" style="display:none" id="precio'+INDEX_ROW+'" onkeyup="change_price('+INDEX_ROW+')" name="precio[]" class="form-control show_input'+INDEX_ROW+'" value="'+(precio!=''?precio:0)+'"><span id="precio_s'+INDEX_ROW+'" class="hide_span'+INDEX_ROW+'">'+(precio!=''?precio:0)+'</span></td>'+
                        '<td><input type="text" style="display:none" id="total'+INDEX_ROW+'" readonly="" name="total[]" class="form-control show_input'+INDEX_ROW+'" value="'+(total!=''?total:0)+'"><span id="total_s'+INDEX_ROW+'" class="hide_span'+INDEX_ROW+'">'+(total!=''?total:0)+'</span></td>'+
                        '<td><button type="button" style="display:none" onClick="update_item('+INDEX_ROW+')" class="btn btn-success show_input'+INDEX_ROW+'">Save</button><button type="button" onClick="edit_item('+INDEX_ROW+')" class="btn btn-primary hide_span'+INDEX_ROW+'">Edit</button></td>'+
                        '<td><button type="button" onClick="delete_item('+INDEX_ROW+')" class="btn btn-danger">Delete</button></td>'+
                    '</tr>';
        $('#table tbody').append(fila);
        INDEX_ROW++;
        calcular_total();
        $('input[name="articulo0"]').val('');
        $('input[name="cantidad0"]').val(1);
        $('input[name="precio0"]').val(0);
        $('input[name="total0"]').val(0);
    });

    $('#form_data').submit(function(e) {
        e.preventDefault();

        if (validar()) {
            var formData = new FormData($('#form_data')[0]);
            $.ajax({
                url: base_url+'sales/add_sales',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType:false,
                processData:false,

                success: function(data)
                {
                    if(data.status) //if success close modal and reload ajax table
                    {
                        refresh();
                    }
                    else
                    {
                    alert('Error');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error adding / update data');
                }
            })
        }else{
            alert('Debe agregar como minimo un item');
        }
    });

/*    $('#hora_xx').timepicker();
    // Bootstrap datepicker
    $('.form-control.fecha_xx').datepicker({
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        language: 'es'
    });

    $('.form-control.revision_xx').datepicker({
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        orientation: 'bottom'
    });

    $('.form-control.cilindro_xx').datepicker({
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        orientation: 'bottom'
    });*/

    var elements = document.getElementsByTagName("INPUT");
    for (var i = 0; i < elements.length; i++) {
        elements[i].oninvalid = function (e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {
                switch (e.srcElement.id) {
                    case "nom":
                        e.target.setCustomValidity("Cliente es requerido");
                        break;
                    case "ruc":
                        e.target.setCustomValidity("RUC es requerido");
                        break;
                }
            }
        };
        elements[i].oninput = function (e) {
            e.target.setCustomValidity("");
        };
    }

});

function validar() {
    var cont = 0;
    $('input[name="total[]"]').each(function(index, el) {
        cont ++;
    });
    return cont;
}

function refresh() {
    document.location.href = base_url+"customers";
}

function delete_item(index){
    $(".fila"+index).remove();
    calcular_total();
}
function edit_item(index){
    $(".show_input"+index).show();
    $(".hide_span"+index).hide();
}
function update_item(index){
    var articulo = $('#articulo'+index).val();
    var cantidad = $('#cantidad'+index).val();
    var precio = $('#precio'+index).val();
    var total = $('#total'+index).val();
    $('#articulo_s'+index).text(articulo);
    $('#cantidad_s'+index).text(cantidad);
    $('#precio_s'+index).text(precio);
    $('#total_s'+index).text(total);
    $(".show_input"+index).hide();
    $(".hide_span"+index).show();
    calcular_total();
}

function change_price(index){
    var cantidad = $('#cantidad'+index).val();
    var precio = $('#precio'+index).val();
    cantidad = cantidad!=''?cantidad:1;
    $('#total'+index).val(precio*cantidad);

}
function change_qty(index){
    var cantidad = $('#cantidad'+index).val();
    var precio = $('#precio'+index).val();
    var total = $('#total'+index).val();
    cantidad = cantidad!=''?cantidad:1;
    $('#total'+index).val(precio*cantidad);
}

function calcular_total(){
    var total = 0;
    var sub_total = 0;
    var igv = 0;
    $('input[name="total[]"]').each(function(index, el) {
        total = total+parseFloat($(el).val());
    });
    sub_total = total/1.18;
    igv = total - sub_total;
    $("#sub_total").val(sub_total.toFixed(2));
    $("#igv").val(igv.toFixed(2));
    $("#precio_total").val(total.toFixed(2));
}

/*Script de crongrama de pagos*/

function createNew() {
    $("#add-more").hide();
    var data = '<tr class="table-row" id="new_row_ajax">' +
    '<td contenteditable="false" id="txt_title" onBlur="addToHiddenField(this,\'id\')" onClick="editRow(this);"></td>' +
    '<td contenteditable="true" class="fecha_date" onBlur="addToHiddenField(this,\'fecha\')" onClick="editRow(this);">2019-07-03</td>' +
    '<td contenteditable="false" id="txt_title" onBlur="addToHiddenField(this,\'pagar\')" onClick="editRow(this);">00</td>' +
    '<td contenteditable="false" id="txt_title" onBlur="addToHiddenField(this,\'acuenta\')" onClick="editRow(this);">00</td>' +
    '<td contenteditable="false" id="txt_description" onBlur="addToHiddenField(this,\'saldo\')" onClick="editRow(this);">00</td>' +
    '<td contenteditable="true" id="txt_ciudad" onBlur="addToHiddenField(this,\'total\')" onClick="editRow(this);"></td>' +
    '<td><input type="hidden" id="fecha"/><input type="hidden" id="pagar"/><input type="hidden" id="acuenta"/><input type="hidden" id="saldo"/><input type="hidden" id="total"/><span id="confirmAdd"><a class="btn btn-success btn-xs" href="javascript:void(0)" title="Guardar" onClick="addToDatabase()">Guardar</a> / <a class="btn btn-warning btn-xs" href="javascript:void(0)" title="Cancelar" onclick="cancelAdd();">Cancelar</a></span></td>' +   
    '</tr>';
  $("#table-body").append(data);
}

$('.fecha_date').datepicker({
    format: 'yyyy-mm-dd',
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true
    }).on('changeDate', function(e) {
    $(this).text(moment(e.date).format('YYYY-MM-DD'));
});

function cancelAdd() {
    $("#add-more").show();
    $("#new_row_ajax").remove();
}
function editRow(editableObj) {
  $(editableObj).css("background","#FFF");
}

function saveToDatabase(editableObj,column,id) {
    //calcular(editableObj,column,id);
    var temp = $(editableObj);
    var obj_acuenta,obj_saldo,obj_total,obj_pagar,obj_fecha;
    var fecha_val,pagar_val,acuenta_val,saldo_val,total_val;
    if(column == 'pagar'){
        obj_pagar = temp
        obj_acuenta = temp.next();
        obj_saldo = temp.next().next();
        obj_total = temp.next().next().next();
        obj_fecha = temp.prev();
        //cargando valores
        pagar_val= obj_pagar.text();
        acuenta_val= obj_acuenta.text();
        saldo_val= obj_saldo.text();
        total_val= obj_total.text();
        fecha_val = obj_fecha.text();
        //Validar vacios
        pagar_val = (pagar_val!='')?parseInt(pagar_val):'00'
        acuenta_val = (acuenta_val!='')?parseInt(acuenta_val):'00';
        saldo_val = (saldo_val!='')?parseInt(saldo_val):'00';
        total_val = (total_val!='')?parseInt(total_val):'00';

        saldo_val = total_val-(pagar_val+acuenta_val);
        //saldo_val = total_val-pagar_val;
        acuenta_val = pagar_val+acuenta_val;
        //acuenta_val = pagar_val;
        if(saldo_val==0){
            obj_acuenta.text('00');
            acuenta_val = 0;
            saldo_val = 0;
            obj_saldo.text('00');
            obj_pagar.attr('contenteditable', 'false');
            obj_pagar.parent().addClass('bg-success')
        }else{
            obj_acuenta.text(acuenta_val);
            obj_saldo.text(saldo_val);
        }
        obj_pagar.text('00');
        pagar_val = 0;
    }else if(column == 'fecha'){

        obj_fecha = temp;
        obj_pagar = temp.next()
        obj_acuenta = temp.next().next();
        obj_saldo = temp.next().next().next();
        obj_total = temp.next().next().next().next();
        //cargando valores
        pagar_val= obj_pagar.text();
        acuenta_val= obj_acuenta.text();
        saldo_val= obj_saldo.text();
        total_val= obj_total.text();
        fecha_val = obj_fecha.text();
    }
  $(editableObj).css("background","#FFF url("+base_url+"assets/dist/img/cargando.gif) no-repeat right");
  $.ajax({
    url: base_url+'customers/update_payment',
    type: "POST",
    data:'fecha='+fecha_val+'&pagar='+pagar_val+'&acuenta='+acuenta_val+'&saldo='+saldo_val+'&total='+total_val+'&id='+id,
    success: function(data){
      $(editableObj).css("background","#FDFDFD");
    }
  });
}

function addToDatabase() {
  var customer_id = $("#customer_id").val();
  var proceso_id = $("#procesoID").val();
  var fecha = $("#fecha").val();
  var pagar = $("#pagar").val();
  var acuenta = $("#acuenta").val();
  var saldo = $("#saldo").val();
  var total = $("#total").val();
    $("#confirmAdd").html('<img src="'+base_url+'assets/dist/img/cargando.gif"/>');
    $.ajax({
        url: base_url+'customers/add_payment',
        type: "POST",
        data: 'fecha='+fecha+'&pagar='+pagar+'&acuenta='+acuenta+'&saldo='+saldo+'&total='+total+'&customer_id='+customer_id+'&proceso_id='+proceso_id,
        success: function(data){
          $("#new_row_ajax").remove();
          $("#add-more").show();          
          $("#table-body").append(data);
        }
    });
}

function addToHiddenField(addColumn,hiddenField) {
    var columnValue = $(addColumn).text();
    $("#"+hiddenField).val(columnValue);
}

function deleteRecord(id) {
    if(confirm("Esta seguro de eliminar el registro?")) {
        $.ajax({
            url: base_url+'customers/delete_payment/'+id,
            type: "POST",
            data:'id='+id,
            success: function(data){
              $("#table-row-"+id).remove();
            }
        });
    }
}

function printRecord(id) {
    $.ajax({
        url : base_url+'customers/print_documento_proceso/'+id,
        type: "POST",
        dataType: "html",
        success: function(data)
        {
            $('#ipdf_proceso').html(data);
            $('#modal_print_proceso').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al imprimir');
        }
    });
}


//Part of Files...

$(function() {
    custumerid=$("#customer_id").val();
    //datatables
    table_files = $('#table_files').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+'customers/ajax_list_files/'+custumerid,
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ -2 ], //2 last column (photo)
                "orderable": false, //set not orderable
            },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});

function add_files()
{
    save_method = 'add';
    $('#form_file')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_file').modal('show'); // show bootstrap modal
    $('.modal-title').text('Agregar archivo'); // Set Title to Bootstrap modal title

    $('#photo-preview').hide(); // hide photo preview modal

    $('#label-photo').text('Subir archivo'); // label photo upload
}

function edit_files(id)
{
    save_method = 'update';
    $('#form_file')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : base_url+'customers/ajax_edit_files/'+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="file_id"]').val(data.file_id);
            $('[name="date_in"]').datepicker('update',data.date_in);
            $('[name="resolucion"]').val(data.resolucion);
            $('[name="acto"]').val(data.acto);
            $('[name="folio"]').val(data.folio);
            $('[name="sumilla"]').val(data.sumilla);
            $('#modal_file').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar archivo'); // Set title to Bootstrap modal title

            $('#photo-preview').show(); // show photo preview modal

            if(data.file_name)
            {
            var extensiones = data.file_name.substring(data.file_name.lastIndexOf("."));
                if (extensiones != ".jpg" && extensiones != ".png") {
                    $('#label-photo').text('Cambiar archivo'); // label photo upload
                    $('#photo-preview div').text('(Archivo PDF)');
                }else{
                    $('#label-photo').text('Cambiar archivo'); // label photo upload
                    $('#photo-preview div').html('<img src="'+base_url+'upload/'+data.file_name+'" class="img-responsive">'); // show photo
                    $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="'+data.file_name+'"/> Borrar archivo al guarar'); // remove photo
                }
            }
            else
            {
                $('#label-photo').text('Subir archivo'); // label photo upload
                $('#photo-preview div').text('(No hay archivo)');
            }

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table_files()
{
    table_files.ajax.reload(null,false); //reload datatable ajax 
}

function save_files()
{
    $('#btnSaveFile').text('Guardando...'); //change button text
    $('#btnSaveFile').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = base_url+'customers/ajax_add_files';
    } else {
        url = base_url+'customers/ajax_update_files';
    }

    // ajax adding data to database

    var formData = new FormData($('#form_file')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_file').modal('hide');
                reload_table_files();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSaveFile').text('Guardar'); //change button text
            $('#btnSaveFile').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSaveFile').text('Guardar'); //change button text
            $('#btnSaveFile').attr('disabled',false); //set button enable 

        }
    });
}

function ver_files(id) {
    $('#modal_file_view').modal('show'); // show bootstrap modal
    $('.modal-title').text('Detalle Archivos');
    $.ajax({
        url : base_url+'customers/get_files/' + id,
        type: 'GET',
        dataType: 'JSON',
        success:function(data){
            if (data=="") {
                html='<tr><td colspan="14">No existen registros...</td></tr>';
            }else {
                 html='';
                $.each(data, function(item, val) {
                  html +='<tr><th>Fecha</th><td>'+val.date_in+'</td></tr>';
                  html +='<tr><th>Resolución Nro</th><td>'+val.resolucion+'</td></tr>';
                  html +='<tr><th>Acto</th><td>'+val.acto+'</td></tr>';
                  html +='<tr><th>Folio</th><td>'+val.folio+'</td></tr>';
                  html +='<tr><th>Sumilla</th><td>'+val.sumilla+'</td></tr>';
                });     
            }         
            $("#modal_file_view .modal-body table").html(html);
            }
        })
}

function delete_files(id)
{
    if(confirm('Estas seguro que quieres eliminar?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+'customers/ajax_delete_files/'+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_file').modal('hide');
                reload_table_files();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function add_contraria() {
    save_method = 'add';
    $('#add_contraria')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_add_contraria').modal('show'); // show bootstrap modal
    $('.modal-title').text('Parte Contraria'); // Set Title to Bootstrap modal title
}

    $("#new_contraria").click(function() {
        //preventDefault();
        $('.help-block').empty(); // clear error string
        $('.modal-title:last').html('Nuevo Paciente');
        $('#modal_new_contraria').modal('show');
        //alert('message?: DOMString');
    });

    /*Add new patient*/
    $('.modal:last').on('click', '#add-contraria',  function(e){
            $.post(base_url+'customers/ajax_add', {
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                email: $('#email').val(),
                gender: $('#gender').val(),
            },
            function(obj){
                var data = JSON.parse(obj);
                if(data.status){
                $('.modal:last').modal('hide');
                $('#form_new_contraria')[0].reset();
                //$('#calendar').fullCalendar("refetchEvents");
                $.notify({
                    message: 'Paciente se agregado correctamente' 
                },{
                    type: 'info'
                });
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }

            });
    });