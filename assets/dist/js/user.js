
var save_method; //for save method string
var table;

$(function() {

    //datatables
    table = $('#user').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "scrollX": true,

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+'user/ajax_list',
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

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,
        language: 'es'
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

    $(".module_checkboxes").change(function(event) {
        var ides=$(this).attr('id');
        var cheke;
        if ($(this).is(":checked")) { cheke=true; }else{ cheke=false; }
        $('[data-module-checkbox-id="'+ides+'"]').each(function(index, el) {
            $(el).prop('checked', cheke);
        });
    });
    $(".module_action_checkboxes").change(function(event) {
        var id=$(this).attr('data-module-checkbox-id');
         if ($(this).is(":checked")) {
            $("#"+id).prop('checked', true);
         }
    });
});

function add_user()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Nuevo Usuario'); // Set Title to Bootstrap modal title  
}

function view_user(user_id)
{
    $('#modal_form_view').modal('show'); // show bootstrap modal
    $('.modal-title').text('Detalle Usuario');
    $.ajax({
        url : base_url+'user/get_user/' + user_id,
        type: 'GET',
        dataType: 'JSON',
        success:function(data){
            if (data=="") {
                html='<tr><td colspan="14">No existen registros...</td></tr>';
            }else {
                 html='';
                $.each(data, function(item, val) {
                  html +='<tr><th>NOMBRES</th><td>'+val.first_name+'</td></tr>';
                  html +='<tr><th>APELLIDOS</th><td>'+val.last_name+'</td></tr>';
                  html +='<tr><th>DNI</th><td>'+val.dni+'</td></tr>';
                  html +='<tr><th>FECHA DE NACIMIENTO</th><td>'+val.birth_date+'</td></tr>';
                  if (val.gender == 1) {
                    html +='<tr><th>GENERO</th><td>Masculino</td></tr>';
                  } else {
                    html +='<tr><th>GENERO</th><td>Femenino</td></tr>';
                  }
                  html +='<tr><th>EMAIL</th><td>'+val.email+'</td></tr>';
                  html +='<tr><th>TELEFONO</th><td>'+val.phone+'</td></tr>';
                  html +='<tr><th>DIRECCION</th><td>'+val.address+'</td></tr>';
                  html +='<tr><th>FECHA DE REGISTRO</th><td>'+val.date_reg+'</td></tr>';
                  html +='<tr><th>USUARIO</th><td>'+val.username+'</td></tr>';
                  if (val.status == 1) {
                    html +='<tr><th>ESTADO</th><td</th><td>Activo</td></tr>';
                  } else {
                    html +='<tr><th>ESTADO</th><td</th><td>Deshabilitado</td></tr>';
                  }
                });     
            }         
            $("#modal_form_view .modal-body table").html(html);
            }
        })
}

function edit_user(person_id)
{
    save_method = 'update';
    $(".module_action_checkboxes").removeAttr('checked');
    $(".module_checkboxes").removeAttr('checked');
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : base_url+'user/ajax_edit/' + person_id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $.each(data.permissions, function(index, val) {
                $('[id="'+val+'"]').attr('checked', 'checked');
            });
            $('[name="person_id"]').val(data.person_id);
            $('[name="first_name"]').val(data.first_name);
            $('[name="last_name"]').val(data.last_name);
            $('[name="dni"]').val(data.dni);
            $('[name="birth_date"]').datepicker('update',data.birth_date);
            $('[name="gender"]').val(data.gender);
            $('[name="email"]').val(data.email);
            $('[name="phone"]').val(data.phone);
            $('[name="address"]').val(data.address);
            $('[name="rol"]').val(data.rol);
            $('[name="username"]').val(data.username);
            //$('[name="password"]').val(data.password);
            $('[name="status"]').val(data.status);
 
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Usuario'); // Set title to Bootstrap modal title
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
    $('#btnSave').text('guardando...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = base_url+'user/ajax_add';
    } else {
        url = base_url+'user/ajax_update';
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

function delete_user(user_id)
{
    if(confirm('Estas seguro que quieres eliminar?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+'user/ajax_delete/'+ user_id,
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
                alert('Error al eliminar');
            }
        });
    }
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