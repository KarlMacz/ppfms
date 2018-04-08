function loadTable(start, limit) {
    ajaxRequest('../backend/ajax/table_all_pending_orders.php', 'POST', {
        start: start,
        limit: limit
    }, function(response) {
        $('#orders-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#orders-table tbody').append('<tr>\
                        <td>' + response.data[ctr].tracking_number + '</td>\
                        <td>' + response.data[ctr].products + '</td>\
                        <td>' + response.data[ctr].datetime_ordered + '</td>\
                        <td class="text-center">' + response.data[ctr].actions + '</td>\
                    </tr>');
            }
        } else {
            $('#orders-table tbody').append('<tr>\
                    <td class="text-center" colspan="4">No results found.</td>\
                </tr>');
        }

        $('[data-toggle="tooltip"]').tooltip();

        $('.pagination').html('<li' + (currentPaginationPage === 1 ? ' class="disabled"' : '') + '><a href="#"><span>&laquo;</span></a></li>');

        for(var i = 0; i < Math.ceil(response.data_total_count / limit); i++) {
            $('.pagination').append('<li' + (currentPaginationPage === (i + 1) ? ' class="active"' : '') + '><a href="#" data-page="' + (i + 1) + '"><span>' + (i + 1) + '</span></a></li>');
        }

        $('.pagination').append('<li' + (currentPaginationPage === Math.ceil(response.data_total_count / limit) ? ' class="disabled"' : '') + '><a href="#"><span>&raquo;</span></a></li>');
    });
}

$(document).ready(function() {
    loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);

    $('body').on('change', '.filter-table', function() {
        tableLimit = parseInt($(this).find('option:selected').val());

        loadTable((1 * tableLimit) - tableLimit, tableLimit);

        return false;
    });

    $('body').on('click', '.pagination a', function() {
        currentPaginationPage = parseInt($(this).attr('data-page'));

        loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);

        return false;
    });

    $('body').on('click', '.dispatch-button', function() {
        openModal('dispatch-order-modal', 'static');

        $('#dispatch-order-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#dispatch-order-modal .negative-button', function() {
        $('#dispatch-order-modal').attr('data-id', '');

        closeModal('dispatch-order-modal');
    });

    $('body').on('click', '#dispatch-order-modal .positive-button', function() {
        closeModal('dispatch-order-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/dispatch_order.php', 'POST', {
            id: $('#dispatch-order-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Order Registry', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });

    $('body').on('click', '.cancel-button', function() {
        openModal('cancel-order-modal', 'static');

        $('#cancel-order-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#cancel-order-modal .negative-button', function() {
        $('#cancel-order-modal').attr('data-id', '');

        closeModal('cancel-order-modal');
    });

    $('body').on('click', '#cancel-order-modal .positive-button', function() {
        closeModal('cancel-order-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/cancel_order.php', 'POST', {
            id: $('#cancel-order-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Cancel Order', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });
});
