function loadTable(start, limit) {
    ajaxRequest('../backend/ajax/table_product_inventories.php', 'POST', {
        start: start,
        limit: limit
    }, function(response) {
        $('#products-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#products-table tbody').append('<tr>\
                        <td class="text-center">' + response.data[ctr].code + '</td>\
                        <td>' + response.data[ctr].name + '</td>\
                        <td>' + response.data[ctr].description + '</td>\
                        <td class="text-right">' + response.data[ctr].stocks + '</td>\
                        <td class="text-center">' + response.data[ctr].actions + '</td>\
                    </tr>');
            }
        } else {
            $('#products-table tbody').append('<tr>\
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

    $('#print-qr-modal').on('hidden.bs.modal', function() {
        $('#qr-frame').attr('src', '');
    });

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

    $('body').on('click', '.view-button', function() {
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/modal_view_inventories.php', 'POST', {
            id: $(this).attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            
            if(response.status === 'Ok') {
                setModalContent('view-modal', 'View Stocks', response.output.body);
                openModal('view-modal');

                $('[data-toggle="tooltip"]').tooltip();
            } else {
                setModalContent('status-modal', 'View Stocks', response.message);
                openModal('status-modal', 'static');

                setTimeout(function() {
                    closeModal('status-modal');
                    loadBuyersTable((currentBuyersPaginationPage * tableBuyersLimit) - tableBuyersLimit, tableBuyersLimit);
                }, 2000);
            }
        });
    });

    $('body').on('click', '.fetch-button', function() {
        closeModal('view-modal');
        openModal('fetch-modal', 'static');

        $('#fetch-form input[name="id"]').val($(this).attr('data-id'));
        $('#fetch-form input[name="quantity"]').val('1');
    });

    $('body').on('click', '#fetch-modal .negative-button', function() {
        $('#fetch-form input[name="id"]').val('');

        closeModal('fetch-modal');
    });

    $('body').on('click', '#fetch-modal .positive-button', function() {
        var empty = $('#fetch-form').find('input[required]').filter(function() {
            return this.value === '';
        });

        if(empty.length === 0) {
            closeModal('fetch-modal');
            openModal('loader-modal', 'static');

            ajaxRequest('../backend/ajax/fetch_box.php', 'POST', $('#fetch-form').serialize(), function(response) {
                closeModal('loader-modal');
                setModalContent('status-modal', 'Fetch Box', response.message);
                openModal('status-modal', 'static');

                setTimeout(function() {
                    closeModal('status-modal');
                    loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
                }, 2000);
            });
        }
    });

    $('body').on('click', '.excess-button', function() {
        closeModal('view-modal');
        openModal('excess-modal', 'static');

        $('#excess-form input[name="id"]').val($(this).attr('data-id'));
        $('#excess-form input[name="quantity"]').attr('max', $(this).attr('data-in-stock'));
        $('#excess-form input[name="quantity"]').val('1');
    });

    $('body').on('click', '#excess-modal .negative-button', function() {
        $('#excess-form input[name="id"]').val('');
        $('#excess-form input[name="quantity"]').attr('max', '');

        closeModal('excess-modal');
    });

    $('body').on('click', '#excess-modal .positive-button', function() {
        var empty = $('#excess-form').find('input[required]').filter(function() {
            return this.value === '';
        });

        if(empty.length === 0) {
            closeModal('excess-modal');
            openModal('loader-modal', 'static');

            ajaxRequest('../backend/ajax/excess_box.php', 'POST', $('#excess-form').serialize(), function(response) {
                closeModal('loader-modal');
                setModalContent('status-modal', 'Fetch Box', response.message);
                openModal('status-modal', 'static');

                setTimeout(function() {
                    closeModal('status-modal');
                    loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
                }, 2000);
            });
        }
    });

    $('body').on('click', '.print-qr-button', function() {
        openModal('print-qr-modal');
        $('#qr-frame').attr('src', '../backend/pdf/generate_product_qr_code.php?id=' + $(this).attr('data-id'));
    });
});
