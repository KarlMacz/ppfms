var currentBillingAddressPaginationPage = 1;
var currentShippingAddressPaginationPage = 1;
var tableBillingAddressLimit = 10;
var tableShippingAddressLimit = 10;

function loadBillingAddressTable(start, limit) {
    ajaxRequest('../backend/ajax/table_billing_address.php', 'POST', {
        start: start,
        limit: limit
    }, function(response) {
        $('#billing-address-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#billing-address-table tbody').append('<tr>\
                        <td>' + response.data[ctr].name + '</td>\
                        <td>' + response.data[ctr].address + '</td>\
                        <td class="text-center">' + response.data[ctr].actions + '</td>\
                    </tr>');
            }
        } else {
            $('#billing-address-table tbody').append('<tr>\
                    <td class="text-center" colspan="4">No results found.</td>\
                </tr>');
        }

        $('[data-toggle="tooltip"]').tooltip();

        $('.billing-address-pagination').html('<li' + (currentPaginationPage === 1 ? ' class="disabled"' : '') + '><a href="#"><span>&laquo;</span></a></li>');

        for(var i = 0; i < Math.ceil(response.data_total_count / limit); i++) {
            $('.billing-address-pagination').append('<li' + (currentPaginationPage === (i + 1) ? ' class="active"' : '') + '><a href="#" data-page="' + (i + 1) + '"><span>' + (i + 1) + '</span></a></li>');
        }

        $('.billing-address-pagination').append('<li' + (currentPaginationPage === Math.ceil(response.data_total_count / limit) ? ' class="disabled"' : '') + '><a href="#"><span>&raquo;</span></a></li>');
    });
}

function loadShippingAddressTable(start, limit) {
    ajaxRequest('../backend/ajax/table_shipping_address.php', 'POST', {
        start: start,
        limit: limit
    }, function(response) {
        $('#shipping-address-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#shipping-address-table tbody').append('<tr>\
                        <td>' + response.data[ctr].name + '</td>\
                        <td>' + response.data[ctr].address + '</td>\
                        <td class="text-center">' + response.data[ctr].actions + '</td>\
                    </tr>');
            }
        } else {
            $('#shipping-address-table tbody').append('<tr>\
                    <td class="text-center" colspan="4">No results found.</td>\
                </tr>');
        }

        $('[data-toggle="tooltip"]').tooltip();

        $('.shipping-address-pagination').html('<li' + (currentPaginationPage === 1 ? ' class="disabled"' : '') + '><a href="#"><span>&laquo;</span></a></li>');

        for(var i = 0; i < Math.ceil(response.data_total_count / limit); i++) {
            $('.shipping-address-pagination').append('<li' + (currentPaginationPage === (i + 1) ? ' class="active"' : '') + '><a href="#" data-page="' + (i + 1) + '"><span>' + (i + 1) + '</span></a></li>');
        }

        $('.shipping-address-pagination').append('<li' + (currentPaginationPage === Math.ceil(response.data_total_count / limit) ? ' class="disabled"' : '') + '><a href="#"><span>&raquo;</span></a></li>');
    });
}

$(document).ready(function() {
    loadBillingAddressTable((currentPaginationPage * tableBillingAddressLimit) - tableBillingAddressLimit, tableBillingAddressLimit);
    loadShippingAddressTable((currentPaginationPage * tableShippingAddressLimit) - tableShippingAddressLimit, tableShippingAddressLimit);

    $('body').on('click', '.billing-address-pagination a', function() {
        currentBillingAddressPaginationPage = parseInt($(this).attr('data-page'));

        loadBillingAddressTable((currentBillingAddressPaginationPage * tableBillingAddressLimit) - tableBillingAddressLimit, tableBillingAddressLimit);

        return false;
    });

    $('body').on('click', '.shipping-address-pagination a', function() {
        currentShippingAddressPaginationPage = parseInt($(this).attr('data-page'));

        loadShippingAddressTable((currentShippingAddressPaginationPage * tableShippingAddressLimit) - tableShippingAddressLimit, tableShippingAddressLimit);

        return false;
    });

    $('body').on('click', '.add-billing-address-button', function() {
        openModal('add-billing-address-modal');
    });

    $('body').on('click', '.add-shipping-address-button', function() {
        openModal('add-shipping-address-modal');
    });

    $('body').on('click', '.remove-billing-address-button', function() {
        openModal('remove-billing-address-modal', 'static');

        $('#remove-billing-address-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#remove-billing-address-modal .negative-button', function() {
        $('#remove-billing-address-modal').attr('data-id', '');

        closeModal('remove-billing-address-modal');
    });

    $('body').on('click', '#remove-billing-address-modal .positive-button', function() {
        closeModal('remove-billing-address-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/remove_billing_address.php', 'POST', {
            id: $('#remove-billing-address-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Remove Billing Address', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadBillingAddressTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });

    $('body').on('click', '.remove-shipping-address-button', function() {
        openModal('remove-shipping-address-modal', 'static');

        $('#remove-shipping-address-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#remove-shipping-address-modal .negative-button', function() {
        $('#remove-shipping-address-modal').attr('data-id', '');

        closeModal('remove-shipping-address-modal');
    });

    $('body').on('click', '#remove-shipping-address-modal .positive-button', function() {
        closeModal('remove-shipping-address-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/remove_shipping_address.php', 'POST', {
            id: $('#remove-shipping-address-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Remove Shipping Address', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadShippingAddressTable((currentPaginationPage * tableLimit) - tableLimit, tableLimit);
            }, 2000);
        });
    });
});
