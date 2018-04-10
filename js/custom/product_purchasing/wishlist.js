function loadTable(start, limit) {
    ajaxRequest('../backend/ajax/table_wishlists.php', 'POST', {
        start: start,
        limit: limit
    }, function(response) {
        $('#wishlist-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#wishlist-table tbody').append('<tr>\
                        <td>' + response.data[ctr].product_code + '</td>\
                        <td>' + response.data[ctr].name + '</td>\
                        <td>' + response.data[ctr].category + '</td>\
                        <td>' + response.data[ctr].type + '</td>\
                        <td class="text-center">' + response.data[ctr].actions + '</td>\
                    </tr>');
            }
        } else {
            $('#wishlist-table tbody').append('<tr>\
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

    $('body').on('click', '.remove-button', function() {
        openModal('remove-modal', 'static');

        $('#remove-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#remove-modal .negative-button', function() {
        $('#remove-modal').attr('data-id', '');

        closeModal('remove-modal');
    });

    $('body').on('click', '#remove-modal .positive-button', function() {
        closeModal('remove-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/remove_wishlist.php', 'POST', {
            id: $('#remove-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Remove Product from Wishlist', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });
});
