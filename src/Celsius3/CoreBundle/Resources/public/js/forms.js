$.fn.modal.Constructor.prototype.enforceFocus = function () {
};

$('.summernote').summernote({
    height: 200
});

function getOldValues() {
    var values = [];
    var form_name = 'order_materialData';
    values[form_name + '_title'] = $('#' + form_name + '_title').val();
    values[form_name + '_authors'] = $('#' + form_name + '_authors').val();
    values[form_name + '_year'] = $('#' + form_name + '_year').val();
    values[form_name + '_startPage'] = $('#' + form_name + '_startPage').val();
    values[form_name + '_endPage'] = $('#' + form_name + '_endPage').val();
    return values;
}

function noLibrarian(id) {
    $('#order_originalRequest_owner_autocomplete').attr('disabled', 'disabled');
    $('#order_originalRequest_owner_autocomplete').val('');
    $('#order_originalRequest_owner').val(id);
    $('#order_originalRequest_librarian').val('');
}

function librarian(id) {
    $('#order_originalRequest_owner_autocomplete').removeAttr('disabled');
    $('#order_originalRequest_owner_autocomplete').val('');
    $('#order_originalRequest_owner').val('');
    $('#order_originalRequest_librarian').val(id);
}

function getCatalogId(inputName) {
    return inputName.substr(7);
}

function registerSearch() {
    var input = $(this);
    var catalogId = getCatalogId(input.attr('name'));
    $.ajax({
        url: Routing.generate('admin_catalog_search_mark'),
        dataType: 'json',
        data: 'order_id=' + document_id + '&instance_id=' +
                instance_id + '&catalog_id=' + catalogId + '&result=' +
                input.val()
    }).done(function (data) {
        input.parent().siblings('.catalog-result').text(data.date);
    });
}

$('.mark-catalog').click(registerSearch);

/**
 * Turns default date picker into a jQuery UI widget
 */
if (_locale !== "en") {
    $.datepicker.setDefaults($.datepicker.regional[_locale]);
} else {
    $.datepicker.setDefaults($.datepicker.regional[""]);
}
$('.date').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    yearRange: 'c-100:c',
    maxDate: new Date()
});

/**
 * Autocomplete fields related event
 */
$(document).on("focus", "input.autocomplete:not(.ui-autocomplete-input)", function () {
    $(this).autocomplete({
        source: function (request, response) {
            var field = $(this);
            $.ajax({
                url: ajax_path,
                dataType: "json",
                data: {
                    term: request.term,
                    target: field[0].element.attr('target')
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            var id = $(this).attr('id').replace('_autocomplete', '', 'gi');
            $('#' + id).val(ui.item.id);
        }
    });
});

$(document).on("focus", "input.autocomplete_multi:not(.ui-autocomplete-input)", function () {
    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }

    $(this).autocomplete({
        source: function (request, response) {
            var field = $(this);
            $.ajax({
                url: ajax_path,
                dataType: "json",
                data: {
                    term: extractLast(request.term),
                    target: field[0].element.attr('target')
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        search: function () {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        focus: function () {
            // prevent value inserted on focus
            return false;
        },
        select: function (event, ui) {
            var terms = split(this.value);
            // remove the current input
            terms.pop();
            // add the selected item
            terms.push(ui.item.id);
            // add placeholder to get the comma-and-space at the end
            terms.push("");
            this.value = terms.join(", ");

            return false;
        }
    });
});

var loadMaterialData = function () {
    var oldValues = getOldValues();
    var urlChange;

    if ((user_role === 'ROLE_SUPER_ADMIN') || (user_role === 'ROLE_ADMIN')) {
        urlChange = Routing.generate('admin_order_change');
    } else {
        urlChange = Routing.generate('user_order_change');
    }

    $.ajax({
        type: 'POST',
        url: urlChange,
        data: {
            material: $('#order_materialDataType').val()
        },
        success: function (data) {
            $('#material_data').html(data);
            for (var key in oldValues) {
                $('#' + key).val(oldValues[key]);
            }
        }
    });
};
/**
 * Material type change related event
 */
$('#order_materialDataType').change(loadMaterialData);

$('#order_instance').change(function () {
    $('#order_owner_autocomplete').val('');
    $('#order_owner').val('');
});

$('form[name="order"]').on('submit', function (e) {
    if (_.isEmpty($('#order_originalRequest_owner').val())) {
        e.preventDefault();
        $('#order_originalRequest_owner_autocomplete').parent().addClass('has-error');
        $('#order_originalRequest_owner_autocomplete').focus();
        $('html, body').animate({
            scrollTop: $('#order_originalRequest_owner_autocomplete').offset().top,
            marginTop: '100px'
        }, 1000);
    }
});

if (user_exists) {
    // Controles para los widgets del formulario de carga de pedidos de un
    // bibliotecario
    if ($('#order_originalRequest_target').val() === 'me') {
        noLibrarian(user_id);
    }

    $('#order_originalRequest_target').change(function () {
        if ($('#order_originalRequest_target').val() === 'me') {
            noLibrarian(user_id);
        } else {
            librarian(user_id);
        }
    });
}

$('.union_link').click(function () {
    $('#batch-action').val($(this).attr('class').split('_')[0]);
    if ($('input[type=checkbox]:checked').length >= 2) {
        $('.batch_form').submit();
    }
});


$('.enable_link').click(function () {
    $('#batch-action').val($(this).attr('class').split('_')[0]);
    if ($('input[type=checkbox]:checked').length >= 1) {
        $('.batch_form').submit();
    }
});


/*
 * News date edition
 */
$('#news_date').datetimepicker({
    showSecond: true
});
var dateWidgets = $('#news_date');
dateWidgets.hide();
$('.news-date').parent().append(
        '<div class="date-text form-control">' + dateWidgets.val() +
        '</div><div><a class="show-date-widget btn btn-default">' +
        news_text_change + '</a></div>');
$(document).on('click', '.show-date-widget', function () {
    $('.news-date').parent().children('div').hide();
    dateWidgets.show();
});

/*
 * Como no se utiliza accordion-group hay que ocultar "a mano" los elementos
 * mostrados cuando se presiona otro link en el flujo de Order
 */
$('.state-list a.pointer').click(function (e) {
    $('#state-info .in').collapse('hide');
});

/*
 * Form submission
 */
$('.delete-message').click(function () {
    $(this).parent('form').submit();
});

$('.submit-download-form').click(function () {
    var form = $(this).parent();
    form.submit();
    form.remove();
});

$(document).on('click', '.submit-form', function (e) {
    e.preventDefault();
    $(this).parent().submit();
});

$(document).on('click', '.doSubmit', function () {
    $($(this).attr('value')).submit();
});

/*
 * Form batch selection
 */
$('.check-all').click(function () {
    $('.batch-checkbox').prop('checked', 'checked');
});

$('.uncheck-all').click(function () {
    $('.batch-checkbox').prop('checked', '');
});

$(document).ready(function () {
    $('#form_test_connection').click(function () {
        $('.test-message').remove();
        $('#form_test_connection').parent().append('<img class="test-message" alt="" src="/bundles/celsius3core/images/select2-spinner.gif" align="center" />');
        $.ajax({
            type: 'POST',
            url: Routing.generate('admin_instance_rest_test_smtp'),
            data: {
                'smtp_host': $('#form_smtp_host').val(),
                'smtp_port': $('#form_smtp_port').val(),
                'smtp_protocol': $('#form_smtp_protocol').val(),
                'smtp_username': $('#form_smtp_username').val(),
                'smtp_password': $('#form_smtp_password').val()
            },
            success: function (data) {
                $('.test-message').remove();

                var icon = 'remove';
                if (data.test) {
                    icon = 'ok';
                }

                $('#form_test_connection').parent().append('<span class="test-message"><span class="glyphicon glyphicon-' + icon + '"></span>' + data.message + '</span>');

            }
        });
    });
});

$(document).ready(function () {
    var tempChapter = '';
    $(document).on('change', '#order_materialData_withIndex', function () {
        if ($('#order_materialData_withIndex:checked').val()) {
            tempChapter = $('#order_materialData_chapter').val();
            $('#order_materialData_chapter')
                    .val('')
                    .attr('disabled', true);
        } else {
            $('#order_materialData_chapter')
                    .val(tempChapter)
                    .attr('disabled', false);
        }
    });
});

$(document).ready(function () {
    $('html').on('click', '.orderDeleteButton', function () {
        $('#orderDeleteAlert #orderDeleteAlertConfirmButton').attr('data-order-id', $(this).attr('data-order-id'));
        $('#orderDeleteAlert').modal('show');
    });

    $('#orderDeleteAlert #orderDeleteAlertConfirmButton').click(function(){
        $.ajax({
            type: 'POST',
            url: Routing.generate('superadmin_order_delete', { 'id': $(this).attr('data-order-id') }),
            success: function (data) {
                if(data.success){
                    $('#order_' + data.id + ' .main_info .title_and_author > .titulo').first()
                        .append('<span class="text-danger">[DELETED]</span>');

                    $('#order-actions-' + data.id + ' .orderDelUndelButton span.fa')
                        .removeClass('fa-remove').addClass('fa-check');
                    $('#order-actions-' + data.id + ' .orderDelUndelButton')
                        .addClass('btn-success')
                        .addClass('orderUndeleteButton')
                        .removeClass('btn-danger')
                        .removeClass('orderDeleteButton');
                }
            }
        });

        $('#orderDeleteAlert').modal('hide');
    });



    $('html').on('click','.orderUndeleteButton', function () {
        $('#orderUndeleteAlert #orderUndeleteAlertConfirmButton').attr('data-order-id', $(this).attr('data-order-id'));
        $('#orderUndeleteAlert').modal('show');
    });

    $('#orderUndeleteAlert #orderUndeleteAlertConfirmButton').click(function(){
        $.ajax({
            type: 'POST',
            url: Routing.generate('superadmin_order_undelete', { 'id': $(this).attr('data-order-id') }),
            success: function (data) {
                if(data.success){
                    $('#order_' + data.id + ' .main_info .title_and_author > .titulo').first()
                        .text(function(){ return $(this).text().replace('[DELETED]',''); });

                        $('#order-actions-' + data.id + ' .orderDelUndelButton span.fa')
                            .removeClass('fa-check').addClass('fa-remove');
                        $('#order-actions-' + data.id + ' .orderDelUndelButton')
                            .addClass('btn-danger')
                            .addClass('orderDeleteButton')
                            .removeClass('btn-success')
                            .removeClass('orderUndeleteButton');
                }
            }
        });

        $('#orderUndeleteAlert').modal('hide');
    });
});
