toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
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
                /*$('.btn-theme').addClass('m-loader m-loader--light m-loader--left');*/
                load('open');
            },
            success: function (response) {

                //$('html').animate({scrollTop: 0}, 'slow');
                if (response.message) {
                    $(".ajax_response").html(response.message);
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
            },
            error: function (xhr, status, errorThrown) {
                if (xhr.status !== 200) {
                    toastr.error('Ocorreu um erro interno, entre em contato com o suporte!');
                }
            },
            complete: function () {
                /*$('.btn-theme').removeClass('m-loader m-loader--light m-loader--left');*/
                load('close');
            }
        });
    });
});

function load(action) {
    var load_div = $(".ajax_load");
    if (action === "open") {
        load_div.fadeIn().css("display", "flex");
    } else {
        load_div.fadeOut();
    }
}

//Date Picker Form
var KTBootstrapDatepicker = function () {

    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }

    // Private functions
    var demos = function () {
        // range picker
        $('#kt_datepicker_5').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            templates: arrows,
            format: 'dd/mm/yyyy',
            language: "pt-BR"
        });
    };

    $.fn.datepicker.dates['pt-BR'] = {
        days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
        daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
        months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        today: "Hoje",
        monthsTitle: "Meses",
        clear: "Limpar",
        format: "dd/mm/yyyy"
    };

    return {
        // public functions
        init: function () {
            demos();
        }
    };
}();