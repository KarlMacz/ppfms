var currentPaginationPage = 1;
var tableLimit = 10;

function openModal(id, backdrop) {
    if(backdrop == undefined || backdrop == null) {
        backdrop = true;
    }

    $('#' + id + '.modal').modal({
        backdrop: backdrop,
        keyboard: false,
        show: true
    });
}

function closeModal(id) {
    $('#' + id + '.modal').modal('hide');
}

function setModalContent(id, title, content, footer) {
    $('#' + id + '.modal .modal-title').text(title);
    $('#' + id + '.modal .modal-body').html(content);

    if(footer != undefined && footer != null) {
        $('#' + id + '.modal .modal-footer').html(footer);
    }
}

function ajaxRequest(url, method, data, successCallback, errorCallback) {
    if(errorCallback === undefined || errorCallback === null || typeof errorCallback !== 'function') {
        errorCallback = function(arg0, arg1, arg2) {
            var tab = window.open();

            tab.document.write(arg0.responseText);
        };
    }

    $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: 'json',
        success: successCallback,
        error: errorCallback
    });
}

function validateInputs(inputs, validations, path, doSomethingAfter) {
    if(path === undefined || path === null) {
        path = '';
    }

    ajaxRequest(path + 'backend/ajax/validate_inputs.php', 'POST', {
        inputs: inputs,
        validations: validations
    }, function(response) {
        console.log(response.message);

        if(response.status === 'Ok') {
            for(var i = 0; i < response.data.length; i++) {
                $('[name="' + response.data[i].field + '"]').closest('.form-group').removeClass('has-success');
                $('[name="' + response.data[i].field + '"]').closest('.form-group').removeClass('has-warning');
                $('[name="' + response.data[i].field + '"]').closest('.form-group').removeClass('has-error');

                $('[name="' + response.data[i].field + '"]').closest('.form-group').remove('.help-block');
                
                if(!response.data[i].validation_result) {
                    $('[name="' + response.data[i].field + '"]').closest('.form-group').addClass('has-error');
                    $('[name="' + response.data[i].field + '"]').closest('.form-group').append('<span class="help-block">' + response.data[i].message + '</span>');
                }
            }

            if(response.invalid_count === 0) {
                if(doSomethingAfter !== undefined && doSomethingAfter !== null && typeof doSomethingAfter === 'function') {
                    doSomethingAfter();
                }
            }
        }
    });
}

function resizer() {
    $('.card').each(function() {
        var cardImageDiv = $(this).find('.card-image');

        cardImageDiv.height((cardImageDiv.width() * 0.5625) + 'px');

        var cardImage = $(this).find('.card-image img');

        if(cardImage.height() <= cardImageDiv.height()) {
            cardImage.css({
                'height': '100%',
                'width': 'auto'
            });
        }
    });
}

$(document).ready(function() {
    resizer();

    $('[data-toggle="tooltip"]').tooltip();

    $(window).on('resize', function() {
        resizer();
    });

    $('.modal').on('shown.bs.modal', function() {
        $('[autofocus]').focus();
    });
});
