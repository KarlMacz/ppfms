function loadTable(start, limit) {
    ajaxRequest('../backend/ajax/table_batches_tracking.php', 'POST', {
        start: start,
        limit: limit
    }, function(response) {
        $('#batches-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#batches-table tbody').append('<tr>\
                        <td class="text-center">' + response.data[ctr].number + '</td>\
                        <td>' + response.data[ctr].product + '</td>\
                        <td>' + response.data[ctr].quantity + '</td>\
                        <td>' + response.data[ctr].in + '</td>\
                        <td>' + response.data[ctr].out + '</td>\
                    </tr>');
            }
        } else {
            $('#batches-table tbody').append('<tr>\
                    <td class="text-center" colspan="5">No results found.</td>\
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

    $('body').on('click', '.add-production-button', function() {
        openModal('add-production-modal', 'static');
    });

    $('body').on('click', '#add-production-modal .negative-button', function() {
        closeModal('add-production-modal');
    });

    $('body').on('click', '#add-production-modal .positive-button', function() {
        closeModal('add-production-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/add_batch.php', 'POST', $('#add-production-form').serialize(), function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Add Batch', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable((1 * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });

    $('body').on('click', '.finished-button', function() {
        openModal('finished-modal', 'static');

        $('#finished-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#finished-modal .negative-button', function() {
        $('#finished-modal').attr('data-id', '');

        closeModal('finished-modal');
    });

    $('body').on('click', '#finished-modal .positive-button', function() {
        closeModal('finished-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/mark_as_finished_batch.php', 'POST', {
            id: $('#finished-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Mark Batch as Finished', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable((1 * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });

    $('body').on('click', '.extra-button', function() {
        openModal('loader-modal', 'static');

        var thisElement = $(this);

        ajaxRequest('../backend/ajax/modal_batch_information_list.php', 'POST', {
            id: $(this).attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            
            if(response.status === 'Ok') {
                setModalContent('extra-modal', 'Extra Product Information', response.output.body);
            } else {
                setModalContent('extra-modal', 'Extra Product Information', response.message);
            }

            openModal('extra-modal', 'static');

            $('[data-toggle="tooltip"]').tooltip();
            $('#extra-form input[name="id"]').val(thisElement.attr('data-id'));
        });
    });

    $('body').on('click', '#extra-modal .negative-button', function() {
        $('#extra-form input[name="id"]').val('');

        closeModal('extra-modal');
    });

    $('body').on('click', '#extra-modal .positive-button', function() {
        closeModal('extra-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/add_extra_batch.php', 'POST', $('#extra-form').serialize(), function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Extra Product Information', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable((1 * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });

    $('body').on('click', '.remove-extra-button', function() {
        closeModal('extra-modal');
        openModal('remove-extra-modal', 'static');

        $('#remove-extra-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#remove-extra-modal .negative-button', function() {
        $('#remove-extra-modal').attr('data-id', '');

        closeModal('remove-extra-modal');
    });

    $('body').on('click', '#remove-extra-modal .positive-button', function() {
        closeModal('remove-extra-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/remove_batch_information.php', 'POST', {
            id: $('#remove-extra-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Remove Extra Product Information', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable((1 * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });
});
