var shippingFeeWithin = 1;
var shippingFeeOutside = 1;

function loadShippingFees() {
    ajaxRequest('../backend/ajax/table_cart.php', 'POST', {}, function(response) {
    });
}

function loadTable() {
    ajaxRequest('../backend/ajax/table_cart.php', 'POST', {}, function(response) {
        $('#carts-table tbody').html('');

        if(response.status === 'Ok' && response.data.length > 0) {
            for(var ctr = 0; ctr < response.data.length; ctr++) {
                $('#carts-table tbody').append('<tr>\
                        <td>' + response.data[ctr].name + '</td>\
                        <td>' + response.data[ctr].quantity + '</td>\
                        <td class="text-right">Php ' + response.data[ctr].total + '</td>\
                        <td class="text-center">' + response.data[ctr].actions + '</td>\
                    </tr>');
            }

            $('#carts-table tfoot').html('<tr>\
                <th class="text-right" colspan="2">Total Amount:</th>\
                <th class="text-right">Php <span id="total-amount">' + response.data2 + '</span></th>\
                <th class="text-center">\
                    <button type="button" class="remove-all-button btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Remove All from Cart"><span class="fas fa-trash fa-fw"></span></button>\
                </th>\
            </tr>');
        } else {
            $('#carts-table tbody').append('<tr>\
                    <td class="text-center" colspan="4">No results found.</td>\
                </tr>');

            $('#carts-table tfoot').html('<tr>\
                <th class="text-right" colspan="2">Total Amount:</th>\
                <th class="text-right">Php 0</th>\
                <th></th>\
            </tr>');
        }

        $('[data-toggle="tooltip"]').tooltip();
    });
}

$(document).ready(function() {
    loadTable();

    $('body').on('click', '.edit-button', function() {
        openModal('edit-quantity-modal', 'static');

        $('#edit-quantity-form #name-input').val($(this).attr('data-name'));
        $('#edit-quantity-form input[name="cart_id"]').val($(this).attr('data-id'));
        $('#edit-quantity-form input[name="quantity"]').val($(this).attr('data-quantity'));
        $('#edit-quantity-form input[name="quantity"]').attr('max', $(this).attr('data-available'));
    });

    $('body').on('click', '#edit-quantity-modal .negative-button', function() {
        $('#edit-quantity-form #name-input').val('');
        $('#edit-quantity-form input[name="cart_id"]').val('');
        $('#edit-quantity-form input[name="quantity"]').val(1);
        $('#edit-quantity-form input[name="quantity"]').attr('max', '');

        closeModal('edit-quantity-modal');
    });

    $('body').on('click', '#edit-quantity-modal .positive-button', function() {
        var empty = $('#edit-quantity-form').find('input[required]').filter(function() {
            return this.value === '';
        });

        if(empty.length === 0) {
            closeModal('edit-quantity-modal');
            openModal('loader-modal', 'static');

            ajaxRequest('../backend/ajax/edit_cart_quantity.php', 'POST', $('#edit-quantity-form').serialize(), function(response) {
                closeModal('loader-modal');
                setModalContent('status-modal', 'Edit Quantity', response.message);
                openModal('status-modal', 'static');

                setTimeout(function() {
                    closeModal('status-modal');
                    loadTable();
                }, 2000);
            });
        }
    });

    $('body').on('click', '.remove-button', function() {
        openModal('remove-from-cart-modal', 'static');

        $('#remove-from-cart-modal').attr('data-id', $(this).attr('data-id'));
    });

    $('body').on('click', '#remove-from-cart-modal .negative-button', function() {
        $('#remove-from-cart-modal').attr('data-id', '');

        closeModal('remove-from-cart-modal');
    });

    $('body').on('click', '#remove-from-cart-modal .positive-button', function() {
        closeModal('remove-from-cart-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/remove_from_cart.php', 'POST', {
            id: $('#remove-from-cart-modal').attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Remove from Cart', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable();
            }, 2000);
        });
    });

    $('body').on('click', '.remove-all-button', function() {
        openModal('remove-all-from-cart-modal', 'static');
    });

    $('body').on('click', '#remove-all-from-cart-modal .negative-button', function() {
        closeModal('remove-all-from-cart-modal');
    });

    $('body').on('click', '#remove-all-from-cart-modal .positive-button', function() {
        closeModal('remove-all-from-cart-modal');
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/remove_all_from_cart.php', 'POST', {}, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Remove All from Cart', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
                loadTable();
            }, 2000);
        });
    });

    $('body').on('click', '.checkout-button', function() {
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/settings.php', 'POST', {}, function(response) {
            closeModal('loader-modal');
            openModal('checkout-modal', 'static');

            if(response.status === 'Ok') {
                shippingFeeWithin = response.data.shipping_fee_within_metro_manila;
                shippingFeeOutside = response.data.shipping_fee_outside_metro_manila;
            }
        });
    });

    $('body').on('click', '#checkout-modal .negative-button', function() {
        closeModal('checkout-modal');
    });

    $('body').on('click', '#checkout-modal .positive-button', function() {
        var empty = $('#checkout-form').find('select[required]').filter(function() {
            return this.value === '';
        });

        if(empty.length === 0) {
            closeModal('checkout-modal');
            openModal('loader-modal', 'static');

            ajaxRequest('../backend/ajax/checkout.php', 'POST', $('#checkout-form').serialize(), function(response) {
                closeModal('loader-modal');
                setModalContent('status-modal', 'Checkout', response.message);
                openModal('status-modal', 'static');

                setTimeout(function() {
                    closeModal('status-modal');
                    loadTable();
                }, 2000);
            });
        }
    });

    $('body').on('change', '#shipping-address-input', function() {
        var isMetroManila = false;

        $('#checkout-modal .yes-button').attr('disabled', true);
        $('#shipping-fee-block').html('<div class="text-center"><span class="fas fa-spinner fa-pulse fa-3x fa-fw"></span></div>');

        ajaxRequest('https://maps.googleapis.com/maps/api/geocode/json', 'GET', {
            address: $(this).find('option:selected').attr('data-address'),
            key: 'AIzaSyBm8OIKZ7OtOykRclmFgfF9wRboRgnFGN8'
        }, function(response) {
            $('#checkout-modal .yes-button').attr('disabled', false);

            if(response.status === 'OK') {
                for(var i = 0; i < response.results[0].address_components.length; i++) {
                    if(response.results[0].address_components[i].types[0] === 'administrative_area_level_1') {
                        if(response.results[0].address_components[i].short_name === 'NCR') {
                            isMetroManila = true;
                        }
                    }
                }
            }

            if(isMetroManila) {
                $('#checkout-form input[name="shipping_fee"]').val(shippingFeeWithin);
                $('#shipping-fee-block').html('<table class="table table-bordered">\
                        <tbody>\
                            <tr>\
                                <td class="is-size-4 text-right">Shipping Fee:</td>\
                                <td class="is-size-4">Php ' + parseFloat(shippingFeeWithin).toFixed(2) + '</td>\
                            </tr>\
                            <tr>\
                                <td class="is-size-4 text-right">Total:</td>\
                                <td class="is-size-4">Php ' + parseFloat(parseFloat(shippingFeeWithin) + parseFloat($('#total-amount').text())).toFixed(2) + '</td>\
                            </tr>\
                        </tbody>\
                    </table>');
            } else {
                $('#checkout-form input[name="shipping_fee"]').val(shippingFeeOutside);
                $('#shipping-fee-block').html('<table class="table table-bordered">\
                        <tbody>\
                            <tr>\
                                <td class="is-size-4 text-right">Shipping Fee:</td>\
                                <td class="is-size-4">Php ' + parseFloat(shippingFeeOutside).toFixed(2) + '</td>\
                            </tr>\
                            <tr>\
                                <td class="is-size-4 text-right">Total:</td>\
                                <td class="is-size-4">Php ' + parseFloat(parseFloat(shippingFeeOutside) + parseFloat($('#total-amount').text())).toFixed(2) + '</td>\
                            </tr>\
                        </tbody>\
                    </table>');
            }
        });
    });
});
