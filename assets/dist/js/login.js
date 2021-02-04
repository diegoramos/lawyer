
    $('#auth').hide();
    $('#redirect').hide();
    $('#error').hide();
    $('#flash').delay(3000).fadeOut();

    $(document).bind("ajaxStart", function () {
        $('#sign').hide();
        $('#auth').show();
    });

    $(document).on('ajaxError', function () {
        $('#sign').show();
        $('#auth').hide();
        $('#error').delay(5000).fadeOut();
        $(document).trigger('reset');
    });

    $(document).on('success', function () {
        $('#sign').show();
        $('#auth').hide();
        $('#redirect').show();
    });

    $(document).ready(function () {
        $('#login').on('submit', function (e) {
            e.preventDefault();
            var url = base_url;
            var data = $(this).serialize();
            var redirect = $(this).attr('redirect');
            $.ajax({
                url: url+'login/check_login',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'error') {
                        $('#error').show().html('<i class="fa fa-warning"></i> ' +response.message);
                        $(document).trigger('ajaxError');
                    }
                    else {
                        $(document).trigger('success');

                        window.location = url + redirect;//'dashboard';
                    }
                }
            });
            
        });
    });