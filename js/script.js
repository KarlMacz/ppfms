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

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();

    $('.modal').on('shown.bs.modal', function() {
        $('[autofocus]').focus();
    });
});
