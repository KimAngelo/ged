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
                document.querySelector(".btn-theme").setAttribute("disabled", "disabled");
                load('open');
            },
            success: function (response) {

                if (response.message) {
                    $('html').animate({scrollTop: 0}, 'slow');
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
                document.querySelector(".btn-theme").removeAttribute("disabled");
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

function clearForm(myFormElement) {

    var elements = document.querySelector(myFormElement).elements;

    for (i = 0; i < elements.length; i++) {

        field_type = elements[i].type.toLowerCase();

        switch (field_type) {

            case "text":
            case "password":
            case "textarea":
            case "number":

                elements[i].value = "";
                break;

            case "radio":
            case "checkbox":
                if (elements[i].checked) {
                    elements[i].checked = false;
                }
                break;

            case "select-one":
            case "select-multi":
                elements[i].selectedIndex = -1;
                break;

            default:
                break;
        }
    }
}

function checkbox_sign() {
    let documents = [];
    let document_signed = document.querySelectorAll('input[name=document_signed]');
    document_signed.forEach(document_list => {
        document_list.addEventListener('change', () => {
            if (document_list.checked) {
                documents.push(document_list.value);
                document.querySelector('.button-to-sign').classList.remove('d-none');
                document.querySelector('.label-to-sign').classList.add('d-none');
                return false;
            }
            let indice = documents.indexOf(document_list.value);
            while (indice >= 0) {
                documents.splice(indice, 1);
                indice = documents.indexOf(document_list.value);
            }
            if (documents.length === 0) {
                document.querySelector('.button-to-sign').classList.add('d-none');
                document.querySelector('.label-to-sign').classList.remove('d-none');
                return false;
            }
        });
    });
    let button_sign = document.querySelector('.button-to-sign');
    button_sign.addEventListener('click', () => {
        const url = button_sign.getAttribute('data-url');
        $.post(url, {action: 'sign', documents: documents}, function (response, status) {
            if (response.message_warning) {
                toastr.warning(response.message_warning);
                return false;
            }
            if (response.refresh) {
                $('html').animate({scrollTop: 0}, 'slow');
                setTimeout(function () {
                    window.location.reload();
                }, 500);
            }
        }, 'json');
    });
}