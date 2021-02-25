$(function(){

    var initialLocaleCode = 'es';
    var currentDate; // Mantiene pulsado el día al agregar un nuevo evento
    var currentEvent; // Mantiene el objeto de evento cuando edita un evento

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
    // Colopicker
    $('#color').colorpicker();

    // Fullcalendar
    $('#calendar').fullCalendar({
        header: {
            left: 'prev, next, today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        },
        defaultView: 'agendaWeek',
        // Get all events stored in database
        locale: initialLocaleCode,
        defaultDate: moment().format('YYYY-MM-DD'),
        longPressDelay: 1,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        draggable: true,
        selectHelper: true,
        editable: true, // Make the event resizable true 
        events: base_url+'calendar/getEventos',   
        select: function(start, end) {
                $('#start').val(moment(start).format('YYYY-MM-DD'));
                $('#start_time').val(moment(start).format('h:mm A'));
                $('#end_time').val(moment(end).format('h:mm A'));
                //$('#end').val(moment(end).format('YYYY-MM-DD hh:mm:ss'));
                 // Open modal to add event
            modal_cita({
                // Available buttons when adding
                buttons: {
                    add: {
                        id: 'add-event', // Buttons id
                        css: 'btn-success', // Buttons class
                        label: 'Agregar' // Buttons label
                    }
                },
                title: 'Nueva Cita' // Modal title
            });
        }, 
           
        eventDrop: function(event, delta, revertFunc,start,end) {
            
            start = event.start.format('YYYY-MM-DD HH:mm:ss');     
            var st_time = moment(event.start).format('h:mm A');
            var en_time = moment(event.end).format('h:mm A');
               $.post(base_url+'calendar/dragUpdateEvent',{
                id:event.id,
                start : start,
                start_time : st_time,
                end_time :en_time
            }, function(result){
                $.notify({
                    // options
                    message: 'Actualizado Correctamente' 
                },{
                    // settings
                    type: 'success'
                });
            });

          },
          eventResize: function(event,dayDelta,minuteDelta,revertFunc) {

            start = event.start.format('YYYY-MM-DD HH:mm:ss');
            var st_time = moment(event.start).format('h:mm A');
            var en_time = moment(event.end).format('h:mm A');

               $.post(base_url+'calendar/dragUpdateEvent',{
                id:event.id,
                start : start,
                start_time : st_time,
                end_time :en_time
            }, function(result){
                $.notify({
                    // options
                    message: 'Actualizado Correctamente'
                },{
                    // settings
                    type: 'success'
                });
            });
        },
          
        // Event Mouseover
        eventMouseover: function(calEvent, jsEvent, view){

            var tooltip = '<div class="event-tooltip">' + calEvent.description + '</div>';
            $(".content").append(tooltip);

            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.event-tooltip').fadeIn('500');
                $('.event-tooltip').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.event-tooltip').css('top', e.pageY + 10);
                $('.event-tooltip').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
            $(this).css('z-index', 8);
            $('.event-tooltip').remove();
        },
        // Handle Existing Event Click
        eventClick: function(calEvent, jsEvent, view) {
            // Set currentEvent variable according to the event clicked in the calendar
            currentEvent = calEvent;
            $('#start').val(calEvent ? moment(calEvent.start).format('YYYY-MM-DD') : '');
            $('#start_time').val(calEvent ? moment(calEvent.start).format('h:mm A') :'' );
            $('#end_time').val(calEvent ? moment(calEvent.end).format('h:mm A') : '');

            // Open modal to edit or delete event
            modal_cita({
                // Available buttons when editing
                buttons: {
                    delete: {
                        id: 'delete-event',
                        css: 'btn-danger',
                        label: 'Eliminar'
                    },
                    update: {
                        id: 'update-event',
                        css: 'btn-success',
                        label: 'Actualizar'
                    }
                },
                title: 'Editar Cita"' + calEvent.title + '"',
                event: calEvent
            });
        }

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


    // Prepares the modal window according to data passed
    function modal_cita(data) {
        // Set modal title
        $('.modal-title').html(data.title);
        // Clear buttons except Cancel
        $('.cita button:not(".btn-default")').remove();
        // Set input values
        $("#event_id").val(data.event ? data.event.id : '')
        $('#search_patient').val(data.event ? data.event.title : '');
        $('#description').val(data.event ? data.event.description : '');
        $('#lawyer_id').val(data.event ? data.event.lawyer_id : '');
        $('#customer_id').val(data.event ? data.event.customer_id : '');
        $('#person_id').val(data.event ? data.event.person_id : '');
        //moment(end).format('LT')
        if (data.event) {
            $('#divstatus').show();
            $('#status').val(data.event.status);
            $('#verPacienteH').show();
            
        }else{
            $('#divstatus').hide();
            $('#status').val('Pendiente');
            $('#verPacienteH').hide();
        }
        

        //$('#color').val(data.event ? data.event.color : '#3a87ad');
        // Create Butttons
        $.each(data.buttons, function(index, button){
            $('.cita').prepend('<button type="button" id="' + button.id  + '" class="btn ' + button.css + '">' + button.label + '</button>')
        })
        //Show Modal
        $('#modal_new_cita').modal('show');
    }

    // Handle Click on Add Button
    $('.modal').on('click', '#add-event',  function(e){
        if(validator(['search_patient', 'lawyer_id'])) {
            $.post(base_url+'calendar/addEvent', {
                customer_id: $('#customer_id').val(),
                description: $('#description').val(),
                start: $('#start').val(),
                start_time: $('#start_time').val(),
                end: $('#end').val(),
                end_time: $('#end_time').val(),
                lawyer_id: $('#lawyer_id').val(),

            }, function(result){
                $('.modal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                $.notify({
                    message: 'Cita Agregado Correctamente' 
                },{
                    type: 'info'
                });
            });
        }
    });

    /*Add new patient*/
    $('.modal:last').on('click', '#add-patient',  function(e){
            $.post(base_url+'customers/ajax_add', {
                hist_clinic: $('#hist_clinic').val(),
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                email: $('#email').val(),
                gender: $('#gender').val(),
            },
            function(obj){
                var data = JSON.parse(obj);
                if(data.status){
                $('.modal:last').modal('hide');
                $('#form_patient')[0].reset();
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

    // Handle click on Update Button
    $('.modal').on('click', '#update-event',  function(e){
        if(validator(['search_patient', 'lawyer_id'])) {
            $.post(base_url+'calendar/updateEvent', {
                event_id: currentEvent.id,
                customer_id: $('#customer_id').val(),
                description: $('#description').val(),
                //color: $('#color').val(),
                start: $('#start').val(),
                start_time: $('#start_time').val(),
                end: $('#end').val(),
                end_time: $('#end_time').val(),
                lawyer_id: $('#lawyer_id').val(),
                status: $('#status').val(),
            }, function(result){
                //$('.alert').addClass('alert-success').text('Event updated successfuly');
                $('.modal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                $.notify({
                    message: 'Cita Actualizado Correctamente' 
                },{
                    type: 'success'
                });
            });
        }
    });

    // Handle Click on Delete Button
    $('.modal').on('click', '#delete-event',  function(e){
        $.get(base_url+'calendar/deleteEvent?id=' + currentEvent.id, function(result){
            //$('.alert').addClass('alert-success').text('Event deleted successfully !');
            $('.modal').modal('hide');
            $('#calendar').fullCalendar("refetchEvents");
            $.notify({
                message: 'Cita Eliminado' 
            },{
                type: 'danger'
            });
        });
    });

    function hide_notify()
    {
        setTimeout(function() {
            $('.alert').removeClass('alert-success').text('');
        }, 2000);
    }

    // Dead Basic Validation For Inputs
    function validator(elements) {
        var errors = 0;
        $.each(elements, function(index, element){
            //alert(element);
            if($.trim($('#' + element).val()) == '') errors++;
        });
        if(errors) {
            $('.error').html('Selecione un cliente y un abogado');
            return false;
        }
        return true;
    }

    $("#search_patient").autocomplete({
        source: base_url+'calendar/get_customer',
        minLength: 1,
            select: function(event,ui){
                $("#customer_id").val(ui.item.customer_id);
            }
    });

    $("#new_patient").click(function() {
        //preventDefault();
        $('.help-block').empty(); // clear error string
        $('.modal-title:last').html('Nuevo Cliente');
        $('#modal_new_patient').modal('show');
        //alert('message?: DOMString');
    });

    $("#verPacienteH").click(function(event) {
        window.location=base_url+"customers/view/"+$("#person_id").val()+"/specific_event/"+$("#event_id").val();
    });

});


