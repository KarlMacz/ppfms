function loadTable(start, limit) {
    ajaxRequest('../backend/ajax/table_products.php', 'POST', {
        start: start,
        limit: limit
    }, function(response) {
        $('#products-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#products-table tbody').append('<tr>\
                        <td>' + response.data[ctr].name + '</td>\
                        <td>' + response.data[ctr].category + '</td>\
                        <td>' + response.data[ctr].type + '</td>\
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

        ajaxRequest('../backend/ajax/modal_view_product.php', 'POST', {
            id: $(this).attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            
            if(response.status === 'Ok') {
                setModalContent('view-product-modal', 'View Product Information', response.output.body, response.output.footer);
                openModal('view-product-modal');
            } else {
                setModalContent('status-modal', 'View Product Information', response.message);
                openModal('status-modal', 'static');

                setTimeout(function() {
                    closeModal('status-modal');
                    loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
                }, 2000);
            }
        });
    });

    $('body').on('click', '.add-to-cart-button', function() {
        closeModal('view-product-modal');
        openModal('add-to-cart-modal', 'static');

        $('#add-to-cart-form #name-input').val($(this).attr('data-name'));
        $('#add-to-cart-form input[name="id"]').val($(this).attr('data-id'));
        $('#add-to-cart-form input[name="quantity"]').val(1);
        $('#add-to-cart-form input[name="quantity"]').attr('max', $(this).attr('data-available'));
    });

    $('body').on('click', '.add-to-wishlist-button', function() {
        closeModal('view-product-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/add_to_wishlist.php', 'POST', {
            id: $(this).attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Add to Wishlist', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });

    $('body').on('click', '#add-to-cart-modal .negative-button', function() {
        $('#add-to-cart-form #name-input').val('');
        $('#add-to-cart-form input[name="id"]').val('');
        $('#add-to-cart-form input[name="quantity"]').val('');
        $('#add-to-cart-form input[name="quantity"]').attr('max', '');

        closeModal('add-to-cart-modal');
    });

    $('body').on('click', '#add-to-cart-modal .positive-button', function() {
        var empty = $('#add-to-cart-form').find('input[required]').filter(function() {
            return this.value === '';
        });

        if(empty.length === 0) {
            closeModal('add-to-cart-modal');
            openModal('loader-modal', 'static');

            ajaxRequest('../backend/ajax/add_to_cart.php', 'POST', $('#add-to-cart-form').serialize(), function(response) {
                closeModal('loader-modal');
                setModalContent('status-modal', 'Add to Cart', response.message);
                openModal('status-modal', 'static');

                setTimeout(function() {
                    closeModal('status-modal');
                    loadTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
                }, 2000);
            });

            return false;
        }
    });
});
