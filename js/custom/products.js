$(document).ready(function() {
    $('body').on('click', '.add-to-cart-button', function() {
        openModal('add-to-cart-modal', 'static');

        $('#add-to-cart-form #name-input').val($(this).attr('data-name'));
        $('#add-to-cart-form input[name="id"]').val($(this).attr('data-id'));
        $('#add-to-cart-form input[name="quantity"]').val(1);
        $('#add-to-cart-form input[name="quantity"]').attr('max', $(this).attr('data-available'));
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
                }, 2000);
            });

            return false;
        }
    });

    $('body').on('click', '.add-to-wishlist-button', function() {
        openModal('loader-modal', 'static');

        ajaxRequest('../backend/ajax/add_to_wishlist.php', 'POST', {
            id: $(this).attr('data-id')
        }, function(response) {
            closeModal('loader-modal');
            setModalContent('status-modal', 'Add to Wishlist', response.message);
            openModal('status-modal', 'static');

            setTimeout(function() {
                closeModal('status-modal');
            }, 2000);
        });
    });
});
