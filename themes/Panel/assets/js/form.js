toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-left",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "100",
    "hideDuration": "1000",
    "timeOut": "3000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$(function () {
    $('.form').on('submit', function (e) {
        e.preventDefault();
        var form = $(this)[0];
        var data = new FormData(form);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.btn-theme').addClass('spinner spinner-white spinner-left');
            },
            success: function (response) {
                $('.btn-theme').removeClass('spinner spinner-white spinner-left');
                //$('html').animate({scrollTop: 0}, 'slow');
                if (response.message) {
                    $('html').animate({scrollTop: 0}, 'slow');
                    $('.ajax_response').html(response.message);
                    return false;
                }
                if (response.message_success) {
                    toastr.success(response.message_success);
                    return false;
                }
                if (response.message_warning) {
                    toastr.warning(response.message_warning);
                    return false;
                }
                if (response.message_error) {
                    toastr.error(response.message_error);
                    return false;
                }
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
                if (response.refresh) {
                    $('html').animate({scrollTop: 0}, 'slow');
                    setTimeout(function () {
                        window.location.reload();
                    }, 500);

                }
            }
        });
    });
});